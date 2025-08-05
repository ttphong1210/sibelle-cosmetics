import router from "@/router";
import axios from "axios";

const adminApi = axios.create({
  baseURL: process.env.VUE_APP_API_ADMIN_URL || 'http://192.168.2.1:8080/api/admin/',
  timeout: 10000,
});

adminApi.interceptors.request.use((config) => {
  const token = localStorage
    .getItem("token")
    ?.replace(/[\n\r"]/g, "")
    .trim();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

adminApi.interceptors.response.use(
  (response) => {
    return response;
  },

  async (error) => {
    const originalRequest = error.config;
    if (error.response?.status === 401) {
      if (originalRequest._retry) {
        console.log("Request already retried, redirecting to login page...");
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        alert("Phiên đăng nhập đã hết hạn. Vui lòng đăng nhập lại.");
        if (router.currentRoute.value.path != "/login-auth.html") {
          router.push("/login-auth.html");
        }

        return Promise.reject(error);
      }

      originalRequest._retry = true;

      localStorage.removeItem("token");
      localStorage.removeItem("user");

      if (router.currentRoute.value.path != "/login-auth.html") {
        router.push("/login-auth.html");
      }

      return Promise.reject(error);
    }

    if (error.response?.status === 403) {
      console.warn("Access forbidden:", error.response.data?.message);
      alert("Bạn không có quyền truy cập ", "error");

      return Promise.reject(error);
    }

    if (error.response?.status === 500) {
      console.error("Server error:", error.response.data?.message);
      alert("Lỗi máy chủ, vui lòng thử lại sau");
    }

    return Promise.reject(error);
  }
);

export default adminApi;
