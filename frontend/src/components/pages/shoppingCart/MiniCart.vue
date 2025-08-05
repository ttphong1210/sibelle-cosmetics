<template>
 <router-link to="/cart/show.html" class="icons" style="padding-right:10px;">
    <i class="ti-shopping-cart"></i>
    <span class="button-cart-total-item">{{ cartCount }}</span>
</router-link>
<ul class="submenu-dropdown submenu-dropdown-list list-submenu-cart">
    <li class="nav-item" style="padding: 10px 15px;">
        <div class="minicart-header d-flex">
            <h5> Giỏ hàng</h5>
            <span>{{ cartCount }} sản phẩm</span>
        </div>
        <table>
            <tbody>
                <tr v-for="item in cartItems" :key="item.id" class="items-minicart">
                    <td style="width: 30%;">
                        <img width="100%" :src="'http://192.168.2.2:8080/storage/avatar/'+item.img_url">
                    </td>
                    <td class="name-cart minicart">{{item.name}}</td>
                    <td class="qty-cart minicart">{{item.quantity}}x</td>
                    <td class="price-cart minicart">{{formatCurrency(item.price)}}</td>
                     <!-- Remove Button -->
                     <button @click="removeFromCart(item.id)" class="btn-delete-danger">X</button>
                </tr>
            </tbody>
        </table>
        <div class="minicart-total">
            <div class="row">
                <div class="col-md-6">
                    Tạm tính:
                </div>
                <div class="col-md-6 text-right"> {{ formatCurrency(cartSubtotal) }}</div>
            </div>
        </div>
        <div class="minicart-button d-flex">
            <div class="col-md-6">
                <button class="link-to-cart">
                  
                  <router-link to="/cart/show.html">Xem giỏ hàng</router-link>
                </button>
            </div>
            <div class="col-md-6">
            <button class="link-to-checkout">
              <router-link :to="cartCount > 0 ? '/checkout.html' : '/products.html'">
                {{ cartCount > 0 ? 'Thanh toán' : 'Mua sắm ngay' }}
              </router-link>
            </button>
          </div>

        </div>
    </li>
</ul>
  </template>
  
<script>
import { CartService } from '@/utils/cart';
import eventBus from '@/utils/eventBus';

  export default{
    data (){
      return {
        cartItems: [],
        form: {
                name: '',
                price: '',
                img_url: '',
             
            },
      };
    },
    computed: {
        cartCount(){
            return this.cartItems.reduce((total, item) => total + item.quantity,0);
        },
        cartSubtotal(){
            return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
        },
    },
    mounted() {
      this.cartItems = CartService.getCart();
      eventBus.on('load-cart', this.updateCart);
    },
    beforeUnmount(){
      eventBus.off('load-cart', this.updateCart);
    },
    methods: {
      updateCart(){
        this.cartItems = [...CartService.getCart()];
      },
      removeFromCart(prodId){
        CartService.removeFromCart(prodId);
        this.updateCart();
      }
    }
  }
</script>
<style>
.btn-delete-danger{
  border: none;
  background-color: transparent;
  color: red;
}
</style>