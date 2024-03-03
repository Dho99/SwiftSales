<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Gumlet\ImageResize;

class DropzoneController extends Controller
{
    public function uploadImage(Request $request, $dirname)
    {
        $input = $request->all();
        $rules = array(
            'file' => 'image|max:5120|mimes:jpg,png,jpeg,webp',
        );
        $validation = Validator::make($input, $rules);
        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $directory = storage_path('app/public/uploads/'.$dirname);
        $filename = $file->getClientOriginalName();
        $store = $file->move($directory, $filename);
        $resizeImage = new ImageResize($directory.'/'.$filename);
        $resizeImage->resizeToBestFit(500, 300);
        Storage::delete('public/uploads/'.$dirname.'/'.$filename);
        $filename = 'resized'.time().'-'.$filename;
        $upload_success = $resizeImage->save($directory.'/'.$filename);

        if ($upload_success) {
            return response()->json(['file' => $filename], 200);
        } else {
            return response()->json('error', 400);
        }
    }

    public function removeImage(Request $request, $dirname){
        $data = $request->all();

        $filename = $data['filename'];

        $delete = Storage::delete('public/uploads/'.$dirname.'/'.$filename);

        return response()->json(['file' => $data], 200);
    }

}
