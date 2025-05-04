import { defineStore } from 'pinia';
import { useToast } from 'vue-toastification';

// LocalStorage yardımcı fonksiyonları
const saveToLocalStorage = (key, data) => {
  try {
    localStorage.setItem(key, JSON.stringify(data));
    return true;
  } catch (error) {
    console.error(`LocalStorage kayıt hatası (${key}):`, error);
    return false;
  }
};

const getFromLocalStorage = (key, defaultValue = null) => {
  try {
    const data = localStorage.getItem(key);
    if (!data) return defaultValue;
    return JSON.parse(data);
  } catch (error) {
    console.error(`LocalStorage okuma hatası (${key}):`, error);
    return defaultValue;
  }
};

export const useCartStore = defineStore('cart', {
  state: () => {
    // LocalStorage'dan mevcut verileri almaya çalış, hata olursa boş dizi kullan
    let savedItems = [];
    try {
      const savedState = getFromLocalStorage('cart-store', { items: [] });
      savedItems = Array.isArray(savedState.items) ? savedState.items : [];
    } catch (error) {
      console.error('Cart store başlatma hatası:', error);
    }
    
    return {
      items: savedItems,
      loading: false,
      lastError: null,
    };
  },
  
  persist: {
    key: 'cart-store',
    storage: localStorage,
    paths: ['items'],
  },
  
  getters: {
    totalItems: (state) => state.items.length,
    totalAmount: (state) => {
      try {
        return state.items.reduce((total, item) => {
          const price = parseFloat((item.price || '0').toString().replace(/[^\d.-]/g, '')) || 0;
          const participants = item.participants || 1;
          return total + (price * participants);
        }, 0);
      } catch (error) {
        console.error('Price calculation error:', error);
        return 0;
      }
    }
  },
  
  actions: {
    addToCart(tour, participants, date) {
      const toast = useToast();
      console.log('Sepete ekleniyor:', { tour, participants, date });
      
      try {
        // Tur nesnesi kontrolü
        if (!tour) {
          console.error('Tur nesnesi bulunamadı.');
          toast.error('Tur bilgileri eksik, sepete eklenemedi.');
          return;
        }
        
        // Tur ID kontrolü
        if (!tour.id) {
          console.error('Tur ID\'si bulunamadı:', tour);
          toast.error('Tur kimliği eksik, sepete eklenemedi.');
          return;
        }
        
        // Tur title kontrolü
        if (!tour.title) {
          console.warn('Tur başlığı eksik:', tour);
          tour.title = 'İsimsiz Tur';
        }
        
        // Tur fiyatı kontrolü
        if (!tour.price) {
          console.warn('Tur fiyatı eksik veya sıfır:', tour);
        }
        
        // Eğer bu tur zaten sepette varsa katılımcı sayısını güncelle
        const existingItem = this.items.find(item => item.id === tour.id);
        
        if (existingItem) {
          existingItem.participants = participants || 1;
          existingItem.date = date;
          toast.info(`${tour.title} turu sepette güncellendi!`);
          console.log('Updated cart item:', existingItem);
        } else {
          // Yeni ürün ekle
          const newItem = {
            id: tour.id,
            title: tour.title || 'İsimsiz Tur',
            price: tour.price || 0,
            featured_image: tour.featured_image || '/images/tours/default.jpg',
            participants: participants || 1,
            date: date || new Date().toISOString().substr(0, 10),
            slug: tour.slug || tour.id
          };
          
          this.items.push(newItem);
          toast.success(`${newItem.title} sepete eklendi!`);
          console.log('Added new item to cart:', newItem);
        }
        
        // LocalStorage'a kaydet
        this.saveCart();
        
        // Sepet drawer'ını açmak için event fırlat
        window.dispatchEvent(new CustomEvent('cart-updated', { 
          detail: { action: 'added', tour: tour }
        }));
        
        console.log('Current cart:', this.items);
        return true;
      } catch (error) {
        console.error('Error adding to cart:', error);
        this.lastError = error.message;
        
        // Daha detaylı hata mesajı
        let errorMessage = 'Sepete eklenirken bir hata oluştu.';
        if (error.message) {
          errorMessage += ` Hata: ${error.message}`;
        }
        
        toast.error(errorMessage);
        return false;
      }
    },
    
    removeFromCart(itemId) {
      const toast = useToast();
      try {
        const index = this.items.findIndex(item => item.id === itemId);
        
        if (index !== -1) {
          const item = this.items[index];
          this.items.splice(index, 1);
          toast.success(`${item.title} sepetten çıkarıldı`);
          console.log('Removed item from cart:', item);
          
          // LocalStorage güncelle
          this.saveCart();
        }
      } catch (error) {
        console.error('Error removing from cart:', error);
        this.lastError = error.message;
        toast.error('Sepetten çıkarırken bir hata oluştu.');
      }
    },
    
    clearCart() {
      try {
        this.items = [];
        this.saveCart();
        
        const toast = useToast();
        toast.info('Sepet temizlendi');
        console.log('Cart cleared');
      } catch (error) {
        console.error('Error clearing cart:', error);
        this.lastError = error.message;
      }
    },
    
    // Sepet verilerini LocalStorage'a kaydet
    saveCart() {
      try {
        saveToLocalStorage('cart-store', { items: this.items });
        return true;
      } catch (error) {
        console.error('Cart save error:', error);
        this.lastError = error.message;
        return false;
      }
    },
    
    // Ödeme başarılı olduktan sonra sepeti temizle
    completePayment() {
      this.clearCart();
    },
    
    // Sepet verilerini manuel olarak kontrol et ve yenile
    checkAndFixCartData() {
      try {
        console.log('Sepet verilerini kontrol ediliyor...');
        
        // 1. LocalStorage'dan doğrudan veriyi al
        const storedData = getFromLocalStorage('cart-store');
        console.log('LocalStorage durumu:', storedData);
        
        // 2. Sepet verilerini değerlendirerek düzelt
        if (storedData && storedData.items && Array.isArray(storedData.items)) {
          // Geçerli verileri ayıkla
          const validItems = storedData.items.filter(item => item && item.id);
          
          // Geçerli verileri sepete ekle
          this.items = validItems.map(item => ({
            ...item,
            participants: item.participants || 1,
            price: item.price || 0
          }));
          
          console.log('Sepet düzeltme sonrası:', this.items);
          
          // Düzeltilmiş verileri kaydet
          this.saveCart();
          console.log('Düzeltilmiş sepet verisi kaydedildi');
        } else {
          console.warn('LocalStorage sepet verisi bulunamadı veya bozuk');
          
          // LocalStorage'da doğru formatla verileri kaydet
          if (this.items.length > 0) {
            this.saveCart();
          } else {
            // Sepet zaten boş ise boş bir sepet yapısı kaydet
            saveToLocalStorage('cart-store', { items: [] });
          }
        }
        
        return true;
      } catch (error) {
        console.error('Error checking cart data:', error);
        this.lastError = error.message;
        return false;
      }
    },
    
    // Test amaçlı örnek tur ekle
    addTestItem() {
      const testTour = {
        id: Date.now(), // Benzersiz ID oluştur
        title: 'Test Turu',
        price: 1500,
        featured_image: '/images/tours/default.jpg',
        slug: 'test-tour',
      };
      
      this.addToCart(testTour, 2, new Date().toISOString().split('T')[0]);
      
      return testTour.id; // Eklenen turun ID'sini dön
    }
  }
}); 