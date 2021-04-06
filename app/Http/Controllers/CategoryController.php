<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $level = $request->query('level');

      if($level == -1){
        $categories = DB::table('categories')->orderBy('position')->get();

        foreach ($categories as $category) {
          $count = DB::table('categories')->where('parent_id', '=', $category->id)->count();
          $productCount = DB::table('products')->where('category_id','=',$category->id)->count();
          $category->count = $count;
          $category->productCount = $productCount;
        }
        return $categories;
      }

//      $categories = DB::select('select * from categories');
      $categories = DB::table('categories')
        ->where('parent_id','=', $level)
        ->orderBy('position')
        ->get();


      foreach ($categories as $category) {
        $count = DB::table('categories')->where('parent_id', '=', $category->id)->count();
        $productCount = DB::table('products')->where('category_id','=',$category->id)->count();
        $category->count = $count;
        $category->productCount = $productCount;
        }
//        $result = array();
//        foreach ($categories as $cat){
//            $result[$cat->parent_id][] = $cat;
//        }
//        dump($request);



        return $categories;
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
        DB::table('categories')->insert([
            'name' => $data["name"],
            'parent_id' => $data["parentId"],
            'link'=>$data["link"]
        ]);
//        dump($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
      DB::table('categories')->where('id', '=', $id)->update(['name'=>$data['name'],'link'=>$data['link']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      DB::table('products')->where('category_id', '=', $id)->delete();
      DB::table('categories')->where('id', '=', $id)->delete();


    }
}
