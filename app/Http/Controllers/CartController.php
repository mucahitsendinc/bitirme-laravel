<?php

namespace App\Http\Controllers;

use App\Models\ShoppingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\UserCart;

class CartController extends Controller
{

    /**
     * @OA\GET(
     * path="/api/user/cart/get",
     * summary="Sepeti Getir",
     * description="Kullanıcının sepetini listeler.",
     * operationId="userCartGet",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı sepeti başarı ile sorgulandı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı sepeti başarı ile listelendi"),
     *        )
     *     )
     * )
     */
    public function get(Request $request){
        try {
            $cart = ShoppingSession::where('user_id', $request->get('user')->id)->first();
            if($cart==null){
                return response()->json([
                    'error' => false,
                    'message' => 'Sepet başarı ile sorgulandı.',
                    'total' =>0,
                    'cart' => []
                ], 200);
            }
            $cartItems = [];
            $total=0;
            foreach ($cart->getCart as $key => $value) {
                $product = $value->getProduct;
                $type= $product->getFirstImage['type']??'url';
                $path= $product->getFirstImage['path']??'';
                $price= $product->price * $value->quantity;
                $total+= $price;
                $discountPrice = 0;
                $discounts = [];
                $list = $product->getDiscounts;
                for ($i = 0; $i < count($list); $i++) {
                    $current = $list[$i]->getDiscount;
                    $newdiscount = $value->quantity * $product->price * $current->percent / 100;
                    $discountPrice += $newdiscount;
                    array_push($discounts, [
                        'id' => "product-".$current->id,
                        'discount' => $current->percent,
                        'name' => $current->name,
                        'description' => $current->description,
                    ]);
                }
                if($value->getCategory!=null){
                    $list = $value->getCategory->getDiscounts;
                    for ($i = 0; $i < count($list); $i++) {
                        $current = $list[$i]->getDiscount;
                        $newdiscount = $value->price * $current->percent / 100;
                        $discountPrice += $newdiscount;
                        array_push($discounts, [
                            'id' => "category-" . $current->id,
                            'discount' => $current->percent,
                            'name' => $current->name,
                            'description' => $current->description,
                        ]);
                    }
                }

                array_push($cartItems, [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->getCategory->name,
                    'price' => $product->price,
                    'quantity' => $value->quantity,
                    'total' => $price,
                    'imageType' => $type,
                    'image'=> $path,
                    'discountPrice' => $discountPrice,
                    'discounts' => $discounts,
                ]);
            }
            return response()->json([
                'error' => false,
                'message' => 'Sepet başarı ile sorgulandı.',
                'total' => $total,
                'cart' => $cartItems
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'error' => true,
                'message' => 'Sepet sorgulanırken bir hata oluştu.',
                'error_message' => $ex->getMessage()
            ], 400);
        }
        return response()->json([
            'error' => true,
            'message' => 'Sepet sorgulanırken bir hata oluştu.'
        ], 400);
    }

    /**
     * @OA\POST(
     * path="/api/user/cart/add",
     * summary="Sepete Ekle",
     * description="Kullanıcının sepetine ekleme yapar.",
     * operationId="userCartAdd",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Sepeti getirir.",
     *    @OA\JsonContent(
     *       required={"products"},
     *          @OA\Property(property="products", type="array",
     *              @OA\Items(type="object", format="object",
     *                      @OA\Property(property="product_id", type="integer", example="1"),
     *                      @OA\Property(property="quantity", type="integer", example="1"),
     *                  ),
     *              ),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcı sepetine ürün eklendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Kullanıcı sepetine ürün eklendi."),
     *        )
     *     )
     * )
     */
    public function add(Request $request){
        $validation=Validator::make($request->all(),[
            'products'=>'required',
        ]);
        if($validation->fails()){
            $messages=[
                'products'=>($validation->getMessageBag())->messages()['products'] ?? 'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Gerekli bilgiler eksik',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ],400);
        }

