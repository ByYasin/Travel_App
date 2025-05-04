<template>
  <section class="py-12 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">İndirimli Turlar</h2>
        <p class="text-gray-600 dark:text-gray-400">Sınırlı süreyle özel fiyatlarla sunulan cazip tur fırsatları</p>
      </div>

      <!-- Hikaye Önizlemeleri -->
      <div class="relative">
        <!-- Sol Gradient ve Buton -->
        <div class="absolute left-0 top-0 bottom-8 flex items-center z-20">
          <button @click="scrollLeft" 
                  class="w-8 h-8 flex items-center justify-center bg-white/80 dark:bg-gray-800/80 rounded-full shadow-lg text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 -ml-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
        </div>
        
        <!-- Sağ Gradient ve Buton -->
        <div class="absolute right-0 top-0 bottom-8 flex items-center z-20">
          <button @click="scrollRight" 
                  class="w-8 h-8 flex items-center justify-center bg-white/80 dark:bg-gray-800/80 rounded-full shadow-lg text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 -mr-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
        
        <div ref="scrollContainer" 
             class="flex gap-4 md:gap-6 overflow-x-auto pb-8 px-4 md:px-8 justify-start md:justify-center min-h-[120px] scrollbar-hide scroll-smooth"
             @touchstart="handleTouchStart"
             @touchmove="handleTouchMove"
             @touchend="handleTouchEnd">
          <div v-for="(tour, index) in popularTours" 
               :key="tour.id"
               @click="openStory(index)"
               class="relative flex-shrink-0 cursor-pointer group">
            <!-- Hikaye Çerçevesi -->
            <div class="relative w-20 h-20 md:w-28 md:h-28 rounded-full p-[2px] shadow-lg transform transition-all duration-300 group-hover:scale-105"
                 :class="tour.isViewed ? 'bg-gradient-to-tr from-gray-400 to-gray-500 dark:from-gray-600 dark:to-gray-700' : 'bg-gradient-to-tr from-primary-400 via-primary-500 to-primary-600'">
              <!-- Tur Resmi - Lazy Loading -->
              <div class="w-full h-full rounded-full overflow-hidden bg-[#1A1A1A] p-[2px]">
                <img v-lazy="tour.image" 
                     :data-src="tour.image"
                     :alt="tour.title"
                     class="w-full h-full object-cover rounded-full transform transition-transform duration-500 group-hover:scale-110">
              </div>
            </div>
            
            <!-- Tur Başlığı -->
            <div class="absolute -bottom-6 md:-bottom-8 left-1/2 -translate-x-1/2 text-xs md:text-sm font-medium text-gray-900 dark:text-white whitespace-nowrap text-center w-24 md:w-32">
              {{ tour.title }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Instagram Tarzı Tam Ekran Görüntüleme -->
    <div v-if="showStory" 
         class="fixed inset-0 bg-black z-50 flex items-center justify-center"
         @click="closeStory">
      <div class="relative w-full md:w-[500px] h-[100vh] bg-black overflow-hidden"
           @click.stop>
        <!-- Üst Bar -->
        <div class="absolute top-0 left-0 right-0 z-20">
          <!-- Progress Bar -->
          <div class="px-2 pt-2 pb-4 flex gap-1">
            <div v-for="n in popularTours.length" :key="n" 
                 class="h-0.5 flex-1 bg-white/30 rounded-full overflow-hidden">
              <div class="h-full bg-white rounded-full transition-all duration-300"
                   :class="{ 
                     'animate-progress': currentStoryIndex === n-1 && !isPaused,
                     'paused': isPaused
                   }"
                   :style="currentStoryIndex === n-1 ? 
                          isPaused ? `width: ${progressWidth}%` : '' : 
                          currentStoryIndex > n-1 ? 'width: 100%' : 'width: 0'"></div>
            </div>
          </div>

          <!-- Kullanıcı Bilgisi -->
          <div class="px-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 md:w-8 md:h-8 rounded-full overflow-hidden border border-white/20">
                <img v-lazy="popularTours[currentStoryIndex].image" 
                     :alt="popularTours[currentStoryIndex].title"
                     class="w-full h-full object-cover">
              </div>
              <div class="flex items-center gap-2">
                <span class="text-white text-xs md:text-sm font-medium">{{ popularTours[currentStoryIndex].title }}</span>
                <span class="text-white/60 text-[10px] md:text-xs">3s</span>
              </div>
            </div>
            <div class="flex items-center gap-3 md:gap-4">
              <button @click.stop="togglePause" 
                      class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-black/20 hover:bg-black/40 rounded-full transition-all duration-300">
                <svg v-if="!isPaused" class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
              <button @click.stop="closeStory" 
                      class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-black/20 hover:bg-black/40 rounded-full transition-all duration-300">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Story İçeriği -->
        <div class="w-full h-full">
          <TransitionGroup name="story" tag="div" class="w-full h-full">
            <!-- Görsel Preload İle Yükleniyor -->
            <img :key="currentStoryIndex" 
                 :src="storyImageCache[currentStoryIndex] || popularTours[currentStoryIndex].image" 
                 :alt="popularTours[currentStoryIndex].title"
                 class="w-full h-full object-cover">
          </TransitionGroup>
        </div>

        <!-- Alt Bilgi Alanı -->
        <div class="absolute bottom-0 left-0 right-0 p-3 md:p-4 bg-gradient-to-t from-black via-black/70 to-transparent">
          <div class="flex flex-col gap-2 md:gap-3">
            <!-- Tur Detayları -->
            <div class="flex items-center gap-3 md:gap-4 text-white/90">
              <div class="flex items-center gap-1 md:gap-1.5">
                <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                </svg>
                <span class="text-sm md:text-base font-medium">{{ popularTours[currentStoryIndex].rating }}</span>
              </div>
              <span class="text-white/30">•</span>
              <div class="flex items-center gap-1 md:gap-1.5">
                <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm md:text-base font-medium">{{ popularTours[currentStoryIndex].location }}</span>
              </div>
              <span class="text-white/30">•</span>
              <span class="text-base md:text-lg font-bold text-white">{{ popularTours[currentStoryIndex].price }}</span>
            </div>

            <!-- Rezervasyon Butonu -->
            <button @click="handleReservation" 
                    class="relative px-3 py-2 md:px-4 md:py-2.5 bg-white text-black rounded-lg text-xs md:text-sm font-semibold hover:bg-white/90 transition-all duration-300 flex items-center justify-center gap-1.5 md:gap-2">
              <span>Rezervasyon Yap</span>
              <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Dokunmatik Alanlar -->
        <div class="absolute inset-y-0 left-0 w-1/3" @click.stop="previousStory"></div>
        <div class="absolute inset-y-0 right-0 w-1/3" @click.stop="nextStory"></div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
