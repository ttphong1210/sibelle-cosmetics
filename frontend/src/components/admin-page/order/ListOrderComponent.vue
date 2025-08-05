<template>
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active">
        <a href="/admin/list-order.html">Danh sách đơn hàng</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="row element-button">
            <div class="col-sm-2">
              <a
                class="btn btn-add btn-sm"
                href="form-add-don-hang.html"
                title="Thêm"
                ><i class="fas fa-plus"></i> Tạo mới đơn hàng</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-delete btn-sm nhap-tu-file"
                type="button"
                title="Nhập"
                onclick="myFunction(this)"
                ><i class="fas fa-file-upload"></i> Tải từ file</a
              >
            </div>

            <div class="col-sm-2">
              <a
                class="btn btn-delete btn-sm print-file"
                type="button"
                title="In"
                onclick="myApp.printTable()"
                ><i class="fas fa-print"></i> In dữ liệu</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-delete btn-sm print-file js-textareacopybtn"
                type="button"
                title="Sao chép"
                ><i class="fas fa-copy"></i> Sao chép</a
              >
            </div>

            <div class="col-sm-2">
              <a class="btn btn-excel btn-sm" href="" title="In"
                ><i class="fas fa-file-excel"></i> Xuất Excel</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-delete btn-sm pdf-file"
                type="button"
                title="In"
                onclick="myFunction(this)"
                ><i class="fas fa-file-pdf"></i> Xuất PDF</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-delete btn-sm"
                type="button"
                title="Xóa"
                onclick="myFunction(this)"
                ><i class="fas fa-trash-alt"></i> Xóa tất cả
              </a>
            </div>
          </div>

          <div
            id="sampleTable_wrapper"
            class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"
          >
            <div class="row" @keyup.enter="fetchDataOrder">
              <!-- <form class="row"> -->
              <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="sampleTable_length">
                  <label
                    >Hiện
                    <select
                      v-model="statusFilter"
                      name="sampleTable_length"
                      aria-controls="sampleTable"
                      class="form-control form-control-sm"
                    >
                      <option :value="0">Tất cả đơn hàng</option>
                      <option :value="1">Chưa giao hàng</option>
                      <option :value="2">Đang giao hàng</option>
                      <option :value="3">Hoàn thành</option>
                    </select>
                    danh mục, có tổng {{ totalOrder }} đơn hàng</label
                  >
                </div>
              </div>
              <div class="col-sm-12 col-md-6">
                <div id="sampleTable_filter" class="dataTables_filter">
                  <label
                    >Tìm kiếm:<input
                      type="search"
                      class="form-control form-control-sm"
                      placeholder="Tìm mã đơn, tên, email"
                      aria-controls="sampleTable"
                      v-model="searchQuery"
                  /></label>
                </div>
              </div>
              <!-- </form> -->
            </div>

            <div class="row">
              <table class="table table-hover table-bordered" id="sampleTable">
                <thead>
                  <tr>
                    <th width="10"><input type="checkbox" id="all" /></th>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Đơn hàng</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Tình trạng</th>
                    <th>Tính năng</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="order in orders" :key="order.id">
                    <td width="10">
                      <input type="checkbox" name="check1" value="1" />
                    </td>
                    <td>{{ order.order_code }}</td>
                    <td>{{ order.cust_name }}</td>
                    <td class="name-text-ellipsis">
                      {{ order.product_names }}
                    </td>
                    <td>{{ order.total_quantity_products }}</td>
                    <td>{{ formatCurrency(order.total) }}</td>
                    <td>
                      <span
                        :class="
                          orderStatusClass[order.order_status] ||
                          'badge bg-dark'
                        "
                        >{{
                          orderStatusMap[order.order_status] || "Không xác định"
                        }}</span
                      >
                    </td>
                    <td>
                      <button
                        class="btn btn-primary btn-sm trash"
                        type="button"
                        title="Xóa"
                        @click.prevent="deleteOrder(order.id)"
                      >
                        <i class="fas fa-trash-alt"></i>
                      </button>
                      <button
                        class="btn btn-primary btn-sm edit"
                        type="button"
                        title="Sửa"
                        data-toggle="modal"
                        data-target="#ModalUP"
                        @click.prevent="setOrderEdit(order)"
                      >
                        <i class="fa fa-edit"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="row">
              <div class="col-sm-12 col-md-5">
                <div
                  class="dataTables_info"
                  id="sampleTable_info"
                  role="status"
                  aria-live="polite"
                >
                  Hiện {{ toOrder }} của {{ totalOrder }} danh mục
                </div>
              </div>
              <!-- Pagination -->
              <div class="d-flex justify-content-end mt-3  col-md-7">
                <nav>
                  <ul class="pagination">
                    <li
                      class="page-item"
                      :class="{ disabled: currentPage === 1 }"
                    >
                      <a
                        class="page-link"
                        href="#"
                        @click.prevent="changePage(currentPage - 1)"
                        >Lùi</a
                      >
                    </li>
                    <li
                      v-for="page in totalPages"
                      :key="page"
                      class="page-item"
                      :class="{ active: page === currentPage }"
                    >
                      <a
                        class="page-link"
                        href="#"
                        @click.prevent="changePage(page)"
                        >{{ page }}</a
                      >
                    </li>
                    <li
                      class="page-item"
                      :class="{ disabled: currentPage === totalPages }"
                    >
                      <a
                        class="page-link"
                        href="#"
                        @click.prevent="changePage(currentPage + 1)"
                        >Tiếp</a
                      >
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div
    class="modal fade"
    id="ModalUP"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
    data-keyboard="false"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <span class="thong-tin-thanh-toan">
                <h5>Thông tin đơn hàng</h5>
              </span>
            </div>
          </div>
          <div class="row" v-if="orderToEdit">
            <table class="table table-hover table-bordered" id="sampleTable">
              <thead>
                <tr>
                  <th class="col-md-4">Thông tin khách hàng</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Họ và Tên khách hàng</td>
                  <td>{{ orderToEdit.cust_name }}</td>
                </tr>
                <tr>
                  <td>Ngày đặt hàng</td>
                  <td>{{ orderToEdit.date_order }}</td>
                </tr>
                <tr>
                  <td>Số điện thoại</td>
                  <td>{{ orderToEdit.cust_phone }}</td>
                </tr>
                <tr>
                  <td>Địa chỉ</td>
                  <td>{{ orderToEdit.address }}</td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td>{{ orderToEdit.cust_email }}</td>
                </tr>
                <tr>
                  <td>Thanh toán</td>
                  <td></td>
                </tr>
                <tr>
                  <td>Ghi chú</td>
                  <td>{{ orderToEdit.notes }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <table class="table table-hover table-bordered" id="sampleTable">
            <thead>
              <tr>
                <th class="sorting col-md-1">STT</th>
                <th class="sorting_asc col-md-4">Tên sản phẩm</th>
                <th class="sorting col-md-2">Số lượng</th>
                <th class="sorting col-md-2">Đơn giá</th>
                <th class="sorting col-md-2">Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in productWithOrder" :key="item.prod_id">
                <td>{{ index + 1 }}</td>
                <td>{{ item.prod_name }}</td>
                <td>{{ item.quantily }}</td>
                <td>{{ formatCurrency(item.prod_price) }}</td>
                <td>{{ formatCurrency(item.prod_price * item.quantily) }}</td>
              </tr>
              <tr>
                <td colspan="3">
                  <b>Tổng tiền:</b> <small>(Bao gồm phí ship)</small>
                </td>
                <td></td>
                <td colspan="1">
                  <b class="text-red">{{ formatCurrency(orderTotal) }}</b>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="exampleSelect1" class="control-label"
                >Trạng thái đơn hàng</label
              >
              <select
                class="form-control"
                id="exampleSelect1"
                v-model="selectedStatusOrder"
              >
                <option :value="1">Chưa giao hàng</option>
                <option :value="2">Đang giao hàng</option>
                <option :value="3">Hoàn thành</option>
              </select>
            </div>
          </div>

          <a href="#" style="float: right; font-weight: 600; color: #ea0000"
            >Chỉnh sửa nâng cao</a
          >
          <button
            class="btn btn-save"
            type="button"
            @click.prevent="updateOrderStatus(orderToEdit.id)"
          >
            Lưu lại
          </button>
          <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
</template>
<script>
import adminApi from "@/api/admin";
import eventBus from "@/utils/eventBus";

export default {
  props: {
    listCategory: Array,
    listBrand: Array,
  },
  data() {
    return {
      orders: "",
      orderToEdit: null,
      productWithOrder: null,
      orderTotal: null,
      selectedStatusOrder: null,
      totalPages: 1,
      currentPage: 1,
      totalOrder: "",
      toOrder: "",
      searchQuery: "",
      statusFilter: 0,
      message: "",
      orderStatusMap: {
        1: "Chưa giao hàng",
        2: "Đang giao hàng",
        3: "Hoàn thành",
      },
      orderStatusClass: {
        1: "badge bg-secondary",
        2: "badge bg-warning",
        3: "badge bg-success",
      },
    };
  },
  mounted() {
    this.fetchDataOrder();
  },
  methods: {
    async fetchDataOrder(page = 1) {
      try {
        eventBus.emit("show-loading");
        const params = {
          page,
          search: this.searchQuery,
          status: this.statusFilter,
        };
        const response = await adminApi.get(`/order/list-order?`, { params });

        this.orders = response.data.order.data;
        this.totalPages = response.data.order.last_page;
        this.currentPage = response.data.order.current_page;
        this.totalOrder = response.data.order.total;
        this.toOrder = response.data.order.to;
      } catch (error) {
        if (error.response) {
          alert(error.response.message);
        }
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.fetchDataOrder(page);
      }
    },
    async setOrderEdit(order) {
      try {
        eventBus.emit("show-loading");
        this.orderToEdit = { ...order };
        const orderID = this.orderToEdit.id;
        const response = await adminApi.get(`order/edit-order/${orderID}`);
        this.productWithOrder = response.data.data;
        this.orderTotal = response.data.total;
        this.selectedStatusOrder = this.orderToEdit.order_status;
      } catch (error) {
        if (error.response) {
          console.log("Error:", error.response);
        }
        console.log(error);
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    async updateOrderStatus(orderID) {
      try {
        eventBus.emit("show-loading");
        const status = this.selectedStatusOrder;
        const response = await adminApi.post(`order/edit-order/${orderID}`, {
          order_status: status,
        });
        this.message = response.data.message;
        await this.fetchDataOrder(this.currentPage);
      } catch (error) {
        console.log("error: ", error);
      } finally {
        eventBus.emit("hide-loading");
      }
    },
    async deleteOrder(orderID) {
      if (confirm("Bạn có chắc muốn xóa đơn hàng này?")) {
        try {
          eventBus.emit("show-loading");
          const response = await adminApi.delete(`order/delete/${orderID}`);
          alert(response.data.message);
          await this.fetchDataOrder(this.currentPage);
        } catch (error) {
          if (error.response) {
            if (error.response.status === 403) {
              alert("Bạn không có quyền xoá đơn hàng");
            }
          }
        } finally {
          eventBus.emit("hide-loading");
        }
      }
    },
  },
};
</script>
<style>
.name-text-ellipsis {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  max-width: 500px;
}
</style>
