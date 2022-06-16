<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Discount;

class DiscountController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/seller/discount/get",
     * summary="İndirimleri Listele",
     * description="Var olan indirimleri listeler.",
     * operationId="getDiscounts",
     * tags={"Kampanya"},
     * @OA\Response(
     *    response=200,
     *    description="İndirimler listelendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="İndirimler listelendi."),
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        try {
            return response()->json([
                'error' => false,
                'message' => 'İndirimler listelendi.',

                'discounts' => Discount::all()
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error'=>true,'message'=>'Teknik bir hata oluştu','exception' => $ex->getMessage()], 400);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu', 'exception' => $ex->getMessage()], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/discount/add",
     * summary="Kampanya Oluştur",
     * description="Genel kampanya oluştur, daha sonra ürün, kullanıcı veya kategoriye tanımlanabilir.",
     * operationId="discountAdd",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni kampanya oluşturulur.",
     *    @OA\JsonContent(
     *       required={"name","percent"},
     *          @OA\Property(property="name", type="string", example="Kampanya Adı"),
     *          @OA\Property(property="description", type="string", example="Açıklama"),
     *          @OA\Property(property="percent", type="integer", example="10"),
     *          @OA\Property(property="start_date", type="string", example="2019-01-01"),
     *          @OA\Property(property="end_date", type="string", example="2019-01-01"),
     *          @OA\Property(property="coupon", type="string", example="KUPON-KODU"),
     *          @OA\Property(property="max_uses", type="integer", example="En fazla kullanım sayısı"),
     *          @OA\Property(property="max_uses_user", type="integer", example="Kullanıcı için en fazla kullanım sayısı"),
     *          @OA\Property(property="max_discount_amount", type="integer", example="En fazla indirim tutarı"),
     *          @OA\Property(property="max_discount_amount_user", type="integer", example="Kullanıcı bazlı en fazla indirim tutarı"),
     *          @OA\Property(property="min_order_amount", type="integer", example="İndirim için gerekli minimum tutar"),
     *          @OA\Property(property="active", type="boolean", example="Oluşturulunca aktif olacak mı?"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya oluşturuldu.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya oluşturuldu."),
     *        )
     *     )
     * )
     */
    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'percent' => 'required|numeric|min:0|max:100'
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'percent' => ($validation->getMessageBag())->messages()['percent'] ?? 'success'
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
            $discount = new Discount;
            $discount->name = $request->name;
            $discount->percent = $request->percent;
            $discount->description = $request->description ?? null;
            $discount->start_date= $request->start_date ?? null;
            $discount->end_date = $request->end_date ?? null;
            $discount->coupon = $request->coupon ?? null;
            $discount->max_uses =  $request->max_uses ?? null;
            $discount->max_uses_user= $request->max_uses_user ?? null;
            $discount->max_discount_amount =  $request->max_discount_amount ?? null;
            $discount->max_discount_amount_user =  $request->max_discount_amount_user ?? null;
            $discount->min_order_amount =  $request->min_order_amount ?? null;
            $discount->active =  $request->active ? 1 : 0;
            $discount->save();
            return response()->json([
                'error' => false,
                'message' => 'İndirim başarıyla eklendi.',
                'discount' => $discount
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'İndirim eklenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
    }

    /**
     * @OA\Post(
     * path="/api/seller/discount/update",
     * summary="Kampanya Güncelle",
     * description="Genel kampanyayı güncelle.",
     * operationId="discountUpdate",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir kampanyayı günceller. Göndermediğiniz alanlar silinir.",
     *    @OA\JsonContent(
     *       required={"name","percent","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="name", type="string", example="Kampanya Adı"),
     *          @OA\Property(property="description", type="string", example="Açıklama"),
     *          @OA\Property(property="percent", type="integer", example="10"),
     *          @OA\Property(property="start_date", type="string", example="2019-01-01"),
     *          @OA\Property(property="end_date", type="string", example="2019-01-01"),
     *          @OA\Property(property="coupon", type="string", example="KUPON-KODU"),
     *          @OA\Property(property="max_uses", type="integer", example="En fazla kullanım sayısı"),
     *          @OA\Property(property="max_uses_user", type="integer", example="Kullanıcı için en fazla kullanım sayısı"),
     *          @OA\Property(property="max_discount_amount", type="integer", example="En fazla indirim tutarı"),
     *          @OA\Property(property="max_discount_amount_user", type="integer", example="Kullanıcı bazlı en fazla indirim tutarı"),
     *          @OA\Property(property="min_order_amount", type="integer", example="İndirim için gerekli minimum tutar"),
     *          @OA\Property(property="active", type="boolean", example="Güncellenince aktif olacak mı?"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya güncellendi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya güncellendi."),
     *        )
     *     )
     * )
     */
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discounts',
            'name' => 'required',
            'percent' => 'required|numeric|min:0|max:100'
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'percent' => ($validation->getMessageBag())->messages()['percent'] ?? 'success',
                'active' => ($validation->getMessageBag())->messages()['active'] ?? 'success'
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
            $discount = Discount::find($request->discount_id);
            $discount->name = $request->name;
            $discount->percent = $request->percent;
            $discount->description = $request->description ?? null;
            $discount->start_date= $request->start_date ?? null;
            $discount->end_date = $request->end_date ?? null;
            $discount->coupon = $request->coupon ?? null;
            $discount->max_uses_user =  $request->max_uses_user ?? null;
            $discount->max_uses =  $request->max_uses ?? null;
            $discount->max_discount_amount =  $request->max_discount_amount ?? null;
            $discount->max_discount_amount_user =  $request->max_discount_amount_user ?? null;
            $discount->min_order_amount =  $request->min_order_amount ?? null;
            $discount->active =  ($request->active??false) ? 1 : 0;
            $discount->save();
            return response()->json([
                'error' => false,
                'message' => 'İndirim başarıyla güncellendi.',
                'discount' => $discount
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'İndirim güncellenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
        return response()->json(['error' => true, 'message' => 'İndirim bulunamadı.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/discount/delete",
     * summary="Kampanya Sil",
     * description="Genel kampanyayı sil.",
     * operationId="discountDelete",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir kampanyayı siler.",
     *    @OA\JsonContent(
     *       required={"name","percent","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya silindi",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya silindi."),
     *        )
     *     )
     * )
     */
    public function delete(Request $request){
        $validation = Validator::make($request->all(), [
            'discount_id' => 'required|numeric'
        ]);
        if($validation->fails()){
            $messages = [
                'discount_id' => ($validation->getMessageBag())->messages()['discount_id'] ?? 'success'
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
            $discount = Discount::find($request->discount_id);
            $discount->delete();
            return response()->json([
                'error' => false,
                'message' => 'İndirim başarıyla silindi.'
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'İndirim silinirken bir hata oluştu.', 'exception' => $ex], 401);
        }
    }
}
