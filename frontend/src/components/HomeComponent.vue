<template>
  <!--================Home Banner Area =================-->
  <section class="home_banner_area mb-40">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div
          id="carouselExampleIndicators"
          class="carousel slide"
          data-ride="carousel"
        >
          <ol class="carousel-indicators">
            <li
              v-for="(slider, index) in sliders"
              :key="slider.id"
              data-target="#carouselExampleIndicators"
              :data-slide-to="index"
              :class="{ active: index === 0 }"
            ></li>
          </ol>
          <div class="carousel-inner">
            <div
              v-for="(slider, index) in sliders"
              :key="slider.id"
              class="carousel-item"
              :class="{ active: index === 0 }"
            >
              <img
                class="d-block w-100"
                :src="getImageUrlSlider(slider.image)"
                :alt="slider.title"
              />
            </div>
          </div>
          <a
            class="carousel-control-prev"
            href="#carouselExampleIndicators"
            role="button"
            data-slide="prev"
          >
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a
            class="carousel-control-next"
            href="#carouselExampleIndicators"
            role="button"
            data-slide="next"
          >
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->
  <!-- Start feature Area -->
  <section class="feature-area section_gap_bottom_custom">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-money"></i>
              <h3>Đảm bảo hoàn tiền</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-truck"></i>
              <h3>Miễn phí vận chuyển</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-support"></i>
              <h3>Hỗ trợ 24/7</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-blockchain"></i>
              <h3>Bảo mật thanh toán</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End feature Area -->

  <!--================ Feature Product Area =================-->
  <section class="feature_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>Sản Phẩm Nổi bật</span></h2>
          </div>
        </div>
      </div>
      <div id="navigation-bar">
        <button id="prev-btn" class="nav-btn">
          <i class="fa fa-angle-left" aria-hidden="true"></i>
        </button>
        <button id="next-btn" class="nav-btn">
          <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>
      </div>
      <Swiper
        :loop="true"
        :navigation="{ nextEl: '#next-btn', prevEl: '#prev-btn' }"
        :slidesPerView="4"
        :centeredSlides="true"
        :pagination="false"
        :modules="modules"
      >
        <SwiperSlide
          v-for="item in products_featured"
          :key="item.prod_id"
          class="col-md-3"
        >
          <div class="single-product">
            <form enctype="multipart/form-data">
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
                  class="img-fluid"
                  :src="getProductAvatar(item.prod_img)"
                  alt=""
                />
                <div class="p_icon">
                  <router-link
                    :to="getDetailProductUrl(item.prod_id, item.prod_slug)"
                  >
                    <i class="ti-eye icon-style"></i>
                  </router-link>
                  <a class="icon-ti-heart ti-heart-favorite">
                    <i
                      class="ti-heart icon-style"
                      @click="clickToggleFavorite(item)"
                      :class="findItemFavorite(item) ? 'favorite-active' : ''"
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
                <router-link
                  :to="getDetailProductUrl(item.prod_id, item.prod_slug)"
                  class="d-block"
                >
                  <h4>{{ item.prod_name }}</h4>
                </router-link>
                <div class="mt-3">
                  <span class="mr-4">{{
                    formatCurrency(item.prod_price)
                  }}</span>
                  <del
                    ><small>
                      {{ formatCurrency(item.prod_promotion) }}</small
                    ></del
                  >
                </div>
              </div>
            </form>
          </div>
        </SwiperSlide>
      </Swiper>
    </div>
  </section>
  <!--================ End Feature Product Area =================-->

  <!--================ New Product Area =================-->
  <section class="new_product_area section_gap_top section_gap_bottom_custom">
    <div class="container">
      <!-- Tiêu đề -->
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>SẢN PHẨM MỚI</span></h2>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Sản phẩm nổi bật -->
        <div class="col-lg-6">
          <div class="new_product">
            <h5 class="text-uppercase">Lựa chọn hàng đầu</h5>
            <h3 class="text-uppercase">Sữa tắm nước hoa</h3>
            <div class="product-img">
              <img
                class="img-fluid"
                src="@/assets/img/product/new-product/images.png"
                alt="Sữa tắm nước hoa"
              />
            </div>
            <h4>120.000</h4>
            <a href="#" class="main_btn">Thêm vào giỏ hàng</a>
          </div>
        </div>

        <!-- Danh sách sản phẩm mới -->
        <div class="col-lg-6 mt-5 mt-lg-0">
          <div class="row">
            <div
              v-for="item in newProducts"
              :key="item.prod_id"
              class="col-lg-6 col-md-6"
            >
              <div class="single-product">
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
                <div class="product-img new-product-img">
                  <img
                    class="img-fluid w-100"
                    :src="getProductAvatar(item.prod_img)"
                    alt=""
                  />
                  <div class="p_icon">
                    <router-link
                      :to="getDetailProductUrl(item.prod_id, item.prod_slug)">
                      <i class="ti-eye icon-style"></i>
                    </router-link>
                    <a
                      class="icon-ti-heart ti-heart-favorite"
                      :data-id="item.prod_id"
                    >
                      <i
                        class="ti-heart icon-style"
                        @click="clickToggleFavorite(item)"
                        :class="findItemFavorite(item) ? 'favorite-active' : ''"
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
                  <router-link
                    :to="getDetailProductUrl(item.prod_id, item.prod_slug)" class="d-block"
                  >
                    <h4>{{ item.prod_name }}</h4>
                  </router-link>
                  <div class="mt-3">
                    <span class="mr-4"
                      >{{ formatCurrency(item.prod_price) }}
                    </span>
                    <del
                      ><small>
                        {{ formatCurrency(item.prod_promotion) }}</small
                      ></del
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================ End New Product Area =================-->

  <!--================ Suggested Products Area =================-->
  <section class="inspired_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>Sản phẩm gợi ý</span></h2>
          </div>
        </div>
      </div>
      <div class="row">
        <div
          v-for="item in suggestedProducts"
          :key="item.prod_id"
          class="col-lg-3 col-md-6"
        >
          <div class="single-product">
            <input
              type="hidden"
              :value="item.prod_id"
              :class="'product_favorite_id_' + item.prod_id"
            />
            <input
              type="hidden"
              :value="item.prod_name"
              :class="'product_favorite_name_' + item.prod_name"
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

            <div class="product-img suggested-product-img">
              <img
                class="img-fluid w-100"
                :src="getProductAvatar(item.prod_img)"
                alt=""
              />
              <div class="p_icon">
                <router-link
                      :to="getDetailProductUrl(item.prod_id, item.prod_slug)">
                      <i class="ti-eye icon-style"></i>
                    </router-link>
                <a class="icon-ti-heart ti-heart-favorite">
                  <i
                    class="ti-heart icon-style"
                    @click="clickToggleFavorite(item)"
                    :class="findItemFavorite(item) ? 'favorite-active' : ''"
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
              <router-link
                    :to="getDetailProductUrl(item.prod_id, item.prod_slug)" class="d-block"
                  >
                    <h4>{{ item.prod_name }}</h4>
                  </router-link>
              <div class="mt-3">
                <span class="mr-4">{{ formatCurrency(item.prod_price) }} </span>
                <del
                  ><small>
                    {{ formatCurrency(item.prod_promotion) }}</small
                  ></del
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!--================ End Suggested Products Area =================-->
</template>
<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import api from "@/axios.js";
import favoriteProductMixins from "@/mixins/favoriteProductMixin";
import cartMixins from "@/mixins/cartMixins";
import { Navigation } from "swiper/modules";
import eventBus from "@/utils/eventBus";

