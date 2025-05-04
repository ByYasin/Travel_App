<template>
  <div class="min-h-screen">
    <PageHeader 
      title="Unutulmaz Turlar" 
      subtitle="Hayatınızın en güzel anılarını biriktireceğiniz benzersiz deneyimler"
      background-image="https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=1200"
    >
      <template #actions>
        <button 
          @click="scrollToElement('filter')" 
          class="px-6 py-3 bg-white text-primary-600 rounded-lg shadow-md hover:bg-gray-100 transition-colors font-medium flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
          </svg>
          Turları Filtrele
        </button>
        <button 
          @click="scrollToElement('tours')" 
          class="px-6 py-3 bg-primary-600 text-white rounded-lg shadow-md hover:bg-primary-700 transition-colors font-medium flex items-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
          </svg>
          Keşfetmeye Başla
        </button>
      </template>
    </PageHeader>

    <!-- Mobil Filtre Butonu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <button 
        @click="showFilters = !showFilters"
        class="lg:hidden w-full mb-6 flex items-center justify-center gap-2 py-3 px-4 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        {{ showFilters ? 'Filtreleri Gizle' : 'Filtreleri Göster' }}
      </button>
    </div>

    <PageSection variant="default">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filtreleme Paneli -->
        <div id="filter" class="w-full lg:w-1/4" :class="{ 'hidden lg:block': !showFilters }">
          <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-md mb-8 sticky top-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Mükemmel Turu Bul
            </h2>
            
            <div class="space-y-5">
              <!-- Bölge Filtresi -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bölge</label>
                <select v-model="filters.region" 
                        @change="handleFilterChange"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                  <option value="">Tüm Bölgeler</option>
                  <option v-for="region in regions" :key="region" :value="region">{{ region }}</option>
                </select>
              </div>

              <!-- Tur Tipi Filtresi -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tur Tipi</label>
                <select v-model="filters.type" 
                        @change="handleFilterChange"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                  <option value="">Tüm Turlar</option>
                  <option v-for="type in tourTypes" :key="type" :value="type">{{ type }}</option>
                </select>
              </div>

              <!-- Süre Filtresi -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Süre</label>
                <select v-model="filters.duration" 
                        @change="handleFilterChange"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                  <option value="">Tüm Süreler</option>
                  <option v-for="duration in durations" :key="duration" :value="duration">{{ duration }}</option>
                </select>
              </div>

              <!-- Fiyat Aralığı -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fiyat Aralığı</label>
                <select v-model="filters.priceRange" 
                        @change="handleFilterChange"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                  <option value="">Tüm Fiyatlar</option>
                  <option v-for="range in priceRanges" :key="range.value" :value="range.value">{{ range.label }}</option>
                </select>
              </div>

              <!-- Sıralama -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sıralama</label>
                <select v-model="filters.sortBy" 
                        @change="handleFilterChange"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                  <option v-for="option in sortOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
                </select>
              </div>

              <!-- Filtreleri Sıfırla -->
              <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <button @click="resetFilters" 
                        class="w-full flex items-center justify-center gap-2 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  Filtreleri Sıfırla
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Tur Kartları -->
        <div class="w-full lg:w-3/4">
          <!-- Yükleniyor animasyonu -->
          <div v-if="isLoading" class="flex flex-col items-center justify-center py-12">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-primary-500 border-solid"></div>
            <p class="mt-4 text-gray-600 dark:text-gray-300">Turlar yükleniyor...</p>
          </div>
          
          <!-- Boş durum -->
          <div v-else-if="tours.length === 0" class="col-span-full text-center py-10">
            <div class="mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-700 dark:text-gray-300 mb-2">Hiç tur bulunamadı</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Lütfen filtrelerinizi değiştirerek tekrar deneyin.</p>
            <button @click="resetFilters" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
              Filtreleri Sıfırla
            </button>
          </div>
          
          <!-- Tur listesi -->
          <div id="tours" v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Tur Kartı -->
            <div 
              v-for="tour in tours" 
              :key="tour.id" 
              class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full transform hover:-translate-y-1"
            >
              <!-- Tur Görseli -->
              <div class="relative overflow-hidden h-52">
                <img 
                  :src="tour.featured_image || tour.image || '/images/tours/default.jpg'" 
                  :alt="tour.title" 
                  class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                />
                <span class="absolute top-3 left-3 bg-primary-600 text-white text-xs font-semibold px-2 py-1 rounded-md shadow-md">
                  {{ tour.category_name || tour.category || 'Kategorisiz' }}
                </span>
                <span class="absolute top-3 right-3 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-xs font-semibold px-2 py-1 rounded-md shadow-md">
                  {{ tour.duration }}
                </span>
                <!-- Favori Butonu -->
                <form 
                  @submit.prevent="toggleFavorite(tour)" 
                  class="absolute bottom-3 right-3"
                >
                  <input type="hidden" name="_token" :value="csrfToken">
                  <input type="hidden" name="tour_id" :value="tour.id">
                  <button 
                    type="submit"
                    class="p-2 rounded-full bg-white dark:bg-gray-800 shadow-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    :class="[tour.is_favorited ? 'text-red-500' : 'text-gray-400 dark:text-gray-500 hover:text-red-500 dark:hover:text-red-500']"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" :fill="tour.is_favorited ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </button>
                </form>
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
                    {{ typeof tour.average_rating === 'number' ? tour.average_rating.toFixed(1) : tour.average_rating }}
                  </div>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-3">
                  {{ tour.description }}
                </p>
                
                <!-- Dahil Olanlar -->
                <div v-if="tour.included" class="mt-1 mb-3">
                  <span class="text-xs text-gray-500 dark:text-gray-400 mb-1 block">Dahil Olanlar:</span>
                  <div class="flex flex-wrap gap-1">
                    <span 
                      v-for="(item, index) in sliceIncluded(tour.included)" 
                      :key="index"
                      class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-0.5 rounded-full"
                    >
                      {{ item }}
                    </span>
                    <span 
                      v-if="hasMoreIncluded(tour.included)" 
                      class="text-xs text-primary-600 dark:text-primary-400"
                    >
                      +{{ getIncludedItemsCount(tour.included) - getIncludedLimit() }} daha
                    </span>
                  </div>
                </div>
                
                <div class="mt-auto">
                  <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="font-semibold text-gray-900 dark:text-white text-lg">
                      {{ tour.price.toLocaleString('tr-TR') }} ₺
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
          
          <!-- Sayfalama -->
          <div v-if="!isLoading && tours.length > 0" class="mt-8 flex justify-center items-center space-x-2">
            <button 
              @click="changePage(1)" 
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
              :disabled="currentPage === 1"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
              </svg>
            </button>
            <button 
              @click="changePage(currentPage - 1)" 
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :class="{'opacity-50 cursor-not-allowed': currentPage === 1}"
              :disabled="currentPage === 1"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            
            <div class="flex space-x-1">
              <button 
                v-for="page in totalPages" 
                :key="page" 
                @click="changePage(page)" 
                class="w-10 h-10 flex items-center justify-center rounded-lg transition-colors"
                :class="page === currentPage ? 
                  'bg-primary-600 text-white' : 
                  'border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
              >
                {{ page }}
              </button>
            </div>
            
            <button 
              @click="changePage(currentPage + 1)" 
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
              :disabled="currentPage === totalPages"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
            <button 
              @click="changePage(totalPages)" 
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :class="{'opacity-50 cursor-not-allowed': currentPage === totalPages}"
              :disabled="currentPage === totalPages"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </PageSection>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/store/auth'
