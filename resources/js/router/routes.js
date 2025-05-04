import { createRouter, createWebHistory } from 'vue-router';

// Ana Sayfalar
import Home from '@/pages/Home.vue';
import About from '@/pages/About.vue';
import Tours from '@/pages/Tours.vue';
import TourDetail from '@/pages/TourDetail.vue';
import Contact from '@/pages/Contact.vue';
import Checkout from '@/pages/Checkout.vue';
import OrderConfirmation from '@/pages/OrderConfirmation.vue';

// Auth Sayfaları
import Login from '@/pages/Auth/Login.vue';
import Register from '@/pages/Auth/Register.vue';
import ForgotPassword from '@/pages/Auth/ForgotPassword.vue';
import ResetPassword from '@/pages/Auth/ResetPassword.vue';

// Profil Sayfaları
import UserProfile from '@/pages/Profile/UserProfile.vue';
import UserReservations from '@/pages/Profile/UserReservations.vue';
import UserSettings from '@/pages/Profile/UserSettings.vue';

// Admin Sayfaları
import AdminDashboard from '@/pages/Admin/Dashboard.vue';
import AdminTours from '@/pages/Admin/Tours.vue';
import AdminCategories from '@/pages/Admin/Categories.vue';
import AdminReservations from '@/pages/Admin/Reservations.vue';
import AdminUsers from '@/pages/Admin/Users.vue';
import AdminSettings from '@/pages/Admin/Settings.vue';
import AdminReports from '@/pages/Admin/Reports.vue';
import AdminAdvancedReports from '@/pages/Admin/AdvancedReports.vue';
import AdminAnalyticsReports from '@/pages/Admin/AnalyticsReports.vue';
import AdminContact from '@/pages/Admin/Contact.vue';

// Layout bileşenleri
import MainLayout from '@/layouts/MainLayout.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

// Routes
const routes = [
  // Ana Sayfalar
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { layout: MainLayout, title: 'Ana Sayfa' }
  },
  {
    path: '/hakkimizda',
    name: 'about',
    component: About,
    meta: { layout: MainLayout, title: 'Hakkımızda' }
  },
  {
    path: '/turlar',
    name: 'tours',
    component: Tours,
    meta: { layout: MainLayout, title: 'Turlar' }
  },
  {
    path: '/tours',
    redirect: '/turlar'
  },
  {
    path: '/turlar/:slug',
    name: 'tour-detail',
    component: TourDetail,
    meta: { layout: MainLayout, title: 'Tur Detayı' }
  },
  {
    path: '/iletisim',
    name: 'contact',
    component: Contact,
    meta: { layout: MainLayout, title: 'İletişim' }
  },
  // Sepet ve Ödeme Sayfaları
  {
    path: '/cart',
    name: 'cart',
    redirect: '/',
    meta: { title: 'Sepetim' }
  },
  {
    path: '/odeme',
    name: 'checkout',
    component: Checkout,
    meta: { layout: MainLayout, title: 'Ödeme' }
  },
  {
    path: '/onay',
    name: 'order-confirmation',
    component: OrderConfirmation,
    meta: { layout: MainLayout, title: 'Sipariş Onayı' }
  },
  
  // Auth Sayfaları
  {
    path: '/giris',
    name: 'login',
    component: Login,
    meta: { layout: MainLayout, title: 'Giriş Yap' }
  },
  {
    path: '/kayit',
    name: 'register',
    component: Register,
    meta: { layout: MainLayout, title: 'Kayıt Ol' }
  },
  {
    path: '/sifremi-unuttum',
    name: 'forgot-password',
    component: ForgotPassword,
    meta: { layout: MainLayout, title: 'Şifremi Unuttum' }
  },
  {
    path: '/sifre-sifirlama/:token',
    name: 'reset-password',
    component: ResetPassword,
    meta: { layout: MainLayout, title: 'Şifre Sıfırlama' }
  },
  
  // Profil Sayfaları
  {
    path: '/profil',
    name: 'user-profile',
    component: UserProfile,
    meta: { layout: MainLayout, title: 'Profilim', requiresAuth: true }
  },
  {
    path: '/profil/rezervasyonlarim',
    name: 'user-reservations',
    component: UserReservations,
    meta: { layout: MainLayout, title: 'Rezervasyonlarım', requiresAuth: true }
  },
  {
    path: '/profil/ayarlar',
    name: 'user-settings',
    component: UserSettings,
    meta: { layout: MainLayout, title: 'Ayarlar', requiresAuth: true }
  },
  
  // Admin Sayfaları
  {
    path: '/admin',
    name: 'admin-dashboard',
    component: AdminDashboard,
    meta: { layout: AdminLayout, title: 'Admin Panel', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/turlar',
    name: 'admin-tours',
    component: AdminTours,
    meta: { layout: AdminLayout, title: 'Tur Yönetimi', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/kategoriler',
    name: 'admin-categories',
    component: AdminCategories,
    meta: { layout: AdminLayout, title: 'Kategori Yönetimi', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/rezervasyonlar',
    name: 'admin-reservations',
    component: AdminReservations,
    meta: { layout: AdminLayout, title: 'Rezervasyon Yönetimi', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/kullanicilar',
    name: 'admin-users',
    component: AdminUsers,
    meta: { layout: AdminLayout, title: 'Kullanıcı Yönetimi', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/ayarlar',
    name: 'admin-settings',
    component: AdminSettings,
    meta: { layout: AdminLayout, title: 'Sistem Ayarları', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/raporlar',
    name: 'admin-analytics-reports',
    component: AdminAnalyticsReports,
    meta: { layout: AdminLayout, title: 'Analitik Raporlar', requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/admin/iletisim',
    name: 'admin-contact',
    component: AdminContact,
    meta: { layout: AdminLayout, title: 'İletişim Mesajları', requiresAuth: true, requiresAdmin: true }
  },
  
  // 404 Sayfası
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: () => import('@/pages/NotFound.vue'),
    meta: { layout: MainLayout, title: 'Sayfa Bulunamadı' }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    // Her sayfa değişiminde sayfanın başına scrol yap
    return { top: 0 };
  }
});

// Global navigation guard
router.beforeEach((to, from, next) => {
  // Sayfa başlığını ayarla
  document.title = to.meta.title ? `${to.meta.title} | Tur Sitesi` : 'Tur Sitesi';
  
  // Auth gerektiren sayfalar için kontrol
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('token');
    if (!token) {
      next({ name: 'login', query: { redirect: to.fullPath } });
      return;
    }
    
    // Admin yetkisi gerektiren sayfalar için ek kontrol
    if (to.meta.requiresAdmin) {
      const user = JSON.parse(localStorage.getItem('user') || '{}');
      if (user.role_id !== 0) { // 0 = admin
        next({ name: 'home' });
        return;
      }
    }
  }
  
  next();
});

export default router; 