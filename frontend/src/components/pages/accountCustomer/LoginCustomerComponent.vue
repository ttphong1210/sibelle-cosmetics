<template>
  <div class="container">
    <div class="returning_customer" style="margin: 5rem 0">
      <p>
        Nếu bạn đã mua sắm với chúng tôi trước đây, vui lòng nhập thông tin chi
        tiết của bạn vào ô bên dưới. Nếu bạn là khách hàng mới, vui lòng Đăng ký
        tài khoản và chuyển đến phần Thanh toán & Giao hàng.
      </p>
      <div class="row contact_form" novalidate="novalidate">
        <div class="col-md-6 login flex-item">
          <div class="wrap">
            <form @submit.prevent="validateFormLogin()">
              <div class="error" v-if="errorsMessage">
                <span class="text-red">{{ errorsMessage }}</span>
              </div>
              <div class="col-md-12 form-group p_star">
                <span
                  class="placeholder"
                  data-placeholder="Username or Email"
                ></span>
                <input
                  type="email"
                  required
                  class="form-control"
                  id="name"
                  name="email"
                  v-model="email"
                />
                <span v-if="errors.email" class="text-red">{{
                  errors.email
                }}</span>
              </div>
              <div class="col-md-12 form-group p_star">
                <span class="placeholder" data-placeholder="Password"></span>
                <input
                  required
                  class="form-control"
                  id="password"
                  name="password"
                  v-model="password"
                  :type="showPassword ? 'text' : 'password'"
                />
                <span v-if="errors.password" class="text-red">{{
                  errors.password
                }}</span>

                <div class="creat_account">
                  <input
                    type="checkbox"
                    id="check"
                    v-model="showPassword"
                    name="selector"
                  />
                  <label for="check" id="checkdisplay">{{
                    showPassword ? "Ẩn mật khẩu" : "Hiện mật khẩu"
                  }}</label>
                </div>
              </div>
              <div class="col-md-12 form-group">
                <button type="submit" value="submit" class="btn submit_btn">
                  Đăng nhập
                </button>
                <div class="creat_account">
                  <input type="checkbox" id="f-option" name="selector" />
                  <label for="f-option">Ghi nhớ đăng nhập</label>
                </div>
                <a class="lost_pass" href="{{asset('account/forgot-password')}}"
                  >Quên mật khẩu?</a
                >
              </div>
            </form>
          </div>
        </div>
        <div class="col-md-6 notification-register flex-item">
          <div class="wrap">
            <h5>Khách hàng mới ?</h5>
            <p style="font-family: 'FontAwesome'">
              Đăng ký trang web này cho phép bạn truy cập trạng thái và lịch sử
              đơn hàng của mình. Chúng tôi sẽ nhanh chóng thiết lập một tài
              khoản mới cho bạn. Vì điều này sẽ chỉ yêu cầu bạn cung cấp thông
              tin cần thiết để làm cho quá trình mua hàng nhanh hơn và dễ dàng
              hơn
            </p>
            <button class="btn">
              <a href="register.html" style="color: white" class="btn"
                >Tạo tài khoản</a
              >
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "@/axios";
import eventBus from "@/utils/eventBus";

export default {
  data() {
    return {
      email: "",
      password: "",
      showPassword: false,
      message: "",
      errors: {},
      customer: "",
      errorsMessage: "",
    };
  },

  methods: {
    submit() {
      this.$emit("submit", this.email);
    },
    validateFormLogin() {
      this.error = {};
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!this.email) {
        this.errors.email = "Vui lòng nhập email";
      } else if (!emailRegex.test(this.email)) {
        this.errors.email = "Email không hợp lệ";
      }
      if (!this.password) {
        this.errors.password = "Vui lòng nhập mật khẩu";
      } else if (this.password.length < 6) {
        this.errors.password = "Mật khẩu tối thiểu 6 ký tự";
      }
      if (Object.keys(this.errors).length === 0) {
        this.handleLogin();
      }
    },
    async handleLogin() {
      try {
        const response = await api.post("customer/login", {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem(
          "customer",
          JSON.stringify(response.data.data.customer)
        );
        localStorage.setItem(
          "customer_token",
          JSON.stringify(response.data.data.token)
        );
        this.message = response.data.message;
        this.errorsMessage = "";
        this.$router.go(-1);
        eventBus.emit("customer-login");
      } catch (error) {
        if (error.response && error.response.status === 401) {
          this.errorsMessage = error.response.data.message; // "Email hoặc mật khẩu không chính xác"
        } else if (error.response && error.response.status === 400) {
          this.errors = "Vui lòng nhập đúng định dạng email và mật khẩu.";
        } else {
          this.errors = "Đã xảy ra lỗi. Vui lòng thử lại.";
        }
      }
    },
  },
};
</script>
<style>
.contact_form {
  text-align: left;
}
.text-red {
  color: red;
}
.error {
  text-align: center;
}
</style>
