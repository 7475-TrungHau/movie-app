import React from 'react';

function SubscriptionTab({ user }) {
    return (
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
    );
}

export default SubscriptionTab;