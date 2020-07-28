<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\Http\Requests\ShipmentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShipmentController extends Controller
{
    public function store(ShipmentRequest $request)
    {
        $shipment = new Shipment();
        $shipment->user_id = Auth::id() || 1;
        $this->copyModelFromRequest($shipment, $request);
        $this->syncProducts($shipment, $request);
        Session::flash('success', 'New Inbound shipment created!');
        return response()->json($shipment, 200);
    }

    public function show($id)
    {

        $shipment = Shipment::with(['user', 'products'])->find($id);
        if(Auth::id() === $shipment->user_id) {
            return response()->json($shipment, 200);
        } else {
            return response()->json(null, 403);
        }
    }

    public function update(ShipmentRequest $request, $id)
    {
        $shipment = Shipment::find($id);
        if(Auth::id() === $shipment->user_id) {
            $this->copyModelFromRequest($shipment, $request);
            $this->syncProducts($shipment, $request);
            Session::flash('success', 'Inbound shipment updated!');
            return response()->json($shipment, 200);
        } else {
            Session::flash('error', 'It isn\'t your shipment!');
            return response()->json(null, 403);
        }
    }


    private function copyModelFromRequest(Shipment &$shipment, ShipmentRequest $request) {
        $shipment->tracking_number = $request->tracking_number;
        $shipment->shipping_company = $request->shipping_company;
        $shipment->comment = $request->comment;
        $shipment->quantity = 0;
        if(isset($request->received)) {
            $shipment->received = $request->received;
        }
        if(isset($request->shipped)) {
            $shipment->shipped = $request->shipped;
        }
    }

    private function syncProducts(Shipment &$shipment, ShipmentRequest $request) {
        $arrayProducts = [];
        foreach ($request->product_shipments as $product_shipment) {
            $shipment->quantity += $product_shipment['quantity'];
            $arrayProducts[$product_shipment['product_id']] = ['quantity' => $product_shipment['quantity']];
        }
        $shipment->save();
        $shipment->products()->sync($arrayProducts);
    }
}
