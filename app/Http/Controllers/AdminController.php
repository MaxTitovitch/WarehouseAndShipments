<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        return 'index';
    }

    public function orders() {
        return 'orders';
    }

    public function products() {
        return 'products';
    }

    public function supplies() {
        return 'supplies';
    }

    public function billing() {
        return 'billing';
    }
}
