<template>
  <div>
    <!-- Overlay, drawer açıkken arka planı karartır -->
    <div 
      v-if="isOpen" 
      @click="close" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-opacity duration-300"
      :class="{'opacity-100': isOpen, 'opacity-0': !isOpen}"
    ></div>
    
    <!-- Sepet Drawer -->
    <div 
      class="fixed top-0 right-0 h-full bg-white dark:bg-gray-800 shadow-xl z-50 transition-all duration-300 transform overflow-hidden flex flex-col"
      :class="[
        isOpen ? 'translate-x-0 w-full sm:w-96' : 'translate-x-full w-0',
      ]"
    >
      <!-- Drawer Header -->
      <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center">
          <svg class="w-6 h-6 mr-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          Sepetim <span v-if="cartStore.totalItems > 0" class="ml-2 text-sm font-normal text-gray-600 dark:text-gray-400">({{ cartStore.totalItems }} ürün)</span>
        </h2>
        <button 
          @click="close"
          class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors duration-200"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      
      <!-- Cart Content -->
      <div class="flex-1 overflow-y-auto p-4">
        <!-- Sepet Boş Mesajı -->
        <div v-if="!cartStore.totalItems" class="flex flex-col items-center justify-center h-full text-center py-8">
          <svg class="w-20 h-20 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          <h3 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-2">Sepetiniz Boş</h3>
          <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-xs mx-auto">Harika turları keşfedin ve unutulmaz bir tatil deneyimi için rezervasyon yapın.</p>
          
          <div class="space-y-3 w-full max-w-xs">
            <router-link @click="close" to="/turlar" 
              class="block w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md text-center">
              Turları Keşfedin
            </router-link>
            
            <button @click="cartStore.addTestItem(); toast.success('Test turu eklendi!'); close();" 
              class="block w-full py-2 px-4 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 font-medium rounded-lg transition duration-200 border border-gray-300 dark:border-gray-600 text-center">
              Popüler Turları Görün
            </button>
          </div>
          
          <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 w-full max-w-xs">
            <p class="text-xs text-gray-500 dark:text-gray-400">
              Sepete tur eklemek için tur detay sayfasına gidip rezervasyon yapabilirsiniz.
            </p>
          </div>
        </div>
        
        <!-- Sepet İçeriği -->
        <div v-else class="space-y-4">
          <div v-for="item in cartStore.items" :key="item.id" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3 shadow-sm">
            <div class="flex gap-3">
              <!-- Tur Görseli -->
              <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                <img 
                  :src="item.featured_image || '/images/tours/default.jpg'" 
                  :alt="item.title" 
                  class="w-full h-full object-cover" 
                />
              </div>
              
              <!-- Tur Bilgileri -->
              <div class="flex-1 min-w-0">
                <div class="flex justify-between">
                  <h3 class="text-sm font-medium text-gray-800 dark:text-white truncate">
                    <router-link @click="close" :to="`/turlar/${item.slug}`" class="hover:text-primary-600 dark:hover:text-primary-400">
                      {{ item.title }}
                    </router-link>
                  </h3>
                  <button 
                    @click="cartStore.removeFromCart(item.id)"
                    class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 ml-1"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
                
                <!-- Tarih ve Kişi Sayısı -->
                <div class="mt-1 flex flex-wrap gap-2 text-xs text-gray-600 dark:text-gray-400">
                  <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ formatDate(item.date) }}</span>
                  </div>
                  
                  <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>{{ item.participants }} Kişi</span>
                  </div>
                </div>
                
                <!-- Fiyat Bilgisi -->
                <div class="mt-2 flex justify-between items-center">
                  <!-- Kişi Sayısı Ayarlama -->
                  <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded">
                    <button 
                      @click="updateParticipants(item, Math.max(1, item.participants - 1))"
                      class="px-2 py-0.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                    <span class="px-2 py-0.5 text-xs font-medium">{{ item.participants }}</span>
                    <button 
                      @click="updateParticipants(item, item.participants + 1)"
                      class="px-2 py-0.5 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                      </svg>
                    </button>
                  </div>
                  
                  <div class="text-sm font-medium text-primary-600 dark:text-primary-400">
                    {{ formatPrice(calculateItemTotal(item)) }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Cart Footer -->
      <div v-if="cartStore.totalItems > 0" class="p-4 border-t border-gray-200 dark:border-gray-700">
        <!-- Toplam Tutar -->
        <div class="flex justify-between items-center mb-4">
          <span class="text-gray-600 dark:text-gray-400">Toplam</span>
          <span class="text-lg font-semibold text-primary-600 dark:text-primary-400">{{ formatPrice(cartStore.totalAmount) }}</span>
        </div>
        
        <!-- Buttons -->
        <div class="space-y-2">
          <button
            @click="goToCheckout"
            class="w-full py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-center font-medium rounded-lg transition duration-200"
          >
            Ödemeye Geç
          </button>
          
          <button
            @click="clearCart"
            class="w-full py-2 px-4 bg-transparent hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 text-center font-medium rounded-lg transition duration-200 border border-gray-300 dark:border-gray-600"
          >
            Sepeti Temizle
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits, ref, watch } from 'vue';
import { useCartStore } from '@/store/cart';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['close']);
const cartStore = useCartStore();
const router = useRouter();
const toast = useToast();

