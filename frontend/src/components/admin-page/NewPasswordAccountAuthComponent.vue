<template>
  <div class="bg-primary">
    <div class="container">
      <!-- Outer Row -->
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
          <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
              <!-- Nested Row within Card Body -->
              <div class="row">
                <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                <div class="col-lg-6">
                    <div v-if="errorMessage" class="alert alert-danger" style="background-color: #f8d7da;color: #721c24;">
                        {{ errorMessage }}
                    </div>
                  <div class="p-5">
                    <div class="text-center">
                      <h1 class="h4 text-gray-900 mb-2">Đặt mật khẩu mới</h1>
                    </div>
                    <form class="user">
                      <div class="form-group">
                        <input
                          :type="showPassword ? 'text' : 'password'"
                          v-model="password"
                          class="form-control form-control-user"
                          aria-describedby="emailHelp"
                          placeholder="Nhập mật khẩu mới..."
                        />
                        <span class="toggle-password" @click="togglePassword">
                          <i
                            :class="
                              showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'
                            "
                          ></i>
                        </span>
                        <span class="error" v-if="validatedErrors.password">{{
                          validatedErrors.password
                        }}</span>
                      </div>
                      <div class="form-group">
                        <input
                          :type="showPassword ? 'text' : 'password'"
                          v-model="password_confirmation"
                          class="form-control form-control-user"
                          id="exampleRepeatPassword"
                          placeholder="Xác nhận mật khẩu"
                        />
                        <span
                          class="error"
                          v-if="validatedErrors.password_confirmation"
                          >{{ validatedErrors.password_confirmation }}</span
                        >
                      </div>
                      <a
                        href=""
                        @click.prevent="handleCheckPassword()"
                        class="btn btn-primary btn-user btn-block"
                      >
                        Đặt mật khẩu
                      </a>
                    </form>
                    <hr />
                    <div class="text-center">
                      <a class="small" href="forgot-password.html"
                        >Gửi lại yêu cầu mới!</a
                      >
                    </div>
                    <div class="text-center">
                      <a class="small" href="register-auth.html"
                        >Tạo tài khoản mới!</a
                      >
                    </div>
                    <div class="text-center">
                      <a class="small" href="/login-auth.html"
                        >Quay lại đăng nhập!</a
                      >
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
      password: "",
      password_confirmation: "",
      showPassword: false,
      message: "",
      validatedErrors: {},
      errorMessage: "",
      token: this.$route.query.token,
      email: this.$route.query.email,
    };
  },

  methods: {
    handleCheckPassword() {
      this.validatedErrors = {};
      if (!this.password) {
        return (this.validatedErrors.password = "Vui lòng nhập mật khẩu");
      } else if (this.password.length < 8) {
        return (this.validatedErrors.password = "Mật khẩu có ít nhất 8 ký tự");
      }

      if (this.password_confirmation != this.password) {
        return (this.validatedErrors.password_confirmation =
          "Xác nhận mật khẩu không khớp");
      }
      if (Object.keys(this.validatedErrors).length === 0) {
        this.submitNewPassword();
      }
    },
    async submitNewPassword() {
      this.validatedErrors = {};
      this.errorMessage = "";
      try {
        eventBus.emit("show-loading");
        const response = await api.post("auth/update-new-password-auth", {
          password: this.password,
          token: this.token,
          email: this.email,
        });
        this.errorMessage = "";
        alert(response.data.message);
        this.$router.push('/login-auth.html');
      } catch (error) {
        if (error.response) {
          if (error.response.status === 422) {
            (this.validatedErrors = error.response.data), error || {};
            this.errorMessage =
              error.response.data.message || "Dữ liệu không hợp lệ";
          } else if (error.response.status === 400) {
            this.errorMessage =
              error.response.data.message || "Link hoặc token đã hết hạn";
          }else if(error.response.status === 404){
            this.errorMessage = error.response.data.message;
          }
        } else {
          this.errorMessage = "Không thể kết nối đến server. Vui lòng thử lại!";
        }
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    togglePassword() {
      this.showPassword = !this.showPassword;
    },
  },
};
</script>
<style>
.bg-primary {
  min-height: 100vh;
  background-color: #4e73df;
  background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
  background-size: cover;
}
.form-control:focus {
  color: #6e707e;
  background-color: #fff;
  border-color: #bac8f3;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
form.user .form-control-user {
  font-size: 0.925rem;
  border-radius: 10rem;
  padding: 1.75rem 1.25rem;
}
.form-group .error {
  color: red;
  margin-left: 1rem;
}
/* .btn-primary {
  color: #fff;
  background-color: #4e73df;
  border-color: #4e73df;
} */
.btn-primary.focus,
.btn-primary:focus {
  color: #fff;
  background-color: #2e59d9;
  border-color: #2653d4;
  box-shadow: 0 0 0 0.2rem rgba(105, 136, 228, 0.5);
}
.toggle-password {
  position: absolute;
  top: 28%;
  right: 17%;
  transform: translateY(-50%);
  cursor: pointer;
}
</style>
