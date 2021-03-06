<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categoryId =  $request->query('categoryId');
        $products = DB::table('products')
          ->where('category_id','=', $categoryId)
          ->get();

        foreach ($products as $product){

            $product->images  = DB::table('files')
              ->where('owner_id','=', $product->id)
              ->get();

        }

        return $products;
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

      $data = $request->all();

      $id = DB::table('products')->insertGetId([
        'name' => $data["name"],
        'category_id'=>$data['categoryId']
      ]);
      return $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     $product =  DB::table('products')->where('id', '=', $id)->get();
      $product[0]->images  = DB::table('files')
        ->where('owner_id','=', $id)
        ->get();

     return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $data = $request->all();

      $id = DB::table('products')
        ->where('id',$id)
        ->update(['name'=>$data['name']]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $files = DB::table('files')->where('owner_id', '=', $id)->get();
      foreach ($files as $file){
        Storage::delete($file->link);
      }
      DB::table('files')->where('owner_id', '=',$id)->delete();
      DB::table('products')->where('id', '=', $id)->delete();
    }
}
