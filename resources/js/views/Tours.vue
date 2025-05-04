<template>
  <div class="container mx-auto px-4 pt-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Turlar</h1>
      <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
        <input
          type="text"
          v-model="search"
          placeholder="Tur ara..."
          class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white w-full md:w-60"
          @input="handleSearch"
        />
        <button @click="fetchTours" class="p-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-primary-600"></div>
    </div>

    <div v-else-if="error" class="flex flex-col items-center py-12">
      <div class="text-red-600 dark:text-red-400 mb-4">{{ error }}</div>
      <button @click="fetchTours" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        Yeniden Dene
      </button>
    </div>

    <div v-else-if="tours.length === 0" class="flex flex-col items-center py-12">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div class="text-gray-600 dark:text-gray-400 mb-4">Tur bulunamadı. Başka bir arama denemek ister misiniz?</div>
      <button @click="search = ''; fetchTours()" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        Tümünü Göster
      </button>
    </div>

    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="tour in tours"
        :key="tour.id"
        class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 dark:border-gray-700 flex flex-col h-full"
      >
        <div class="relative overflow-hidden">
          <router-link :to="{ name: 'tour-detail', params: { id: tour.id }}">
            <img
              :src="tour.image || '/images/tours/default.jpg'"
              :alt="tour.title"
              class="w-full h-48 object-cover transition-transform duration-500 hover:scale-105"
              loading="lazy"
            />
          </router-link>
          <div class="absolute top-3 right-3 bg-white dark:bg-gray-900 text-primary-600 font-bold py-1 px-3 rounded-full text-sm shadow">
            {{ formatPrice(tour.price) }}
          </div>
          <!-- Badge'ler -->
          <div v-if="tour.duration" class="absolute bottom-3 left-3 bg-black/50 text-white text-xs px-2 py-1 rounded">
            {{ tour.duration }}
          </div>
        </div>
        <div class="p-4 flex flex-col flex-grow">
          <div class="flex justify-between items-start mb-2">
            <router-link :to="{ name: 'tour-detail', params: { id: tour.id }}">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors line-clamp-1">
                {{ tour.title }}
              </h3>
            </router-link>
          </div>
          
          <p class="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2 flex-grow">
            {{ tour.description }}
          </p>
          
          <router-link
            :to="{ name: 'tour-detail', params: { id: tour.id }}"
            class="w-full py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded text-center transition-colors inline-flex items-center justify-center"
          >
            <span>Detayları İncele</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const tours = ref([]);
const loading = ref(true);
const error = ref(null);
const search = ref('');
let searchTimeout = null;

// Fiyat formatlama
const formatPrice = (price) => {
  if (!price) return '0 TL';
  return `${Number(price).toLocaleString('tr-TR')} TL`;
};

const fetchTours = async () => {
  try {
    loading.value = true;
    error.value = null;
    
    // İstek parametreleri
    const params = new URLSearchParams();
    if (search.value.trim()) {
      params.append('query', search.value.trim());
    }
    
    // Önbellek kullanımı
    const cacheKey = `tours_${params.toString() || 'all'}`;
    const cachedData = sessionStorage.getItem(cacheKey);
    
    if (cachedData && !search.value.trim()) {
      tours.value = JSON.parse(cachedData);
      loading.value = false;
      return;
    }

    // API isteği
    const response = await axios.get(`/api/tours${search.value.trim() ? '/search' : ''}?${params.toString()}`);
    tours.value = response.data;
    
    // Veriyi önbelleğe al (sadece tüm turlar için)
    if (!search.value.trim()) {
      sessionStorage.setItem(cacheKey, JSON.stringify(response.data));
    }
  } catch (err) {
    error.value = 'Turlar yüklenirken bir hata oluştu.';
    console.error('Error fetching tours:', err);
  } finally {
    loading.value = false;
  }
};

const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchTours();
  }, 300);
};

onMounted(() => {
  fetchTours();
});
</script> 