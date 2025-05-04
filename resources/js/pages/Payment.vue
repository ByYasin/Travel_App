<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pt-8 pb-16">
    <!-- Üst Kısım - Mini Adım Gösterimi -->
    <div class="w-full bg-white dark:bg-gray-800 py-6 shadow-sm mb-8">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 sm:mb-0">Ödeme Bilgileri</h1>
          
          <div class="flex items-center">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-medium">1</div>
              <div class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Rezervasyon</div>
            </div>
            <div class="w-8 h-1 bg-primary-200 dark:bg-primary-800 mx-2"></div>
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-medium">2</div>
              <div class="ml-2 text-sm font-medium text-gray-900 dark:text-white">Ödeme</div>
            </div>
            <div class="w-8 h-1 bg-gray-200 dark:bg-gray-700 mx-2"></div>
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center font-medium">3</div>
              <div class="ml-2 text-sm font-medium text-gray-500 dark:text-gray-400">Onay</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Özet Kartı -->
        <div class="lg:col-span-1 order-2 lg:order-1">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-24">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
              <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              Rezervasyon Özeti
            </h2>
            
            <!-- Tur Bilgileri -->
            <div class="mb-6">
              <img :src="tour.image" :alt="tour.title" class="w-full h-48 object-cover rounded-lg mb-4">
              <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ tour.title }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">{{ tour.description }}</p>
              
              <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                <p class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  {{ tour.date }} - {{ tour.time }}
                </p>
                <p class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  {{ tour.meetingPoint }}
                </p>
              </div>
            </div>

            <!-- Dahil Olanlar -->
            <div class="mb-6">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Dahil Olanlar
              </h4>
              <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                <li v-for="item in tour.inclusions" :key="item" class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ item }}
                </li>
              </ul>
            </div>

            <!-- Dahil Olmayanlar -->
            <div class="mb-6">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Dahil Olmayanlar
              </h4>
              <ul class="space-y-1 text-sm text-gray-600 dark:text-gray-300">
                <li v-for="item in tour.exclusions" :key="item" class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                  {{ item }}
                </li>
              </ul>
            </div>

            <!-- Fiyat Detayları -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
              <div class="space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-300">Tur Ücreti</span>
                  <span class="text-gray-900 dark:text-white">₺{{ tour.price }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-300">Vergi (%18)</span>
                  <span class="text-gray-900 dark:text-white">₺{{ (tour.price * 0.18).toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600 dark:text-gray-300">İşlem Ücreti</span>
                  <span class="text-gray-900 dark:text-white">₺10.00</span>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                  <div class="flex justify-between font-semibold">
                    <span class="text-gray-900 dark:text-white">Toplam</span>
                    <span class="text-primary-600 dark:text-primary-400">₺{{ (tour.price * 1.18 + 10).toFixed(2) }}</span>
                  </div>
                  <div v-if="form.installment > 1" class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                    {{ form.installment }} Taksit x ₺{{ installmentAmount }}
                  </div>
                </div>
              </div>
            </div>

            <!-- İptal Politikası -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
              <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                İptal Politikası
              </h4>
              <ul class="text-xs text-gray-600 dark:text-gray-300 space-y-1">
                <li>• Tur tarihinden 14 gün öncesine kadar ücretsiz iptal</li>
                <li>• 7-14 gün arasında %50 kesinti uygulanır</li>
                <li>• 7 günden az kala iptal edilirse iade yapılmaz</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Ödeme Formu -->
        <div class="lg:col-span-2 order-1 lg:order-2">
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <form @submit.prevent="handlePayment" class="space-y-8">
              <!-- Kişisel Bilgiler -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  Kişisel Bilgiler
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad</label>
                    <input type="text" v-model="form.firstName" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p v-if="errors.firstName" class="mt-1 text-sm text-red-600">{{ errors.firstName }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Soyad</label>
                    <input type="text" v-model="form.lastName" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p v-if="errors.lastName" class="mt-1 text-sm text-red-600">{{ errors.lastName }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta</label>
                    <input type="email" v-model="form.email" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon</label>
                    <input type="tel" v-model="form.phone" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
                  </div>
                </div>
              </div>

              <!-- Kredi Kartı Bilgileri -->
              <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                  </svg>
                  Kredi Kartı Bilgileri
                </h3>
                <div class="space-y-4">
                  <div class="p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-100 dark:border-primary-900 mb-4">
                    <div class="flex items-center gap-2 text-sm text-primary-800 dark:text-primary-300">
                      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      <span>Test için kart numarası <strong>4242 4242 4242 4242</strong>, son kullanma tarihi gelecek bir tarih, CVV için herhangi 3 rakam kullanabilirsiniz.</span>
                    </div>
                  </div>
                  
                  <!-- Kart Önizleme -->
                  <div class="relative mb-6">
                    <div class="perspective-1000">
                      <div class="relative w-full h-52 rounded-xl shadow-lg overflow-hidden transition-all duration-300 transform card-3d"
                           :class="[getCardBackgroundClass]">
                        <div class="absolute inset-0 bg-gradient-to-br opacity-60"
                             :class="getCardGradient"></div>
                             
                        <!-- Kart logosu -->
                        <div class="absolute top-4 right-4">
                          <img v-if="cardTypeIcon" :src="cardTypeIcon" class="h-10" alt="Card Type">
                          <div v-else class="w-12 h-8 bg-white/20 rounded-md flex items-center justify-center backdrop-blur-sm">
                            <span class="text-white text-xl font-bold">CARD</span>
                          </div>
                        </div>
                        
                        <!-- Banka logosu -->
                        <div class="absolute top-4 left-4">
                          <div class="text-white text-xl font-semibold tracking-tight opacity-90">BANKA KARTI</div>
                        </div>
                        
                        <!-- Çip -->
                        <div class="absolute top-20 left-6">
                          <svg width="60" height="45" viewBox="0 0 60 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="60" height="45" rx="6" fill="url(#chip-gradient)"/>
                            <path d="M0 8H60M0 16H60M0 24H60M0 32H60" stroke="rgba(255,255,255,0.2)" stroke-width="1"/>
                            <rect x="20" y="6" width="20" height="33" fill="rgba(0,0,0,0.2)"/>
                            <defs>
                              <linearGradient id="chip-gradient" x1="0" y1="0" x2="60" y2="45" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#FFD700"/>
                                <stop offset="1" stop-color="#B8860B"/>
                              </linearGradient>
                            </defs>
                          </svg>
                        </div>
                        
                        <!-- NFC icon -->
                        <div class="absolute top-20 left-20">
                          <svg class="w-7 h-7 text-white opacity-80" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z" fill="currentColor" opacity="0.8"/>
                          </svg>
                        </div>
                        
                        <!-- Kart Numarası -->
                        <div class="absolute bottom-20 left-6 right-6">
                          <div class="flex space-x-3 justify-between">
                            <span v-for="(group, index) in cardNumberGroups" :key="index" 
                                 class="text-white text-lg md:text-xl tracking-wider font-mono">
                              {{ group }}
                            </span>
                          </div>
                        </div>
                        
                        <!-- Kart Sahibi ve Skt -->
                        <div class="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                          <div>
                            <p class="text-white/70 text-xxs uppercase font-medium mb-1">KART SAHİBİ</p>
                            <p class="text-white text-sm font-medium uppercase truncate max-w-[140px]">
                              {{ cardholderName || 'AD SOYAD' }}
                            </p>
                          </div>
                          <div class="text-right">
                            <p class="text-white/70 text-xxs uppercase font-medium mb-1">GEÇERLİLİK</p>
                            <p class="text-white text-sm font-medium">{{ form.expiryDate || 'MM/YY' }}</p>
                          </div>
                        </div>
                        
                        <!-- Hologram effect -->
                        <div class="absolute bottom-6 right-16 w-12 h-12 rounded-full 
                                   bg-gradient-to-r from-purple-400/40 to-blue-500/40 
                                   backdrop-blur-md opacity-60"></div>
                      </div>
                    </div>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kart Numarası</label>
                    <div class="relative">
                      <input type="text" v-model="form.cardNumber" required maxlength="19"
                             class="w-full pl-4 pr-12 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                             @input="formatCardNumber">
                      <div class="absolute right-3 top-3">
                        <img :src="cardTypeIcon" class="w-8 h-5" v-if="cardTypeIcon">
                      </div>
                    </div>
                    <p v-if="errors.cardNumber" class="mt-1 text-sm text-red-600">{{ errors.cardNumber }}</p>
                  </div>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Son Kullanma Tarihi</label>
                      <input type="text" v-model="form.expiryDate" required maxlength="5" placeholder="MM/YY"
                             class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                             @input="formatExpiryDate">
                      <p v-if="errors.expiryDate" class="mt-1 text-sm text-red-600">{{ errors.expiryDate }}</p>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVV</label>
                      <input type="text" v-model="form.cvv" required maxlength="3"
                             class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                      <p v-if="errors.cvv" class="mt-1 text-sm text-red-600">{{ errors.cvv }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Ödeme Butonu -->
              <div class="space-y-4">
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                  <div class="flex items-center mb-4">
                    <input type="checkbox" v-model="acceptTerms" id="terms" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <label for="terms" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                      <a href="#" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Şartlar ve koşulları</a> kabul ediyorum
                    </label>
                  </div>
                  <p v-if="errors.terms" class="mt-1 text-sm text-red-600">{{ errors.terms }}</p>
                </div>
                
                <button type="submit" 
                        :disabled="isProcessing || !acceptTerms"
                        class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                  <svg v-if="isProcessing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ isProcessing ? 'İşleniyor...' : 'Ödemeyi Tamamla' }}
                </button>
              </div>

              <!-- Güvenlik Bilgisi -->
              <div class="flex items-center justify-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 pt-4">
                <div class="flex items-center mr-4">
                  <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                  </svg>
                  <span>256-bit SSL ile güvenli ödeme</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  <span>Güvenli alışveriş garantisi</span>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useCartStore } from '@/store/cart'
import PaymentService from '../services/PaymentService'

const router = useRouter()
const route = useRoute()
const toast = useToast()
const cartStore = useCartStore()

// Taksit seçeneklerini kaldırdık, sadece peşin ödeme var
// const installments = [1, 2, 3]

// Form verileri
const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  cardNumber: '',
  expiryDate: '',
  cvv: '',
  installment: 1 // Sadece peşin ödeme
})

// Hata mesajları için
const errors = ref({})

// Şartları kabul etme durumu
const acceptTerms = ref(false)

// İşlem durumu
const isProcessing = ref(false)

// Checkout verileri
const checkoutData = ref(null)

// Sayfa yüklendiğinde checkout verilerini kontrol et
onMounted(() => {
  const checkoutDataStr = localStorage.getItem('checkout-data')
  if (!checkoutDataStr) {
    toast.error('Ödeme bilgileri bulunamadı')
    router.push('/')
    return
  }

  try {
    const parsedData = JSON.parse(checkoutDataStr)
    // Zaman kontrolü: 30 dakikadan eski değilse
    const timeElapsed = Date.now() - parsedData.timestamp
    if (timeElapsed < 30 * 60 * 1000) {
      checkoutData.value = parsedData
      
      // Kayıtlı kullanıcıysa bilgileri doldur
      prefillUserData()
    } else {
      toast.error('Ödeme oturumunuz zaman aşımına uğradı.')
      router.push('/')
    }
  } catch (error) {
    console.error('Checkout verisi çözülemedi:', error)
    localStorage.removeItem('checkout-data')
    router.push('/')
  }
})

// Kayıtlı kullanıcı bilgilerini forma doldur
const prefillUserData = () => {
  // Kullanıcı localStorage, vuex veya api'den alınabilir
  const user = JSON.parse(localStorage.getItem('user') || '{}')
  if (user.name) {
    const nameParts = user.name.split(' ')
    if (nameParts.length > 1) {
      form.value.firstName = nameParts[0]
      form.value.lastName = nameParts.slice(1).join(' ')
    } else {
      form.value.firstName = user.name
    }
  }
  
  if (user.email) {
    form.value.email = user.email
  }
  
  if (user.phone) {
    form.value.phone = user.phone
  }
}

// Tur bilgileri - Sepetten gelen verilerle güncelleniyor
const tour = computed(() => {
  if (!checkoutData.value || !checkoutData.value.items || checkoutData.value.items.length === 0) {
    return {
      title: 'Tur bilgisi yok',
      price: 0,
      image: '/images/tours/default.jpg',
      description: '',
      date: '',
      time: '',
      meetingPoint: '',
      inclusions: [],
      exclusions: []
    }
  }

  // İlk ürünü ana tur olarak göster
  const firstItem = checkoutData.value.items[0]
  
  return {
    id: firstItem.id,
    title: firstItem.title,
    price: checkoutData.value.totalAmount,
    image: firstItem.featured_image || '/images/tours/default.jpg',
    description: firstItem.description || 'Tur açıklaması',
    date: firstItem.date || 'Tarih bilgisi yok',
    time: firstItem.time || '',
    meetingPoint: firstItem.meeting_point || 'Buluşma noktası',
    inclusions: [
      'Balon turu',
      'Ulaşım',
      'Kahvaltı',
      'Şampanya seremonisi'
    ],
    exclusions: [
      'Kişisel harcamalar',
      'Bahşişler',
      'Seyahat sigortası'
    ]
  }
})

// Taksit tutarı - Artık sadece peşin ödeme var
const installmentAmount = computed(() => {
  const total = tour.value.price * 1.18 + 10
  return total.toFixed(2)
})

// Kart önizleme için formatlı numara
const formattedCardNumberForDisplay = computed(() => {
  if (!form.value.cardNumber) return '**** **** **** ****'
  return form.value.cardNumber.padEnd(19, '*')
})

// Kart tipi belirleme (Troy kartı da ekledik)
const cardTypeIcon = computed(() => {
  const number = form.value.cardNumber.replace(/\s+/g, '')
  
  if (number.length < 4) return null
  
  if (/^4/.test(number)) {
    return 'https://cdn.jsdelivr.net/gh/aaronfagan/svg-credit-card-payment-icons/flat/visa.svg'
  } else if (/^5[1-5]/.test(number)) {
    return 'https://cdn.jsdelivr.net/gh/aaronfagan/svg-credit-card-payment-icons/flat/mastercard.svg'
  } else if (/^3[47]/.test(number)) {
    return 'https://cdn.jsdelivr.net/gh/aaronfagan/svg-credit-card-payment-icons/flat/amex.svg'
  } else if (/^9/.test(number) || /^65/.test(number)) {
    // Troy kart numaraları genellikle 9 ile başlar veya 65 ile başlayabilir
    return 'https://www.mastercard.us/content/dam/public/mastercardcom/na/us/en/consumers/find-a-card/images/troy-card-image-v2.svg'
  }
  
  return null
})

// Kart tipi adı
const cardTypeName = computed(() => {
  const number = form.value.cardNumber.replace(/\s+/g, '')
  
  if (number.length < 4) return ''
  
  if (/^4/.test(number)) {
    return 'VISA'
  } else if (/^5[1-5]/.test(number)) {
    return 'MASTERCARD'
  } else if (/^3[47]/.test(number)) {
    return 'AMEX'
  } else if (/^9/.test(number) || /^65/.test(number)) {
    return 'TROY'
  }
  
  return 'CARD'
})

// Kart numarası formatlama
const formatCardNumber = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 0) {
    value = value.match(/.{1,4}/g).join(' ')
  }
  form.value.cardNumber = value
}

// Son kullanma tarihi formatlama
const formatExpiryDate = (e) => {
  let value = e.target.value.replace(/\D/g, '')
  if (value.length > 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4)
  }
  form.value.expiryDate = value
}

// Form doğrulama
const validateForm = () => {
  errors.value = {}
  let isValid = true
  
  // Kişisel bilgiler doğrulama
  if (!form.value.firstName) {
    errors.value.firstName = 'Ad alanı zorunludur'
    isValid = false
  }
  
  if (!form.value.lastName) {
    errors.value.lastName = 'Soyad alanı zorunludur'
    isValid = false
  }
  
  if (!form.value.email) {
    errors.value.email = 'E-posta alanı zorunludur'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.value.email = 'Geçerli bir e-posta adresi girin'
    isValid = false
  }
  
  if (!form.value.phone) {
    errors.value.phone = 'Telefon alanı zorunludur'
    isValid = false
  }
  
  // Kart bilgileri doğrulama
  if (!form.value.cardNumber) {
    errors.value.cardNumber = 'Kart numarası zorunludur'
    isValid = false
  } else if (form.value.cardNumber.replace(/\s/g, '').length < 16) {
    errors.value.cardNumber = 'Geçerli bir kart numarası girin'
    isValid = false
  }
  
  if (!form.value.expiryDate) {
    errors.value.expiryDate = 'Son kullanma tarihi zorunludur'
    isValid = false
  } else if (!/^\d{2}\/\d{2}$/.test(form.value.expiryDate)) {
    errors.value.expiryDate = 'Geçerli bir son kullanma tarihi girin (AA/YY)'
    isValid = false
  } else {
    // Son kullanma tarihinin geçerliliğini kontrol et
    const [month, year] = form.value.expiryDate.split('/')
    const expMonth = parseInt(month, 10)
    const expYear = parseInt('20' + year, 10)
    
    const now = new Date()
    const currentMonth = now.getMonth() + 1
    const currentYear = now.getFullYear()
    
    if (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth)) {
      errors.value.expiryDate = 'Kartınızın süresi dolmuş'
      isValid = false
    }
  }
  
  if (!form.value.cvv) {
    errors.value.cvv = 'CVV zorunludur'
    isValid = false
  } else if (!/^\d{3}$/.test(form.value.cvv)) {
    errors.value.cvv = 'Geçerli bir CVV girin (3 rakam)'
    isValid = false
  }
  
  // Şartları kabul et
  if (!acceptTerms.value) {
    errors.value.terms = 'Şartları ve koşulları kabul etmelisiniz'
    isValid = false
  }
  
  return isValid
}

