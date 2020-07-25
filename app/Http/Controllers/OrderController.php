<?php

namespace App\Http\Controllers;

use App\Order;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $order = new Order();
        $order->user_id = Auth::id() || 1;
        $this->copyModelFromRequest($order, $request);
        $this->syncProducts($order, $request->order_products);
        return response()->json($order, 200);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'products'])->find($id);
        if(Auth::id() === $order->user_id) {
            return response()->json($order, 200);
        } else {
            return response()->json(null, 403);
        }
    }

    public function update(OrderRequest $request, $id)
    {
        $order = Order::find($id);
        if(Auth::id() === $order->user_id) {
            $this->copyModelFromRequest($order, $request);
            $this->syncProducts($order, $request->order_products);
            return response()->json($order, 200);
        } else {
            return response()->json(null, 403);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if(Auth::id() === $order->user_id) {
            $order->products()->sync([]);
            return response()->json(Order::destroy($id), 200);
        } else {
            return response()->json(null, 403);
        }
    }

    public function copy($id)
    {
        $order = new Order();
        if(Auth::id() === $order->user_id) {
            $cloneableOrder = Order::find($id);
            $order->user_id = Auth::id() || 1;
            $this->copyModelFromRequest($order, $cloneableOrder, false);
            $this->syncCloneProducts($order, $cloneableOrder->products);
            return response()->json($order, 200);
        } else {
            return response()->json(null, 403);
        }
    }


    private function copyModelFromRequest(Order $order, $request, $isAppendUnrequired = true) {
        $order->customer = $request->customer;
        $order->comment = $request->comment;
        $order->tracking_number = $request->tracking_number;
        $order->packing_selection = $request->packing_selection;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->zip_postal_code = $request->zip_postal_code;
        $order->state_region = $request->state_region;
        $order->country = $request->country;
        $order->phone = $request->phone;
        $order->shipping_company = $request->shipping_company;
        if(isset($request->status) && $isAppendUnrequired) {
            $order->status = $request->status;
        }
        if(isset($request->shipping_cost) && $isAppendUnrequired) {
            $order->shipping_cost = $request->shipping_cost;
        }
        if(isset($request->shipped) && $isAppendUnrequired) {
            $order->shipped = $request->shipped;
        }
    }

    private function syncProducts(Order $order, $order_products) {
        $arrayProducts = [];
        foreach ($order_products as $order_product) {
            $order_product =  \GuzzleHttp\json_decode($order_product);
            $arrayProducts[$order_product->product_id] = [
                'quantity' => $order_product->quantity,
                'price' => $order_product->price ?? null,
                'description' => $order_product->description ?? null
            ];
        }
        $order->save();
        $order->products()->sync($arrayProducts);
    }

    private function syncCloneProducts(Order $order, $products) {

        $arrayProducts = [];
        foreach ($products as $product) {
            $arrayProducts[$product->id] = [
                'quantity' => $product->pivot->quantity,
                'price' => $product->pivot->price ?? null,
                'description' => $product->pivot->description ?? null
            ];
        }
        $order->save();
        $order->products()->sync($arrayProducts);
    }
}
