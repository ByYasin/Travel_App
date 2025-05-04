<template>
  <div class="container mx-auto px-4">
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="error" class="text-center py-12 text-red-600 dark:text-red-400">
      {{ error }}
    </div>

    <div v-else-if="tour" class="max-w-4xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <img
          :src="tour.image"
          :alt="tour.title"
          class="w-full h-96 object-cover"
        />
        <div class="p-8">
          <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            {{ tour.title }}
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">
            {{ tour.description }}
          </p>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Tur Detayları
              </h3>
              <ul class="space-y-2">
                <li class="flex items-center text-gray-600 dark:text-gray-300">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Süre: {{ tour.duration }}
                </li>
                <li class="flex items-center text-gray-600 dark:text-gray-300">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  Başlangıç: {{ tour.start_location }}
                </li>
                <li class="flex items-center text-gray-600 dark:text-gray-300">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Tarih: {{ formatDate(tour.start_date) }} - {{ formatDate(tour.end_date) }}
                </li>
                <li class="flex items-center text-gray-600 dark:text-gray-300">
                  <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  Maksimum Katılımcı: {{ tour.max_participants }}
                </li>
              </ul>
            </div>
            
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                Dahil Olanlar
              </h3>
              <ul class="space-y-2">
                <li v-for="(item, index) in tour.includes" :key="index" class="flex items-center text-gray-600 dark:text-gray-300">
                  <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ item }}
                </li>
              </ul>
            </div>
          </div>

          <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              Dahil Olmayanlar
            </h3>
            <ul class="space-y-2">
              <li v-for="(item, index) in tour.excludes" :key="index" class="flex items-center text-gray-600 dark:text-gray-300">
                <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ item }}
              </li>
            </ul>
          </div>

          <div class="flex justify-between items-center mb-10">
            <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
              {{ tour.price }} TL
            </span>
            <button class="btn btn-primary">
              Rezervasyon Yap
            </button>
          </div>
          
          <!-- Tur Değerlendirmeleri Bölümü -->
          <div class="mt-12 border-t pt-8 border-gray-200 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
              Tur Değerlendirmeleri
            </h2>
            
            <!-- Değerlendirme İstatistikleri -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8">
              <div class="flex items-center mb-4 md:mb-0">
                <div class="mr-4">
                  <span class="text-4xl font-bold text-gray-900 dark:text-white">{{ reviewStats.average_rating || 0 }}</span>
                  <span class="text-lg text-gray-500 dark:text-gray-400">/5</span>
                </div>
                <div>
                  <div class="flex items-center mb-1">
                    <div class="flex">
                      <template v-for="i in 5" :key="i">
                        <svg class="w-5 h-5" :class="i <= Math.round(reviewStats.average_rating || 0) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                      </template>
                    </div>
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ reviewStats.review_count || 0 }} değerlendirme</span>
                  </div>
                </div>
              </div>
              
              <button 
                v-if="isAuthenticated" 
                @click="openReviewForm = !openReviewForm" 
                class="btn btn-secondary"
              >
                Değerlendirme Yap
              </button>
              <button v-else @click="login" class="btn btn-secondary">
                Değerlendirme yapmak için giriş yapın
              </button>
            </div>
            
            <!-- Değerlendirme Formu -->
            <div v-if="openReviewForm" class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-8">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ editingReview ? 'Değerlendirmenizi Düzenleyin' : 'Yeni Değerlendirme' }}
              </h3>
              
              <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Puanınız
                </label>
                <div class="flex">
                  <button 
                    v-for="star in 5" 
                    :key="star" 
                    @click="newReview.rating = star"
                    class="p-1"
                  >
                    <svg class="w-8 h-8" :class="star <= newReview.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div class="mb-4">
                <label for="review-comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Yorumunuz
                </label>
                <textarea
                  id="review-comment"
                  v-model="newReview.comment"
                  rows="4"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
                  placeholder="Deneyiminizi paylaşın..."
                ></textarea>
                <p v-if="reviewError" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ reviewError }}</p>
              </div>
              
              <div class="flex justify-end space-x-2">
                <button @click="openReviewForm = false" class="btn btn-outline">
                  İptal
                </button>
                <button 
                  @click="submitReview" 
                  class="btn btn-primary"
                  :disabled="reviewSubmitting"
                >
                  <span v-if="reviewSubmitting">Gönderiliyor...</span>
                  <span v-else>{{ editingReview ? 'Güncelle' : 'Gönder' }}</span>
                </button>
              </div>
            </div>
            
            <!-- Değerlendirme Listesi -->
            <div v-if="loadingReviews" class="flex justify-center py-8">
              <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
            </div>
            
            <div v-else-if="reviewsError" class="text-center py-8 text-red-600 dark:text-red-400">
              {{ reviewsError }}
            </div>
            
            <div v-else-if="reviews.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
              Henüz değerlendirme yapılmamış. İlk değerlendirmeyi siz yapın!
            </div>
            
            <div v-else class="space-y-6">
              <div v-for="review in reviews" :key="review.id" class="border-b border-gray-200 dark:border-gray-700 pb-6 mb-6 last:border-b-0">
                <div class="flex justify-between items-start">
                  <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                      <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-white">
                        {{ review.user.name.charAt(0).toUpperCase() }}
                      </div>
                    </div>
                    <div>
                      <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ review.user.name }}
                      </h4>
                      <div class="flex items-center mt-1 mb-2">
                        <div class="flex">
                          <template v-for="i in 5" :key="i">
                            <svg class="w-4 h-4" :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                          </template>
                        </div>
                        <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                          {{ formatDate(review.created_at) }}
                        </span>
                      </div>
                      <div class="text-gray-700 dark:text-gray-300 mb-2">
                        {{ review.comment }}
                      </div>
                      <div class="flex items-center space-x-4">
                        <button 
                          @click="toggleLike(review)" 
                          class="text-sm flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                        >
                          <svg class="w-4 h-4 mr-1" :class="{'text-primary-500': review.userLiked}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                          </svg>
                          {{ review.likes_count || 0 }}
                        </button>
                        
                        <button 
                          @click="showReplyForm(review.id)" 
                          class="text-sm flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                        >
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                          </svg>
                          Yanıtla
                        </button>
                        
                        <div v-if="isCurrentUserReview(review)" class="flex items-center space-x-2">
                          <button 
                            @click="editReview(review)" 
                            class="text-sm flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                          >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Düzenle
                          </button>
                          
                          <button 
                            @click="deleteReview(review.id)" 
                            class="text-sm flex items-center text-red-500 hover:text-red-700"
                          >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Sil
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Yanıt Formu -->
                <div v-if="activeReplyId === review.id" class="mt-4 ml-14">
                  <textarea
                    v-model="replyContent"
                    rows="2"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white"
                    placeholder="Yanıtınızı yazın..."
                  ></textarea>
                  <div class="flex justify-end space-x-2 mt-2">
                    <button @click="cancelReply" class="btn btn-sm btn-outline">
                      İptal
                    </button>
                    <button 
                      @click="submitReply(review.id)" 
                      class="btn btn-sm btn-primary"
                      :disabled="replySubmitting"
                    >
                      {{ replySubmitting ? 'Gönderiliyor...' : 'Yanıtla' }}
                    </button>
                  </div>
                </div>
                
                <!-- Yanıtlar -->
                <div v-if="review.replies && review.replies.length > 0" class="mt-4 ml-14 space-y-4">
                  <div v-for="reply in review.replies" :key="reply.id" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <div class="flex items-start">
                      <div class="flex-shrink-0 mr-3">
                        <div class="w-8 h-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-white text-sm">
                          {{ reply.user.name.charAt(0).toUpperCase() }}
                        </div>
                      </div>
                      <div class="flex-1">
                        <div class="flex items-center justify-between">
                          <h5 class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ reply.user.name }}
                          </h5>
                          <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ formatDate(reply.created_at) }}
                          </span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">
                          {{ reply.content }}
                        </p>
                        <div class="flex items-center mt-2">
                          <button 
                            @click="toggleReplyLike(reply)" 
                            class="text-xs flex items-center text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                          >
                            <svg class="w-3 h-3 mr-1" :class="{'text-primary-500': reply.userLiked}" fill="currentColor" viewBox="0 0 20 20">
                              <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                            </svg>
                            {{ reply.likes_count || 0 }}
                          </button>
                          
                          <div v-if="isCurrentUserReply(reply)" class="ml-4">
                            <button 
                              @click="deleteReply(reply.id)" 
                              class="text-xs flex items-center text-red-500 hover:text-red-700"
                            >
                              <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                              </svg>
                              Sil
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useAuthStore } from '../store/auth';
import axios from 'axios';

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();
const tour = ref(null);
const loading = ref(true);
const error = ref(null);

