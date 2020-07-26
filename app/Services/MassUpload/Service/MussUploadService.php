<?php

namespace App\Services\MassUpload\Service;

use PHPExcel_IOFactory;
use App\Product;
use Illuminate\Support\Facades\DB;
use App\Shipment;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\BalanceHistory;
use Mockery\Exception;

class MussUploadService {
    public function parse($file, $typeEntity) {
        switch ($typeEntity) {
            default:case'shipments':
                return $this->parseShipments($file);
            case'products':
                return $this->parseProducts($file);
            case'orders':
                return $this->parseOrders($file);
        }
    }


    private function parseShipments($file) {
        $shipments = [];
        try {
            $excel = PHPExcel_IOFactory::load($file);
            Foreach ($excel->getWorksheetIterator() as $worksheet) {
                $list = $worksheet->toArray();
                foreach ($list as $row) {
                    $shipment = new Shipment();
                    $shipment->tracking_number = $row[0];
                    $shipment->shipping_company = $row[1];
                    $shipment->comment = $row[2];
                    $shipment->quantity = 0;
                    $shipment->user_id = Auth::id();
                    $products = explode('|', $row[3]); $pivot = [];
                    foreach ($products as $product) {
                        $id = Product::where('name', explode(':', $product)[0])->first()->id;
                        $quantity = explode(':', $product)[1];
                        $pivot[$id] = ['quantity' => $quantity];
                        $shipment->quantity += $quantity;
                    }
                    $shipments[] = [$shipment, $pivot];
                }
            }
            return $this->saveEntities($shipments);
        } catch (\Exception $exception) {
            return 'Error';
        }
    }

    private function parseProducts($file) {
        $products = [];
        try {
            $excel = PHPExcel_IOFactory::load($file);
            Foreach ($excel->getWorksheetIterator() as $worksheet) {
                $list = $worksheet->toArray();
                foreach ($list as $row) {
                    $product = new Product();
                    $product->name = $row[0];
                    $product->brand = $row[1];
                    $product->upc = $row[2];
                    $product->sku = $row[3];
                    $products[] = [$product];
                }
            }
            return $this->saveEntities($products);
        } catch (\Exception $exception) {
            return 'Error';
        }
    }

    private function parseOrders($file) {
        $orders = [];
        try {
            $excel = PHPExcel_IOFactory::load($file);
            Foreach ($excel->getWorksheetIterator() as $worksheet) {
                $list = $worksheet->toArray();
                $totalCost = 0;
                foreach ($list as $row) {
                    $order = new Order();
                    $order->customer = $row[0];
                    $order->comment = $row[1];
                    $order->shipping_company = $row[2];
                    $order->tracking_number = $row[3];
                    $order->packing_selection = $row[4];
                    $order->address = $row[5];
                    $order->city = $row[6];
                    $order->zip_postal_code = $row[7];
                    $order->state_region = $row[8];
                    $order->country = $row[9];
                    $order->phone = $row[10];
                    $order->user_id = Auth::id();
                    $products = explode('|', $row[11]); $pivot = [];
                    foreach ($products as $product) {
                        $productFields = explode(':', $product);
                        $id = Product::where('name', $productFields[0])->first()->id;
                        $pivot[$id] = ['quantity' => $productFields[1]];
                        if(isset($productFields[2])) {
                            $pivot[$id]['price'] = $productFields[2];
                            $totalCost += $pivot[$id]['quantity'] * $pivot[$id]['price'];
                        }
                        if(isset($productFields[3])) {
                            $pivot[$id]['description'] = $productFields[3];
                        }
                    }
                    $arrayData = [$order, $pivot];
                    if($totalCost != 0) {
                        $arrayData[] = $totalCost;
                    }
                    $orders[] = $arrayData;
                }
            }
            return $this->saveEntities($orders);
        } catch (\Exception $exception) {
            return 'Error';
        }
    }

    private function saveEntities(array $entities) {
        try {
            DB::transaction(function() use ($entities){
                foreach ($entities as $entity) {
                    $entity[0]->save();
                    if(isset($entity[1])) {
                        $entity[0]->products()->sync($entity[1]);
                    }
                    if(isset($entity[2])) {
                        $user = Auth::user();
                        if( $user->balance - $entity[2] >= 0) {
                            $balanceHistory = new BalanceHistory();
                            $balanceHistory->user_id = $user->id;
                            $balanceHistory->current_balance = $user->balance - $entity[2];
                            $balanceHistory->transaction_cost = $entity[2];
                            $balanceHistory->type = 'Debit';
                            $balanceHistory->save();
                            $user->balance -= $entity[2];
                            $user->save();
                        } else {
                            throw new Exception();
                        }
                    }
                }
            });
            return 'Success';
        } catch (\Exception $exception) {
            return 'Error';
        }
    }
}