import React from 'react';
import { Link } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHome, faArrowLeft } from '@fortawesome/free-solid-svg-icons';

function Error() {
    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-900 px-4">
            <div className="max-w-md w-full text-center">
                <div className="mb-8">
                    <h1 className="text-6xl font-bold text-red-500 mb-4">404</h1>
                    <img
                        src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExNjZlNjRkNzRkNzJiNDVmZDZiMzA1YzQ1ZDJkMzA1ZDJlMDZiMDRlZiZlcD12MV9pbnRlcm5hbF9naWZzX2dpZklkJmN0PWc/14uQ3cOFteDGU/giphy.gif"
                        alt="Error animation"
                        className="mx-auto h-48 rounded-lg mb-4"
                    />
                    <h2 className="text-2xl font-semibold text-white mb-2">Oops! Không tìm thấy nội dung</h2>
                    <p className="text-gray-400 mb-8">
                        Nội dung bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.
                    </p>

                    <div className="flex flex-col sm:flex-row justify-center gap-4">
                        <Link
                            to="/"
                            className="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300"
                        >
                            <FontAwesomeIcon icon={faHome} />
                            Về trang chủ
                        </Link>
                        <button
                            onClick={() => window.history.back()}
                            className="flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg transition duration-300"
                        >
                            <FontAwesomeIcon icon={faArrowLeft} />
                            Quay lại
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Error;