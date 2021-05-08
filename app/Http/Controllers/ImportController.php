<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Facades\MassUpload;
use Illuminate\Support\Facades\Hash;

class ImportController extends Controller
{
    public function parse(Request $request) {
        $typeEntity  = $request->get('type');
        $file = $request->file('file');
        $path = $this->getFileData($file);

        $isParsingSuccess = MassUpload::parse(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $path, $typeEntity);

        if($isParsingSuccess == "Success") {
            Session::flash('success', 'Готово');
        } else {
            Session::flash('error', 'Ошибка импорта');
        }
        Storage::delete($path);
        return redirect()->route('inbound-shipments');
    }

    private function getFileData($file) {
        $nameParts = explode('.', $file->getClientOriginalName());
        $typeFile = array_pop( $nameParts);
        $randomName = date('l-jS-\of-F-Y-h-i-s-A');
        return Storage::putFileAs('files-imports', $file, "$randomName.$typeFile");
    }
}
