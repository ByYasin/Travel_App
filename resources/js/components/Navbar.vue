<template>
  <nav 
    class="bg-white dark:bg-gray-900 fixed w-full z-30 top-0 start-0 border-b border-gray-200 dark:border-gray-700 shadow-sm transition-all duration-300"
    :class="{'shadow-md': scrolled}"
  >
    <!-- Overlay -->
    <div v-if="isMobileMenuOpen" 
         @click="closeMobileMenu"
         class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 z-20"></div>

    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <router-link to="/" class="flex items-center space-x-3 rtl:space-x-reverse group transition-all duration-300">
        <!-- Logo (örnek) - Siz kendi SVG logonuzu ekleyebilirsiniz -->
        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg text-white flex items-center justify-center overflow-hidden group-hover:shadow-md transition-all duration-300">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12,3L2,12h3v8h6v-6h2v6h6v-8h3L12,3z"></path>
          </svg>
        </div>
        <span class="self-center text-2xl font-semibold whitespace-nowrap text-gray-800 dark:text-white relative">
          TravelApp
          <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-500 group-hover:w-full transition-all duration-300"></span>
        </span>
      </router-link>
      <div class="flex md:order-2 space-x-3 md:space-x-4 rtl:space-x-reverse">
        <!-- Sepet Butonu -->
        <button 
          @click="toggleCartDrawer"
          class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 transform hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 rounded-full"
          aria-label="Sepet"
        >
          <div class="relative">
            <!-- Yeni modern sepet ikonu -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="9" cy="21" r="1"></circle>
              <circle cx="20" cy="21" r="1"></circle>
              <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            
            <!-- Sepet sayacı badge -->
            <span 
              v-if="cartStore.totalItems > 0" 
              class="absolute -top-2 -right-2 bg-primary-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center transform transition-all duration-300 animate-pulse"
            >
              {{ cartStore.totalItems }}
            </span>
          </div>
        </button>

        <!-- Tema Değiştirme Butonu -->
        <button 
          @click="toggleTheme" 
          class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-primary-400 dark:focus:ring-primary-500 hover:rotate-12"
        >
          <svg v-if="themeStore.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <!-- Giriş Yapmamış Kullanıcı için Butonlar -->
        <template v-if="!isLoggedIn">
          <router-link to="/login" 
            class="py-2 px-4 text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white focus:outline-none transition-all duration-300 flex items-center"
            @click="closeMobileMenu"
          >
            Giriş Yap
          </router-link>
          <router-link to="/register"
            class="py-2 px-4 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg border border-primary-600 hover:border-primary-700 focus:outline-none transition-all duration-300 flex items-center shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
            @click="closeMobileMenu"
          >
            Kayıt Ol
          </router-link>
        </template>

        <!-- Giriş Yapmış Kullanıcı için Profil Dropdown -->
        <div v-else class="relative user-menu-dropdown">
          <button 
            @click="toggleUserMenu"
            class="flex items-center space-x-2 focus:outline-none"
          >
            <div v-if="userAvatar" class="h-8 w-8 rounded-full overflow-hidden bg-primary-600">
              <img class="h-full w-full object-cover" :src="userAvatar" alt="User avatar">
            </div>
            <div v-else class="h-8 w-8 rounded-full flex items-center justify-center text-white overflow-hidden" :class="getAvatarColor">
              <span class="text-sm font-medium">{{ initials }}</span>
            </div>
            <span class="hidden md:inline-block text-sm font-medium text-gray-700 dark:text-gray-200">
              {{ userData?.name }}
            </span>
            <svg class="hidden md:inline-block h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <div 
            v-if="userMenuOpen" 
            class="absolute right-0 mt-2 w-48 py-2 bg-white dark:bg-gray-800 rounded-md shadow-xl z-10 border border-gray-200 dark:border-gray-700"
          >
            <!-- Admin Paneli Bağlantısı (Sadece admin rolü için) -->
            <router-link 
              v-if="isAdmin"
              to="/admin/"
              @click="userMenuOpen = false"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <svg class="w-5 h-5 mr-2 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              Admin Paneli
            </router-link>
            
            <router-link 
              to="/profile" 
              @click="userMenuOpen = false"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <svg class="w-5 h-5 mr-2 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span>Profilim</span>
            </router-link>
            
            <router-link 
              to="/profile/reservations" 
              @click="userMenuOpen = false"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <svg class="w-5 h-5 mr-2 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
              </svg>
              Rezervasyonlarım
            </router-link>
            <router-link 
              to="/profile/favorites" 
              @click="userMenuOpen = false"
              class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors"
            >
              <svg class="w-5 h-5 mr-2 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
              </svg>
              Favorilerim
            </router-link>
            <div class="border-t border-gray-200 dark:border-gray-600"></div>
            <button 
              @click="logout"
              class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Çıkış Yap
            </button>
          </div>
        </div>

        <button data-collapse-toggle="navbar-sticky" 
                type="button" 
                @click="toggleMobileMenu"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-600 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600 transition-all duration-300" 
                aria-controls="navbar-sticky" 
                :aria-expanded="isMobileMenuOpen">
          <span class="sr-only">Menüyü aç</span>
          <div class="w-6 flex justify-center items-center relative">
            <span class="block absolute h-0.5 w-6 bg-current transform transition duration-300 ease-in-out"
                  :class="{'rotate-45': isMobileMenuOpen, ' -translate-y-1.5': !isMobileMenuOpen }"></span>
            <span class="block absolute h-0.5 w-6 bg-current transform transition duration-300 ease-in-out"
                  :class="{'opacity-0': isMobileMenuOpen }"></span>
            <span class="block absolute h-0.5 w-6 bg-current transform transition duration-300 ease-in-out"
                  :class="{'-rotate-45': isMobileMenuOpen, ' translate-y-1.5': !isMobileMenuOpen}"></span>
          </div>
        </button>
      </div>
      <div class="items-center justify-between w-full md:flex md:w-auto md:order-1 relative z-30" 
           :class="[
             'transition-all duration-300 ease-in-out',
             isMobileMenuOpen ? 'block' : 'hidden md:block'
           ]" 
           id="navbar-sticky">
        <ul class="flex flex-col p-4 mt-4 font-medium border border-gray-200 rounded-lg bg-white/80 md:p-0 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700 md:flex-nowrap backdrop-blur-sm">
          <li class="relative group">
            <router-link to="/" 
                        @click="handleMenuClick"
                        class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 md:pt-3 md:pb-3 md:dark:hover:text-primary-400 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent md:border-0 transition-all duration-300">
              Ana Sayfa
              <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    :class="[$route.path === '/' ? 'scale-x-100' : '']"></span>
            </router-link>
          </li>
          <li class="relative group">
            <router-link to="/tours" 
                        @click="handleMenuClick"
                        class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 md:pt-3 md:pb-3 md:dark:hover:text-primary-400 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent md:border-0 transition-all duration-300">
              Turlar
              <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    :class="[$route.path === '/tours' ? 'scale-x-100' : '']"></span>
            </router-link>
          </li>
          <li class="relative group">
            <router-link to="/sss" 
                        @click="handleMenuClick"
                        class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 md:pt-3 md:pb-3 md:dark:hover:text-primary-400 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent md:border-0 transition-all duration-300">
              SSS
              <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    :class="[$route.path === '/sss' ? 'scale-x-100' : '']"></span>
            </router-link>
          </li>
          <li class="relative group">
            <router-link to="/about" 
                        @click="handleMenuClick"
                        class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 md:pt-3 md:pb-3 md:dark:hover:text-primary-400 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent md:border-0 transition-all duration-300">
              Hakkımızda
              <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    :class="[$route.path === '/about' ? 'scale-x-100' : '']"></span>
            </router-link>
          </li>
          <li class="relative group">
            <router-link to="/contact" 
                        @click="handleMenuClick"
                        class="block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 md:pt-3 md:pb-3 md:dark:hover:text-primary-400 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent md:border-0 transition-all duration-300">
              İletişim
              <span class="absolute inset-x-0 bottom-0 h-0.5 bg-primary-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    :class="[$route.path === '/contact' ? 'scale-x-100' : '']"></span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Sepet Drawer -->
  <CartDrawer :is-open="isCartDrawerOpen" @close="closeCartDrawer" />
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '@/store/auth';
import { useThemeStore } from '@/store/theme';
import { useCartStore } from '@/store/cart';
import CartDrawer from '@/components/CartDrawer.vue';

