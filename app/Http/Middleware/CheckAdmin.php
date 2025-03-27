<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        try {


            // Kiểm tra nếu chưa đăng nhập
            if (!Auth::check()) {
                return redirect()->route('admin.login')->withErrors(['msg' => 'Vui lòng đăng nhập để truy cập khu vực admin']);
            }

            // Kiểm tra nếu không phải admin
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('admin.login')->withErrors(['msg' => 'Bạn không có quyền truy cập khu vực admin']);
            }

            return $next($request);
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->withErrors(['msg' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
}
