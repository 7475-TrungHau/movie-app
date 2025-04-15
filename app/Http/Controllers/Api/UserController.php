<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Subscription;
use App\Models\History;
use App\Models\Episode;
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

    public function getFavorites(Request $request)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }

            $perPage = $request->input('per_page', 10); // Default 10 items per page

            $favorites = Favorite::where('user_id', $user->id)
                ->with(['movie:id,name,thumbnail_url,slug'])
                ->paginate($perPage);

            if ($favorites->isEmpty()) {
                return response()->json(['message' => 'Không tìm thấy phim yêu thích'], 404);
            }

            return response()->json([
                'favorites' => $favorites,
                'message' => 'Lấy danh sách phim yêu thích thành công'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message' => 'Có lỗi xảy ra khi lấy danh sách phim yêu thích'
            ], 500);
        }
    }

    public function getHistory(Request $request)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }

            $perPage = $request->input('per_page', 10);
            $movieId = $request->input('movie_id');

            $query = History::where('user_id', $user->id);

            // If movie_id is provided, filter episodes by that movie
            if ($movieId) {
                $query->whereHas('episode', function ($query) use ($movieId) {
                    $query->where('movie_id', $movieId);
                });
            }

            $histories = $query
                ->with(['episode:id,episode_number,title,thumbnail_url,movie_id,slug', 'episode.movie:id,name,slug,thumbnail_url'])
                ->orderBy('last_watched_at', 'desc')
                ->paginate($perPage);

            if ($histories->isEmpty()) {
                return response()->json(['message' => 'Không tìm thấy lịch sử xem phim'], 404);
            }

            return response()->json([
                'histories' => $histories,
                'message' => 'Lấy danh sách lịch sử xem phim thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Có lỗi xảy ra khi lấy lịch sử xem phim'
            ], 500);
        }
    }

    public function getHistories(Request $request)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }

            $histories = History::where('user_id', $user->id)
                ->with(['episode:id,episode_number,title,thumbnail_url,movie_id', 'episode.movie:id,name,slug,thumbnail_url'])
                ->orderBy('last_watched_at', 'desc')
                ->get();

            if ($histories->isEmpty()) {
                return response()->json(['message' => 'Không tìm thấy lịch sử xem phim'], 404);
            }

            return response()->json([
                'histories' => $histories,
                'message' => 'Lấy danh sách lịch sử xem phim thành công'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Có lỗi xảy ra khi lấy lịch sử xem phim'
            ], 500);
        }
    }

    public function deleteHistory(Request $request, $episodeId = null)
    {
        try {
            $user = $request->attributes->get('author_user');
            if (!$user) {
                return response()->json(['message' => 'Xác thực user thất bại'], 401);
            }

            $query = History::where('user_id', $user->id);

            if ($episodeId) {
                // Xóa lịch sử cho một tập phim cụ thể
                $query->where('episode_id', $episodeId);
            }

            $deleted = $query->delete();

            if ($deleted == 0) {
                return response()->json(['message' => 'Không tìm thấy lịch sử để xóa'], 404);
            }

            return response()->json([
                'message' => $episodeId ? 'Xóa lịch sử xem tập phim thành công' : 'Xóa toàn bộ lịch sử xem phim thành công',
                'deleted_count' => $deleted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Có lỗi xảy ra khi xóa lịch sử xem phim'
            ], 500);
        }
    }
}
