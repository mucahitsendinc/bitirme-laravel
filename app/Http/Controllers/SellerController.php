<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    public function create_product(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);
        if($validation->fails()){
            return response()->json(['error' => true,'message'=> 'Bu işlem için gerekli bilgiler eksik.'], 401);
        }
        try {
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock ?? 0;
            $product->category_id = $request->category_id;
            $product->image_id = $request->image ?? null;
            $product->discount_id = $request->discount ?? null;
            $product->save();
        } catch (\Exception $ex) {
            return response()->json(['error' => true,'message'=> 'Ürün eklenirken bir hata oluştu.','exception'=>$ex], 401);
        }
    }
}
