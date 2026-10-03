<?php

namespace App\Controllers;

use App\Models\ProductModel;

class Products extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        $data = [
            'title'    => 'Product List',
            'products' => $productModel
                ->orderBy('name', 'ASC')
                ->findAll(),
        ];

        return view('products/index', $data);
    }
}