import axios from 'axios'
import PageHeader from '@/components/PageHeader.vue'
import PageSection from '@/components/PageSection.vue'
import apiService from '@/services/apiService'

// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Auth store (kullanıcı giriş durumu için)
const authStore = useAuthStore()
const toast = useToast()

// Smooth scroll için hash link fonksiyonu
const scrollToElement = (elementId) => {
  const element = document.getElementById(elementId);
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}

// Yükleme durumu
const isLoading = ref(true)
const error = ref(null)

// Pagination (Sayfalama)
const currentPage = ref(1)
const itemsPerPage = 6
const totalTours = ref(0)
const totalPages = computed(() => Math.ceil(totalTours.value / itemsPerPage))

// Yeniden yükleme durumu
const forceRefresh = ref(false);

// API'den turları getir
const fetchTours = async (page = currentPage.value, apiFilters = null) => {
  isLoading.value = true;
  error.value = null;
  
  try {
    // Filtre parametrelerini oluştur
    const params = new URLSearchParams();
    
    // Sayfalama parametreleri
    params.append('page', page);
    params.append('per_page', itemsPerPage);
    
    // Filtreleme parametreleri
    if (apiFilters) {
      if (apiFilters.region) params.append('region', apiFilters.region);
      if (apiFilters.type) params.append('type', apiFilters.type);
      if (apiFilters.duration) params.append('duration', apiFilters.duration);
      if (apiFilters.price) params.append('price', apiFilters.price);
      if (apiFilters.sort) params.append('sort', apiFilters.sort);
    }
    
    // API isteğini logla
    console.log('API İsteği:', `/tours?${params.toString()}`);
    
    // Ön bellek ve performans iyileştirmeleri
    const cacheKey = params.toString();
    const cachedData = sessionStorage.getItem(`tours_${cacheKey}`);
    
    if (cachedData && !forceRefresh.value) {
      // Önbellekten veri kullanma
      const parsedData = JSON.parse(cachedData);
      tours.value = parsedData.data;
      totalTours.value = parsedData.total;
      totalPages.value = parsedData.last_page;
      isLoading.value = false;
      
      // Önbellek kullanıldığında hızlı yükleme sağla
      setTimeout(() => {
        console.log('Cached data loaded from sessionStorage');
      }, 0);
      
      // Kullanıcı giriş yaptıysa, favorileri kontrol et
      if (authStore.isLoggedIn) {
        await checkFavoriteStatuses();
      }
      
      return;
    }
    
    // İstek yap (apiService ile)
    const response = await apiService.get(`/tours`, Object.fromEntries(params));
    
    // Yanıtı işle
    if (response && response.data) {
      tours.value = response.data;
      totalTours.value = response.total;
      totalPages.value = response.last_page;
      
      // Veriyi önbelleğe al
      sessionStorage.setItem(`tours_${cacheKey}`, JSON.stringify(response));
      
      // Kullanıcı giriş yaptıysa, favorileri kontrol et
      if (authStore.isLoggedIn) {
        await checkFavoriteStatuses();
      }
    } else {
      throw new Error('API yanıtı geçersiz');
    }
  } catch (err) {
    console.error('Error fetching tours:', err);
    error.value = 'Turlar yüklenirken bir hata oluştu.';
  } finally {
    isLoading.value = false;
    forceRefresh.value = false;
  }
};