// Drawer'ı kapat
const close = () => {
  emit('close');
};

// ESC tuşu ile kapatma
watch(() => props.isOpen, (newVal) => {
  if (newVal) {
    document.body.style.overflow = 'hidden'; // Arkaplan scrollunu engelle
    document.addEventListener('keydown', handleEscKey);
  } else {
    document.body.style.overflow = ''; // Arkaplan scrollunu serbest bırak
    document.removeEventListener('keydown', handleEscKey);
  }
});

const handleEscKey = (event) => {
  if (event.key === 'Escape') {
    close();
  }
};

// Sepet fonksiyonları
const calculateItemTotal = (item) => {
  const price = parseFloat((item.price || '0').toString().replace(/[^\d.-]/g, '')) || 0;
  return price * (item.participants || 1);
};

// Kişi sayısını güncelle
const updateParticipants = (item, newCount) => {
  if (newCount < 1) return; // Minimum 1 kişi
  
  try {
    // Cart store'daki ilgili ürünü güncelle
    const index = cartStore.items.findIndex(i => i.id === item.id);
    if (index !== -1) {
      cartStore.items[index].participants = newCount;
      toast.info(`${item.title} için kişi sayısı güncellendi!`);
      
      // LocalStorage'ı manuel güncelle
      cartStore.saveCart();
    }
  } catch (error) {
    console.error('Kişi sayısı güncellenirken hata oluştu:', error);
    toast.error('Kişi sayısı güncellenirken bir hata oluştu.');
  }
};

// Sepeti temizle
const clearCart = () => {
  if (confirm('Sepetteki tüm ürünleri silmek istediğinize emin misiniz?')) {
    cartStore.clearCart();
    toast.success('Sepet başarıyla temizlendi');
  }
};

// Tarih formatlama
const formatDate = (dateString) => {
  if (!dateString) return '';
  
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('tr-TR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(date);
};

// Fiyat formatı
const formatPrice = (price) => {
  if (price === undefined || price === null) return '0 ₺';
  
  return new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(price).replace('₺', '') + ' ₺';
};

// Ödemeye Geç butonu için yeni fonksiyon
const goToCheckout = () => {
  if (cartStore.totalItems === 0) {
    toast.error('Sepetinizde ürün bulunmuyor.');
    return;
  }
  
  // Sepet verilerini checkout-data'ya kaydet
  const checkoutData = {
    totalAmount: cartStore.totalAmount,
    items: cartStore.items,
    timestamp: Date.now()
  };
  localStorage.setItem('checkout-data', JSON.stringify(checkoutData));
  
  // Drawer'ı kapat
  close();
  
  // Payment sayfasına yönlendir
  router.push('/payment');
};
</script>

<style scoped>
/* Animasyon */
.translate-x-0 {
  transform: translateX(0);
}

.translate-x-full {
  transform: translateX(100%);
}

/* Scrollbar */
.overflow-y-auto {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
  background-color: rgba(75, 85, 99, 0.5);
}
</style> 