// Değerlendirmeler için ref'ler
const reviews = ref([]);
const reviewStats = ref({
  average_rating: 0,
  review_count: 0
});
const loadingReviews = ref(false);
const reviewsError = ref(null);

// Değerlendirme formu
const openReviewForm = ref(false);
const newReview = ref({
  rating: 0,
  comment: ''
});
const reviewError = ref('');
const reviewSubmitting = ref(false);
const editingReview = ref(false);
const editingReviewId = ref(null);

// Yanıt formu
const activeReplyId = ref(null);
const replyContent = ref('');
const replySubmitting = ref(false);

const isAuthenticated = computed(() => {
  return authStore.isAuthenticated;
});

const currentUserId = computed(() => {
  return authStore.user?.id;
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const fetchTour = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await axios.get(`/api/tours/${route.params.id}`);
    tour.value = response.data;
  } catch (err) {
    error.value = 'Tur detayları yüklenirken bir hata oluştu.';
    console.error('Error fetching tour:', err);
  } finally {
    loading.value = false;
  }
};

const fetchReviews = async () => {
  try {
    loadingReviews.value = true;
    reviewsError.value = null;
    const response = await axios.get(`/api/tours/${route.params.id}/reviews`);
    
    if (response.data.success) {
      reviews.value = response.data.data.reviews;
      reviewStats.value = {
        average_rating: response.data.data.average_rating,
        review_count: response.data.data.review_count
      };
    } else {
      reviewsError.value = 'Değerlendirmeler yüklenirken bir hata oluştu.';
    }
  } catch (err) {
    reviewsError.value = 'Değerlendirmeler yüklenirken bir hata oluştu.';
    console.error('Error fetching reviews:', err);
  } finally {
    loadingReviews.value = false;
  }
};

