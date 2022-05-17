<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
    public function parseCSV(Request $request)
    {
        
        Excel::import(new ProductsImport, request()->file('file'));
        return redirect()->back()->with('success','Data Imported Successfully');
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
