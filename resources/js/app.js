import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './App.vue';
import router from './router';
import axios from 'axios';

// Toast kütüphanesi
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

// Lazy Loading
import LazyLoad from './utils/lazyLoad';

// CSS import
import '../css/app.css';

// Debug modu
const isDebug = true;
if (isDebug) {
  console.log('Debug modu aktif');
  window.onerror = function(message, source, lineno, colno, error) {
    console.error('Hata yakalandı:', { message, source, lineno, colno, error });
    return false;
  };
}

// Axios ayarları
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.withCredentials = true;

// CSRF token ayarı
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
const app = createApp(App);

// Vue hata ayıklama
app.config.errorHandler = (err, vm, info) => {
  console.error('Vue hata:', err);
  console.error('Hatanın gerçekleştiği bileşen:', vm);
  console.error('Hata detayı:', info);
};

// Router hook
router.beforeEach((to, from, next) => {
  console.log(`Sayfaya gidiliyor: ${to.path}`);
  next();
});

app.use(router);
app.use(pinia);
app.use(Toast, {
  transition: "Vue-Toastification__bounce",
  maxToasts: 3,
  newestOnTop: true,
  position: "top-right",
  timeout: 3000,
  closeOnClick: true,
  pauseOnHover: true
});

// Lazy Loading direktifini kaydet
app.use(LazyLoad);

app.mount('#app');