const authStore = useAuthStore();
const themeStore = useThemeStore();
const cartStore = useCartStore();
const route = useRoute();
const router = useRouter();
const isMobileMenuOpen = ref(false);
const isLoggedIn = computed(() => authStore.isLoggedIn);
const userData = computed(() => authStore.userData);
const scrolled = ref(false);
const userMenuOpen = ref(false);
const isCartDrawerOpen = ref(false); // Cart drawer durumu
// Kullanıcı profil resmini saklayan değişken
const userAvatar = ref(null);

// Kullanıcı avatar'ını ayarlayan fonksiyon
const setUserAvatar = () => {
  if (isLoggedIn.value && userData.value) {
    // Kullanıcı avatar yolu varsa onu kullan, yoksa default avatarı kullan
    if (userData.value.avatar) {
      userAvatar.value = userData.value.avatar;
    } else {
      // Default avatar yok, bu durumda baş harflerini gösteren div'e geçiyoruz
      userAvatar.value = null;
    }
  } else {
    userAvatar.value = null;
  }
};

// Scroll durumunu izleme
const handleScroll = () => {
  scrolled.value = window.scrollY > 20;
};

// Admin kontrolü için kullanıcı role_id'sini kontrol et
const isAdmin = computed(() => {
  // Rol ID'si 0 ise admin olarak kabul et
  return userData.value?.role_id === 0;
});

