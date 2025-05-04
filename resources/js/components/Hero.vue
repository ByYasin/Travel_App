<template>
  <section class="relative min-h-screen w-full flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 w-screen">
      <img src="https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg" alt="Hero Background" 
           class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-black/50"></div>
    </div>
    <div class="relative max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
        Türkiye'nin En Güzel Yerlerini
        <span class="text-primary-400">Keşfedin</span>
      </h1>
      <p class="text-xl text-gray-300 max-w-2xl mx-auto mb-8">
        Profesyonel rehberler eşliğinde unutulmaz deneyimler yaşayın
      </p>
      <div class="flex flex-wrap gap-4 justify-center">
        <router-link to="/tours" 
                    class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-xl font-medium transition-all duration-300 flex items-center gap-2 group">
          <span>Turları Keşfet</span>
          <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>

<script setup>
// Component logic here if needed
</script>

<style scoped>
/* Tam genişlik için stil */
section {
  width: 100vw;
  max-width: 100%;
  margin: 0;
  padding: 0;
}
</style> 