<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function exportProducts(){
        if(Auth::user()->role == 'Admin') {
            $entities = Product::orderBy('id', 'desc')->get();
        } else {
            $entities = Product::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        }
        header('Content-type: application/txt');
        header('Content-Disposition: attachment; filename="products.csv"');
        foreach ($entities as $entity){
            echo  iconv("UTF-8", "Windows-1251","{$entity->name}; {$entity->brand};{$entity->upc};{$entity->sku};\r\n");
        }
    }

    public function exportOrders(){
        if(Auth::user()->role == 'Admin') {
            $entities = Order::orderBy('id', 'desc')->get();
        } else {
            $entities = Order::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        }
        header('Content-type: application/txt;');
        header('Content-Disposition: attachment; filename="orders.csv"');
        foreach ($entities as $entity){
            $products = '';
            foreach ($entity->products as $product){
                $products .= "{$product->name}:{$product->pivot->quantity}:";
                if($product->pivot->price) {
                    $products .= "{$product->pivot->price}:";
                }
                if($product->pivot->description) {
                    $products .= "{$product->pivot->description}:";
                }
                $products = substr($products,0,-1) . '|';
            }
            if ($products) {
                $products = substr($products,0,-1);
            }
            echo iconv("UTF-8", "Windows-1251",
                "{$entity->customer}; {$entity->comment};{$entity->company_name};{$entity->shipping_company};"
                ."{$entity->tracking_number}; {$entity->packing_selection};{$entity->address};{$entity->city};"
                ."{$entity->zip_postal_code}; {$entity->state_region};{$entity->country};{$entity->phone};$products;\r\n"
            );
        }
    }

    public function exportShipments(){
        if(Auth::user()->role == 'Admin') {
            $entities = Shipment::orderBy('id', 'desc')->get();
        } else {
            $entities = Shipment::where('user_id', Auth::id())->orderBy('id', 'desc')->get();
        }
        header('Content-type: application/txt;');
        header('Content-Disposition: attachment; filename="shipments.csv"');
        foreach ($entities as $entity){
            $products = '';
            foreach ($entity->products as $product){
                $products .= "{$product->name}:{$product->pivot->quantity}|";
            }
            if ($products) {
                $products = substr($products,0,-1);
            }
            echo iconv("UTF-8", "Windows-1251","{$entity->tracking_number}; {$entity->shipping_company};{$entity->comment};{$entity->quantity};$products;\r\n");
        }
    }

}
