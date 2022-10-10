<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CropImageController extends Controller
{
   
    public function index()
    {
      return view('croppie');
    }
   
    public function uploadCropImage(Request $request)
    {
        $image = $request->image;

        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name= time().'.png';
        $path = public_path('uploads/crop/'.$image_name);

        file_put_contents($path, $image);
		
		
		
		
        return response()->json(['image'=>$image_name]);
    }
}