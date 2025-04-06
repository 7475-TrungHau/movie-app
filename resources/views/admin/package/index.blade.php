@extends('layouts.admin') {{-- Sử dụng layout admin của bạn --}}

@section('title', 'Quản lý Gói Cước')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Quản lý Gói Cước</h1>
        <a href="{{ route('admin.package.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Thêm Gói Mới</a>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Gói</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá (VNĐ)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời Hạn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số Phim</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($packages as $package)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $package->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $package->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($package->price) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $package->duration_days }} ngày</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $package->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $package->is_active ? 'Kích hoạt' : 'Vô hiệu' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('admin.package.movies', $package->id) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $package->movies->count() }} phim
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.package.edit', $package->id) }}" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                            <form action="{{ route('admin.package.destroy', $package->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa gói này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $packages->links() }}
    </div>
</div>
@endsection