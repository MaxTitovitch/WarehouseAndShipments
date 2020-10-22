<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if($request->id) {
            $products = [];
            foreach (Product::where('user_id', $request->id)->get() as $product) {
                $products[] = $product->name;
            }
            return response()->json($products, 200);
        } else if($request->auth) {
            $products = [];
            foreach (Product::where('user_id', Auth::id())->get() as $product) {
                $products[] = $product->name;
            }
            return response()->json($products, 200);
        } else if($request->all_id) {
            return response()->json(Product::where('user_id', $request->all_id)->get(), 200);
        } else if($request->all_auth) {
            return response()->json(Product::where('user_id', Auth::id())->get(), 200);
        } else {
            return response()->json(Product::all(), 200);
        }
    }

    public function store(ProductRequest $request)
    {
        $product = new Product();
        $this->copyModelFromRequest($product, $request);
        $product->user_id = Auth::id();
        $product->save();
        Session::flash('success', 'New Product created!');
        return response()->json($product, 200);
    }

    public function show($id)
    {
        return response()->json(Product::with('user')->find($id), 200);
    }


    public function update(ProductRequest $request, $id)
    {
        $product = Product::find($id);
        $this->copyModelFromRequest($product, $request);
        Session::flash('success', 'Product updated!');
        return response()->json($product, 200);
    }

    private function copyModelFromRequest(Product &$product, ProductRequest $request) {
        $product->name = $request->name;
        $product->brand = $request->brand;
        $product->upc = $request->upc;
        $product->sku = $request->sku;
//        $product->received = $request->received;
//        $product->available = $request->available;
//        $product->in_transit = $request->in_transit;
        $product->save();
    }
}
