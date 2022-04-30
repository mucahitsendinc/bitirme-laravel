<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
use Illuminate\Http\Request;
use App\Models\Route;

class MainController extends Controller
{
    /**
     * @OA\GET(
     * path="/api/routes",
     * summary="Arayüz için gerekli rotalar.",
     * description="Ön yüz için kullanılacak rotalar.",
     * operationId="routesGet",
     * tags={"Arayüz"},
     * security={{"deha_token":{}}},
     * @OA\Response(
     *    response=200,
     *    description="Rotalar başarı ile sorgulandı.",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Önyüz rotaları başarı ile sorgulandı."),
     *        )
     *     )
     * )
     */
    public function getRoutes(Request $request){

        try {
            $routes=Route::get(['label','exact','path','auth']);
            return response()->json([
                'error'=>false,
                'message' => 'Rota başarı ile sorgulandı.',
                'routes'=>$routes
            ],200);
        }catch (\Exception $ex){
            return response()->json([
                'error'=>true,
                'message' => 'Rota sorgulanırken bir hata oluştu.',
                'exception'=>$ex->getMessage()
            ],400);
        }
        return response()->json([
            'error'=>true,
            'message' => 'Rota sorgulanırken bir hata oluştu.',
        ],400);

    }

    public static function getAllRoutes(){
        return Route::get(['label','exact','path','auth']);
    }
}