// Sayfa değiştirme
const changePage = (page) => {
  if (page === currentPage.value) return;
  
  // Sayfayı güncelle
  currentPage.value = page;
  
  // Sayfanın en üstüne git (smooth scroll)
  const toursElement = document.getElementById('tours');
  if (toursElement) {
    toursElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  
  // Turları yükle
  fetchTours(page);
};

// Filtreleme değişkenleri
const showFilters = ref(false)
const filters = ref({
  region: '',
  type: '',
  duration: '',
  priceRange: '',
  sortBy: 'newest'
})

// Bölgeler
const regions = ref([
  'Akdeniz',
  'Ege',
  'Marmara',
  'Karadeniz',
  'İç Anadolu',
  'Doğu Anadolu',
  'Güneydoğu Anadolu'
])

// Tur tipleri
const tourTypes = ref([
  'Kültür Turu',
  'Doğa Turu',
  'Deniz Turu',
  'Yemek Turu'
])

// Süreler
const durations = ref([
  '1 Gün',
  '2-3 Gün',
  '4-7 Gün',
  '8+ Gün'
])

// Fiyat aralıkları
const priceRanges = ref([
  { label: '0 - 500 TL', value: '0-500' },
  { label: '501 - 1000 TL', value: '501-1000' },
  { label: '1001 - 2000 TL', value: '1001-2000' },
  { label: '2000+ TL', value: '2000+' }
])

// Sıralama seçenekleri
const sortOptions = ref([
  { label: 'En Yeni', value: 'newest' },
  { label: 'En Eski', value: 'oldest' },
  { label: 'En Düşük Fiyat', value: 'price-low' },
  { label: 'En Yüksek Fiyat', value: 'price-high' },
  { label: 'En Yüksek Puan', value: 'rating' },
  { label: 'En Popüler', value: 'popularity' }
])

// Tur verileri
const tours = ref([]);

// Filtreleri uygula ve turları yeniden yükle
const applyFilters = async () => {
  // URL arama parametrelerini güncelle
  const queryParams = new URLSearchParams();
  
  if (filters.value.region) queryParams.set('region', filters.value.region);
  if (filters.value.type) queryParams.set('type', filters.value.type);
  if (filters.value.duration) queryParams.set('duration', filters.value.duration);
  if (filters.value.priceRange) queryParams.set('price', filters.value.priceRange);
  if (filters.value.sortBy) queryParams.set('sort', filters.value.sortBy);
  
  // URL'i güncelle, geçmişi değiştirmeden
  const queryString = queryParams.toString();
  const newUrl = queryString ? `${window.location.pathname}?${queryString}` : window.location.pathname;
  window.history.replaceState({}, '', newUrl);
  
  // API filtreleri oluştur
  const apiFilters = {
    region: filters.value.region,
    type: filters.value.type,
    duration: filters.value.duration,
    price: filters.value.priceRange,
    sort: filters.value.sortBy
  };
  
  // Turları filtrelerle birlikte getir ve 1. sayfaya dön
  await fetchTours(1, apiFilters);
};

// Filtreleri değiştiğinde API'yi çağır
const handleFilterChange = () => {
  applyFilters();
};

// Filtreleri sıfırla
const resetFilters = () => {
  filters.value = {
    region: '',
    type: '',
    duration: '',
    priceRange: '',
    sortBy: 'newest'
  };
  
  // Filtreleri sıfırla ve turları yeniden getir
  applyFilters();
  
  // 250ms sonra yumuşak geçişle tours bölümüne kaydır
  setTimeout(() => {
    scrollToElement('tours');
  }, 250);
};

// Tur detay sayfasına yönlendirme
const router = useRouter();
const goToTourDetail = (tourId) => {
  // Geçiş animasyonu için 100ms bekle
  isLoading.value = true;
  setTimeout(() => {
    isLoading.value = false;
    router.push(`/tour/${tourId}`);
  }, 300);
};

// Dahil olanları dilimle - İşlev yardımcı fonksiyonu
const sliceIncluded = (included) => {
  if (!included) return [];
  
  // Veri diziden string'e dönüşümünü kontrol et
  let includedArray = included;
  
  if (typeof included === 'string') {
    try {
      includedArray = JSON.parse(included);
    } catch (e) {
      console.error('Included parsing error:', e);
      return [];
    }
  }
  
  // Dizi değilse boş dizi döndür
  if (!Array.isArray(includedArray)) return [];
  
  return includedArray.slice(0, getIncludedLimit());
};

// Dahil olanların limitini getir
const getIncludedLimit = () => {
  return window.innerWidth < 640 ? 1 : 2; // Mobilde 1, masaüstünde 2 öğe göster
};

// Dahil olanlarda daha fazla var mı kontrolü
const hasMoreIncluded = (included) => {
  if (!included) return false;
  
  let includedArray = included;
  
  if (typeof included === 'string') {
    try {
      includedArray = JSON.parse(included);
    } catch (e) {
      console.error('Included parsing error:', e);
      return false;
    }
  }
  
  if (!Array.isArray(includedArray)) return false;
  
  return includedArray.length > getIncludedLimit();
};

// Dahil olanlar sayısını getir
const getIncludedItemsCount = (included) => {
  if (!included) return 0;
  
  let includedArray = included;
  
  if (typeof included === 'string') {
    try {
      includedArray = JSON.parse(included);
    } catch (e) {
      console.error('Included parsing error:', e);
      return 0;
    }
  }
  
  return Array.isArray(includedArray) ? includedArray.length : 0;
};

// Favori işlemleri
const toggleFavorite = async (tour) => {
  try {
    // Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
    if (!authStore.isLoggedIn) {
      toast.info('Favorilere eklemek için giriş yapmalısınız');
      router.push(`/login?redirect=/tours`);
      return;
    }
    
    // UI'ı hemen güncelle (optimistic update)
    tour.is_favorited = !tour.is_favorited;
    
    // Form verisi oluştur
    const formData = new FormData();
    formData.append('_token', csrfToken);
    
    // AJAX isteği gönder (apiService kullanarak)
    const response = await apiService.post(`/favorites/toggle/${tour.id}`, { _token: csrfToken });
    
    if (response && response.success) {
      // Sunucudan gelen gerçek durumu güncelle
      tour.is_favorited = response.is_favorited;
      
      toast.success(response.message);
    } else {
      // Hata durumunda UI'ı eski haline çevir
      tour.is_favorited = !tour.is_favorited;
      toast.error(response.message || 'Favori işlemi sırasında bir hata oluştu');
    }
  } catch (error) {
    console.error('Favori işlemi sırasında hata oluştu:', error);
    
    // Hata durumunda UI'ı eski haline çevir
    if (tour) {
      tour.is_favorited = !tour.is_favorited;
    }
    
    toast.error('Favori işlemi sırasında bir hata oluştu');
  }
};

// Kullanıcı giriş yapmışsa, turlar yüklendiğinde favori durumlarını kontrol et
const checkFavoriteStatuses = async () => {
  try {
    // Eğer kullanıcı giriş yapmamışsa veya turlar yüklenmediyse işlem yapma
    if (!authStore.isLoggedIn || !tours.value || !Array.isArray(tours.value) || tours.value.length === 0) {
      return;
    }
    
    // Tours zaten API'den gelirken is_favorited özelliği ile geliyor olmalı
    // Ancak bazen API'den bu veriyi alamayabiliriz
    // Bu durumda varsayılan değer olarak false atayalım
    for (const tour of tours.value) {
      if (tour && !('is_favorited' in tour)) {
        tour.is_favorited = false;
      }
    }
  } catch (error) {
    console.error('Favori durumları kontrol edilirken hata oluştu:', error);
  }
};

// Turlar yüklendiğinde favori durumlarını kontrol et
watch(() => tours.value, async (newTours) => {
  if (newTours && newTours.length > 0) {
    await checkFavoriteStatuses();
  }
}, { deep: true });

// Kullanıcı oturumu değiştiğinde favori durumlarını güncelle
watch(() => authStore.isLoggedIn, async (isLoggedIn) => {
  if (isLoggedIn && tours.value && tours.value.length > 0) {
    await checkFavoriteStatuses();
  } else if (!isLoggedIn && tours.value && Array.isArray(tours.value)) {
    // Kullanıcı çıkış yapmışsa tüm turları favoriden çıkar
    tours.value.forEach(tour => {
      if (tour) {
        tour.is_favorited = false;
      }
    });
  }
});

onMounted(async () => {
  // URL parametrelerini al
  const urlParams = new URLSearchParams(window.location.search);
  
  // URL'deki parametreleri filtrelere uygula
    if (urlParams.has('region')) {
    filters.value.region = urlParams.get('region');
    }
    if (urlParams.has('type')) {
    filters.value.type = urlParams.get('type');
  }
  if (urlParams.has('duration')) {
    filters.value.duration = urlParams.get('duration');
    }
    if (urlParams.has('price')) {
    filters.value.priceRange = urlParams.get('price');
  }
  if (urlParams.has('sort')) {
    filters.value.sortBy = urlParams.get('sort');
  }
  if (urlParams.has('page')) {
    currentPage.value = parseInt(urlParams.get('page'));
  }
  
  // İlk yüklemede API'den taze veri almak için forceRefresh'i true yapalım
  forceRefresh.value = true;
  
  // Filtreleri uygulayarak turları getir
  await fetchTours(currentPage.value, {
    region: filters.value.region,
    type: filters.value.type,
    duration: filters.value.duration,
    price: filters.value.priceRange,
    sort: filters.value.sortBy
  });
  
  // Sonraki istekler için forceRefresh'i false yapalım
  forceRefresh.value = false;

  // İlk yüklemede favori durumlarını kontrol et
  if (authStore.isLoggedIn && tours.value && tours.value.length > 0) {
    await checkFavoriteStatuses();
  }
});
</script>

<style scoped>
.rotate-icon {
  transform: rotate(0deg);
  transition: transform 0.3s;
}

.rotate-icon.active {
  transform: rotate(180deg);
}

.truncate-text {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.tag {
  transition: all 0.2s;
}

.tag:hover {
  transform: translateY(-2px);
}

.filter-animation-enter-active,
.filter-animation-leave-active {
  transition: all 0.3s ease-out;
  max-height: 1000px;
  opacity: 1;
  overflow: hidden;
}

.filter-animation-enter-from,
.filter-animation-leave-to {
  max-height: 0;
  opacity: 0;
}

/* Sayfa geçiş animasyonu */
.page-enter-active,
.page-leave-active {
  transition: all 0.4s ease-out;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Tur kartları için hover efektleri */
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Sayfa arkaplanında pattern */
.bg-pattern {
  background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

/* Scrollbar gizleme */
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
  display: none; /* Chrome, Safari and Opera */
}

/* Animasyonlar */
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

.animate-progress {
  animation: progress 6s linear forwards;
}

@keyframes progress {
  0% {
    width: 0%;
  }
  100% {
    width: 100%;
  }
}

.paused {
  animation-play-state: paused;
}
</style> 