<template>
  <div class="relative mb-16">
    <!-- Arka plan gradient ve desen -->
    <div 
      class="bg-gradient-to-r from-blue-700 via-blue-600 to-blue-800 h-64 md:h-80 rounded-b-3xl overflow-hidden shadow-xl relative"
      :style="backgroundImageStyle"
    >
      <!-- Arka plan örtüsü (koyu resimler için) -->
      <div class="absolute inset-0 bg-black/30 backdrop-brightness-75 backdrop-blur-sm"></div>
      
      <!-- Desen -->
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      
      <!-- Dekoratif daireler -->
      <div class="absolute -top-20 -right-20 w-80 h-80 bg-white dark:bg-blue-800 opacity-10 rounded-full"></div>
      <div class="absolute -bottom-40 -left-20 w-96 h-96 bg-white dark:bg-blue-600 opacity-10 rounded-full"></div>
      
      <!-- Daha küçük ve parlak dekoratif noktalar -->
      <div class="absolute top-1/4 right-1/4 w-12 h-12 bg-white opacity-20 rounded-full blur-md"></div>
      <div class="absolute bottom-1/3 left-1/3 w-8 h-8 bg-white opacity-20 rounded-full blur-md"></div>
      <div class="absolute top-1/2 left-1/5 w-16 h-16 bg-white opacity-15 rounded-full blur-md"></div>
    </div>
    
    <!-- İçerik -->
    <div class="absolute inset-0 flex flex-col items-center justify-center px-4">
      <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-xl text-center">{{ title }}</h1>
      <p 
        v-if="subtitle" 
        class="text-xl text-white text-opacity-90 max-w-lg text-center drop-shadow-md mb-6"
      >
        {{ subtitle }}
      </p>
      
      <!-- İçerik alanı (slot) -->
      <div v-if="$slots.actions" class="flex flex-wrap justify-center gap-4 mt-4">
        <slot name="actions"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  backgroundImage: {
    type: String,
    default: null
  }
});

const backgroundImageStyle = computed(() => {
  if (props.backgroundImage) {
    return {
      'background-image': `linear-gradient(to right, rgba(29, 78, 216, 0.85), rgba(37, 99, 235, 0.8), rgba(29, 78, 216, 0.85)), url(${props.backgroundImage})`,
      'background-size': 'cover',
      'background-position': 'center'
    };
  }
  return {};
});
</script>

<style scoped>
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.15'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Sayfa yükleme animasyonu */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
  animation: fadeIn 0.5s ease-out forwards;
}
</style> 