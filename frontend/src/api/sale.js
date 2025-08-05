import axios from "axios";

const API_BASE_URL = process.env.VUE_APP_API_URL;
export const saleApi = {
    createOrder(OrderData) {
        return axios.post(`${API_BASE_URL}sale`, OrderData);
    } ,
    //kiểm tra tồn kho
    checkInventory(items){
        return axios.post(`${API_BASE_URL}check-inventory`, {items});
    },
    //thêm hàng vào hàng đặt trước
    reservedStock(productID, totalQuantity){
        return axios.post(`${API_BASE_URL}reserved-stock`, {productID, totalQuantity});
    },
    //xoá khỏi giỏ hàng, xoá reserve_stock
    deleteReserveStock(items){
        return axios.post(`${API_BASE_URL}delete-reserved-stock`, {items});
    }
}