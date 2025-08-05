<template>
  <div class="bg-primary">
    <div class="container">
      <!-- Outer Row -->
      <div class="row justify-content-center">
          <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
              <!-- Nested Row within Card Body -->
              <div class="row">
                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                <div class="col-lg-6">
                  <div class="p-5">
                    <div class="text-center">
                      <h1 class="h4 text-gray-900 mb-4">Welcome Admin Si Belle Cosmetic!</h1>
                    </div>
                    <form class="user">
                      <div class="form-group">
                        <input
                          type="email"
                          class="form-control form-control-user"
                          id="exampleInputEmail"
                          aria-describedby="emailHelp"
                          placeholder="Enter Email Address..."
                          v-model="email"
                        />
                      </div>
                      <div class="form-group">
                        <input
                          :type="showPassword ? 'text' : 'password'"
                          class="form-control form-control-user"
                          id="exampleInputPassword"
                          placeholder="Password"
                          v-model="password"
                        />
                        <span
                          class="toggle-password"
                          @click="togglePassword"
                        >
                          <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </span>
                      </div>
                      <div class="form-group">
                        <div class="custom-control custom-checkbox small">
                          <input
                            type="checkbox"
                            class="custom-control-input"
                            id="customCheck"
                          />
                          <label class="custom-control-label" for="customCheck"
                            >Remember Me</label
                          >
                        </div>
                      </div>
                      <a
                        href=""
                        class="btn btn-primary-login btn-user btn-block" @click.prevent="actionLogin()"
                      >
                        Login
                      </a>
                      <hr />
                      <a
                        href="index.html"
                        class="btn btn-google btn-user btn-block"
                      >
                        <i class="fab fa-google fa-fw"></i> Login with Google
                      </a>
                      <a
                        href="index.html"
                        class="btn btn-facebook btn-user btn-block"
                      >
                        <i class="fab fa-facebook-f fa-fw"></i> Login with
                        Facebook
                      </a>
                    </form>
                    <hr />
                    <div class="text-center">
                      <a class="small" href="/forgot-password.html"
                        >Quên mật khẩu?</a
                      >
                    </div>
                    <div class="text-center">
                      <a class="small" href="register-auth.html"
                        >Đăng ký tài khoản!</a
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
import api from '@/axios';
import eventBus from '@/utils/eventBus';

export default {
  data(){
    return {
      message: "",
      user: "",
      showPassword: false,
    }
  },

  methods: {
    async actionLogin(){
      eventBus.emit('show-loading');
      try{
        const response = await api.post('/login',{
          email: this.email,
          password: this.password
        });
        this.message = response.data.message;
        this.user = response.data.user;
        localStorage.setItem("user", JSON.stringify(this.user));
        localStorage.setItem('token', JSON.stringify(response.data.access_token));
        this.$router.push({path: "admin/index.html"});
      }catch(error){
        alert('Đăng nhập thất bại, vui lòng kiểm tra lại');
        console.log('error', error);
      }finally{
        eventBus.emit('hide-loading');
      }
    },
    togglePassword(){
      this.showPassword = !this.showPassword;
    }
    
  }
}
</script>
<style scoped>
.bg-primary {
  min-height: 100vh;
  background-color: #4e73df;
  background-image: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
  background-size: cover;
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
.btn-primary-login{
  color: #fff;
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
.toggle-password{
  position: absolute;
    top: 33%;
    right: 17%;
    transform: translateY(-50%);
    cursor: pointer;
}
</style>
