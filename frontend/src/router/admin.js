import AdminPageComponent from "@/components/admin-page/AdminPageComponent.vue";
import ListProductComponent from "@/components/admin-page/products/ListProductComponent.vue";
import FormAddProductComponent from "@/components/admin-page/products/FormAddProductComponent.vue";
import LoginAuthenticationComponent from "@/components/admin-page/LoginAuthenticationComponent.vue";
import ForgotPasswordComponent from "@/components/admin-page/ForgotPasswordComponent.vue";
import ListUserComponent from "@/components/admin-page/user/ListUserComponent.vue";
import RegisterAuthAccountComponent from "@/components/admin-page/RegisterAccountAuthComponent.vue";
import NewPasswordAccountAuthComponent from "@/components/admin-page/NewPasswordAccountAuthComponent.vue";
import ListOrderComponent from "@/components/admin-page/order/ListOrderComponent.vue";
import AdminControlPageComponent from "@/components/admin-page/AdminControlPageComponent.vue";
import PosComponentVue from "@/components/admin-page/pos-ban-hang/PosComponent.vue";

const admin = [
  {
    path: "/login-auth.html",
    component: LoginAuthenticationComponent,
    meta: { title: "Đăng nhập Admin" },
  },
  {
    path: "/forgot-password.html",
    component: ForgotPasswordComponent,
    meta: { title: "Quên mật khẩu" },
  },
  {
    path: "/register-auth.html",
    component: RegisterAuthAccountComponent,
    meta: { title: "Đăng ký tài khoản Admin" },
  },
  {
    path: "/update-password-auth",
    component: NewPasswordAccountAuthComponent,
    meta: { title: "Đặt lại mật khẩu mới" },
  },
  {
    path: "/admin",
    component: AdminPageComponent,
    children: [
      {
        path: "index.html",
        component: AdminControlPageComponent,
        meta: { title: "Admin | Bảng điều khiển", requiresAuth: true },
      },
      {
        path: "products.html",
        component: ListProductComponent,
        meta: { title: "Admin | Danh sách sản phẩm", requiresAuth: true },
      },
      {
        path: "form-add-products.html",
        component: FormAddProductComponent,
        meta: { title: "Admin | Thêm sản phẩm" , requiresAuth: true},
      },
      {
        path: "list-user.html",
        component: ListUserComponent,
        meta: { title: "Admin | Danh sách tài khoản",requiresAuth: true, requiresRole: true },
      },
      {
        path: "list-order.html",
        component: ListOrderComponent,
        meta: { title: "Admin | Danh sách đơn hàng", requiresAuth: true },
      },
    ],
  },
  {
    path: "/phan-mem-ban-hang.html",
    component: PosComponentVue,
    meta: { title: "Phần mềm bán hàng", requiresAuth: true },
  },
];
export default admin;
