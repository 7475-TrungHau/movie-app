<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Movie;
use Exception;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query()->with('category')->withCount('episodes');

        // Search
        if ($search = $request->input('search')) {
            $query->whereFullText(['name', 'description', 'genres', 'actor', 'director'], $search);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $movies = $query->with('category')->paginate(1);

        return view('admin.movie.index', compact('movies'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.movie.create', compact('categories'));
    }

    public function handleFileUpload(Request $request, $file, $url, $folder, $existingUrl = null)
    {
        if (!is_dir(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }
        if ($request->hasFile($file)) {
            $file = $request->file($file);
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path($folder), $fileName);
            return '/' . $folder . '/' . $fileName;
        } elseif ($request->filled($url)) {
            if ($existingUrl && file_exists(public_path($existingUrl))) {
                unlink(public_path($existingUrl));
            }
            return $request->input($url);
        }
        return $existingUrl;
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'slug' => 'required|max:512|unique:movies|string',
                'name' => 'required|max:512|string',
                'description' => 'nullable|string',
                'actor' => 'nullable|string',
                'director' => 'nullable|string',
                'year' => 'nullable|integer',
                'type' => 'required|in:movie,series',
                'genres' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'poster_url' => 'nullable|string|max:1000',
                'poster_file' => 'nullable|image|max:4096',
                'trailer_url' => 'nullable|string|max:1000',
                'trailer_file' => 'nullable',
                'thumbnail_url' => 'nullable|string|max:1000',
                'thumbnail_file' => 'nullable|image|max:4096',
            ]);

            $data = $request->only(['slug', 'name', 'description', 'actor', 'director', 'year', 'type', 'genres', 'category_id']);
            $data['poster_url'] = $this->handleFileUpload($request, 'poster_file', 'poster_url', 'posters');
            $data['trailer_url'] = $this->handleFileUpload($request, 'trailer_file', 'trailer_url', 'trailers');
            $data['thumbnail_url'] = $this->handleFileUpload($request, 'thumbnail_file', 'thumbnail_url', 'thumbnails');

            Movie::create($data);

            return redirect()->route('admin.movie.index')->with('success', 'Tạo mới phim thành công');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
        }
    }

    public function edit(Movie $movie)
    {
        $categories = Category::all();
        return view('admin.movie.edit', compact('movie', 'categories'));
    }

    public function update(Request $request)
    {
        $movie = Movie::findOrFail($request->id);
        if (!$movie) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy phim']);
        }
        $request->validate([
            'slug' => 'required|string|max:512|unique:movies,slug,' . $movie->id,
            'name' => 'required|string|max:512',
            'description' => 'nullable|string',
            'actor' => 'nullable|string',
            'director' => 'nullable|string',
            'year' => 'nullable|integer',
            'type' => 'required|in:movie,series',

            'genres' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'poster_url' => 'nullable|string|max:1000',
            'poster_file' => 'nullable|image|max:4096',
            'trailer_url' => 'nullable|string|max:1000',
            'trailer_file' => 'nullable',
            'thumbnail_url' => 'nullable|string|max:1000',
            'thumbnail_file' => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['slug', 'name', 'description', 'actor', 'director', 'year', 'type', 'genres', 'category_id']);
        $data['poster_url'] = $this->handleFileUpload($request, 'poster_file', 'poster_url', 'posters', $movie->poster_url);
        $data['trailer_url'] = $this->handleFileUpload($request, 'trailer_file', 'trailer_url', 'trailers', $movie->trailer_url);
        $data['thumbnail_url'] = $this->handleFileUpload($request, 'thumbnail_file', 'thumbnail_url', 'thumbnails', $movie->thumbnail_url);

        $movie->update($data);

        return redirect()->route('admin.movie.index')->with('success', 'Cập nhật phim thành công');
    }

    public function destroy(Movie $movie)
    {
        if ($movie->poster_url && file_exists(public_path($movie->poster_url))) {
            unlink(public_path($movie->poster_url));
        }
        if ($movie->trailer_url && file_exists(public_path($movie->trailer_url))) {
            unlink(public_path($movie->trailer_url));
        }
        if ($movie->thumbnail_url && file_exists(public_path($movie->thumbnail_url))) {
            unlink(public_path($movie->thumbnail_url));
        }
        $movie->delete();
        return redirect()->route('admin.movie.index')->with('success', 'Xóa phim thành công');
    }
}
