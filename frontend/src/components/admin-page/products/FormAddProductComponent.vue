<template>
  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item">
        <a href="products.html"> Danh sách sản phẩm </a>
      </li>
      <li class="breadcrumb-item"><a href="">Thêm sản phẩm</a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Tạo mới sản phẩm</h3>
        <div class="tile-body">
          <div class="row element-button">
            <div class="col-sm-2">
              <a
                class="btn btn-add btn-sm"
                data-toggle="modal"
                data-target="#exampleModalCenter"
                ><i class="fas fa-folder-plus"></i> Thêm nhà cung cấp</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-add btn-sm"
                data-toggle="modal"
                data-target="#adddanhmuc"
                ><i class="fas fa-folder-plus"></i> Thêm danh mục</a
              >
            </div>
            <div class="col-sm-2">
              <a
                class="btn btn-add btn-sm"
                data-toggle="modal"
                data-target="#addthuonghieu"
                ><i class="fas fa-folder-plus"></i> Thêm thương hiệu</a
              >
            </div>
          </div>
          <form class="row">
            <div class="form-group col-md-3">
              <label class="control-label">Tên sản phẩm</label>
              <input class="form-control" type="text" v-model="form.name" />
              <div v-if="validationError.name" class="text-red-500">
                {{ validationError.name[0] }}
              </div>
            </div>

            <div class="form-group col-md-3">
              <label class="control-label">Số lượng</label>
              <input
                class="form-control"
                type="number"
                v-model="form.quantity"
              />
              <div v-if="validationError.quantity" class="text-red-500">
                {{ validationError.quantity[0] }}
              </div>
            </div>
            <div class="form-group col-md-3">
              <label for="exampleSelect1" class="control-label"
                >Trạng thái</label
              >
              <select
                class="form-control"
                id="exampleSelect1"
                v-model="form.status"
              >
                <div v-if="validationError.status" class="text-red-500">
                  {{ validationError.status[0] }}
                </div>
                <option>-- Chọn Trạng thái --</option>
                <option value="0">Còn hàng</option>
                <option value="1">Hết hàng</option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="exampleSelect1" class="control-label">Danh mục</label>
              <select
                class="form-control"
                id="exampleSelect1"
                v-model="form.category"
              >
                <option
                  v-for="categories in listCategory"
                  :key="categories.cate_id"
                  :value="categories.cate_id"
                >
                  {{ categories.cate_name }}
                </option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label for="exampleSelect1" class="control-label"
                >Thương hiệu</label
              >
              <select
                class="form-control"
                id="exampleSelect1"
                v-model="form.brand"
              >
                <option
                  v-for="brands in listBrand"
                  :key="brands.brand_id"
                  :value="brands.brand_id"
                >
                  {{ brands.brand_name }}
                </option>
              </select>
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">Giá bán khuyến mãi</label>
              <input class="form-control" type="text" v-model="form.price" />
              <div v-if="validationError.price" class="text-red-500">
                {{ validationError.price[0] }}
              </div>
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">Giá bán bình thường</label>
              <input
                class="form-control"
                type="text"
                v-model="form.promotion"
              />
            </div>
            <div class="form-group col-md-3">
              <label class="control-label">Sản phẩm nổi bật</label>
              <select class="form-control" v-model="form.featured">
                <option value="0">Có</option>
                <option value="1">Không</option>
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
              <div id="thumbbox" v-if="imagePreview">
                <img
                  :src="imagePreview"
                  height="auto"
                  width="10%"
                  alt="Thumb image"
                  id="thumbimage"
                />
                <a class="removeimg" @click.prevent="removeImage"> X </a>
              </div>
              <div v-if="validationError.imageAvatar" class="text-red-500">
                {{ validationError.imageAvatar[0] }}
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
            <div class="form-group col-md-12">
              <label class="control-label">Thư viện ảnh sản phẩm</label>
              <div id="myfileupload">
                <input
                  type="file"
                  ref="filelGalleryInput"
                  @change="onChangeGalleryImage"
                  accept="image/*"
                  multiple
                  id="uploadfilegallery"
                  name="ImageUpload"
                  style="display: none"
                />
              </div>
              <div
                id="thumbbox"
                v-if="imageGalleryPreview.length"
                style="display: flex"
              >
                <div
                  style="width: 10%; margin-right: 20px"
                  v-for="(imgSrc, index) in imageGalleryPreview"
                  :key="index"
                >
                  <img
                    :src="imgSrc"
                    height="100%"
                    width="100%"
                    alt="Thumb image"
                    id="thumbimage"
                  />
                  <a
                    class="removeimg"
                    @click.prevent="removeImageGallery(index)"
                  >
                    X
                  </a>
                </div>
              </div>
              <div v-if="galleryErrors.length" class="text-red-500">
                <div v-for="(error, index) in galleryErrors" :key="index">
                  {{ error }}
                </div>
              </div>
              <div id="boxchoice">
                <a
                  href="javascript:"
                  class="Choicefile"
                  @click="$refs.filelGalleryInput.click()"
                  ><i class="fas fa-cloud-upload-alt"></i> Chọn ảnh</a
                >
                <p style="clear: both"></p>
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label"
                >Tóm tắt chức năng chính sản phẩm</label
              >
              <Ckeditor :editor="editor" v-model="form.summary" :config="editorConfig"></Ckeditor>
              <!-- <textarea
                class="form-control"
                name="summary"
                id="summary"
                v-model="form.summary"
              ></textarea> -->

            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Mô tả sản phẩm</label>
              <!-- <textarea
                class="form-control"
                name="mota"
                id="mota"
                v-model="form.description"
              ></textarea> -->
              <Ckeditor
                :editor="editor"
                v-model="form.description"
                :config="editorConfig"
              ></Ckeditor>
              
            </div>
          </form>
        </div>
        <button class="btn btn-save" type="button" @click="actionAddProduct">
          Lưu lại
        </button>
        <a class="btn btn-cancel" href="/admin/products.html">Hủy bỏ</a>
      </div>
    </div>
  </div>

  <!--
  MODAL CHỨC VỤ 
