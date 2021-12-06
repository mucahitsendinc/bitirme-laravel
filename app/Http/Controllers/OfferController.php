<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductOffer;
use App\Models\CategoryOffer;
use App\Models\UserOffer;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/seller/offer/product/add",
     * summary="Ürüne Kampanya Tanımla",
     * description="Var olan ürüne var olan bir kampanya tanımlaması yapılır.",
     * operationId="offerProductAdd",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan ürüne var olan bir kampanya tanımlaması yapılır.",
     *    @OA\JsonContent(
     *       required={"product_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="product_id", type="integer", example="Ürün Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function create_product_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'product_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'product_id'=>($validation->getMessageBag())->messages()['product_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success'
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
            $product_offer = new ProductOffer();
            $product_offer->product_id = $request->product_id;
            $product_offer->discount_id = $request->discount_id;
            $product_offer->save();
            return response()->json(['success' => 'Seçili ürüne kampanya başarı ile uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/category/add",
     * summary="Kategoriye Kampanya Tanımla",
     * description="Var olan kategoriye var olan bir kampanya tanımlaması yapılır.",
     * operationId="offerCategoryAdd",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kategoriye var olan bir kampanya tanımlaması yapılır.",
     *    @OA\JsonContent(
     *       required={"category_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="category_id", type="integer", example="Kategori Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function create_category_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'category_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'category_id'=>($validation->getMessageBag())->messages()['category_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success'
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
            $category_offer = new CategoryOffer();
            $category_offer->category_id = $request->category_id;
            $category_offer->discount_id = $request->discount_id;
            $category_offer->save();
            return response()->json(['success' => 'Seçili kategoride kampanya başarı ile uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/user/add",
     * summary="Kullanıcıya Kampanya Tanımla",
     * description="Var olan kullanıcıya var olan bir kampanya tanımlaması yapılır.",
     * operationId="offerUserAdd",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kullanıcıya var olan bir kampanya tanımlaması yapılır.",
     *    @OA\JsonContent(
     *       required={"user_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="user_id", type="integer", example="Kullanıcı Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function create_user_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'user_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'user_id'=>($validation->getMessageBag())->messages()['user_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success'
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
            $user_offer = new UserOffer();
            $user_offer->user_id = $request->user_id;
            $user_offer->discount_id = $request->discount_id;
            $user_offer->save();
            return response()->json(['success' => 'Seçili kullanıcıya kampanya başarı ile uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/product/update",
     * summary="Kategori Kampanya Güncelle",
     * description="Var olan üründeki var olan bir kampanyayı günceller.",
     * operationId="offerProductUpdate",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan üründeki var olan bir kampanyayı günceller.",
     *    @OA\JsonContent(
     *       required={"product_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="product_id", type="integer", example="Ürün Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function update_product_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'product_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
            'offer_id'=>'required|numeric'
        ]);
        if($validation->fails()){
            $messages=[
                'product_id'=>($validation->getMessageBag())->messages()['product_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success',
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success'
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
            $product_offer = ProductOffer::where('id', $request->offer_id)->first();
            $product_offer->discount_id = $request->discount_id;
            $product_offer->product_id = $request->product_id;
            $product_offer->save();
            return response()->json(['success' => 'Seçili ürüne kampanya başarı ile güncellenerek uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/category/update",
     * summary="Kategoriye Kampanya Tanımla",
     * description="Var olan kategorideki var olan bir kampanyayı günceller.",
     * operationId="offerCategoryUpdate",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kategorideki var olan bir kampanyayı günceller.",
     *    @OA\JsonContent(
     *       required={"category_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="category_id", type="integer", example="Ürün Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function update_category_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'category_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
            'offer_id'=>'required|numeric'
        ]);
        if($validation->fails()){
            $messages=[
                'category_id'=>($validation->getMessageBag())->messages()['category_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success',
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success'
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
            $category_offer = CategoryOffer::where('id', $request->offer_id)->first();
            $category_offer->discount_id = $request->discount_id;
            $category_offer->category_id = $request->category_id;
            $category_offer->save();
            return response()->json(['success' => 'Seçili kategoride kampanya başarı ile güncellenerek uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/user/update",
     * summary="Kullanıcı Kampanya Tanımla",
     * description="Var olan kullanıcıdaki var olan bir kampanyayı günceller.",
     * operationId="offerUserUpdate",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kullanıcıdaki var olan bir kampanyayı günceller.",
     *    @OA\JsonContent(
     *       required={"user_id","discount_id"},
     *          @OA\Property(property="discount_id", type="integer", example="Kampanya Id"),
     *          @OA\Property(property="user_id", type="integer", example="Ürün Id"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kampanya tanımlandı",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kampanya tanımlandı."),
     *        )
     *     )
     * )
     */
    public function update_user_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'user_id'=>'required|numeric',
            'discount_id'=>'required|numeric',
            'offer_id'=>'required|numeric'
        ]);
        if($validation->fails()){
            $messages=[
                'user_id'=>($validation->getMessageBag())->messages()['user_id'] ?? 'success',
                'discount_id'=>($validation->getMessageBag())->messages()['discount_id'] ?? 'success',
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success'
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
            $user_offer = UserOffer::where('id', $request->offer_id)->first();
            $user_offer->discount_id = $request->discount_id;
            $user_offer->user_id = $request->user_id;
            $user_offer->save();
            return response()->json(['success' => 'Seçili kullanıcıya kampanya başarı ile güncellenerek uygulandı.']);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/product/delete",
     * summary="Ürün Kampanya Sil",
     * description="Var olan üründeki var olan bir kampanyayı siler.",
     * operationId="offerProductDelete",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan üründeki var olan bir kampanyayı siler.",
     *    @OA\JsonContent(
     *       required={"offer_id"},
     *          @OA\Property(property="offer_id", type="integer", example="Kampanya Tanım Id"),
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
    public function delete_product_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'offer_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success',
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
            $product_offer = ProductOffer::where('id',$request->offer_id)->first();
            if($product_offer){
                $product_offer->delete();
                return response()->json(['success' => 'Seçili üründen kampanya başarı ile kaldırıldı.']);
            }
            return response()->json(['error' => true, 'message' => 'Seçili üründe kampanya bulunamadı.'], 400);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/category/delete",
     * summary="Kategori Kampanya Sil",
     * description="Var olan kategorideki var olan bir kampanyayı siler.",
     * operationId="offerCategoryDelete",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kategorideki var olan bir kampanyayı siler.",
     *    @OA\JsonContent(
     *       required={"offer_id"},
     *          @OA\Property(property="offer_id", type="integer", example="Kampanya Tanım Id"),
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
    public function delete_category_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'offer_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success',
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
            $category_offer = CategoryOffer::where('id',$request->offer_id)->first();
            if($category_offer){
                $category_offer->delete();
                return response()->json(['success' => 'Seçili kategoriden kampanya başarı ile kaldırıldı.']);
            }
            return response()->json(['error' => true, 'message' => 'Seçili kategoride kampanya bulunamadı.'], 400);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }

    /**
     * @OA\Post(
     * path="/api/seller/offer/user/delete",
     * summary="Kullanıcı Kampanya Sil",
     * description="Var olan kullanıcıdaki var olan bir kampanyayı siler.",
     * operationId="offerUserDelete",
     * tags={"Kampanya"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kullanıcıdaki var olan bir kampanyayı siler.",
     *    @OA\JsonContent(
     *       required={"offer_id"},
     *          @OA\Property(property="offer_id", type="integer", example="Kampanya Tanım Id"),
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
    public function delete_user_offer(Request $request){
        $validation=Validator::make($request->all(),[
            'offer_id'=>'required|numeric',
        ]);
        if($validation->fails()){
            $messages=[
                'offer_id'=>($validation->getMessageBag())->messages()['offer_id'] ?? 'success',
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
            $user_offer = UserOffer::where('id',$request->offer_id)->first();
            if($user_offer){
                $user_offer->delete();
                return response()->json(['success' => 'Seçili kullanıcıdan kampanya başarı ile kaldırıldı.']);
            }
            return response()->json(['error' => true, 'message' => 'Seçili kullanıcıda kampanya bulunamadı.'], 400);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.','exception'=>$ex], 403);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu.'], 400);
    }
}
