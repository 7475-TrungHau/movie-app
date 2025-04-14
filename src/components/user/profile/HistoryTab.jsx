import React, { useState, useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faTrash, faSpinner } from '@fortawesome/free-solid-svg-icons';
import axios from 'axios';
import { useToast } from '@context/ToastContext';

function HistoryTab() {
    const [viewingHistory, setViewingHistory] = useState([]);
    const [loading, setLoading] = useState(true);
    const [deleting, setDeleting] = useState(null);
    const { success, error } = useToast();

    useEffect(() => {
        fetchHistory();
    }, []);

    const fetchHistory = async () => {
        try {
            setLoading(true);
            const token = localStorage.getItem('token');
            const response = await axios.get('http://localhost:8000/api/user/history', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });
            setViewingHistory(response.data.history);
            setLoading(false);
        } catch (err) {
            error('Không thể tải lịch sử xem phim');
            setLoading(false);
        }
    };

    const handleDeleteHistoryItem = async (historyId) => {
        try {
            setDeleting(historyId);
            const token = localStorage.getItem('token');
            await axios.delete(`http://localhost:8000/api/user/history/${historyId}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            // Update local state after successful deletion
            setViewingHistory(viewingHistory.filter(item => item.id !== historyId));
            success('Đã xóa khỏi lịch sử xem');
            setDeleting(null);
        } catch (err) {
            error('Không thể xóa lịch sử xem');
            setDeleting(null);
        }
    };

    const handleDeleteAllHistory = async () => {
        if (!window.confirm('Bạn có chắc chắn muốn xóa tất cả lịch sử xem?')) return;

        try {
            setLoading(true);
            const token = localStorage.getItem('token');
            await axios.delete('http://localhost:8000/api/user/history', {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            setViewingHistory([]);
            success('Đã xóa tất cả lịch sử xem');
            setLoading(false);
        } catch (err) {
            error('Không thể xóa lịch sử xem');
            setLoading(false);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center items-center h-64">
                <FontAwesomeIcon icon={faSpinner} spin className="text-blue-500 text-2xl" />
            </div>
        );
    }

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h2 className="text-xl font-semibold text-gray-800">Lịch sử xem</h2>
                {viewingHistory.length > 0 && (
                    <button
                        onClick={handleDeleteAllHistory}
                        className="px-3 py-1 bg-red-100 text-red-700 rounded-md text-sm hover:bg-red-200 transition-colors"
                    >
                        Xóa tất cả
                    </button>
                )}
            </div>

            {viewingHistory.length > 0 ? (
                <div className="space-y-4">
                    {viewingHistory.map(item => (
                        <div key={item.id} className="flex items-center bg-white p-4 rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <img
                                src={item.thumbnail || 'https://via.placeholder.com/80?text=No+Image'}
                                alt={item.title}
                                className="w-20 h-20 object-cover rounded-md mr-4"
                            />
                            <div className="flex-1">
                                <h3 className="font-medium text-gray-900">{item.title}</h3>
                                <p className="text-sm text-gray-500">
                                    Xem ngày: {new Date(item.last_watched_at).toLocaleDateString('vi-VN')}
                                </p>
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
                            <div className="flex items-center gap-2">
                                <a
                                    href={`/xem-phim/${item.movie_slug}${item.episode_slug ? '/' + item.episode_slug : ''}`}
                                    className="px-3 py-1 bg-blue-100 text-blue-700 rounded-md text-sm hover:bg-blue-200 transition-colors"
                                >
                                    Tiếp tục
                                </a>
                                <button
                                    onClick={() => handleDeleteHistoryItem(item.id)}
                                    disabled={deleting === item.id}
                                    className="p-2 text-red-500 hover:bg-red-50 rounded-full transition-colors"
                                >
                                    {deleting === item.id ? (
                                        <FontAwesomeIcon icon={faSpinner} spin />
                                    ) : (
                                        <FontAwesomeIcon icon={faTrash} />
                                    )}
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="bg-white rounded-lg border border-gray-200 p-6 text-center">
                    <p className="text-gray-500">Bạn chưa xem phim nào gần đây</p>
                </div>
            )}
        </div>
    );
}

export default HistoryTab;