<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


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

//Route::get('/', function () {
//    return view('welcome');
//});

//Route::get('/', function () {
////    $test = "test value";
//////    return view('welcome', compact('test'));
////    return view('welcome')->with('test',$test);
////});
////
////Route::view('/contact','contact',['contact'=>'данные']);
//////Route::redirect('contact', '/');
////
////Route::match(['post','get'], '/home', function () {
////    if(isset($_POST['name'])){
////        return view('home')->with('form', $_POST['name']);
////    }else
////        return view('home');
////
////});
////Route::get('/product/{id}', function ($id){
////    return "post $id";
////});
///
Route::get('/','App\Http\Controllers\HomeController@index')->name('home');
Route::get('/admin','App\Http\Controllers\AdminController@index')->name('admin');
//Route::get('/get-categories', 'App\Http\Controllers\CategoriesController@getCategories');
//Route::post('/create-category', 'App\Http\Controllers\CategoriesController@createCategory');


// Секция работы с drag and drop
Route::put('/drag', '\App\Http\Controllers\DragController@changeParent');
Route::get('/drag', '\App\Http\Controllers\DragController@getCategoryChildes');
Route::put('/drag/order', '\App\Http\Controllers\DragController@changeOrder');

// CRUD для категорий каталога
Route::resource('categories', \App\Http\Controllers\CategoryController::class);

// Продукты
Route::resource('products', \App\Http\Controllers\ProductController::class);

// отображение конкретной категории
Route::get('/{slug}', '\App\Http\Controllers\ShowCategoryController@showCategory');


//загрузка файлов

Route::post('/upload', '\App\Http\Controllers\FilesUploadController@uploadFiles');
