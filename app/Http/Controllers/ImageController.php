<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
use App\Models\Icon;
use App\Models\ProductImage;
use App\Models\UserImage;
use App\Models\Setting;

use App\Http\Controllers\ImageKitController;

class ImageController extends Controller
{

    public $sizes=[
        'height'=>500,
        'width'=>500
    ];

    /**
     * @OA\Post(
     * path="/api/seller/image/add",
     * summary="resim ekle test",
     * description="resim ekle test",
     * operationId="imageAdd",
     * tags={"Resim"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="resim ekle test",
     *    @OA\JsonContent(
     *       required={"image"},
     *          @OA\Property(property="image", type="text", example="data:image/jpeg;base64,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"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya silindi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya silindi."),
     *        )
     *     )
     * )
     */
    public function upload(Request $request){
        $validation=Validator::make($request->all(),[
            'image'=>'required|min:50|max:5000000'
        ]);
        if($validation->fails() || strpos($request->image,'data:image/')===false){
            $messages=[
                'image' => ($validation->getMessageBag())->messages()['image'] ?? 'success',
                'status' => 'Fotoğraf base64 formatında olmalı.'
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
        try {
            $checkDriver = Setting::where('setting', 'image_driver')->first();
            if ($checkDriver->option == 'imagekit') {
                $imageKit = new ImageKitController();
                $response = $imageKit->upload_imagekit($request->image);
                
                if ($response['error']=='false') {
                    $save = new Image();
                    $save->name = $response['success']->name;
                    $save->path = $response['success']->url;
                    $save->type = 'url';
                    $save->fileId=$response['success']->fileId;
                    $save->height = $response['success']->height??null;
                    $save->width= $response['success']->width??null;
                    $save->size= $response['success']->size??null;
                    $save->thumbnailUrl= $response['success']->thumbnailUrl??null;
                    $save->save();
                    return response()->json([
                        'error' => false,
                        'message' => 'Fotoğraf yüklendi.',
                        'image' => $save
                    ], 200);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Fotoğraf yüklenirken bir sorun oluştu.',
                        'exception' => $response['message']
                    ], 400);
                }
            }else if($checkDriver->option=='server'){
                $data=$request->image;
                $data = str_replace('data:image/png;base64,', '', $data);
                $data = str_replace('data:image/jpeg;base64,', '', $data);
                $data = str_replace('data:image/jpg;base64,', '', $data);
                $data = str_replace('data:image/gif;base64,', '', $data);
                $data = str_replace(' ', '+', $data);
                $data = base64_decode($data);
                $folderName = 'public/uploads/';
                $safeName = time() . '.' . 'jpeg';
                $destinationPath = public_path() . $folderName;
                $success = file_put_contents(public_path() . '/uploads/' . $safeName, $data);
                die($success);
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $save = new Image();
                $save->name = $name;
                $save->path = '/images/' . $name;
                $save->type = 'file';
                $save->height = $image->getClientSize();
                $save->width= $image->getClientSize();
                $save->size= $image->getClientSize();
                $save->save();
                return response()->json([
                    'error' => false,
                    'message' => 'Fotoğraf yüklendi.',
                    'image' => $save
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Fotoğraf yüklenirken bir sorun oluştu.',
                'exception' => $ex->getMessage()
            ], 400);
        }
    }

    

}
