<?php

namespace App\Http\Controllers;
use \App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct() {
        return Product::all();
    }

    public function createProduct(Request $request) {
        return Product::create([
            "name"=>$request->name,
            "description"=>$request->description,
            "price"=>$request->price,
        ]);
    }
};