import { useRouter } from 'vue-router';
import { observeLazyElement } from '../utils/lazyLoad';

const router = useRouter();
const scrollContainer = ref(null);
const showStory = ref(false);
const currentStoryIndex = ref(0);
const currentStoryImageIndex = ref(0);
const showComments = ref(false);
const newComment = ref('');
const isLiked = ref(false);
const isPaused = ref(false);
const progressWidth = ref(0);
let storyTimer = null;
let touchStartX = 0;

// Hikaye görselleri için önbellek
const storyImageCache = ref({});

// Görüntülenen öğeleri izleme
watch(showStory, (newVal) => {
  if (newVal) {
    // Hikaye açıldığında, önceden yükle
    preloadStoryImages();
    startStoryTimer();
  } else {
    // Hikaye kapatıldığında zamanlayıcıyı durdur
    clearStoryTimer();
  }
});

// Önceki ve sonraki görselleri önceden yükler
function preloadStoryImages() {
  // Mevcut görsel
  const current = popularTours.value[currentStoryIndex.value];
  if (current && current.image) {
    preloadImage(current.image, currentStoryIndex.value);
  }
  
  // Sonraki görsel
  if (currentStoryIndex.value < popularTours.value.length - 1) {
    const next = popularTours.value[currentStoryIndex.value + 1];
    if (next && next.image) {
      preloadImage(next.image, currentStoryIndex.value + 1);
    }
  }
  
  // Önceki görsel
  if (currentStoryIndex.value > 0) {
    const previous = popularTours.value[currentStoryIndex.value - 1];
    if (previous && previous.image) {
      preloadImage(previous.image, currentStoryIndex.value - 1);
    }
  }
}

// Görsel önyükleme fonksiyonu
function preloadImage(src, index) {
  if (!storyImageCache.value[index]) {
    const img = new Image();
    img.onload = () => {
      storyImageCache.value[index] = src;
    };
    img.src = src;
  }
}

const scrollLeft = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({
      left: -320,
      behavior: 'smooth'
    });
  }
};

const scrollRight = () => {
  if (scrollContainer.value) {
    scrollContainer.value.scrollBy({
      left: 320,
      behavior: 'smooth'
    });
  }
};

