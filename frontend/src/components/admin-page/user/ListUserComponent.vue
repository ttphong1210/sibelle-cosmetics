<template>
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb side">
      <li class="breadcrumb-item active">
        <a href="">Danh sách nhân viên</a>
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
                href="form-add-nhan-vien.html"
                title="Thêm"
                ><i class="fas fa-plus"></i> Tạo mới nhân viên</a
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
          <table
            class="table table-hover table-bordered js-copytextarea"
            cellpadding="0"
            cellspacing="0"
            border="0"
            id="sampleTable"
          >
            <thead>
              <tr>
                <th width="10"><input type="checkbox" id="all" /></th>
                <th>ID nhân viên</th>
                <th width="150">Họ và tên</th>
                <th width="20">Ảnh thẻ</th>
                <th width="250">Email</th>
                <!-- <th>Ngày sinh</th> -->
                <!-- <th>Giới tính</th> -->
                <th>SĐT</th>
                <th>Chức vụ</th>
                <th width="100">Tính năng</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in account" :key="item.id">
                <td width="10">
                  <input type="checkbox" name="check1" value="1" />
                </td>
                <td>#CD1283{{ item.id }}</td>
                <td>{{ item.name }}</td>

                <td>
                  <img
                    class="img-card-person"
                    :src="`http://192.168.2.2:8080/storage/account-avatar/${item.image}`"
                    alt=""
                  />
                </td>
                <td>{{ item.email }}</td>
                <!-- <td>12/02/1999</td> -->
                <!-- <td>Nữ</td> -->
                <td>{{ item.number_phone }}</td>
                <td>{{ item.roles[0]?.name || "Không có quyền" }}</td>
                <td class="table-td-center">
                  <button
                    class="btn btn-primary btn-sm trash"
                    type="button"
                    title="Xóa"
                    onclick="myFunction(this)"
                  >
                    <i class="fas fa-trash-alt"></i>
                  </button>
                  <button
                    class="btn btn-primary btn-sm edit"
                    type="button"
                    title="Sửa"
                    id="show-emp"
                    data-toggle="modal"
                    data-target="#ModalUP"
                    @click="handleEditAccount(item)"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--
  MODAL
-->
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
                <h5>Chỉnh sửa thông tin nhân viên cơ bản</h5>
              </span>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label class="control-label">ID nhân viên</label>
              <input
                class="form-control"
                type="text"
                required
                :value="'#CD218' + setEditAccount.id"
                disabled
              />
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Họ và tên</label>
              <input
                class="form-control"
                type="text"
                required
                v-model="setEditAccount.name"
              />
              <div v-if="validatedErrors.name" class="text-red-500">
                {{ validatedErrors.name }}
              </div>
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Số điện thoại</label>
              <input
                class="form-control"
                type="text"
                required
                v-model="setEditAccount.number_phone"
              />
              <div v-if="validatedErrors.number_phone" class="text-red-500">
                {{ validatedErrors.number_phone }}
              </div>
            </div>
            <div class="form-group col-md-6">
              <label class="control-label">Địa chỉ email</label>
              <input
                class="form-control"
                type="text"
                required
                v-model="setEditAccount.email"
                disabled
              />
            </div>
            <div class="form-group col-md-6">
              <label for="exampleSelect1" class="control-label">Chức vụ</label>
              <select
                v-model="setEditAccount.roles"
                class="form-control"
                id="exampleSelect1"
              >
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.name }}
                </option>
                <!-- <option :value="2">NV Đăng bài</option>
                <option :value="3">NV Bán hàng</option> -->
              </select>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Ảnh sản phẩm</label>
              <div id="myfileupload">
                <input
                  type="file"
                  ref="filelInput"
                  @change="onChangeImage"
                  accept="image/*"
                  id="uploadfile"
                  name="ImageUpload"
                  style="display: none"
                />
              </div>
              <div id="thumbbox" v-if="avatarPreview">
                <img
                  :src="avatarPreview"
                  height="auto"
                  width="10%"
                  alt="Thumb image"
                  id="thumbimage"
                />
                <a class="removeimg" @click.prevent="removeImage"> X </a>
              </div>
              <div id="boxchoice">
                <a
                  href="javascript:"
                  class="Choicefile"
                  @click="$refs.filelInput.click()"
                  ><i class="fas fa-cloud-upload-alt"></i> Chọn ảnh</a
                >
                <p style="clear: both"></p>
              </div>
            </div>
          </div>
          <a href="#" style="float: right; font-weight: 600; color: #ea0000"
            >Chỉnh sửa nâng cao</a
          >

          <button
            class="btn btn-save"
            type="button"
            @click.prevent="sendToSeverEditAccount(setEditAccount.id)"
          >
            Lưu lại
          </button>
          <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!--
  MODAL
--></template>
<script>
import adminApi from "@/api/admin";
import eventBus from "@/utils/eventBus";

export default {
  props: {
    listCategory: Array,
    listBrand: Array
  },
  data() {
    return {
      account: "",
      message: "",
      setEditAccount: "",
      avatarPreview: null,
      avatarFile: null,
      validatedErrors: {},
      roles: [],
      errorMessage: "",
    };
  },
  mounted() {
    this.fetchDataRoles();
    this.fetchAccountAuth();
  },
  methods: {
    async fetchDataRoles() {
      try {
        const response = await adminApi.get("account-auth/roles");
        this.roles = response.data.roles;
      } catch (error) {
        this.errorMessage = "Không thể lấy danh sách vai trò";
        
      }
    },
    async fetchAccountAuth() {
      try {
        eventBus.emit('show-loading');
        const response = await adminApi.get("account-auth/list");
        this.account = response.data.accounts.data;
      } catch (error) {
        if (error.response) {
          this.message = error.response.data;
        }
      }finally{
        eventBus.emit('hide-loading');
      }
    },
    handleEditAccount(account) {
      this.setEditAccount = {
        ...account,
        roles: account.roles.length ? account.roles[0].id : null,
      };
      this.avatarFile = account.image;
      this.avatarPreview = `http://192.168.2.2:8080/storage/account-avatar/${this.avatarFile}`;
    },

    async sendToSeverEditAccount(accountID) {
      this.validatedErrors = {};
      if (!this.setEditAccount.name) {
        this.validatedErrors.name = "Nhập tên người dùng";
      }
      if (!this.setEditAccount.number_phone) {
        this.validatedErrors.number_phone = "Nhập số điện thoại";
      } else if (!/^0\d{9}$/.test(this.setEditAccount.number_phone)) {
        this.validatedErrors.number_phone =
          "Số điện thoại không hợp lệ (phải bắt đầu bằng 0 và đủ 10 số)";
      }
      if (Object.keys(this.validatedErrors).length > 0) {
        return;
      }

      try {
        eventBus.emit("show-loading");
        const formData = new FormData();
        for (const key in this.setEditAccount) {
          if (key !== "image") {
            formData.append(key, this.setEditAccount[key]);
          }
        }
        if (this.avatarFile instanceof File) {
          formData.append("avatar", this.avatarFile);
        }

        const response = await adminApi.post(
          `account-auth/edit/${accountID}`,
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        );

        alert(response.data.message);
        this.fetchAccountAuth();
        } catch (error) {
        console.log(error);
      } finally {
        eventBus.emit("hide-loading");
      }
    },

    onChangeImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.avatarFile = file;
        this.avatarPreview = URL.createObjectURL(file);
      }
    },
  },
};
</script>
