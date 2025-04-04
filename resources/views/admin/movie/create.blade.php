@extends('layouts.admin')

@section('title', 'Add New Movie')
@section('page-title', 'Add New Movie')

@section('styles')
<style>
    .preview-image {
        max-height: 200px;
        max-width: 100%;
        object-fit: contain;
    }

    .form-section {
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-section:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <form action="{{ route('admin.movie.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-600">Please correct the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        @if(session('error'))

        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-600">{{ session('error') }}</h3>
                </div>
            </div>
        </div>
        @endif
        <!-- Basic Information -->
        <div class="bg-white p-6 rounded-lg shadow form-section">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Movie Title <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="origin_name" class="block text-sm font-medium text-gray-700 mb-1">Original Title</label>
                    <input type="text" name="origin_name" id="origin_name" value="{{ old('origin_name') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" id="type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="movie" {{ old('type') == 'movie' ? 'selected' : '' }}>Movie</option>
                        <option value="series" {{ old('type') == 'series' ? 'selected' : '' }}>Series</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Release Year</label>
                    <input type="number" name="year" id="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 5 }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="genres" class="block text-sm font-medium text-gray-700 mb-1">Genres</label>
                    <input type="text" name="genres" id="genres" value="{{ old('genres') }}" placeholder="Action, Adventure, Comedy, etc."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        <!-- Details Section -->
        <div class="bg-white p-6 rounded-lg shadow form-section">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Movie Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="director" class="block text-sm font-medium text-gray-700 mb-1">Director</label>
                    <input type="text" name="director" id="director" value="{{ old('director') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="actor" class="block text-sm font-medium text-gray-700 mb-1">Actors</label>
                    <input type="text" name="actor" id="actor" value="{{ old('actor') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Media Section -->
        <div class="bg-white p-6 rounded-lg shadow form-section">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Media Content</h2>

            <!-- Poster -->
            <div class="mb-8">
                <h3 class="text-md font-medium text-gray-700 mb-2">Movie Poster</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="use_poster_url" class="toggle-source mr-2" data-target="poster">
                    <label for="use_poster_url" class="text-sm text-gray-600">Use URL instead of uploading file</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <div class="file-input-container" id="poster_file_container">
                            <label for="poster_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Poster Image</label>
                            <input type="file" name="poster_file" id="poster_file" accept="image/*" class="media-file w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div class="url-input-container hidden" id="poster_url_container">
                            <label for="poster_url" class="block text-sm font-medium text-gray-700 mb-1">Poster URL</label>
                            <input type="url" name="poster_url" id="poster_url" value="{{ old('poster_url') }}" placeholder="https://example.com/image.jpg" class="media-url w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <p class="block text-sm font-medium text-gray-700 mb-1">Preview</p>
                        <div class="border rounded-md h-48 flex items-center justify-center bg-gray-50" id="poster_preview_container">
                            <p class="text-gray-400 text-sm" id="poster_placeholder">No poster selected</p>
                            <img src="" alt="Poster Preview" class="hidden preview-image" id="poster_preview">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thumbnail -->
            <div class="mb-8">
                <h3 class="text-md font-medium text-gray-700 mb-2">Movie Thumbnail</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="use_thumbnail_url" class="toggle-source mr-2" data-target="thumbnail">
                    <label for="use_thumbnail_url" class="text-sm text-gray-600">Use URL instead of uploading file</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <div class="file-input-container" id="thumbnail_file_container">
                            <label for="thumbnail_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Thumbnail Image</label>
                            <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*" class="media-file w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <div class="url-input-container hidden" id="thumbnail_url_container">
                            <label for="thumbnail_url" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                            <input type="url" name="thumbnail_url" id="thumbnail_url" value="{{ old('thumbnail_url') }}" placeholder="https://example.com/thumbnail.jpg" class="media-url w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <p class="block text-sm font-medium text-gray-700 mb-1">Preview</p>
                        <div class="border rounded-md h-48 flex items-center justify-center bg-gray-50" id="thumbnail_preview_container">
                            <p class="text-gray-400 text-sm" id="thumbnail_placeholder">No thumbnail selected</p>
                            <img src="" alt="Thumbnail Preview" class="hidden preview-image" id="thumbnail_preview">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trailer -->
            <div class="mb-4">
                <h3 class="text-md font-medium text-gray-700 mb-2">Movie Trailer</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="use_trailer_url" class="toggle-source mr-2" data-target="trailer">
                    <label for="use_trailer_url" class="text-sm text-gray-600">Use URL instead of uploading file</label>
                </div>

                <div class="file-input-container" id="trailer_file_container">
                    <label for="trailer_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Trailer Video</label>
                    <input type="file" name="trailer_file" id="trailer_file" accept="video/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div class="url-input-container hidden" id="trailer_url_container">
                    <label for="trailer_url" class="block text-sm font-medium text-gray-700 mb-1">Trailer URL</label>
                    <input type="url" name="trailer_url" id="trailer_url" value="{{ old('trailer_url') }}" placeholder="https://example.com/trailer.mp4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.movie.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-opacity-50 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 transition-colors duration-200">
                Create Movie
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Handle slug generation
    document.getElementById('name').addEventListener('blur', function() {
        if (!document.getElementById('slug').value) {
            const name = this.value;
            const slug = name.toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Loại bỏ dấu tiếng Việt
                .replace(/[^\w\s-]/g, '') // Loại bỏ ký tự đặc biệt
                .replace(/\s+/g, '-') // Thay khoảng trắng bằng dấu gạch ngang
                .replace(/-+/g, '-'); // Loại bỏ dấu gạch ngang thừa
            document.getElementById('slug').value = slug;
        }
    });

    // Handle media source toggle
    document.querySelectorAll('.toggle-source').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const target = this.dataset.target;
            const fileContainer = document.getElementById(`${target}_file_container`);
            const urlContainer = document.getElementById(`${target}_url_container`);

            if (this.checked) {
                fileContainer.classList.add('hidden');
                urlContainer.classList.remove('hidden');
                // Clear file input when switching to URL
                const fileInput = document.getElementById(`${target}_file`);
                if (fileInput) fileInput.value = '';
            } else {
                fileContainer.classList.remove('hidden');
                urlContainer.classList.add('hidden');
                // Clear URL input when switching to file
                const urlInput = document.getElementById(`${target}_url`);
                if (urlInput) urlInput.value = '';
            }
        });
    });

    // Handle image preview
    function setupImagePreview(inputId, previewId, placeholderId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);

        if (input) {
            input.addEventListener('change', function() {
                if (inputId.includes('file')) {
                    // File input
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                } else {
                    // URL input
                    if (this.value) {
                        preview.src = this.value;
                        preview.classList.remove('hidden');
                        placeholder.classList.add('hidden');

                        // Handle errors for URLs
                        preview.onerror = function() {
                            preview.classList.add('hidden');
                            placeholder.classList.remove('hidden');
                            placeholder.textContent = 'Invalid image URL';
                        };
                    } else {
                        preview.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                        placeholder.textContent = 'No image selected';
                    }
                }
            });
        }
    }

    // Setup image previews
    setupImagePreview('poster_file', 'poster_preview', 'poster_placeholder');
    setupImagePreview('poster_url', 'poster_preview', 'poster_placeholder');
    setupImagePreview('thumbnail_file', 'thumbnail_preview', 'thumbnail_placeholder');
    setupImagePreview('thumbnail_url', 'thumbnail_preview', 'thumbnail_placeholder');
</script>
@endsection