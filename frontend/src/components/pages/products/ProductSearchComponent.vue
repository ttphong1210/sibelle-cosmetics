<template>
  <section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div
          class="banner_content d-md-flex justify-content-between align-items-center"
        >
          <div class="mb-3 mb-md-0">
            <h2>Tìm kiếm với từ khoá: {{ keyword }}</h2>
          </div>
          <div class="page_link">
            <a href="/">Trang chủ</a>
            <a href="/products.html">Sản phẩm</a>
            <!-- <a href="">Women Fashion</a> -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->

  <!--================Category Product Area =================-->
  <section class="cat_product_area section_gap">
    <div class="container">
      <div class="row flex-row-reverse">
        <div class="col-lg-9">
          <div class="product_top_bar">
            <div class="left_dorp">
              <form action="">
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

                <select class="show">
                  <option value="1">Show 12</option>
                  <option value="2">Show 14</option>
                  <option value="4">Show 16</option>
                </select>
              </form>
            </div>
          </div>

          <div class="latest_product_inner">
            <div v-if="products.length > 0" class="row">
              <div
                v-for="item in products"
                :key="item.prod_id"
                class="col-lg-4 col-md-6"
              >
                <div class="single-product">
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
                        <i class="ti-eye"></i>
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
                      <a href="" @click.prevent="actionAddToCart(item)">
                        <i class="ti-shopping-cart"></i>
                      </a>
                    </div>
                  </div>
                  <div class="product-btm">
                    <a
                      :href="
                        'detail/' +
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
                      <del>{{ formatCurrency(item.prod_promotion) }}</del>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="container text-center">
              <p>Không tìm thấy sản phẩm nào với từ khoá "{{ keyword }}"!</p>
              <router-link to="/products.html" class="btn btn-success"
                >Quay lại mua sắm</router-link
              >
            </div>
          </div>
        </div>

        <SidebarFilterComponent />
      </div>
    </div>
  </section>
</template>
<script>
import favoriteProductMixin from "@/mixins/favoriteProductMixin";
import SidebarFilterComponent from "../SidebarFilterComponent.vue";
import cartMixins from "@/mixins/cartMixins";
import api from "@/axios";
import eventBus from "@/utils/eventBus";

export default {
  mixins: [favoriteProductMixin, cartMixins],
  components: { SidebarFilterComponent },
  data() {
    return {
      searchQuery: "",
      products: [],
      keyword: this.$route.query.result || "",
    };
  },
  mounted() {
    this.searchProducts();
  },
  watch: {
    $route(to, from) {
      if (to.query.result !== from.query.result) {
        this.keyword = to.query.result;
        this.searchProducts();
      }
    },
  },
  methods: {
    async searchProducts() {
      eventBus.emit('show-loading');
      try {
        const response = await api.get("search", {
          params: {
            result: this.keyword,
          },
        });
        this.products = response.data.item.data || [];
      } catch (error) {
        console.error("Lỗi khi tìm kiếm sản phẩm:", error);
      }finally{
        eventBus.emit('hide-loading');
      }
    },
  },
};
</script>
