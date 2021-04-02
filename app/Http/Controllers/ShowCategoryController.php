<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowCategoryController extends Controller
{
      public function showCategory ($slug){

        $categoryId = DB::table('categories')
          ->where('link', '=', $slug)->get('id');

        $products = DB::table('products')
          ->where('category_id', '=', $categoryId[0]->id)
          ->get();



        $title = "Просмотр категории";
        return view('category')->with(['title'=>$title, 'slug'=>$slug, 'products'=>$products]);

  }
}
