<template>
  <section class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 relative overflow-hidden py-16">
    <!-- Dekoratif Elementler -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-500/10 dark:bg-primary-400/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-primary-400/10 dark:bg-primary-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Seyahat Ekonomisi</h2>
        <p class="text-gray-600 dark:text-gray-300 text-lg">Seyahatinizi akıllıca planlayın, bütçenizi kontrol altında tutun</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- Bütçe Hesaplayıcı -->
        <div class="bg-white dark:bg-gray-800 backdrop-blur-sm rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-400 transition-all shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Bütçe Hesaplayıcı</h3>
            <svg class="w-6 h-6 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div class="space-y-4">
            <select class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white" v-model="selectedCity">
              <option value="">Şehir Seçin</option>
              <option value="istanbul">İstanbul</option>
              <option value="antalya">Antalya</option>
              <option value="kapadokya">Kapadokya</option>
            </select>
            <div class="flex gap-4">
              <input type="number" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" v-model="days" placeholder="Gün Sayısı">
              <input type="number" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400" v-model="persons" placeholder="Kişi Sayısı">
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Tahmini Bütçe:</div>
              <div class="text-2xl font-bold text-primary-600 dark:text-primary-400" v-if="estimatedBudget">₺{{ estimatedBudget }}</div>
              <div class="text-lg text-gray-400 dark:text-gray-500" v-else>---</div>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">*Düşük sezonda %40'a varan indirimler olabilir</div>
          </div>
        </div>

        <!-- Fiyat Analizi -->
        <div class="bg-white dark:bg-gray-800 backdrop-blur-sm rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:border-primary-500 dark:hover:border-primary-400 transition-all shadow-sm">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Sezonluk Fiyat Analizi</h3>
            <svg class="w-6 h-6 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
            </svg>
          </div>
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                <p class="text-gray-600 dark:text-gray-300">Yüksek Sezon</p>
                <p class="text-gray-900 dark:text-white font-bold">Haz - Ağu</p>
              </div>
              <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                <p class="text-gray-600 dark:text-gray-300">Düşük Sezon</p>
                <p class="text-gray-900 dark:text-white font-bold">Kas - Şub</p>
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 dark:text-gray-300">Fiyat Farkı</span>
                <span class="text-gray-900 dark:text-white font-bold">%40</span>
              </div>
              <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                <div class="bg-primary-500 dark:bg-primary-400 h-2 rounded-full" style="width: 40%"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- İpuçları -->
      <div class="mt-12 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 backdrop-blur-sm rounded-2xl p-8 border border-gray-200 dark:border-gray-700 shadow-lg max-w-4xl mx-auto transition-all">
        <div class="flex items-center space-x-4 mb-8">
          <span class="bg-primary-500 dark:bg-primary-600 rounded-full p-2 shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
          </span>
          <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
            <span class="relative inline-block">
              Tasarruf İpuçları
              <span class="absolute -bottom-1 left-0 h-1 bg-primary-500 dark:bg-primary-400 w-3/4 rounded-full"></span>
            </span>
          </h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- İpucu 1 -->
          <div class="relative overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-600 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-primary-100 dark:bg-primary-900/30 rounded-bl-full z-0"></div>
            <div class="relative z-10">
              <div class="bg-primary-500 dark:bg-primary-600 text-white p-3 rounded-lg inline-block mb-4 shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
              <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Erken Rezervasyon</h4>
              <p class="text-gray-700 dark:text-gray-300 mb-4">3 ay önceden rezervasyon yaparak <span class="font-bold text-primary-600 dark:text-primary-400">%25'e varan indirimler</span> elde edebilirsiniz.</p>
              
              <div class="mt-2">
                <div class="flex items-center justify-between mb-1">
                  <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tasarruf Potansiyeli</span>
                  <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">25%</span>
                </div>
                <div class="w-full h-2.5 bg-gray-200 dark:bg-gray-600 rounded-full">
                  <div class="h-2.5 rounded-full bg-primary-500 dark:bg-primary-400" style="width: 25%"></div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- İpucu 2 -->
          <div class="relative overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-600 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-primary-100 dark:bg-primary-900/30 rounded-bl-full z-0"></div>
            <div class="relative z-10">
              <div class="bg-primary-500 dark:bg-primary-600 text-white p-3 rounded-lg inline-block mb-4 shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Grup İndirimleri</h4>
              <p class="text-gray-700 dark:text-gray-300 mb-4">6+ kişi ile rezervasyon yaptığınızda <span class="font-bold text-primary-600 dark:text-primary-400">toplam fiyatta %15 indirim</span> kazanırsınız.</p>
              
              <div class="flex items-center justify-between bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-900/20 dark:to-transparent p-2 rounded-lg mt-2">
                <div class="flex -space-x-2">
                  <div class="w-8 h-8 rounded-full bg-primary-200 dark:bg-primary-800 flex items-center justify-center text-xs font-medium text-primary-800 dark:text-primary-200 border-2 border-white dark:border-gray-800">2+</div>
                  <div class="w-8 h-8 rounded-full bg-primary-300 dark:bg-primary-700 flex items-center justify-center text-xs font-medium text-primary-800 dark:text-primary-200 border-2 border-white dark:border-gray-800">4+</div>
                  <div class="w-8 h-8 rounded-full bg-primary-500 dark:bg-primary-600 flex items-center justify-center text-xs font-medium text-white border-2 border-white dark:border-gray-800">6+</div>
                </div>
                <span class="text-xs font-medium text-primary-600 dark:text-primary-400">Kişi sayısı arttıkça indirim artar</span>
              </div>
            </div>
          </div>
          
          <!-- Yerine yeni bir ipucu kartı eklenebilir -->
          <div class="relative overflow-hidden bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-100 dark:border-gray-600 shadow-md hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
            <div class="absolute top-0 right-0 w-20 h-20 bg-primary-100 dark:bg-primary-900/30 rounded-bl-full z-0"></div>
            <div class="relative z-10">
              <div class="bg-primary-500 dark:bg-primary-600 text-white p-3 rounded-lg inline-block mb-4 shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                </svg>
              </div>
              <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Online Ödemede İndirim</h4>
              <p class="text-gray-700 dark:text-gray-300 mb-4">Online ödeme yöntemlerimizle rezervasyon yaptığınızda <span class="font-bold text-primary-600 dark:text-primary-400">%5 ekstra indirim</span> kazanın.</p>
              
              <div class="p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                <div class="flex items-center space-x-2 mb-2">
                  <svg class="w-5 h-5 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  <span class="text-sm text-gray-700 dark:text-gray-300">Anında onay</span>
                </div>
                <div class="flex items-center space-x-2">
                  <svg class="w-5 h-5 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  <span class="text-sm text-gray-700 dark:text-gray-300">Güvenli ödeme</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

