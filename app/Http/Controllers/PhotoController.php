<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request){
        $imageData = $request->input('image');
        if ($imageData){
            // Remove the "data:image/png;base64," part if it's present
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);

            // Decode the base64 image
            $image = base64_decode($imageData);

            //Generate a Unique FileName
            $fileName = 'photo_'.uniqid().'.png';

            // Store the image in the 'public/photos' directory
            $filePath = 'public/live-stream/photos/' . $fileName;
            Storage::put($filePath, $image);
            return response()->json(['success' => true, 'file' => $fileName]);
        } else {
            return response()->json(['success' => false, 'error' => 'No image data']);
        }
    }
}
