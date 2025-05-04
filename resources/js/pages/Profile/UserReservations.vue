<template>
  <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Rezervasyonlarım</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Tüm tur rezervasyonlarınızı bu sayfadan görüntüleyebilir ve yönetebilirsiniz.</p>
      </div>
      
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
      
      <!-- Rezervasyonlar Listesi -->
      <div v-if="!loading && !error && reservations.length > 0" class="space-y-8">
        <div class="overflow-hidden bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Tur Bilgisi
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Rezervasyon Tarihi
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Katılımcı Sayısı
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Toplam Tutar
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Durum
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="reservation in reservations" :key="reservation.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-md overflow-hidden">
                      <img class="h-12 w-12 object-cover" 
                           :src="getTourImage(reservation)" 
                           :alt="reservation.tour_title || reservation.tour?.title || 'Tur resmi'">
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ reservation.tour_title || reservation.tour?.title || `Tur #${reservation.tour_id}` }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ reservation.category_name || reservation.tour?.category?.name || 'Genel' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">{{ formatDate(reservation.reservation_date) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">{{ reservation.participant_count }} Kişi</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ formatPrice(reservation.total_price) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                        :class="getStatusClass(reservation.status)">
                    {{ getStatusText(reservation.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <router-link :to="`/profile/reservations/${reservation.id}`" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">
                    Detaylar
                  </router-link>
                  
                  <button v-if="canCancel(reservation)" 
                          @click="confirmCancel(reservation)"
                          class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                    İptal Et
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Boş Durum -->
      <div v-else-if="!loading && !error && reservations.length === 0" class="text-center py-16 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h2 class="mt-4 text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Henüz rezervasyonunuz yok</h2>
        <p class="mt-2 text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">
          Yeni bir tur rezervasyonu yapmak için turlarımızı inceleyin ve size uygun olanı seçin.
        </p>
        <router-link to="/tours" class="mt-6 inline-block px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
          Turları Keşfedin
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';
import { useToast } from 'vue-toastification';

const router = useRouter();
const toast = useToast();

// Durum değişkenleri
const loading = ref(true);
const error = ref(null);
const successMessage = ref('');
const reservations = ref([]);

// Verileri yükle
const fetchReservations = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    console.log('API\'den rezervasyonlar getiriliyor...');
    const response = await axios.get('/api/reservations');
    
    // API yanıtını kontrol et ve doğru veri yapısını kullan
    if (response.data && (response.data.data || Array.isArray(response.data))) {
      // Bazı API'ler data alanında veriyi döndürür, bazıları doğrudan dizi döndürür
      const reservationData = response.data.data || response.data;
      
      console.log('API yanıtı:', response.data);
      console.log('Tüm rezervasyonlar:', reservationData);
      
      if (reservationData.length === 0) {
        console.warn('API yanıtında hiç rezervasyon yok');
      }
      
      // Hiçbir filtreleme yapmadan tüm rezervasyonları göster
      reservations.value = reservationData;
      
      console.log('İşlenmiş rezervasyonlar:', reservations.value);
      
      // Eksik tour bilgilerini kontrol et ve düzeltmeler yap
      reservations.value.forEach(reservation => {
        console.log(`İşlenen rezervasyon #${reservation.id}:`, reservation);
        
        // Rezervasyonun tur bilgisi eksikse veya eksik alanlar varsa
        if (!reservation.tour) {
          reservation.tour = { 
            title: `Tur #${reservation.tour_id}`,
            featured_image: '/images/tours/default.jpg'
          };
        } else if (!reservation.tour.featured_image) {
          // Tour nesnesi var ama resim yoksa
          reservation.tour.featured_image = '/images/tours/default.jpg';
        }
        
        // Katılımcı sayısı belirtilmemişse varsayılan 1 yap
        if (!reservation.participant_count) {
          reservation.participant_count = 1;
        }
        
        // İşlem numarası yoksa rezervasyon ID'sinden oluştur
        if (!reservation.transaction_id) {
          reservation.transaction_id = `TR${reservation.id}`;
        }
      });
      
      // Tarih sırasına göre sırala - en yeni rezervasyonlar üstte
      reservations.value.sort((a, b) => {
        const dateA = new Date(a.created_at || a.reservation_date);
        const dateB = new Date(b.created_at || b.reservation_date);
        return dateB - dateA;
      });
      
      console.log('Listelenen rezervasyonlar:', reservations.value);
    } else {
      reservations.value = [];
      console.warn('API yanıtı beklenen formatta değil:', response.data);
    }
  } catch (err) {
    console.error('Rezervasyonlar yüklenirken hata oluştu:', err);
    error.value = 'Rezervasyonlarınız yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
    
    // Hata durumunda kullanıcıya daha fazla bilgi ver
    if (err.response) {
      if (err.response.status === 401) {
        error.value = 'Oturumunuz sona ermiş görünüyor. Lütfen tekrar giriş yapın.';
      } else if (err.response.status === 403) {
        error.value = 'Bu bilgilere erişim izniniz yok.';
      } else if (err.response.status === 500) {
        error.value = 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.';
      }
    }
  } finally {
    loading.value = false;
  }
};

// İptal etme fonksiyonu
const cancelReservation = async (reservationId) => {
  try {
    const response = await axios.post(`/api/reservations/${reservationId}/cancel`);
    
    // Başarılı yanıt durumunda mesajı göster ve durum bilgisini güncelle
    if (response.data.success) {
      successMessage.value = response.data.message;
      
      // Liste içindeki rezervasyonu bul ve durumunu güncelle
      const reservation = reservations.value.find(r => r.id === reservationId);
      if (reservation) {
        reservation.status = 'cancelled';
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
const confirmCancel = (reservation) => {
  if (confirm(`"${reservation.tour?.title}" turuna ait rezervasyonu iptal etmek istediğinize emin misiniz?`)) {
    cancelReservation(reservation.id);
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

const canCancel = (reservation) => {
  return reservation.status !== 'cancelled' && reservation.status !== 'completed';
};

const getTourImage = (reservation) => {
  // SQL sorgusuyla direkt gelen tur_image alanını önce kontrol et
  if (reservation.tour_image) {
    console.log('Resim tour_image alanından alınıyor:', reservation.tour_image);
    return reservation.tour_image;
  }
  
  // Tur nesnesi varsa featured_image alanını kontrol et
  if (reservation.tour && reservation.tour.featured_image) {
    console.log('Resim reservation.tour.featured_image alanından alınıyor:', reservation.tour.featured_image);
    return reservation.tour.featured_image;
  }
  
  // Tur nesnesi varsa image alanını kontrol et (alternatif alan)
  if (reservation.tour && reservation.tour.image) {
    console.log('Resim reservation.tour.image alanından alınıyor:', reservation.tour.image);
    return reservation.tour.image;
  }
  
  // Hiçbiri yoksa varsayılan resmi döndür
  console.log('Varsayılan resim kullanılıyor');
  return '/images/tours/default.jpg';
};

// Sayfa yüklendiğinde verileri getir
onMounted(() => {
  fetchReservations();
});
</script> 