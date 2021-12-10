<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Validator;
use App\Models\Image;
class GalleryController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/seller/gallery/get",
     * summary="Galeri Listesi",
     * description="Tanımlanan galeri listesi.Galeri ID'si gönderilirse ID'ye ait görseller de listelenir.",
     * operationId="galleryGet",
     * tags={"Galeri"},
     * security={{"deha_token":{}}},
     * @OA\Parameter(
     *    name="gallery_id",
     *    in="query",
     *    description="Galeri Id",
     *    required=false,
     * ),
     */
    public function get(Request $request)
    {
        try {
            if(isset($request->gallery_id)){
                $gallery = Gallery::where('id', $request->gallery_id)->first();
                return response()->json([
                    'error' => false,
                    'message' => 'Sorgu başarılı',
                    'gallery' => [
                        'id'=>$gallery->id,
                        'name'=>$gallery->name,
                        'description'=>$gallery->description,
                        'images'=>$gallery->getImages,
                        'created_at'=>$gallery->created_at,
                        'updated_at'=>$gallery->updated_at
                    ],
                ], 200);
            }else{
                $galleries = Gallery::all();
                return response()->json([
                    'error' => false,
                    'message' => 'Sorgu başarılı',
                    'galleries' => $galleries
                ], 200);
            }
            
        } catch (\Exception $th) {
            return response()->json([
                'error' => true,
                'message' => 'Teknik bir hata oluştu.',
                'exception' => $th->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/gallery/add",
     * summary="Galeri Oluştur",
     * description="Yeni galeri tanımı oluştur.",
     * operationId="unitAdd",
     * tags={"Galeri"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni bir galeri tanımlar.",
     *    @OA\JsonContent(
     *       required={"name"},
     *          @OA\Property(property="name", type="string", example="Galeri adı"),
     *          @OA\Property(property="description", type="string", example="Galeri açıklaması"),
     *    ),
     * ),
     */
    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
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
            $gallery = new Gallery();
            $gallery->name = $request->name;
            $gallery->description = $request->description ?? null;
            $gallery->save();
            return response()->json([
                'error' => false,
                'message' => 'Galeri başarıyla eklendi.',
                'gallery' => $gallery
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'error' => true,
                'message' => 'Teknik bir hata oluştu.',
                'exception' => $th->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/gallery/update",
     * summary="Galeri Güncelle",
     * description="Var olan bir galeri tanımını güncelle.",
     * operationId="galleryUpdate",
     * tags={"Galeri"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir galeri tanımını güncelle.",
     *    @OA\JsonContent(
     *       required={"name","gallery_id"},
     *          @OA\Property(property="gallery_id", type="integer", example="Galeri Tanım Id"),
     *          @OA\Property(property="name", type="string", example="Galeri adı"),
     *          @OA\Property(property="description", type="string", example="Galeri açıklaması"),
     *    ),
     * ),
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'gallery_id' => 'required|numeric'
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'gallery_id' => ($validation->getMessageBag())->messages()['gallery_id'] ?? 'success',
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
            $gallery = Gallery::find($request->gallery_id);
            $gallery->name = $request->name;
            $gallery->description = $request->description ?? null;
            $gallery->save();
            return response()->json([
                'error' => false,
                'message' => 'Galeri başarıyla güncellendi.',
                'gallery' => $gallery
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'error' => true,
                'message' => 'Teknik bir hata oluştu.',
                'exception' => $th->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/gallery/delete",
     * summary="Galeri Sil",
     * description="Var olan bir galeri tanımını siler.(Bu galeriye tanımlı tüm resimler silinecektir!)",
     * operationId="galleryDelete",
     * tags={"Galeri"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir galeri tanımını siler.(Bu galeriye tanımlı tüm resimler silinecektir!)",
     *    @OA\JsonContent(
     *       required={"gallery_id"},
     *          @OA\Property(property="gallery_id", type="integer", example="Galeri Tanım Id"),
     *    ),
     * ),
     */
    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'gallery_id' => 'required|numeric'
        ]);
        if ($validation->fails()) {
            $messages = [
                'gallery_id' => ($validation->getMessageBag())->messages()['gallery_id'] ?? 'success',
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
            $gallery = Gallery::find($request->gallery_id);
            $gallery->delete();
            return response()->json([
                'error' => false,
                'message' => 'Galeri başarıyla silindi.'
            ], 200);
        } catch (\Exception $th) {
            return response()->json([
                'error' => true,
                'message' => 'Teknik bir hata oluştu.',
                'exception' => $th->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/gallery/image/add",
     * summary="Galeriye Resim Ekle",
     * description="Galeriye daha önce yüklenmiş olan bir resim ekler.",
     * operationId="galleryImageAdd",
     * tags={"Galeri"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Galeriye daha önce yüklenmiş olan bir resim ekler.",
     *    @OA\JsonContent(
     *       required={"gallery_id","image_id"},
     *          @OA\Property(property="gallery_id", type="integer", example="Galeri Tanım Id"),
     *          @OA\Property(property="image_id", type="integer", example="Resim Id"),
     *    ),
     * ),
     */
    public function image_add(Request $request){
        $validation=Validator::make($request->all(),[
            'gallery_id'=>'required|numeric',
            'image_id'=>'required|numeric'
        ]);
        if($validation->fails()){
            $messages=[
                'gallery_id'=>($validation->getMessageBag())->messages()['gallery_id']??'success',
                'image_id'=>($validation->getMessageBag())->messages()['image_id']??'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Bu işlem için gerekli bilgiler eksik.',
                'validation'=>array_filter($messages,function($e){
                    if($e!='success'){
                        return true;
                    }
                })
            ],400);
        }
        try{
            $gallery=Gallery::find($request->gallery_id);
            $image=Image::find($request->image_id);
            if($gallery && $image){
                $image->gallery_id=$gallery->id;
                $image->save();
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Resim veya galeri bulunamadı.'
                ],400);
            }
            return response()->json([
                'error'=>false,
                'message'=>'Resim başarıyla galeriye eklendi.'
            ],200);
        }catch(\Exception $th){
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu.'
        ],400);
    }

    public function image_delete(Request $request){
        $validation=Validator::make($request->all(),[
            'image_id'=>'required|numeric'
        ]);
        if($validation->fails()){
            $messages=[
                'image_id'=>($validation->getMessageBag())->messages()['image_id']??'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Bu işlem için gerekli bilgiler eksik.',
                'validation'=>array_filter($messages,function($e){
                    if($e!='success'){
                        return true;
                    }
                })
            ],400);
        }
        try{
            $image=Image::find($request->image_id);
            if($image){
                $image->gallery_id=null;
                $image->save();
            }else{
                return response()->json([
                    'error'=>true,
                    'message'=>'Resim bulunamadı.'
                ],400);
            }
            return response()->json([
                'error'=>false,
                'message'=>'Resim başarıyla galeriden silindi.'
            ],200);
        }catch(\Exception $th){
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu.'
        ],400);
    }

}
