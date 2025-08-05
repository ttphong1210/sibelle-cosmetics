<template>
  <section class="banner_area">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div
          class="banner_content d-md-flex justify-content-between align-items-center"
        >
          <div class="mb-3 mb-md-0">
            <h2>Tất cả sản phẩm</h2>
            <div class="content-box">
              <p class="intro-text">Tại Si.Belle bạn sẽ được:</p>

              <ul class="feature-list">
                <li class="feature-item">
                  <div class="feature-icon">
                    <i class="fas fa-graduation-cap"></i>
                  </div>
                  <div class="feature-text">
                    Chia sẻ kiến thức chăm sóc da theo từng độ tuổi.
                  </div>
                </li>

                <li class="feature-item">
                  <div class="feature-icon">
                    <i class="fas fa-user-md"></i>
                  </div>
                  <div class="feature-text">
                    Tư vấn quy trình dưỡng da phù hợp theo nhu cầu.
                  </div>
                </li>

                <li class="feature-item">
                  <div class="feature-icon">
                    <i class="fas fa-certificate"></i>
                  </div>
                  <div class="feature-text">
                    Cam kết hàng chính hãng đã qua kiểm nghiệm và chọn lọc.
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Image Section -->
          <div class="col-md-5 d-none d-md-block">
            <div class="image-container">
              <div class="main-image">
                <img
                  src="https://images.unsplash.com/photo-1556228578-8c89e6adf883?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80"
                  alt="Skincare Products"
                />

                <!-- Floating elements -->
                <div class="floating-element element-1">
                  <i class="fas fa-heart"></i>
                </div>
                <div class="floating-element element-2">
                  <i class="fas fa-sparkles"></i>
                </div>
                <div class="floating-element element-3">
                  <i class="fas fa-leaf"></i>
                </div>
              </div>

              <!-- Decorative circles -->
              <div class="deco-circle circle-1"></div>
              <div class="deco-circle circle-2"></div>
              <div class="deco-circle circle-3"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->

  <!--================Category Product Area =================-->
  <section class="cat_product_area section_gap">
    <div class="container">
      <div class="row">
        <SidebarFilterComponent />

        <div class="col-lg-9">
          <div class="product_top_bar">
            <div class="left_dorp">
              <select class="sorting" id="sort">
                <option value="{{Request::url()}}?sort_by=none">
                  Sắp xếp theo...
                </option>
                <option value="{{Request::url()}}?sort_by=gia_tang_dan">
                  Giá tăng dần...
                </option>
                <option value="{{Request::url()}}?sort_by=gia_giam_dan">
                  Giá giảm dần...
                </option>
              </select>
            </div>
          </div>

          <div class="latest_product_inner">
            <div v-if="products && products.length > 0" class="row">
              <div
                v-for="item in products"
                :key="item.prod_id"
                class="col-lg-4 col-md-6"
              >
                <div class="single-product card-product">
                  <input
                    type="hidden"
                    :value="item.prod_id"
                    :class="'product_favorite_id_' + item.prod_id"
                  />
                  <input
                    type="hidden"
                    :value="item.prod_name"
                    :class="'product_favorite_name_' + item.prod_id"
                  />
                  <input
                    type="hidden"
                    :value="item.prod_img"
                    :class="'product_favorite_image_' + item.prod_id"
                  />
                  <input
                    type="hidden"
                    :value="item.prod_price"
                    :class="'product_favorite_price_' + item.prod_id"
                  />
                  <div class="product-img">
                    <img
                      class="card-img"
                      :src="getProductAvatar(item.prod_img)"
                      alt=""
                    />
                    <div class="p_icon">
                      <a
                        :href="
                          'detail/' +
                          item.prod_id +
                          '/' +
                          item.prod_slug +
                          '.html'
                        "
                      >
                        <i class="ti-eye icon-style"></i>
                      </a>
                      <a
                        class="icon-ti-heart ti-heart-favorite"
                        :data-id="item.prod_id"
                      >
                        <i
                          class="ti-heart icon-style"
                          @click="clickToggleFavorite(item)"
                          :class="
                            findItemFavorite(item) ? 'favorite-active' : ''
                          "
                        ></i>
                      </a>
                      <a
                        href="#"
                        @click.prevent="actionAddToCart(item)"
                        class="add-to-cart"
                      >
                        <i class="ti-shopping-cart icon-style"></i>
                      </a>
                    </div>
                  </div>
                  <div class="product-btm">
                    <a
                      :href="
                        '/detail/' +
                        item.prod_id +
                        '/' +
                        item.prod_slug +
                        '.html'
                      "
                      class="d-block"
                    >
                      <h4>{{ item.prod_name }}</h4>
                    </a>
                    <div class="mt-3">
                      <span class="mr-4">{{
                        formatCurrency(item.prod_price)
                      }}</span>
                      <del
                        ><small>{{
                          formatCurrency(item.prod_promotion)
                        }}</small></del
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <p>Không có sản phẩm nào!</p>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
              <nav>
                <ul class="pagination">
                  <li
                    class="page-item"
                    :class="{ disabled: currentPage === 1 }"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="changePage(currentPage - 1)"
                      >Lùi</a
                    >
                  </li>
                  <li
                    v-for="page in totalPages"
                    :key="page"
                    class="page-item"
                    :class="{ active: page === currentPage }"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="changePage(page)"
                      >{{ page }}</a
                    >
                  </li>
                  <li
                    class="page-item"
                    :class="{ disabled: currentPage === totalPages }"
                  >
                    <a
                      class="page-link"
                      href="#"
                      @click.prevent="changePage(currentPage + 1)"
                      >Tiếp</a
                    >
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Category Product Area =================-->
</template>
<script>
import api from "@/axios.js";
import favoriteProductMixins from "@/mixins/favoriteProductMixin";
import cartMixins from "@/mixins/cartMixins";
import SidebarFilterComponent from "../SidebarFilterComponent.vue";
import eventBus from "@/utils/eventBus";

