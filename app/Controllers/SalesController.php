<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SaleModel;

class SalesController extends BaseController
{
    private ProductModel $products;
    private CustomerModel $customers;
    private SaleModel $sales;

    public function __construct()
    {
        $this->products  = new ProductModel();
        $this->customers = new CustomerModel();
        $this->sales     = new SaleModel();
    }

    public function index()
    {
        return $this->history();
    }

    public function create()
    {
        return view('sales/create', [
            'products'  => $this->products->where('stock_quantity >', 0)->orderBy('name')->findAll(),
            'customers' => $this->customers->orderBy('full_name')->findAll(),
        ]);
    }

    public function store()
    {
        $productIds = $this->request->getPost('product_id');
        $quantities = $this->request->getPost('quantity');
        $customerId = (int) $this->request->getPost('customer_id');

        if (! is_array($productIds) || ! is_array($quantities) || $customerId < 1) {
            return redirect()->back()->withInput()->with('error', 'Select a customer and at least one product.');
        }

        $items = $this->normaliseItems($productIds, $quantities);
        if ($items === []) {
            return redirect()->back()->withInput()->with('error', 'Add at least one valid product and quantity.');
        }

        $receiptNumber = 'POS-' . date('YmdHis') . '-' . strtoupper(bin2hex(random_bytes(3)));
        $db = db_connect();
        $db->transBegin();

        foreach ($items as $productId => $quantity) {
            $product = $db->query(
                'SELECT * FROM products WHERE id = ? FOR UPDATE',
                [$productId]
            )->getRowArray();

            if (! $product) {
                return $this->rollbackWithError($db, 'Product not found.');
            }

            if ($quantity > (int) $product['stock_quantity']) {
                return $this->rollbackWithError(
                    $db,
                    'Insufficient stock for ' . $product['name'] . '. Available quantity: ' . $product['stock_quantity']
                );
            }

            $db->table('sales')->insert([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'sold_by' => session()->get('user_id'),
                'quantity' => $quantity,
                'total_price' => (float) $product['price'] * $quantity,
                'receipt_number' => $receiptNumber,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $db->table('products')->where('id', $productId)->update([
                'stock_quantity' => (int) $product['stock_quantity'] - $quantity,
            ]);
        }

        if (! $db->transStatus()) {
            return $this->rollbackWithError($db, 'The sale could not be recorded.');
        }

        $db->transCommit();

        return redirect()->to(site_url('sales/receipt/' . $receiptNumber))
            ->with('success', 'Sale recorded successfully.');
    }

    public function edit(int $id)
    {
        $sale = $this->sales->find($id);
        if (! $sale) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $receiptSales = $this->sales
            ->where('receipt_number', $sale['receipt_number'])
            ->orderBy('id')
            ->findAll();

        return view('sales/edit', [
            'sale' => $sale,
            'receiptSales' => $receiptSales,
            'products' => $this->products->orderBy('name')->findAll(),
            'customers' => $this->customers->orderBy('full_name')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $productIds = $this->request->getPost('product_id');
        $quantities = $this->request->getPost('quantity');
        $customerId = (int) $this->request->getPost('customer_id');

        if (! is_array($productIds) || ! is_array($quantities) || $customerId < 1) {
            return redirect()->back()->withInput()->with('error', 'Select a customer and at least one item.');
        }

        $newItems = $this->normaliseItems($productIds, $quantities);
        if ($newItems === []) {
            return redirect()->back()->withInput()->with('error', 'Add at least one valid product and quantity.');
        }

        $db = db_connect();
        $db->transBegin();

        $sale = $db->query('SELECT * FROM sales WHERE id = ? FOR UPDATE', [$id])->getRowArray();
        if (! $sale) {
            return $this->rollbackWithError($db, 'Sale record not found.');
        }

        $oldSales = $db->query(
            'SELECT * FROM sales WHERE receipt_number = ? FOR UPDATE',
            [$sale['receipt_number']]
        )->getResultArray();

        $productIdsToLock = array_keys($newItems);
        foreach ($oldSales as $oldSale) {
            $productIdsToLock[] = (int) $oldSale['product_id'];
        }
        sort($productIdsToLock);
        $lockedProducts = [];
        foreach (array_unique($productIdsToLock) as $productId) {
            $lockedProducts[$productId] = $db->query(
                'SELECT * FROM products WHERE id = ? FOR UPDATE',
                [$productId]
            )->getRowArray();
        }

        $availableStock = [];
        foreach ($lockedProducts as $productId => $product) {
            if (! $product) {
                return $this->rollbackWithError($db, 'Product not found.');
            }
            $availableStock[$productId] = (int) $product['stock_quantity'];
        }

        foreach ($oldSales as $oldSale) {
            $oldProductId = (int) $oldSale['product_id'];
            $availableStock[$oldProductId] += (int) $oldSale['quantity'];
        }

        foreach ($newItems as $productId => $quantity) {
            if ($quantity > $availableStock[$productId]) {
                return $this->rollbackWithError($db, 'Insufficient stock for the updated sale.');
            }
            $availableStock[$productId] -= $quantity;
        }

        foreach ($availableStock as $productId => $stockQuantity) {
            $db->table('products')->where('id', $productId)->update([
                'stock_quantity' => $stockQuantity,
            ]);
        }

        $db->table('sales')->where('receipt_number', $sale['receipt_number'])->delete();

        foreach ($newItems as $productId => $quantity) {
            $db->table('sales')->insert([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'sold_by' => $sale['sold_by'],
                'quantity' => $quantity,
                'total_price' => (float) $lockedProducts[$productId]['price'] * $quantity,
                'receipt_number' => $sale['receipt_number'],
                'created_at' => $sale['created_at'],
            ]);
        }

        if (! $db->transStatus()) {
            return $this->rollbackWithError($db, 'The sale could not be updated.');
        }

        $db->transCommit();

        return redirect()->to(site_url('sales/receipt/' . $sale['receipt_number']))
            ->with('success', 'Sale updated successfully.');
    }

    public function receipt(string $receiptNumber)
    {
        $sales = $this->sales->history()
            ->where('sales.receipt_number', $receiptNumber)
            ->findAll();

        if ($sales === []) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('sales/receipt', [
            'sales' => $sales,
            'receiptNumber' => $receiptNumber,
        ]);
    }

    public function history()
    {
        return view('sales/history', [
            'sales' => $this->sales->history()->findAll(),
        ]);
    }

    private function normaliseItems(array $productIds, array $quantities): array
    {
        $items = [];
        foreach ($productIds as $index => $productId) {
            $productId = (int) $productId;
            $quantity = (int) ($quantities[$index] ?? 0);
            if ($productId > 0 && $quantity > 0) {
                $items[$productId] = ($items[$productId] ?? 0) + $quantity;
            }
        }
        return $items;
    }

    private function rollbackWithError($db, string $message)
    {
        $db->transRollback();
        return redirect()->back()->withInput()->with('error', $message);
    }
}
