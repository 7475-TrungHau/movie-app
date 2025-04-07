import React, { useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import axios from 'axios';

function PaymentResult() {
    const location = useLocation();
    const navigate = useNavigate();
    const query = new URLSearchParams(location.search);
    const subscriptionId = query.get('subscription_id');
    const error = query.get('error');

    const [payment, setPayment] = useState(null);
    const [loading, setLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState(null);

    const token = localStorage.getItem('token');

    useEffect(() => {
        document.title = "Kết Quả Thanh Toán - PlayFilm";

        if (subscriptionId) {
            const fetchPayment = async () => {
                setLoading(true);
                setErrorMessage(null);
                try {
                    const response = await axios.get(`http://localhost:8000/api/payment/getPayment/${subscriptionId}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    });
                    setPayment(response.data);
                } catch (err) {
                    console.error("Error fetching payment details:", err);
                    setErrorMessage('Không thể tải thông tin chi tiết thanh toán. Vui lòng kiểm tra lịch sử giao dịch của bạn.');
                } finally {
                    setLoading(false);
                }
            };

            fetchPayment();
        } else if (!error) {
            setLoading(false);
        } else {
            setLoading(false);
        }
    }, [subscriptionId, token, error]);

    if (loading) {
        return (
            <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
                <div className="text-center">
                    <svg className="animate-spin h-10 w-10 text-red-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <h2 className="text-2xl font-semibold mb-2">Đang xử lý thanh toán...</h2>
                    <p className="text-gray-400">Vui lòng chờ trong giây lát.</p>
                </div>
            </div>
        );
    }

    if (subscriptionId && payment) {
        return (
            <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
                <div className="bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full text-center border border-gray-700">
                    <svg className="h-16 w-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h2 className="text-3xl font-bold mb-3 text-green-400">Thanh Toán Thành Công!</h2>
                    <p className="text-gray-300 mb-6">Cảm ơn bạn đã đăng ký gói dịch vụ của PlayFilm.</p>

                    <div className="text-left bg-gray-700 p-4 rounded-md space-y-3 mb-8">
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Mã đăng ký:</span>
                            <span>{payment.subscription_id}</span>
                        </p>
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Tên gói:</span>
                            <span>{payment.subscription?.package?.name ?? 'N/A'}</span>
                        </p>
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Giá:</span>
                            <span>{payment.amount?.toLocaleString('vi-VN') ?? 'N/A'}đ</span>
                        </p>
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Ngày thanh toán:</span>
                            <span>{payment.payment_date ? new Date(payment.payment_date).toLocaleDateString('vi-VN') : 'N/A'}</span>
                        </p>
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Phương thức:</span>
                            <span>{payment.payment_method ?? 'N/A'}</span>
                        </p>
                        <p className="text-gray-300 flex justify-between">
                            <span className="font-semibold text-gray-100">Thời hạn gói:</span>
                            <span>
                                {payment.subscription?.start_date ? new Date(payment.subscription.start_date).toLocaleDateString('vi-VN') : 'N/A'} -{' '}
                                {payment.subscription?.end_date ? new Date(payment.subscription.end_date).toLocaleDateString('vi-VN') : 'N/A'}
                            </span>
                        </p>
                    </div>

                    <button
                        onClick={() => navigate('/')}
                        className="w-full bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                    >
                        Xem Phim Ngay
                    </button>
                </div>
            </div>
        );
    }

    if (subscriptionId && errorMessage) {
        return (
            <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
                <div className="bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full text-center border border-gray-700">
                    <svg className="h-16 w-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h2 className="text-3xl font-bold mb-3 text-yellow-400">Thanh Toán Thành Công</h2>
                    <p className="text-gray-300 mb-4">Giao dịch của bạn đã được xử lý thành công.</p>
                    <p className="text-yellow-300 bg-yellow-900 bg-opacity-30 px-4 py-2 rounded-md mb-6">{errorMessage}</p>
                    <button
                        onClick={() => navigate('/')}
                        className="w-full bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                    >
                        Về Trang Chính
                    </button>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
                <div className="bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full text-center border border-gray-700">
                    <svg className="h-16 w-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h2 className="text-3xl font-bold mb-3 text-red-500">Thanh Toán Thất Bại</h2>
                    <p className="text-gray-300 mb-6">Rất tiếc, đã có lỗi xảy ra trong quá trình thanh toán.</p>
                    <p className="text-red-300 bg-red-900 bg-opacity-30 px-4 py-2 rounded-md mb-8">Lỗi: {decodeURIComponent(error)}</p>
                    <button
                        onClick={() => navigate('/subscription-plans')}
                        className="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-blue-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
                    >
                        Chọn Lại Gói Đăng Ký
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
            <div className="bg-gray-800 p-8 rounded-lg shadow-xl max-w-md w-full text-center border border-gray-700">
                <svg className="h-16 w-16 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2 className="text-2xl font-semibold mb-2">Không Tìm Thấy Thông Tin Giao Dịch</h2>
                <p className="text-gray-400 mb-6">Không thể xác định trạng thái thanh toán. Vui lòng kiểm tra lại hoặc liên hệ hỗ trợ.</p>
                <button
                    onClick={() => navigate('/dashboard')}
                    className="w-full bg-red-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                >
                    Về Trang Chính
                </button>
            </div>
        </div>
    );
}

export default PaymentResult;