export default {
  mixins: [favoriteProductMixins, cartMixins],
  components: {
    SidebarFilterComponent,
  },
  data() {
    return {
      products: [],
      currentPage: 1,
      totalPages: 1,
    };
  },
  mounted() {
    this.fetchProducts();
  },
  methods: {
    async fetchProducts(page = 1) {
      eventBus.emit("show-loading");
      try {
        const responseProducts = await api.get(`product/?page=${page}`);
        this.products = responseProducts.data.data;
        this.currentPage = responseProducts.data.current_page;
        this.totalPages = responseProducts.data.last_page;
      } catch (error) {
        console.error("Error fetch products", error);
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.fetchProducts(page);
      }
    },
  },
};
</script>

<style scoped>
.banner_area {
  position: relative;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 400px;
  overflow: hidden;
}

.banner_area::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(
      circle at 20% 80%,
      rgba(255, 182, 193, 0.3) 0%,
      transparent 50%
    ),
    radial-gradient(
      circle at 80% 20%,
      rgba(173, 216, 230, 0.3) 0%,
      transparent 50%
    );
  pointer-events: none;
}

.banner_area::after {
  content: "";
  position: absolute;
  top: -50%;
  right: -20%;
  width: 200px;
  height: 200px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  animation: float 6s ease-in-out infinite;
}

@keyframes float {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-20px) rotate(180deg);
  }
}

.banner_inner {
  position: relative;
  z-index: 2;
  min-height: 400px;
  padding: 80px 0;
}

.banner_content {
  position: relative;
  z-index: 3;
}

.banner_content h2 {
  /* font-family: 'Playfair Display', serif; */
  font-size: 3.5rem;
  font-weight: 700;
  /* color: #ffffff; */
  margin-bottom: 2rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
  position: relative;
  display: inline-block;
}

.banner_content h2::after {
  content: "";
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #ff6b6b, #feca57);
  border-radius: 2px;
  animation: slideIn 1s ease-out 0.5s both;
}

@keyframes slideIn {
  from {
    width: 0;
    opacity: 0;
  }
  to {
    width: 80px;
    opacity: 1;
  }
}

.content-box {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 2.5rem;
  /* box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 8px 16px rgba(0, 0, 0, 0.06); */
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.3s ease;
  animation: fadeInUp 1s ease-out;
}

