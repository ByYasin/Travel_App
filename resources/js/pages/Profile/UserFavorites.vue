<template>
  <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">Favori Turlarım</h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Beğendiğiniz ve daha sonra incelemek istediğiniz tüm turları burada bulabilirsiniz.</p>
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
      
      <!-- Favoriler -->
      <div v-if="!loading && !error && favorites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="tour in favorites" :key="tour.id" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full transform hover:-translate-y-1">
          <!-- Tur Görseli -->
          <div class="relative overflow-hidden h-52">
            <img 
              :src="tour.featured_image || tour.image || '/images/tours/default.jpg'" 
              :alt="tour.title" 
              class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
            />
            <span v-if="tour.category" class="absolute top-3 left-3 bg-primary-600 text-white text-xs font-semibold px-2 py-1 rounded-md shadow-md">
              {{ tour.category.name }}
            </span>
            <span class="absolute top-3 right-3 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-md shadow-md">
              {{ tour.duration }}
            </span>
            <!-- Favori Kaldır Butonu -->
            <button 
              @click="removeFavorite(tour.id)"
              class="absolute bottom-3 right-3 p-2 rounded-full bg-white dark:bg-gray-800 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-red-500"
              title="Favorilerden çıkar"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>
          
          <!-- Tur Bilgileri -->
          <div class="p-4 flex-grow flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
              {{ tour.title }}
            </h3>
            <div class="mt-1 mb-2 flex items-center">
              <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ tour.location }}
              </div>
              <div v-if="tour.average_rating" class="flex items-center text-sm text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                {{ formatRating(tour.average_rating) }}
              </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
              {{ tour.description }}
            </p>
            
            <div class="mt-auto">
              <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                <div class="font-semibold text-gray-900 dark:text-white text-lg">
                  {{ formatPrice(tour.price) }}
                </div>
                <router-link 
                  :to="`/tour/${tour.id}`" 
                  class="px-3 py-1.5 text-sm bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center"
                >
                  Detaylar
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Boş Durum -->
      <div v-else-if="!loading && !error && favorites.length === 0" class="text-center py-16 bg-gray-50 dark:bg-gray-800 rounded-lg shadow">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <h2 class="mt-4 text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Henüz favori turunuz yok</h2>
        <p class="mt-2 text-gray-500 dark:text-gray-400 mb-4 max-w-md mx-auto">Beğendiğiniz turları favorilere ekleyerek daha sonra kolayca erişebilirsiniz.</p>
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
const favorites = ref([]);

// Favorileri yükle
const fetchFavorites = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const response = await axios.get('/api/favorites');
    favorites.value = response.data.data;
  } catch (err) {
    console.error('Favoriler yüklenirken hata oluştu:', err);
    error.value = 'Favorileriniz yüklenirken bir hata oluştu. Lütfen daha sonra tekrar deneyin.';
  } finally {
    loading.value = false;
  }
};

// Favori kaldırma
const removeFavorite = async (tourId) => {
  try {
    const response = await axios.delete(`/api/favorites/remove/${tourId}`);
    
    if (response.data.success) {
      // Listeyi güncelle
      favorites.value = favorites.value.filter(tour => tour.id !== tourId);
      
      // Mesajı göster
      successMessage.value = response.data.message || 'Tur favorilerinizden kaldırıldı.';
      toast.success(successMessage.value);
      
      // 5 saniye sonra mesajı kaldır
      setTimeout(() => {
        successMessage.value = '';
      }, 5000);
    }
  } catch (err) {
    console.error('Favori kaldırılırken hata oluştu:', err);
    
    let errorMessage = 'Favori kaldırılırken bir hata oluştu.';
    if (err.response && err.response.data && err.response.data.message) {
      errorMessage = err.response.data.message;
    }
    
    error.value = errorMessage;
    toast.error(errorMessage);
    
    // 5 saniye sonra hata mesajını kaldır
    setTimeout(() => {
      error.value = null;
    }, 5000);
  }
};

// Yardımcı fonksiyonlar
const formatPrice = (price) => {
  if (!price) return '0 ₺';
  
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price);
};

const formatRating = (rating) => {
  if (!rating) return '0.0';
  return parseFloat(rating).toFixed(1);
};

// Sayfa yüklendiğinde favorileri getir
onMounted(() => {
  fetchFavorites();
});
</script> 