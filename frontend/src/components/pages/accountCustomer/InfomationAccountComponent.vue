<template>
  <div class="profile-layout container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="profile-avatar">
        <div class="avatar-container">
          <img
            v-if="user.avatar"
            :src="user.avatar"
            :alt="user.name"
            class="avatar"
          />
          <div v-else class="avatar-placeholder">
            <i class="fas fa-user"></i>
          </div>
          <button class="avatar-upload" @click="uploadAvatar" v-if="isEditing">
            <i class="fas fa-camera"></i>
          </button>
        </div>
        <div class="profile-name">{{ user.name }}</div>
        <div class="profile-email">{{ user.email }}</div>
      </div>

      <div class="profile-stats">
        <div class="stat-item">
          <span class="stat-number">{{ user.totalOrders }}</span>
          <span class="stat-label">Đơn hàng</span>
        </div>
        <div class="stat-item">
          <span class="stat-number">{{ user.totalSpent }}đ</span>
          <span class="stat-label">Tổng chi tiêu</span>
        </div>
      </div>

      <ul class="menu-list">
        <li class="menu-item">
          <a
            href="#"
            class="menu-link"
            :class="{ active: activeTab === 'profile' }"
            @click.prevent="activeTab = 'profile'"
          >
            <i class="fas fa-user"></i>
            Thông tin cá nhân
          </a>
        </li>
        <li class="menu-item">
          <a
            href="#"
            class="menu-link"
            :class="{ active: activeTab === 'orders' }"
            @click.prevent="activeTab = 'orders'"
          >
            <i class="fas fa-shopping-bag"></i>
            Đơn hàng của tôi
          </a>
        </li>
        <li class="menu-item">
          <a
            href="#"
            class="menu-link"
            :class="{ active: activeTab === 'addresses' }"
            @click.prevent="activeTab = 'addresses'"
          >
            <i class="fas fa-map-marker-alt"></i>
            Địa chỉ giao hàng
          </a>
        </li>
        <li class="menu-item">
          <a
            href="#"
            class="menu-link"
            :class="{ active: activeTab === 'security' }"
            @click.prevent="activeTab = 'security'"
          >
            <i class="fas fa-shield-alt"></i>
            Bảo mật
          </a>
        </li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Alert Messages -->
      <div v-if="alert.show" class="alert" :class="alert.type">
        <i
          class="fas"
          :class="
            alert.type === 'alert-success'
              ? 'fa-check-circle'
              : 'fa-exclamation-circle'
          "
        ></i>
        {{ alert.message }}
      </div>

      <!-- Profile Tab -->
      <div v-if="activeTab === 'profile'">
        <div class="content-header">
          <h2 class="content-title">Thông tin cá nhân</h2>
          <button
            class="edit-btn"
            @click="toggleEdit"
            :class="{ cancel: isEditing }"
          >
            <i class="fas" :class="isEditing ? 'fa-times' : 'fa-edit'"></i>
            {{ isEditing ? "Hủy" : "Chỉnh sửa" }}
          </button>
        </div>

        <div class="info-form">
          <div class="form-group">
            <label class="form-label">Họ và tên</label>
            <input
              type="text"
              class="form-input"
              v-model="formData.name"
              :readonly="!isEditing"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Email</label>
            <input
              type="email"
              class="form-input"
              v-model="formData.email"
              :readonly="!isEditing"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Số điện thoại</label>
            <input
              type="tel"
              class="form-input"
              v-model="user.number_phone"
              :readonly="!isEditing"
            />
          </div>

          <div class="form-group">
            <label class="form-label">Ngày sinh</label>
            <input
              type="date"
              class="form-input"
              v-model="formData.birthday"
              :readonly="!isEditing"
            />
          </div>
          <div class="form-group">
            <label class="form-label">Nghề nghiệp</label>
            <input
              type="text"
              class="form-input"
              v-model="formData.occupation"
              :readonly="!isEditing"
            />
          </div>

          <div class="form-group full-width">
            <label class="form-label">Địa chỉ</label>
            <textarea
              class="form-textarea"
              v-model="formData.address"
              :readonly="!isEditing"
              placeholder="Nhập địa chỉ đầy đủ"
            ></textarea>
          </div>
        </div>

        <div v-if="isEditing" class="action-buttons">
          <button
            class="btn btn-primary"
            @click="saveProfile"
            :disabled="isSaving"
          >
            <div v-if="isSaving" class="loading"></div>
            <i v-else class="fas fa-save"></i>
            {{ isSaving ? "Đang lưu..." : "Lưu thay đổi" }}
          </button>
          <button class="btn btn-secondary" @click="cancelEdit">
            <i class="fas fa-times"></i>
            Hủy bỏ
          </button>
        </div>

        <!-- Password Change Section -->
        <div class="password-section">
          <h3><i class="fas fa-lock"></i> Đổi mật khẩu</h3>
          <div class="info-form">
            <div class="form-group">
              <label class="form-label">Mật khẩu hiện tại</label>
              <input
                type="password"
                class="form-input"
                v-model="passwordData.currentPassword"
                placeholder="Nhập mật khẩu hiện tại"
              />
            </div>
            <div class="form-group">
              <label class="form-label">Mật khẩu mới</label>
              <input
                type="password"
                class="form-input"
                v-model="passwordData.newPassword"
                placeholder="Nhập mật khẩu mới"
              />
            </div>
            <div class="form-group full-width">
              <label class="form-label">Xác nhận mật khẩu mới</label>
              <input
                type="password"
                class="form-input"
                v-model="passwordData.confirmPassword"
                placeholder="Nhập lại mật khẩu mới"
              />
            </div>
          </div>
          <div class="action-buttons">
            <button
              class="btn btn-primary"
              @click="changePassword"
              :disabled="isChangingPassword"
            >
              <div v-if="isChangingPassword" class="loading"></div>
              <i v-else class="fas fa-key"></i>
              {{ isChangingPassword ? "Đang đổi..." : "Đổi mật khẩu" }}
            </button>
          </div>
        </div>
      </div>

      <!-- Orders Tab -->
      <div v-if="activeTab === 'orders'">
        <div class="content-header">
          <h2 class="content-title">Đơn hàng của tôi</h2>
        </div>
        <div style="text-align: center; padding: 50px; color: #666">
          <i
            class="fas fa-shopping-bag"
            style="font-size: 64px; margin-bottom: 20px"
          ></i>
          <h3>Chức năng đang phát triển</h3>
          <p>Danh sách đơn hàng sẽ được hiển thị tại đây</p>
        </div>
      </div>

      <!-- Addresses Tab -->
      <div v-if="activeTab === 'addresses'">
        <div class="content-header">
          <h2 class="content-title">Địa chỉ giao hàng</h2>
        </div>
        <div style="text-align: center; padding: 50px; color: #666">
          <i
            class="fas fa-map-marker-alt"
            style="font-size: 64px; margin-bottom: 20px"
          ></i>
          <h3>Chức năng đang phát triển</h3>
          <p>Quản lý địa chỉ giao hàng sẽ được hiển thị tại đây</p>
        </div>
      </div>

      <!-- Security Tab -->
      <div v-if="activeTab === 'security'">
        <div class="content-header">
          <h2 class="content-title">Bảo mật tài khoản</h2>
        </div>
        <div style="text-align: center; padding: 50px; color: #666">
          <i
            class="fas fa-shield-alt"
            style="font-size: 64px; margin-bottom: 20px"
          ></i>
          <h3>Chức năng đang phát triển</h3>
          <p>Cài đặt bảo mật sẽ được hiển thị tại đây</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      activeTab: "profile",
      isEditing: false,
      isSaving: false,
      isChangingPassword: false,
      alert: {
        show: false,
        type: "alert-success",
        message: "",
      },
      user: localStorage.getItem("customer"),
      formData: {},
      originalData: {},
      passwordData: {
        currentPassword: "",
        newPassword: "",
        confirmPassword: "",
      },
    };
  },
  created() {
    this.loadUserData();
    this.resetFormData();
  },
  methods: {
    loadUserData() {
      const savedUser = localStorage.getItem("customer");
      if (savedUser) {
        try {
          const userData = JSON.parse(savedUser);
          this.user = { ...this.user, ...userData };
        } catch (error) {
          console.error("Error loading user data:", error);
        }
      }
    },
    resetFormData() {
      this.formData = { ...this.user };
      this.originalData = { ...this.user };
    },
    toggleEdit() {
      if (this.isEditing) {
        this.cancelEdit();
      } else {
        this.isEditing = true;
        this.originalData = { ...this.formData };
      }
    },
    cancelEdit() {
      this.isEditing = false;
      this.formData = { ...this.originalData };
    },
    async saveProfile() {
      this.isSaving = true;

      try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Validation
        if (!this.formData.name.trim()) {
          throw new Error("Họ tên không được để trống");
        }

        if (!this.formData.email.trim()) {
          throw new Error("Email không được để trống");
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.formData.email)) {
          throw new Error("Email không hợp lệ");
        }

        // Phone validation
        if (
          this.formData.phone &&
          !/^[0-9]{10,11}$/.test(this.formData.phone.replace(/\s/g, ""))
        ) {
          throw new Error("Số điện thoại không hợp lệ");
        }

        // Update user data
        this.user = { ...this.formData };

        // Save to localStorage
        localStorage.setItem("customer", JSON.stringify(this.user));

        this.isEditing = false;
        this.showAlert("success", "Cập nhật thông tin thành công!");
      } catch (error) {
        this.showAlert("error", error.message);
      } finally {
        this.isSaving = false;
      }
    },
    async changePassword() {
      if (!this.passwordData.currentPassword) {
        this.showAlert("error", "Vui lòng nhập mật khẩu hiện tại");
        return;
      }

      if (!this.passwordData.newPassword) {
        this.showAlert("error", "Vui lòng nhập mật khẩu mới");
        return;
      }

      if (this.passwordData.newPassword.length < 6) {
        this.showAlert("error", "Mật khẩu mới phải có ít nhất 6 ký tự");
        return;
      }

      if (this.passwordData.newPassword !== this.passwordData.confirmPassword) {
        this.showAlert("error", "Mật khẩu xác nhận không khớp");
        return;
      }

      this.isChangingPassword = true;

      try {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Reset password form
        this.passwordData = {
          currentPassword: "",
          newPassword: "",
          confirmPassword: "",
        };

        this.showAlert("success", "Đổi mật khẩu thành công!");
      } catch (error) {
        this.showAlert("error", "Có lỗi xảy ra khi đổi mật khẩu");
      } finally {
        this.isChangingPassword = false;
      }
    },
    uploadAvatar() {
      // Create file input
      const input = document.createElement("input");
      input.type = "file";
      input.accept = "image/*";
    },
  },
};
</script>
<style>
/* Main Layout */
.profile-layout {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 30px;
  margin: 20px;
}

