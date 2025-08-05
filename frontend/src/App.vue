<template>
  <main>
    <div v-if="isLoading" class="loading-overlay">
      <div class="spinner"></div>
      <p>Đang xử lý, vui lòng chờ...</p>
    </div>
    <router-view></router-view>
  </main>
</template>
<script>
import eventBus from "./utils/eventBus";

export default {
  name: "App",
  data() {
    return {
      isLoading: false,
    };
  },
  created() {
    eventBus.on('show-loading', () => {
      this.isLoading = true;
    });
    eventBus.on('hide-loading', () => {
      this.isLoading = false;
    });
  },
  
};
</script>
<style>
.loading-overlay {
  position: fixed;
  z-index: 9999;
  background-color: rgba(255, 255, 255, 0.7);
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.spinner {
  border: 5px solid #f3f3f3;
  border-top: 5px solid #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;
  margin-bottom: 10px;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
