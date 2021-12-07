<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\Icon;
use App\Models\ProductImage;
use App\Models\UserImage;
use App\Models\Setting;

class ImageController extends Controller
{

    public $sizes=[
        'height'=>500,
        'width'=>500
    ];


    public function upload(Request $request){
        $validation=Validator::make($request->all(),[
            'image'=>'required|min:50|max:5000000'
        ]);
        if($validation->fails()){
            $messages=[
                'image' => ($validation->getMessageBag())->messages()['image'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ], 400);
        }
        $checkDriver=Setting::where('setting','image_driver')->first();
        if($checkDriver->option=='imagekit'){
            $this->upload_imagekit($request->image);
        }
    }

    public function upload_imagekit($image){
        $imagekit=new ImageKit();
        $imagekit->upload($image);
    }
    
}
