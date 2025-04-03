import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { loginUser } from "@services/authService";
import { useToast } from "@context/ToastContext"; // Import useToast

const Login = () => {
    const [formData, setFormData] = useState({ email: "", password: "" });
    const [error, setError] = useState("");
    const navigate = useNavigate();
    const { success, error: errorToast } = useToast(); // Lấy các hàm từ useToast

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        console.log("formData: ", formData);

        try {
            const response = await loginUser(formData);
            if (response.data.error) {
                setError(response.data.error);
                errorToast(response.data.error);
                return;
            }
            localStorage.setItem("token", response.data.token);

            success("Đăng nhập thành công! <br/> Bạn sẽ được chuyển hướng đến trang chủ", 4000);
            setTimeout(() => {
                navigate("/");
            }, 3000);
        } catch (err) {
            const errorMessage = err.response?.data?.message || "Đăng nhập thất bại!";
            setError(errorMessage);
            errorToast(errorMessage, 3000);
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
                        Đăng nhập tài khoản
                    </h1>
                </div>

                {/* Hiển thị lỗi nếu có */}
                {error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span className="block sm:inline">{error}</span>
                    </div>
                )}

                {/* Form đăng nhập */}
                <form onSubmit={handleSubmit} className="space-y-6">
                    {/* Trường Email */}
                    <div>
                        <label
                            htmlFor="email"
                            className="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Địa chỉ Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autoComplete="email"
                            required
                            value={formData.email}
                            onChange={handleChange}
                            placeholder="you@example.com"
                            className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>

                    {/* Trường Mật khẩu */}
                    <div>
                        <label
                            htmlFor="password"
                            className="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Mật khẩu
                        </label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autoComplete="current-password"
                            required
                            value={formData.password}
                            onChange={handleChange}
                            placeholder="••••••••"
                            className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>

                    {/* Tùy chọn "Quên mật khẩu?" */}
                    <div className="text-sm text-right">
                        <a href="/forgot-password" className="font-medium text-indigo-600 hover:text-indigo-500">
                            Quên mật khẩu?
                        </a>
                    </div>

                    {/* Nút Đăng nhập */}
                    <div>
                        <button
                            type="submit"
                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                        >
                            Đăng nhập
                        </button>
                    </div>
                </form>

                {/* Liên kết Đăng ký */}
                <p className="mt-8 text-center text-sm text-gray-600">
                    Chưa có tài khoản?{' '}
                    <a href="/register" className="font-medium text-indigo-600 hover:text-indigo-500">
                        Đăng ký ngay
                    </a>
                </p>
            </div>
        </div>
    );
};

export default Login;