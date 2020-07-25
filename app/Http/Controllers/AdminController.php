<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\User;
use App\Product;
use App\Order;

class AdminController extends Controller
{
    public function index() {
        $statistic = [];
        return view('index')->with(['statistic' => $statistic]);
    }

    public function inboundShipments() {
        $shipments = Shipment::all();
        return view('inbound-shipments')->with(['shipments' => $shipments]);
    }

    public function users() {
        $users = User::all();
        return view('users')->with(['users' => $users]);
    }

    public function products() {
        $products = Product::all();
        return view('products')->with(['products' => $products]);
    }

    public function orders() {
        $orders = Order::all();
        return view('orders')->with(['orders' => $orders]);
    }
}
