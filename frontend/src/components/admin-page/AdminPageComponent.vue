<template>
  <header class="app-header row">
    <div class="col-md-4">
      <!-- Sidebar toggle button-->
      <a
        class="app-sidebar__toggle"
        @click.prevent="toggleSidebar"
        href="#"
        data-toggle="sidebar"
        aria-label="Hide Sidebar"
        >MENU</a
      >
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <!-- Navbar Right Menu-->
      <ul class="app-nav">
        <li>
          <a class="app-nav__item" @click.prevent="actionLogOut()">
            <i class="bx bx-log-out bx-rotate-180"></i>
          </a>
        </li>
      </ul>
    </div>
  </header>
  <!-- Sidebar menu-->
  <div
    class="app-sidebar__overlay"
    data-toggle="sidebar"
    :class="{ active: sidebarVisible }"
    @click="sidebarVisible = false"
  ></div>
  <aside class="app-sidebar" :class="{ active: sidebarVisible }">
    <div class="app-sidebar__user">
      <div>
        <p class="app-sidebar__user-name"><b>Si Belle Cosmetic</b></p>
        <p class="app-sidebar__user-designation">Trang quản lý</p>
      </div>
    </div>
    <hr />
    <ul class="app-menu">
      <li>
        <router-link class="app-menu__item haha" to="/phan-mem-ban-hang.html"
          ><i class="app-menu__icon bx bx-cart-alt"></i>
          <span class="app-menu__label">POS Bán Hàng</span></router-link
        >
      </li>
      <li>
        <router-link to="/admin/index.html" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Bảng điều khiển</span>
        </router-link>
      </li>
      <li>
        <router-link to="/admin/list-user.html" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Quản lý nhân viên</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Quản lý khách hàng</span>
        </router-link>
      </li>
      <li>
        <router-link to="/admin/products.html" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Quản lý sản phẩm</span>
        </router-link>
      </li>
      <li>
        <router-link to="/admin/list-order.html" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Quản lý đơn hàng</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Quản lý nội bộ</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Bảng kê lương</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Báo cáo doanh thu</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Lịch công tác</span>
        </router-link>
      </li>
      <li>
        <router-link to="" class="app-menu__item">
          <i class="app-menu__icon bx bx-purchase-tag-alt"></i>
          <span class="app-menu__label">Cài đặt hệ thống</span>
        </router-link>
      </li>
    </ul>
  </aside>
  <main class="app-content">
    <router-view :listCategory="listCategory" :listBrand="listBrand" />
  </main>
</template>
<script>
import adminApi from "@/api/admin";
import api from "@/axios";
import eventBus from "@/utils/eventBus";

export default {
  data() {
    return {
      user: null,
      listCategory: [],
      listBrand: [],
      sidebarVisible: false,
    };
  },
  async mounted() {
    const userData = localStorage.getItem("user");
    if (userData) {
      this.user = JSON.parse(userData);
    }
    await this.fetchData("category");
    await this.fetchData("brand/list-brands");
  },
  methods: {
    toggleSidebar() {
      this.sidebarVisible = !this.sidebarVisible;
    },
    async actionLogOut() {
      if (confirm("Bạn có chắc muốn đăng xuất ?")) {
        eventBus.emit("show-loading");
        try {
          const token = localStorage.getItem("token")?.replace(/[\n\r"]/g, '').trim();
          const response = await api.post(`logout`, null, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
          });

          this.message = response.data.message;
          localStorage.removeItem("user");
          localStorage.removeItem("token");
          this.$router.push("/login-auth.html");
        } catch (error) {
          if (error.response) {
            console.log(error.response.error);
          }
        } finally {
          eventBus.emit("hide-loading");
        }
      }
    },
    async fetchData(type) {
      try {
        const response = await adminApi.get(type);
        if (response.data) {
          if (type === "category") {
            this.listCategory = response.data.listCategory;
          } else if (type === "brand/list-brands") {
            this.listBrand = response.data.listBrands;
          }
        }
      } catch (error) {
        console.log("Error", error);
      }
    },
  },
};
</script>
<style scoped>
@import "@/assets/css/admin-css/main.css";
/* .element-button .coll-sm-2{
    padding-left: 10px !important;
  } */
.search-bar {
  padding: 3px 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
