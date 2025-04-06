@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Gói Cước')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-semibold text-gray-900 mb-4">Chỉnh Sửa Gói Cước</h1>

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('admin.package.update', $package->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Tên Gói</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $package->name) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $package->price) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="duration_days" class="block text-sm font-medium text-gray-700">Thời hạn (ngày)</label>
                    <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('duration_days')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="is_active" class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $package->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Kích hoạt</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $package->description) }}</textarea>
                    @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="features" class="block text-sm font-medium text-gray-700">Tính năng (mỗi dòng 1 tính năng)</label>
                    <textarea name="features" id="features" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('features', $package->features) }}</textarea>
                    @error('features')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium text-gray-900">Danh sách phim</h2>
                <div class="flex space-x-3">
                    <div class="relative w-64">
                        <input type="text" id="movieSearch" placeholder="Tìm kiếm phim..." class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <select id="sortMovies" class="px-3 py-2 border border-gray-300 rounded-md">
                        <option value="name_asc">Tên (A-Z)</option>
                        <option value="name_desc">Tên (Z-A)</option>
                        <option value="type_movie">Loại: Phim lẻ</option>
                        <option value="type_series">Loại: Series</option>
                    </select>
                </div>
            </div>

            <div class="border border-gray-200 rounded-md">
                <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Chọn</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">ID</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Hình ảnh</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên phim</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thể loại</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Loại</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="movieList">
                            @foreach($movies as $movie)
                            <tr class="movie-item hover:bg-gray-50 cursor-pointer" data-type="{{ $movie->type ?? 'movie' }}">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <input type="checkbox" name="movie_ids[]" id="movie-{{ $movie->id }}" value="{{ $movie->id }}"
                                        {{ (is_array(old('movie_ids', $selectedMovieIds)) && in_array($movie->id, old('movie_ids', $selectedMovieIds))) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600">
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">{{ $movie->id }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if($movie->thumbnail_url)
                                    <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->name }}" class="h-12 w-16 object-cover rounded">
                                    @else
                                    <div class="h-12 w-16 bg-gray-200 flex items-center justify-center rounded">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <label for="movie-{{ $movie->id }}" class="block text-sm text-gray-700 cursor-pointer">{{ $movie->name }}</label>
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500">{{ $movie->genres ?? 'N/A' }}</td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movie->type == 'series' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $movie->type == 'series' ? 'Series' : 'Phim lẻ' }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="text-sm text-gray-600">
                    Đã chọn: <span id="selectedCount" class="font-medium">0</span> phim | Tổng cộng: {{ $movies->total() }} phim
                </div>

                <div>
                    {{ $movies->links() }}
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cập Nhật
            </button>
            <a href="{{ route('admin.package.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                Hủy
            </a>
        </div>
    </form>
</div>

<style>
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('movieSearch');
        const sortSelect = document.getElementById('sortMovies');
        const movieItems = document.querySelectorAll('.movie-item');
        const checkboxes = document.querySelectorAll('input[name="movie_ids[]"]');
        const selectedCountElement = document.getElementById('selectedCount');

        // Store selected movies to retain when changing pages
        let selectedMovies = new Set(
            JSON.parse('{!! json_encode($selectedMovieIds) !!}')
        );

        // Cập nhật số lượng phim đã chọn
        function updateSelectedCount() {
            selectedCountElement.textContent = document.querySelectorAll('input[name="movie_ids[]"]:checked').length;
        }

        // Khởi tạo số lượng phim đã chọn
        updateSelectedCount();

        // Cập nhật số lượng khi checkbox thay đổi
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedMovies.add(this.value);
                } else {
                    selectedMovies.delete(this.value);
                }
                updateSelectedCount();
            });
        });

        // Tìm kiếm phim
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            movieItems.forEach(item => {
                const movieName = item.querySelector('label').textContent.toLowerCase();
                if (movieName.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Sắp xếp phim
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const tbody = document.getElementById('movieList');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                if (sortValue === 'name_asc') {
                    return a.querySelector('label').textContent.localeCompare(b.querySelector('label').textContent);
                } else if (sortValue === 'name_desc') {
                    return b.querySelector('label').textContent.localeCompare(a.querySelector('label').textContent);
                } else if (sortValue === 'type_movie') {
                    // Ưu tiên hiển thị phim lẻ trước
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return 1;
                    if (aType !== 'series' && bType === 'series') return -1;
                    return a.querySelector('label').textContent.localeCompare(b.querySelector('label').textContent);
                } else if (sortValue === 'type_series') {
                    // Ưu tiên hiển thị series trước
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return -1;
                    if (aType !== 'series' && bType === 'series') return 1;
                    return a.querySelector('label').textContent.localeCompare(b.querySelector('label').textContent);
                }
                return 0;
            });

            // Xóa các hàng hiện tại
            rows.forEach(row => row.remove());

            // Thêm lại các hàng đã sắp xếp
            rows.forEach(row => tbody.appendChild(row));
        });

        // Click vào dòng cũng chọn checkbox
        movieItems.forEach(item => {
            item.addEventListener('click', function(event) {
                if (event.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    checkbox.checked = !checkbox.checked;

                    if (checkbox.checked) {
                        selectedMovies.add(checkbox.value);
                    } else {
                        selectedMovies.delete(checkbox.value);
                    }

                    updateSelectedCount();
                }
            });
        });

        // Xử lý phân trang để lưu dữ liệu đã chọn
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                e.preventDefault();

                // Lưu các checkbox đã chọn vào localStorage
                localStorage.setItem('selectedMovies', JSON.stringify(Array.from(selectedMovies)));

                // Chuyển đến trang đích
                window.location.href = e.target.href;
            }
        });

        // Khi trang tải, khôi phục các checkbox đã chọn từ localStorage
        const savedMovies = localStorage.getItem('selectedMovies');
        if (savedMovies) {
            const savedMovieIds = JSON.parse(savedMovies);
            savedMovieIds.forEach(id => {
                selectedMovies.add(id);
                const checkbox = document.getElementById(`movie-${id}`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });

            // Cập nhật lại số lượng phim đã chọn
            updateSelectedCount();

            // Xóa dữ liệu đã lưu để không ảnh hưởng đến các phiên làm việc khác
            localStorage.removeItem('selectedMovies');
        }

        // Thêm sự kiện submit form
        document.querySelector('form').addEventListener('submit', function(e) {
            // Đảm bảo rằng tất cả các phim đã chọn đều được gửi đi
            // ngay cả khi chúng không hiển thị trên trang hiện tại
            selectedMovies.forEach(movieId => {
                const existingInput = document.getElementById(`movie-${movieId}`);
                if (!existingInput || !existingInput.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'movie_ids[]';
                    hiddenInput.value = movieId;
                    this.appendChild(hiddenInput);
                }
            });
        });
    });
</script>
@endsection
@endsection