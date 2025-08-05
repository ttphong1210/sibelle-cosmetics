<template>
  <!--================Checkout Area =================-->
  <section class="checkout_area section_gap">
    <div class="container">
      <!-- Loader -->
      <div id="loader-overlay" style="display: none">
        <img src="http://192.168.2.2:8080/img/loading.gif" alt="Loading..." />
      </div>
      <!-- Thông báo -->
      <div id="message" style="display: none" class="alert alert-success"></div>

      <div class="billing_details">
        <div class="row">
          <form
            id="checkoutForm"
            class="row contact_form"
            @submit.prevent="submitOrder"
            enctype="multipart/form-data"
          >
            <div class="col-lg-8">
              <h3>Địa chỉ giao hàng</h3>
              <div class="row">
                <div class="col-md-12 form-group p_star">
                  <span class="placeholder" data-placeholder="Họ và Tên"></span>
                  <label for="name"
                    ><i class="zmdi zmdi-account material-icons-name"></i
                  ></label>
                  <input
                    type="text"
                    id="name"
                    class="form-control"
                    name="name"
                    v-model="form.name"
                  />
                  <div v-if="validationError.name" class="text-red-500">
                    {{ validationError.name[0] }}
                  </div>
                </div>
                <div class="col-md-6 form-group p_star">
                  <span
                    class="placeholder"
                    data-placeholder="Số điện thoại"
                  ></span>
                  <label for="name"
                    ><i class="zmdi zmdi-account material-icons-name"></i
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    id="number"
                    name="number_phone"
                    v-model="form.number_phone"
                  />
                  <div v-if="validationError.number_phone" class="text-red-500">
                    {{ validationError.number_phone[0] }}
                  </div>
                </div>
                <div class="col-md-6 form-group p_star">
                  <span class="placeholder" data-placeholder=" Email"></span>
                  <label for="name"
                    ><i class="zmdi zmdi-account material-icons-name"></i
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    v-model="form.email"
                  />
                  <div v-if="validationError.email" class="text-red-500">
                    {{ validationError.email[0] }}
                  </div>
                </div>
                <div class="box-body col-md-12">
                  <div class="row">
                    <div class="col-md-4 form-group">
                      <select
                        name="city"
                        id="city"
                        class="form-control choose city"
                        v-model="selectedCities"
                        @change="fetchDistricts"
                      >
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                        <option
                          v-for="c in cities"
                          :key="c.matp"
                          :value="c.matp"
                        >
                          {{ c.name_city }}
                        </option>
                      </select>
                      <div v-if="validationError.city" class="text-red-500">
                        {{ validationError.city[0] }}
                      </div>
                    </div>
                    <div class="col-md-4 form-group">
                      <select
                        name="district"
                        id="district"
                        class="form-control choose district"
                        v-model="selectedDistricts"
                        @change="fetchWard"
                      >
                        <option value="">-- Chọn quận/huyện --</option>
                        <option
                          v-for="d in districts"
                          :key="d.maqh"
                          :value="d.maqh"
                        >
                          {{ d.name_district }}
                        </option>
                      </select>
                      <div v-if="validationError.district" class="text-red-500">
                        {{ validationError.district[0] }}
                      </div>
                    </div>
                    <div class="col-md-4 form-group">
                      <select
                        name="ward"
                        id="ward"
                        class="form-control ward"
                        v-model="selectedWard"
                      >
                        <option value="0">-- Chọn phường/xã --</option>
                        <option
                          v-for="ward in wards"
                          :key="ward.xaid"
                          :value="ward.xaid"
                        >
                          {{ ward.name_ward }}
                        </option>
                      </select>
                      <div v-if="validationError.ward" class="text-red-500">
                        {{ validationError.ward[0] }}
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 form-group p_star">
                  <span
                    class="placeholder"
                    data-placeholder="Địa chỉ chi tiết"
                  ></span>
                  <label for="name"
                    ><i class="zmdi zmdi-account material-icons-name"></i
                  ></label>
                  <input
                    type="text"
                    class="form-control"
                    id="address"
                    name="address"
                    v-model="form.address"
                  />
                  <div v-if="validationError.address" class="text-red-500">
                    {{ validationError.address[0] }}
                  </div>
                </div>
                <div class="col-md-12 form-group">
                  <div class="creat_account">
                    <input type="checkbox" id="f-option2" name="selector" />
                    <label for="f-option2">Create an account?</label>
                  </div>
                </div>
                <div class="col-md-12 form-group">
                  <div class="creat_account">
                    <h3>Chi tiết vận chuyển</h3>
                    <input type="checkbox" id="f-option3" name="selector" />
                    <label for="f-option3">Gửi hàng đến địa chỉ khác?</label>
                  </div>
                  <textarea
                    class="form-control"
                    name="notes"
                    id="notes"
                    rows="1"
                    value="Không ghi chú cho đơn hàng"
                    placeholder="Ghi chú đơn hàng"
                    v-model="form.notes"
                  ></textarea>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="order_box">
                <h2>Thông tin đơn hàng</h2>
                <ul class="list">
                  <li v-for="item in cart" :key="item.id">
                    <a href="#">
                      <span style="width: 70%" class="cart-name">{{
                        item.name
                      }}</span>
                      <span style="width: 12%" class="middle"
                        >x {{ item.quantity }}</span
                      >
                      <span style="width: 20%" class="last">{{
                        formatCurrency(item.price * item.quantity)
                      }}</span>
                    </a>
                  </li>
                </ul>
                <ul class="list list_2">
                  <li style="margin: 0">
                    <a class="summary-main table" style="margin: 0px" href="#">
                      <p class="col subtotal-title">Tạm tính:</p>
                      <span class="col text-right">{{
                        formatCurrency(totalPrice)
                      }}</span>
                    </a>
                  </li>
                  <div class="check-total-checkout">
                    <li>
                      <a id="fee-ship-checkout">
                        <p class="col subtotal-title">Phí ship:</p>
                        <span> {{ formatCurrency(feeship) }} </span>
                      </a>
                    </li>
                    <li>
                      <a
                        class="summary-main table"
                        id="total-checkout"
                        href="#"
                      >
                        <p class="col total-title">Tổng tiền:</p>
                        <span class="col text-right">
                          {{ formatCurrency(totalAfterFeeship) }}
                        </span>
                      </a>
                    </li>
                  </div>
                </ul>
                <div class="payment_item">
                  <div class="radion_btn">
                    <input
                      type="radio"
                      id="f-option5"
                      name="payments"
                      value="COD"
                      v-model="form.payments"
                    />
                    <label for="f-option5">Tiền mặt</label>
                    <div class="check"></div>
                  </div>
                  <p>Vui lòng thanh toán khi nhận hàng.</p>
                </div>
                <div class="payment_item active">
                  <div class="radion_btn">
                    <input
                      type="radio"
                      id="f-option6"
                      name="payments"
                      value="credit_card"
                      v-model="form.payments"
                    />
                    <label for="f-option6">Chuyển khoản</label>
                    <img
                      src="http://192.168.2.2:8080/img/product/single-product/card.jpg"
                      alt=""
                    />
                    <div class="check"></div>
                  </div>
                  <div id="textbox" style="display: none">
                    <p class="textbox-banking">
                      Số tài khoản: 0898238258 Chủ tài khoản: TRAN THE PHONG
                      Ngân hàng: MB BANK - MB (NGAN HANG QUAN DOI ) Chi nhánh:
                      DA NANG Nội dung chuyển khoản: “ Tên + SĐT đặt hàng ”
                    </p>
                  </div>
                </div>
                <div class="form-group" style="display: grid">
                  <button type="submit" class="main_btn" name="submit">
                    Thực hiện đơn hàng
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <!--================End Checkout Area =================-->
</template>
<script>
import api from "@/axios";
import { CartService } from "@/utils/cart";
import { debounce } from "@/utils/debounce";
import eventBus from "@/utils/eventBus";