const login = () => {
  router.push({ name: 'login', query: { redirect: route.fullPath } });
};

const isCurrentUserReview = (review) => {
  return review.user_id === currentUserId.value;
};

const isCurrentUserReply = (reply) => {
  return reply.user_id === currentUserId.value;
};

const submitReview = async () => {
  if (newReview.value.rating === 0) {
    reviewError.value = 'Lütfen bir puan seçin.';
    return;
  }
  
  if (!newReview.value.comment.trim()) {
    reviewError.value = 'Lütfen bir yorum yazın.';
    return;
  }
  
  reviewError.value = '';
  reviewSubmitting.value = true;
  
  try {
    let response;
    
    if (editingReview.value) {
      response = await axios.put(`/api/reviews/${editingReviewId.value}`, {
        rating: newReview.value.rating,
        comment: newReview.value.comment
      });
    } else {
      response = await axios.post(`/api/tours/${tour.value.id}/reviews`, {
        rating: newReview.value.rating,
        comment: newReview.value.comment
      });
    }
    
    if (response.data.success) {
      openReviewForm.value = false;
      resetReviewForm();
      fetchReviews(); // Değerlendirmeleri yeniden yükle
    } else {
      reviewError.value = response.data.message || 'Bir hata oluştu.';
    }
  } catch (err) {
    if (err.response && err.response.data.message) {
      reviewError.value = err.response.data.message;
    } else {
      reviewError.value = 'Değerlendirme gönderilirken bir hata oluştu.';
    }
    console.error('Error submitting review:', err);
  } finally {
    reviewSubmitting.value = false;
  }
};

