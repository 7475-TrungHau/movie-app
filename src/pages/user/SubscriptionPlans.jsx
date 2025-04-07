import React, { useState, useEffect } from 'react';
import axios from 'axios';

function SubscriptionPlans() {
    const [plans, setPlans] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedPlan, setSelectedPlan] = useState(null); // State để lưu gói được chọn
    const [paymentMethod, setPaymentMethod] = useState('vnpay'); // State để lưu phương thức thanh toán

    const user = JSON.parse(localStorage.getItem('user')) ?? null;
    const token = localStorage.getItem('token');

    if (!token) {
        window.location.href = '/login'; // Chuyển hướng nếu không có token
    }

    useEffect(() => {
        document.title = "Chọn Gói Đăng Ký - PlayFilm";

        const fetchPlans = async () => {
            try {
                const response = await axios.get('http://localhost:8000/api/payment/packages', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                });
                setPlans(response.data);
                setLoading(false);
            } catch (err) {
                setError('Không thể tải danh sách gói');
                setLoading(false);
            }
        };

        fetchPlans();
    }, []);

    // Hàm xử lý khi chọn gói
    const handleSelectPlan = (plan) => {
        if (user.package_name !== plan.name) { // Chỉ cho chọn nếu chưa đăng ký gói đó
            setSelectedPlan(plan);
        }
    };

    // Hàm xử lý khi xác nhận thanh toán
    const handleConfirmPayment = async () => {
        if (!selectedPlan) return;

        try {
            const response = await axios.get(`http://localhost:8000/api/payment/vnpay/${selectedPlan.id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });

            if (response.data.status === 'success') {
                console.log('Redirecting to payment URL:', response.data);

                window.location.href = response.data.url;
            } else {
                alert('Có lỗi khi khởi tạo thanh toán');
            }
        } catch (error) {
            console.error('Error initiating payment:', error);
            alert('Không thể kết nối đến cổng thanh toán');
        }
    };

    if (loading) {
        return <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center">Đang tải...</div>;
    }

    if (error) {
        return <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center">{error}</div>;
    }

    return (
        <div className="min-h-screen bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 text-white mt-16 w-full mb-10">
            <div className="max-w-4xl mx-auto">
                <h1 className="text-3xl font-extrabold text-center mb-8">Chọn Gói Phù Hợp Với Bạn</h1>
                <p className="text-center text-gray-400 mb-12">
                    Thưởng thức hàng ngàn bộ phim và chương trình truyền hình chất lượng cao.
                </p>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {plans.map((plan) => (
                        <div
                            key={plan.id}
                            className={`bg-gray-800 rounded-lg shadow-lg p-6 flex flex-col ${plan.is_popular ? 'border-2 border-red-500 relative' : ''}`}
                        >
                            {plan.is_popular && (
                                <span className="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                                    Phổ biến
                                </span>
                            )}
                            <h2 className="text-xl font-semibold mb-4">{plan.name}</h2>
                            <p className="text-3xl font-bold mb-6 flex justify-between">
                                {plan.price == 0 ? 'Miễn Phí' : `${plan.price.toLocaleString('vi-VN')}đ`}
                                <p className="text-gray-400 mb-4 text-sm">{plan.duration_days} ngày</p>
                            </p>
                            <ul className="space-y-2 mb-8 flex-grow">
                                {plan.features ? (
                                    plan.features.split(/,|\n/).map((feature, index) => (
                                        <li key={index} className="flex items-center">
                                            <svg
                                                className="w-5 h-5 text-green-500 mr-2 flex-shrink-0"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M5 13l4 4L19 7"
                                                ></path>
                                            </svg>
                                            <span className="text-gray-300">{feature.trim()}</span>
                                        </li>
                                    ))
                                ) : (
                                    <li className="text-gray-300">Không có tính năng đặc biệt</li>
                                )}
                            </ul>
                            {user.package_name === plan.name ? (
                                <button
                                    className="text-white font-bold py-2 px-4 rounded bg-gray-700 cursor-not-allowed"
                                    disabled
                                >
                                    Đã Đăng Ký
                                </button>
                            ) : (
                                <button
                                    className="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-600 cursor-pointer"
                                    onClick={() => handleSelectPlan(plan)}
                                >
                                    Đăng Ký Ngay
                                </button>
                            )}
                        </div>
                    ))}
                </div>

                {/* Phần hiển thị thông tin chi tiết khi chọn gói */}
                {selectedPlan && (
                    <div className="mt-12 bg-gray-800 rounded-lg p-6 max-w-2xl mx-auto">
                        <h2 className="text-2xl font-bold mb-4">Xác Nhận Thanh Toán</h2>
                        <div className="space-y-4">
                            <div>
                                <p className="text-gray-400">Gói đã chọn:</p>
                                <p className="text-xl font-semibold">{selectedPlan.name}</p>
                            </div>
                            <div>
                                <p className="text-gray-400">Giá:</p>
                                <p className="text-xl font-semibold">
                                    {selectedPlan.price == 0 ? 'Miễn Phí' : `${selectedPlan.price.toLocaleString('vi-VN')}đ`}
                                </p>
                            </div>
                            <div>
                                <p className="text-gray-400">Thời hạn:</p>
                                <p className="text-xl font-semibold">{selectedPlan.duration_days} ngày</p>
                            </div>
                            <div>
                                <p className="text-gray-400">Thông tin người dùng:</p>
                                <p className="text-xl font-semibold">{user.name} ({user.email})</p>
                            </div>
                            <div>
                                <p className="text-gray-400">Phương thức thanh toán:</p>
                                <select
                                    value={paymentMethod}
                                    onChange={(e) => setPaymentMethod(e.target.value)}
                                    className="w-full p-2 bg-gray-700 text-white rounded"
                                >
                                    <option value="vnpay">VNPay</option>
                                    {/* Thêm các phương thức khác nếu cần */}
                                    <option value="momo" disabled>MoMo (Đang phát triển)</option>
                                </select>
                            </div>
                        </div>
                        <div className="mt-6 flex justify-end space-x-4">
                            <button
                                onClick={() => setSelectedPlan(null)}
                                className="bg-gray-600 text-white py-2 px-4 rounded hover:bg-gray-700"
                            >
                                Hủy
                            </button>
                            <button
                                onClick={handleConfirmPayment}
                                className="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600"
                            >
                                Thanh Toán
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}

export default SubscriptionPlans;