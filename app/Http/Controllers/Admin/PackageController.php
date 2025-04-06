<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    /**
     * Hiển thị danh sách các gói
     */
    public function index()
    {
        $packages = Package::paginate(10);
        return view('admin.package.index', compact('packages'));
    }

    /**
     * Hiển thị form tạo gói mới
     */
    public function create()
    {
        // Ban đầu chỉ truyền trang đầu tiên
        $movies = Movie::orderBy('name')->paginate(100);
        return view('admin.package.create', compact('movies'));
    }

    /**
     * Lấy danh sách phim theo phân trang qua AJAX
     */
    public function getMovies(Request $request)
    {
        $query = Movie::orderBy('name');

        // Tìm kiếm theo tên nếu có
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Lọc theo loại phim nếu có
        if ($request->has('type') && in_array($request->type, ['series', 'movie'])) {
            $query->where('type', $request->type);
        }

        // Sắp xếp theo
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'episodes_asc':
                    $query->orderBy('episodes_count', 'asc');
                    break;
                case 'episodes_desc':
                    $query->orderBy('episodes_count', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
                    break;
            }
        }

        $movies = $query->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.package.partials.movie-list', compact('movies'))->render(),
                'pagination' => view('admin.package.partials.pagination', compact('movies'))->render(),
            ]);
        }

        return $movies;
    }


    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Debug
            Log::info('Package store - Request data:', $request->all());

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'features' => 'nullable|string',
                'movie_ids' => 'nullable|array',
                'movie_ids.*' => 'exists:movies,id',
            ]);

            $validated['is_active'] = $request->has('is_active') ? 1 : 0;


            Log::info('Package store - Validated data:', $validated);

            $package = new Package();
            $package->name = $validated['name'];
            $package->price = $validated['price'];
            $package->duration_days = $validated['duration_days'];
            $package->description = $validated['description'] ?? null;
            $package->features = $validated['features'] ?? null;
            $package->is_active = $validated['is_active'];
            $package->save();

            // Debug
            Log::info('Package created with ID: ' . $package->id);

            // Thêm phim vào gói nếu có
            if ($request->has('movie_ids') && is_array($request->movie_ids)) {
                $package->movies()->attach($request->movie_ids);
                // Debug
                Log::info('Attached movies: ', $request->movie_ids);
            }

            DB::commit();

            return redirect()->route('admin.package.index')->with('success', 'Gói đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Package store error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi tạo gói: ' . $e->getMessage()]);
        }
    }


    public function edit(Package $package)
    {
        $movies = Movie::orderBy('name')->paginate(100);
        $selectedMovieIds = $package->movies()->pluck('movies.id')->toArray();

        return view('admin.package.edit', compact('package', 'movies', 'selectedMovieIds'));
    }


    public function update(Request $request, Package $package)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:packages,name,' . $package->id,
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'features' => 'nullable|string',
                'movie_ids' => 'nullable|array',
                'movie_ids.*' => 'exists:movies,id',
            ]);


            $validated['is_active'] = $request->has('is_active') ? 1 : 0;


            $package->update($validated);


            if ($request->has('movie_ids')) {
                $package->movies()->sync($request->movie_ids);
            } else {
                $package->movies()->detach();
            }
            DB::commit();

            return redirect()->route('admin.package.index')
                ->with('success', 'Gói đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Package update error: ' . $e->getMessage());

            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra khi cập nhật gói: ' . $e->getMessage()]);
        }
    }

    public function destroy(Package $package)
    {
        try {
            DB::beginTransaction();

            // Xóa các liên kết với phim
            $package->movies()->detach();
            $package->delete();

            DB::commit();

            return redirect()->route('admin.package.index')
                ->with('success', 'Gói đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Package delete error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Có lỗi xảy ra khi xóa gói: ' . $e->getMessage()]);
        }
    }

    public function showMovies(Package $package)
    {
        $movies = $package->movies()->paginate(10);
        return view('admin.package.movies', compact('package', 'movies'));
    }
}
