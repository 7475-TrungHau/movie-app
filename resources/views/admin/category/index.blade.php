@extends('layouts.admin')

@section('title', 'Category')

@section('page-title', 'Quản lý danh mục')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Button Thêm mới -->
    <div class="mb-6">
        <a href="{{ route('admin.category.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Thêm Danh Mục
        </a>
    </div>

    <!-- Thông báo -->
    @if (session('success'))
    <div id="success-message" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div id="error-message" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mô tả</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thumbnail</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($categories as $category)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 line-clamp-2">{{ $category->description ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($category->thumbnail_url)
                            <img src="{{ $category->thumbnail_url }}" alt="{{ $category->name }}"
                                class="w-12 h-12 object-cover rounded-md shadow-sm">
                            @else
                            <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->slug }}', '{{ $category->description  }}', '{{ $category->thumbnail_url  }}')"
                                class="text-blue-600 hover:text-blue-800 mr-4">Sửa</button>
                            <form action="{{ route('admin.category.delete', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Sửa Danh Mục</h2>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục</label>
                <input type="text" name="name" id="edit_name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                <input type="text" name="slug" id="edit_slug"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea name="description" id="edit_description"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                <input type="text" name="thumbnail_url" id="edit_thumbnail_url"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Lưu</button>
            </div>
        </form>
    </div>
</div>

<!-- Script cho Modal -->
<script>
    function openEditModal(id, name, slug, description, thumbnail_url) {
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_slug').value = slug;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_thumbnail_url').value = thumbnail_url;
        document.getElementById('editForm').action = '{{ url("admin/category") }}/' + id;
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    function hideMessage(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            setTimeout(() => {
                element.style.transition = 'opacity 0.5s ease-out';
                element.style.opacity = '0';
                setTimeout(() => {
                    element.style.display = 'none';
                }, 500);
            }, 5000);
        }
    }

    hideMessage('success-message');
    hideMessage('error-message');
</script>
@endsection