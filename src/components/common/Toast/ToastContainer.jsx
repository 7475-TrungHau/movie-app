import React from 'react';
import Toast from './Toast';
import { useToast } from '@context/ToastContext';

const ToastContainer = () => {
    const { toasts, removeToast } = useToast();

    console.log("ToastContainer rendered with toasts:", toasts);

    if (!toasts || toasts.length === 0) {
        return null;
    }

    return (
        <div className="fixed top-12 right-4 z-50 flex flex-col space-y-2 items-end">
            {toasts.map(toast => (
                <Toast
                    key={toast.id}
                    id={toast.id}
                    message={toast.message}
                    type={toast.type}
                    duration={toast.duration}
                    onClose={removeToast}
                />
            ))}
        </div>
    );
};

export default ToastContainer;