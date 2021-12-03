<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Discount;

class DiscountController extends Controller
{
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
            $discount->max_uses_user=
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
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discounts',
            'name' => 'required',
            'percent' => 'required|numeric|min:0|max:100',
            'active' => 'required|boolean'
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
            $discount->active =  $request->active ? 1 : 0;
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
