<template>
  <section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div
          class="banner_content d-md-flex justify-content-between align-items-center"
        >
          <div class="mb-3 mb-md-0">
            <h2>Sản phẩm {{ categoryName }}</h2>
            <!-- <p>Very us move be blessed multiply night</p> -->
          </div>
          <div class="page_link">
            <a href="/">Trang chủ</a>
            <!-- <a href="category.html">Shop</a>
          <a href="category.html">Women Fashion</a> -->
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
            <div class="row">
              <div
                v-for="item in productWithCategory"
                :key="item.prod_id"
                class="col-lg-4 col-md-6"
              >
                <div class="single-product">
                  <form action="">
                    <input
                      type="hidden"
                      :value="item.prod_id"
                      :class="`product_favorite_id_${item.prod_id}`"
                    />
                    <input
                      type="hidden"
                      :value="item.prod_name"
                      :class="`product_favorite_name_${item.prod_id}`"
                    />
                    <input
                      type="hidden"
                      :value="item.prod_img"
                      :class="`product_favorite_img_${item.prod_id}`"
                    />
                    <input
                      type="hidden"
                      :value="item.prod_price"
                      :class="`product_favorite_price_${item.prod_id}`"
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
                      <router-link :to="getDetailProductUrl(item.prod_id, item.prod_slug)"> <h4>{{ item.prod_name }}</h4> </router-link>
                      <div class="mt-3">
                        <span class="mr-4">{{
                          formatCurrency(item.prod_price)
                        }}</span>
                        <del> {{ formatCurrency(item.prod_promotion) }}</del>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            
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
        <SidebarFilterComponent />
      </div>
    </div>
  </section>
  <!--================End Category Product Area =================-->
</template>
<script>
import api from "@/axios.js";
import cartMixins from "@/mixins/cartMixins";
import favoriteProductMixins from "@/mixins/favoriteProductMixin";
import SidebarFilterComponent from "../SidebarFilterComponent.vue";

export default {
  mixins: [favoriteProductMixins, cartMixins],
  components: {
    SidebarFilterComponent,
  },
  data() {
    return {
      productWithCategory: [],
      categoryName: "",
      currentPage: 1,
      totalPages: 1,
    };
  },
  watch: {
    '$route.params':{
      immediate: true,
      handler(){
        this.fetchProductWithCategory(1);
      }
    }
  },
  mounted() {
    this.fetchProductWithCategory();
  },
  methods: {
    async fetchProductWithCategory(page = 1) {
      try {
        const categoryID = this.$route.params.id;
        const response = await api.get(
          `products/category/${categoryID}/?page=${page}`
        );
        this.productWithCategory = response.data.productWithCategory.data;
        this.categoryName = response.data.categoryName || "";
        this.currentPage = response.data.productWithCategory.current_page;
        this.totalPages = response.data.productWithCategory.last_page;
      } catch (e) {
        console.error("Lỗi khi lấy dữ liệu sản phẩm:", e);
        this.productWithCategory = [];
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.fetchProductWithCategory(page);
      }
    },
  },
};
</script>
