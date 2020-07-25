<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return 'index';
    }

    public function inboundShipments() {
        return view('inbound-shipments');
    }


    public function users() {
        return 'billing';
    }

    public function products() {
        return 'products';
    }

    public function orders() {
        return 'orders';
    }
}
