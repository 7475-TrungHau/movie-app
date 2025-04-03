<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use PHPUnit\Event\Subscriber;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        try {
            $user = $request->attributes->get('author_user');  // cais này sẽ lấy ra user đã được xác thực từ middleware CheckUser
            // $subscription = Subscription::where('user_id', $user->id)
            //     ->where('end_date', '>=', now())
            //     ->with(['package'])
            //     ->first();
            return response()->json([
                'user' => $user,
                // 'subscriptions' => $subscription ? $subscription : null,
                'message' => 'Lấy thông tin người dùng thành công'
            ])->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
