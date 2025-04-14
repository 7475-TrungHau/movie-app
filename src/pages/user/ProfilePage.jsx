import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { getUserInfo, updateProfile, changePassword } from '@services/authService';
import { useToast } from '@context/ToastContext';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUser, faTicketAlt, faHistory, faHeart, faCog, faEdit, faCheck } from '@fortawesome/free-solid-svg-icons';

function ProfilePage() {
    const [user, setUser] = useState(null);
    const [editMode, setEditMode] = useState(false);
    const [formData, setFormData] = useState({});
    const [passwordData, setPasswordData] = useState({
        current_password: '',
        new_password: '',
        new_password_confirmation: ''
    });
    const [activeTab, setActiveTab] = useState('profile');
    const { success, error } = useToast();
    const navigate = useNavigate();

    // Mock data for demonstration
    const [viewingHistory] = useState([
        { id: 1, title: 'Avengers: Endgame', date: '2023-06-15', thumbnail: 'https://via.placeholder.com/150', progress: 75 },
        { id: 2, title: 'Stranger Things', date: '2023-06-10', thumbnail: 'https://via.placeholder.com/150', progress: 100 },
        { id: 3, title: 'The Witcher', date: '2023-06-05', thumbnail: 'https://via.placeholder.com/150', progress: 30 },
    ]);

    const [favorites] = useState([
        { id: 1, title: 'Breaking Bad', date: '2023-05-20', thumbnail: 'https://via.placeholder.com/150' },
        { id: 2, title: 'Game of Thrones', date: '2023-05-15', thumbnail: 'https://via.placeholder.com/150' },
    ]);

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

    if (!user) return (
        <div className="min-h-screen flex items-center justify-center bg-gray-100">
            <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
        </div>
    );

    return (
        <div className="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8 w-full mt-15 mb-10">
            <div className="max-w-6xl mx-auto">
                <div className="bg-white rounded-lg shadow-lg overflow-hidden">
                    {/* User Header with Avatar */}
                    <div className="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-8">
                        <div className="flex flex-col md:flex-row items-center">
                            <div className="relative mb-4 md:mb-0 md:mr-6">
                                <img
                                    src={user.avatar || 'https://via.placeholder.com/150'}
                                    alt="Avatar"
                                    className="w-24 h-24 rounded-full object-cover border-4 border-white"
                                />
                                {user.role === "admin" && (
                                    <span className="absolute bottom-0 right-0 bg-yellow-500 text-xs text-white font-bold px-2 py-1 rounded-full">
                                        ADMIN
                                    </span>
                                )}
                            </div>
                            <div className="text-center md:text-left">
                                <h1 className="text-2xl font-bold text-white">{user.full_name}</h1>
                                <p className="text-blue-100">@{user.username}</p>
                                <p className="text-blue-100">{user.email}</p>
                            </div>
                        </div>
                    </div>

                    {/* Navigation Tabs */}
                    <div className="flex overflow-x-auto bg-gray-50 border-b border-gray-200">
                        <button
                            onClick={() => setActiveTab('profile')}
                            className={`px-4 py-3 font-medium text-sm flex items-center ${activeTab === 'profile' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'}`}
                        >
                            <FontAwesomeIcon icon={faUser} className="mr-2" />
                            Thông tin tài khoản
                        </button>
                        <button
                            onClick={() => setActiveTab('subscription')}
                            className={`px-4 py-3 font-medium text-sm flex items-center ${activeTab === 'subscription' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'}`}
                        >
                            <FontAwesomeIcon icon={faTicketAlt} className="mr-2" />
                            Gói dịch vụ
                        </button>
                        <button
                            onClick={() => setActiveTab('history')}
                            className={`px-4 py-3 font-medium text-sm flex items-center ${activeTab === 'history' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'}`}
                        >
                            <FontAwesomeIcon icon={faHistory} className="mr-2" />
                            Lịch sử xem
                        </button>
                        <button
                            onClick={() => setActiveTab('favorites')}
                            className={`px-4 py-3 font-medium text-sm flex items-center ${activeTab === 'favorites' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'}`}
                        >
                            <FontAwesomeIcon icon={faHeart} className="mr-2" />
                            Phim yêu thích
                        </button>
                        <button
                            onClick={() => setActiveTab('settings')}
                            className={`px-4 py-3 font-medium text-sm flex items-center ${activeTab === 'settings' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-gray-700'}`}
                        >
                            <FontAwesomeIcon icon={faCog} className="mr-2" />
                            Cài đặt
                        </button>
                    </div>

                    {/* Tab Content */}
                    <div className="p-6">
                        {/* Profile Tab */}
                        {activeTab === 'profile' && (
                            <div>
                                <div className="flex justify-between items-center mb-6">
                                    <h2 className="text-xl font-semibold text-gray-800">Thông tin cá nhân</h2>
                                    <button
                                        onClick={() => setEditMode(!editMode)}
                                        className="flex items-center text-blue-600 hover:text-blue-800"
                                    >
                                        <FontAwesomeIcon icon={editMode ? faCheck : faEdit} className="mr-1" />
                                        {editMode ? 'Hủy' : 'Chỉnh sửa'}
                                    </button>
                                </div>

                                {editMode ? (
                                    <form onSubmit={handleUpdateProfile} className="space-y-6">
                                        <div className="flex items-center space-x-4 mb-4">
                                            <img
                                                src={user.avatar || 'https://via.placeholder.com/150'}
                                                alt="Avatar"
                                                className="w-20 h-20 rounded-full object-cover border border-gray-200"
                                            />
                                            <div>
                                                <label htmlFor="avatar-upload" className="cursor-pointer text-sm font-medium text-blue-600 hover:text-blue-800 bg-blue-500 px-3 py-2 rounded-md">
                                                    Thay đổi ảnh đại diện
                                                </label>
                                                <input
                                                    id="avatar-upload"
                                                    type="file"
                                                    accept="image/*"
                                                    onChange={(e) => setFormData({ ...formData, avatar: e.target.files[0] })}
                                                    className="hidden"
                                                />
                                                {formData.avatar instanceof File && (
                                                    <p className="text-sm text-gray-500 mt-1">
                                                        Đã chọn: {formData.avatar.name}
                                                    </p>
                                                )}
                                            </div>
                                        </div>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1">Họ tên</label>
                                                <input
                                                    type="text"
                                                    value={formData.full_name}
                                                    onChange={(e) => setFormData({ ...formData, full_name: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1">Tên đăng nhập</label>
                                                <input
                                                    type="text"
                                                    value={formData.username}
                                                    onChange={(e) => setFormData({ ...formData, username: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input
                                                    type="email"
                                                    value={formData.email}
                                                    disabled
                                                    className="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500"
                                                />
                                                <p className="text-xs text-gray-500 mt-1">Email không thể thay đổi</p>
                                            </div>
                                        </div>
                                        <div>
                                            <button
                                                type="submit"
                                                className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                            >
                                                Lưu thay đổi
                                            </button>
                                        </div>
                                    </form>
                                ) : (
                                    <div className="bg-white rounded-lg border border-gray-200 p-6">
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div className="space-y-3">
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-500">Họ tên</h3>
                                                    <p className="text-base font-medium">{user.full_name}</p>
                                                </div>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-500">Email</h3>
                                                    <p className="text-base font-medium">{user.email}</p>
                                                </div>
                                            </div>
                                            <div className="space-y-3">
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-500">Tên đăng nhập</h3>
                                                    <p className="text-base font-medium">{user.username}</p>
                                                </div>
                                                <div>
                                                    <h3 className="text-sm font-medium text-gray-500">Vai trò</h3>
                                                    <p className="text-base font-medium">
                                                        {user.role === "admin" ? (
                                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ADMIN
                                                            </span>
                                                        ) : (
                                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                USER
                                                            </span>
                                                        )}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Subscription Tab */}
                        {activeTab === 'subscription' && (
                            <div>
                                <h2 className="text-xl font-semibold text-gray-800 mb-6">Gói dịch vụ của bạn</h2>

                                {user && user.packages?.length > 0 ? (
                                    <div className="space-y-6">
                                        {user.packages.map((pkg, index) => (
                                            <div key={index} className="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                                <div className={`px-6 py-4 ${pkg.name === 'VIP' ? 'bg-gradient-to-r from-yellow-400 to-yellow-600' : pkg.name === 'PRO' ? 'bg-gradient-to-r from-purple-500 to-purple-700' : 'bg-gradient-to-r from-blue-400 to-blue-600'}`}>
                                                    <h3 className="text-xl font-bold text-white">{pkg.name}</h3>
                                                </div>
                                                <div className="p-6">
                                                    <div className="mb-4">
                                                        <p className="text-gray-600 mb-1">Trạng thái</p>
                                                        <p className="font-medium">
                                                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                Đang hoạt động
                                                            </span>
                                                        </p>
                                                    </div>
                                                    <div className="mb-4">
                                                        <p className="text-gray-600 mb-1">Ngày bắt đầu</p>
                                                        <p className="font-medium">{new Date(pkg.start_date).toLocaleDateString('vi-VN', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                                    </div>
                                                    <div className="mb-6">
                                                        <p className="text-gray-600 mb-1">Ngày hết hạn</p>
                                                        <p className="font-medium">{new Date(pkg.end_date).toLocaleDateString('vi-VN', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                                    </div>

                                                    <div className="flex flex-wrap gap-3">
                                                        <a
                                                            href="/subscription"
                                                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                        >
                                                            Nâng cấp gói
                                                        </a>
                                                        <button
                                                            className="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                        >
                                                            Hủy tự động gia hạn
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="bg-white rounded-lg border border-gray-200 p-6 text-center">
                                        <div className="mb-4">
                                            <img
                                                src="https://via.placeholder.com/150"
                                                alt="No subscription"
                                                className="w-24 h-24 mx-auto opacity-50"
                                            />
                                        </div>
                                        <h3 className="text-lg font-medium text-gray-900 mb-2">Bạn chưa đăng ký gói dịch vụ nào</h3>
                                        <p className="text-gray-500 mb-6">Đăng ký ngay để trải nghiệm đầy đủ các tính năng cao cấp</p>
                                        <a
                                            href="/subscription"
                                            className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            Xem các gói dịch vụ
                                        </a>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* History Tab */}
                        {activeTab === 'history' && (
                            <div>
                                <h2 className="text-xl font-semibold text-gray-800 mb-6">Lịch sử xem</h2>

                                {viewingHistory.length > 0 ? (
                                    <div className="space-y-4">
                                        {viewingHistory.map(item => (
                                            <div key={item.id} className="flex items-center bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                                                <img
                                                    src={item.thumbnail}
                                                    alt={item.title}
                                                    className="w-20 h-20 object-cover rounded-md mr-4"
                                                />
                                                <div className="flex-1">
                                                    <h3 className="font-medium text-gray-900">{item.title}</h3>
                                                    <p className="text-sm text-gray-500">Xem ngày: {new Date(item.date).toLocaleDateString('vi-VN')}</p>
                                                    <div className="mt-2 w-full bg-gray-200 rounded-full h-2.5">
                                                        <div
                                                            className="bg-blue-600 h-2.5 rounded-full"
                                                            style={{ width: `${item.progress}%` }}
                                                        ></div>
                                                    </div>
                                                    <p className="text-xs text-gray-500 mt-1">
                                                        {item.progress === 100 ? 'Đã xem xong' : `Đã xem ${item.progress}%`}
                                                    </p>
                                                </div>
                                                <a
                                                    href={`/xem-phim/${item.id}`}
                                                    className="ml-4 px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm hover:bg-blue-200"
                                                >
                                                    Tiếp tục
                                                </a>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="bg-white rounded-lg border border-gray-200 p-6 text-center">
                                        <p className="text-gray-500">Bạn chưa xem phim nào gần đây</p>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Favorites Tab */}
                        {activeTab === 'favorites' && (
                            <div>
                                <h2 className="text-xl font-semibold text-gray-800 mb-6">Phim yêu thích</h2>

                                {favorites.length > 0 ? (
                                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        {favorites.map(item => (
                                            <div key={item.id} className="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                                <img
                                                    src={item.thumbnail}
                                                    alt={item.title}
                                                    className="w-full h-40 object-cover"
                                                />
                                                <div className="p-4">
                                                    <h3 className="font-medium text-gray-900 mb-1">{item.title}</h3>
                                                    <p className="text-sm text-gray-500 mb-3">Thêm vào: {new Date(item.date).toLocaleDateString('vi-VN')}</p>
                                                    <div className="flex justify-between">
                                                        <a
                                                            href={`/xem-phim/${item.id}`}
                                                            className="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                                        >
                                                            Xem ngay
                                                        </a>
                                                        <button
                                                            className="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        >
                                                            Xóa
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                ) : (
                                    <div className="bg-white rounded-lg border border-gray-200 p-6 text-center">
                                        <p className="text-gray-500">Bạn chưa thêm phim yêu thích nào</p>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Settings Tab */}
                        {activeTab === 'settings' && (
                            <div>
                                <h2 className="text-xl font-semibold text-gray-800 mb-6">Cài đặt tài khoản</h2>

                                <div className="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Đổi mật khẩu</h3>
                                    <p className="text-gray-500 mb-4">
                                        Để bảo mật tài khoản, bạn nên sử dụng mật khẩu mạnh và thay đổi định kỳ
                                    </p>
                                    <a
                                        href="/forgot-password"
                                        className="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Đổi mật khẩu
                                    </a>
                                </div>

                                <div className="bg-white rounded-lg border border-gray-200 p-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Tùy chọn thông báo</h3>
                                    <div className="space-y-4">
                                        <div className="flex items-center justify-between">
                                            <div>
                                                <p className="font-medium text-gray-700">Thông báo phim mới</p>
                                                <p className="text-sm text-gray-500">Nhận thông báo khi có phim mới được thêm vào</p>
                                            </div>
                                            <label className="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" className="sr-only peer" defaultChecked />
                                                <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>

                                        <div className="flex items-center justify-between">
                                            <div>
                                                <p className="font-medium text-gray-700">Thông báo gói dịch vụ</p>
                                                <p className="text-sm text-gray-500">Nhận thông báo về gói dịch vụ sắp hết hạn</p>
                                            </div>
                                            <label className="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" className="sr-only peer" defaultChecked />
                                                <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>

                                        <div className="flex items-center justify-between">
                                            <div>
                                                <p className="font-medium text-gray-700">Email marketing</p>
                                                <p className="text-sm text-gray-500">Nhận email về khuyến mãi và ưu đãi</p>
                                            </div>
                                            <label className="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" value="" className="sr-only peer" />
                                                <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ProfilePage;