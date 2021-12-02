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
            ], 401);
        }
        try {
            $discount = new Discount;
            $discount->name = $request->name;
            $discount->percent = $request->percent;
            $discount->description = $request->description ?? null;
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
}
