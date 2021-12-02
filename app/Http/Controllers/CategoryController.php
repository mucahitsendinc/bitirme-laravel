<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{

    public function create(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|regex:/^[a-zA-Z1-9 ]+$/u|max:255'
        ]);
        if ($validation->fails()) {
            $messages = [
                'name' => ($validation->getMessageBag())->messages()['name'] ?? 'success'
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
            $slugify = DataCrypter::slugify($request->name);
            $checkSlugs = Category::where('slug', 'like', "%" . $slugify . "%")->get();
            $slug = count($checkSlugs) > 0 ? $slugify . '-' . count($checkSlugs) : $slugify;
            $category = new Category;
            $category->name = $request->name;
            $category->parent_id = $request->parent_id ?? null;
            $category->icon_id = $request->icon_id ?? null;
            $category->image_id = $request->image_id ?? null;
            $category->slug = $slug;
            $category->save();
            return response()->json([
                'error' => false,
                'message' => 'Kategori başarıyla eklendi.',
                'category' => $category
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Kategori eklenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
    }
}