const openStory = (index) => {
  currentStoryIndex.value = index;
  showStory.value = true;
  popularTours.value[index].isViewed = true;
  startTime = performance.now();
  progressWidth.value = 0;
  animationFrameId = requestAnimationFrame(updateProgressBar);
};

const closeStory = () => {
  showStory.value = false;
  isPaused.value = false;
  cancelAnimationFrame(animationFrameId);
  progressWidth.value = 0;
  startTime = 0;
};

const nextStory = () => {
  if (currentStoryIndex.value < popularTours.value.length - 1) {
    currentStoryIndex.value++;
    progressWidth.value = 0;
    startTime = performance.now();
    popularTours.value[currentStoryIndex.value].isViewed = true;
    animationFrameId = requestAnimationFrame(updateProgressBar);
  } else {
    closeStory();
  }
};

const previousStory = () => {
  if (currentStoryIndex.value > 0) {
    currentStoryIndex.value--;
    progressWidth.value = 0;
    startTime = performance.now();
    animationFrameId = requestAnimationFrame(updateProgressBar);
  }
};

const nextStoryImage = () => {
  if (currentStoryImageIndex.value < popularTours.value[currentStoryIndex.value].storyImages.length - 1) {
    currentStoryImageIndex.value++;
    startStoryTimer();
  } else {
    nextStory();
  }
};

const previousStoryImage = () => {
  if (currentStoryImageIndex.value > 0) {
    currentStoryImageIndex.value--;
    startStoryTimer();
  } else {
    previousStory();
  }
};

const startStoryTimer = () => {
  stopStoryTimer();
  if (!isPaused.value) {
    startProgress();
    storyTimer = setTimeout(() => {
      nextStory();
    }, 3000);
  }
};

const stopStoryTimer = () => {
  stopProgress();
  if (storyTimer) {
    clearTimeout(storyTimer);
    storyTimer = null;
  }
};

// Otomatik kaydırma için
let autoScrollTimer = null;

const startAutoScroll = () => {
  autoScrollTimer = setInterval(() => {
    if (scrollContainer.value) {
      const isAtEnd = scrollContainer.value.scrollLeft + scrollContainer.value.clientWidth >= scrollContainer.value.scrollWidth;
      if (isAtEnd) {
        scrollContainer.value.scrollTo({ left: 0, behavior: 'smooth' });
      } else {
        scrollContainer.value.scrollBy({ left: 320, behavior: 'smooth' });
      }
    }
  }, 5000);
};

onMounted(() => {
  startAutoScroll();
  loadViewedStories();
});

onUnmounted(() => {
  if (autoScrollTimer) {
    clearInterval(autoScrollTimer);
  }
  stopStoryTimer();
  showStory.value = false;
  isPaused.value = false;
  progressWidth.value = 0;
  startTime = 0;
  animationFrameId = null;
  cancelAnimationFrame(animationFrameId);
});

const popularTours = ref([
  {
    id: 1,
    title: 'Kapadokya',
    description: 'Eşsiz peribacalarının arasında unutulmaz bir deneyim',
    price: '₺2,999',
    location: 'Nevşehir',
    images: ['https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=800'],
    image: 'https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=800',
    rating: 4.9,
    isViewed: false
  },
  {
    id: 2,
    title: 'Pamukkale',
    description: 'Termal suların doğal harikası',
    price: '₺1,999',
    location: 'Denizli',
    images: ['https://images.pexels.com/photos/3894868/pexels-photo-3894868.jpeg?auto=compress&cs=tinysrgb&w=800'],
    image: 'https://images.pexels.com/photos/3894868/pexels-photo-3894868.jpeg?auto=compress&cs=tinysrgb&w=800',
    rating: 4.8,
    isViewed: false
  },
  {
    id: 3,
    title: 'Efes Antik Kenti',
    description: 'Tarihin derinliklerine bir yolculuk',
    price: '₺1,499',
    location: 'İzmir',
    images: ['https://images.pexels.com/photos/11421498/pexels-photo-11421498.jpeg?auto=compress&cs=tinysrgb&w=800'],
    image: 'https://images.pexels.com/photos/11421498/pexels-photo-11421498.jpeg?auto=compress&cs=tinysrgb&w=800',
    rating: 4.7,
    isViewed: false
  },
  {
    id: 4,
    title: 'İstanbul Turu',
    description: 'İki kıtanın birleştiği büyüleyici şehir',
    price: '₺2,199',
    location: 'İstanbul',
    images: ['https://images.pexels.com/photos/13929622/pexels-photo-13929622.jpeg?auto=compress&cs=tinysrgb&w=800'],
    image: 'https://images.pexels.com/photos/13929622/pexels-photo-13929622.jpeg?auto=compress&cs=tinysrgb&w=800',
    rating: 4.9,
    isViewed: false
  },
  {
    id: 5,
    title: 'Fethiye Ölüdeniz',
    description: 'Turkuaz suların mutlak güzelliği',
    price: '₺3,299',
    location: 'Muğla',
    images: ['https://images.pexels.com/photos/13978999/pexels-photo-13978999.jpeg?auto=compress&cs=tinysrgb&w=800'],
    image: 'https://images.pexels.com/photos/13978999/pexels-photo-13978999.jpeg?auto=compress&cs=tinysrgb&w=800',
    rating: 4.8,
    isViewed: false
  }
]);

