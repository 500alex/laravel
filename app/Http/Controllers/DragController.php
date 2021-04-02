<?php

namespace App\Http\Controllers;

use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DragController extends Controller
{
    public function changeParent(Request $request){
      $requestBody = $request->all();

      DB::table('categories')
        ->where('id', $requestBody['categoryId'])
        ->update(['parent_id' => $requestBody['parentId']]);

    }

    public function getCategoryChildes(Request $request) {
      $parentId = $request->query('parentId');

      $childes = DB::table('categories')
        ->where('parent_id', $parentId)
        ->orderBy('position')
        ->get();

      return $childes;
    }
    public function changeOrder(Request $request) {
        $data = $request->all();
        $list = $data['list'];
        foreach ($list as $category){
          DB::table('categories')
            ->where('id', '=', $category['id'])
            ->update(['position'=> $category['position']]);
        }
//        return $list;
    }
}