/* Sidebar */
.sidebar {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 30px;
  height: fit-content;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.profile-avatar {
  text-align: center;
  margin-bottom: 30px;
}

.avatar-container {
  position: relative;
  display: inline-block;
  margin-bottom: 20px;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #fff;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea, #764ba2);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 48px;
  border: 4px solid #fff;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.avatar-upload {
  position: absolute;
  bottom: 0;
  right: 0;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  cursor: pointer;
  font-size: 14px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.avatar-upload:hover {
  background: #0056b3;
  transform: scale(1.1);
}

.profile-name {
  font-size: 24px;
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.profile-email {
  color: #666;
  font-size: 16px;
  margin-bottom: 20px;
}

.profile-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 30px;
}

.stat-item {
  text-align: center;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 10px;
}

.stat-number {
  font-size: 24px;
  font-weight: bold;
  color: #007bff;
  display: block;
}

.stat-label {
  font-size: 12px;
  color: #666;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.menu-list {
  list-style: none;
}

.menu-item {
  margin-bottom: 5px;
}

.menu-link {
  display: flex;
  align-items: center;
  padding: 15px;
  color: #666;
  text-decoration: none;
  border-radius: 10px;
  transition: all 0.3s ease;
  font-weight: 500;
}

.menu-link:hover,
.menu-link.active {
  background: #007bff;
  color: white;
  transform: translateX(5px);
}

.menu-link i {
  margin-right: 15px;
  width: 20px;
}

/* Main Content */
.main-content {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 30px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.content-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #f1f3f4;
}

.content-title {
  font-size: 24px;
  font-weight: 600;
  color: #333;
}

.edit-btn {
  background: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s ease;
}

.edit-btn:hover {
  background: #1e7e34;
  transform: translateY(-2px);
}

.edit-btn.cancel {
  background: #dc3545;
}

.edit-btn.cancel:hover {
  background: #bd2130;
}

/* Form Styles */
.info-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
}

.form-group {
  margin-bottom: 25px;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

.form-label {
  display: block;
  margin-bottom: 8px;
  color: #555;
  font-weight: 600;
  font-size: 14px;
}

.form-input,
.form-select,
.form-textarea {
  width: 100%;
  padding: 15px;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 16px;
  transition: all 0.3s ease;
  background: white;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.form-input:read-only {
  background: #f8f9fa;
  cursor: not-allowed;
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 15px;
  margin-top: 30px;
  padding-top: 30px;
  border-top: 2px solid #f1f3f4;
}

.btn {
  padding: 12px 25px;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background: #007bff;
  color: white;
}

.btn-primary:hover {
  background: #0056b3;
  transform: translateY(-2px);
}

.btn-secondary {
  background: #6c757d;
  color: white;
}

.btn-secondary:hover {
  background: #5a6268;
  transform: translateY(-2px);
}

.btn-danger {
  background: #dc3545;
  color: white;
}

.btn-danger:hover {
  background: #bd2130;
  transform: translateY(-2px);
}

/* Password Change Section */
.password-section {
  background: #f8f9fa;
  border-radius: 10px;
  padding: 25px;
  margin-top: 30px;
}

.password-section h3 {
  color: #333;
  margin-bottom: 20px;
  font-size: 18px;
}

/* Alerts */
.alert {
  padding: 15px 20px;
  border-radius: 8px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.alert-success {
  background: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.alert-error {
  background: #f8d7da;
  color: #721c24;
  border: 1px solid #f5c6cb;
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-layout {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .sidebar {
    order: 2;
  }

  .main-content {
    order: 1;
  }

  .info-form {
    grid-template-columns: 1fr;
  }

  .content-header {
    flex-direction: column;
    gap: 15px;
    align-items: flex-start;
  }

  .action-buttons {
    flex-direction: column;
  }
}

/* Loading Animation */
.loading {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
