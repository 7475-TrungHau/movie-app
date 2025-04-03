import axios from 'axios';

const API_URL = 'https://phimapi.com';
const API_APP = 'http://localhost:8000/api';

let token = localStorage.getItem('token');

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

const apiApp = axios.create({
    baseURL: API_APP,
    headers: {
        Authorization: token ? `Bearer ${token}` : null,
        'Content-Type': 'application/json',
    }
});

export const setToken = (newToken) => {
    token = newToken;
    apiApp.defaults.headers['Authorization'] = token ? `Bearer ${token}` : null;
}
export const getApp = (url, params) => apiApp.get(url, { params });
export const postApp = (url, data) => apiApp.post(url, data);
export const get = (url, params) => api.get(url, { params });

export default api;


