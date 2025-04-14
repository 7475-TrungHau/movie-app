<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use PHPUnit\Event\Subscriber;
use Illuminate\Support\Facades\Log;

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




            $activeSubscriptions = $user->subscriptions()
                ->where('end_date', '>=', now())
                ->where('status', 'active')
                ->with(['package:id,name,price'])
                ->get();

            $user->packages = $activeSubscriptions->map(function ($subscription) {
                $package = $subscription->package;
                if ($package) {
                    $package->end_date = $subscription->end_date; // Add end_date from subscription to package
                }
                return $package;
            });
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                $user->avatar = url($user->avatar);
            }
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

    public function getUserWithSubscription(Request $request)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }
            $subscription = Subscription::where('user_id', $user->id)
                ->where('end_date', '>=', now())
                ->with(['package:id,name,price'])
                ->first();
            if (!$subscription) {
                return response()->json(['message' => 'Không tìm thấy gói cước cho người dùng này'], 404);
            }
            return response()->json([
                'user' => $user,
                'subscription' => $subscription,
                'message' => 'Lấy thông tin người dùng và gói cước thành công'
            ])->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {

        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }

            $validatedData = $request->validate([
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
                'full_name' => 'sometimes|required|string|max:255',
                'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:4048' // Validate avatar file
            ]);

            $updateData = [];
            if ($request->filled('username')) {
                $updateData['username'] = $validatedData['username'];
            }
            if ($request->filled('full_name')) {
                $updateData['full_name'] = $validatedData['full_name'];
            }

            // Handle avatar upload using adapted logic
            $folder = 'avatars';
            $folderPath = public_path($folder);

            // Ensure the directory exists
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $newAvatarPath = '/' . $folder . '/' . $fileName;

                // Delete old avatar if it exists and is a valid file path
                if ($user->avatar && file_exists(public_path($user->avatar))) {
                    // Basic check to avoid deleting unintended files if $user->avatar is not a path
                    if (strpos($user->avatar, '/') !== false) {
                        unlink(public_path($user->avatar));
                    }
                }

                // Move the new file
                $file->move($folderPath, $fileName);
                $updateData['avatar'] = $newAvatarPath; // Store the public path
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            // Refresh user data to include the updated avatar URL if needed
            $user->refresh();

            // Optionally return the updated user object
            return response()->json([
                'message' => 'Cập nhật thông tin thành công!',
                'user' => $user // Return updated user data
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('User update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Cập nhật thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function getFavoriteMovies(Request $request, $movieId)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }
            $favorite = Favorite::where('user_id', $user->id)
                ->where('movie_id', $movieId)
                ->first();
            if (!$favorite) {
                return response()->json([
                    'message' => 'Không tìm thấy phim yêu thích',
                    'is_favorite' => 0
                ], 404);
            }
            return response()->json([
                'is_favorite' => 1,
                'message' => 'Lấy thông tin phim yêu thích thành công'
            ])->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