        try {
            $products = $request->products;
            $user_id= $request->get('user')->id;
            $total=0;
            $productList=[];
            foreach ($products as $key => $value) {
                $value=(object)$value;
                $product=Product::find($value->product_id);
                if(!$product){
                    return response()->json([
                        'error'=>true,
                        'message'=>'Ürün bulunamadı',
                    ],400);
                }
                if($value->quantity>$product->stock){
                    return response()->json([
                        'error'=>true,
                        'message'=>'Ürün stokta yeteri kadar olmadığı için işlem iptal edildi.',
                    ],400);
                }
                array_push($productList,['product_id'=>$product->id,'quantity'=>$value->quantity]);
                $total+=$product->price*$value->quantity;
            }

            $checkSession=ShoppingSession::where('user_id',$user_id)->first();
            $session_id=null;
            if($checkSession==null){
                $session=new ShoppingSession();
                $session->user_id=$request->get('user')->id;
                $session->total=$total;
                $session->save();
                $session_id=$session->id;
            }else{
                $checkSession->total=$checkSession->total+$total;
                $checkSession->save();
                $session_id=$checkSession->id;
            }
            $allProducts=[];
            foreach ($productList as $key => $value) {
                $checkProduct=UserCart::where('product_id',$value['product_id'])->where('session_id',$session_id)->first();
                if($checkProduct!=null){
                    $checkProduct->quantity=$value['quantity']+$checkProduct->quantity;
                    $checkProduct->save();
                }else{
                    array_push($allProducts,['product_id'=>$value['product_id'],'quantity'=>$value['quantity'],'session_id'=>$session_id]);
                }
            }
            $saveCart=UserCart::insert($allProducts);
            return response()->json([
                'error'=>false,
                'message'=>'Ürünler sepete eklendi',
                'total'=>$total,
                'cart_id'=>$session_id
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
    }

    /**
     * @OA\POST(
     * path="/api/user/cart/update",
     * summary="Sepeti Güncelleyerek Ekle",
     * description="Kullanıcının sepetine ekleme yapar, önceden sepette olanları yok eder.",
     * operationId="userCartUpdate",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Sepeti Günceller.",
     *    @OA\JsonContent(
     *       required={"products"},
     *          @OA\Property(property="products", type="array",
     *              @OA\Items(type="object", format="object",
     *                      @OA\Property(property="product_id", type="integer", example="1"),
     *                      @OA\Property(property="quantity", type="integer", example="1"),
     *                  ),
     *              ),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ürün sepete eklendi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ürün sepete eklendi."),
     *        )
     *     )
     * )
     */
    public function update(Request $request){
        $validation=Validator::make($request->all(),[
            'products'=>'required'
        ]);
        if($validation->fails()){
            $messages=[
                'products'=>($validation->getMessageBag())->messages()['products'] ?? 'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Gerekli bilgiler eksik',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ],400);
        }
        try {
            $products = (json_decode($request->products))->products;
            $user_id= $request->get('user')->id;
            $total=0;
            $productList=[];
            foreach ($products as $key => $value) {
                $product=Product::find($value->product_id);
                if(!$product){
                    return response()->json([
                        'error'=>true,
                        'message'=>'Ürün bulunamadı',
                    ],400);
                }
                if($value->quantity>$product->stock){
                    return response()->json([
                        'error'=>true,
                        'message'=>'Ürün stokta yeteri kadar olmadığı için işlem iptal edildi.',
                    ],400);
                }
                $key=array_search($value->product_id,array_column($productList,'product_id'));
                if($key!==false){
                    $productList[$key]['quantity']+=$value->quantity;
                }else{
                    array_push($productList,['product_id'=>$value->product_id,'quantity'=>$value->quantity]);
                }
                $total+=$product->price*$value->quantity;
            }
            $checkSession=ShoppingSession::where('user_id',$user_id)->first();
            $session_id=null;
            if($checkSession==null){
                $session=new ShoppingSession();
                $session->user_id=$request->get('user')->id;
                $session->total=$total;
                $session->save();
                $session_id=$session->id;
            }else{
                $checkSession->total=$total;
                $checkSession->save();
                $session_id=$checkSession->id;
            }
            $allProducts=[];
            UserCart::where('session_id', $session_id)->delete();
            foreach ($productList as $key => $value) {
                array_push($allProducts,['product_id'=>$value['product_id'],'quantity'=>$value['quantity'],'session_id'=>$session_id]);
            }
            $saveCart=UserCart::insert($allProducts);
            if($saveCart){
                return response()->json([
                    'error'=>false,
                    'message'=>'Ürünler sepete eklendi',
                    'total'=>$total,
                    'cart_id'=>$session_id
                ],200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu',
        ],400);
    }

    /**
     * @OA\POST(
     * path="/api/user/cart/delete",
     * summary="Sepeti Sil",
     * description="Kullanıcının sepetini tamamen siler.",
     * operationId="userCartDelete",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Kullanıcının sepeti silindi.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sepet silindi."),
     *        )
     *     )
     * )
     */
    public function delete(Request $request){
        try {
            $user_id= $request->get('user')->id;
            $checkSession=ShoppingSession::where('user_id',$user_id)->delete();
            return response()->json([
                'error'=>false,
                'message'=>'Sepet silindi',
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu',
        ],400);
    }

    /**
     * @OA\POST(
     * path="/api/user/cart/increment",
     * summary="Sepetteki Ürünü Arttır",
     * description="Kullanıcının sepetinde bulunan ürünü arttırır.",
     * operationId="userCartIncrement",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Sepetteki ürünü arttırır.",
     *    @OA\JsonContent(
     *       required={"product_id"},
     *          @OA\Property(property="product_id", type="integer", example="1" , description="Ürün Id"),
     *          @OA\Property(property="quantity", type="integer", example="1" , description="Ürün Adeti"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ürün arttırıldı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ürün arttırıldı."),
     *        )
     *     )
     * )
     */
    public function increment(Request $request){
        $validation=Validator::make($request->all(),[
            'product_id'=>'required'
        ]);
        if($validation->fails()){
            $messages=[
                'product_id'=>($validation->getMessageBag())->messages()['product_id'] ?? 'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Gerekli bilgiler eksik',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ],400);
        }
        try {
            $user_id= $request->get('user')->id;
            $checkSession=ShoppingSession::where('user_id',$user_id)->first();
            if($checkSession==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Sepet bulunamadı',
                ],400);
            }
            $checkProduct=UserCart::where('product_id',$request->product_id)->where('session_id',$checkSession->id)->first();
            if($checkProduct==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Ürün bulunamadı',
                ],400);
            }