// Kart numarasını 4'lü gruplara ayırma
const cardNumberGroups = computed(() => {
  const number = form.value.cardNumber || '**** **** **** ****'
  // Kart numarasını 4'lü gruplar halinde dizi olarak döndür
  return number.replace(/\s/g, '').match(/.{1,4}/g) || ['****', '****', '****', '****']
})

// Kart sahibi adı
const cardholderName = computed(() => {
  if (form.value.firstName && form.value.lastName) {
    return `${form.value.firstName} ${form.value.lastName}`
  }
  return null
})

// Kredi kartı tipine göre arka plan rengi
const getCardBackgroundClass = computed(() => {
  const number = form.value.cardNumber.replace(/\s/g, '')
  
  if (number.length < 4) return 'bg-gradient-to-br from-gray-700 to-gray-900'
  
  if (/^4/.test(number)) {
    return 'bg-gradient-to-br from-blue-900 to-blue-600' // Visa
  } else if (/^5[1-5]/.test(number)) {
    return 'bg-gradient-to-br from-red-800 to-red-500' // Mastercard
  } else if (/^3[47]/.test(number)) {
    return 'bg-gradient-to-br from-gray-800 to-gray-600' // Amex
  } else if (/^9/.test(number) || /^65/.test(number)) {
    return 'bg-gradient-to-br from-teal-800 to-teal-500' // Troy
  }
  
  return 'bg-gradient-to-br from-gray-700 to-gray-900'
})

