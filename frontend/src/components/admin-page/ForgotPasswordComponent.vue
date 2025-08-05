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
                  <div class="p-5">
                    <div class="text-center">
                      <h1 class="h4 text-gray-900 mb-2">
                        Forgot Your Password?
                      </h1>
                      <p class="mb-4" style="font-size: 12px;">
                        Chỉ cần nhập địa chỉ email của bạn bên dưới và chúng tôi sẽ gửi cho bạn liên kết để đặt lại mật khẩu!
                      </p>
                    </div>
                    <form class="user">
                      <div class="form-group">
                        <input
                          type="email"
                          v-model="form.email"
                          class="form-control form-control-user"
                          id="exampleInputEmail"
                          aria-describedby="emailHelp"
                          placeholder="Enter Email Address..."
                        />
                        <span v-if="validatedErrors.email">
                          {{ Array.isArray(validatedErrors.email) ? validatedErrors.email[0] : validatedErrors.email }}
                        </span>

                      </div>
                      <a
                        href=""
                        @click.prevent="handleErrorEmail()"
                        class="btn btn-primary btn-user btn-block"
                      >
                        Gửi yêu cầu đặt lại mật khẩu
                      </a>
                    </form>
                    <hr />
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
import api from '@/axios';

  export default {
    data() {
      return {
        form: {
          email: ""
        },
        message: "",
        validatedErrors: {}
      }
    },
    methods: {
      handleErrorEmail(){
        this.validatedErrors = {};
        if(!this.form.email){
          return this.validatedErrors.email = "Vui lòng nhập Email";
        }else  if(!/^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(this.form.email)){
          return this.validatedErrors.email = "Nhập Email đúng định dạng";
        }
        if(Object.keys(this.validatedErrors).length === 0){
          this.submitToServer();
        }
      },
    async submitToServer(){
        try{
          const response = await api.post('auth/forgot-password', {
            email: this.form.email
          });
          this.message = response.data.message;
          alert(this.message);
          this.$router.go();
        }catch(error){
          if(error.response && error.response.status === 422){
            this.validatedErrors.email = error.response.data.errors.email;
          }
        }
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
.btn-primary{
  color: #fff;
  background-color: #4e73df;
  border-color: #4e73df;
}
.btn-primary:hover {
  color: #fff;
  background-color: #2e59d9;
  border-color: #2653d4;
}
.btn-primary.focus,
.btn-primary:focus {
  color: #fff;
  background-color: #2e59d9;
  border-color: #2653d4;
  box-shadow: 0 0 0 0.2rem rgba(105, 136, 228, 0.5);
}
.form-group span {
  margin-left: 1rem;
  color: red;
}
</style>