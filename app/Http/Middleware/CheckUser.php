<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckUser
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
            if (!empty($roles) && !in_array($user->role, $roles)) {
                return response()->json(['error' => 'Bạn không có quyền truy cập'], 403);
            }
            $request->attributes->set('author_user', $user);
            return $next($request);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token đã hết hạn'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token không hợp lệ 123' . $e->getMessage()], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token không được cung cấp'], 401);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Có lỗi xảy ra: ' . $th->getMessage()], 500);
        }
    }
}
