import { createRouter, createWebHistory } from "vue-router";
import home from "../router/home.js";
import admin from "./admin.js";
import adminApi from "@/api/admin.js";
import account_customer from "./account_customer.js";

const routes = [...home, ...admin, ...account_customer];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
router.beforeEach(async(to, from, next) => {
  document.title = to.meta.title || "Si Belle Cosmetics"; // Đặt tiêu đề mặc định nếu không có meta

  const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
  const requiredRole = to.matched.some((record) => record.meta.requiresRole);
  const token = localStorage
    .getItem("token")
    ?.replace(/[\n\r"]/g, "")
    .trim();
  const user = JSON.parse(localStorage.getItem("user"));

  if (requiresAuth && !token) {
    return next("/login-auth.html");
  }
  if (requiresAuth && token) {
    try {
      await adminApi.get("auth/check");
      if (requiredRole.length > 0) {
        if (!user.role || !requiredRole.some((r) => user.role === r)) {
          alert("Bạn không có quyền truy cập");
        }
      }
      return next();
    } catch (error) {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      return next("/login-auth.html");
    }
  }

  return next();
});

export default router;