export default {
  data() {
    return {
      loading: false,
      validationError: "",
      message: "",
      form: {
        name: "",
        number_phone: "",
        email: "",
        city: "",
        district: "",
        ward: "",
        address: "",
        payments: "cash",
        notes: "",
      },
      cities: [],
      districts: [],
      wards: [],
      cart: [],
      selectedCities: "   ",
      selectedDistricts: "",
      selectedWard: "",
      feeship: "0",
      totalAfterFeeship: "0",
    };
  },
  computed: {
    totalPrice() {
      return this.cart.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
      );
    },
  },
  mounted() {
    this.fetchCity();
    this.cart = CartService.getCart();
  },
  watch: {
    selectedWard(newWard) {
      if (newWard && newWard !== "0") {
        this.calculateShippingFee();
      }
    },
    totalPrice() {
      this.calculateTotalAfterFeeship();
    },
    feeship() {
      this.calculateTotalAfterFeeship();
    },
  },
  methods: {
    getCsrfToken() {
      return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    },
    async fetchCity() {
      try {
        let responseCities = await api.get("city");
        this.cities = responseCities.data.city;
      } catch (e) {
        console.error("Lỗi khi lấy danh sách tỉnh/thành phố:", e);
      }
    },

    async fetchDistricts() {
      if (!this.selectedCities) {
        this.districts = [{ value: "", text: "-- Chọn quận/huyện --" }];
        return;
      }
      try {
        let responseDistricts = await api.post("select-shipping-information", {
          action: "city",
          ma_id: this.selectedCities,
        });

        this.districts = responseDistricts.data.districts;
        this.selectDistricts = "";
        this.wards = [];
      } catch (e) {
        console.error("Lỗi khi lấy danh sách quận/huyện:", e);
      }
    },

    async fetchWard() {
      if (!this.selectedDistricts) {
        return;
      }
      try {
        let response = await api.post("select-shipping-information", {
          action: "district",
          ma_id: this.selectedDistricts,
        });
        this.wards = response.data.wards;
        this.selectedWard = "";
      } catch (e) {
        console.error("Lỗi khi lấy danh sách phường/xã:", e);
      }
    },

    async calculateShippingFee() {
      if (
        !this.selectedCities ||
        !this.selectedDistricts ||
        !this.selectedWard
      ) {
        alert("Vui lòng nhập đầy đủ thông tin để tính phí vận chuyển!");
        return;
      }
      try {
        this.loading = true;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const response = await api.post("/charge-shipping", {
          matp: this.selectedCities,
          maqh: this.selectedDistricts,
          xaid: this.selectedWard,
          _token: token,
        });
        if (response.data) {
          this.feeship = response.data.feeship;
          this.message = "Đã cập nhật phí vận chuyển";
          setTimeout(() => (this.message = ""), 3000);
        }
      } catch (error) {
        alert("Đã xảy ra lỗi khi tính phí vận chuyển. Vui lòng thử lại.");
        this.feeship = "25000";
        console.log(error);
      } finally {
        this.calculateTotalAfterFeeship();
        this.loading = false;
      }
    },
    calculateTotalAfterFeeship() {
      const totalProduct = this.totalPrice;
      const feeAmount =
        typeof this.feeship === "string"
          ? parseInt(this.feeship.replace(/\./g, ""))
          : this.feeship;
      this.totalAfterFeeship = totalProduct + feeAmount;
    },
    async submitOrder() {
      const token = localStorage.getItem("customer_token");
      if (!token) {
        alert("Bạn cần đăng nhập để đặt hàng!");
        return;
      }
      this.message = "";
      this.validationError = "";
      eventBus.emit("show-loading");
      try {
        const orderData = {
          ...this.form,
          city: this.selectedCities,
          district: this.selectedDistricts,
          ward: this.selectedWard,
          cart: CartService.getCart(),
          totalAfterFeeship: this.totalAfterFeeship,
        };
        const response = await api.post("/checkout", orderData);
        this.message = response.data.message;
        alert(this.message);
        CartService.clearCart();
        window.location.href = "/";
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationError = error.response.data.error;
        } else {
          console.log(error);
          alert("Có lỗi xảy ra, vui lòng thử lại!");
        }
      } finally {
        eventBus.emit("hide-loading");
      }
    },
  },
  created() {
    this.calculateShippingFee = debounce(this.calculateShippingFee, 300);
  },
};
</script>
<style>
.text-red-500 {
  color: red;
}
</style>
