import React, { createContext, useContext, useState, useCallback, useMemo } from 'react';
import { v4 as uuidv4 } from 'uuid';

const ToastContext = createContext();

export const ToastProvider = ({ children }) => {
    const [toasts, setToasts] = useState([]);

    // Memoize addToast để tránh tạo mới hàm mỗi khi render
    const addToast = useCallback((message, type = 'info', duration = 3000) => {
        const id = uuidv4();
        console.log("Adding toast:", { id, message, type, duration });
        setToasts(prevToasts => [...prevToasts, { id, message, type, duration }]);

        if (duration !== Infinity) {
            setTimeout(() => {
                setToasts(prevToasts => prevToasts.filter(toast => toast.id !== id));
            }, duration);
        }
        return id;
    }, []);

    // Memoize removeToast để tránh tạo mới hàm mỗi khi render
    const removeToast = useCallback(id => {
        setToasts(prevToasts => prevToasts.filter(toast => toast.id !== id));
    }, []);

    // Memoize các hàm helper
    const success = useCallback((message, duration) =>
        addToast(message, 'success', duration), [addToast]);

    const error = useCallback((message, duration) =>
        addToast(message, 'error', duration), [addToast]);

    const info = useCallback((message, duration) =>
        addToast(message, 'info', duration), [addToast]);

    const warning = useCallback((message, duration) =>
        addToast(message, 'warning', duration), [addToast]);

    // Memoize giá trị context để tránh render lại không cần thiết
    const contextValue = useMemo(() => ({
        toasts,
        addToast,
        removeToast,
        success,
        error,
        info,
        warning
    }), [toasts, addToast, removeToast, success, error, info, warning]);

    return (
        <ToastContext.Provider value={contextValue}>
            {children}
        </ToastContext.Provider>
    );
};

export const useToast = () => {
    const context = useContext(ToastContext);

    if (!context) {
        throw new Error('useToast must be used within a ToastProvider');
    }

    return context;
};