const incrementViews = (index) => {
  popularTours.value[index].views++;
  popularTours.value[index].isViewed = true;
  saveViewedStory(popularTours.value[index].id);
};

let startTime = 0;
let elapsedTime = 0;
let animationFrameId = null;
const storyDuration = 6000; // 6 saniye

watch(showStory, (newValue) => {
  if (!newValue) {
    cancelAnimationFrame(animationFrameId);
    progressWidth.value = 0;
    startTime = 0;
  }
});

const updateProgressBar = (timestamp) => {
  if (!startTime) {
    startTime = timestamp;
  }
  
  if (isPaused.value) {
    return;
  }
  
  const progress = timestamp - startTime;
  progressWidth.value = (progress / storyDuration) * 100;
  
  if (progress < storyDuration) {
    animationFrameId = requestAnimationFrame(updateProgressBar);
  } else {
    nextStory();
  }
};

const togglePause = () => {
  isPaused.value = !isPaused.value;
  
  if (isPaused.value) {
    cancelAnimationFrame(animationFrameId);
  } else {
    startTime = performance.now() - (progressWidth.value / 100) * storyDuration;
    animationFrameId = requestAnimationFrame(updateProgressBar);
  }
};

const getTimeAgo = () => {
  return '5 dk önce'; // Bu kısım dinamik olarak hesaplanabilir
};

const toggleLike = () => {
  isLiked.value = !isLiked.value;
  if (isLiked.value) {
    popularTours.value[currentStoryIndex.value].likes++;
  } else {
    popularTours.value[currentStoryIndex.value].likes--;
  }
};

const addComment = () => {
  if (newComment.value.trim()) {
    popularTours.value[currentStoryIndex.value].comments.push({
      user: "Kullanıcı",
      userImage: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=800&auto=format&fit=crop&q=60",
      text: newComment.value,
      time: "Şimdi"
    });
    newComment.value = '';
  }
};

const handleReservation = () => {
  router.push('/');
};

// İzlenen hikayeleri localStorage'dan yükle
const loadViewedStories = () => {
  const viewedStories = JSON.parse(localStorage.getItem('viewedStories') || '[]');
  popularTours.value.forEach(tour => {
    tour.isViewed = viewedStories.includes(tour.id);
  });
};

// İzlenen hikayeleri localStorage'a kaydet
const saveViewedStory = (tourId) => {
  const viewedStories = JSON.parse(localStorage.getItem('viewedStories') || '[]');
  if (!viewedStories.includes(tourId)) {
    viewedStories.push(tourId);
    localStorage.setItem('viewedStories', JSON.stringify(viewedStories));
  }
};

// Touch events
const handleTouchStart = (event) => {
  touchStartX = event.touches[0].clientX;
};

const handleTouchMove = (event) => {
  // Sayfa kaydırmasını engellemek için preventDefault yapmıyoruz
  // Modern tarayıcılarda passive event listener olarak çalışır
};

const handleTouchEnd = (event) => {
  const touchEndX = event.changedTouches[0].clientX;
  const diffX = touchEndX - touchStartX;
  
  // Eğer dokunma hareketi yeterince genişse kaydırma yapalım
  if (Math.abs(diffX) > 50) {
    if (diffX > 0) {
      scrollLeft();
    } else {
      scrollRight();
    }
  }
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}

.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

/* Story Geçiş Animasyonları */
.story-enter-active,
.story-leave-active {
  transition: all 0.3s ease;
}

.story-enter-from {
  opacity: 0;
}

.story-leave-to {
  opacity: 0;
}

.story-enter-to,
.story-leave-from {
  opacity: 1;
}

/* Progress Bar Animasyonu */
@keyframes progress {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

.animate-progress {
  width: v-bind(progressWidth + '%');
  transition: width 16.67ms linear;
}

.animate-progress.paused {
  transition: none;
}
</style> 