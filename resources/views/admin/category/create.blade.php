@extends('layouts.admin')
@section('title', 'Thêm Danh Mục')
@section('page-title', 'Tạo Danh Mục Mới')
@section('content')
<div class="max-w-4xl mx-auto">
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition">Bảng điều khiển</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('admin.category.index') }}" class="text-gray-500 hover:text-blue-600 transition">Danh mục</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-blue-600 font-medium">Tạo Danh Mục Mới</li>
        </ol>
    </nav>


    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-lg mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Tạo Danh Mục Mới</h2>
                    <p class="text-gray-500 mt-1">Điền thông tin bên dưới để tạo danh mục mới</p>
                </div>
            </div>
        </div>
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Có lỗi xảy ra!</strong>
            <span class="block sm:inline">Vui lòng kiểm tra lại thông tin đã nhập.</span>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form action="{{ route('admin.category.store') }}" method="POST" class="p-6">
            @csrf

            <div class="mb-8">
                <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Thông tin cơ bản</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Tên Danh Mục <span class="text-red-500">*</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="name" id="name"
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="VD: Hành Động, Chính Kịch, Hài"
                                value="{{ old('name') }}"
                                onkeyup="generateSlug()">

                            @if(!$errors->has('name'))
                            <p class="mt-1 text-xs text-gray-500">Tên sẽ được hiển thị cho người dùng trên trang web.</p>
                            @endif

                            @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                            Slug URL <span class="text-red-500">*</span>
                            <span class="ml-1 text-xs text-gray-400" title="Slug được sử dụng trong URL của trang danh mục">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="slug" id="slug"
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('slug') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="VD: hanh-dong, chinh-kich, hai"
                                value="{{ old('slug') }}">

                            @if(!$errors->has('slug'))
                            <p class="mt-1 text-xs text-gray-500">Tự động tạo từ tên. Ví dụ: danh-muc-cua-toi</p>
                            @endif

                            @error('slug')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Mô tả
                        <span class="ml-1 text-xs font-normal text-gray-500">(Không bắt buộc)</span>
                    </label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('description') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Nhập mô tả chi tiết cho danh mục này...">{{ old('description') }}</textarea>

                    @if(!$errors->has('description'))
                    <p class="mt-1 text-xs text-gray-500">Cung cấp mô tả ngắn gọn về danh mụ c này để giúp người dùng hiểu nội dung của nó.</p>
                    @endif

                    @error('description')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-md font-medium text-gray-700 mb-4 pb-2 border-b">Phương tiện</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="thumbnail_url" class="block text-sm font-medium text-gray-700 mb-1">
                            URL Hình Thu Nhỏ
                            <span class="ml-1 text-xs font-normal text-gray-500">(Không bắt buộc)</span>
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="thumbnail_url" id="thumbnail_url"
                                class="pl-10 w-full border border-gray-300 rounded-lg py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('thumbnail_url') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                placeholder="https://vidu.com/hinh-anh.jpg"
                                value="{{ old('thumbnail_url') }}"
                                onkeyup="previewThumbnail()">

                            @if(!$errors->has('thumbnail_url'))
                            <p class="mt-1 text-xs text-gray-500">Nhập URL cho hình ảnh thu nhỏ của danh mục.</p>
                            @endif

                            @error('thumbnail_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Xem trước</label>
                        <div id="thumbnail-preview" class="border border-gray-200 rounded-lg h-48 bg-gray-50 flex items-center justify-center overflow-hidden relative group">
                            <div id="thumbnail-placeholder" class="text-center p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm text-gray-400 mt-2">Không có ảnh xem trước</p>
                            </div>
                            <img id="thumbnail-image" class="hidden w-full h-full object-contain" src="" alt="Xem trước hình thu nhỏ">

                            <button type="button" id="clear-thumbnail"
                                class="hidden group-hover:flex absolute top-2 right-2 bg-white/80 hover:bg-white rounded-full p-1 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                                onclick="clearThumbnail()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-8">
                <a href="{{ route('admin.category.index') }}"
                    class="inline-flex items-center px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Quay lại Danh sách
                </a>

                <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tạo Danh Mục
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function generateSlug() {
        const name = document.getElementById('name').value;
        const slug = name.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();

        document.getElementById('slug').value = slug;
    }

    function previewThumbnail() {
        const url = document.getElementById('thumbnail_url').value;
        const previewImg = document.getElementById('thumbnail-image');
        const placeholder = document.getElementById('thumbnail-placeholder');
        const clearBtn = document.getElementById('clear-thumbnail');

        if (url && url.trim() !== '') {
            previewImg.src = url;
            previewImg.classList.remove('hidden');
            placeholder.classList.add('hidden');
            clearBtn.classList.remove('hidden');

            previewImg.onerror = function() {
                previewImg.classList.add('hidden');
                placeholder.classList.remove('hidden');
                placeholder.querySelector('p').textContent = 'URL hình ảnh không hợp lệ';
                placeholder.querySelector('svg').classList.add('text-red-300');
            };

            previewImg.onload = function() {
                placeholder.querySelector('svg').classList.remove('text-red-300');
            };
        } else {
            previewImg.classList.add('hidden');
            placeholder.classList.remove('hidden');
            placeholder.querySelector('p').textContent = 'Không có ảnh xem trước';
            placeholder.querySelector('svg').classList.remove('text-red-300');
            clearBtn.classList.add('hidden');
        }
    }

    function clearThumbnail() {
        document.getElementById('thumbnail_url').value = '';
        previewThumbnail();
    }

    window.onload = function() {
        previewThumbnail();
    };
</script>
@endsection