-->
  <div
    class="modal fade"
    id="exampleModalCenter"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    data-backdrop="static"
    data-keyboard="false"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <span class="thong-tin-thanh-toan">
                <h5>Thêm mới nhà cung cấp</h5>
              </span>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Nhập tên nhà cung cấp mới</label>
              <input class="form-control" type="text" required />
            </div>
          </div>
        </div>
        <div>
          <button class="btn btn-save" type="button">Lưu lại</button>
          <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
        </div>
      </div>
      <!-- <div class="modal-footer"></div> -->
    </div>
  </div>
  <!--
MODAL
-->

  <!--
  MODAL DANH MỤC
-->
  <div
    class="modal fade"
    id="adddanhmuc"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    data-backdrop="static"
    data-keyboard="false"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <span class="thong-tin-thanh-toan">
                <h5>Thêm mới danh mục</h5>
              </span>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Nhập tên danh mục mới</label>
              <input
                v-model="inputNameCategory"
                class="form-control"
                type="text"
                required
              />
              <div v-if="validationError.name" class="text-red-500">
                {{ validationError.name[0] }}
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label"
                >Danh mục sản phẩm hiện đang có</label
              >
              <ul
                v-for="categories in listCategory"
                :key="categories.cate_id"
                style="padding-left: 20px"
              >
                <li>
                  {{ categories.cate_name }}
                  <button
                    @click="deleteCategory(categories.cate_id)"
                    class="btn btn-sm btn-danger ml-2"
                    style="margin-left: 10px"
                  >
                  <i class="fas fa-trash-alt"></i>
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <button
            @click.prevent="actionAddCategory()"
            class="btn btn-save"
            type="button"
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
-->
  <!--
  MODAL TÌNH TRẠNG
-->
  <div
    class="modal fade"
    id="addthuonghieu"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalCenterTitle"
    data-backdrop="static"
    data-keyboard="false"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <span class="thong-tin-thanh-toan">
                <h5>Thêm mới thương hiệu</h5>
              </span>
            </div>
            <div class="form-group col-md-12">
              <label class="control-label">Nhập thương hiệu mới</label>
              <input class="form-control" type="text" v-model="inputNameBrand" required />
            </div>
            <div class="form-group col-md-12">
              <label class="control-label"
                >Thương hiệu sản phẩm hiện đang có</label
              >
              <ul
                v-for="brands in listBrand"
                :key="brands.brand_id"
                style="padding-left: 20px"
              >
                <li>
                  {{ brands.brand_name }}
                  <button
                    @click="deleteBrand(brands.brand_id)"
                    class="btn btn-sm btn-danger ml-2"
                    style="margin-left: 10px"
                  >
                  <i class="fas fa-trash-alt"></i>
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <div>
            <button class="btn btn-save" type="button" @click.prevent="actionAddBrand()">Lưu lại</button>
            <a class="btn btn-cancel" data-dismiss="modal" href="#">Hủy bỏ</a>
          </div>
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
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import { Ckeditor } from "@ckeditor/ckeditor5-vue";