            $newQuantity= $checkProduct->quantity + ($request->quantity ?? 1);
            $stockCheck = Product::find($request->product_id);
            if ($newQuantity > $stockCheck->stock) {
                return response()->json([
                    'error' => true,
                    'message' => 'Ürün stokta yeteri kadar olmadığı için işlem iptal edildi.',
                ], 400);
            }
            $checkProduct->quantity= $newQuantity;
            $checkProduct->save();

            return response()->json([
                'error'=>false,
                'message'=>'Ürün sepete eklendi',
                'newQuantity'=>$newQuantity,
                'cart_id'=> $checkSession->id
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu',
        ],400);

    }

    /**
     * @OA\POST(
     * path="/api/user/cart/decrement",
     * summary="Sepetteki Ürünü Azalt",
     * description="Kullanıcının sepetinde bulunan ürünü azaltır.Quantity gönderilmezse 1 adet azaltılır.",
     * operationId="userCartDecrement",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Sepetteki ürünü azaltır.",
     *    @OA\JsonContent(
     *       required={"product_id"},
     *          @OA\Property(property="product_id", type="integer", example="1" , description="Ürün Id"),
     *          @OA\Property(property="quantity", type="integer", example="1" , description="Ürün Adeti"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ürün sepete azaltıldı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ürün sepetten azaltıldı."),
     *        )
     *     )
     * )
     */
    public function decrement(Request $request){
        $validation=Validator::make($request->all(),[
            'product_id'=>'required'
        ]);
        if($validation->fails()){
            $messages=[
                'product_id'=>($validation->getMessageBag())->messages()['product_id'] ?? 'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Gerekli bilgiler eksik',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ],400);
        }
        try {
            $user_id= $request->get('user')->id;
            $checkSession=ShoppingSession::where('user_id',$user_id)->first();
            if($checkSession==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Sepet bulunamadı',
                ],400);
            }
            $checkProduct=UserCart::where('product_id',$request->product_id)->where('session_id',$checkSession->id)->first();
            if($checkProduct==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Ürün bulunamadı',
                ],400);
            }
            $newQuantity= $checkProduct->quantity - ($request->quantity ?? 1);
            if($newQuantity<=0){
                $checkProduct->delete();
                return response()->json([
                    'error'=>false,
                    'message'=>'Ürün sepetten silindi',
                    'newQuantity'=>0,
                    'cart_id'=> $checkSession->id
                ],200);
            }
            $checkProduct->quantity= $newQuantity;
            $checkProduct->save();

            return response()->json([
                'error'=>false,
                'message'=>'Ürün sepetten '.($request->quantity ?? 1).' azaltıldı',
                'newQuantity'=>$newQuantity,
                'cart_id'=> $checkSession->id
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu',
        ],400);
    }

    /**
     * @OA\POST(
     * path="/api/user/cart/extraction",
     * summary="Sepetteki Ürünü Çıkart",
     * description="Kullanıcının sepetinde bulunan ürünü tamamen çıkartır.",
     * operationId="userCartExtraction",
     * tags={"Kullanıcı Sepet"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Sepetteki ürünü kaldırır.",
     *    @OA\JsonContent(
     *       required={"product_id"},
     *          @OA\Property(property="product_id", type="integer", example="1" , description="Ürün Id")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Ürün sepetten kaldırıldı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Ürün sepetten kaldırıldı."),
     *        )
     *     )
     * )
     */
    public function extraction(Request $request){
        $validation=Validator::make($request->all(),[
            'product_id'=>'required'
        ]);
        if($validation->fails()){
            $messages=[
                'product_id'=>($validation->getMessageBag())->messages()['product_id'] ?? 'success',
            ];
            return response()->json([
                'error'=>true,
                'message'=>'Gerekli bilgiler eksik',
                'validation' => array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ],400);
        }
        try {
            $user_id= $request->get('user')->id;
            $checkSession=ShoppingSession::where('user_id',$user_id)->first();
            if($checkSession==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Sepet bulunamadı',
                ],400);
            }
            $checkProduct=UserCart::where('product_id',$request->product_id)->where('session_id',$checkSession->id)->first();
            if($checkProduct==null){
                return response()->json([
                    'error'=>true,
                    'message'=>'Ürün bulunamadı',
                ],400);
            }
            $checkProduct->delete();

            return response()->json([
                'error'=>false,
                'message'=>'Ürün sepetten silindi',
                'cart_id'=> $checkSession->id
            ],200);
        } catch (\Exception $ex) {
            return response()->json([
                'error'=>true,
                'message'=>'Teknik bir hata oluştu',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message'=>'Teknik bir hata oluştu',
        ],400);
    }
}
