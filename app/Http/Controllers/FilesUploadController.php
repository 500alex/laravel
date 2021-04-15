<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FilesUploadController extends Controller
{
    public function uploadFiles(Request $request) {
      if ($request->hasFile('file')) {
        $ownerId =  $request->get('ownerId');
        $folder = date('Y-m-d');
        $path = $request->file('file')->store("images/{$folder}");
        $name = $request->file('file')->getClientOriginalName();

        $id = DB::table('files')->insertGetId([
          'link'=> $path,
          'name' => $name,
          'owner_id'=>$ownerId
        ]);

        return $id;

      }else {
        return null;
      }
    }
    public function destroy($id) {
      $file = DB::table('files')->where('id', '=', $id)->first();
      Storage::delete($file->link);
      DB::table('files')->where('id', '=', $id)->delete();
    }
}
