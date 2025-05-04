<template>
  <div class="container mx-auto px-4">
    <div class="text-center py-12">
      <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
        Dünyayı Keşfetmeye Başlayın
      </h1>
      <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
        En iyi tur fırsatları ve unutulmaz deneyimler için doğru adrestesiniz.
      </p>
      <router-link to="/tours" class="btn btn-primary">
        Turları Keşfet
      </router-link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-12">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="text-primary-600 dark:text-primary-400 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Geniş Tur Seçenekleri</h3>
        <p class="text-gray-600 dark:text-gray-300">
          Yurtiçi ve yurtdışı turlar, özel turlar ve daha fazlası.
        </p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="text-primary-600 dark:text-primary-400 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Esnek Rezervasyon</h3>
        <p class="text-gray-600 dark:text-gray-300">
          Kolay rezervasyon ve iptal seçenekleri ile güvenle rezervasyon yapın.
        </p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="text-primary-600 dark:text-primary-400 mb-4">
          <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Güvenli Ödeme</h3>
        <p class="text-gray-600 dark:text-gray-300">
          Güvenli ödeme seçenekleri ile güvenle alışveriş yapın.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
// Component logic here
</script> 