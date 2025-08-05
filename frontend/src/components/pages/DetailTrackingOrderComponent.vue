<template>
<div class="container" style="padding: 20px">
    <h2>Thông tin đơn hàng</h2>
    <div class="card mt-4">
        <div class="card-header">
            <h4>Mã đơn hàng: {{ order.order_code }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Ngày đặt hàng:</strong> {{ order.date_order }}</p>
            <p><strong>Tổng tiền:</strong> {{ formatCurrency(order.total) }}</p>
            <p><strong>Trạng thái:</strong>
                <span v-if="order.order_status == 1">Chưa giao</span>
                <span v-else-if="order.order_status == 2">Đang giao</span>
                <span v-else>Đã giao</span>
            </p>
            <p><strong>Phương thức thanh toán:</strong> {{ order.order_payment }}</p>

            <h5 class="mt-3">Thông tin khách hàng</h5>
            <p><strong>Tên:</strong> {{ order.customer?.cust_name }}</p>
            <p><strong>Email:</strong> {{ order.customer?.cust_email }}</p>
            <p><strong>Số điện thoại:</strong> {{ order.customer?.cust_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ order.customer?.address }}</p>

            <h5 class="mt-3">Chi tiết sản phẩm</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="detail in order.order_details" :key="detail.id">
                        <td>{{ detail.product.prod_name }}</td>
                        <td>{{ detail.quantily }}</td>
                        <td>{{ formatCurrency(detail.price)}} </td>
                        <td>{{ formatCurrency(detail.price * detail.quantily) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <b style="color: grey;">Tổng tiền:</b> <small>(Bao gồm phí ship)</small>
                        </td>
                        <td colspan="1" style="color: red">{{ formatCurrency(order.total) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
<script>
import api from '@/axios';
export default {

        data(){
            return {
                order: [],
                orderCode: this.$route.query.orderCode
            };
        },
        mounted(){
            this.fetchDataDetailTrackingOrder();
        },
        methods: {
            async fetchDataDetailTrackingOrder(){
                try {
                    const response = await api.get("detail-tracking-order",{
                        params: {
                        order_code: this.orderCode
                       }
                    });
                    this.order = response.data.orderItem;
                } catch (error) {
                    alert("Lỗi lấy dữ liệu đơn hàng.");
                }
            }
        }
    }
</script>
<style>
    @import '@/assets/css/trackingorder.css';
</style>