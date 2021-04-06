<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowCategoryController extends Controller
{
      public function showCategory ($slug){

        $category = DB::table('categories')
          ->where('link', '=', $slug)->get();

//        $products = DB::table('products')
//          ->where('category_id', '=', $category[0]->id)
//          ->get();

//        $title = "Просмотр категории";
//        return $category;
        return view('category')->with('category', $category);

  }
}
