<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductsController extends BaseController
{
    protected ProductModel $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function index()
    {
        return view('products/index', [
            'products' => $this->products
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function createForm()
    {
        return view('products/create');
    }

    public function create()
    {
        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $this->uploadImage('image', 'products');

        $this->products->insert([
            'name' => trim((string) $this->request->getPost('name')),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to(site_url('products'))
            ->with('success', 'Product added successfully.');
    }

    public function edit(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('products/edit', [
            'product' => $product,
        ]);
    }

    public function update(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'name' => 'required|max_length[100]',
            'price' => 'required|decimal',
            'stock_quantity' => 'required|integer|greater_than_equal_to[0]',
            'image' => 'permit_empty|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]|max_size[image,2048]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $imageName = $product['image'];

        $file = $this->request->getFile('image');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $newImageName = $this->uploadImage('image', 'products');
            if ($newImageName) {
                $imageName = $newImageName;
                $this->deleteImageFile($product['image'], 'products');
            }
        } elseif ($this->request->getPost('delete_image') === '1') {
            $imageName = null;
            $this->deleteImageFile($product['image'], 'products');
        }

        $this->products->update($id, [
            'name' => trim((string) $this->request->getPost('name')),
            'price' => $this->request->getPost('price'),
            'stock_quantity' => $this->request->getPost('stock_quantity'),
            'image' => $imageName,
        ]);

        return redirect()
            ->to(site_url('products'))
            ->with('success', 'Product updated successfully.');
    }

    public function delete(int $id)
    {
        $product = $this->products->find($id);

        if (! $product) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->products->delete($id);
        $this->deleteImageFile($product['image'], 'products');

        return redirect()
            ->to(site_url('products'))
            ->with('success', 'Product deleted successfully.');
    }

    private function uploadImage(string $field, string $folder): ?string
    {
        $file = $this->request->getFile($field);

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        $directory = FCPATH . 'uploads/' . $folder;

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $newName = $file->getRandomName();
        $file->move($directory, $newName);

        return $newName;
    }

    private function deleteImageFile(?string $fileName, string $folder): void
    {
        if ($fileName) {
            $path = FCPATH . 'uploads/' . $folder . '/' . basename($fileName);
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}
