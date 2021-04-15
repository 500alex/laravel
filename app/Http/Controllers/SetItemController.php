<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetItemController extends Controller
{

    public function index(Request $request){
      $setId = $request->query('setId');
      $setItems = DB::table('set_items')->select('id','name')->where('set_id', $setId)->get();
      return $setItems;
    }

    public function store(Request $request){
      $values = $request->get('values');
      $setId = $request->get('setId');
      foreach ($values as $value){
        DB::table('set_items')->insert(['name'=>$value, 'set_id'=>$setId]);
      }
    }
}
