@php
// Xác định trạng thái ban đầu dựa trên URL hiện có (Dùng ở nhiều nơi nên khai báo ở đây)
$isVideoUrlAbsolute = $episode->video_url && (str_starts_with($episode->video_url, 'http://') || str_starts_with($episode->video_url, 'https://'));
$isThumbnailUrlAbsolute = $episode->thumbnail_url && (str_starts_with($episode->thumbnail_url, 'http://') || str_starts_with($episode->thumbnail_url, 'https://'));
@endphp

@extends('layouts.admin')

@section('title', 'Edit Episode')
@section('page-title', 'Edit Episode')

@section('styles')
<style>
    /* --- Basic Styles --- */
    .preview-image {
        max-height: 200px;
        max-width: 100%;
        object-fit: contain;
        border-radius: 0.375rem;
        /* rounded-md */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .form-section {
        border-bottom: 1px solid #e5e7eb;
        /* border-gray-200 */
        padding-bottom: 1.5rem;
        /* pb-6 */
        margin-bottom: 1.5rem;
        /* mb-6 */
    }

    .form-section:last-child {
        border-bottom: none;
    }

    /* --- Current Media Preview --- */
    .media-preview {
        position: relative;
        margin-bottom: 1rem;
        /* mb-4 */
    }

    .media-preview .current-media {
        background-color: rgba(0, 0, 0, 0.03);
        border-radius: 0.375rem;
        /* rounded-md */
        padding: 1rem;
        /* p-4 */
        border: 1px solid #e5e7eb;
        /* border-gray-200 */
    }

    .media-preview .current-media-label {
        background-color: rgba(79, 70, 229, 0.1);
        /* bg-indigo-100 */
        color: rgb(79, 70, 229);
        /* text-indigo-700 */
        border-radius: 9999px;
        /* rounded-full */
        padding: 0.25rem 0.75rem;
        /* px-3 py-1 */
        font-size: 0.75rem;
        /* text-xs */
        font-weight: 500;
        /* font-medium */
        display: inline-block;
        margin-bottom: 0.75rem;
        /* mb-3 */
    }

    /* --- Radio Button Options --- */
    .radio-option {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        /* mb-2 */
    }

    .radio-option input[type="radio"] {
        margin-right: 0.5rem;
        /* mr-2 */
        color: rgb(79, 70, 229);
        /* text-indigo-600 */
        width: 1rem;
        /* w-4 */
        height: 1rem;
        /* h-4 */
        border-color: #9ca3af;
        /* border-gray-400 */
    }

    .radio-option input[type="radio"]:focus {
        @apply ring-2 ring-offset-2 ring-indigo-500;
        /* focus:ring-indigo-500 */
    }

    .radio-option label {
        font-size: 0.875rem;
        /* text-sm */
        color: #374151;
        /* text-gray-700 */
        cursor: pointer;
    }

    /* --- Disabled Input Styling --- */
    input:disabled,
    textarea:disabled {
        background-color: #f3f4f6;
        /* bg-gray-100 */
        cursor: not-allowed;
        opacity: 0.7;
    }

    .file-input-disabled {
        /* Custom class added by JS */
        cursor: not-allowed;
    }

    .file-input-disabled .file\:bg-indigo-50 {
        background-color: #e5e7eb !important;
        /* bg-gray-200 */
        color: #9ca3af !important;
        /* text-gray-400 */
    }

    .file-input-disabled .file\:text-indigo-700 {
        color: #9ca3af !important;
        /* text-gray-400 */
    }

    .file-input-disabled:hover .file\:bg-indigo-100 {
        background-color: #e5e7eb !important;
        /* bg-gray-200 */
    }

    /* --- New Preview Area Styling --- */
    .new-preview-area {
        margin-top: 1rem;
        /* mt-4 */
        border: 2px dashed #d1d5db;
        /* border-dashed border-gray-300 */
        padding: 1rem;
        /* p-4 */
        border-radius: 0.375rem;
        /* rounded-md */
        min-height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        background-color: #f9fafb;
        /* bg-gray-50 */
        overflow: hidden;
        /* Hide potential overflow from previews */
    }

    .new-preview-area.hidden {
        display: none;
    }

    .new-preview-area video,
    .new-preview-area img,
    .new-preview-area iframe {
        max-width: 100%;
        max-height: 300px;
        /* Limit preview height */
        border-radius: 0.25rem;
        /* rounded-sm */
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        object-fit: contain;
        /* Ensure image/video fits */
    }

    .new-preview-area .preview-placeholder,
    .new-preview-area .preview-error {
        color: #6b7280;
        /* text-gray-500 */
        font-size: 0.875rem;
        /* text-sm */
    }

    .new-preview-area .preview-error {
        color: #dc2626;
        /* text-red-600 */
        font-weight: 500;
        /* font-medium */
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    {{-- Thông báo lỗi server (Validation etc.) --}}
    @if ($errors->any())
    <div id="message" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra khi gửi biểu mẫu</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Chỉnh sửa tập phim cho: {{ $movie->name }}</h3>

        {{-- Thông báo lỗi client-side (JS validation) --}}
        <div id="error-message" class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm hidden">
            <p id="error-text"></p>
        </div>

        <form action="{{ route('admin.movie.episodes.update', ['movie' => $movie, 'episode' => $episode]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Thông tin cơ bản --}}
            <div class="form-section">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $episode->title) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    {{-- Slug --}}
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $episode->slug) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 bg-gray-50" readonly>
                        <p class="mt-1 text-xs text-gray-500">Tự động tạo từ tiêu đề.</p>
                    </div>
                    {{-- Episode Number --}}
                    <div>
                        <label for="episode_number" class="block text-sm font-medium text-gray-700 mb-1">Số tập <span class="text-red-500">*</span></label>
                        <input type="number" name="episode_number" id="episode_number" value="{{ old('episode_number', $episode->episode_number) }}" required min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    {{-- Release Date --}}
                    <div>
                        <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày phát hành <span class="text-red-500">*</span></label>
                        <input type="date" name="release_date" id="release_date" value="{{ old('release_date', $episode->release_date ? Carbon\Carbon::parse($episode->release_date)->format('Y-m-d') : '') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $episode->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Media Section --}}
            <div class="form-section">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Nội dung Media</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Video Section --}}
                    <div class="border border-gray-200 p-4 rounded-lg space-y-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Video</h3>

                        {{-- Xem trước Video hiện tại --}}
                        @if($episode->video_url)
                        <div class="media-preview">
                            <div class="current-media">
                                <span class="current-media-label">Video hiện tại</span>
                                <div class="flex justify-center items-center bg-black rounded-md overflow-hidden">
                                    @php
                                    $isVideoYouTube = false;
                                    $videoEmbedUrl = $episode->video_url; // Default to original URL
                                    if ($isVideoUrlAbsolute) {
                                    // Simple check for YouTube URLs (can be improved with regex)
                                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $episode->video_url, $match)) {
                                    $videoId = $match[1];
                                    $videoEmbedUrl = 'https://www.youtube.com/embed/' . $videoId; // Standard embed URL
                                    $isVideoYouTube = true;
                                    }
                                    // Add more platform checks here if needed (Vimeo, etc.)
                                    }
                                    @endphp

                                    @if($isVideoYouTube)
                                    <iframe src="{{ $videoEmbedUrl }}" class="w-full aspect-video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @elseif ($isVideoUrlAbsolute || !file_exists(public_path($episode->video_url)))
                                    {{-- If it's an absolute URL (not YouTube) or local file doesn't exist --}}
                                    <video src="{{ $episode->video_url }}" controls class="w-full aspect-video">Trình duyệt không hỗ trợ video. <a href="{{ $episode->video_url }}" target="_blank" class="text-indigo-600 hover:underline">Link trực tiếp</a></video>
                                    @else
                                    {{-- Assume it's a local file path --}}
                                    <video src="{{ asset($episode->video_url) }}" controls class="w-full aspect-video">Trình duyệt không hỗ trợ video.</video>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Lựa chọn nguồn Video --}}
                        <fieldset class="space-y-2">
                            <legend class="text-sm font-medium text-gray-700 mb-2">Chọn nguồn video mới:</legend>
                            <div class="radio-option">
                                <input type="radio" id="video_source_url" name="video_source_type" value="url" {{ $isVideoUrlAbsolute ? 'checked' : '' }}>
                                <label for="video_source_url">Sử dụng URL</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="video_source_file" name="video_source_type" value="file" {{ !$isVideoUrlAbsolute ? 'checked' : '' }}>
                                <label for="video_source_file">Tải lên file mới</label>
                            </div>
                        </fieldset>

                        {{-- Input Video --}}
                        <div class="space-y-4">
                            <div class="url-input-container {{ $isVideoUrlAbsolute ? '' : 'hidden' }}" id="video_url_container">
                                <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                                <input type="url" name="video_url" id="video_url" value="{{ old('video_url', $episode->video_url) }}" {{ !$isVideoUrlAbsolute ? 'disabled' : '' }} class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="https://example.com/video.mp4 hoặc YouTube URL">
                            </div>
                            <div class="file-input-container {{ !$isVideoUrlAbsolute ? '' : 'hidden' }}" id="video_file_container">
                                <label for="video_file" class="block text-sm font-medium text-gray-700 mb-1">Tải lên file video mới</label>
                                <input type="file" name="video_file" id="video_file" accept="video/*" {{ $isVideoUrlAbsolute ? 'disabled' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 {{ $isVideoUrlAbsolute ? 'file-input-disabled' : '' }}">
                                @if ($episode->video_url && !$isVideoUrlAbsolute) <p class="mt-1 text-xs text-gray-500">File hiện tại: {{ basename($episode->video_url) }}</p> @endif
                            </div>
                        </div>

                        {{-- Khu vực xem trước Video mới --}}
                        <div id="video-preview-area" class="new-preview-area hidden">
                            <span class="preview-placeholder">Xem trước video sẽ hiển thị ở đây</span>
                        </div>
                    </div>

                    {{-- Thumbnail Section --}}
                    <div class="border border-gray-200 p-4 rounded-lg space-y-4">
                        <h3 class="text-md font-semibold text-gray-800 mb-3">Thumbnail</h3>
                        {{-- Xem trước Thumbnail hiện tại --}}
                        @if($episode->thumbnail_url)
                        <div class="media-preview">
                            <div class="current-media">
                                <span class="current-media-label">Thumbnail hiện tại</span>
                                <div class="flex justify-center items-center">
                                    <img src="{{ $isThumbnailUrlAbsolute || !file_exists(public_path($episode->thumbnail_url)) ? $episode->thumbnail_url : asset($episode->thumbnail_url) }}" alt="Current thumbnail" class="preview-image">
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Lựa chọn nguồn Thumbnail --}}
                        <fieldset class="space-y-2">
                            <legend class="text-sm font-medium text-gray-700 mb-2">Chọn nguồn thumbnail mới:</legend>
                            <div class="radio-option">
                                <input type="radio" id="thumbnail_source_url" name="thumbnail_source_type" value="url" {{ $isThumbnailUrlAbsolute ? 'checked' : '' }}>
                                <label for="thumbnail_source_url">Sử dụng URL</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="thumbnail_source_file" name="thumbnail_source_type" value="file" {{ !$isThumbnailUrlAbsolute ? 'checked' : '' }}>
                                <label for="thumbnail_source_file">Tải lên file mới</label>
                            </div>
                        </fieldset>

                        {{-- Input Thumbnail --}}
                        <div class="space-y-4">
                            <div class="url-input-container {{ $isThumbnailUrlAbsolute ? '' : 'hidden' }}" id="thumbnail_url_container">
                                <label for="thumbnail_url" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                                <input type="url" name="thumbnail_url" id="thumbnail_url" value="{{ old('thumbnail_url', $episode->thumbnail_url) }}" {{ !$isThumbnailUrlAbsolute ? 'disabled' : '' }} class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="https://example.com/thumbnail.jpg">
                            </div>
                            <div class="file-input-container {{ !$isThumbnailUrlAbsolute ? '' : 'hidden' }}" id="thumbnail_file_container">
                                <label for="thumbnail_file" class="block text-sm font-medium text-gray-700 mb-1">Tải lên file thumbnail mới</label>
                                <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*" {{ $isThumbnailUrlAbsolute ? 'disabled' : '' }} class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 {{ $isThumbnailUrlAbsolute ? 'file-input-disabled' : '' }}">
                                @if ($episode->thumbnail_url && !$isThumbnailUrlAbsolute) <p class="mt-1 text-xs text-gray-500">File hiện tại: {{ basename($episode->thumbnail_url) }}</p> @endif
                            </div>
                        </div>

                        {{-- Khu vực xem trước Thumbnail mới --}}
                        <div id="thumbnail-preview-area" class="new-preview-area hidden">
                            <span class="preview-placeholder">Xem trước thumbnail sẽ hiển thị ở đây</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.movie.episodes', $movie) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-200 shadow-sm">Hủy bỏ</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-md transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cập nhật Tập phim</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- Function to hide server-side error messages after a delay ---
    function hiddenMessage() {
        const message = document.getElementById('message');
        if (message) {
            setTimeout(function() {
                // Use smooth transition for hiding
                message.style.transition = 'opacity 0.5s ease-out';
                message.style.opacity = '0';
                setTimeout(() => message.style.display = 'none', 500); // Fully hide after transition
            }, 10000); // Hide after 10 seconds
        }
    }

    // --- Function to convert text to a URL-friendly slug ---
    function convertToSlug(text) {
        const from = 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ';
        const to = 'aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydAAAAAAAAAAAAAAAAAEEEEEEEEEEEIIIIIOOOOOOOOOOOOOOOOOUUUUUUUUUUUYYYYYD';
        for (let i = 0; i < from.length; i++) {
            text = text.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        return text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '') // Remove invalid chars
            .replace(/[\s-]+/g, '-'); // Collapse whitespace and hyphens, replace with single hyphen
    }

    // --- Main script execution after DOM is loaded ---
    document.addEventListener('DOMContentLoaded', function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        // --- Input & Container References ---
        const videoUrlInput = document.getElementById('video_url');
        const videoFileInput = document.getElementById('video_file');
        const videoUrlContainer = document.getElementById('video_url_container');
        const videoFileContainer = document.getElementById('video_file_container');
        const videoSourceRadios = document.querySelectorAll('input[name="video_source_type"]');

        const thumbnailUrlInput = document.getElementById('thumbnail_url');
        const thumbnailFileInput = document.getElementById('thumbnail_file');
        const thumbnailUrlContainer = document.getElementById('thumbnail_url_container');
        const thumbnailFileContainer = document.getElementById('thumbnail_file_container');
        const thumbnailSourceRadios = document.querySelectorAll('input[name="thumbnail_source_type"]');

        // --- Preview Area References ---
        const videoPreviewArea = document.getElementById('video-preview-area');
        const thumbnailPreviewArea = document.getElementById('thumbnail-preview-area');

        // --- Error Message References ---
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        // --- Store Object URLs ---
        let currentVideoObjectURL = null;
        let currentThumbnailObjectURL = null;

        // --- Slug Generation Logic ---
        if (titleInput && slugInput) {
            titleInput.addEventListener('input', function() {
                slugInput.value = convertToSlug(this.value);
            });
            // Initial slug if title exists on load
            if (titleInput.value) {
                slugInput.value = convertToSlug(titleInput.value);
            }
        }

        // --- Helper: Update Preview Area Content ---
        function updatePreviewArea(areaElement, content, isError = false) {
            areaElement.innerHTML = ''; // Clear previous content
            if (content instanceof Element) {
                areaElement.appendChild(content);
                areaElement.classList.remove('hidden'); // Show the area
            } else if (typeof content === 'string') {
                const textElement = document.createElement('span');
                textElement.className = isError ? 'preview-error' : 'preview-placeholder';
                textElement.textContent = content;
                areaElement.appendChild(textElement);
                areaElement.classList.remove('hidden'); // Show the area
            } else {
                // If content is null or undefined, hide the area
                areaElement.classList.add('hidden');
            }
        }

        // --- Helper: Clear Preview Area and Show Placeholder ---
        function clearPreview(areaElement, placeholderText = "Xem trước sẽ hiển thị ở đây") {
            updatePreviewArea(areaElement, placeholderText); // Show placeholder
            areaElement.classList.add('hidden'); // Hide the area initially when cleared
        }

        // --- Helper: Get YouTube Embed URL from various YouTube link formats ---
        function getYouTubeEmbedUrl(url) {
            let videoId = null;
            // Regex to capture video ID from various YouTube URL formats
            const patterns = [
                /(?:https?:\/\/)?(?:www\.)?youtube\.com\/(?:watch\?v=|embed\/|v\/|)([\w-]{11})/, // Standard formats
                /(?:https?:\/\/)?(?:www\.)?youtu\.be\/([\w-]{11})/ // Shortened format
            ];

            for (const pattern of patterns) {
                const match = url.match(pattern);
                if (match && match[1]) {
                    videoId = match[1];
                    break;
                }
            }

            if (videoId) {
                return 'https://www.youtube.com/embed/' + videoId; // Return standard embed URL
            }
            return null; // Not a recognized YouTube URL
        }

        // --- Preview Logic: Video File Selection ---
        videoFileInput.addEventListener('change', function() {
            clearPreview(videoPreviewArea, 'Đang tải xem trước video...'); // Show loading state
            // Revoke old object URL if exists
            if (currentVideoObjectURL) {
                URL.revokeObjectURL(currentVideoObjectURL);
                currentVideoObjectURL = null;
            }

            const file = this.files[0];
            if (file && file.type.startsWith('video/')) {
                currentVideoObjectURL = URL.createObjectURL(file); // Create temporary URL
                const videoElement = document.createElement('video');
                videoElement.src = currentVideoObjectURL;
                videoElement.controls = true; // Add controls for preview
                videoElement.onerror = () => {
                    updatePreviewArea(videoPreviewArea, 'Lỗi: Không thể xem trước file video này.', true);
                    URL.revokeObjectURL(currentVideoObjectURL); // Clean up on error
                    currentVideoObjectURL = null;
                };
                videoElement.onloadedmetadata = () => { // Show only when ready
                    updatePreviewArea(videoPreviewArea, videoElement);
                }
                // Handle cases where metadata might not load quickly or at all
                setTimeout(() => {
                    if (videoElement.readyState === 0 && !videoPreviewArea.querySelector('video')) { // Check if not loaded after a delay
                        updatePreviewArea(videoPreviewArea, 'Lỗi: Không thể tải siêu dữ liệu video để xem trước.', true);
                        if (currentVideoObjectURL) URL.revokeObjectURL(currentVideoObjectURL);
                        currentVideoObjectURL = null;
                    }
                }, 3000); // Wait 3 seconds

            } else if (file) {
                // Handle invalid file type already validated by setupBasicFileValidation, but double-check
                updatePreviewArea(videoPreviewArea, 'Lỗi: File đã chọn không phải là video.', true);
                this.value = ''; // Clear the invalid selection
            } else {
                clearPreview(videoPreviewArea, 'Xem trước video sẽ hiển thị ở đây'); // No file selected
            }
        });

        // --- Preview Logic: Thumbnail File Selection ---
        thumbnailFileInput.addEventListener('change', function() {
            clearPreview(thumbnailPreviewArea, 'Đang tải xem trước ảnh...');
            // Revoke old object URL
            if (currentThumbnailObjectURL) {
                URL.revokeObjectURL(currentThumbnailObjectURL);
                currentThumbnailObjectURL = null;
            }

            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                currentThumbnailObjectURL = URL.createObjectURL(file);
                const imgElement = document.createElement('img');
                imgElement.src = currentThumbnailObjectURL;
                imgElement.alt = "Xem trước thumbnail";
                imgElement.classList.add('preview-image'); // Apply existing style
                imgElement.onerror = () => {
                    updatePreviewArea(thumbnailPreviewArea, 'Lỗi: Không thể xem trước file ảnh này.', true);
                    URL.revokeObjectURL(currentThumbnailObjectURL);
                    currentThumbnailObjectURL = null;
                };
                imgElement.onload = () => { // Show only when loaded
                    updatePreviewArea(thumbnailPreviewArea, imgElement);
                };
            } else if (file) {
                updatePreviewArea(thumbnailPreviewArea, 'Lỗi: File đã chọn không phải là ảnh.', true);
                this.value = '';
            } else {
                clearPreview(thumbnailPreviewArea, 'Xem trước thumbnail sẽ hiển thị ở đây');
            }
        });

        // --- Preview Logic: Video URL Input (on blur) ---
        videoUrlInput.addEventListener('blur', function() {
            clearPreview(videoPreviewArea, 'Đang tải xem trước từ URL...'); // Show loading state
            const url = this.value.trim();
            if (!url) {
                clearPreview(videoPreviewArea, 'Xem trước video sẽ hiển thị ở đây'); // Clear if empty
                return;
            };

            const youtubeEmbedUrl = getYouTubeEmbedUrl(url);

            if (youtubeEmbedUrl) {
                // Create YouTube Iframe
                const iframeElement = document.createElement('iframe');
                iframeElement.src = youtubeEmbedUrl;
                iframeElement.setAttribute('frameborder', '0');
                iframeElement.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                iframeElement.setAttribute('allowfullscreen', '');
                iframeElement.className = 'w-full aspect-video'; // Ensure it fits
                updatePreviewArea(videoPreviewArea, iframeElement);
            } else {
                // Try as Direct Video Link
                const videoElement = document.createElement('video');
                videoElement.src = url;
                videoElement.controls = true;
                let loaded = false; // Flag to prevent double error message
                videoElement.onerror = () => {
                    if (!loaded) updatePreviewArea(videoPreviewArea, 'Không thể tải xem trước từ URL này (Link không hợp lệ, không phải video trực tiếp, hoặc bị chặn CORS).', true);
                    loaded = true; // Set flag on error too
                };
                videoElement.onloadedmetadata = () => {
                    if (!loaded) updatePreviewArea(videoPreviewArea, videoElement);
                    loaded = true;
                }
                // Timeout check if metadata never loads
                setTimeout(() => {
                    if (!loaded) {
                        updatePreviewArea(videoPreviewArea, 'Không thể tải xem trước từ URL này (Timeout).', true);
                    }
                }, 5000); // 5 second timeout
            }
        });

        // --- Preview Logic: Thumbnail URL Input (on blur) ---
        thumbnailUrlInput.addEventListener('blur', function() {
            clearPreview(thumbnailPreviewArea, 'Đang tải xem trước ảnh từ URL...');
            const url = this.value.trim();
            if (!url) {
                clearPreview(thumbnailPreviewArea, 'Xem trước thumbnail sẽ hiển thị ở đây');
                return;
            }

            const imgElement = document.createElement('img');
            imgElement.src = url;
            imgElement.alt = "Xem trước thumbnail";
            imgElement.classList.add('preview-image'); // Use existing style
            let loaded = false;
            imgElement.onerror = () => {
                if (!loaded) updatePreviewArea(thumbnailPreviewArea, 'Không thể tải xem trước thumbnail từ URL này.', true);
                loaded = true;
            };
            imgElement.onload = () => {
                if (!loaded) updatePreviewArea(thumbnailPreviewArea, imgElement);
                loaded = true;
            }
            // Timeout check
            setTimeout(() => {
                if (!loaded) {
                    updatePreviewArea(thumbnailPreviewArea, 'Không thể tải xem trước thumbnail từ URL này (Timeout).', true);
                }
            }, 5000); // 5 second timeout
        });

        // --- Radio Button Toggle Logic (Handles enabling/disabling inputs and clearing previews) ---
        function setupRadioToggle(radios, urlInput, fileInput, urlContainer, fileContainer, previewArea, previewPlaceholder) {
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // 1. Clear Preview Area for this section
                    clearPreview(previewArea, previewPlaceholder);

                    // 2. Revoke Object URLs if switching away from file
                    if (fileInput === videoFileInput && currentVideoObjectURL && this.value !== 'file') {
                        URL.revokeObjectURL(currentVideoObjectURL);
                        currentVideoObjectURL = null;
                    }
                    if (fileInput === thumbnailFileInput && currentThumbnailObjectURL && this.value !== 'file') {
                        URL.revokeObjectURL(currentThumbnailObjectURL);
                        currentThumbnailObjectURL = null;
                    }

                    // 3. Enable/Disable inputs and toggle container visibility
                    if (this.value === 'url') {
                        urlInput.disabled = false;
                        fileInput.disabled = true;
                        fileInput.value = ''; // IMPORTANT: Clear file input value
                        urlContainer.classList.remove('hidden');
                        fileContainer.classList.add('hidden');
                        fileInput.classList.add('file-input-disabled'); // Add styling class
                        // Attempt preview if URL has value
                        if (urlInput.value) urlInput.dispatchEvent(new Event('blur'));

                    } else { // 'file'
                        urlInput.disabled = true;
                        // urlInput.value = ''; // Keep URL value if user switches back and forth? Optional.
                        fileInput.disabled = false;
                        urlContainer.classList.add('hidden');
                        fileContainer.classList.remove('hidden');
                        fileInput.classList.remove('file-input-disabled'); // Remove styling class
                        // Attempt preview if file already selected (unlikely needed after clear)
                        // if(fileInput.files.length > 0) fileInput.dispatchEvent(new Event('change'));
                    }
                });
            });
        }

        // --- Initialize Radio Button Logic ---
        setupRadioToggle(videoSourceRadios, videoUrlInput, videoFileInput, videoUrlContainer, videoFileContainer, videoPreviewArea, 'Xem trước video sẽ hiển thị ở đây');
        setupRadioToggle(thumbnailSourceRadios, thumbnailUrlInput, thumbnailFileInput, thumbnailUrlContainer, thumbnailFileContainer, thumbnailPreviewArea, 'Xem trước thumbnail sẽ hiển thị ở đây');

        // --- File Input Client-Side Validation (Basic type check) ---
        function setupBasicFileValidation(fileInput, allowedTypePrefix, message, previewArea, placeholder) {
            if (fileInput && errorMessage && errorText) {
                fileInput.addEventListener('change', function() {
                    errorMessage.classList.add('hidden'); // Hide previous JS errors
                    errorText.textContent = '';
                    const file = this.files[0];
                    if (file && !file.type.startsWith(allowedTypePrefix)) {
                        // Show client-side error message div
                        errorText.textContent = message;
                        errorMessage.classList.remove('hidden');
                        this.value = ''; // Clear the invalid file selection

                        // Also clear the preview area for this input
                        clearPreview(previewArea, placeholder); // Clear preview as well

                        setTimeout(() => { // Auto-hide client error
                            errorMessage.classList.add('hidden');
                            errorText.textContent = '';
                        }, 5000);
                    }
                });
            }
        }
        setupBasicFileValidation(videoFileInput, 'video/', 'Lỗi: File đã chọn không phải video.', videoPreviewArea, 'Xem trước video sẽ hiển thị ở đây');
        setupBasicFileValidation(thumbnailFileInput, 'image/', 'Lỗi: File đã chọn không phải ảnh.', thumbnailPreviewArea, 'Xem trước thumbnail sẽ hiển thị ở đây');


        // --- Initial Auto-hide for Server Errors ---
        hiddenMessage();

        // --- Trigger initial preview if inputs have values on load ---
        // Needs to happen AFTER radio toggle setup to ensure correct element is enabled
        if (videoUrlInput.value && !videoUrlInput.disabled) videoUrlInput.dispatchEvent(new Event('blur'));
        if (thumbnailUrlInput.value && !thumbnailUrlInput.disabled) thumbnailUrlInput.dispatchEvent(new Event('blur'));
        // Preview for pre-selected files is less common, but could be added if needed

    }); // End DOMContentLoaded
</script>
@endsection