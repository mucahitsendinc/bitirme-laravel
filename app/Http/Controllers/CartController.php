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
     * Sepeti getir
     */
    public function get(Request $request){
        try {
            $cart = ShoppingSession::where('user_id', $request->get('user')->id)->first();
            $cartItems = [];
            $total=0;
            foreach ($cart->getCart as $key => $value) {
                $product = $value->getProduct;
                $type= $product->getFirstImage['type'];
                $path= $product->getFirstImage['path'];
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
                        'id' => $current->id,
                        'discount' => $current->percent,
                        'name' => $current->name,
                        'description' => $current->description,
                    ]);
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
     * Sepete ekle
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
     * Sepet silme
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
     * Sepet ürün artır
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
     * Sepet ürün azalt
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
     * Sepet ürün çıkar
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
