import React from 'react';
import { Link } from 'react-router-dom'; // Nếu bạn sử dụng React Router

function Footer() {
    return (
        <footer className="bg-gray-900 text-white py-6">
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {/* Cột 1: Logo và thông tin */}
                    <div>
                        <Link to="/" className="text-2xl font-bold">
                            PlayFilm
                        </Link>
                        <p className="mt-2 text-gray-400">
                            Nền tảng xem phim trực tuyến hàng đầu.
                        </p>
                    </div>

                    {/* Cột 2: Liên kết nhanh */}
                    <div>
                        <h4 className="text-lg font-semibold mb-4">Liên kết</h4>
                        <ul className="space-y-2">
                            <li>
                                <Link to="/about" className="hover:text-gray-300">Về chúng tôi</Link>
                            </li>
                            <li>
                                <Link to="/contact" className="hover:text-gray-300">Liên hệ</Link>
                            </li>
                            <li>
                                <Link to="/terms" className="hover:text-gray-300">Điều khoản</Link>
                            </li>
                            {/* Thêm các liên kết khác */}
                        </ul>
                    </div>

                    {/* Cột 3: Mạng xã hội */}
                    <div>
                        <h4 className="text-lg font-semibold mb-4">Kết nối</h4>
                        <div className="flex space-x-4">
                            {/* Thay thế bằng icon thật */}
                            <a href="#" className="hover:text-blue-500">
                                FB
                            </a>
                            <a href="#" className="hover:text-pink-500">
                                IG
                            </a>
                            <a href="#" className="hover:text-blue-400">
                                TW
                            </a>
                        </div>
                    </div>
                </div>

                {/* Copyright */}
                <div className="mt-8 text-center text-gray-500">
                    &copy; {new Date().getFullYear()} PlayFilm. All rights reserved.
                </div>
            </div>
        </footer>
    );
}

export default Footer;