<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if(!$token = JWTAuth::getToken()){
                return response()->json([
                    'success' => false,
                    'message' => 'Token không được cung cấp'
                ], 401);
            }
            $user = JWTAuth::authenticate($token);
            if(!$user){
                return response()->json([
                    'success' => false,
                    'message' => 'User không tồn tại'
                ], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Phiên đăng nhập đã hết hạn',
                'error_code' => 'TOKEN_EXPIRED'
            ], 401);
        }catch(TokenInvalidException $e){
            return response()->json([
                'success' => false,
                'message' => 'Token không hợp lệ',
                'error_code' => 'TOKEN_INVALID'
            ], 401);
        }catch(JWTException $e){
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xác thực token',
                'error_code' => 'TOKEN_ERROR'
            ], 500);
        }
        return $next($request);
    }
}
    