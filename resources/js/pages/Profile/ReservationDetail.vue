<template>
  <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Yükleniyor İndikatörü -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-primary-500 border-solid"></div>
        <span class="ml-3 text-gray-600 dark:text-gray-400">Yükleniyor...</span>
      </div>

      <!-- Hata Mesajı -->
      <div v-else-if="error" class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6 rounded-md shadow-sm">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm">{{ error }}</p>
            <div class="mt-2">
              <router-link to="/profile/reservations" class="text-sm font-medium text-red-800 dark:text-red-300 hover:underline">
                Rezervasyonlarıma Dön
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Başarı Mesajı -->
      <div v-if="successMessage" class="bg-green-100 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-400 p-4 mb-6 rounded-md shadow-sm">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm">{{ successMessage }}</p>
          </div>
        </div>
      </div>

      <!-- Rezervasyon Detayları -->
      <div v-if="!loading && !error && reservation" class="space-y-6">
        <div class="mb-8">
          <router-link to="/profile/reservations" class="flex items-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Rezervasyonlarıma Dön
          </router-link>
          
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Rezervasyon Detayları</h1>
          <p class="text-gray-600 dark:text-gray-400">Rezervasyon ID: #{{ reservation.id }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
          <!-- Rezervasyon Durumu -->
          <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
              <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Rezervasyon Durumu</span>
              <span class="px-3 py-1 text-xs font-semibold rounded-full" :class="getStatusClass(reservation.status)">
                {{ getStatusText(reservation.status) }}
              </span>
            </div>
          </div>
          
          <!-- Tur Bilgileri -->
          <div class="p-6">
            <div class="flex flex-col sm:flex-row">
              <div class="w-full sm:w-1/3 mb-4 sm:mb-0 sm:pr-4">
                <div class="rounded-lg overflow-hidden h-40">
                  <img :src="reservation.tour?.featured_image || reservation.tour?.image || '/images/tours/default.jpg'" 
                      :alt="reservation.tour?.title" 
                      class="w-full h-full object-cover"
                  >
                </div>
              </div>
              <div class="w-full sm:w-2/3">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ reservation.tour?.title }}</h2>
                <div class="mb-4 text-sm">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                    {{ reservation.tour?.category?.name || 'Genel' }}
                  </span>
                  <span class="ml-2 text-gray-600 dark:text-gray-400">{{ reservation.tour?.location }}</span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">{{ reservation.tour?.description }}</p>
                <router-link :to="`/tour/${reservation.tour?.id}`" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                  Tur detaylarını görüntüle
                </router-link>
              </div>
            </div>
          </div>
          
          <!-- Rezervasyon Detayları -->
          <div class="border-t border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rezervasyon Bilgileri</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <div class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Rezervasyon Tarihi</span>
                  <span class="text-gray-900 dark:text-white">{{ formatDate(reservation.reservation_date) }}</span>
                </div>
                
                <div class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Katılımcı Sayısı</span>
                  <span class="text-gray-900 dark:text-white">{{ reservation.participant_count }} Kişi</span>
                </div>
                
                <div class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Ödeme Yöntemi</span>
                  <span class="text-gray-900 dark:text-white">
                    {{ getPaymentMethodText(reservation.payment_method) }}
                  </span>
                </div>
              </div>
              
              <div>
                <div class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Rezervasyon Oluşturma Tarihi</span>
                  <span class="text-gray-900 dark:text-white">{{ formatDateTime(reservation.created_at) }}</span>
                </div>
                
                <div v-if="reservation.special_requests" class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Özel İstekler</span>
                  <span class="text-gray-900 dark:text-white">{{ reservation.special_requests }}</span>
                </div>
                
                <div class="mb-4">
                  <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Toplam Tutar</span>
                  <span class="text-xl font-bold text-gray-900 dark:text-white">{{ formatPrice(reservation.total_price) }}</span>
                </div>
              </div>
            </div>
          </div>
          
          <!-- İşlemler -->
          <div class="border-t border-gray-200 dark:border-gray-700 p-6">
            <div class="flex justify-end">
              <button 
                v-if="canCancel(reservation)"
                @click="confirmCancel"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-sm transition-colors"
              >
                Rezervasyonu İptal Et
              </button>
            </div>
          </div>
        </div>
        
        <!-- Bilgilendirme Notu -->
        <div class="mt-8 bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 text-sm text-blue-700 dark:text-blue-300">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
              </svg>
            </div>
            <div class="ml-3">
              <p>Rezervasyon iptali en az 48 saat öncesinde yapılmalıdır. İptal politikamız gereğince, tur tarihine 48 saatten az kala yapılan iptallerde geri ödeme yapılmamaktadır.</p>
              <p class="mt-2">Sorularınız için <router-link to="/contact" class="font-medium underline">iletişim sayfamızı</router-link> ziyaret edebilirsiniz.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const route = useRoute();
