<?php

namespace App\Http\Controllers;

use App\Order;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\BalanceHistory;
use Illuminate\Support\Facades\Session;
use App\Product;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $user = User::find($request->user_id ?? Auth::id());
//        if($balanceHistory = $this->createBalanceHistory($this->calculateBalance($request), $user)) {
            $order = new Order();
            $order->user_id = $user->id;
            $this->copyModelFromRequest($order, $request);
            $this->syncProducts($order, $request->order_products);
//            $balanceHistory->save();
//            $user->balance += ($balanceHistory->type === 'Debit' ? -1 : 1) * $balanceHistory->transaction_cost;
//            $user->save();
//            $order->user->sendOrderNotification($order);
            $this->updateProducts();
            Session::flash('success', 'New Order created!');
            return response()->json($order, 200);
//        } else {
//            Session::flash('error', 'Insufficient funds on the balance sheet!');
//            return response()->json(['error' => 'Недостаточно средств'], 200);
//            $order->delete();
//        }
    }

    public function show($id)
    {
        $order = Order::with(['user', 'products'])->find($id);
        if(Auth::id() === $order->user_id || Auth::user()->role == 'Admin') {
            return response()->json($order, 200);
        } else {
            return response()->json(null, 200);
        }
    }

    public function update(OrderRequest $request, $id)
    {
        $order = Order::find($id);
        $user = User::find($request->user_id ?? Auth::id());
//        $user = User::find($request->user_id ?? Auth::id());
        if($user->id === $order->user_id || $user->role === 'Admin') {
            if($balanceHistory = $this->updateBalanceHistory($request, $user, $order)) {
                if (!$order->shipped && $request->shipped) {
                    $order->user->sendOrderNotification($order);
                }
                $this->copyModelFromRequest($order, $request);
                $order->save();
                $this->syncProducts($order, $request->order_products);
                if($balanceHistory) {
                    $balanceHistory->save();
                    $user->balance += ($balanceHistory->type === 'Debit' ? -1 : 1) * $balanceHistory->transaction_cost;
                    $user->save();
                }
                $this->updateProducts();
                Session::flash('success', 'Order updated!');
                return response()->json($order, 200);
            } else {
                Session::flash('error', 'Insufficient funds on the balance sheet!');
                return response()->json(['error' => 'Недостаточно средств'], 200);
            }
        } else {
            Session::flash('error', 'It isn\'t your order!');
            return response()->json(null, 200);
        }
    }

    public function copy($id)
    {
        $order = new Order();
        $cloneableOrder = Order::find($id);
        $user = User::find($cloneableOrder->user_id);
        $authUser = User::find(Auth::id());
        if($authUser->id === $cloneableOrder->user_id || $authUser->role === 'Admin') {
            if($balanceHistory = $this->createBalanceHistory($this->calculateCopyBalance($cloneableOrder, false), $user, $order)) {
                $order->user_id = Auth::id();
                $this->copyModelFromRequest($order, $cloneableOrder, false);
                $this->syncCloneProducts($order, $cloneableOrder->products);

                if($balanceHistory) {
                    $balanceHistory->save();
                    $user->balance += ($balanceHistory->type === 'Debit' ? -1 : 1) * $balanceHistory->transaction_cost;
                    $user->save();
                }
                $this->updateProducts();
                Session::flash('success', 'Order copied!');
                return response()->json($order, 200);
            } else {
                Session::flash('error', 'Insufficient funds on the balance sheet!');
                return response()->json(['error' => 'Недостаточно средств'], 200);
            }
        } else {
            Session::flash('error', 'It isn\'t your order!');
            return response()->json(null, 200);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if(Auth::id() === $order->user_id) {
            $order->products()->sync([]);
            $this->updateProducts();
//            $order->user->sendOrderNotification($order);
            Session::flash('success', 'Order deleted!');
            return response()->json(Order::destroy($id), 200);
        } else {
            Session::flash('error', 'It isn\'t your order!');
            return response()->json(null, 200);
        }
    }

    private function copyModelFromRequest(Order $order, $request, $isAppendUnrequired = true) {
        $order->customer = $request->customer;
        $order->comment = $request->comment;
        $order->company_name = $request->company_name;
        $order->packing_selection = $request->packing_selection;
        $order->address = $request->address;
        $order->city = $request->city;
        $order->zip_postal_code = $request->zip_postal_code;
        $order->state_region = $request->state_region;
        $order->country = $request->country;
        $order->phone = $request->phone;
        $order->shipping_company = $request->shipping_company;
        if(isset($request->tracking_number) && $isAppendUnrequired) {
            $order->tracking_number = $request->tracking_number;
        }
        if(isset($request->status) && $isAppendUnrequired) {
            $order->status = $request->status;
        }
        if(isset($request->shipping_cost) && $isAppendUnrequired) {
            $order->shipping_cost = $request->shipping_cost;
        }
        if(isset($request->shipped) && $isAppendUnrequired) {
            $order->shipped = $request->shipped;
        }
        if($order->shipped){
            $order->status = 'Shipped';
        }
    }

    private function syncProducts(Order $order, $order_products) {
        $arrayProducts = [];
        foreach ($order_products as $order_product) {
            $arrayProducts[$order_product['product_id']] = [
                'quantity' => $order_product['quantity'],
                'price' => $order_product['price'] ?? null,
                'description' => $order_product['description'] ?? null
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

    private function createBalanceHistory($transactionCost, User $user, $order) {
        if($user->balance - $transactionCost >= 0) {
            $balanceHistory = new BalanceHistory();
            $balanceHistory->user_id = $user->id;
            $balanceHistory->current_balance = $user->balance - $transactionCost;
            $balanceHistory->transaction_cost = $transactionCost;
            $balanceHistory->type = 'Debit';
            $balanceHistory->comment = "Order ID: " . $order->id;
            return $balanceHistory;
        } else {
            return false;
        }
    }

    private function updateBalanceHistory(OrderRequest $request, User $user, Order $lastOrder) {
        $transactionCost = $this->calculateDifferenceBalance($request, $lastOrder);

        if($user->balance - $transactionCost >= 0) {
            $balanceHistory = new BalanceHistory();
            $balanceHistory->user_id = $user->id;
            $balanceHistory->current_balance = $user->balance - $transactionCost;
            $balanceHistory->transaction_cost = abs($transactionCost);
            $balanceHistory->type = $transactionCost > 0 ? 'Debit' : 'Credit';
            $balanceHistory->comment = "Order ID: {$lastOrder->id}";
            return $balanceHistory;
        } else {
            return false;
        }
    }

    private function calculateBalance(OrderRequest $request) {
//        $newBalance = $request->shipping_cost ?? 0;
//        foreach ($request->order_products as $order_product) {
//            $newBalance += ( $order_product['price'] ?? 0 ) * $order_product['quantity'];
//        }
//        return $newBalance;
        return $request->shipping_cost ?? 0;
    }

    private function calculateCopyBalance(Order $order, $isWithShippingCost = true) {
//        $newBalance = $isWithShippingCost ? ($order->shipping_cost ?? 0) : 0;
//        foreach ($order->products as $product) {
//            $newBalance += ( $product->pivot->price ?? 0 ) * $product->pivot->quantity;
//        }
//        return $newBalance;
        return $isWithShippingCost ? ($order->shipping_cost ?? 0) : 0;
    }

    private function calculateDifferenceBalance(OrderRequest $request, Order $lastOrder) {
        $newBalance = $this->calculateBalance($request);
        $lastBalance = $this->calculateCopyBalance($lastOrder);
        return $newBalance - $lastBalance;
    }


    private function updateProducts( ) {
        foreach (Product::with('shipments')->with('orders')->get() as $product) {
            $product->in_transit = 0;
            $product->available = 0;
            $product->received = 0;
            foreach ($product->shipments as $shipment) {
                if($shipment->received == null) {
                    $product->in_transit += $shipment->pivot->quantity;
                } if($shipment->received !== null) {
                    $product->available += $shipment->pivot->quantity;
                }
            }
            foreach ($product->orders as $order) {
//                if($orders->status == 'Created') {
                if($order->shipped != null) {
                    $product->received += $order->pivot->quantity;
                }
            }
            $product->save();
        }

    }

}
