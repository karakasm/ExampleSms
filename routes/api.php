<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Firebase\JWT\JWT;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);

Route::get('generate-token',function (Request $request){
    $issuedAt = new  DateTimeImmutable();
    $exp = $issuedAt->modify('+5 minutes')->getTimestamp();
    $payload = [
        'token_id' => base64_encode(random_bytes(16)),
        'iat' => $issuedAt->getTimestamp(),
        'exp' => $exp,
        'data' => [
            'username' => $request->username,
        ],
    ];

    $token = JWT::encode($payload, env('TOKEN_KEY'), 'HS256');
    return $token;
});

Route::middleware('jwt.verify')->group(function (){
    Route::get('index', function (Request $request){
       return "Welcome to Homepage";
    });

    Route::apiResource('users.messages', SmsController::class)->only(['index','store','create','show']);
});
