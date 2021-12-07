<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Icon;
use App\Models\ProductImage;
use App\Models\UserImage;

class ImageController extends Controller
{
    public function get_user_image($id)
    {
        try {
            $image = UserImage::find($id);
            return response()->json([
                'error' => false,
                'message' => 'Resim başarı ile sorgulandı',
                'id'=>$id,
                'user_image' => [
                    'id' => $image->id,
                    'image' => $image->image,
                    'imageType' => $image->type
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Teknik bir h ata oluştu.',
                'exception' => $ex->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }
    
}
