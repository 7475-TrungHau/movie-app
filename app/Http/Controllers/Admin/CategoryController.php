<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|unique:categories',
                'slug' => 'required|string|max:255|unique:categories',
                'description' => 'nullable|string',
                'thumbnail_url' => 'nullable|string',
            ]);

            Category::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'description' => $request->description,
                'thumbnail_url' => $request->thumbnail_url,
            ]);

            return redirect()->route('admin.category.index')->with('success', 'Tạo mới danh mục thành công');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra, vui lòng thử lại' . $e->getMessage()]);
        }
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100|unique:categories,name,' . $request->id,
                'slug' => 'required|string|max:255|unique:categories,slug,' . $request->id,
                'description' => 'nullable|string',
                'thumbnail_url' => 'nullable|string',
            ]);

            $category = Category::find($request->id);
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'description' => $request->description,
                'thumbnail_url' => $request->thumbnail_url,
            ]);

            return redirect()->route('admin.category.index')->with('success', 'Cập nhật danh mục thành công');
        } catch (Exception $e) {
            //throw $th;
            return redirect()->back()->withErrors(['errormsg' => 'Có lỗi xảy ra, vui lòng thử lại', 'error' => $e->getMessage()]);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->movies->count() > 0) {
                return redirect()->back()->withErrors(['error' => 'Không thể xóa danh mục này vì có phim thuộc danh mục này']);
            }

            $category->delete();
            return redirect()->route('admin.category.index')->with('success', 'Xóa danh mục thành công');
        } catch (Exception $e) {
            //throw $th;
            return redirect()->back()->withErrors(['errormsg' => 'Có lỗi xảy ra, vui lòng thử lại', 'error' => $e->getMessage()]);
        }
    }

    public function destroyAny(Category $category)
    {
        try {
            if ($category->movies()->count() > 0) {
                foreach ($category->movies as $movie) {
                    $movie->update(['category_id' => null]);
                }
            }

            $category->delete();
            return redirect()->route('admin.category.index')->with('success', 'Xóa danh mục thành công');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['errormsg' => 'Có lỗi xảy ra, vui lòng thử lại', 'error' => $e->getMessage()]);
        }
    }

    public function show(Category $category)
    {
        return view('admin.category.show', compact('category'));
    }
}
