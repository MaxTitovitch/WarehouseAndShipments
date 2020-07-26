<?php

namespace App\Services\MassUpload\Service;

use PHPExcel_IOFactory;
use App\Product;
use Illuminate\Support\Facades\DB;

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
        return null;
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
                    $products[] = $product;
                }
            }
            return $this->saveEntities($products);
        } catch (\Exception $ex) {
            return 'Error';
        }
    }

    private function parseOrders($file) {
        return null;
    }

    private function saveEntities(array $entities) {
        try {
            DB::transaction(function() use ($entities){
                foreach ($entities as $entity) {
                    $entity->save();
                }
            });
            return 'Success';
        } catch (\Exception $exception) {
            return 'Error';
        }
    }
}