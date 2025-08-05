<template>
  <div v-if="cartItems.length > 0" class="container px-4 py-5 mx-auto">
    <div class="row d-flex justify-content-center">
      <table class="cart-product-item-table">
        <thead id="tableheader">
          <tr>
            <td id="header-shopping-cart" class="tablesheet-col1">
              {{ cartItems.length }} sản phẩm
            </td>
            <td class="tablesheet-col2"></td>
            <td class="tablesheet-col3">Số Lượng</td>
            <td class="tablesheet-col4"></td>
          </tr>
        </thead>
      </table>
    </div>
    <div id="cart-content">
      <div
        v-for="item in cartItems"
        :key="item.id"
        class="row d-flex justify-content-center"
      >
        <table class="cart-product-item-table">
          <tbody>
            <tr>
              <td class="tablesheet-col1">
                <div class="book">
                  <img
                  :src="getProductAvatar(item.img_url)" class="book-img"
                  />
                </div>
              </td>
              <td class="tablesheet-col2">
                <div class="my-auto flex-column d-flex pad-left">
                  <div class="products-description-name">
                    <h6 class="mob-text"> <router-link :to="/detail/+item.id+'/'+item.slug+'.html'"> {{ item.name }} </router-link> </h6>
                  </div>
                  <div class="products-description-price">
                    <p class="mob-text">{{ formatCurrency(item.price) }}</p>
                  </div>
                  <div class="products-description-subprice">
                    <p class="mob-text">
                      Thành tiền:
                      {{ formatCurrency(item.price * item.quantity) }}
                    </p>
                  </div>
                  
                </div>
              </td>
              <td class="tablesheet-col3">
                <div class="quantity-input">
                  <button
                    @click="updateQuantity(item.id, item.quantity - 1)"
                    :disabled="item.quantity <= 1"
                  >
                    -
                  </button>
                  <input type="text" :value="item.quantity" readonly />
                  <button @click="updateQuantity(item.id, item.quantity + 1)">
                    +
                  </button>
                </div>
              </td>
              <td class="tablesheet-col4">
                <div class="btn-delete" @click="removeFromCart(item.id)" style="cursor: pointer;">
                  <a class="highlight">
                    <svg
                      width="18px"
                      height="18px"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 448 512"
                    >
                      <path
                        d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0L284.2 0c12.1 0 23.2 6.8 28.6 17.7L320 32l96 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 96C14.3 96 0 81.7 0 64S14.3 32 32 32l96 0 7.2-14.3zM32 128l384 0 0 320c0 35.3-28.7 64-64 64L96 512c-35.3 0-64-28.7-64-64l0-320zm96 64c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16l0 224c0 8.8 7.2 16 16 16s16-7.2 16-16l0-224c0-8.8-7.2-16-16-16z"
                      />
                    </svg>
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="card">
            <div class="row">
              <div class="col-lg-8">
                <h5>Chính sách mua hàng</h5>
                <ul>
                  <li>Sản phẩm được đổi 1 lần duy nhất, không hỗ trợ trả.</li>
                  <li>Sản phẩm còn đủ tem mác, chưa qua sử dụng.</li>
                  <li>Sản phẩm nguyên giá được đổi trong 7 ngày</li>
                  <li>
                    Sản phẩm sale chỉ hỗ trợ đổi size (nếu cửa hàng còn) trong 7
                    ngày
                  </li>
                </ul>
              </div>
              <div class="col-lg-4 mt-2">
                <div class="row d-flex justify-content-between px-4">
                  <p class="mb-1 text-left">Tạm tính:</p>
                  <h6 class="mb-1 text-right">{{ formatCurrency(cartTotal) }}</h6>
                </div>

                <div
                  class="row d-flex justify-content-between px-4"
                  id="cart-total"
                >
                  <p class="mb-1 text-left">Tổng tiền sản phẩm:</p>
                  <h6 class="mb-1 text-right">{{ formatCurrency(cartTotal) }}</h6>
                </div>
                <button class="btn-block btn-blue">
                  <span>
                    <span id="checkout"
                      ><router-link to="/checkout.html">Tiến hành đặt hàng</router-link>
                    </span>
                    <span id="check-amt">{{ formatCurrency(cartTotal) }}</span>
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="container text-center">
    <p>Chưa có sản phẩm trong giỏ hàng!</p>
    <router-link to="/products.html" class="btn btn-success">Quay lại mua sắm</router-link>
  </div>
</template>
<script>
import { CartService } from "@/utils/cart";
import eventBus from "@/utils/eventBus";

export default {
  data() {
    return {
      cartItems: [],
    };
  },
  computed: {
    cartTotal() {
        return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
    }
  },
  mounted() {
    this.cartItems = CartService.getCart();
    eventBus.on("load-cart", this.updateCart);
  },
  beforeUnmount() {
    eventBus.off("load-cart", this.updateCart);
  },
  methods: {
    updateCart() {
      this.cartItems = CartService.getCart();
    },
    removeFromCart(id) {
      CartService.removeFromCart(id);
      this.updateCart();
    },
    updateQuantity(id, quantity) {
      CartService.updateCart(id, quantity);
      this.updateCart();
    },
  },
};
</script>
<style scoped>
    @import "@/assets/css/cart.css";
    .btn-delete{
      background-color: white;
    }
</style>
