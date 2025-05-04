<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 w-full max-w-full overflow-x-hidden">
    <!-- Sayfa Arka Plan Deseni -->
    <div class="fixed inset-0 pointer-events-none -z-10 w-screen">
      <div class="absolute inset-0 bg-pattern opacity-5 dark:opacity-3"></div>
      <!-- Sol üst dekoratif gradient -->
      <div class="absolute left-0 top-0 w-1/3 h-1/3 bg-gradient-to-br from-primary-500/10 via-blue-500/5 to-transparent blur-3xl"></div>
      <!-- Sağ alt dekoratif gradient -->
      <div class="absolute right-0 bottom-0 w-1/3 h-1/3 bg-gradient-to-tl from-primary-500/10 via-indigo-500/5 to-transparent blur-3xl"></div>
    </div>
    
    <Navbar />
    
    <main class="flex-grow pt-20 w-full">
      <!-- Sayfa içeriği - animasyon kaldırıldı -->
      <slot />
    </main>
    
    <!-- Back to top button -->
    <button 
      v-show="showBackToTop"
      @click="scrollToTop"
      class="fixed right-8 bottom-8 z-50 p-3 rounded-full bg-primary-600 text-white shadow-lg hover:bg-primary-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1"
      aria-label="Sayfa başına dön"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
      </svg>
    </button>
    
    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

// Yukarı çık butonu kontrolü
const showBackToTop = ref(false);

// Sayfa kaydırma olayını dinle
const handleScroll = () => {
  showBackToTop.value = window.scrollY > 500;
};

// Sayfa başına dön fonksiyonu
const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
};

// Component mount/unmount yaşam döngüsü
onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Sayfa geçiş animasyonları kaldırıldı */

/* Arka plan deseni */
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23338cf8' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
  .bg-pattern {
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%235b61ea' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
}
</style> 