export default {
  mixins: [favoriteProductMixins, cartMixins],
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [Navigation],
    };
  },
  data() {
    return {
      products_featured: [],
      newProducts: [],
      suggestedProducts: [],
      sliders: [],
    };
  },
  mounted() {
    this.fetchProducts();
    this.fetchSlider();
  },
  methods: {
    async fetchProducts() {
      eventBus.emit("show-loading");
      try {
        const responseFeatured = await api.get("productfeatured");
        this.products_featured = responseFeatured.data;

        const responseNew = await api.get("productnew");
        this.newProducts = responseNew.data;

        const responseSuggested = await api.get("productsuggested");
        this.suggestedProducts = responseSuggested.data;
      } catch (error) {
        console.error("Error fetching data", error);
        alert("Có lỗi xảy ra khi lấy dữ liệu sản phẩm. Vui lòng thử lại sau.");
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    async fetchSlider() {
      try {
        const response = await api.get("slider");
        this.sliders = response.data.sliders;
      } catch (error) {
        console.error("Error fetching data", error);
        alert("Có lỗi xảy ra khi lấy dữ liệu slider. Vui lòng thử lại sau.");
      }
    },
    getImageUrlSlider(image) {
      return `http://192.168.2.2:8080/storage/slider/${image}`;
    },
  },
};
</script>
