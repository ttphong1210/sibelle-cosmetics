import { createApp } from 'vue'
import App from './App.vue'


// Create the Vue application
const app = createApp(App);

// Import Bootstrap and Bootstrap-Vue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css';

// Import custom CSS file
// import './assets/css/style.css';
// import './assets/css/bootstrap.css';
// import './assets/css/fonts/themify-icons.css';
// import '@/assets/css/admin-css/main.css';
// Import BootstrapVueNext
import BootstrapVueNext from 'bootstrap-vue-next';

import router from './router/index.js';
import globalMixins from './mixins/globalMixins';
// Use BootstrapVueNext throughout your project
app.use(BootstrapVueNext);

app.mixin(globalMixins);
app.use(router);
// Mount the Vue application
app.mount('#app');
// createApp(App).mount('#app')
