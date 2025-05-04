<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Başlık ve Geri Butonu -->
      <div class="flex items-center justify-between mb-8">
        <button @click="router.back()" 
                class="flex items-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
          </svg>
          Geri
        </button>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rezervasyon Değişikliği</h1>
        <div class="w-10"></div> <!-- Boşluk için -->
      </div>

      <!-- Ana Kart -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <!-- Mevcut Rezervasyon -->
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Mevcut Rezervasyon</h2>
          <div class="flex items-center space-x-4">
            <img :src="reservation.image" :alt="reservation.title" 
                 class="w-20 h-20 rounded-lg object-cover">
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ reservation.title }}</h3>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(reservation.date) }} - {{ reservation.time }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-400">{{ reservation.participant_count }} Kişi</p>
            </div>
          </div>
        </div>

        <!-- Değişiklik Formu -->
        <div class="p-6">
          <form @submit.prevent="submitChanges">
            <!-- Tarih Seçimi -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Yeni Tarih
              </label>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <input type="date" 
                         v-model="changes.date" 
                         :min="minDate"
                         class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-gray-300">
                </div>
                <div>
                  <select v-model="changes.time" 
                          class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-gray-300">
                    <option value="">Saat Seçin</option>
                    <option value="05:00">05:00</option>
                    <option value="06:00">06:00</option>
                    <option value="07:00">07:00</option>
                    <option value="08:00">08:00</option>
                    <option value="09:00">09:00</option>
                    <option value="10:00">10:00</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Katılımcı Sayısı -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Katılımcı Sayısı
              </label>
              <div class="flex items-center space-x-4">
                <button type="button" 
                        @click="decreaseParticipants" 
                        :disabled="changes.participant_count <= 1"
                        class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300 disabled:opacity-50">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                  </svg>
                </button>
                <span class="text-lg font-medium text-gray-900 dark:text-white">{{ changes.participant_count }}</span>
                <button type="button" 
                        @click="increaseParticipants" 
                        :disabled="changes.participant_count >= 10"
                        class="w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300 disabled:opacity-50">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Ödeme Seçenekleri -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Ödeme Seçenekleri
              </label>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <label v-for="option in paymentOptions" :key="option.value" 
                       class="relative flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg cursor-pointer">
                  <input type="radio" 
                         v-model="changes.paymentOption" 
                         :value="option.value" 
                         class="sr-only">
                  <div class="flex items-center justify-between w-full">
                    <div>
                      <p class="text-sm font-medium text-gray-900 dark:text-white">{{ option.label }}</p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">{{ option.description }}</p>
                    </div>
                    <div class="flex items-center">
                      <span class="text-sm font-medium text-gray-900 dark:text-white">
                        ₺{{ formatPrice(option.amount) }}
                      </span>
                      <div class="ml-2 w-4 h-4 border-2 border-gray-300 dark:border-gray-500 rounded-full"></div>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            <!-- Değişiklik Ücreti -->
            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm font-medium text-yellow-800 dark:text-yellow-400">Değişiklik Ücreti</p>
                  <p class="text-xs text-yellow-600 dark:text-yellow-500">Rezervasyon değişikliği için ek ücret alınacaktır.</p>
                </div>
                <span class="text-lg font-semibold text-yellow-800 dark:text-yellow-400">
                  ₺{{ formatPrice(changeFee) }}
                </span>
              </div>
            </div>

            <!-- Toplam Tutar -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-gray-900 dark:text-white">Toplam Tutar</p>
                <span class="text-lg font-semibold text-gray-900 dark:text-white">
                  ₺{{ formatPrice(totalAmount) }}
                </span>
              </div>
            </div>

            <!-- Butonlar -->
            <div class="flex flex-col sm:flex-row gap-4">
              <button type="submit" 
                      class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Değişiklikleri Onayla
              </button>
              <button type="button" 
                      @click="router.back()" 
                      class="px-6 py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-medium rounded-lg transition-colors">
                İptal
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const reservation = ref(null)
const changes = ref({
  date: '',
  time: '',
  participant_count: 1,
  paymentOption: 'full'
})

// Örnek rezervasyon verisi
const sampleReservation = {
  id: 1,
  title: 'Kapadokya Balon Turu',
  date: '2024-04-15',
  time: '05:00',
  location: 'Kapadokya Balon Kalkış Noktası',
  amount: '2960.00',
  installment: 1,
  status: 'active',
  transactionId: 'TR202403120001',
  image: 'https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg',
  participant_count: 2,
  description: 'Kapadokya\'nın eşsiz manzarasını balonla keşfedin. Güneşin doğuşunu izleyerek unutulmaz bir deneyim yaşayın.',
  inclusions: ['Balon uçuşu', 'Transfer', 'Kahvaltı', 'Sertifika'],
  exclusions: ['Kişisel harcamalar', 'Ekstra aktiviteler'],
  cancellationPolicy: 'Tur tarihinden 48 saat öncesine kadar ücretsiz iptal hakkı vardır.'
}

// Ödeme seçenekleri
const paymentOptions = [
  {
    value: 'full',
    label: 'Peşin Ödeme',
    description: 'Tek seferde ödeyin',
    amount: 2960
  },
  {
    value: 'installment2',
    label: '2 Taksit',
    description: '2 eşit taksitte ödeyin',
    amount: 1480
  },
  {
    value: 'installment3',
    label: '3 Taksit',
    description: '3 eşit taksitte ödeyin',
    amount: 986.67
  }
]

// Minimum tarih (bugün)
const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

// Değişiklik ücreti
const changeFee = 100

// Toplam tutar
const totalAmount = computed(() => {
  const baseAmount = parseFloat(reservation.value?.amount || 0)
  return baseAmount + changeFee
})

// Katılımcı sayısını artır
const increaseParticipants = () => {
  if (changes.value.participant_count < 10) {
    changes.value.participant_count++
  }
}

// Katılımcı sayısını azalt
const decreaseParticipants = () => {
  if (changes.value.participant_count > 1) {
    changes.value.participant_count--
  }
}

// Tarih formatla
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

// Fiyat formatla
const formatPrice = (price) => {
  return parseFloat(price).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

// Değişiklikleri gönder
const submitChanges = () => {
  if (confirm('Rezervasyon değişikliklerini onaylıyor musunuz?')) {
    // API'ye değişiklik isteği gönder
    alert('Rezervasyon başarıyla güncellendi!')
    router.push(`/profile/reservations/${reservation.value.id}`)
  }
}

// Sayfa yüklendiğinde
onMounted(() => {
  // API'den rezervasyon detaylarını al
  // Şimdilik örnek veriyi kullanıyoruz
  reservation.value = sampleReservation
  changes.value.participant_count = reservation.value.participant_count
})
</script> 