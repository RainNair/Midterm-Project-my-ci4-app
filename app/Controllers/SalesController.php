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
            'products'  => $this->products->where('stock_quantity >', 0)->findAll(),
            'customers' => $this->customers->orderBy('full_name')->findAll(),
        ]);
    }

    public function store()
    {
        $rules = [
            'product_id' => 'required|integer',
            'customer_id' => 'permit_empty|integer',
            'quantity' => 'required|integer|greater_than[0]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = (int) $this->request->getPost('product_id');
        $quantity  = (int) $this->request->getPost('quantity');
        $customerId = $this->request->getPost('customer_id') ?: null;

        $this->sales->transBegin();

        // Lock the product row while checking and updating stock.
        $product = $this->sales->query(
            'SELECT * FROM products WHERE id = ? FOR UPDATE',
            [$productId]
        )->getRowArray();

        if (! $product) {
            $this->sales->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Product not found.');
        }

        if ($quantity > (int) $product['stock_quantity']) {
            $this->sales->transRollback();

            return redirect()->back()
                ->withInput()
                ->with(
                    'error',
                    'Insufficient stock. Available quantity: ' . $product['stock_quantity']
                );
        }

        $totalPrice = (float) $product['price'] * $quantity;

        $this->sales->insert([
            'product_id'  => $productId,
            'customer_id' => $customerId,
            'sold_by'     => session()->get('user_id'),
            'quantity'    => $quantity,
            'total_price' => $totalPrice,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        $this->products->update($productId, [
            'stock_quantity' => (int) $product['stock_quantity'] - $quantity,
        ]);

        if (! $this->sales->transStatus()) {
            $this->sales->transRollback();

            return redirect()->back()
                ->withInput()
                ->with('error', 'The sale could not be recorded.');
        }

        $this->sales->transCommit();

        return redirect()->to('/sales/history')
            ->with('success', 'Sale recorded successfully.');
    }

    public function history()
    {
        return view('sales/history', [
            'sales' => $this->sales->history()->findAll(),
        ]);
    }
}