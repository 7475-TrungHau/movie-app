<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Movie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EpisodeController extends Controller
{
    public function index(Movie $movie)
    {
        $episodes = $movie->episodes()->paginate(10);
        return view('admin.episode.index', compact('movie', 'episodes'));
    }

    public function handleFileUpload(Request $request, $file, $url, $folder, $existingUrl = null)
    {
        if (!is_dir(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }
        if ($request->hasFile($file)) {
            $file = $request->file($file);
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path($folder), $fileName);
            return '/' . $folder . '/' . $fileName;
        } else {
            if ($request->filled($url)) {
                if ($existingUrl && file_exists(public_path($existingUrl))) {
                    unlink(public_path($existingUrl));
                }
                return $request->input($url);
            }
        }
        return $existingUrl;
    }

    public function create(Movie $movie)
    {
        return view('admin.episode.create', compact('movie'));
    }

    public function store(Request $request, Movie $movie)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255', // Thêm điều kiện unique cho trường 'slug'
                'description' => 'nullable|string',
                'video_url' => 'required|url',
                'release_date' => 'required|date',
                'episode_number' => 'required|integer',
                'thumbnail_url' => 'nullable|url',
                'video_file' => 'nullable',
                'thumbnail_file' => 'nullable|image|max:4096'
            ]);

            $data = $request->only(['title', 'description', 'release_date', 'episode_number', 'slug']);
            $data['video_url'] = $this->handleFileUpload($request, 'video_file', 'video_url', 'videos');
            $data['thumbnail_url'] = $this->handleFileUpload($request, 'thumbnail_file', 'thumbnail_url', 'thumbnails');


            $movie->episodes()->create($data);

            return redirect()->route('admin.movie.episodes', $movie)
                ->with('success', 'Episode created successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function edit(Movie $movie, Episode $episode)
    {
        return view('admin.episode.edit', compact('movie', 'episode'));
    }

    public function update(Request $request, Movie $movie, Episode $episode)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'release_date' => 'required|date',
                'episode_number' => 'required|integer',
                'video_url' => 'nullable|string',
                'thumbnail_url' => 'nullable|string',
                'video_file' => 'nullable|file',
                'thumbnail_file' => 'nullable|image|max:4096'
            ]);

            $data = $request->only(['title', 'description', 'release_date', 'episode_number', 'slug']);
            $data['video_url'] = $this->handleFileUpload($request, 'video_file', 'video_url', 'videos', $episode->video_url);
            $data['thumbnail_url'] = $this->handleFileUpload($request, 'thumbnail_file', 'thumbnail_url', 'thumbnails', $episode->thumbnail_url);

            $episode->update($data);

            return redirect()->route('admin.movie.episodes', $movie)
                ->with('success', 'Episode updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function destroy(Movie $movie, Episode $episode)
    {
        try {
            $episodeTitle = $episode->title; // Lưu lại tiêu đề để thông báo

            // --- Xóa các File liên quan ---

            // 1. Xóa file Video (nếu là file cục bộ)
            // Kiểm tra xem video_url có tồn tại và *không* phải là URL tuyệt đối (http)
            if ($episode->video_url && !Str::startsWith($episode->video_url, ['http://', 'https://'])) {
                // Tạo đường dẫn đầy đủ đến file trong thư mục public
                $videoPath = public_path($episode->video_url);

                // Kiểm tra xem file có tồn tại không trước khi xóa
                if (File::exists($videoPath)) {
                    try {
                        File::delete($videoPath); // Thực hiện xóa file
                    } catch (Exception $e) {
                        // Ghi log lỗi nếu xóa file thất bại (quan trọng để debug)
                        Log::error("Lỗi xóa file video của tập phim {$episode->id}: {$videoPath}. Lỗi: " . $e->getMessage());
                        // Bạn có thể chọn dừng lại và báo lỗi ở đây,
                        // hoặc tiếp tục xóa bản ghi DB và chỉ ghi log lỗi file.
                        // Ví dụ: return redirect()->back()->with('error', 'Không thể xóa file video.');
                    }
                } else {
                    // Ghi log cảnh báo nếu đường dẫn có nhưng file không tồn tại
                    Log::warning("File video không tìm thấy để xóa cho tập phim {$episode->id}: {$videoPath}");
                }
            }

            // 2. Xóa file Thumbnail (nếu là file cục bộ)
            // Logic tương tự như xóa video
            if ($episode->thumbnail_url && !Str::startsWith($episode->thumbnail_url, ['http://', 'https://'])) {
                $thumbnailPath = public_path($episode->thumbnail_url);
                if (File::exists($thumbnailPath)) {
                    try {
                        File::delete($thumbnailPath);
                    } catch (Exception $e) {
                        Log::error("Lỗi xóa file thumbnail của tập phim {$episode->id}: {$thumbnailPath}. Lỗi: " . $e->getMessage());
                        // return redirect()->back()->with('error', 'Không thể xóa file thumbnail.');
                    }
                } else {
                    Log::warning("File thumbnail không tìm thấy để xóa cho tập phim {$episode->id}: {$thumbnailPath}");
                }
            }

            // --- Xóa Bản ghi trong Cơ sở dữ liệu ---
            $episode->delete(); // Xóa bản ghi episode khỏi database

            // Chuyển hướng về trang danh sách episodes của movie đó với thông báo thành công
            return redirect()->route('admin.movie.episodes', $movie)
                ->with('success', "Tập phim '{$episodeTitle}' đã được xóa thành công!");
        } catch (Exception $e) {
            // Xử lý các lỗi không mong muốn khác có thể xảy ra
            Log::error('Lỗi khi xóa tập phim ' . $episode->id . ': ' . $e->getMessage() . ' Stack Trace: ' . $e->getTraceAsString());

            // Chuyển hướng về trang trước đó với thông báo lỗi chung
            return redirect()->route('admin.movie.episodes', $movie)
                ->with('error', 'Đã xảy ra lỗi trong quá trình xóa tập phim. Vui lòng thử lại hoặc kiểm tra log.');
        }
    }
}
