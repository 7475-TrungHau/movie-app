<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Package::query();
            // Thêm filter hoặc search nếu cần
            $packages = $query->paginate($request->input('per_page', 15));
            return response()->json($packages);
        } catch (\Exception $e) {
            Log::error("Error listing packages (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Không thể lấy danh sách gói'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:packages,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $package = Package::create($validator->validated());
            return response()->json($package, 201);
        } catch (\Exception $e) {
            Log::error("Error creating package (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Tạo gói thất bại'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        try {
            // Load thêm danh sách phim thuộc gói này
            $package->load('movies:id,name'); // Chỉ lấy id và name của phim
            return response()->json($package);
        } catch (\Exception $e) {
            Log::error("Error showing package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Không thể lấy thông tin gói'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:packages,name,' . $package->id,
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_days' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $package->update($validator->validated());
            return response()->json($package);
        } catch (\Exception $e) {
            Log::error("Error updating package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Cập nhật gói thất bại'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        try {
            // Xóa các liên kết phim trước khi xóa gói (hoặc để cascade delete xử lý)
            // $package->movies()->detach(); // Nếu không dùng cascade delete

            $package->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error("Error deleting package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Xóa gói thất bại'], 500);
        }
    }

    // --- Quản lý Movies trong Package ---

    /**
     * Gán danh sách phim vào một gói (đồng bộ hóa).
     * Nhận vào một mảng các movie_id.
     */
    public function syncMovies(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'movie_ids' => 'required|array',
            'movie_ids.*' => 'required|integer|exists:movies,id', // Đảm bảo movie_id tồn tại
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $movieIds = $request->input('movie_ids');
            $package->movies()->sync($movieIds); // sync sẽ xóa các liên kết cũ và thêm các liên kết mới

            // Load lại danh sách phim đã gán
            $package->load('movies:id,name');

            return response()->json([
                'message' => 'Đã cập nhật danh sách phim cho gói.',
                'package' => $package
            ]);
        } catch (\Exception $e) {
            Log::error("Error syncing movies for package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Cập nhật phim cho gói thất bại'], 500);
        }
    }

    /**
     * Thêm một phim vào gói.
     */
    public function addMovie(Request $request, Package $package, Movie $movie)
    {
        try {
            // Kiểm tra xem phim đã tồn tại trong gói chưa
            if ($package->movies()->where('movie_id', $movie->id)->exists()) {
                return response()->json(['message' => 'Phim đã có trong gói này.'], 400);
            }

            $package->movies()->attach($movie->id);

            return response()->json(['message' => 'Đã thêm phim vào gói.']);
        } catch (\Exception $e) {
            Log::error("Error adding movie {$movie->id} to package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Thêm phim vào gói thất bại'], 500);
        }
    }

    /**
     * Xóa một phim khỏi gói.
     */
    public function removeMovie(Request $request, Package $package, Movie $movie)
    {
        try {
            $detached = $package->movies()->detach($movie->id);

            if ($detached) {
                return response()->json(['message' => 'Đã xóa phim khỏi gói.']);
            } else {
                return response()->json(['message' => 'Phim không tồn tại trong gói này.'], 404);
            }
        } catch (\Exception $e) {
            Log::error("Error removing movie {$movie->id} from package {$package->id} (Admin): " . $e->getMessage());
            return response()->json(['error' => 'Xóa phim khỏi gói thất bại'], 500);
        }
    }
}
