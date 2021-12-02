<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
class ProductController extends Controller
{

    /**
     * Ürünleri listele
     */
    public function get(Request $request){
        $products = Product::all();
        $response=[];
        
        foreach ($products as $key => $value) {
            $discountPrice = 0;
            $discounts=[];
            foreach ($value->getDiscount->toArray() as $discount) {
                $newdiscount=$value->price*$discount['percent']/100;
                $discountPrice+=$newdiscount;
                array_push($discounts,[
                    'id'=>$discount['id'],
                    'name'=>$discount['name'],
                    'description' => $discount['description'],
                    'percent'=>$discount['percent'],
                    'price'=>$newdiscount
                ]);
            }
            array_push($response,[
                'product_id'=>$value->id,
                'name'=>$value->name,
                'price'=>$value->price,
                'description'=>$value->description,
                'discounts'=>$discounts,
                'discountPrice'=>$discountPrice,
                'images'=>array_map(function($item){
                    return [
                        'image_id'=>$item['id'],
                        'name'=>$item['name'],
                        'type'=>$item['type'],
                        'path'=>$item['path']
                    ];
                }, $value->getImage->toArray())
            ]);
        }
        return response()->json([
            'error'=>false,
            'message'=>'Ürünler başarı ile sorgulandı.',
            'products' => $response
        ],200);
    }

    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'description' => ($validation->getMessageBag())->messages()['description'] ?? 'success',
                'price' => ($validation->getMessageBag())->messages()['price'] ?? 'success',
                'category_id' => ($validation->getMessageBag())->messages()['category_id'] ?? 'success'
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
            $price = doubleval($request->price);
            $slugify = DataCrypter::slugify($request->name);
            $checkSlugs = Product::where('slug', 'like', "%" . $slugify . "%")->get();
            $slug = count($checkSlugs) > 0 ? $slugify . '-' . count($checkSlugs) : $slugify;
            $product = new Product;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $price;
            $product->stock = floor(intval($request->stock)) ?? 0;
            $product->category_id = $request->category_id;
            $product->image_id = $request->image ?? null;
            $product->discount_id = $request->discount ?? null;
            $product->slug = $slug;
            $product->save();
            return response()->json([
                'error' => false,
                'message' => 'Ürün başarıyla eklendi.',
                'product' => $product
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Ürün eklenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
    }

    /**
     * Ürünü güncelle
     */
    public function update(Request $request){
        $validation = Validator::make($request->all(), [
            'product_id' => 'required|numeric',
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:3',
            'price' => 'required',
            'category' => 'required',
            'image' => 'required',
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success',
                'description' => ($validation->getMessageBag())->messages()['description'] ?? 'success',
                'price' => ($validation->getMessageBag())->messages()['price'] ?? 'success',
                'category' => ($validation->getMessageBag())->messages()['category_id'] ?? 'success',
                'image' => ($validation->getMessageBag())->messages()['image'] ?? 'success',
                'product_id' => ($validation->getMessageBag())->messages()['product_id'] ?? 'success',
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
            $price = doubleval($request->price);
            $slugify = DataCrypter::slugify($request->name);
            $checkSlugs = Product::where('slug', 'like', "%" . $slugify . "%")->get();
            $product = Product::find($request->id);
            $slug = $product->name!=$request->name ? count($checkSlugs) > 0 ? $slugify . '-' . count($checkSlugs) : $slugify : $product->slug;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $price;
            $product->stock = floor(intval($request->stock??0));
            $product->category_id = $request->category_id;
            $product->image_id = $request->image ?? null;
            $product->discount_id = $request->discount ??null;
            $product->slug = $slug;
            $product->save();
            return response()->json([
                'error' => false,
                'message' => 'Ürün başarıyla güncellendi.',
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'category' => $product->category->name,
                    'image' => $product->getImage,
                    'discount' => $product->discount->name ?? null,
                    'slug' => $product->slug
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Ürün güncellenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
        return response()->json(['error' => true, 'message' => 'Ürün bulunamadı.'], 401);
    }
}
