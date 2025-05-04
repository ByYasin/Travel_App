<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Başarı Kartı -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <!-- Yükleniyor durumu -->
        <div v-if="isLoading" class="p-8 flex flex-col items-center justify-center">
          <div class="w-16 h-16 border-4 border-primary-500 border-t-transparent rounded-full animate-spin mb-4"></div>
          <p class="text-gray-600 dark:text-gray-300">Rezervasyonunuz oluşturuluyor...</p>
        </div>

        <div v-else>
          <!-- Başarı Animasyonu ve Başlık -->
          <div class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 p-8 text-center">
            <div class="mb-4 relative">
              <div class="w-24 h-24 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto animate-scale-check">
                <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2">
                <div class="px-4 py-1 bg-green-500 rounded-full">
                  <span class="text-xs font-medium text-white">Onaylandı</span>
                </div>
              </div>
            </div>
            <h1 class="text-2xl font-bold text-white mb-2">Ödeme Başarıyla Tamamlandı!</h1>
            <p class="text-primary-100">Rezervasyonunuz başarıyla oluşturuldu.</p>
          </div>

          <!-- Rezervasyon Detayları -->
          <div class="p-8">
            <!-- İşlem Numarası -->
            <div class="flex items-center justify-center mb-8">
              <span class="px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300">
                İşlem No: #{{ reservationInfo.transactionId }}
              </span>
            </div>

            <!-- Ödeme Detayları -->
            <div class="space-y-6">
              <!-- Tutar Bilgisi -->
              <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center space-x-4">
                  <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Toplam Tutar</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ reservationInfo.installment > 1 ? `${reservationInfo.installment} Taksit` : 'Tek Çekim' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold text-gray-900 dark:text-white">₺{{ reservationInfo.amount }}</p>
                  <p v-if="reservationInfo.installment > 1" class="text-sm text-gray-500 dark:text-gray-400">
                    {{ reservationInfo.installment }}x ₺{{ (reservationInfo.amount / reservationInfo.installment).toFixed(2) }}
                  </p>
                </div>
              </div>

              <!-- E-posta Bildirimi -->
              <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                  </div>
                  <div class="ml-3">
                    <p class="text-sm text-blue-600 dark:text-blue-400">
                      Rezervasyon detaylarınız e-posta adresinize gönderildi.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Butonlar -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                <button @click="goToReservations" 
                        class="w-full px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium transition-colors flex items-center justify-center space-x-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                  </svg>
                  <span>Rezervasyonlarım</span>
                </button>
                <button @click="downloadPDF" 
                        class="w-full px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-xl font-medium transition-colors flex items-center justify-center space-x-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                  <span>Bileti İndir</span>
                </button>
              </div>

              <!-- Yardım Bağlantısı -->
              <div class="text-center mt-6">
                <a href="/contact" class="text-sm text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                  Yardıma mı ihtiyacınız var? Bizimle iletişime geçin
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import { useAuthStore } from '../store/auth'
import { useCartStore } from '../store/cart'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const cartStore = useCartStore()
const isLoading = ref(true)

// Rezervasyon bilgileri
const reservationInfo = ref({
  tourId: route.query.tourId || null,
  amount: route.query.amount || '0',
  installment: parseInt(route.query.installment) || 1,
  transactionId: generateTransactionId()
})

// İşlem numarası oluşturma
function generateTransactionId() {
  const date = new Date()
  const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0')
  return `TR${date.getFullYear()}${(date.getMonth() + 1).toString().padStart(2, '0')}${random}`
}

// Rezervasyonlara git
const goToReservations = () => {
  router.push('/profile/reservations')
}

// PDF İndir
const downloadPDF = () => {
  // PDF indirme işlemi burada implement edilecek
  alert('PDF indirme özelliği yakında eklenecek!')
}

// Sayfa yüklendiğinde rezervasyonu kaydet
onMounted(async () => {
  try {
    if (!authStore.isLoggedIn) {
      router.push('/login')
      return
    }

    // Rezervasyon ve ödeme bilgilerini al
    const tourId = route.query.tourId
    
    if (!tourId) {
      console.error('Tour ID eksik')
      isLoading.value = false
      return
    }

    // Sepet durumunu kontrol et
    const cartItems = cartStore.items
    console.log('Ödeme öncesi sepet durumu:', { itemCount: cartItems.length, items: cartItems })

    // Rezervasyon bilgilerini kaydet
    const reservationData = {
      tour_id: tourId,
      total_price: reservationInfo.value.amount,
      reservation_date: new Date().toISOString().split('T')[0],
      status: 'completed',
      payment_method: 'credit_card',
      transaction_id: reservationInfo.value.transactionId,
      installment: reservationInfo.value.installment
    }

    // Rezervasyonu API'ye gönder
    const response = await axios.post('/api/reservations', reservationData)
    
    // Rezervasyon bilgilerini güncelle
    if (response.data.reservation) {
      reservationInfo.value = {
        ...reservationInfo.value,
        ...response.data.reservation
      }
    }
    
    // Ödeme tamamlandığı için sepeti temizle
    console.log('Ödeme başarılı: Sepeti temizleniyor...')
    
    // Sepeti temizle ve localStorage'ı güncelle
    cartStore.completePayment()
    
    // Sepet temizlendikten sonra localStorage kontrolü yap
    if (localStorage.getItem('cart-store')) {
      try {
        const cartData = JSON.parse(localStorage.getItem('cart-store'))
        if (Array.isArray(cartData.items) && cartData.items.length > 0) {
          console.warn('Sepet localStorage\'da hala temizlenmedi, manuel temizleme yapılıyor')
          localStorage.setItem('cart-store', JSON.stringify({ items: [] }))
        }
      } catch (e) {
        console.error('LocalStorage temizleme hatası:', e)
      }
    }
    
    console.log('Sepet başarıyla temizlendi')
    console.log('Temizleme sonrası sepet durumu:', { itemCount: cartStore.items.length })
    
    isLoading.value = false
  } catch (error) {
    console.error('Rezervasyon hatası:', error)
    isLoading.value = false
  }
})

// Başarı animasyonu için CSS
const style = document.createElement('style')
style.textContent = `
  @keyframes scale-check {
    0% { transform: scale(0.8); opacity: 0; }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); opacity: 1; }
  }
  .animate-scale-check {
    animation: scale-check 0.5s ease-out forwards;
  }
`
document.head.appendChild(style)
</script> 