// Kullanıcı verilerini yenileme fonksiyonu
const refreshUserData = async () => {
  try {
    console.log('Kullanıcı verileri yenileniyor...');
    await authStore.refreshUserData();
    alert('Kullanıcı verileri başarıyla güncellendi!');
  } catch (error) {
    console.error('Kullanıcı verileri güncellenirken hata:', error);
    alert('Kullanıcı verileri güncellenirken bir hata oluştu.');
  }
};

// Route değişikliğini izle
watch(() => route.path, () => {
  closeMobileMenu();
  userMenuOpen.value = false;
});

// Auth durumu değiştiğinde avatar'ı güncelle
watch(() => isLoggedIn.value, () => {
  setUserAvatar();
});

// User verisi değiştiğinde avatar'ı güncelle
watch(() => userData.value, () => {
  setUserAvatar();
});

// ESC tuşu ile menüyü kapatma
const handleEscKey = (event) => {
  if (event.key === 'Escape') {
    closeMobileMenu();
    userMenuOpen.value = false;
  }
};

// Sayfa kaydırmasını engelleme/etkinleştirme
const toggleBodyScroll = (disable) => {
  if (disable) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
};

const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value;
  toggleBodyScroll(isMobileMenuOpen.value);
};

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false;
  toggleBodyScroll(false);
};

// Menü öğelerine tıklandığında menüyü kapatma
const handleMenuClick = () => {
  closeMobileMenu();
};

// Tema değiştirme
const toggleTheme = () => {
  themeStore.toggleTheme();
};

// Kullanıcı menüsü toggle
const toggleUserMenu = () => {
  userMenuOpen.value = !userMenuOpen.value;
};

// Çıkış yapma
const logout = async () => {
  try {
    console.log('Navbar: Çıkış işlemi başlatılıyor');
    await authStore.logout();
    console.log('Navbar: Çıkış başarılı');
    
    // Sayfa yenileme - sayfayı yenilemeden önce bir log yapalım
    console.log('Navbar: Sayfa yenilenecek');
    window.location.href = '/';
  } catch (error) {
    console.error('Navbar: Çıkış yapılırken hata oluştu', error);
  }
};

// Kullanıcı baş harflerini hesaplayan computed property
const initials = computed(() => {
  if (!userData.value || !userData.value.name) return '';
  return userData.value.name
    .split(' ')
    .map(n => n[0])
    .join('')
    .toUpperCase();
});

// Avatar rengini cinsiyete göre ayarlayan computed property
const getAvatarColor = computed(() => {
  if (!userData.value || !userData.value.gender) return 'bg-primary-600'; // Varsayılan
  
  switch(userData.value.gender) {
    case 'female':
      return 'bg-pink-500'; // Kadın için pembe
    case 'male':
    default:
      return 'bg-primary-600'; // Erkek ve diğer için mavi (primary)
  }
});

// Sepet drawer'ını aç/kapat
const toggleCartDrawer = () => {
  isCartDrawerOpen.value = !isCartDrawerOpen.value;
};

// Sepet drawer'ını kapat
const closeCartDrawer = () => {
  isCartDrawerOpen.value = false;
};

// Dark mode kontrolü ve scroll event listener
onMounted(() => {
  // Kullanıcı avatar'ını ayarla
  setUserAvatar();
  
  // Scroll event
  window.addEventListener('scroll', handleScroll);
  
  // ESC key event
  document.addEventListener('keydown', handleEscKey);

  // Dışa tıklama event
  document.addEventListener('click', (event) => {
    const userMenuEl = document.querySelector('.user-menu-dropdown');
    if (userMenuEl && !userMenuEl.contains(event.target) && userMenuOpen.value) {
      userMenuOpen.value = false;
    }
  });

  // ESC tuşu ile cart drawer'ı kapatma
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isCartDrawerOpen.value) {
      closeCartDrawer();
    }
  });
  
  // Sepete ürün eklendiğinde drawer'ı otomatik aç
  window.addEventListener('cart-updated', (event) => {
    if (event.detail && event.detail.action === 'added') {
      isCartDrawerOpen.value = true;
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  document.removeEventListener('keydown', handleEscKey);
  document.removeEventListener('click');
  window.removeEventListener('cart-updated');
  toggleBodyScroll(false);
});
</script>

<style scoped>
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

@media (max-width: 767px) {
  #navbar-sticky {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 0 0 1rem 1rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform-origin: top;
    animation: slideDown 0.3s ease-out forwards;
    overflow: hidden;
    border: 1px solid rgba(229, 231, 235, 0.5);
  }

  .dark #navbar-sticky {
    background: #1f2937;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(55, 65, 81, 0.5);
  }
  
  @keyframes slideDown {
    from {
      transform: scaleY(0);
      opacity: 0;
    }
    to {
      transform: scaleY(1);
      opacity: 1;
    }
  }
}

/* Aktif menü için stiller */
@media (min-width: 768px) {
  .router-link-active .absolute {
    background-color: var(--primary-color, #0ea5e9);
  }
}
</style>