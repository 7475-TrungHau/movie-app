import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faHeart, faSpinner } from '@fortawesome/free-solid-svg-icons';
import { Link } from 'react-router-dom';

function FavoritesTab({ favorites, loading, currentPage, totalPages, onPageChange, onRemoveFavorite }) {

    useEffect(() => {
        // Reload component when favorites change
        return () => {
            // Cleanup if needed
        };
    }, [favorites])
    return (
        <div>
            <h2 className="text-xl font-semibold text-gray-800 mb-6">Phim yêu thích</h2>

            {loading ? (
                <div className="flex justify-center items-center py-12">
                    <FontAwesomeIcon icon={faSpinner} spin className="text-blue-600 text-2xl" />
                </div>
            ) : favorites && favorites.data && favorites.data.length > 0 ? (
                <>
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        {favorites.data.map(item => (
                            <div key={item.id} className="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                                <img
                                    src={item.movie.thumbnail_url || 'https://via.placeholder.com/150'}
                                    alt={item.movie.name}
                                    className="w-full h-40 object-cover"
                                />
                                <div className="p-4">
                                    <h3 className="font-medium text-gray-900 mb-1">{item.movie.name}</h3>
                                    <p className="text-sm text-gray-500 mb-3">Thêm vào: {new Date(item.added_at).toLocaleDateString('vi-VN')}</p>
                                    <div className="flex justify-between">
                                        <Link
                                            to={`/xem-phim/${item.movie.slug}`}
                                            className="text-blue-600 hover:text-blue-800 text-sm font-medium"
                                        >
                                            Xem ngay
                                        </Link>
                                        <button
                                            onClick={() => onRemoveFavorite(item.movie_id)}
                                            className="text-red-600 hover:text-red-800 text-sm font-medium flex items-center"
                                        >
                                            <FontAwesomeIcon icon={faHeart} className="mr-1" />
                                            Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Pagination */}
                    {totalPages > 1 && (
                        <div className="flex justify-center mt-8">
                            <nav className="flex items-center">
                                <button
                                    onClick={() => onPageChange(currentPage - 1)}
                                    disabled={currentPage === 1}
                                    className={`px-3 py-1 rounded-md mr-2 ${currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}`}
                                >
                                    Trước
                                </button>

                                {[...Array(totalPages)].map((_, index) => (
                                    <button
                                        key={index}
                                        onClick={() => onPageChange(index + 1)}
                                        className={`px-3 py-1 rounded-md mx-1 ${currentPage === index + 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}`}
                                    >
                                        {index + 1}
                                    </button>
                                ))}

                                <button
                                    onClick={() => onPageChange(currentPage + 1)}
                                    disabled={currentPage === totalPages}
                                    className={`px-3 py-1 rounded-md ml-2 ${currentPage === totalPages ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'}`}
                                >
                                    Sau
                                </button>
                            </nav>
                        </div>
                    )}
                </>
            ) : (
                <div className="bg-white rounded-lg border border-gray-200 p-6 text-center">
                    <div className="mb-4">
                        <FontAwesomeIcon icon={faHeart} className="text-gray-300 text-5xl" />
                    </div>
                    <p className="text-gray-500">Bạn chưa thêm phim yêu thích nào</p>
                    <Link
                        to="/"
                        className="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Khám phá phim ngay
                    </Link>
                </div>
            )}
        </div>
    );
}

export default FavoritesTab;