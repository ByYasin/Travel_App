<template>
  <section class="py-24 bg-white dark:bg-gray-800 relative overflow-hidden">
    <!-- Arka plan deseni -->
    <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="text-center mb-20">
        <span class="inline-block px-4 py-2 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-sm font-semibold mb-6 animate-fade-in">
          Neden Bizi Tercih Etmelisiniz
        </span>
        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6 animate-slide-up">
          Unutulmaz Anılar, Eşsiz Deneyimler
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto animate-slide-up-delayed">
          Her anı özel olan, her detayı düşünülmüş, her anı hatırlanmaya değer bir tatil deneyimi için doğru adrestesiniz.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Sol Taraf - İstatistikler -->
        <div class="space-y-12">
          <div class="flex items-center space-x-6 group hover:bg-white dark:hover:bg-gray-800 p-6 rounded-2xl transition-all duration-300 hover:shadow-xl">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">10+ Yıl</h3>
              <p class="text-gray-600 dark:text-gray-400 text-lg">Deneyimli ekibimizle profesyonel hizmet</p>
            </div>
          </div>

          <div class="flex items-center space-x-6 group hover:bg-white dark:hover:bg-gray-800 p-6 rounded-2xl transition-all duration-300 hover:shadow-xl">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
              </svg>
            </div>
            <div>
              <h3 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">4.9/5</h3>
              <p class="text-gray-600 dark:text-gray-400 text-lg">Müşteri memnuniyeti oranı</p>
            </div>
          </div>

          <div class="flex items-center space-x-6 group hover:bg-white dark:hover:bg-gray-800 p-6 rounded-2xl transition-all duration-300 hover:shadow-xl">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div>
              <h3 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">%100</h3>
              <p class="text-gray-600 dark:text-gray-400 text-lg">Sigortalı ve güvenli hizmet</p>
            </div>
          </div>
        </div>

        <!-- Sağ Taraf - Özellikler -->
        <div class="space-y-8">
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
              <div>
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Hızlı Rezervasyon</h4>
                <p class="text-gray-600 dark:text-gray-400">Saniyeler içinde rezervasyon yapın, anında onay alın.</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Uygun Fiyat</h4>
                <p class="text-gray-600 dark:text-gray-400">En uygun fiyatlarla kaliteli hizmet sunuyoruz.</p>
              </div>
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="flex items-start space-x-4">
              <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
              </div>
              <div>
                <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">7/24 Destek</h4>
                <p class="text-gray-600 dark:text-gray-400">Kesintisiz müşteri desteği ile yanınızdayız.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
// Component logic here if needed
</script>

<style scoped>
.bg-grid-pattern {
  background-image: linear-gradient(to right, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
}

.animate-fade-in {
  animation: fadeIn 0.5s ease-in-out;
}

.animate-slide-up {
  animation: slideUp 0.5s ease-in-out;
}

.animate-slide-up-delayed {
  animation: slideUp 0.5s ease-in-out 0.2s backwards;
}

.animate-bounce-slow {
  animation: bounce 2s infinite;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.group:hover {
  transform: translateX(10px);
}
</style> 