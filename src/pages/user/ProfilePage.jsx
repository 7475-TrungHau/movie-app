import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { getUserInfo, updateProfile, changePassword } from '@services/authService';
import { useToast } from '@context/ToastContext';

function ProfilePage() {
    const [user, setUser] = useState(null);
    const [editMode, setEditMode] = useState(false);
    const [formData, setFormData] = useState({});
    const [passwordData, setPasswordData] = useState({
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
    });
    const { success, error } = useToast();
    const navigate = useNavigate();

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const res = await getUserInfo();
                setUser(res.data.user);
                setFormData({
                    full_name: res.data.user.full_name,
                    username: res.data.user.username,
                    avatar: res.data.user.avatar,
                    email: res.data.user.email
                });


            } catch (err) {
                error('Lỗi tải thông tin người dùng');
            }
        };
        fetchUser();
    }, []);

    const handleUpdateProfile = async (e) => {
        e.preventDefault();
        const formDataToSend = new FormData();

        // Thêm các trường thông tin cơ bản
        formDataToSend.append('full_name', formData.full_name);
        formDataToSend.append('username', formData.username);
        formDataToSend.append('email', formData.email);

        // Thêm file avatar nếu có
        if (formData.avatar && formData.avatar instanceof File) {
            formDataToSend.append('avatar', formData.avatar);
        }

        try {
            const res = await updateProfile(formDataToSend);
            console.log("log res: ", res);

            // Cập nhật lại thông tin user sau khi update thành công
            if (res.data && res.data.user) {
                setUser(res.data.user);
            } else {
                // Tải lại thông tin người dùng nếu response không có dữ liệu user
                const userInfo = await getUserInfo();
                setUser(userInfo.data.user);
            }

            success('Cập nhật thông tin thành công!');
            setEditMode(false);
        } catch (err) {
            error(err.response?.data?.message || 'Cập nhật thất bại');
        }
    };

    const handlePasswordChange = async (e) => {
        e.preventDefault();
        try {
            await changePassword(passwordData);
            success('Đổi mật khẩu thành công!');
            setPasswordData({
                current_password: '',
                new_password: '',
                new_password_confirmation: ''
            });
        } catch (err) {
            error(err.response?.data?.message || 'Đổi mật khẩu thất bại');
        }
    };

    if (!user) return <div>Loading...</div>;

    return (
        <div className="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8 w-full mt-15 mb-10">
            <div className="max-w-4xl mx-auto">
                <h1 className="text-3xl font-bold text-gray-900 mb-8">Quản lý tài khoản</h1>

                {/* Thông tin cá nhân */}
                <div className="bg-white rounded-lg shadow p-6 mb-8">
                    <div className="flex justify-between items-center mb-4">
                        <h2 className="text-xl font-semibold">Thông tin cá nhân</h2>
                        <button
                            onClick={() => setEditMode(!editMode)}
                            className="text-blue-600 hover:text-blue-800"
                        >
                            {editMode ? 'Hủy' : 'Chỉnh sửa'}
                        </button>
                    </div>

                    {editMode ? (
                        <form onSubmit={handleUpdateProfile} >
                            <div className="space-y-4">
                                <div className="flex items-center space-x-4">
                                    <img
                                        src={user.avatar || 'https://via.placeholder.com/150'} // Placeholder if no avatar
                                        alt="Avatar"
                                        className="w-16 h-16 rounded-full object-cover"
                                    />
                                    <div>
                                        <label htmlFor="avatar-upload" className="cursor-pointer text-sm font-medium text-blue-600 hover:text-blue-800">
                                            Thay đổi ảnh đại diện
                                        </label>
                                        <input
                                            id="avatar-upload"
                                            type="file"
                                            accept="image/*"
                                            onChange={(e) => setFormData({ ...formData, avatar: e.target.files[0] })}
                                            className="hidden"
                                        />
                                        {/* Optional: Add preview for selected image */}
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">Họ tên</label>
                                    <input
                                        type="text"
                                        value={formData.full_name}
                                        onChange={(e) => setFormData({ ...formData, full_name: e.target.value })}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700">UserName</label>
                                    <input
                                        type="text"
                                        value={formData.username}
                                        onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    />
                                </div>
                                <button
                                    type="submit"
                                    className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    ) : (
                        <div className="flex items-center space-x-5">
                            <img
                                src={user.avatar || 'https://via.placeholder.com/150'} // Placeholder if no avatar
                                alt="Avatar"
                                className="w-24 h-24 rounded-full object-cover"
                            />
                            <div className="space-y-1">
                                <p className='font-bold '><span className="font-medium  px-2">Tên: </span> {user.full_name}</p>
                                <p className='font-bold'><span className="font-medium px-2">Email: </span> {user.email}</p>
                                <p className='font-bold'><span className="font-medium px-2">Tài khoản: </span> {user.username}</p>
                                <p className='font-bold'><span className='font-medium px-2 pr-2'>Role: </span>{user.role === "admin" ? "ADMIN" : "USER"}</p>
                            </div>
                        </div>
                    )}
                </div>

                {/* Đổi mật khẩu */}
                <div className="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 className="text-xl font-semibold mb-4">Đổi mật khẩu</h2>
                    <form onSubmit={handlePasswordChange} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                            <input
                                type="password"
                                value={passwordData.current_password}
                                onChange={(e) => setPasswordData({ ...passwordData, current_password: e.target.value })}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                            <input
                                type="password"
                                value={passwordData.new_password}
                                onChange={(e) => setPasswordData({ ...passwordData, new_password: e.target.value })}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
                            <input
                                type="password"
                                value={passwordData.new_password_confirmation}
                                onChange={(e) => setPasswordData({ ...passwordData, new_password_confirmation: e.target.value })}
                                className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            />
                        </div>
                        <button
                            type="submit"
                            className="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            Đổi mật khẩu
                        </button>
                    </form>
                </div>

                {/* Thông tin gói dịch vụ */}
                <div className="bg-white rounded-lg shadow p-6">

                    <h2 className="text-xl font-semibold mb-4">Gói dịch vụ 123</h2>
                    {user && user.packages?.length > 0 ?
                        user.packages.map((pkg, index) => (
                            <div key={index} className="">
                                <p><span className="font-medium">Gói hiện tại:</span> {pkg.name}</p>
                                <p className="text-sm text-gray-600">
                                    <span className="font-medium">Ngày hết hạn:</span> {new Date(pkg.end_date).toLocaleDateString('vi-VN', { year: 'numeric', month: 'long', day: 'numeric' })}
                                </p>
                                <a
                                    href="/subscription"
                                    className="text-blue-600 hover:text-blue-800 inline-block mt-2"
                                >
                                    Nâng cấp gói →
                                </a>
                            </div>
                        ))
                        : (
                            <div className="text-gray-500">
                                <p>Bạn chưa đăng ký gói dịch vụ nào</p>
                                <a
                                    href="/subscription"
                                    className="text-blue-600 hover:text-blue-800 inline-block mt-2"
                                >
                                    Đăng ký ngay →
                                </a>
                            </div>
                        )}
                </div>
            </div>
        </div>
    );
}

export default ProfilePage;