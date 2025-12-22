import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';

// Setup axios defaults
axios.defaults.headers.common['Accept'] = 'application/json';

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router).mount('#app');