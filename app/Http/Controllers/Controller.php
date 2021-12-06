<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Dehasoft E-Ticaret Çözümü",
 *    version="1.0.0",
 * @OA\SecurityScheme(
 *    securityScheme="API Key Auth",
 *    type="apiKey",
 *    in="header",
 *    name="Authorization",
 *  )
 * )
 * 
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