export default {
  components: {
    Ckeditor
  },
  props: {
    listCategory: {
      type: Array,
      default: () => [],
    },
    listBrand: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      editor: ClassicEditor,
      editorConfig: {
        licenseKey: 'GPL',
          toolbar: [
            'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote'
          ]
        },
      validationError: "",
      imagePreview: null,
      imageGalleryPreview: [],
      imageFile: null,
      imageGalleryFile: [],
      inputNameCategory: "",
      inputNameBrand: "",
      message: "",
      form: {
        name: "",
        quantity: "",
        status: "",
        category: "",
        brand: "",
        price: "",
        promotion: "",
        featured: "",
        description: "",
        summary: "",
      },
    };
  },
  computed: {
    galleryErrors(){
      return Object.keys(this.validationError)
      .filter(key => key.startsWith('gallery'))
      .map(key => this.validationError[key][0]);
    }
  },
  methods: {
    async actionAddCategory() {
      try {
        const response = await adminApi.post("category/add", {
          name: this.inputNameCategory,
        });
        this.message = response.data.message;
        alert(this.message);
        window.location.href = "/admin/form-add-products.html";
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationError = error.response.data.error;
        }
      }
    },
    async deleteCategory(cateID) {
      if (confirm("Bạn có chắc chắn muốn xoá ?")) {
        try {
          eventBus.emit("show-loading");
          const response = await adminApi.get(`category/delete/${cateID}`);
          this.message = response.data.message;
          alert(this.message);
          window.location.href = "/admin/form-add-products.html";
        } catch (error) {
          console.log("Error", error);
        } finally {
          eventBus.emit("hide-loading");
        }
      }
    },
    async actionAddBrand(){
      try{
        eventBus.emit('show-loading');
        const response = await adminApi.post('brand/add', {
          name: this.inputNameBrand
        });
        this.message = response.data.message;
        alert(this.message);
        this.$router.go();
      }catch(error){
        alert('Vui lòng thử lại');
      }finally{
        eventBus.emit('hide-loading');
      }
    },
    async deleteBrand(brandID){
      if(confirm('Bạn có chắc chắn sẽ xoá không ?')){
        try{
          eventBus.emit('show-loading');
          const response = await adminApi.get(`brand/delete/${brandID}`);
          this.message = response.data.message;
          alert(this.message);
          this.$router.go();
        }catch(error){
          alert('Vui lòng thử lại sau');
        }finally{
          eventBus.emit('hide-loading');
        }
      }
    },
    onChangeImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.imageFile = file;
        this.imagePreview = URL.createObjectURL(file);
      }
    },
    removeImage() {
      this.imageFile = null;
      this.imagePreview = null;
      this.$refs.filelInput.value = null;
    },
    onChangeGalleryImage(event) {
      const files = Array.from(event.target.files);
      files.forEach((file) => {
        this.imageGalleryFile.push(file);
        this.imageGalleryPreview.push(URL.createObjectURL(file));
      });
    },
    removeImageGallery(index) {
      this.imageGalleryFile.splice(index, 1);
      this.imageGalleryPreview.splice(index, 1);
    },
    async actionAddProduct() {
      this.message = "";
      this.validationError = "";
      try {
        eventBus.emit("show-loading");
        const formData = new FormData();
        for (const key in this.form) {
          formData.append(key, this.form[key]);
        }
        if (this.imageFile) {
          formData.append("imageAvatar", this.imageFile);
        }
        if (this.imageGalleryFile && this.imageGalleryFile.length > 0) {
          this.imageGalleryFile.forEach((file) => {
            formData.append("gallery[]", file);
          });
        }
        const response = await adminApi.post("product/add", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });
        this.message = response.data.message;
        alert(this.message);
        this.$router.go();
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.validationError = error.response.data.error;
        } else if (error) {
          const xhr = error.response?.request;
          if (xhr) {
            console.error("📦 xhr status:", xhr.status);
            console.error("📦 xhr responseText:", xhr.responseText);
          }
        } else {
          alert("Có lỗi xảy ra vui lòng thử lại sau");
        }
      } finally {
        eventBus.emit("hide-loading");
      }
    },
  },
};
</script>
<style scoped>
.removeimg {
  cursor: pointer;
  color: red !important;
  font-size: 24px;
  margin-top: 5px;
  display: inline-block;
  position: absolute;
  top: 15px;
}
#boxchoice {
  margin-top: 10px;
}
</style>
