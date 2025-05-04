<template>
  <header class="admin-header p-3 md:p-4 sticky top-0 z-20">
    <div class="flex justify-between items-center">
      <!-- Sidebar Toggle ve Sayfa Başlığı -->
      <div class="flex items-center space-x-3">
        <button 
          @click="$emit('toggle-sidebar')" 
          class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 lg:hidden"
          aria-label="Toggle sidebar"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        
        <h1 class="text-lg md:text-xl font-medium text-gray-800 dark:text-white truncate max-w-[150px] sm:max-w-xs md:max-w-full">
          {{ pageTitle }}
        </h1>
      </div>
      
      <!-- Sağ Bölüm: Tema Butonu, Bildirimler ve Profil -->
      <div class="flex items-center space-x-1 md:space-x-3">
        <!-- Tema Toggle Butonu -->
        <button 
          @click="toggleDarkMode" 
          class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
          aria-label="Toggle dark mode"
        >
          <svg v-if="darkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>
        
        <!-- Bildirimler -->
        <div class="relative">
          <button 
            @click="toggleNotifications" 
            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
            aria-label="Notifications"
          >
            <div class="relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <span v-if="notificationCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full h-4 w-4 flex items-center justify-center text-xs">
                {{ notificationCount }}
              </span>
            </div>
          </button>
          
          <!-- Bildirim Dropdown -->
          <div v-if="showNotifications" class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-30 border dark:border-gray-700">
            <div class="p-3 border-b dark:border-gray-700">
              <div class="flex justify-between items-center">
                <h3 class="font-medium text-gray-800 dark:text-white">Bildirimler</h3>
                <span class="text-xs bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded-full">{{ notificationCount }} yeni</span>
              </div>
            </div>
            <div class="max-h-64 overflow-y-auto">
              <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500 dark:text-gray-400">
                Bildirim bulunmuyor.
              </div>
              <a 
                v-for="(notification, index) in notifications" 
                :key="index" 
                href="#" 
                class="block px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <div class="flex items-start">
                  <div :class="['h-8 w-8 rounded-full flex items-center justify-center text-white', notification.color]">
                    <svg v-if="notification.type === 'reservation'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <svg v-else-if="notification.type === 'message'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ notification.title }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ notification.time }}</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="p-2 border-t dark:border-gray-700">
              <a href="#" class="block text-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Tüm bildirimleri gör</a>
            </div>
          </div>
        </div>
        
        <!-- Profil Dropdown -->
        <div class="relative">
          <button 
            @click="toggleProfileMenu" 
            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
            aria-label="User menu"
          >
            <div v-if="userAvatar" class="h-8 w-8 rounded-full overflow-hidden">
              <img class="h-full w-full object-cover" :src="userAvatar" alt="User avatar">
            </div>
            <div v-else class="h-8 w-8 rounded-full flex items-center justify-center text-white overflow-hidden" :class="avatarColor">
              <span class="text-sm font-medium">{{ userInitials }}</span>
            </div>
            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ userName }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="hidden md:block h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          
          <!-- Profil Menü Dropdown -->
          <div v-if="showProfileMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-30 border dark:border-gray-700">
            <a href="/admin/settings" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Ayarlar
              </div>
            </a>
            <div class="border-t dark:border-gray-700 my-1"></div>
            <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Çıkış Yap
              </div>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  sidebarOpen: {
    type: Boolean,
    default: false
  },
  pageTitle: {
    type: String,
    default: 'Dashboard'
  }
});

const router = useRouter();

// Tema kontrolü
const darkMode = ref(false);
const showNotifications = ref(false);
const showProfileMenu = ref(false);

// Örnek bildirimler
const notifications = ref([
  {
    type: 'reservation',
    title: 'Yeni rezervasyon: Kapadokya Turu',
    time: '5 dakika önce',
    color: 'bg-blue-500'
  },
  {
    type: 'message',
    title: 'Yeni mesaj: Ali Yılmaz',
    time: '10 dakika önce',
    color: 'bg-green-500'
  },
  {
    type: 'system',
    title: 'Sistem güncellemesi tamamlandı',
    time: '1 saat önce',
    color: 'bg-purple-500'
  }
]);

const notificationCount = ref(2);

