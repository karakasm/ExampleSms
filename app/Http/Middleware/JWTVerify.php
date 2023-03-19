<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\Response;

class JWTVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->bearerToken()){
            return \response()->json([
                'data' => null,
                'status' => 'error',
                'code' => '401',
                'message' => 'Bearer Token is not found.',
                ],401);
        }

        try {
            $token = JWT::decode($request->bearerToken(),new Key(env('TOKEN_KEY'),'HS256'));
        }
        catch (ExpiredException $exception){
            return \response()->json([
                'data' => null,
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => '401',
            ],401);
        }
        catch (Exception $exception){
            return \response()->json([
                'data' => null,
                'status' => 'error',
                'message' => $exception->getMessage(),
                'code' => '401',
            ], 401);
        }

        return $next($request);
    }
}
