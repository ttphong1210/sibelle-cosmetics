<template>
  <div class="bg-primary">
    <div class="container">
      <div class="row justify-content-center">
        <div class="card-tag o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
              <div class="col-lg-7">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Đăng ký tài khoản!</h1>
                  </div>
                  <form class="user">
                    <div class="form-group">
                      <input
                        type="text"
                        v-model="form.name"
                        class="form-control form-control-user"
                        id="exampleFirstName"
                        placeholder="Họ và tên"
                      />
                      <span v-if="errors.name">{{ Array.isArray(errors.name) ? errors.name[0] : errors.name }}</span>
                    </div>
                    <div class="form-group">
                      <input
                        type="email"
                        v-model="form.email"
                        class="form-control form-control-user"
                        id="exampleInputEmail"
                        placeholder="Địa chỉ Email"
                      />
                      <span v-if="errors.email">{{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}</span>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-6 mb-3 mb-sm-0">
                        <input
                          :type="showPassword ? 'text' : 'password'"
                          v-model="form.password"
                          class="form-control form-control-user"
                          id="exampleInputPassword"
                          placeholder="Mật khẩu"
                        /><span
                          class="toggle-password"
                          @click="togglePassword"
                        >
                          <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                        <span v-if="errors.password">{{
                          Array.isArray(errors.password) ? errors.password[0] : errors.password
                        }}</span>
                      </div>
                      <div class="col-sm-6">
                        <input
                          :type="showPassword ? 'text' : 'password'"
                          v-model="form.password_confirmation"
                          class="form-control form-control-user"
                          id="exampleRepeatPassword"
                          placeholder="Xác nhận mật khẩu"
                        />
                        <span v-if="errors.password_confirmation">{{
                          errors.password_confirmation
                        }}</span>
                      </div>
                    </div>
                    <a
                      @click.prevent="actionSubmitRegister()"
                      class="btn btn-primary btn-user btn-block"
                    >
                      Register Account
                    </a>
                    <hr />
                    <a
                      href="index.html"
                      class="btn btn-google btn-user btn-block"
                    >
                      <i class="fab fa-google fa-fw"></i> Register with Google
                    </a>
                    <a
                      href="index.html"
                      class="btn btn-facebook btn-user btn-block"
                    >
                      <i class="fab fa-facebook-f fa-fw"></i> Register with
                      Facebook
                    </a>
                  </form>
                  <hr />
                  <div class="text-center">
                    <a class="small" href="forgot-password.html"
                      >Bạn đã quên mật khẩu ?</a
                    >
                  </div>
                  <div class="text-center">
                    <a class="small" href="login-auth.html"
                      >Bạn đã có tài khoản ! Quay về trang đăng nhập !</a
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
</template>

<script>
import api from "@/axios";
import eventBus from "@/utils/eventBus";

export default {
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {},
      message: "",
      showPassword: false
    };
  },
  methods: {
    actionSubmitRegister() {
      this.errors = {};
      if (!this.form.name) {
        this.errors.name = "Vui lòng nhập họ & tên";
      }

      if (!this.form.email) {
        this.errors.email = "Vui lòng nhập địa chỉ Email";
      } else if (!/^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(this.form.email)) {
        this.errors.email = "Email phải có định dạng @gmail.com";
      }

      if (!this.form.password) {
        this.errors.password = "Vui lòng nhập mật khẩu";
      } else if (this.form.password.length < 8) {
        this.errors.password = "Mật khẩu phải từ 8 ký tự trở lên";
      }

      if (this.form.password_confirmation != this.form.password) {
        this.errors.password_confirmation = "Xác nhận mật khẩu không khớp";
      }

      if (Object.keys(this.errors).length === 0) {
        this.submitToServer();
      }
    },

    async submitToServer() {
      try {
        eventBus.emit("show-loading");
        const formData = new FormData();
        for (const key in this.form) {
          formData.append(key, this.form[key]);
        }
        const response = await api.post("auth/register-account", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        this.message = response.data.message;
        alert(this.message);
        this.$router.push("login-auth.html");
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors.name = error.response.data.error.name;
          this.errors.email = error.response.data.error.email;
          this.errors.password = error.response.data.error.password;
        }
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    togglePassword(){
      this.showPassword = !this.showPassword;
    }
  },
};
</script>
<style scoped>
.bg-primary {
  min-height: 100vh;
  background-color: #4e73df;
  background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
  background-size: cover;
}
.card-tag {
  background-color: white;
  border-radius: 0.25rem;
}
.form-control {
  display: block;
  width: 100%;
  height: calc(1.5em + 0.75rem + 2px);
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  font-weight: 400;
  line-height: 1.5;
  color: #6e707e;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid #d1d3e2;
  border-radius: 0.35rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.form-control:focus {
  color: #6e707e;
  background-color: #fff;
  border-color: #bac8f3;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}
form.user .custom-checkbox.small label {
  line-height: 1.5rem;
}
form.user .custom-checkbox.small label {
  line-height: 1.5rem;
}
form.user .form-control-user {
  font-size: 0.925rem;
  border-radius: 10rem;
  padding: 1.75rem 1.25rem;
}
form.user .btn-user {
  font-size: 0.925rem;
  border-radius: 10rem;
  padding: 0.75rem 1rem;
}
.btn-primary {
  color: #fff !important;
  background-color: #4e73df;
  border-color: #4e73df;
}
.btn-primary:hover {
  color: #fff;
  background-color: #2e59d9;
  border-color: #2653d4;
}
.btn-primary-login.focus,
.btn-primary-login:focus {
  color: #fff;
  background-color: #2e59d9;
  border-color: #2653d4;
  box-shadow: 0 0 0 0.2rem rgba(105, 136, 228, 0.5);
}
.btn-google {
  color: #fff;
  background-color: #ea4335;
  border-color: #fff;
}
.btn-google:hover {
  color: #fff;
  background-color: #e12717;
  border-color: #e6e6e6;
}
.btn-google.focus,
.btn-google:focus {
  color: #fff;
  background-color: #e12717;
  border-color: #e6e6e6;
  box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}
.btn-google.disabled,
.btn-google:disabled {
  color: #fff;
  background-color: #ea4335;
  border-color: #fff;
}
.btn-google:not(:disabled):not(.disabled).active,
.btn-google:not(:disabled):not(.disabled):active,
.show > .btn-google.dropdown-toggle {
  color: #fff;
  background-color: #d62516;
  border-color: #dfdfdf;
}
.btn-google:not(:disabled):not(.disabled).active:focus,
.btn-google:not(:disabled):not(.disabled):active:focus,
.show > .btn-google.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}
.btn-facebook {
  color: #fff;
  background-color: #3b5998;
  border-color: #fff;
}
.btn-facebook:hover {
  color: #fff;
  background-color: #30497c;
  border-color: #e6e6e6;
}
.btn-facebook.focus,
.btn-facebook:focus {
  color: #fff;
  background-color: #30497c;
  border-color: #e6e6e6;
  box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}
.btn-facebook.disabled,
.btn-facebook:disabled {
  color: #fff;
  background-color: #3b5998;
  border-color: #fff;
}
.btn-facebook:not(:disabled):not(.disabled).active,
.btn-facebook:not(:disabled):not(.disabled):active,
.show > .btn-facebook.dropdown-toggle {
  color: #fff;
  background-color: #2d4373;
  border-color: #dfdfdf;
}
.btn-facebook:not(:disabled):not(.disabled).active:focus,
.btn-facebook:not(:disabled):not(.disabled):active:focus,
.show > .btn-facebook.dropdown-toggle:focus {
  box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.5);
}
.p-5 {
  padding: 3rem !important;
}
.toggle-password {
  position: absolute;
  top: 45%;
  right: 11%;
  transform: translateY(-50%);
  cursor: pointer;
}

.form-group span {
  margin-left: 1rem;
  color: red;
}

</style>
