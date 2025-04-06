@extends('layouts.admin')

@section('title', 'Quản lý Payments')
@section('page-title', 'Quản lý Payments')

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

    <!-- Bảng danh sách payments -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-4 font-semibold">ID</th>
                        <th class="p-4 font-semibold">User</th>
                        <th class="p-4 font-semibold">Subscription</th>
                        <th class="p-4 font-semibold">Amount</th>
                        <th class="p-4 font-semibold">Method</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @forelse ($payments as $payment)
                    <tr class="border-b hover:bg-gray-50 transition duration-150">
                        <td class="p-4">{{ $payment->id }}</td>
                        <td class="p-4">{{ $payment->user->username ?? 'N/A' }}</td>
                        <td class="p-4">{{ $payment->subscription->package->name ?? 'N/A' }}</td>
                        <td class="p-4">{{ number_format($payment->amount) }} VND</td>
                        <td class="p-4">{{ $payment->payment_method }}</td>
                        <td class="p-4">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs {{ $payment->status === 'success' ? 'bg-green-100 text-green-800' : ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="p-4 flex space-x-2">
                            <button onclick="openEditModal('{{ $payment->id }}')" class="bg-blue-600 text-white px-3 py-1 rounded-lg hover:bg-blue-700 transition duration-200">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button onclick="openDeleteModal('{{ $payment->id }}')" class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 transition duration-200">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">Không có thanh toán nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Phân trang -->
    <div class="mt-6">
        {{ $payments->links('pagination::tailwind') }}
    </div>

    <!-- Modal Chỉnh sửa -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Chỉnh sửa Payment</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Status</label>
                    <select name="status" id="editStatus" class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="success">Success</option>
                        <option value="pending">Pending</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
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
            <p class="text-gray-600">Bạn có chắc muốn xóa payment này không?</p>
            <div class="flex justify-end space-x-2 mt-4">
                <button onclick="closeModal('deleteModal')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">Hủy</button>
                <button onclick="submitDelete()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition duration-200">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    let currentPaymentId = null;

    // Mở modal chỉnh sửa
    function openEditModal(id) {
        currentPaymentId = id;
        axios.get(`/admin/payments/${id}/edit`)
            .then(response => {
                const payment = response.data.payment;
                document.getElementById('editStatus').value = payment.status;
                document.getElementById('editModal').classList.remove('hidden');
            })
            .catch(error => alert('Lỗi khi lấy dữ liệu: ' + error));
    }

    // Mở modal xóa
    function openDeleteModal(id) {
        currentPaymentId = id;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    // Đóng modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    // Gửi form chỉnh sửa
    function submitEditForm() {
        const status = document.getElementById('editStatus').value;
        axios.put(`/admin/payments/${currentPaymentId}`, {
                status
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
        axios.delete(`/admin/payments/${currentPaymentId}`, {
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