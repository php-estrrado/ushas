<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Validator;
use File;

class UploadController extends Controller
{
    public function __construct(){ }
    
    function uploadFile(Request $request){ // return ['message'=> 'Hi'];
        $post              =   (object)$request->post();
        echo storage_path().$post->path.' ';
    //    echo '<pre>'; print_r( $request->post()); echo '</pre>';  die;
        $folderName =storage_path().$post->path; 
        if(!is_dir($folderName)){ mkdir($folderName,0755,TRUE); };
        $file = base64_decode($post->file);
        $success = file_put_contents($folderName.'/'.$post->fileName, $file);

        return ['status'=>'success','message'=>'Image Uploaded'];
    }
    
    
    
}