const editReview = (review) => {
  editingReview.value = true;
  editingReviewId.value = review.id;
  newReview.value.rating = review.rating;
  newReview.value.comment = review.comment;
  openReviewForm.value = true;
};

const deleteReview = async (reviewId) => {
  if (!confirm('Bu değerlendirmeyi silmek istediğinizden emin misiniz?')) {
    return;
  }
  
  try {
    const response = await axios.delete(`/api/reviews/${reviewId}`);
    
    if (response.data.success) {
      fetchReviews(); // Değerlendirmeleri yeniden yükle
    } else {
      alert(response.data.message || 'Değerlendirme silinirken bir hata oluştu.');
    }
  } catch (err) {
    alert('Değerlendirme silinirken bir hata oluştu.');
    console.error('Error deleting review:', err);
  }
};

const resetReviewForm = () => {
  newReview.value = {
    rating: 0,
    comment: ''
  };
  editingReview.value = false;
  editingReviewId.value = null;
  reviewError.value = '';
};

const toggleLike = async (review) => {
  if (!isAuthenticated.value) {
    login();
    return;
  }
  
  try {
    const response = await axios.post(`/api/reviews/${review.id}/like`);
    
    if (response.data.success) {
      // Yerel state'i güncelle
      review.userLiked = response.data.data.userLiked;
      review.likes_count = response.data.data.likesCount;
    }
  } catch (err) {
    console.error('Error toggling like:', err);
  }
};

const showReplyForm = (reviewId) => {
  if (!isAuthenticated.value) {
    login();
    return;
  }
  
  activeReplyId.value = reviewId;
  replyContent.value = '';
};

const cancelReply = () => {
  activeReplyId.value = null;
  replyContent.value = '';
};

const submitReply = async (reviewId) => {
  if (!replyContent.value.trim()) {
    return;
  }
  
  replySubmitting.value = true;
  
  try {
    const response = await axios.post(`/reviews/${reviewId}/reply`, {
      content: replyContent.value
    });
    
    if (response.data.success) {
      cancelReply();
      fetchReviews(); // Yanıtları içerecek şekilde değerlendirmeleri yeniden yükle
    }
  } catch (err) {
    console.error('Error submitting reply:', err);
  } finally {
    replySubmitting.value = false;
  }
};

const toggleReplyLike = async (reply) => {
  if (!isAuthenticated.value) {
    login();
    return;
  }
  
  try {
    const response = await axios.post(`/replies/${reply.id}/like`);
    
    if (response.data.success) {
      // Yerel state'i güncelle
      reply.userLiked = response.data.data.userLiked;
      reply.likes_count = response.data.data.likesCount;
    }
  } catch (err) {
    console.error('Error toggling reply like:', err);
  }
};

const deleteReply = async (replyId) => {
  if (!confirm('Bu yanıtı silmek istediğinizden emin misiniz?')) {
    return;
  }
  
  try {
    const response = await axios.delete(`/reviews/reply/${replyId}`);
    
    if (response.data.success) {
      fetchReviews(); // Değerlendirmeleri yeniden yükle
    } else {
      alert(response.data.message || 'Yanıt silinirken bir hata oluştu.');
    }
  } catch (err) {
    alert('Yanıt silinirken bir hata oluştu.');
    console.error('Error deleting reply:', err);
  }
};

onMounted(() => {
  fetchTour();
  fetchReviews();
});
</script> 