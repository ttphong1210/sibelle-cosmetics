<template>
  <div class="container body">
    <div class="product-detail row">
      <div class="col-xs-4 col-md-6 product-detail-image">
        <div class="row product-detail-image-info">
          <div class="col-md-3 image-gallery">
            <!-- <div id="divId"> -->

            <div class="image-gallery-vertical">
              <div
                v-for="(file, index) in productDetailItem.prod_gallery"
                :key="index"
                class="image-item"
                @click="changeImageOnClick(file)"
              >
                <img class="imgStyle" :src="getImageUrl(file)" alt="Gallery Image" />
              </div>
            </div>
          <!-- </div> -->

              <!-- <Swiper
              direction="vertical"
                :slides-per-view="4"
                :space-between="10"
                navigation
                class="mySwiper"
              >
                <SwiperSlide
                  v-for="(file, index) in productDetailItem.prod_gallery"
                  :key="index" 
                >
                  <img
                    class="imgStyle"
                    :src="getImageUrl(file)"
                    alt="Product Image"
                    @click="changeImageOnClick(file)"
                  />
                </SwiperSlide>
              </Swiper> -->
            <!-- <div id="divId">
              <img
                v-for="(file, index) in productDetailItem.prod_gallery"
                :key="index"
                class="imgStyle"
                :src="getImageUrl(file)"
                alt="Product Image"
                @click="changeImageOnClick(file)"
              />
            </div> -->
          </div>
          <div class="col-md-9 image-main-show">
            <transition name="fade" mode="out-in">

            <img id="mainImage" :src="mainImage" :key="mainImage" />
            </transition>
          </div>
        </div>
      </div>
      <div
        class="col-xs-5 col-md-6 product-detail-info"
        style="border: 0px solid gray"
      >
        <div class="product-detail-title">
          <div class="product-detail-name clearfix">
            <h1>{{ productDetailItem.prod_name }}</h1>
            <div class="product-useful">
              <h5>
                <small
                  >Sản phẩm thuộc công dụng:
                  <a
                    style="color: black"
                    :href="
                      '/category/' +
                      productDetailItem.cate_id +
                      '/' +
                      productDetailItem.cate_slug +
                      '.html'
                    "
                    >{{ productDetailItem.cate_name }}</a
                  ></small
                >
              </h5>
            </div>
          </div>
          <div class="product-detail-sale-price">
            <div class="detail-saleoff">
              <h6 class="title-price"><small>Giá đề xuất:</small></h6>
              <span>{{ formatCurrency(productDetailItem.prod_price) }} </span>
              <del
                ><small
                  >Giá thị trường:
                  {{ formatCurrency(productDetailItem.prod_promotion) }}</small
                ></del
              >
            </div>
          </div>
          <div class="product-detail-summary">
            <div>
              <p v-html="productDetailItem.prod_summary"></p>
            </div>
          </div>
          <div class="product-detail-option">
            <div class="qty-section">
              <div class="row">
                <div class="qty-section-title col-md-4">Số lượng:</div>
                <div class="col-md-8">
                  <div id="order-qty" class="enumber-control">
                    <button class="decrement-btn" type="button">-</button>
                    <input
                      aria-label="Số lượng"
                      value="1"
                      min="1"
                      max="100"
                      maxlength="2"
                      name="quantity"
                      class="qty"
                      type="text"
                      readonly
                      style="text-align: center"
                    />
                    <button class="increment-btn" type="button">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="section" style="padding: 15px">
          <h6 class="title-attr">
            <small
              >Tình trạng:
              {{
                productDetailItem.prod_status === 0 ? "Còn hàng" : "Hết hàng"
              }}
            </small>
          </h6>
        </div>

        <div class="section" style="padding: 20px 0">
          <div class="row">
            <div class="col-md-6">
              <button class="btn btn-action btn-add-to-cart add-to-cart">
                <span
                  class="glyphicon glyphicon-shopping-cart"
                  aria-hidden="true"
                >
                </span>
                <a
                  class="icon-ti-cart ti-cart"
                  href="#"
                  @click.prevent="actionAddToCart(productDetailItem)"
                >
                  Thêm vào giỏ hàng <i class="ti-shopping-cart-full"></i>
                </a>
              </button>
            </div>
            <div class="col-md-6">
              <button class="btn btn-action btn-add-to-wishlist">
                <span
                  class="glyphicon glyphicon-heart-empty"
                  style="cursor: pointer"
                ></span
                ><a
                  class="icon-ti-heart ti-heart-favorite"
                  @click="clickToggleFavorite(productDetailItem)"
                  :class="
                    findItemFavorite(productDetailItem) ? 'favorite-active' : ''
                  "
                >
                  Thêm vào yêu thích <i class="ti-heart"></i>
                </a>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="product-detail-content">
      <div class="product-detail-main-content">
        <div class="product-detail-tab">
          <div class="row">
            <div class="product-detail-tab-item col-md-3">
              <a class="pd-tabitem-link" nav="product-info-section"
                >Thông tin sản phẩm</a
              >
            </div>
            <div class="product-detail-tab-item col-md-3">
              <a class="pd-tabitem-link" nav="product-user-guide-section"
                >Hướng dẫn sử dụng</a
              >
            </div>
            <div class="product-detail-tab-item col-md-3">
              <a class="pd-tabitem-link" nav="product-specification-section"
                >Thành phần</a
              >
            </div>
            <div class="product-detail-tab-item col-md-3">
              <a
                class="pd-tabitem-link product-review-section"
                nav="product-review-section"
                >Đánh giá</a
              >
            </div>
          </div>
        </div>
        <div id="product-info-section" class="product-detail-description">
          <div class="product-detail-description-wrapper">
            <h2 class="product-detail-information-title"></h2>
          </div>
          <div
            class="prduct-detail-description-content"
            v-html="productDetailItem.prod_des"
          ></div>
        </div>
        <div id="product-review-section" class="product-detail-review-section">
          <div class="product-detail-description-wrapper">
            <h2 class="product-detail-information-title">
              Đánh giá & nhận xét
            </h2>
          </div>
          <div class="product-detail-review-content">
            <div id="review-summary" class="row">
              <div class="product-review col-md-2 float-left text-right">
                <a class="btnWriteReview btn elife-btn-yellow text-uppercase"
                  >Viết nhận xét</a
                >
              </div>

              <div
                id="reviewForm"
                class="review-form col-md-6"
                style="display: none; background: #f5f5f5"
              >
                <p style="margin-top: 10px">
                  Xin vui lòng chia sẻ đánh giá của bạn về sản phẩm này
                </p>
                <form action="" method="">
                  <input
                    type="hidden"
                    name="product_id"
                    class="product_id"
                    value="{{ $item->prod_id }}"
                  />
                  <div class="form-group">
                    <label for="name">Tiêu đề đánh giá (optional)</label>
                    <input
                      type="text"
                      class="form-control"
                      id="name"
                      name="name"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label for="rating">Đánh giá</label>
                    <select
                      class="form-control"
                      id="rating"
                      name="rating"
                      required
                    >
                      <option value="5">5 sao</option>
                      <option value="4">4 sao</option>
                      <option value="3">3 sao</option>
                      <option value="2">2 sao</option>
                      <option value="1">1 sao</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="comment">Nhận xét</label>
                    <textarea
                      class="form-control"
                      id="comment_content"
                      name="comment_content"
                      rows="4"
                      required
                    ></textarea>
                  </div>
                  <button
                    type="submit"
                    class="btnWriteReview btn-submit-Write-Review btn btn-primary"
                  >
                    Gửi nhận xét
                  </button>
                </form>
              </div>
            </div>
            <!-- Phần hiển thị bình luận -->
            <div class="user-comments mt-5 col-md-12">
              <h4 class="text-uppercase">Nhận xét về sản phẩm</h4>
              <!-- <form>
                            {{csrf_field()}} -->
              <!-- <input type="hidden" name="_token" value=""> -->
              <!-- <input type="hidden" name="comments_product_id" class="comment_product_id" value="{{ $item->prod_id }}">
                        <div id="show_comments">
                            @foreach ($comments as $cmt)
                            <div class="comment row mb-4">
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    
                                    <img src="" alt="avatar" class="img-fluid rounded-circle" style="width: 70px; height: 70px;">
                                </div>
                                <div class="col-md-10">
                                    <div class="comment-header d-flex justify-content-between">
                                        <strong>{{ $cmt->name }}</strong>
                                        <span class="text-muted">{{ $cmt->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="comment-body mt-2">
                                        <p>{{ $cmt->comment }}</p>
                                        <span class="badge badge-primary">{{ $cmt->rating }} sao</span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @endforeach

                        </div> -->
              <!-- </form> -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="product-different">
      <div class="title">
        <h3>Sản phẩm khác</h3>
      </div>
      <div id="navigation-bar">
        <button id="prev-btn" class="nav-btn">
          <i class="fa fa-angle-left" aria-hidden="true"></i>
        </button>
        <button id="next-btn" class="nav-btn">
          <i class="fa fa-angle-right" aria-hidden="true"></i>
        </button>
      </div>
      <div class="lunchbox">
        <div class="row">
          <Swiper
            :loop="true"
            :navigation="{ nextEl: '#next-btn', prevEl: '#prev-btn' }"
            :slidesPerView="4"
            :centeredSlides="true"
            :pagination="false"
            :modules="modules"
          >
            <SwiperSlide
              v-for="item in randomProduct"
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
                      <a
                        :href="
                          getDetailProductUrl(item.prod_id, item.prod_slug)
                        "
                      >
                        <i class="ti-eye icon-style"></i>
                      </a>
                      <a class="icon-ti-heart ti-heart-favorite">
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
                      :href="getDetailProductUrl(item.prod_id, item.prod_slug)"
                      class="d-block"
                    >
                      <h4>{{ item.prod_name }}</h4>
                    </a>
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
      </div>
    </div>
  </div>
</template>
<script>
import api from "@/axios";
import favoriteProductMixin from "@/mixins/favoriteProductMixin";
import { CartService } from "@/utils/cart";
import { SwiperSlide, Swiper } from "swiper/vue";
import { Navigation } from "swiper/modules";
import eventBus from "@/utils/eventBus";

export default {
  mixins: [favoriteProductMixin],
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
      productDetailItem: [],
      randomProduct: [],
      mainImage: "",
      prevBtn: null,
      nextBtn: null,
    };
  },
  mounted() {
    this.fetchProductDetail();
    this.$nextTick(() => {
      this.prevBtn = this.$refs.prevBtn;
      this.nextBtn = this.$refs.nextBtn;
    });
  },
  methods: {
    async fetchProductDetail() {
      eventBus.emit('show-loading');
      const productID = this.$route.params.id;
      try {
        const response = await api.get(`detail/${productID}`);
        this.productDetailItem = response.data.product;
        this.randomProduct = response.data.randomProduct;
        this.mainImage = `http://192.168.2.2:8080/storage/avatar/${this.productDetailItem.prod_img}`;
      } catch (e) {
        alert("Lỗi lấy dữ liệu chi tiết sản phẩm", e);
      }finally{
        eventBus.emit('hide-loading');
      }
    },
    changeImageOnClick(file) {
      this.mainImage = this.getImageUrl(file);
    },
    getImageUrl(file) {
      return `http://192.168.2.2:8080/storage/gallery/${file}`;
    },
    async actionAddToCart(product) {
      await CartService.addToCart(product);
    },
  },
};
</script>
<style scoped>
@import "@/assets/css/detail.css";
</style> 
