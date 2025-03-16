import axios from 'axios';

const API_URL = 'https://phimapi.com';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
    },
});

export const get = (url, params) => api.get(url, { params });

export default api;


