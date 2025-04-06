@extends('layouts.admin')

@section('title', 'Thêm Gói Cước')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Thêm Gói Cước Mới</h1>
            <p class="mt-1 text-sm text-gray-600">Tạo gói cước mới với các thông tin và phim đi kèm</p>
        </div>

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

        <form action="{{ route('admin.package.store') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Phần thông tin gói cước -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Thông tin gói cước</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên gói cước</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá (VNĐ)</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('price')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="duration_days" class="block text-sm font-medium text-gray-700 mb-1">Thời hạn (ngày)</label>
                        <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('duration_days')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', true) ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Kích hoạt gói cước</label>
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="features" class="block text-sm font-medium text-gray-700 mb-1">Tính năng (mỗi dòng 1 tính năng)</label>
                        <textarea name="features" id="features" rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('features') }}</textarea>
                        @error('features')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Phần danh sách phim với thanh cuộn dọc -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">Chọn phim cho gói</h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative w-72">
                            <input type="text" id="movieSearch" placeholder="Tìm kiếm phim..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <select id="sortMovies" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="name_asc">Tên (A-Z)</option>
                            <option value="name_desc">Tên (Z-A)</option>
                            <option value="type_movie">Loại: Phim lẻ</option>
                            <option value="type_series">Loại: Series</option>
                        </select>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">
                                        <input type="checkbox" id="selectAll" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-16">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Ảnh</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tên phim</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Thể loại</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-20">Loại</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="movieList">
                                @foreach($movies as $movie)
                                <tr class="movie-item hover:bg-gray-50 transition-colors cursor-pointer" data-type="{{ $movie->type ?? 'movie' }}">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="checkbox" name="movie_ids[]" id="movie-{{ $movie->id }}" value="{{ $movie->id }}"
                                            {{ (is_array(old('movie_ids')) && in_array($movie->id, old('movie_ids'))) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $movie->id }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($movie->thumbnail_url)
                                        <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->name }}" class="h-12 w-16 object-cover rounded-md">
                                        @else
                                        <div class="h-12 w-16 bg-gray-100 rounded-md flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <label for="movie-{{ $movie->id }}" class="block text-sm font-medium text-gray-900 cursor-pointer">{{ $movie->name }}</label>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $movie->genres ?? 'Chưa có' }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $movie->type == 'series' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
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
                        Đã chọn: <span id="selectedCount" class="font-medium">0</span> phim | Tổng số phim: {{ $movies->total() }}
                    </div>

                    <div>
                        {{ $movies->links() }}
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.package.index') }}"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Tạo gói cước
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Tùy chỉnh thanh cuộn */
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
        const selectAllCheckbox = document.getElementById('selectAll');
        const movieItems = document.querySelectorAll('.movie-item');
        const checkboxes = document.querySelectorAll('input[name="movie_ids[]"]');
        const selectedCountElement = document.getElementById('selectedCount');
        const form = document.querySelector('form');

        // Lưu trữ các phim đã chọn để giữ lại khi chuyển trang
        let selectedMovies = new Set();

        // Khởi tạo với những phim đã chọn sẵn
        document.querySelectorAll('input[name="movie_ids[]"]:checked').forEach(checkbox => {
            selectedMovies.add(checkbox.value);
        });

        // Cập nhật số lượng phim đã chọn
        function updateSelectedCount() {
            selectedCountElement.textContent = selectedMovies.size;
        }

        updateSelectedCount();

        // Xử lý checkbox "Chọn tất cả"
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                if (isChecked) {
                    selectedMovies.add(checkbox.value);
                } else {
                    selectedMovies.delete(checkbox.value);
                }
            });
            updateSelectedCount();
            saveFormData(); // Lưu dữ liệu form khi thay đổi
        });

        // Cập nhật số lượng khi checkbox thay đổi
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedMovies.add(this.value);
                } else {
                    selectedMovies.delete(this.value);
                }
                // Cập nhật trạng thái của checkbox "Chọn tất cả"
                selectAllCheckbox.checked = document.querySelectorAll('input[name="movie_ids[]"]').length === document.querySelectorAll('input[name="movie_ids[]"]:checked').length;
                updateSelectedCount();
                saveFormData(); // Lưu dữ liệu form khi thay đổi
            });
        });

        // Tìm kiếm phim
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            movieItems.forEach(item => {
                const movieName = item.querySelector('label').textContent.toLowerCase();
                item.style.display = movieName.includes(searchTerm) ? '' : 'none';
            });
        });

        // Click vào dòng để chọn checkbox
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

                    // Cập nhật trạng thái của checkbox "Chọn tất cả"
                    selectAllCheckbox.checked = document.querySelectorAll('input[name="movie_ids[]"]').length === document.querySelectorAll('input[name="movie_ids[]"]:checked').length;
                    updateSelectedCount();
                    saveFormData(); // Lưu dữ liệu form khi thay đổi
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
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return 1;
                    if (aType !== 'series' && bType === 'series') return -1;
                    return a.querySelector('label').textContent.localeCompare(b.querySelector('label').textContent);
                } else if (sortValue === 'type_series') {
                    const aType = a.getAttribute('data-type');
                    const bType = b.getAttribute('data-type');
                    if (aType === 'series' && bType !== 'series') return -1;
                    if (aType !== 'series' && bType === 'series') return 1;
                    return a.querySelector('label').textContent.localeCompare(b.querySelector('label').textContent);
                }
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        });

        // Hàm lưu dữ liệu form vào localStorage
        function saveFormData() {
            const formData = {
                name: document.getElementById('name').value,
                price: document.getElementById('price').value,
                duration_days: document.getElementById('duration_days').value,
                is_active: document.getElementById('is_active').checked,
                description: document.getElementById('description').value,
                features: document.getElementById('features').value,
                selectedMovies: Array.from(selectedMovies)
            };
            localStorage.setItem('packageFormData', JSON.stringify(formData));
        }

        // Hàm khôi phục dữ liệu form từ localStorage
        function restoreFormData() {
            const savedData = localStorage.getItem('packageFormData');
            if (savedData) {
                const formData = JSON.parse(savedData);

                // Khôi phục thông tin gói cước
                document.getElementById('name').value = formData.name || '';
                document.getElementById('price').value = formData.price || '';
                document.getElementById('duration_days').value = formData.duration_days || '';
                document.getElementById('is_active').checked = formData.is_active || false;
                document.getElementById('description').value = formData.description || '';
                document.getElementById('features').value = formData.features || '';

                // Khôi phục các phim đã chọn
                formData.selectedMovies.forEach(id => {
                    const checkbox = document.getElementById(`movie-${id}`);
                    if (checkbox) {
                        checkbox.checked = true;
                        selectedMovies.add(id);
                    }
                });

                // Cập nhật trạng thái của checkbox "Chọn tất cả"
                selectAllCheckbox.checked = document.querySelectorAll('input[name="movie_ids[]"]').length === document.querySelectorAll('input[name="movie_ids[]"]:checked').length;
                updateSelectedCount();
            }
        }

        // Lưu dữ liệu form khi người dùng nhập
        document.getElementById('name').addEventListener('input', saveFormData);
        document.getElementById('price').addEventListener('input', saveFormData);
        document.getElementById('duration_days').addEventListener('input', saveFormData);
        document.getElementById('is_active').addEventListener('change', saveFormData);
        document.getElementById('description').addEventListener('input', saveFormData);
        document.getElementById('features').addEventListener('input', saveFormData);

        // Xử lý phân trang để lưu dữ liệu đã chọn
        document.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                e.preventDefault();

                // Lưu dữ liệu form trước khi chuyển trang
                saveFormData();

                // Chuyển đến trang đích
                window.location.href = e.target.href;
            }
        });

        // Khi trang tải, khôi phục dữ liệu form
        restoreFormData();

        // Thêm sự kiện submit form
        form.addEventListener('submit', function(e) {
            // Đảm bảo rằng tất cả các phim đã chọn đều được gửi đi
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

            // Xóa dữ liệu trong localStorage sau khi submit
            localStorage.removeItem('packageFormData');
        });

        // Xóa dữ liệu trong localStorage khi người dùng nhấn "Hủy bỏ"
        const cancelUrl = "{{ route('admin.package.index') }}";
        document.querySelector(`a[href="${cancelUrl}"]`).addEventListener('click', function(e) {
            localStorage.removeItem('packageFormData');
        });
    });
</script>
@endsection
@endsection