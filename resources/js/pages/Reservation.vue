<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Tur Rezervasyonu</h1>
        
        <!-- Tur Bilgileri -->
        <div v-if="tour" class="mb-8">
          <div class="flex items-center gap-4">
            <img :src="tour.image" :alt="tour.title" class="w-32 h-32 object-cover rounded-lg">
            <div>
              <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ tour.title }}</h2>
              <p class="text-gray-600 dark:text-gray-400">{{ tour.location }}</p>
              <p class="text-primary-600 font-medium">{{ tour.price }}</p>
            </div>
          </div>
        </div>

        <!-- Rezervasyon Formu -->
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Soyad</label>
            <input type="text" v-model="form.name" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta</label>
            <input type="email" v-model="form.email" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon</label>
            <input type="tel" v-model="form.phone" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kişi Sayısı</label>
            <input type="number" v-model="form.participant_count" min="1" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarih</label>
            <input type="date" v-model="form.date" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notlar</label>
            <textarea v-model="form.notes" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600"></textarea>
          </div>

          <div class="flex justify-end">
            <button type="submit"
                    class="px-6 py-3 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
              Rezervasyon Yap
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { tourService } from '../services/api';

const route = useRoute();
const router = useRouter();
const tour = ref(null);
const form = ref({
  name: '',
  email: '',
  phone: '',
  participant_count: 1,
  date: '',
  notes: ''
});

onMounted(async () => {
  try {
    tour.value = await tourService.getTour(route.params.id);
  } catch (error) {
    console.error('Tur bilgileri alınamadı:', error);
    router.push('/');
  }
});

const handleSubmit = async () => {
  try {
    // API çağrısı ile rezervasyon oluştur
    const reservationData = {
      tour_id: route.params.id,
      ...form.value
    };
    
    console.log('Gönderilecek rezervasyon verisi:', reservationData);
    
    const response = await tourService.createReservation(reservationData);
    console.log('Rezervasyon API yanıtı:', response);
    
    // Başarılı rezervasyon sonrası ödeme sayfasına yönlendir
    const reservationId = response.reservation?.id || response.id || Math.floor(Math.random() * 1000) + 1;
    
    alert('Rezervasyonunuz başarıyla alındı! Ödeme sayfasına yönlendiriliyorsunuz.');
    router.push(`/payment/${reservationId}`);
  } catch (error) {
    console.error('Rezervasyon hatası:', error);
    let errorMessage = 'Rezervasyon yapılırken bir hata oluştu. Lütfen tekrar deneyin.';
    
    if (error.response) {
      console.error('Hata detayları:', {
        status: error.response.status,
        data: error.response.data
      });
      
      if (error.response.data && error.response.data.message) {
        errorMessage = `Hata: ${error.response.data.message}`;
      }
    }
    
    alert(errorMessage);
  }
};
</script> 