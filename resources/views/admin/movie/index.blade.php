@extends('layouts.admin')

@section('title', 'Danh sách Phim')
@section('page-title', 'Quản lý Phim')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 pb-4 border-b border-gray-200">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Danh sách Phim</h1>
        <a href="{{ route('admin.movie.create') }}"
            class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Thêm Phim Mới
        </a>
    </div>

    @if (session('success'))
    <div id="alert-success" class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm relative" role="alert">
        <span class="font-medium">Thành công!</span> {{ session('success') }}
        <button type="button" onclick="document.getElementById('alert-success').style.display='none'" class="absolute top-2.5 right-3 text-green-800 hover:text-green-900" aria-label="Close">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif
    @if (session('error'))
    <div id="alert-error" class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm relative" role="alert">
        <span class="font-medium">Lỗi!</span> {{ session('error') }}
        <button type="button" onclick="document.getElementById('alert-error').style.display='none'" class="absolute top-2.5 right-3 text-red-800 hover:text-red-900" aria-label="Close">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm border border-gray-200">
        <form id="filterForm" method="GET" action="{{ route('admin.movie.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
            @csrf
            <div class="w-full md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm tên phim</label>
                <input type="text" id="search" name="search" placeholder="Nhập tên phim..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 shadow-sm transition duration-150 text-sm"
                    value="{{ request('search') }}">
            </div>

            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sắp xếp theo</label>
                <select name="sort_by" id="sort_by" onchange="this.form.submit()"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 shadow-sm transition duration-150 text-sm">
                    <option value="id" {{ request('sort_by', 'id') === 'id' ? 'selected' : '' }}>ID</option>
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Tên phim</option>
                    <option value="category_id" {{ request('sort_by') === 'category_id' ? 'selected' : '' }}>Danh mục</option>
                    <option value="type" {{ request('sort_by') === 'type' ? 'selected' : '' }}>Loại</option>
                </select>
            </div>

            <div>
                <label for="sort_direction" class="block text-sm font-medium text-gray-700 mb-1">Thứ tự</label>
                <select name="sort_direction" id="sort_direction" onchange="this.form.submit()"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 shadow-sm transition duration-150 text-sm">
                    <option value="asc" {{ request('sort_direction', 'asc') === 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    <option value="desc" {{ request('sort_direction') === 'desc' ? 'selected' : '' }}>Giảm dần</option>
                </select>
            </div>
            <div class="md:col-start-4">
                <button type="submit" class="w-full sm:w-auto inline-flex justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Tên phim</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Danh mục</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Loại</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Số tập</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Hành động</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($movies as $movie)
                    <tr class="hover:bg-indigo-50 transition duration-150 ease-in-out align-middle">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movie->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $movie->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $movie->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($movie->type == 'series')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Series</span>
                            @elseif($movie->type == 'single')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Single</span>
                            @else
                            {{ ucfirst($movie->type) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            <span class="font-semibold">{{ $movie->episodes_count ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.movie.edit', $movie) }}" title="Chỉnh sửa"
                                    class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out p-1 hover:bg-indigo-100 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.movie.episodes', $movie) }}" title="Quản lý tập phim"
                                    class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out p-1 hover:bg-green-100 rounded">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.movie.delete', $movie) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phim này và tất cả các tập phim liên quan không? Hành động này không thể hoàn tác.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Xóa phim"
                                        class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out p-1 hover:bg-red-100 rounded">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 italic">
                            Không tìm thấy phim nào phù hợp.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8">
        {{ $movies->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Tự động ẩn thông báo thành công/lỗi sau vài giây
    setTimeout(() => {
        const successAlert = document.getElementById('alert-success');
        if (successAlert) {
            successAlert.style.transition = 'opacity 0.5s ease-out';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.style.display = 'none', 500);
        }
        const errorAlert = document.getElementById('alert-error');
        if (errorAlert) {
            errorAlert.style.transition = 'opacity 0.5s ease-out';
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.style.display = 'none', 500);
        }
    }, 5000); // Ẩn sau 5 giây
</script>
@endsection