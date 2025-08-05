<template>
  <div class="header_area">
    <div class="top_menu">
      <div class="container">
        <div class="row">
          <div class="col-lg-7">
            <div class="float-left">
              <p>Phone: 077 940 6396</p>
              <p>email: Si.Belle@gmail.com</p>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="float-right">
              <ul class="right_side">
                <li>
                  <a href=""> Quà tặng </a>
                </li>
                <li>
                  <router-link to="/tracking-order.html">
                    Theo dõi đơn hàng
                  </router-link>
                </li>
                <li>
                  <a href=""> Liên hệ </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="main_menu">
      <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light w-100">
          <!-- Brand and toggle get grouped for better mobile display -->
          <router-link class="navbar-brand logo_h" to="/">
            <img
              :src="require('@/assets/img/Si.belle.jpeg')"
              alt=""
              style="width: 137px; height: 38px"
            />
          </router-link>
          <button
            id="navbar-toggler"
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
            <span class="navbar-toggler-icon"></span>
            <span class="navbar-toggler-icon"></span>
          </button>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div
            class="collapse navbar-collapse offset w-100"
            id="navbarSupportedContent"
          >
            <div class="row w-100 mr-0">
              <div class="col-lg-7 pr-0">
                <ul class="nav navbar-nav center_nav pull-right">
                  <li class="nav-item active">
                    <a class="nav-link" href="/">Trang chủ</a>
                  </li>
                  <li class="nav-item submenu dropdown">
                    <a
                      href=""
                      class="nav-link dropdown-toggle"
                      data-toggle="dropdown"
                      role="button"
                      aria-haspopup="true"
                      aria-expanded="false"
                      >Sản phẩm</a
                    >
                    <ul class="dropdown-menu">
                      <li class="nav-item">
                        <router-link class="nav-link" to="/products.html"
                          >Tất cả sản phẩm</router-link
                        >
                      </li>
                      <li
                        v-for="cate in categories"
                        :key="cate.cate_id"
                        class="nav-item"
                      >
                       
                        <router-link class="nav-link" :to="{
                          name: 'category.show',
                          params: {
                            id: cate.cate_id,
                            slug: cate.cate_slug
                          }
                        }">
                          {{ cate.cate_name }}
                        </router-link>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item submenu dropdown">
                    <a
                      href=""
                      class="nav-link dropdown-toggle"
                      data-toggle="dropdown"
                      role="button"
                      aria-haspopup="true"
                      aria-expanded="false"
                      >Thương hiệu</a
                    >
                    <ul class="dropdown-menu">
                      <li
                        v-for="brand in brands"
                        :key="brand.brand_id"
                        class="nav-item"
                      >
                        <router-link
                          class="nav-link"
                          :to="{
                            name: 'brand.show',
                            params: {
                              id: brand.brand_id,
                              slug: brand.brand_slug,
                            },
                          }"
                        >
                          {{ brand.brand_name }}
                        </router-link>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item submenu dropdown">
                    <a
                      href="#"
                      class="nav-link dropdown-toggle"
                      data-toggle="dropdown"
                      role="button"
                      aria-haspopup="true"
                      aria-expanded="false"
                      >Blog</a
                    >
                    <ul class="dropdown-menu">
                      <li class="nav-item">
                        <a class="nav-link" href="">Quà tặng</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="">Kiến thức chăm sóc</a>
                      </li>
                    </ul>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="">Liên hệ</a>
                  </li>
                </ul>
              </div>

              <div class="col-lg-5 pr-0">
                <ul class="nav navbar-nav navbar-right right_nav pull-right">
                  <li class="nav-item">
                    <div class="icons form-search-icons">
                      <div class="form-search">
                        <input
                          v-model="searchQuery"
                          type="text"
                          placeholder="Tìm kiếm.."
                          id="input-search"
                          @keydown.enter="goToSearchResults"
                          style="border: none; outline: none"
                        />
                      </div>
                    </div>
                  </li>
                  <li
                    id="list-product-minicart"
                    class="nav-item submenu dropdown"
                  >
                    <MiniCart />
                  </li>

                  <li id="user-account" class="nav-item submenu dropdown">
                    <router-link to="/customer/login.html" class="icons"
                      ><i class="ti-user" aria-hidden="true"></i>
                    </router-link>

                    <ul
                      v-if="isLoggedIn"
                      class="dropdown-menu-user show-menu-login"
                    >
                      <li class="nav-link">
                        <router-link to="/customer/profile.html"
                          >Thông tin</router-link
                        >
                      </li>
                      <li class="nav-link">
                        <a @click.prevent="handleLogOut()">Đăng xuất</a>
                      </li>
                    </ul>
                    <ul v-else class="dropdown-menu-user show-menu-login">
                      <li class="nav-link">
                        <router-link to="/customer/login.html"
                          >Đăng nhập</router-link
                        >
                      </li>
                      <li class="nav-link">
                        <router-link to="/customer/register.html"
                          >Đăng ký</router-link
                        >
                      </li>
                    </ul>
                  </li>
                  <li id="favorite-product" class="nav-item">
                    <a href="/favorite.html" class="icons">
                      <i class="ti-heart" aria-hidden="true"></i>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</template>
