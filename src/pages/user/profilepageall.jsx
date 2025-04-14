import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import {
    faUser,
    faTicketAlt,
    faHistory,
    faHeart,
    faCog
} from '@fortawesome/free-solid-svg-icons';
import { getUserInfo, updateProfile, changePassword } from '@services/authService';
import { useToast } from '@context/ToastContext';

// Import tab components
import ProfileTab from '@components/user/profile/ProfileTab';
import SubscriptionTab from '@components/user/profile/SubscriptionTab';
import HistoryTab from '@components/user/profile/HistoryTab';
import FavoritesTab from '@components/user/profile/FavoritesTab';
import SettingsTab from '@components/user/profile/SettingsTab';

// Import mock data (replace with API calls later)
import { viewingHistory, favorites } from '@components/user/profile/mockData';

function ProfilePage() {
    const [user, setUser] = useState(null);
    const [editMode, setEditMode] = useState(false);
    const [activeTab, setActiveTab] = useState('profile');
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
                            Yêu thích
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
                        {activeTab === 'profile' && (
                            <ProfileTab
                                user={user}
                                editMode={editMode}
                                setEditMode={setEditMode}
                                formData={formData}
                                setFormData={setFormData}
                                handleUpdateProfile={handleUpdateProfile}
                            />
                        )}
                        {activeTab === 'subscription' && (
                            <SubscriptionTab user={user} />
                        )}
                        {activeTab === 'history' && (
                            <HistoryTab viewingHistory={viewingHistory} />
                        )}
                        {activeTab === 'favorites' && (
                            <FavoritesTab favorites={favorites} />
                        )}
                        {activeTab === 'settings' && (
                            <SettingsTab
                                passwordData={passwordData}
                                setPasswordData={setPasswordData}
                                handlePasswordChange={handlePasswordChange}
                            />
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default ProfilePage;