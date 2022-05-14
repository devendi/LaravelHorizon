<?php

use App\Imports\UsersImport;
use App\Imports\ProductsImport;
use App\User;
use App\Product;
// use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product', function () {
    return view('product',[
        'users' => Product::all()
    ]);
});

Route::get('/queue', function () {
    $queue = Queue::push('LogMessage',array('message'=>'Time: '.time()));
    
    return view('welcome',[
        'product' => Product::all(),
        'queue' => $queue
    ]);
});

class LogMessage{
    public function fire($job, $date){
        File::append(app_path().'/queue.txt',$date['message'].PHP_EOL);
        $job->delete();
    }
}

Route::post('import', function () {
    Excel::import(new UsersImport, request()->file('file'));
    return redirect()->back()->with('success','Data Imported Successfully');
});

Route::post('import_product', function () {

    $fileName = time().'_'.request()->file->getClientOriginalName();
    request()->file('file')->storeAs('reports', $fileName, 'public');
    
    Excel::import(new ProductsImport, request()->file('file'));
    return redirect()->back()->with('success','Data Imported Successfully');
});
