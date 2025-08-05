import axios from "axios";

const api = axios.create({
    baseURL: process.env.VUE_APP_API_URL || 'http://192.168.2.2:8080/api/',
    withCredentials: true,
    timeout: 15000,
    headers: {
        'Content-Type': "application/json",
        'Accept': "application/json",
        'X-Requested-With': 'XMLHttpRequest',
    }
});

api.interceptors.request.use(config => {
    const token = localStorage.getItem("customer_token");
    if(token){
        config.headers.Authorization = `Bearer ${token.replace(/"/g, '')}`;
    }
    return config;
}, error => Promise.reject(error));

export default api;