const router = useRouter();
const toast = useToast();

// Durum değişkenleri
const loading = ref(true);
const error = ref(null);
const successMessage = ref('');
const reservation = ref(null);

// Verileri yükle
const fetchReservation = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const response = await axios.get(`/api/reservations/${route.params.id}`);
    reservation.value = response.data.data;
  } catch (err) {
    console.error('Rezervasyon detayları yüklenirken hata oluştu:', err);
    
    let errorMessage = 'Rezervasyon detayları yüklenirken bir hata oluştu.';
    if (err.response && err.response.status === 404) {
      errorMessage = 'Rezervasyon bulunamadı veya bu rezervasyona erişim izniniz yok.';
    } else if (err.response && err.response.data && err.response.data.message) {
      errorMessage = err.response.data.message;
    }
    
    error.value = errorMessage;
  } finally {
    loading.value = false;
  }
};

// İptal etme fonksiyonu
const cancelReservation = async () => {
  try {
    const response = await axios.post(`/api/reservations/${route.params.id}/cancel`);
    
    // Başarılı yanıt durumunda mesajı göster ve durum bilgisini güncelle
    if (response.data.success) {
      successMessage.value = response.data.message;
      
      // Rezervasyon durumunu güncelle
      if (reservation.value) {
        reservation.value.status = 'cancelled';
      }
      
      // Bildirim göster
      toast.success('Rezervasyonunuz başarıyla iptal edildi.');
      
      // 5 saniye sonra başarı mesajını temizle
      setTimeout(() => {
        successMessage.value = '';
      }, 5000);
    }
  } catch (err) {
    console.error('Rezervasyon iptal edilirken hata oluştu:', err);
    
    let errorMessage = 'Rezervasyon iptal edilirken bir hata oluştu.';
    if (err.response && err.response.data && err.response.data.message) {
      errorMessage = err.response.data.message;
    }
    
    error.value = errorMessage;
    toast.error(errorMessage);
    
    // 5 saniye sonra hata mesajını temizle
    setTimeout(() => {
      error.value = null;
    }, 5000);
  }
};

// İptal onayı
const confirmCancel = () => {
  if (confirm(`"${reservation.value.tour?.title}" turuna ait rezervasyonu iptal etmek istediğinize emin misiniz?`)) {
    cancelReservation();
  }
};

// Yardımcı fonksiyonlar
const formatDate = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('tr-TR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date);
};

const formatDateTime = (dateString) => {
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('tr-TR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date);
};

const formatPrice = (price) => {
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price);
};

const getStatusText = (status) => {
  switch (status) {
    case 'confirmed': return 'Onaylandı';
    case 'pending': return 'Bekliyor';
    case 'cancelled': return 'İptal Edildi';
    case 'completed': return 'Tamamlandı';
    default: return status;
  }
};

const getStatusClass = (status) => {
  switch (status) {
    case 'confirmed':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
    case 'pending':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
    case 'cancelled':
      return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
    case 'completed':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
  }
};

const getPaymentMethodText = (method) => {
  switch (method) {
    case 'credit_card': return 'Kredi Kartı';
    case 'bank_transfer': return 'Banka Havalesi';
    case 'cash': return 'Nakit';
    default: return method;
  }
};

const canCancel = (reservation) => {
  return reservation.status !== 'cancelled' && reservation.status !== 'completed';
};

// Sayfa yüklendiğinde verileri getir
onMounted(() => {
  fetchReservation();
});
</script> 