.content-box:hover {
  transform: translateY(-5px);
  /* box-shadow: 
                0 30px 60px rgba(0, 0, 0, 0.15),
                0 12px 24px rgba(0, 0, 0, 0.1); */
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.intro-text {
  font-size: 1.1rem;
  color: #2c3e50;
  font-weight: 500;
  margin-bottom: 1.5rem;
  position: relative;
}

.intro-text::before {
  content: "✨";
  position: absolute;
  left: -30px;
  top: 0;
  font-size: 1.2rem;
  animation: sparkle 2s ease-in-out infinite;
}

@keyframes sparkle {
  0%,
  50%,
  100% {
    opacity: 1;
  }
  25%,
  75% {
    opacity: 0.5;
  }
}

.feature-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.feature-item {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1rem;
  padding: 0.8rem;
  border-radius: 12px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.feature-item::before {
  content: "";
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(102, 126, 234, 0.1),
    transparent
  );
  transition: left 0.6s ease;
}

.feature-item:hover::before {
  left: 100%;
}

.feature-item:hover {
  background: rgba(102, 126, 234, 0.05);
  transform: translateX(5px);
}

.feature-icon {
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, #667eea, #764ba2);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.feature-icon i {
  color: white;
  font-size: 1rem;
}

.feature-text {
  font-size: 0.95rem;
  color: #34495e;
  line-height: 1.6;
  font-weight: 400;
}
/* Image Section Styles */
.image-container {
  position: relative;
  height: 400px;
  perspective: 1000px;
}

.main-image {
  position: relative;
  width: 100%;
  height: 100%;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(0, 0, 0, 0.1);
  transform: rotateY(-5deg) rotateX(5deg);
  transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  animation: imageFloat 1.2s ease-out;
}

.main-image:hover {
  transform: rotateY(0deg) rotateX(0deg) scale(1.02);
  box-shadow: 0 35px 70px rgba(0, 0, 0, 0.25), 0 15px 30px rgba(0, 0, 0, 0.15);
}

@keyframes imageFloat {
  from {
    opacity: 0;
    transform: rotateY(-15deg) rotateX(10deg) translateY(50px);
  }
  to {
    opacity: 1;
    transform: rotateY(-5deg) rotateX(5deg) translateY(0);
  }
}

.main-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.main-image:hover img {
  transform: scale(1.1);
}
/* Floating Elements */
.floating-element {
  position: absolute;
  width: 50px;
  height: 50px;
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  border: 2px solid rgba(255, 255, 255, 0.3);
  z-index: 10;
}

.floating-element i {
  font-size: 1.2rem;
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.element-1 {
  top: -10px;
  right: 20px;
  animation: floatElement1 3s ease-in-out infinite;
}

.element-2 {
  bottom: 20px;
  right: -20px;
  animation: floatElement2 4s ease-in-out infinite 1s;
}

.element-3 {
  top: 50%;
  left: -25px;
  animation: floatElement3 3.5s ease-in-out infinite 0.5s;
}

@keyframes floatElement1 {
  0%,
  100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-15px) rotate(180deg);
  }
}

@keyframes floatElement2 {
  0%,
  100% {
    transform: translateX(0px) rotate(0deg);
  }
  50% {
    transform: translateX(-10px) rotate(-180deg);
  }
}

@keyframes floatElement3 {
  0%,
  100% {
    transform: translate(0px, 0px) rotate(0deg);
  }
  50% {
    transform: translate(10px, -10px) rotate(180deg);
  }
}

/* Decorative Circles */
.deco-circle {
  position: absolute;
  border-radius: 50%;
  pointer-events: none;
  z-index: 1;
}

.circle-1 {
  width: 80px;
  height: 80px;
  background: linear-gradient(
    135deg,
    rgba(255, 107, 107, 0.3),
    rgba(254, 202, 87, 0.3)
  );
  top: -20px;
  left: -20px;
  animation: pulse 4s ease-in-out infinite;
}

.circle-2 {
  width: 60px;
  height: 60px;
  background: linear-gradient(
    135deg,
    rgba(102, 126, 234, 0.3),
    rgba(118, 75, 162, 0.3)
  );
  bottom: -10px;
  left: 30px;
  animation: pulse 3s ease-in-out infinite 1s;
}

.circle-3 {
  width: 100px;
  height: 100px;
  background: linear-gradient(
    135deg,
    rgba(255, 182, 193, 0.2),
    rgba(173, 216, 230, 0.2)
  );
  top: 40%;
  right: -30px;
  animation: pulse 5s ease-in-out infinite 2s;
}

@keyframes pulse {
  0%,
  100% {
    transform: scale(1);
    opacity: 0.7;
  }
  50% {
    transform: scale(1.2);
    opacity: 0.4;
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .banner_inner {
    padding: 60px 0;
    min-height: 350px;
  }

  .banner_content h2 {
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
  }

  .content-box {
    padding: 2rem;
    margin-top: 2rem;
  }

  .intro-text {
    font-size: 1rem;
  }

  .feature-text {
    font-size: 0.9rem;
  }

  .intro-text::before {
    left: -25px;
  }
  /* Show image on mobile as background or small version */
  .image-container {
    display: none;
  }
}

@media (max-width: 576px) {
  .banner_content h2 {
    font-size: 2rem;
  }

  .content-box {
    padding: 1.5rem;
  }

  .feature-item {
    padding: 0.6rem;
  }

  .feature-icon {
    width: 35px;
    height: 35px;
  }
}

/* Floating particles animation */
.particle {
  position: absolute;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  pointer-events: none;
  animation: floatParticle 8s linear infinite;
}

.particle:nth-child(1) {
  width: 6px;
  height: 6px;
  left: 10%;
  animation-delay: 0s;
}

.particle:nth-child(2) {
  width: 8px;
  height: 8px;
  left: 20%;
  animation-delay: 2s;
}

.particle:nth-child(3) {
  width: 4px;
  height: 4px;
  left: 70%;
  animation-delay: 4s;
}

@keyframes floatParticle {
  0% {
    transform: translateY(100vh) rotate(0deg);
    opacity: 0;
  }
  10% {
    opacity: 1;
  }
  90% {
    opacity: 1;
  }
  100% {
    transform: translateY(-100px) rotate(360deg);
    opacity: 0;
  }
}
</style>
