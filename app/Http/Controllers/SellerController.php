<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\DataCrypter;

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
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'description' => ($validation->getMessageBag())->messages()['description'] ?? 'success',
                'price' => ($validation->getMessageBag())->messages()['price'] ?? 'success',
                'category_id' => ($validation->getMessageBag())->messages()['category_id'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message'=> 'Bu işlem için gerekli bilgiler eksik.',
                'validation'=> array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ], 401);
        }
        try {
            $price=doubleval($request->price);
            $slugify= DataCrypter::slugify($request->name);
            $checkSlugs= Product::where('slug', 'like', "%" . $slugify . "%")->get();
            $slug=count($checkSlugs)>0?$slugify.'-'.count($checkSlugs):$slugify;
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $price;
            $product->stock = floor(intval($request->stock)) ?? 0;
            $product->category_id = $request->category_id;
            $product->image_id = $request->image ?? null;
            $product->discount_id = $request->discount ?? null;
            $product->slug= $slug;
            $product->save();
            return response()->json([
                'error' => false,
                'message' => 'Ürün başarıyla eklendi.',
                'product' => $product
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true,'message'=> 'Ürün eklenirken bir hata oluştu.','exception'=>$ex], 401);
        }
    }
    public function create_category(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z1-9 ]+$/u|max:255'
        ]);
        if($validation->fails()){
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message'=> 'Bu işlem için gerekli bilgiler eksik.',
                'validation'=> array_filter($messages, function ($e) {
                    if ($e != 'success') {
                        return true;
                    }
                })
            ], 401);
        }

        try {
            $slugify= DataCrypter::slugify($request->name);
            $checkSlugs= Category::where('slug', 'like', "%" . $slugify . "%")->get();
            $slug=count($checkSlugs)>0?$slugify.'-'.count($checkSlugs):$slugify;
            $category = new Category;
            $category->name = $request->name;
            $category->parent_id= $request->parent_id ?? null;
            $category->icon_id= $request->icon_id ?? null;
            $category->image_id= $request->image_id ?? null;
            $category->slug= $slug;
            $category->save();
            return response()->json([
                'error' => false,
                'message' => 'Kategori başarıyla eklendi.',
                'category' => $category
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true,'message'=> 'Kategori eklenirken bir hata oluştu.','exception'=>$ex], 401);
        }

    }
    public function create_discount(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'percent' => 'required|numeric|min:0|max:100'
        ]);
        if($validation->fails()){
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'percent' => ($validation->getMessageBag())->messages()['percent'] ?? 'success'
            ];
            return response()->json([
                'error' => true,
                'message'=> 'Bu işlem için gerekli bilgiler eksik.',
                'validation'=> array_filter($messages, function ($e) {
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
            $discount->description=$request->description??null;
            $discount->save();
            return response()->json([
                'error' => false,
                'message' => 'İndirim başarıyla eklendi.',
                'discount' => $discount
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true,'message'=> 'İndirim eklenirken bir hata oluştu.','exception'=>$ex], 401);
        }

    }
}
