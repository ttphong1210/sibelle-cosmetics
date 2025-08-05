<template>
  <!--================Home Banner Area =================-->
  <section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
      <div class="container">
        <div
          class="banner_content d-md-flex justify-content-between align-items-center"
        >
          <div class="mb-3 mb-md-0">
            <h2>Theo dõi đơn hàng</h2>
            <!-- <p>Very us move be blessed multiply night</p> -->
          </div>
          <div class="page_link">
            <a href="/">Trang chủ</a>
            <a href="/tracking-order">Theo dõi đơn hàng</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================End Home Banner Area =================-->

  <!--================Tracking Box Area =================-->
  <section class="tracking_box_area section_gap">
    <div class="container">
      <div class="tracking_box_inner">
        <p>
          Để theo dõi đơn hàng của bạn, vui lòng nhập ID đơn hàng của bạn vào ô
          bên dưới và nhấn nút "Theo dõi". Mã đơn hàng đã được gửi cho bạn trong email xác nhận mà bạn đã nhận được khi đặt hàng thành công.
        </p>
        <form
          class="row tracking_form"
          @submit.prevent="actionTrackingOrder()"
        >
          <div class="col-md-12 form-group">
            <input
              type="text"
              class="form-control"
              id="order"
              name="order_code"
              v-model="orderCode"
              placeholder="Mã đơn hàng"
              required
            />
          </div>
          <!-- <div class="col-md-12 form-group">
            <input
              type="email"
              v-model="email"
              class="form-control"
              id="email"
              name="email_order"
              placeholder="Địa chỉ Email thanh toán"
              required
            />
          </div> -->
          <div class="col-md-12 form-group">
            <button
              type="submit"
              value="submit"
              class="btn submit_btn submit_btn_tracking_order"
            >
              Theo dõi
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
  <!--================End Tracking Box Area =================-->
</template>
<script>
import api from '@/axios';
import eventBus from '@/utils/eventBus';

export default {
  data() {
    return {
      orderCode: "",
      email: "",
    
    };
  },
  methods: {
    async actionTrackingOrder() {
      eventBus.emit('show-loading');
      try {
        const response = await api.post("tracking-order",{
            order_code: this.orderCode,
            email_order: this.email
        });
        this.messageSuccess = response.data.messageSuccess
        this.$router.push({
            path: "/detail-tracking-order",
            query: {
            orderCode: this.orderCode,
        }});
      } catch (error) {
        if(error.response && error.response.status === 404){
            alert(error.response.data.messageError)
            
        }else{
            this.messageError = "Có lỗi xảy ra. Vui lòng thử lại sau.";
        }
      }finally{
        eventBus.emit('hide-loading');
      }
    },
    
  },
};
</script>

<style scoped>
.tracking_box_area {
  padding-top: 0;
}
.alert {
  font-size: 16px;
  padding: 10px;
  border-radius: 4px;
}
.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
}
.alert-success {
  background-color: #d4edda;
  color: #155724;
}
</style>