// Gradient rengi
const getCardGradient = computed(() => {
  const number = form.value.cardNumber.replace(/\s/g, '')
  
  if (number.length < 4) return 'from-indigo-500/20 to-purple-600/20'
  
  if (/^4/.test(number)) {
    return 'from-blue-400/20 to-blue-800/20' // Visa
  } else if (/^5[1-5]/.test(number)) {
    return 'from-red-400/20 to-red-800/20' // Mastercard
  } else if (/^3[47]/.test(number)) {
    return 'from-gray-400/20 to-gray-700/20' // Amex
  } else if (/^9/.test(number) || /^65/.test(number)) {
    return 'from-teal-400/20 to-teal-800/20' // Troy
  }
  
  return 'from-indigo-500/20 to-purple-600/20'
})

// Ödeme işlemi
const handlePayment = async () => {
  // Form doğrulama
  const isValid = validateForm();
  if (!isValid) return;
  
  // Ödeme işlemi simülasyonu
  isProcessing.value = true;
  
  try {
    // Demo amaçlı - client-side simülasyon da kullanılabilir
    // const response = await PaymentService.simulatePayment(paymentInfo.value);
    
    // Gerçek API çağrısı
    const response = await PaymentService.processPayment(form.value);
    
    if (response.success) {
      toast.success('Ödeme başarıyla tamamlandı!');
      
      // Başarılı ödeme sonrası onay sayfasına yönlendir
      router.push({
        name: 'payment-success',
        params: {
          transactionId: response.transaction_id
        },
        query: {
          amount: tour.value.price * 1.18 + 10,
          method: 'credit_card',
          installment: form.value.installment
        }
      });
    } else {
      toast.error('Ödeme işlemi başarısız oldu. Lütfen tekrar deneyin.');
    }
  } catch (err) {
    console.error('Ödeme işlemi hatası:', err);
    toast.error(err.response?.data?.message || 'Ödeme işlemi sırasında bir hata oluştu');
  } finally {
    isProcessing.value = false;
  }
};
</script>

<style scoped>
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.perspective-1000 {
  perspective: 1000px;
}

.card-3d {
  transform-style: preserve-3d;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.card-3d:hover {
  transform: rotateY(5deg) translateZ(10px);
  box-shadow: -5px 8px 25px rgba(0, 0, 0, 0.3);
}

.text-xxs {
  font-size: 0.65rem;
  line-height: 1rem;
}
</style> 