// Tema değiştirme
const toggleDarkMode = () => {
  darkMode.value = !darkMode.value;
  if (darkMode.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
    // Event ile diğer bileşenleri bilgilendir
    window.dispatchEvent(new CustomEvent('theme-changed', { 
      detail: { theme: 'dark' }
    }));
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
    // Event ile diğer bileşenleri bilgilendir
    window.dispatchEvent(new CustomEvent('theme-changed', { 
      detail: { theme: 'light' }
    }));
  }
};

// Bildirimleri aç/kapat
const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    showProfileMenu.value = false;
  }
};

// Profil menüsünü aç/kapat
const toggleProfileMenu = () => {
  showProfileMenu.value = !showProfileMenu.value;
  if (showProfileMenu.value) {
    showNotifications.value = false;
  }
};

// Çıkış yap fonksiyonu
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

// Dropdown dışına tıklandığında kapatma
const handleClickOutside = (event) => {
  const notificationBtn = document.querySelector('[aria-label="Notifications"]');
  const profileBtn = document.querySelector('[aria-label="User menu"]');
  
  if (notificationBtn && !notificationBtn.contains(event.target) && showNotifications.value) {
    showNotifications.value = false;
  }
  
  if (profileBtn && !profileBtn.contains(event.target) && showProfileMenu.value) {
    showProfileMenu.value = false;
  }
};

const userName = ref('');
const userAvatar = ref('');
const userInitials = ref('');
const userGender = ref(null);
const avatarColor = ref('bg-primary-600');

onMounted(async () => {
  // Tema tercihini kontrol et
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    darkMode.value = true;
    document.documentElement.classList.add('dark');
  } else {
    darkMode.value = false;
    document.documentElement.classList.remove('dark');
  }

  // Kullanıcı bilgisini localStorage'dan çek
  try {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      const userData = JSON.parse(userStr);
      userName.value = userData.name || 'Kullanıcı';
      // Kullanıcı profil resmini al (varsa)
      if (userData.avatar) {
        userAvatar.value = userData.avatar;
      }
      // Cinsiyet bilgisini al ve avatar rengini ayarla
      userGender.value = userData.gender;
      setAvatarColor(userData.gender);
      // Kullanıcı baş harflerini hesapla
      setUserInitials(userData.name);
    } else {
      // Eğer localStorage'da yoksa API'den çekmeyi dene
      const token = localStorage.getItem('token');
      if (token) {
        const response = await fetch('/api/user', {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        if (response.ok) {
          const data = await response.json();
          userName.value = data.name || 'Kullanıcı';
          // Kullanıcı profil resmini al (varsa)
          if (data.avatar) {
            userAvatar.value = data.avatar;
          }
          // Cinsiyet bilgisini al ve avatar rengini ayarla
          userGender.value = data.gender;
          setAvatarColor(data.gender);
          // Kullanıcı baş harflerini hesapla
          setUserInitials(data.name);
        } else {
          userName.value = 'Kullanıcı';
          setUserInitials('Kullanıcı');
        }
      } else {
        userName.value = 'Kullanıcı';
        setUserInitials('Kullanıcı');
      }
    }
  } catch (e) {
    console.error('Kullanıcı bilgisi alınırken hata:', e);
    userName.value = 'Kullanıcı';
    setUserInitials('Kullanıcı');
  }

  // Dropdown dışına tıklandığında kapatma için event listener
  document.addEventListener('click', handleClickOutside);
});

// Avatar rengini cinsiyete göre ayarlama fonksiyonu
const setAvatarColor = (gender) => {
  if (!gender) {
    avatarColor.value = 'bg-primary-600'; // Varsayılan
    return;
  }
  
  switch(gender) {
    case 'female':
      avatarColor.value = 'bg-pink-500'; // Kadın için pembe
      break;
    case 'male':
    default:
      avatarColor.value = 'bg-primary-600'; // Erkek ve diğer için mavi
      break;
  }
};

// Kullanıcı baş harflerini hesaplama fonksiyonu
const setUserInitials = (name) => {
  if (!name) {
    userInitials.value = '?';
    return;
  }
  
  const nameParts = name.split(' ');
  if (nameParts.length === 1) {
    userInitials.value = nameParts[0].charAt(0).toUpperCase();
  } else {
    userInitials.value = (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
  }
};

onUnmounted(() => {
  // Event listener'ı temizle
  document.removeEventListener('click', handleClickOutside);
});
</script>