<script>
import eventBus from "@/utils/eventBus";
import MiniCart from "./pages/shoppingCart/MiniCart.vue";
import api from "@/axios";

export default {
  name: "HeaderComponent",
  components: {
    MiniCart,
  },
  inject: ["state"],
  data() {
    return {
      categories: [],
      brands: [],
      searchQuery: "",
    };
  },
  created() {
    this.fetchCategory();
    this.fetchBrand();
  },
  computed: {
    isLoggedIn() {
      return this.state.isAuthenticated;
    },
  },
  mounted() {
    this.checkExpiredToken();
  },
  watch: {
    isLoggedIn() {
      this.checkExpiredToken();
    },
  },
  methods: {
    async fetchCategory() {
      try {
        const response = await api.get("category");
        this.categories = response.data;
      } catch (error) {
        console.error("Error fetching data", error);
        alert("Có lỗi xảy ra khi lấy dữ liệu thể loại. Vui lòng thử lại sau.");
      }
    },

    async fetchBrand() {
      try {
        const response = await api.get("brand");
        this.brands = response.data;
      } catch (error) {
        console.error("Error fetching data", error);
        alert(
          "Có lỗi xảy ra khi lấy dữ liệu thương hiệu. Vui lòng thử lại sau. "
        );
      }
    },
    goToSearchResults() {
      this.$router.push({
        path: "/search",
        query: {
          result: this.searchQuery,
        },
      });
    },
    checkExpiredToken() {
      const token = localStorage.getItem("customer_token");
      if (!token) {
        return false;
      }
      try {
        const payload = JSON.parse(atob(token.split(".")[1]));
        const now = Math.floor(Date.now() / 1000);
        if (payload.exp && payload.exp < now) {
          alert("Phiên đăng nhập hết hạn");
          this.handleLogOut();
          localStorage.removeItem("customer");
          localStorage.removeItem("customer_token");
          eventBus.emit("customer-logout");
          return true;
        }
      } catch (error) {
        this.handleLogOut();
        return true;
      }
      return false;
    },
    async handleLogOut() {
      try {
        const res = await api.post("customer/logout");
        if (res.data.success) {
          localStorage.removeItem("customer");
          localStorage.removeItem("customer_token");
          this.$router.push("/");
          eventBus.emit("customer-logout");
          alert(res.data.message || "Đăng xuất thành công");
        } else {
          alert(res.data.message || "Đăng xuất thất bại");
        }
      } catch (error) {
        if (error.response) {
          console.error("Lỗi khi logout:", error.response.data);
          alert(error.response.data.message || "Có lỗi xảy ra khi đăng xuất.");
        } else if (error.request) {
          console.error("Không nhận được phản hồi:", error);
          alert("Không thể kết nối đến server.");
        } else {
          console.error("Lỗi:", error.message);
          alert("Lỗi không xác định.");
        }
      }
    },
  },
};
</script>
<style scoped>
.nav-item.submenu .dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background-color: white;
  border: 1px solid #ddd;
  display: none;
}
.nav-item.submenu:hover .dropdown-menu {
  display: block;
}
.dropdown-menu li {
  list-style: none;
}

.dropdown-menu li a {
  padding: 10px 15px;
  display: block;
}

.icons {
  padding: 0 10px;
  font-size: 18px;
}
/* Form tìm kiếm */
.form-search-icons {
  position: relative;
}

.form-search input {
  padding: 5px 10px;
  border-radius: 15px;
}

.show-menu-login {
  display: none;
  position: absolute;
  background: white;
  width: 150px;
  border: 1px solid #ddd;
  z-index: 100;
  top: 40px;
}

#user-account:hover .show-menu-login {
  display: block;
}
.float-right ul > li {
  margin: 0;
}
@media (max-width: 992px) {
  .navbar-collapse {
    flex-direction: column;
  }

  .right_nav {
    justify-content: flex-end;
  }

  .form-search {
    display: flex;
    justify-content: flex-end;
    width: 100%;
  }

  .nav-item {
    width: 100%;
    text-align: center;
  }
  .top_menu {
    display: none;
  }
}

@media (max-width: 768px) {
  .navbar-toggler {
    border: none;
    background-color: transparent;
  }

  .navbar-nav {
    flex-direction: column;
  }

  .navbar-toggler-icon {
    background-color: #333;
    display: block;
    width: 25px;
    height: 3px;
    margin: 4px auto;
  }

  .right_nav.pull-right {
    justify-content: center;
  }
}
/* Dropdown */
.dropdown-menu-user {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  background: #ffffff;
  min-width: 160px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  z-index: 10;
  overflow: hidden;
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.submenu:hover .dropdown-menu-user {
  display: block;
  opacity: 1;
  transform: translateY(0);
}

.dropdown-menu-user li {
  list-style: none;
}

.dropdown-menu-user .nav-link a {
  display: block;
  /* padding: 10px 15px; */
  padding: 0px 6px;
  color: #333;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 400;
  transition: background 0.3s, color 0.3s;
}

.dropdown-menu-user .nav-link a:hover {
  background: #007bff;
  color: white;
}
</style>
