<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{

    /**
     * @OA\GET(
     * path="/api/categories",
     * summary="Kategori Listele",
     * description="Var olan kategorileri listeler.",
     * operationId="getCategories",
     * tags={"Kategori"},
     * @OA\Parameter(
     *  name="count",
     *  in="query",
     *  description="Sayfa numarası",
     *  required=false,
     * ),
     */
    public function get(Request $request){
        try {

            if(isset($request->count)){
                $categories = Category::where('parent_id',null)->orderBy('id', 'desc')->limit($request->count)->get(['id','name','slug','image_id']);
            }else{
                $categories = Category::where('parent_id',null)->orderBy('id', 'desc')->get(['id','name','slug','image_id']);
            }
            $newCategories = [];
            foreach ($categories as $key => $value) {
                $newCategories[$key]['id'] = $value->id;
                $newCategories[$key]['name'] = $value->name;
                $newCategories[$key]['slug'] = $value->slug;
                $newCategories[$key]['image_id'] = $value->image_id;
                $newCategories[$key]['sub_categories'] = Category::where('parent_id',$value->id)->orderBy('id', 'desc')->get(['id','name','slug','image_id']);
            }
            return response()->json([
                'error' => false,
                'message' => 'Kategori listelendi.',
                'categories' => $newCategories
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error'=>true,'message'=>'Teknik bir hata oluştu','exception' => $ex->getMessage()], 400);
        }
        return response()->json(['error' => true, 'message' => 'Teknik bir hata oluştu', 'exception' => $ex->getMessage()], 400);
    }

    /**
     * @OA\POST(
     * path="/api/seller/category/add",
     * summary="Kategori oluştur",
     * description="Yeni bir kategori oluşturur, eğer parent_id gönderilmezse ana kategori gönderilirse, gönderilen parent_id'e göre eşleşen kategorinin alt kategorisi olur.",
     * operationId="sellerCategoryAdd",
     * tags={"Kategori"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Yeni bir kategori oluşturur.",
     *    @OA\JsonContent(
     *       required={"name"},
     *          @OA\Property(property="name", type="string", example="Elektronik" , description="Kategori adı"),
     *          @OA\Property(property="parent_id", type="integer", example="1", description="Eğer parent_id gönderilmezse ana kategori gönderilirse, gönderilen parent_id'e göre eşleşen kategorinin alt kategorisi olur."),
     *          @OA\Property(property="icon_id", type="integer", example="1", description="Kategori ikonu"),
     *          @OA\Property(property="description", type="string", example="Elektronik kategorisi"),
     *    ),
     * ),
     */
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

    /**
     * @OA\POST(
     * path="/api/seller/category/update",
     * summary="Kategori Güncelle",
     * description="Var olan bir kategoriyi günceller.",
     * operationId="sellerCategoryUpdate",
     * tags={"Kategori"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan bir kategoriyi günceller.",
     *    @OA\JsonContent(
     *       required={"name"},
     *          @OA\Property(property="name", type="string", example="Elektronik" , description="Kategori adı"),
     *          @OA\Property(property="parent_id", type="integer", example="1", description="Eğer parent_id gönderilmezse ana kategori gönderilirse, gönderilen parent_id'e göre eşleşen kategorinin alt kategorisi olur."),
     *          @OA\Property(property="icon_id", type="integer", example="1", description="Kategori ikonu"),
     *          @OA\Property(property="description", type="string", example="Elektronik kategorisi"),
     *    ),
     * ),
     */
    public function update(Request $request){
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
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->parent_id = $request->parent_id ?? null;
            $category->icon_id = $request->icon_id ?? null;
            $category->image_id = $request->image_id ?? null;
            $category->slug = $slug;
            $category->save();
            return response()->json([
                'error' => false,
                'message' => 'Kategori başarıyla güncellendi.',
                'category' => $category
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Kategori güncellenirken bir hata oluştu.', 'exception' => $ex], 401);
        }
    }
    /**
     * @OA\POST(
     * path="/api/seller/category/delete",
     * summary="Kategori Sil",
     * description="Var olan bir kategoriyi siler.",
     * operationId="sellerCategoryDelete",
     * tags={"Kategori"},
     * security={{"deha_token":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Var olan kategoriyi siler.",
     *    @OA\JsonContent(
     *       required={"category_id"},
     *          @OA\Property(property="category_id", type="integer", example="1" , description="Kategori Id"),
     *    ),
     * ),
     */
    public function delete(Request $request){
        $validation=Validator::make($request->all(),[
            'category_id'=>'required|integer'
        ]);
        if($validation->fails()){
            $messages = [
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
            $category = Category::find($request->category_id);
            $category->delete();
            return response()->json([
                'error' => false,
                'message' => 'Kategori başarıyla silindi.'
            ], 200);
        } catch (\Exception $ex) {
            return response()->json(['error' => true, 'message' => 'Kategori silinirken bir hata oluştu.', 'exception' => $ex], 401);
        }
        return response()->json(['error' => true, 'message' => 'Kategori silinirken bir hata oluştu.'], 401);
    }
}
