<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckJWTAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(['error' => 'Xac thuc khong thanh cong'], 401);
            }
            if ($user->role !== 'admin') {
                return response()->json(['error' => 'Bạn không có quyền truy cập'], 403);
            }
            return $next($request);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $th->getMessage()], 500);
        }
    }
}
