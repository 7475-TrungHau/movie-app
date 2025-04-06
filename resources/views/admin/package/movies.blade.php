@extends('layouts.admin')

@section('title', 'Phim Trong Gói ' . $package->name)

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Phim Trong Gói: {{ $package->name }}</h1>
                    <div class="mt-2 flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ number_format($package->price) }} VNĐ</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $package->duration_days }} ngày</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                            </svg>
                            <span class="font-medium text-gray-700">{{ $movies->total() }} phim</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 {{ $package->is_active ? 'text-green-500' : 'text-red-500' }} mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium {{ $package->is_active ? 'text-green-700' : 'text-red-700' }}">
                                {{ $package->is_active ? 'Đang kích hoạt' : 'Đã vô hiệu' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.package.edit', $package->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Sửa Gói
                        </div>
                    </a>
                    <a href="{{ route('admin.package.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Quay lại
                        </div>
                    </a>
                </div>
            </div>

            @if($package->description)
            <div class="mt-4 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Mô tả</h3>
                <p class="text-gray-700">{{ $package->description }}</p>
            </div>
            @endif

            @if($package->features)
            <div class="mt-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Tính năng</h3>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach(explode("\n", $package->features) as $feature)
                    @if(trim($feature))
                    <li class="text-gray-700">{{ $feature }}</li>
                    @endif
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-gray-50">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Danh sách phim trong gói</h3>

                <div class="flex space-x-3">
                    <div class="relative">
                        <input type="text" id="movieSearch" placeholder="Tìm kiếm phim..." class="px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <select id="sortMovies" class="px-3 py-2 border border-gray-300 rounded-md">
                        <option value="name_asc">Tên (A-Z)</option>
                        <option value="name_desc">Tên (Z-A)</option>
                        <option value="type_movie">Loại: Phim lẻ</option>
                        <option value="type_series">Loại: Series</option>
                    </select>
                </div>
            </div>

            @if($movies->count() > 0)
            <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình Ảnh</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Phim</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thể Loại</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="movieList">
                        @foreach ($movies as $movie)
                        <tr class="movie-item hover:bg-gray-50" data-type="{{ $movie->type ?? 'movie' }}">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $movie->id }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($movie->thumbnail_url)
                                <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->name }}" class="h-14 w-20 object-cover rounded shadow-sm">
                                @else
                                <div class="h-14 w-20 bg-gray-200 flex items-center justify-center rounded">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $movie->name }}</div>
                                <div class="text-xs text-gray-500">{{ $movie->release_year ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-700">{{ $movie->genres ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movie->type == 'series' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $movie->type == 'series' ? 'Series' : 'Phim lẻ' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-12 text-center text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                </svg>
                <p class="mt-2 text-sm">Không có phim nào trong gói này.</p>
                <a href="{{ route('admin.package.edit', $package->id) }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Thêm phim vào gói
                </a>
            </div>
            @endif
        </div>
    </div>

    @if($movies->count() > 0)
    <div class="mt-4">
        {{ $movies->links() }}
    </div>
    @endif
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

        // Tìm kiếm phim
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            movieItems.forEach(item => {
                const movieName = item.querySelector('.text-sm.font-medium').textContent.toLowerCase();
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
                    return a.querySelector('.text-sm.font-medium').textContent.localeCompare(b.querySelector('.text-sm.font-medium').textContent);
                } else if (sortValue === 'name_desc') {
                    return b.querySelector('.text-sm.font-medium').textContent.localeCompare(a.querySelector('.text-sm.font-medium').textContent);
                } else if (sortValue === 'type_movie') {
                    // Ưu tiên hiển thị phim lẻ trước
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return 1;
                    if (aType !== 'series' && bType === 'series') return -1;
                    return a.querySelector('.text-sm.font-medium').textContent.localeCompare(b.querySelector('.text-sm.font-medium').textContent);
                } else if (sortValue === 'type_series') {
                    // Ưu tiên hiển thị series trước
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return -1;
                    if (aType !== 'series' && bType === 'series') return 1;
                    return a.querySelector('.text-sm.font-medium').textContent.localeCompare(b.querySelector('.text-sm.font-medium').textContent);
                }
                return 0;
            });

            // Xóa các hàng hiện tại
            rows.forEach(row => row.remove());

            // Thêm lại các hàng đã sắp xếp
            rows.forEach(row => tbody.appendChild(row));
        });
    });
</script>
@endsection
@endsection