// Bütçe Hesaplayıcı için değişkenler
const selectedCity = ref('')
const days = ref('')
const persons = ref('')
const estimatedBudget = ref(0)

// Şehirlere göre günlük kişi başı ortalama maliyet
const cityDailyCosts = {
  istanbul: 2500,
  antalya: 3000,
  kapadokya: 2800
}

// Bütçe hesaplama fonksiyonu
const calculateBudget = () => {
  if (!selectedCity.value || !days.value || !persons.value) {
    estimatedBudget.value = 0
    return
  }

  const dailyCost = cityDailyCosts[selectedCity.value]
  const total = dailyCost * parseInt(days.value) * parseInt(persons.value)
  estimatedBudget.value = total.toLocaleString('tr-TR')
}

// Bütçe değişikliklerini izle
watch([selectedCity, days, persons], calculateBudget)

// Sezonluk indirim hesaplama
const calculateSeasonalDiscount = (price) => {
  const currentMonth = new Date().getMonth() + 1
  // Yüksek sezon: Haziran - Ağustos (6-8)
  // Düşük sezon: Kasım - Şubat (11-2)
  if (currentMonth >= 11 || currentMonth <= 2) {
    return price * 0.6 // %40 indirim
  }
  return price
}

onMounted(() => {
  // Sayfa yüklendiğinde yapılacak işlemler
})
</script>

<style scoped>
.bg-gradient-to-br {
  background-size: 200% 200%;
  animation: gradient 15s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type=number] {
  -moz-appearance: textfield;
}

select, input {
  transition: all 0.3s ease;
}

select:focus, input:focus {
  outline: none;
  border-color: rgba(var(--primary-500), 0.5);
  box-shadow: 0 0 0 2px rgba(var(--primary-500), 0.2);
}
</style> 