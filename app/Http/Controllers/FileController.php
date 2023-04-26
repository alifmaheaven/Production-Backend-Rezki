<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Validator;

class FileController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found',
                'server_time' => (int) round(microtime(true) * 1000),
            ], 422);
        }

        $allowedfileExtension = ['pdf', 'jpg', 'png'];
        $files = $request->file('file');
        $errors = [];

        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $check = in_array($extension, $allowedfileExtension);

            if (!$check) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File type not allowed',
                    'server_time' => (int) round(microtime(true) * 1000),
                ], 422);
            }
        }

        $responses = [];

        foreach ($files as $file) {
            $path = $file->store('public/images');
            $name = $file->getClientOriginalName();

            //store image file into directory and db
            // uncomment the code below if you want to save the file to the database
            /*
            $save = new Image();
            $save->title = $name;
            $save->path = $path;
            $save->save();
            */
            $responses[] = [
                'title' => $name,
                // 'path' => $path,
                'url' => Storage::url($path),
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'File uploaded successfully',
            'data' => $responses,
            'server_time' => (int) round(microtime(true) * 1000),
        ], 200);
    }
}

