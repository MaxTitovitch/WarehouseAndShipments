<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\User;
use App\Product;
use App\Order;
use Illuminate\Support\Facades\Auth;
use App\BalanceHistory;

class AdminController extends Controller
{
    public function index() {
        $user = Auth::user();
        $statistic = [
            'balance' => $user->balance,
            'orders' => $user->orders->count(),
            'shipments' => $user->shipments->count(),
            'turnover' => $this->getUserTurnover($user),
        ];
        return view('index')->with(['statistic' => $statistic]);
    }

    public function inboundShipments() {
        $shipments = Shipment::with('user')->get();
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
        $orders = Order::with('user')->get();
        return view('orders')->with(['orders' => $orders]);
    }

    public function chartData(Request $request) {
        $dates = $this->getDates($request);
        $statistic = [
            'orders-shipments' => $this->getOrdersAndShipments($dates),
            'balance' => $this->getBalanceHistory($dates),
            'dates' => $dates
        ];
        return response()->json($statistic, 200);

    }

    private function getUserTurnover($user) {
        $turnover = 0;
        foreach ($user->balanceHistories as $history) {
            $turnover += $history->transaction_cost;
        }
        return $turnover;
    }

    private function getDates(Request $request) {
        $date_start = strtotime($request->date_start);
        $date_end = strtotime($request->date_end);
        if ($date_start && $date_end) {
            $date_start = date('Y-m-d', $date_start);
            $date_end = date('Y-m-d', $date_end);
            if($date_end > date("Y-m-d")) {
                $date_end = date("Y-m-d");
            }
            if($date_start < date('Y-m-d', '2000-01-01')) {
                $date_start = date("Y-m-d", '2000-01-01');
            }
            if($date_start > $date_end) {
                $date_start = date("Y-m-d", mktime(0, 0, 0, date('m', strtotime($date_end)) - 1));
            }
        } else {
            $date_start = date("Y-m-d", mktime(0, 0, 0, date('m') - 1));
            $date_end = date("Y-m-d");
        }
        return ['date_start' => $date_start, 'date_end' => $date_end];
    }

    private function getBalanceHistory($dates) {
        $histories = BalanceHistory::whereRaw("created_at >= \"${dates['date_start']} 00:00:00\" ")
            ->whereRaw("created_at <= \"${dates['date_end']} 23:59:59\"")->where('user_id', Auth::id())->orderBy('created_at')->get();
        $result = [];
        foreach ($histories as $history) {
            $date = explode(' ', $history->created_at)[0];
            if(!empty($result[$date])) {
                $result[$date] += (double)$history->current_balance;
            } else {
                $result[$date] = (double)$history->current_balance;
            }
        }
        return $result;
    }

    private function getOrdersAndShipments($dates) {
        $orders = Order::whereRaw("created_at >= \"${dates['date_start']} 00:00:00\" ")
            ->whereRaw("created_at <= \"${dates['date_end']} 23:59:59\"")->where('user_id', Auth::id())->orderBy('created_at')->get();
        $shipments = Shipment::whereRaw("created_at >= \"${dates['date_start']} 00:00:00\" ")
            ->whereRaw("created_at <= \"${dates['date_end']} 23:59:59\"")->where('user_id', Auth::id())->orderBy('created_at')->get();
        $result = [];
        $this->addOneDataType($result, $orders, 'orders', 'shipments');
        $this->addOneDataType($result, $shipments, 'shipments', 'orders');
        return $result;
    }

    private function addOneDataType(&$result, $entities, $currentName, $lastName) {
        $currentSize = 0;
        foreach ($entities as $entity) {
            $date = explode(' ', $entity->created_at)[0];
            $currentSize++;
            if(!empty($result[$date])) {
                $result[$date][$currentName]++;
            } else {
                $result[$date][$lastName] = 0;
                $result[$date][$currentName] = $currentSize;
            }
        }
        $this->normalizeZeroValues($result, $currentName);
    }

    private function normalizeZeroValues(&$result, $currentName) {
        $last = 0;
        foreach ($result as $key => $resultOne) {
            if($result[$key][$currentName] === 0) {
                $result[$key][$currentName] = $last;
            }
            $last = $result[$key][$currentName];
        }
    }

}
