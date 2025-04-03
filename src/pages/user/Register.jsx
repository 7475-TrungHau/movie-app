import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { registerUser } from "@services/authService";
import { useToast } from "@context/ToastContext";

const Register = () => {
    const [formData, setFormData] = useState({ username: "", email: "", password: "", password_confirmation: "", full_name: "" });
    const [error, setError] = useState("");
    const [autoLogin, setAutoLogin] = useState(false);
    const navigate = useNavigate();
    const { toast, success, error: errorToast } = useToast();

    const handleChange = (e) => {
        setFormData({ ...formData, [e.target.name]: e.target.value });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (formData.password !== formData.password_confirmation) {
            setError("Mật khẩu không khớp!");
            errorToast("Mật khẩu không khớp!");
            return;
        }
        setFormData({ ...formData, full_name: formData.username });

        try {
            const res = await registerUser(formData);
            success("Đăng ký thành công!");

            if (autoLogin && res.data.token) {
                localStorage.setItem("token", res.data.token);
                navigate('/');
            } else {
                navigate("/login");
            }
        } catch (err) {
            const errorMsg = err.response?.data?.message || "Đăng ký thất bại!";
            setError(errorMsg);
            errorToast(errorMsg);
        }
    };

    return (
        <div className="flex w-full items-center justify-center min-h-screen bg-gray-100 px-4 sm:px-6 lg:px-8"
            style={{
                backgroundImage: "url('https://phongvu.vn/cong-nghe/wp-content/uploads/2020/11/119234579_169457918065236_755533166268046471_n-1.jpg')",
                backgroundSize: "cover",
                backgroundPosition: "center",
            }}
        >

            <div className="max-w-md w-full bg-white rounded-xl shadow-lg p-8 space-y-8">
                <div>
                    <h1 className="mt-6 text-center text-3xl font-extrabold text-gray-900">
                        Tạo tài khoản mới
                    </h1>
                </div>

                {error && (
                    <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center" role="alert">
                        <span className="block sm:inline">{error}</span>
                    </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label
                            htmlFor="username"
                            className="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Tên đăng nhập
                        </label>
                        <input
                            id="username"
                            name="username"
                            type="text"
                            required
                            value={formData.username}
                            onChange={handleChange}
                            placeholder="tendangnhap"
                            className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>

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
                            autoComplete="new-password"
                            required
                            value={formData.password}
                            onChange={handleChange}
                            placeholder="••••••••"
                            className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>

                    <div>
                        <label
                            htmlFor="password_confirmation"
                            className="block text-sm font-medium text-gray-700 mb-1"
                        >
                            Xác nhận mật khẩu
                        </label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autoComplete="new-password"
                            required
                            value={formData.password_confirmation}
                            onChange={handleChange}
                            placeholder="••••••••"
                            className="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        />
                    </div>



                    <div className="flex items-center">
                        <input
                            id="autoLogin"
                            name="autoLogin"
                            type="checkbox"
                            checked={autoLogin}
                            onChange={(e) => setAutoLogin(e.target.checked)}
                            className="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                        <label htmlFor="autoLogin" className="ml-2 block text-sm text-gray-900">
                            Tự động đăng nhập sau khi đăng ký
                        </label>
                    </div>

                    <div>
                        <button
                            type="submit"
                            className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out"
                        >
                            Đăng ký
                        </button>
                    </div>
                </form>

                <p className="mt-8 text-center text-sm text-gray-600">
                    Bạn đã có tài khoản?{' '}
                    <a href="/login" className="font-medium text-indigo-600 hover:text-indigo-500">
                        Đăng nhập ngay
                    </a>
                    {/* <Link to="/login" className="font-medium text-indigo-600 hover:text-indigo-500">
                 Đăng nhập ngay
            </Link> */}
                </p>
            </div>
        </div>
    );
};

export default Register;