import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import { useToast } from "@context/ToastContext";

const ForgotPassword = () => {
    const [email, setEmail] = useState("");
    const [loading, setLoading] = useState(false);
    const [step, setStep] = useState(1); // 1: Email form, 2: OTP verification, 3: New password
    const [otp, setOtp] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();
    const { success, error: errorToast } = useToast();

    const handleEmailSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const response = await axios.post("http://localhost:8000/api/forgot-password", { email });
            success("Mã xác nhận đã được gửi đến email của bạn");
            setStep(2); // Move to OTP verification step
        } catch (err) {
            const errorMessage = err.response?.data?.message || "Không thể gửi yêu cầu đặt lại mật khẩu " + err;
            setError(errorMessage);
            errorToast(errorMessage);
        } finally {
            setLoading(false);
        }
    };

    const handleOtpSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError("");

        try {
            const response = await axios.post("http://localhost:8000/api/verify-code", {
                email,
                token: otp
            });
            success("Mã xác nhận hợp lệ");
            setStep(3); // Move to new password step
        } catch (err) {
            const errorMessage = err.response?.data?.message || "Mã xác nhận không hợp lệ";
            setError(errorMessage);
            errorToast(errorMessage);
        } finally {
            setLoading(false);
        }
    };

    const handlePasswordSubmit = async (e) => {
        e.preventDefault();

        if (newPassword !== confirmPassword) {
            setError("Mật khẩu xác nhận không khớp");
            errorToast("Mật khẩu xác nhận không khớp");
            return;
        }

        setLoading(true);
        setError("");
        console.log("email: ", email);
        console.log("otp: ", otp);
        console.log("newPassword: ", newPassword);
        try {


            const response = await axios.post("http://localhost:8000/api/reset-password", {
                email: email,
                token: otp,
                newPassword: newPassword
            });
            success("Đặt lại mật khẩu thành công! Bạn có thể đăng nhập với mật khẩu mới.");
            setTimeout(() => {
                navigate("/login");
            }, 2000);
        } catch (err) {
            const errorMessage = err.response?.data?.message || "Không thể đặt lại mật khẩu";
            setError(errorMessage);
            errorToast(errorMessage);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div
            className="flex items-center justify-center w-full min-h-screen bg-gray-100 px-4 sm:px-6 lg:px-8"
            style={{
                backgroundImage: "url('https://cdn.pixabay.com/video/2016/11/04/6266-190550868_tiny.jpg')",
                backgroundSize: "cover",
                backgroundPosition: "center",
            }}
        >
            <div className="max-w-md w-full bg-white bg-opacity-90 rounded-xl shadow-lg p-8 space-y-8">
                <div>
                    <img className="mx-auto h-12 w-auto" src="https://img.freepik.com/premium-vector/anime-mascot-logo-design-modern-style_404419-56.jpg" alt="Logo" />
                    <h1 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        {step === 1 && "Quên mật khẩu"}
                        {step === 2 && "Xác nhận mã OTP"}
                        {step === 3 && "Đặt lại mật khẩu"}
                    </h1>
                    <p className="mt-2 text-center text-sm text-gray-600">
                        {step === 1 && "Nhập email của bạn để nhận mã xác nhận"}
                        {step === 2 && "Nhập mã xác nhận đã được gửi đến email của bạn"}
                        {step === 3 && "Tạo mật khẩu mới cho tài khoản của bạn"}
                    </p>
                </div>

                {error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span className="block sm:inline">{error}</span>
                    </div>
                )}

                {step === 1 && (
                    <form onSubmit={handleEmailSubmit} className="space-y-6">
                        <div>
                            <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-1">
                                Địa chỉ Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autoComplete="email"
                                required
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                placeholder="you@example.com"
                                className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>

                        <div>
                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                            >
                                {loading ? "Đang xử lý..." : "Gửi mã xác nhận"}
                            </button>
                        </div>
                    </form>
                )}

                {step === 2 && (
                    <form onSubmit={handleOtpSubmit} className="space-y-6">
                        <div>
                            <label htmlFor="otp" className="block text-sm font-medium text-gray-700 mb-1">
                                Mã xác nhận
                            </label>
                            <input
                                id="otp"
                                name="otp"
                                type="text"
                                required
                                value={otp}
                                onChange={(e) => setOtp(e.target.value)}
                                placeholder="Nhập mã OTP"
                                className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>

                        <div>
                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                            >
                                {loading ? "Đang xử lý..." : "Xác nhận"}
                            </button>
                        </div>
                    </form>
                )}

                {step === 3 && (
                    <form onSubmit={handlePasswordSubmit} className="space-y-6">
                        <div>
                            <label htmlFor="newPassword" className="block text-sm font-medium text-gray-700 mb-1">
                                Mật khẩu mới
                            </label>
                            <input
                                id="newPassword"
                                name="newPassword"
                                type="password"
                                required
                                value={newPassword}
                                onChange={(e) => setNewPassword(e.target.value)}
                                placeholder="••••••••"
                                className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                            <p className="mt-1 text-xs text-gray-500">
                                Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt
                            </p>
                        </div>

                        <div>
                            <label htmlFor="confirmPassword" className="block text-sm font-medium text-gray-700 mb-1">
                                Xác nhận mật khẩu
                            </label>
                            <input
                                id="confirmPassword"
                                name="confirmPassword"
                                type="password"
                                required
                                value={confirmPassword}
                                onChange={(e) => setConfirmPassword(e.target.value)}
                                placeholder="••••••••"
                                className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            />
                        </div>

                        <div>
                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                            >
                                {loading ? "Đang xử lý..." : "Đặt lại mật khẩu"}
                            </button>
                        </div>
                    </form>
                )}

                <div className="text-center">
                    <a href="/login" className="font-medium text-indigo-600 hover:text-indigo-500">
                        Quay lại đăng nhập
                    </a>
                </div>
            </div>
        </div>
    );
};

export default ForgotPassword;