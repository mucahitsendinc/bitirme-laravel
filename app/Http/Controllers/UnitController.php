<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/seller/unit/get",
     * summary="Birim Listesi",
     * description="Tanımlanan birim listesi.",
     * operationId="warrantyGet",
     * tags={"Birim"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Birim listelendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Birim listelendi."),
     *        )
     *     )
     * )
     */
    public function get()
    {
        try {
            $warranties = Unit::all();
            return response()->json([
                'error' => false,
                'message' => 'Sorgu başarılı',
                'units' => $warranties
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
     * path="/api/seller/unit/add",
     * summary="Birim Oluştur",
     * description="Yeni birim tanımı oluştur.",
     * operationId="unitAdd",
     * tags={"Birim"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni bir birim tanımlar.",
     *    @OA\JsonContent(
     *       required={"name","symbol"},
     *          @OA\Property(property="name", type="string", example="Birim"),
     *          @OA\Property(property="symbol", type="string", example="Birim Sembolü"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Birim tanımı oluşturuldu.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Birim tanımıı oluşturuldu."),
     *        )
     *     )
     * )
     */
    public function create(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'name' => 'required',
            'symbol' => 'required'
        ]);
        if($validation->fails()){
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'symbol' => ($validation->getMessageBag())->messages()['symbol'] ?? 'success',
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
            $unit = new Unit();
            $unit->name = $request->name;
            $unit->symbol = $request->symbol??null;
            $unit->save();
            return response()->json([
                'error' => false,
                'message' => 'Birim başarıyla eklendi.'
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
     * path="/api/seller/unit/update",
     * summary="Birim Güncelle",
     * description="Var olan bir birim tanımını güncelle.",
     * operationId="unitUpdate",
     * tags={"Birim"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir birim tanımını güncelle.",
     *    @OA\JsonContent(
     *       required={"name","symbol","unit_id"},
     *          @OA\Property(property="unit_id", type="integer", example="Birim Tanım Id"),
     *          @OA\Property(property="name", type="string", example="Birim Adı (Ör:Adet,Kilogram,Metre,Dakika,...)"),
     *          @OA\Property(property="symbol", type="string", example="Birim Sembolü (Ör:KG,M,DK,...)"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Birim tanımı güncellendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Birim tanımıı güncellendi."),
     *        )
     *     )
     * )
     */
    public function update(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'name' => 'required',
            'symbol'=>'required',
            'unit_id' => 'required|numeric'
        ]);
        if($validation->fails()){
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'symbol' => ($validation->getMessageBag())->messages()['symbol'] ?? 'success',
                'unit_id' => ($validation->getMessageBag())->messages()['unit_id'] ?? 'success',
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
            $unit = Unit::find($request->unit_id);
            $unit->name = $request->name;
            $unit->symbol = $request->symbol??null;
            $unit->save();
            return response()->json([
                'error' => false,
                'message' => 'Birim başarıyla güncellendi.'
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
     * path="/api/seller/unit/delete",
     * summary="Birim Sil",
     * description="Var olan bir birim tanımını siler.",
     * operationId="unitDelete",
     * tags={"Birim"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir birim tanımını siler.",
     *    @OA\JsonContent(
     *       required={"unit_id"},
     *          @OA\Property(property="unit_id", type="integer", example="Birim Tanım Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Birim tanımı silindi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Birim tanımıı silindi."),
     *        )
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'unit_id' => 'required|numeric'
        ]);

        if($validation->fails()){
            $messages = [
                'unit_id' => ($validation->getMessageBag())->messages()['unit_id'] ?? 'success',
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
            $unit = Unit::find($request->unit_id);
            if($unit!=null){
                $unit->delete();
            }
            return response()->json([
                'error' => false,
                'message' => 'Birim başarıyla silindi.'
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

}
