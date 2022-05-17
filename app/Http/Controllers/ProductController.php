<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function parseCSV()
    {
    // print_r (storage_path());exit;
    
    // $file_handle = fopen("/var/www/LaravelHorizon/storage\app\public\get-reports\1652775509_yoprint_test_import6.csv", 'r');
    // $file_handle = fopen(storage_path("app/public/get-reports/1652775509_yoprint_test_import6.csv"), 'r');
    // request()->file('file') = 

    $app = Redis::connection();
    $dataCSV = $app->get('files:csv');
    
    explode("|",$dataCSV);
    $getFirstFiles = $dataCSV[1];
    Excel::import(new ProductsImport, storage_path("app/public/get-reports/".$getFirstFiles));
    unset($dataCSV[1]);
    implode("|",$dataCSV); 
    // ddd($file_handle);

    // fclose($file_handle);
        return redirect()->back()->with('success','Data parse Successfully');
    }

    public function saveCSV(Request $request)
    {
        
        $validatedData = $request->validate([
            'filecsv' => 'required|file|max:5024'
        ]);
        
        // ddd($request->file('filecsv'));
        $fileName = time().'_'.$request->file('filecsv')->getClientOriginalName();
        request()->file('filecsv')->storeAs('get-reports', $fileName, 'public');
        // request()->file('filecsv')->storeAs('reports', $fileName, 'public');
        $app = Redis::connection();
        $dataCSV = $app->get('files:csv');
        $dataCSV .= "$fileName|";
        $app->set('files:csv', $dataCSV);
        // $echo = $app->get('files:csv');
        return redirect()->back()->with('success','Data Imported Successfully');
    }
    
}
