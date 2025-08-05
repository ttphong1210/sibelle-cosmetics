<template>
  <HeaderComponent />
  <main>
    <router-view />
  </main>
  <FooterComponent />
</template>
<script>
import eventBus from "@/utils/eventBus";
import FooterComponent from "../FooterComponent.vue";
import HeaderComponent from "../HeaderComponent.vue";
import { provide, reactive } from "vue";

export default {
  setup() {
    const state = reactive({
      isAuthenticated: !!localStorage.getItem("customer_token"),
    });
    eventBus.on("customer-login", () => {
      state.isAuthenticated = true;
    });

    eventBus.on("customer-logout", () => {
      state.isAuthenticated = false;
    });

    provide("state", state);
  },

  components: {
    HeaderComponent,
    FooterComponent,
  },
};
</script>
<style>
/* Import custom CSS file */
@import "@/assets/css/style.css";
@import "@/assets/css/bootstrap.css";
@import "@/assets/css/fonts/themify-icons.css";
</style>
