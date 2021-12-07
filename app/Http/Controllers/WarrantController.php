<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warranty;
use Illuminate\Support\Facades\Validator;

class WarrantController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/seller/warranty/get",
     * summary="Garanti Listesi",
     * description="Tanımlanan garanti listesi.",
     * operationId="warrantyGet",
     * tags={"Garanti"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Garanti listelendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Garanti listelendi."),
     *        )
     *     )
     * )
     */
    public function get()
    {
        try {
            $warranties=Warranty::all();
            return response()->json([
                'error'=>false,
                'message'=>'Sorgu başarılı',
                'warranties' => $warranties
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/warranty/add",
     * summary="Garanti Oluştur",
     * description="Yeni bir garanti oluştur.",
     * operationId="warrantyAdd",
     * tags={"Garanti"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni garanti tanımını oluşturur.",
     *    @OA\JsonContent(
     *       required={"name"},
     *          @OA\Property(property="name", type="string", example="Garanti adı"),
     *          @OA\Property(property="description", type="string", example="Garanti Açıklaması"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Garanti tanımı oluşturuldu.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Garanti tanımı oluşturuldu."),
     *        )
     *     )
     * )
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required'
        ]);
        if($validation->fails()){
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
            ], 401);
        }
        try {
            $warranty=new Warranty();
            $warranty->name=$request->name;
            $warranty->description=$request->description??null;
            $warranty->save();
            return response()->json([
                'error'=>false,
                'message'=>'Garanti eklendi.'
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/warranty/update",
     * summary="Garanti Güncelle",
     * description="Var olan garanti tanımını günceller.",
     * operationId="warrantyUpdate",
     * tags={"Garanti"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan garanti tanımını günceller.",
     *    @OA\JsonContent(
     *       required={"warranty_id","name"},
     *          @OA\Property(property="warranty_id", type="integer", example="Garanti Id"),
     *          @OA\Property(property="name", type="string", example="Garanti adı"),
     *          @OA\Property(property="description", type="string", example="Garanti Açıklaması"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Garanti tanımı güncellendi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Garanti tanımı güncellendi."),
     *        )
     *     )
     * )
     */
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'warranty_id' => 'required',
            'name'=>'required'
        ]);
        if($validation->fails()){
            $messages = [
                'warranty_id' => ($validation->getMessageBag())->messages()['warranty_id'] ?? 'success',
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ], 401);
        }
        try {
            $warranty=Warranty::find($request->id);
            $warranty->name=$request->name;
            $warranty->description=$request->description??null;
            $warranty->save();
            return response()->json([
                'error'=>false,
                'message'=>'Garanti güncellendi.'
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/warranty/delete",
     * summary="Garanti Sil",
     * description="Var olan garanti tanımını siler.",
     * operationId="warrantyDelete",
     * tags={"Garanti"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan garanti tanımını siler.",
     *    @OA\JsonContent(
     *       required={"warranty_id"},
     *          @OA\Property(property="warranty_id", type="integer", example="Garanti Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Garanti tanımı silindi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Garanti tanımı silindi."),
     *        )
     *     )
     * )
     */
    public function delete(Request $request){
        $validation = Validator::make($request->all(), [
            'warranty_id' => 'required'
        ]);
        if($validation->fails()){
            $messages = [
                'warranty_id' => ($validation->getMessageBag())->messages()['warranty_id'] ?? 'success',
            ];
            return response()->json([
                'error' => true,
                'message' => 'Bu işlem için gerekli bilgiler eksik.',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ], 401);
        }
        try {
            $warranty=Warranty::find($request->warranty_id);
            $warranty->delete();
            return response()->json([
                'error'=>false,
                'message'=>'Garanti silindi.'
            ],200);
        } catch (\Exception $th) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu.',
                'exception'=>$th->getMessage()
            ],400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Teknik bir hata oluştu.'
        ], 400);
    }
}
