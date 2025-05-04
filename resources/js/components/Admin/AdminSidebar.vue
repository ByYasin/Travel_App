<template>
  <div>
    <!-- Mobil overlay - sadece mobil görünümde ve sidebar açıkken gösterilir -->
    <div 
      v-if="sidebarOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden"
      @click="$emit('close-sidebar')"
    ></div>

    <!-- Sidebar -->
    <aside
      :class="[
        'fixed top-0 left-0 h-full overflow-y-auto admin-sidebar shadow-lg z-30 transition-all duration-300 w-64',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0 md:w-16 lg:w-64'
      ]"
    >
      <!-- Logo ve Kapatma Butonu -->
      <div class="flex items-center justify-between px-4 py-3 border-b dark:border-gray-700">
        <router-link to="/admin/" class="flex items-center">
          <span :class="['text-lg font-bold text-gray-800 dark:text-white', !sidebarOpen && 'md:hidden']">
            Admin Panel
          </span>
        </router-link>
        <button 
          @click="$emit('close-sidebar')" 
          class="p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none md:hidden"
        >
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Navigasyon -->
      <nav class="px-2 py-4">
        <div class="space-y-1">
          <!-- Dashboard -->
          <router-link 
            to="/admin/" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/' || $route.path === '/admin' }"
          >
            <svg class="w-6 h-6 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Dashboard</span>
          </router-link>

          <!-- Tours -->
          <router-link 
            to="/admin/tours" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path.startsWith('/admin/tours') }"
          >
            <svg class="w-6 h-6 mr-3 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Turlar</span>
          </router-link>

          <!-- Categories -->
          <router-link 
            to="/admin/categories" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/categories' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Kategoriler</span>
          </router-link>

          <!-- Reservations -->
          <router-link 
            to="/admin/reservations" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/reservations' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Rezervasyonlar</span>
          </router-link>

          <!-- Users -->
          <router-link 
            to="/admin/users" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/users' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Kullanıcılar</span>
          </router-link>

          <!-- İletişim (Contact) -->
          <router-link 
            to="/admin/contact" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/contact' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">İletişim</span>
          </router-link>

          <!-- Reports -->
          <router-link 
            to="/admin/reports" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/reports' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Raporlar</span>
          </router-link>

          <!-- Settings -->
        <router-link 
            to="/admin/settings" 
            class="flex items-center px-2 py-2 text-gray-700 hover:bg-gray-100 hover:text-gray-900 rounded-md dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white"
            :class="{ 'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white': $route.path === '/admin/settings' }"
          >
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span :class="[!sidebarOpen && 'md:hidden']">Ayarlar</span>
        </router-link>
        </div>
      </nav>
      
      <!-- Çıkış Butonu -->
      <div class="px-4 py-3 mt-auto border-t dark:border-gray-700 absolute bottom-0 w-full">
        <button 
          @click="logout" 
          class="flex items-center w-full px-2 py-2 text-red-500 hover:bg-red-100 hover:text-red-700 rounded-md dark:text-red-400 dark:hover:bg-red-900/50 dark:hover:text-red-300"
        >
          <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
          <span :class="[!sidebarOpen && 'md:hidden']">Çıkış Yap</span>
        </button>
      </div>
    </aside>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  sidebarOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close-sidebar']);
const router = useRouter();

// Çıkış işlemi
const logout = async () => {
  try {
    // AuthService ile logout işlemini çağır
    const authService = (await import('@/services/auth')).default;
    await authService.logout();
    // Yönlendirme artık authService.logout içinde yapılıyor
  } catch (error) {
    console.error('Çıkış yapılırken hata oluştu:', error);
    localStorage.removeItem('user');
    localStorage.removeItem('token');
    window.location.href = '/login';
  }
};

onMounted(() => {
  // Ekran boyutu 1024px veya daha geniş ise
  // Masaüstünde varsayılan olarak kapalı olsun
  if (window.innerWidth >= 1024) {
    emit('close-sidebar');
  }
});
</script> 