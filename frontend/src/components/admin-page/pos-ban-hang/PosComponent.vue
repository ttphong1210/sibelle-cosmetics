<template>
  <div class="row">
    <div class="col-md-12">
      <!-- <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
          <li class="breadcrumb-item active">
            <a href="">POS bán hàng</a>
          </li>
        </ul>
      </div> -->
      <div class="app-title">
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item">
            <a href="admin/index.html"> Trang chủ </a>
          </li>
          <li class="breadcrumb-item"><a href="">POS bán hàng</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-8">
      <div class="tile">
        <h3 class="tile-title">Phần mềm bán hàng</h3>
        <input
          type="text"
          id="myInput"
          v-model="keyword"
          @keyup.enter="searchProduct"
          placeholder="Nhập mã sản phẩm hoặc tên sản phẩm để tìm kiếm..."
        />
        <div class="du--lieu-san-pham">
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th class="so--luong">Mã hàng</th>
                <th class="so--luong">Tên sản phẩm</th>
                <!-- <th class="so--luong">Ảnh</th> -->
                <th class="so--luong">Giá bán</th>
                <th
                  class="so--luong text-center"
                  style="text-align: center; vertical-align: middle"
                ></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in listProducts" :key="item.prod_id">
                <td>713090{{ item.prod_id }}</td>
                <td>{{ item.prod_name }}</td>
                <td>{{ formatCurrency(item.prod_price) }}</td>
                <td style="text-align: center; vertical-align: middle">
                  <button
                    class="btn btn-primary btn-sm cart-plus"
                    type="button"
                    title="Thêm"
                    @click.prevent="actionAddToCart(item)"
                  >
                    <i class="fas fa-cart-plus"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="alert">
          <i class="fas fa-exclamation-triangle"></i> Gõ mã hoặc tên sản phẩm
          vào thanh tìm kiếm để thêm hàng vào đơn hàng
        </div>

        <!-- danh sach san pham don hang -->
        <div class="du--lieu-san-pham">
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th class="so--luong">Mã hàng</th>
                <th class="so--luong">Tên sản phẩm</th>
                <!-- <th class="so--luong">Ảnh</th> -->
                <th class="so--luong" width="10">Số lượng</th>
                <th class="so--luong">Giá bán</th>
                <th
                  class="so--luong text-center"
                  style="text-align: center; vertical-align: middle"
                ></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in productToCart" :key="item.id">
                <td>713090{{ item.id }}</td>
                <td>{{ item.name }}</td>

                <!-- <td>
                  <input
                    class="so--luong1"
                    type="number"
                    :value="item.quantity"
                  />
                </td> -->
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
                <td>{{ formatCurrency(item.price * item.quantity) }}</td>

                <td style="text-align: center; vertical-align: middle">
                  <button
                    class="btn btn-primary btn-sm trash"
                    type="button"
                    title="Xóa"
                    @click.prevent="removeCart(item.id)"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="tile">
        <h3 class="tile-title">Thông tin thanh toán</h3>
        <div class="row">
          <div class="form-group col-md-10">
            <label class="control-label">Họ tên khách hàng</label>
            <input
              class="form-control"
              type="text"
              placeholder="Tìm kiếm khách hàng"
            />
          </div>
          <div class="form-group col-md-2">
            <label style="text-align: center" class="control-label"
              >Tạo mới</label
            >
            <button
              class="btn btn-primary btn-them"
              data-toggle="modal"
              data-target="#exampleModalCenter"
            >C
              <i class="fas fa-user-plus"></i>
            </button>
          </div>
          <div class="form-group col-md-12">
            <label class="control-label">Nhân viên bán hàng</label>
            <select class="form-control" id="exampleSelect1">
              <option>--- Chọn nhân viên bán hàng ---</option>
              <option v-for="item in account" :key="item.id"> {{item.name}}</option>
              
            </select>
          </div>
          <div class="form-group col-md-12">
            <label class="control-label">Ghi chú đơn hàng</label>
            <textarea
              class="form-control"
              rows="4"
              placeholder="Ghi chú thêm đơn hàng"
            ></textarea>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-12">
            <label class="control-label">Hình thức thanh toán</label>
            <select class="form-control" id="exampleSelect2" required>
              <option>Thanh toán chuyển khoản</option>
              <option>Trả tiền mặt tại quầy</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Tạm tính tiền hàng: </label>
            <p class="control-all-money-tamtinh">
              = {{ formatCurrency(cartTotal) }}
            </p>
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Giảm giá (F7): </label>
            <input class="form-control" type="number" value="0" />
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Tổng cộng thanh toán: </label>
            <p class="control-all-money-total">
              = {{ formatCurrency(cartTotal) }}
            </p>
          </div>
          <div class="form-group col-md-6">
            <label class="control-label">Khách hàng đưa tiền (F8): </label>
            <input class="form-control" type="number" value="290000" />
          </div>
          <div class="form-group col-md-12">
            <label class="control-label">Khách hàng còn nợ: </label>
            <p class="control-all-money">- 129.397.213 VNĐ</p>
          </div>
          <div class="tile-footer col-md-12">
            <button class="btn btn-primary luu-san-pham" type="button">
              Lưu đơn hàng (F9)
            </button>
            <button class="btn btn-primary luu-va-in" type="button">
              Lưu và in hóa đơn (F10)
            </button>

            <a class="btn btn-secondary luu-va-in" href="admin/index.html"
              >Quay về</a
            >
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL -->
  <div
    class="modal fade"
    id="exampleModalCenter"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    data-backdrop="static"
    data-keyboard="false"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <span class="thong-tin-thanh-toan">
                <h5>Tạo mới khách hàng</h5>
              </span>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Họ và tên</label>
              <input class="form-control" type="text" required />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Địa chỉ</label>
              <input class="form-control" type="text" required />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Email</label>
              <input class="form-control" type="text" required />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Ngày sinh</label>
              <input class="form-control" type="date" required />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Số điện thoại</label>
              <input class="form-control" type="number" required />
            </div>
          </div>
          <button class="btn btn-save" type="button">Lưu lại</button>
          <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!--
MODAL
-->

  <!-- The Modal -->
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <div class="modal-header">
        <span class="close">X</span>
      </div>
    </div>
  </div>
</template>

<script>
import adminApi from "@/api/admin";
import cartMixins from "@/mixins/cartMixins";
import { CartService } from "@/utils/cart";
import eventBus from "@/utils/eventBus";

export default {
  mixins: [cartMixins],
  data() {
    return {
      keyword: "",
      listProducts: [],
      productToCart: [],
      account: ""
    };
  },
  mounted() {
    this.productToCart = CartService.getCart();
    eventBus.on("load-cart", this.updateCart);
    this.fetchDataUser();
  },
  beforeUnmount() {
    eventBus.off("load-cart", this.updateCart);
  },
  computed: {
    cartTotal() {
      return this.productToCart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
      );
    },
  },
  methods: {
    async searchProduct() {
      try {
        eventBus.emit("show-loading");
        const response = await adminApi.get(`product/search`, {
          params: { inputQuery: this.keyword },
        });
        this.listProducts = response.data.productList.data;
      } catch (error) {
        console.log(error);
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    async fetchDataUser(){
      try{
        const response = await adminApi.get("account-auth/list");
        this.account = response.data.accounts.data;
      }catch(error){
        if(error.response){
          this.message = error.response.error.message;
        }
      }
    },
    updateCart() {
      this.productToCart = CartService.getCart();
    },
    removeCart(id) {
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
<style>
@media (max-width: 480px) {
  .so--luong1 {
    width: 100% !important;
  }
}
</style>
