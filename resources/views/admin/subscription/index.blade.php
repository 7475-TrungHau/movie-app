@extends('layouts.admin')

@section('title', 'Quản lý Subscriptions')
@section('page-title', 'Quản lý Subscriptions')

@section('content')
<div class="container mx-auto p-6">
    <!-- Thông báo -->
    @if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        {{ session('success') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
        {{ $errors->first() }}
    </div>
    @endif

    <!-- Nút thêm mới -->
    <div class="flex justify-end mb-6">
        <button id="openAddModal" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition duration-200">
            <i class="fas fa-plus mr-2"></i> Thêm mới Subscription
        </button>
    </div>

    <!-- Bảng danh sách subscriptions -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">User</th>
                        <th class="p-4 font-semibold">Package</th>
                        <th class="p-4 font-semibold">Start Date</th>
                        <th class="p-4 font-semibold">End Date</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse ($subscriptions as $subscription)
                    <tr class="border-b hover:bg-gray-50 transition duration-150">
                        <td class="p-4">{{ $subscription->id }}</td>
                        <td class="p-4">{{ $subscription->user->username ?? 'N/A' }}</td>
                        <td class="p-4">{{ $subscription->package->name ?? 'N/A' }}</td>
                        <td class="p-4">{{ \Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') }}</td>
                        <td class="p-4">{{ \Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : ($subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </td>
                        <td class="p-4 flex space-x-2">
                            <button onclick="openEditModal('{{ $subscription->id }}')" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button onclick="openDeleteModal('{{ $subscription->id }}')" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition duration-200">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Không có subscription nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="mt-6">
        {{ $subscriptions->links('pagination::tailwind') }}
    </div>

    <!-- Modal Thêm mới -->
    <div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Thêm mới Subscription</h2>
            <form id="addForm" action="{{ route('admin.subscriptions.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">User</label>
                    <select name="user_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="" disabled selected>Chọn User</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->username }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Package</label>
                    <select name="package_id" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
                        <option value="" disabled selected>Chọn Package</option>
                        @foreach ($packages as $package)
                        <option value="{{ $package->id }}">{{ $package->name }} ({{ number_format($package->price) }} VND)</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('addModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Hủy</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Chỉnh sửa -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Chỉnh sửa Subscription</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Status</label>
                    <select name="status" id="editStatus" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="editEndDate" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Hủy</button>
                    <button type="button" onclick="submitEditForm()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xóa -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Xác nhận xóa</h2>
            <p class="text-gray-600">Bạn có chắc muốn xóa subscription này không?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button onclick="closeModal('deleteModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Hủy</button>
                <button onclick="submitDelete()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentSubscriptionId = null;

    // Mở modal thêm mới
    document.getElementById('openAddModal').addEventListener('click', () => {
        document.getElementById('addModal').classList.remove('hidden');
    });

    // Mở modal chỉnh sửa
    function openEditModal(id) {
        currentSubscriptionId = id;
        axios.get(`/admin/subscriptions/${id}/edit`)
            .then(response => {
                const sub = response.data.subscription;
                document.getElementById('editStatus').value = sub.status;
                document.getElementById('editEndDate').value = sub.end_date.split(' ')[0];
                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => alert('Lỗi khi lấy dữ liệu: ' + error));
    }

    // Mở modal xóa
    function openDeleteModal(id) {
        currentSubscriptionId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Đóng modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Gửi form chỉnh sửa
    function submitEditForm() {
        const status = document.getElementById('editStatus').value;
        const endDate = document.getElementById('editEndDate').value;
        axios.put(`/admin/subscriptions/${currentSubscriptionId}`, {
                status,
                end_date: endDate
            }, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                alert(response.data.message);
                location.reload();
            })
            .catch(error => alert('Lỗi khi cập nhật: ' + error));
    }

    // Gửi yêu cầu xóa
    function submitDelete() {
        axios.delete(`/admin/subscriptions/${currentSubscriptionId}`, {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                alert(response.data.message);
                location.reload();
            })
            .catch(error => alert('Lỗi khi xóa: ' + error));
    }
</script>
@endsection