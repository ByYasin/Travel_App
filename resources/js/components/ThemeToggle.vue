<template>
  <button 
    @click="toggleTheme" 
    class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition-all duration-300 focus:ring-2 focus:ring-primary-400 dark:focus:ring-primary-500 hover:rotate-12"
    :aria-label="themeStore.theme === 'dark' ? 'Açık temaya geç' : 'Koyu temaya geç'"
    :title="themeStore.theme === 'dark' ? 'Açık tema' : 'Koyu tema'"
  >
    <svg v-if="themeStore.theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
  </button>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue';
import { useThemeStore } from '@/store/theme';

// Tema store'unu kullan
const themeStore = useThemeStore();

// Temayı değiştir
const toggleTheme = () => {
  themeStore.toggleTheme();
};

onMounted(() => {
  // Tema store'unu başlat
  const cleanupListener = themeStore.initTheme();
  
  // Bileşen yok edildiğinde event listener'ı temizle
  onUnmounted(() => {
    if (typeof cleanupListener === 'function') {
      cleanupListener();
    }
  });
});
</script>

<style scoped>
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}
</style> 