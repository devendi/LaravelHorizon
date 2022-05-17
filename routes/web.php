<?php

use App\User;
use App\Product;
use App\Imports\UsersImport;
use App\Imports\ProductsImport;
// use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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
    
    // return view('welcome');
    return view('homes',[
        'users' => Product::all()
    ]);
});

Route::get('/product', function () {
    return view('product',[
        'users' => Product::all()
    ]);
});

// Route::get('/redis', function () {
    
//     // // print_r(app()->make('redis'));
//     // $app = app()->make('redis');
//     // $redis = app()->make('redis');
//     // $redis->set('key1', 'value test');
//     // return $redis->get('key1');
    
//     $app = Redis::connection();
//     $app->set('key2', 'value test 2');
//     return $app->get('key2');

// });

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
    
    // ddd(request()->file('file'));
    Excel::import(new ProductsImport, request()->file('file'));
    return redirect()->back()->with('success','Data Imported Successfully');
});

Route::post('import_product2', function () {
    
    $fileName = time().'_'.request()->file->getClientOriginalName();
    request()->file('filecsv')->storeAs('reports', $fileName, 'public');
    return request()->file('filecsv')->store('post-images');
    // ddd(request()->file('file'));
    // Excel::import(new ProductsImport, request()->file('file'));
    // return redirect()->back()->with('success','Data Imported Successfully');
});

Route::post('saveCSV',[ProductController::class, 'saveCSV']);
