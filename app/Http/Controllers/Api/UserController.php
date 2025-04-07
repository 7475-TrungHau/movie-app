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

            $activeSubscription = $user->subscriptions()
                ->where('end_date', '>=', now())
                ->where('status', 'active')
                ->with(['package'])
                ->first();

            $user->package_name = $activeSubscription->package->is_active ? $activeSubscription->package->name : null;
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

    public function getRatingByMovie(Request $request, $movieId)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }
            $rating = $user->ratings()->where('movie_id', $movieId)->first();
            if (!$rating) {
                return response()->json(['message' => 'Không tìm thấy đánh giá cho phim này'], 404);
            }
            return response()->json([
                'rating' => $rating->rating_value,
                'message' => 'Lấy thông tin đánh giá thành công'
            ])->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
