<template> //test
  <div id="app" class="overflow-x-hidden min-h-screen bg-white dark:bg-gray-900">
    <!-- Admin sayfalarında ana layout'u gösterme -->
    <template v-if="isAdminRoute">
      <router-view v-slot="{ Component }">
        <Suspense>
          <template #default>
            <div><!-- Tek bir kök node sağlar -->
              <component :is="Component" />
            </div>
          </template>
          <template #fallback>
            <div class="flex items-center justify-center min-h-screen">
              <div class="w-16 h-16 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
            </div>
          </template>
        </Suspense>
      </router-view>
    </template>
    
    <!-- Normal sayfalar için main layout kullan -->
    <template v-else>
      <MainLayout>
        <router-view v-slot="{ Component }">
          <Suspense>
            <template #default>
              <div><!-- Tek bir kök node sağlar -->
                <component :is="Component" />
              </div>
            </template>
            <template #fallback>
              <div class="flex items-center justify-center min-h-screen">
                <div class="w-16 h-16 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
              </div>
            </template>
          </Suspense>
        </router-view>
      </MainLayout>
    </template>
  </div>
</template>

<script setup>
import { onMounted, onBeforeMount, defineAsyncComponent, computed } from 'vue';
import MainLayout from '@/layouts/MainLayout.vue';
import { useAuthStore } from '@/store/auth';
import { useThemeStore } from '@/store/theme';
import authService from '@/services/auth';
import { watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import NavBar from './components/NavBar.vue';
import ThemeToggle from './components/ThemeToggle.vue';

// Auth store başlatma
const authStore = useAuthStore();
const themeStore = useThemeStore();
const route = useRoute();
const router = useRouter();

// Admin sayfası olup olmadığını kontrol et
const isAdminRoute = computed(() => {
  return route.path.startsWith('/admin');
});

// Global sayfa değişimi kontrolü - Kullanıcı bilgilerini güncelleme
router.beforeEach(async (to, from, next) => {
  console.log('Router: Sayfa değişimi algılandı:', to.path);
  
  // Kullanıcı giriş yapmışsa bilgilerini güncelle
  if (authStore.isLoggedIn) {
    try {
      console.log('Router: Kullanıcı verileri yenileniyor...');
      await authStore.refreshUserData();
      console.log('Router: Kullanıcı verileri güncellendi, rol:', authStore.userData?.role_id);
      
      // Admin sayfasına gidiyor ama admin değilse ana sayfaya yönlendir
      if (to.path.includes('/admin') && authStore.userData?.role_id === 1) {
        console.log('Router: Admin olmayan kullanıcı admin sayfasına erişmeye çalışıyor, ana sayfaya yönlendiriliyor');
        next('/');
        return;
      }
    } catch (error) {
      console.error('Router: Kullanıcı verileri güncellenirken hata:', error);
      
      // Hata durumunda kullanıcıyı çıkış yapmaya zorla
      if (error.response?.status === 401) {
        console.log('Router: 401 Unauthorized hatası, kullanıcı çıkış yapıyor');
        await authStore.logout();
        next('/login');
        return;
      }
    }
  }
  
  // Yetkilendirme gerektiren sayfalar
  if (to.meta.requiresAuth && !authStore.isLoggedIn) {
    console.log('Router: Yetki gerektiren sayfaya erişim engellendi');
    next('/login');
    return;
  }
  
  // Admin sayfaları kontrolü
  if (to.path.startsWith('/admin') && (!authStore.isLoggedIn || authStore.userData?.role_id === 1)) {
    console.log('Router: Admin sayfasına yetkisiz erişim engellendi');
    next('/');
    return;
  }
  
  next();
});

// Route değişikliklerini izle
watch(
  () => route.fullPath,
  () => {
    // Her route değişiminde auth durumunu kontrol et
    const authData = localStorage.getItem('auth');
    console.log(`Route değişti: ${route.fullPath}, Auth durumu: ${authData ? 'Var' : 'Yok'}`);
    
    // Login sayfasındayız ve auth yok, hiçbir şey yapmıyoruz
    if (route.name === 'login' && !authData) {
      console.log('Login sayfasında ve auth yok, store temizliği kontrol ediliyor');
      if (authStore.isLoggedIn) {
        // Store'da user var ama localStorage'da yoksa, store'u temizle
        authStore.logout();
      }
      return;
    }
    
    // Auth verisi var ama store boş - user verilerini yenile
    if (authData && !authStore.isLoggedIn) {
      console.log('Auth bilgisi var ama store boş, user verilerini yeniliyoruz');
      authStore.refreshUserData().catch(error => {
        console.error('User bilgileri alınamadı, çıkış yapılıyor:', error);
        authStore.logout();
      });
    } else if (!authData && authStore.isLoggedIn) {
      // Auth yok ama store dolu, store temizleniyor
      console.log('Auth bilgisi yok ama store dolu, store temizleniyor');
      authStore.logout();
    }
  }
);

// Görselleri ön yükleme
const preloadImages = () => {
  const imageUrls = [
    'https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=1200',
    'https://images.pexels.com/photos/3894868/pexels-photo-3894868.jpeg?auto=compress&cs=tinysrgb&w=1200',
    'https://images.pexels.com/photos/11421498/pexels-photo-11421498.jpeg?auto=compress&cs=tinysrgb&w=1200',
    '/images/payment/visa.png',
    '/images/payment/mastercard.png',
    '/images/payment/troy.png'
  ];
  
  imageUrls.forEach(url => {
    const img = new Image();
    img.src = url;
  });
};

onBeforeMount(() => {
  preloadImages();
  
  // Tutarsız oturum durumunu kontrol et
  const authData = localStorage.getItem('auth');
  
  // Auth bilgisi yoksa diğer kalıntıları temizle
  if (!authData) {
    console.log('Auth bilgisi yok, eski kullanıcı verilerini temizliyorum');
    localStorage.removeItem('user');
  }
  
  // Auth durumunu başlat
  authStore.initAuth();
  console.log('Auth durumu başlatıldı, auth durumu:', authData ? 'Var' : 'Yok');
});

onMounted(() => {
  // Theme store ile tema yönetimini başlat
  const cleanupThemeListener = themeStore.initTheme();
  
  // Önceki Service Worker'ı kaldır
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(registrations => {
      for (let registration of registrations) {
        registration.unregister();
      }
    });
  }
  
  // Kullanıcı giriş yapmışsa otomatik güncellemeyi başlat
  if (authStore.isLoggedIn) {
    console.log('App: Kullanıcı giriş yapmış, otomatik veri güncelleme başlatılıyor');
    authStore.startAutoRefresh();
  }
  
  console.log('App bileşeni yüklendi');
});
</script>

<style>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 100vw;
  overflow-x: hidden;
}

main {
  flex: 1;
  padding: 0;
  width: 100%;
  max-width: 100vw;
}

/* Tema stilleri */
:root {
  --bg-color: #ffffff;
  --text-color: #333333;
  --primary-color: #4a6cf7;
  --secondary-color: #f5f5f5;
  --border-color: #e2e8f0;
  --shadow-color: rgba(0, 0, 0, 0.1);
}

.dark-theme {
  --bg-color: #1e293b;
  --text-color: #f8fafc;
  --primary-color: #5d87ff;
  --secondary-color: #334155;
  --border-color: #475569;
  --shadow-color: rgba(0, 0, 0, 0.3);
}

body {
  background-color: var(--bg-color);
  color: var(--text-color);
  transition: background-color 0.3s ease, color 0.3s ease;
  margin: 0;
  padding: 0;
  width: 100%;
  max-width: 100vw;
  overflow-x: hidden;
}
</style>
