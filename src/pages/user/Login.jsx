import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { loginUser, getGoogleAuthUrl } from "@services/authService";
import { useToast } from "@context/ToastContext"; // Import useToast

const Login = () => {
    const [formData, setFormData] = useState({ email: "", password: "" });
    const [error, setError] = useState("");
    const [isLoading, setIsLoading] = useState(false);
    const navigate = useNavigate();
    const { success, error: errorToast } = useToast(); // Lấy các hàm từ useToast

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsLoading(true);
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
        } finally {
            setIsLoading(false);
        }
    };

    const handleGoogleLogin = async () => {
        setIsLoading(true);
        try {
            const googleAuthUrl = await getGoogleAuthUrl();
            window.location.href = googleAuthUrl;
        } catch (err) {
            const errorMessage = err.response?.data?.message || "Không thể kết nối với Google: ";
            console.log("error: ", err);

            setError(errorMessage);
            errorToast(errorMessage);
            setIsLoading(false);
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
                            disabled={isLoading}
                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                        >
                            {isLoading ? "Đang xử lý..." : "Đăng nhập"}
                        </button>
                    </div>
                </form>

                {/* Divider */}
                <div className="relative my-4">
                    <div className="absolute inset-0 flex items-center">
                        <div className="w-full border-t border-gray-300"></div>
                    </div>
                    <div className="relative flex justify-center text-sm">
                        <span className="px-2 bg-white bg-opacity-90 text-gray-500">Hoặc đăng nhập với</span>
                    </div>
                </div>

                {/* Google Login Button */}
                <div>
                    <button
                        type="button"
                        onClick={handleGoogleLogin}
                        disabled={isLoading}
                        className="w-full flex justify-center items-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                    >
                        <svg className="h-5 w-5 mr-2" viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                            <g transform="matrix(1, 0, 0, 1, 27.009001, -39.238998)">
                                <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.574 55.229 -9.424 56.479 -10.684 57.329 L -10.684 60.329 L -6.824 60.329 C -4.564 58.239 -3.264 55.159 -3.264 51.509 Z" />
                                <path fill="#34A853" d="M -14.754 63.239 C -11.514 63.239 -8.804 62.159 -6.824 60.329 L -10.684 57.329 C -11.764 58.049 -13.134 58.489 -14.754 58.489 C -17.884 58.489 -20.534 56.379 -21.484 53.529 L -25.464 53.529 L -25.464 56.619 C -23.494 60.539 -19.444 63.239 -14.754 63.239 Z" />
                                <path fill="#FBBC05" d="M -21.484 53.529 C -21.734 52.809 -21.864 52.039 -21.864 51.239 C -21.864 50.439 -21.724 49.669 -21.484 48.949 L -21.484 45.859 L -25.464 45.859 C -26.284 47.479 -26.754 49.299 -26.754 51.239 C -26.754 53.179 -26.284 54.999 -25.464 56.619 L -21.484 53.529 Z" />
                                <path fill="#EA4335" d="M -14.754 43.989 C -12.984 43.989 -11.404 44.599 -10.154 45.789 L -6.734 42.369 C -8.804 40.429 -11.514 39.239 -14.754 39.239 C -19.444 39.239 -23.494 41.939 -25.464 45.859 L -21.484 48.949 C -20.534 46.099 -17.884 43.989 -14.754 43.989 Z" />
                            </g>
                        </svg>
                        Đăng nhập với Google
                    </button>
                </div>

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