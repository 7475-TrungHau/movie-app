@extends('layouts.admin')

@section('title', 'Add Episode')
@section('page-title', 'Add New Episode')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="{
    videoSource: 'url',
    videoType: 'direct',
    videoUrl: '',
    videoFile: null,
    previewUrl: null,
    thumbnailSource: 'url',
    thumbnailUrl: '',
    thumbnailFile: null,
    thumbnailPreview: null,
    errorMessage: '',
    showError: false,
    title: '',
    slug: '',
    showErrorMessage(message) {
        this.errorMessage = message;
        this.showError = true;
        setTimeout(() => {
            this.showError = false;
            this.errorMessage = '';
        }, 5000);
    },
    convertToSlug(text) {
        const from = 'àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđÀÁẠẢÃÂẦẤẬẨẪĂẰẮẶẲẴÈÉẸẺẼÊỀẾỆỂỄÌÍỊỈĨÒÓỌỎÕÔỒỐỘỔỖƠỜỚỢỞỠÙÚỤỦŨƯỪỨỰỬỮỲÝỴỶỸĐ';
        const to = 'aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyydAAAAAAAAAAAAAAAAAEEEEEEEEEEEIIIIIOOOOOOOOOOOOOOOOOUUUUUUUUUUUYYYYYD';
        for (let i = 0; i < from.length; i++) {
            text = text.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }
        return text
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '') 
            .replace(/[\s-]+/g, '-');
    },
    handleTitleInput() {
        this.slug = this.convertToSlug(this.title);
    },
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('video/')) {
                this.videoFile = file;
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.showErrorMessage('Vui lòng chọn file video hợp lệ');
                event.target.value = '';
                this.videoFile = null;
                this.previewUrl = null;
            }
        }
    },
    handleUrlChange() {
        if (this.videoType === 'youtube') {
            const videoId = this.extractYoutubeId(this.videoUrl);
            if (videoId) {
                this.previewUrl = `https://www.youtube.com/embed/${videoId}`;
            } else {
                this.showErrorMessage('URL Youtube không hợp lệ');
                this.previewUrl = null;
            }
        } else if (this.videoType === 'facebook') {
            if (this.videoUrl.includes('facebook.com') || this.videoUrl.includes('fb.watch')) {
                this.previewUrl = this.videoUrl;
            } else {
                this.showErrorMessage('URL Facebook không hợp lệ');
                this.previewUrl = null;
            }
        } else {
            this.previewUrl = this.videoUrl;
        }
    },
    extractYoutubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    },
    handleThumbnailFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                this.thumbnailFile = file;
                this.thumbnailPreview = URL.createObjectURL(file);
            } else {
                this.showErrorMessage('Vui lòng chọn file ảnh hợp lệ');
                event.target.value = '';
                this.thumbnailFile = null;
                this.thumbnailPreview = null;
            }
        }
    },
    handleThumbnailUrlChange() {
        if (this.thumbnailUrl) {
            this.thumbnailPreview = this.thumbnailUrl;
        } else {
            this.thumbnailPreview = null;
        }
    }


}">
    @if ($errors->any())
    <div id="message" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Add New Episode for: {{ $movie->name }}</h3>

        <!-- Error Message -->
        <div x-show="showError" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
            <template x-if="showError">
                @if ($errors->any())
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li x-init="errorMessage += '{{ $error }}' + '\n'">{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                <p x-text="errorMessage"></p>
            </template>
        </div>

        <form action="{{ route('admin.movie.episodes.store', $movie) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" required x-model="title" @input="handleTitleInput"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" required x-model="slug"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Episode Number -->
                <div>
                    <label for="episode_number" class="block text-sm font-medium text-gray-700 mb-1">Episode Number</label>
                    <input type="number" name="episode_number" id="episode_number" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Release Date -->
                <div>
                    <label for="release_date" class="block text-sm font-medium text-gray-700 mb-1">Release Date</label>
                    <input type="date" name="release_date" id="release_date" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"></textarea>
                </div>

                <!-- Video Source Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Video Source</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_source" value="url" x-model="videoSource"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Video URL</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_source" value="file" x-model="videoSource"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Upload File</span>
                        </label>
                    </div>
                </div>

                <!-- Video URL Type Selection -->
                <div class="md:col-span-2" x-show="videoSource === 'url'">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Video URL Type</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="direct" x-model="videoType"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Direct URL</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="youtube" x-model="videoType"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">YouTube</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="video_type" value="facebook" x-model="videoType"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Facebook</span>
                        </label>
                    </div>
                </div>

                <!-- Video URL Input -->
                <div class="md:col-span-2" x-show="videoSource === 'url'">
                    <label for="video_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <span x-text="videoType === 'youtube' ? 'YouTube URL' : (videoType === 'facebook' ? 'Facebook Video URL' : 'Direct Video URL')"></span>
                    </label>
                    <input type="url" name="video_url" id="video_url" x-model="videoUrl" @input="handleUrlChange"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        :placeholder="videoType === 'youtube' ? 'https://www.youtube.com/watch?v=...' : (videoType === 'facebook' ? 'https://www.facebook.com/watch?v=...' : 'https://example.com/video.mp4')">
                </div>

                <!-- Video File Upload -->
                <div class="md:col-span-2" x-show="videoSource === 'file'">
                    <label for="video_file" class="block text-sm font-medium text-gray-700 mb-1">Video File</label>
                    <input type="file" name="video_file" id="video_file" accept="video/*" @change="handleFileChange"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Video Preview -->
                <div class="md:col-span-2" x-show="previewUrl">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Video Preview</label>
                    <template x-if="videoType === 'youtube'">
                        <iframe :src="previewUrl" class="w-full rounded-lg shadow-sm" style="height: 400px;"
                            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                    </template>
                    <template x-if="videoType !== 'youtube'">
                        <video x-bind:src="previewUrl" controls class="w-full rounded-lg shadow-sm"
                            style="max-height: 400px;">Your browser does not support the video tag.</video>
                    </template>
                </div>

                <!-- Thumbnail Source Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Thumbnail Source</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="thumbnail_source" value="url" x-model="thumbnailSource"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Image URL</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="thumbnail_source" value="file" x-model="thumbnailSource"
                                class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Upload Image</span>
                        </label>
                    </div>
                </div>

                <!-- Thumbnail URL Input -->
                <div class="md:col-span-2" x-show="thumbnailSource === 'url'">
                    <label for="thumbnail_url" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" id="thumbnail_url" x-model="thumbnailUrl" @input="handleThumbnailUrlChange"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                        placeholder="https://example.com/thumbnail.jpg">
                </div>

                <!-- Thumbnail File Upload -->
                <div class="md:col-span-2" x-show="thumbnailSource === 'file'">
                    <label for="thumbnail_file" class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Image</label>
                    <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*" @change="handleThumbnailFileChange"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                </div>

                <!-- Thumbnail Preview -->
                <div class="md:col-span-2" x-show="thumbnailPreview">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thumbnail Preview</label>
                    <img :src="thumbnailPreview" class="w-full max-w-md rounded-lg shadow-sm" style="max-height: 300px;">
                </div>


            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.movie.episodes', $movie) }}"
                    class="mr-4 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition duration-300">
                    Save Episode
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function hiddenMessage() {
        setTimeout(function() {
            document.getElementById('message').style.display = 'none';
        }, 30000);
    };

    hiddenMessage();
</script>
@endsection