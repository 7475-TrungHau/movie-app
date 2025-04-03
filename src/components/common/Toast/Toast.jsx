import React, { useEffect, useState } from 'react';

const Toast = ({ id, message, type, duration, onClose }) => {
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        console.log("Toast rendered:", { id, message, type });

        // Hiện toast với animation
        setTimeout(() => setIsVisible(true), 10);

        // Tự động đóng sau duration
        if (duration !== Infinity) {
            const timer = setTimeout(() => {
                setIsVisible(false);
                setTimeout(() => onClose(id), 300); // Wait for exit animation
            }, duration);
            return () => clearTimeout(timer);
        }
    }, [id, duration, onClose]);

    const getTypeStyles = () => {
        switch (type) {
            case 'success':
                return 'bg-green-100 border-green-500 text-green-700';
            case 'error':
                return 'bg-red-100 border-red-500 text-red-700';
            case 'warning':
                return 'bg-yellow-100 border-yellow-500 text-yellow-700';
            default:
                return 'bg-blue-100 border-blue-500 text-blue-700';
        }
    };

    return (
        <div
            className={`transform transition-all duration-300 ease-in-out max-w-md w-full pointer-events-auto 
                ${isVisible ? 'translate-y-0 opacity-100' : 'translate-y-2 opacity-0'}`}
        >
            <div className={`rounded-lg shadow-lg border-l-4 ${getTypeStyles()} p-4`}>
                <div className="flex items-start">
                    <div className="ml-3 flex-1">
                        <p className="text-sm font-medium">{message}</p>
                    </div>
                    <div className="ml-4 flex-shrink-0 flex">
                        <button
                            onClick={() => {
                                setIsVisible(false);
                                setTimeout(() => onClose(id), 300);
                            }}
                            className="inline-flex text-gray-400 hover:text-gray-500"
                        >
                            <span className="sr-only">Close</span>
                            <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Toast;