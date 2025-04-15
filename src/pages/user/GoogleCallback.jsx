import React, { useEffect, useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import axios from 'axios';
import { useToast } from '@context/ToastContext';

const GoogleCallback = () => {
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const navigate = useNavigate();
    const location = useLocation();
    const { success, error: errorToast } = useToast();

    useEffect(() => {
        const handleCallback = async () => {
            try {
                // The current URL contains the authorization code from Google
                // We need to send this to our backend
                const response = await axios.get(
                    `http://localhost:8000/api/auth/google/callback${location.search}`
                );

                if (response.data.token) {
                    // Save the token
                    localStorage.setItem('token', response.data.token);

                    // Show success message
                    success('Đăng nhập Google thành công!');

                    // Redirect to home page
                    setTimeout(() => {
                        navigate('/');
                    }, 2000);
                } else {
                    throw new Error('Không nhận được token từ server');
                }
            } catch (err) {
                console.error('Google login error:', err);
                const errorMessage = err.response?.data?.message || 'Đăng nhập Google thất bại';
                setError(errorMessage);
                errorToast(errorMessage);

                // Redirect to login page after error
                setTimeout(() => {
                    navigate('/login');
                }, 3000);
            } finally {
                setLoading(false);
            }
        };

        handleCallback();
    }, [navigate, location, success, errorToast]);

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-100">
                <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
                    <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto mb-4"></div>
                    <h2 className="text-xl font-semibold mb-2">Đang xử lý đăng nhập Google...</h2>
                    <p className="text-gray-600">Vui lòng đợi trong giây lát.</p>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-gray-100">
                <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
                    <div className="text-red-500 text-5xl mb-4">⚠️</div>
                    <h2 className="text-xl font-semibold mb-2">Đăng nhập thất bại</h2>
                    <p className="text-gray-600 mb-4">{error}</p>
                    <p className="text-gray-600">Bạn sẽ được chuyển hướng đến trang đăng nhập...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
                <div className="text-green-500 text-5xl mb-4">✓</div>
                <h2 className="text-xl font-semibold mb-2">Đăng nhập thành công!</h2>
                <p className="text-gray-600">Bạn sẽ được chuyển hướng đến trang chủ...</p>
            </div>
        </div>
    );
};

export default GoogleCallback;