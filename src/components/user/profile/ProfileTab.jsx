import React from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEdit, faCheck } from '@fortawesome/free-solid-svg-icons';

function ProfileTab({ user, editMode, setEditMode, formData, setFormData, handleUpdateProfile }) {
    return (
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
    );
}

export default ProfileTab;