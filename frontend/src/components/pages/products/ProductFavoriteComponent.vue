<template>
     <div class="container px-4 py-5 mx-auto">
    <div class="row d-flex justify-content-center border-top" style="padding-top: 40px">
      <div class="col-2 title-for-product-favorite">
        <div class="row d-flex">
          <div class="my-auto flex-column d-flex pad-left">
            <span class="mob-text">Sản phẩm yêu thích</span>
          </div>
        </div>
      </div>

      <div class="my-auto col-10 item-favorite" style="padding: 0 40px">
        <div v-if="favorites.length > 0">
          <div v-for="item in favorites" :key="item.prod_id" class="row text-right favorite">
            <div class="col-2">
              <img style="width: auto" :src="getProductAvatar(item.prod_img)" alt="Product Image" />
            </div>
            <div class="col-8 product-favorite">
              <div class="product-favorite-name">
                <a :href="/detail/+item.prod_id+'/'+item.prod_slug+'.html'"> <span>{{ item.prod_name }}</span> </a>
              </div>
              <div class="product-favorite-price">
                <span>Giá: {{ formatCurrency(item.prod_price) }}</span>
              </div>
            </div>
            <div class="col-2 product-favorite btn-buy">
              <button class="btn btn-buy-product" @click.prevent="actionAddToCart(item)"> <span class="cart-text">Giỏ hàng</span>  <i class="ti-shopping-cart icon-style"></i></button>
            </div>
          </div>
        </div>
        <div v-else class="row text-center">
          <div class="col-sm-12">
            <br /><br />
            <p>Chưa có sản phẩm được yêu thích!</p>
            <router-link to="/products.html" class="btn btn-success" style="border-radius: 20px; width: 20%">Quay lại mua sắm</router-link>
            <br /><br />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import cartMixins from '@/mixins/cartMixins';
import { FavoriteProductService } from '@/utils/favorite';

    
    export default {
        mixins: [cartMixins],
        data(){
            return {
                favorites: [],
            }
        },
        mounted(){
            this.fetchDataFavorite();
        },
        methods: {
            fetchDataFavorite(){
                this.favorites = FavoriteProductService.getFavorite();
            }
        }
    }
</script>
<style scoped>
.btn-buy-product {
  background-color: #ff5a5f;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 5px;
  cursor: pointer;
}

.btn-buy-product:hover {
  background-color: #e0464e;
}

.favorite img {
  max-width: 100px;
  height: auto;
}

.product-favorite-name {
  font-size: 16px;
  font-weight: bold;
}
.product-favorite-name a{
  color: black;
}

.product-favorite-price {
  font-size: 15px;
  color: red;
}
@media (max-width: 468px){
  .product-favorite-name{
    font-size: 11px;
  }
  .product-favorite-price {
  font-size: 10px;  
  }
  .btn-buy-product {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .btn-buy-product i {
    margin-left: 0; /* Đảm bảo icon ở giữa nếu có margin */
  }

  .btn-buy-product::before {
    content: ""; /* Loại bỏ nội dung text "Giỏ hàng" */
  }

  .btn-buy-product span, 
  .btn-buy-product i + span { /* Ẩn văn bản nếu "Giỏ hàng" nằm trong thẻ span */
    display: none;
  }
}
</style>