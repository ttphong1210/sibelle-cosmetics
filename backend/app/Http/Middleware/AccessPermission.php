<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccessPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $request->merge([
                'auth_user' => $user
            ]);
        } catch (TokenExpiredException $e) {
            return $this->tokenExpiredResponse();
        } catch (TokenInvalidException $e) {
            return $this->tokenInvalidResponse();
        } catch (JWTException $e) {
            return $this->unauthenticatedResponse();
        }
        if ($user && $user->hasRole($roles)) {
            return $next($request);
        }
        return $this->unauthorizedResponse($request);
    }

    /**
     * Response not login authenticated
     */
    protected function unauthenticatedResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Chưa đăng nhập hoặc token không hợp lệ',
            'error_code' => 'UNAUTHENTICATED'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response token expired
     */
    protected function tokenExpiredResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Token đã hết hạn',
            'error_code' => 'TOKEN_EXPIRED'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response token invalid
     */
    protected function tokenInvalidResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Token không hợp lệ',
            'error_code' => 'TOKEN_INVALID'
        ], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Response unauthorized
     */
    protected function unauthorizedResponse($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Bạn không có quyền truy cập',
                'error_code' => 'ACCESS_DENIED'
            ], Response::HTTP_FORBIDDEN);
        }
        return response()->json([
            'message' => 'Bạn không có quyền truy cập'
        ]);
    }
}
