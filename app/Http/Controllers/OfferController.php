<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductOffer;
use App\Models\CategoryOffer;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    /**
     * Ürün kampanya tanımla
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
     * Kategori kampanya tanımla
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
     * Ürün kampanya güncelle
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
     * Kategori kampanya güncelle
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
     * Ürün kampanya sil
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
     * Kategori kampanya sil
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
}
