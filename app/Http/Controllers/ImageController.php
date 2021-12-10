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
use App\Http\Controllers\FileController;

class ImageController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/seller/image/get",
     * summary="Genel alandaki fotoğrafları getir",
     * description="Genel alandaki tüm fotoğrafları getirir.",
     * operationId="imageGet",
     * tags={"Fotoğraf"},
     * security={{"deha_token":{}}},
     * @OA\Parameter(
     *    required=false,
     *    in="query",
     *    name="image_id",
     *    description="Image Id",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Genel alandaki fotoğraflar başarı ile sorgulandı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Genel alandaki fotoğraflar başarı ile sorgulandı."),
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        try {
            if (isset($request->image_id)) {
                $image = ProductImage::find($request->image_id);
                if ($image) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Fotoğraf başarı ile sorgulandı.',
                        'image' => $image
                    ], 200);
                }
                return response()->json([
                    'error' => true,
                    'message' => 'Fotoğraf sorgulanırken bir hata oluştu.'
                ], 400);
            } else {
                $images = Image::all();
            }
            return response()->json([
                'error'=>false,
                'message'=>'Genel alandaki fotoğraflar başarı ile sorgulandı.',
                'images'=>Image::all()
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Genel alandaki fotoğraflar sorgulanırken bir hata oluştu.',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Genel alandaki fotoğraflar sorgulanırken bir hata oluştu.'
        ],400);
    }

    /**
     * @OA\GET(
     * path="/api/seller/product/image/get",
     * summary="Ürün fotoğraflarını getir",
     * description="Tüm ürün fotoğraflarını veya id gönderirseniz belirli bir ürün fotoğrafını getirir.",
     * operationId="productImageGet",
     * tags={"Fotoğraf"},
     * security={{"deha_token":{}}},
     * @OA\Parameter(
     *    required=false,
     *    in="query",
     *    name="product_id",
     *    description="Ürün Id",
     * ),
     * @OA\Parameter(
     *    required=false,
     *    in="query",
     *    name="image_id",
     *    description="Image Id",
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ürün fotoğrafları başarı ile sorgulandı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ürün fotoğrafları başarı ile sorgulandı."),
     *        )
     *     )
     * )
     */
    public function get_product_images(Request $request){
        try {
            if(isset($request->product_id) && isset($request->image_id)){
                $product_image = ProductImage::where('product_id',$request->product_id)->where('image_id',$request->image_id)->first();
                if($product_image){
                    return response()->json([
                        'error'=>false,
                        'message'=>'Ürün fotoğrafı başarı ile sorgulandı.',
                        'image'=>$product_image
                    ],200);
                }
                return response()->json([
                    'error'=>true,
                    'message'=>'Ürün fotoğrafları sorgulanırken bir hata oluştu.'
                ],400);
            }else if(isset($request->product_id)){
                $product_id = $request->product_id;
                $images= ProductImage::where('product_id',$product_id)->get();
            }else if(isset($request->image_id)){
                $image_id = $request->image_id;
                $images= ProductImage::where('id',$image_id)->get();
            }else{
                $images= ProductImage::all();
            }

            return response()->json([
                'error'=>false,
                'message'=>'Ürün fotoğrafları başarı ile sorgulandı.',
                'images'=>$images
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=> 'Ürün fotoğrafları sorgulanırken bir hata oluştu.',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=> 'Ürün fotoğrafları sorgulanırken bir hata oluştu.'
        ],400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/image/add",
     * summary="Genel Fotoğraf yükle",
     * description="Genel alana ilişkilendirilmek üzere fotoğraf yükleme işlemi yapar.",
     * operationId="imageAdd",
     * tags={"Fotoğraf"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Genel alana ilişkilendirilmek üzere fotoğraf yükleme işlemi yapar.Galeri Id Alanı gönderilirse bir galeriye eklenerek fotoğraf yüklenir. Eğer gönderilmez ise genel alana fotoğraf yüklenir.",
     *    @OA\JsonContent(
     *       required={"image"},
     *          @OA\Property(property="gallery_id", type="integer", example="1"),
     *          @OA\Property(property="image", type="text", example="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAABmJLR0QA/wD/AP+gvaeTAAAAm0lEQVQYlYXQTwpBYRSG8d8nAxmIkIGSAQMZYR+KbcjQEGVqGXZgL8r4llK24N/Ad0tceYfvOT1P5wSvtDDGFffY5ZDHHglUsUDwnRBn1RzaKGUspSmhHTCK6uYP9QlJPpZDrPDIUG+Q/CPWcHgnDrDOIG5RxjFEbQcN3CLxjCl26KMHRSxR+aDN0cUM9fR3BUziK1J9DZd4w/YJ6R8dgNmw0QoAAAAASUVORK5CYII="),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Fotoğraf yüklendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Fotoğraf yüklendi."),
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
                    $save->gallery_id = $request->gallery_id??null;
                    $save->uploaded_user_id = $request->get('user')->id;
                    $save->save();
                    if(isset($request->product_id)){;
                        $product = new ProductImage();
                        $product->image_id = $save->id;
                        $product->product_id = $request->product_id;
                        $product->save();

                    }
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

                
                $imageUpload=new FileController();
                $response=$imageUpload->upload_server($request->image);

                if($response['error']=='false'){
                    $save=new Image();
                    $save->name=$response['name'];
                    $save->path=$response['path'];
                    $save->type='url';
                    $save->fileId=$response['success'];
                    $save->gallery_id = $request->gallery_id??null;
                    $save->uploaded_user_id = $request->get('user')->id;
                    $save->save();
                    if (isset($request->product_id)) {;
                        $product = new ProductImage();
                        $product->image_id = $save->id;
                        $product->product_id = $request->product_id;
                        $product->save();
                    }
                    return response()->json([
                        'error' => false,
                        'message' => 'Fotoğraf yüklendi.',
                        'image' => $save
                    ], 200);
                }else{
                    return response()->json([
                        'error' => true,
                        'message' => 'Fotoğraf yüklenirken bir sorun oluştu.',
                        'exception' => $response['message']
                    ], 400);
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Fotoğraf yüklenirken bir sorun oluştu.',
                'exception' => $ex->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Post(
     * path="/api/seller/image/delete",
     * summary="Genel alandaki fotoğrafı sil",
     * description="Genel alandaki fotoğrafı siler kullanıldığı ilişkisel alanlardaki ilişkileri kaldırılır.",
     * operationId="imageDelete",
     * tags={"Fotoğraf"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Genel alandaki fotoğrafı siler kullanıldığı ilişkisel alanlardaki ilişkileri kaldırılır.",
     *    @OA\JsonContent(
     *       required={"image"},
     *          @OA\Property(property="image", type="integer", example="Image Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Fotoğraf silindi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Fotoğraf silindi."),
     *        )
     *     )
     * )
     */
    public function delete(Request $request){
        
        
        $validation=Validator::make($request->all(),[
            'image'=>'required|integer'
        ]);
        if($validation->fails()){
            $messages=[
                'image' => ($validation->getMessageBag())->messages()['image'] ?? 'success',
                'status' => 'Fotoğraf silinirken bir sorun oluştu.'
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
            $checkDriver = (Image::find($request->image))->getDriver->name;
            if ($checkDriver->option == 'imagekit') {
                $imageKit = new ImageKitController();
                $delete=Image::find($request->image);
                $response = $imageKit->delete_imagekit($delete->fileID);
                if ($response['error']=='false') {
                    $delete->delete();
                    return response()->json([
                        'error' => false,
                        'message' => 'Fotoğraf silindi.'
                    ], 200);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Fotoğraf silinirken bir sorun oluştu.',
                        'exception' => $response['message']
                    ], 400);
                }
            }else if($checkDriver->option=='server'){
                $imageUpload=new FileController();
                $delete = Image::find($request->image);
                $response=$imageUpload->delete_server($delete->path);
                if($response['error']=='false'){
                    $delete->delete();
                    return response()->json([
                        'error' => false,
                        'message' => 'Fotoğraf silindi.'
                    ], 200);
                }else{
                    return response()->json([
                        'error' => true,
                        'message' => 'Fotoğraf silinirken bir sorun oluştu.',
                        'exception' => $response['message']
                    ], 400);
                }
            }else{
                $delete = Image::find($request->image);
                $delete->delete();
                return response()->json([
                    'error' => false,
                    'message' => 'Fotoğraf silindi.'
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Fotoğraf silinirken bir sorun oluştu.',
                'exception' => $ex->getMessage()
            ], 400);
        }
    }

}
