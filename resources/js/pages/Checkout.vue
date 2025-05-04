<template>
  <div class="container mx-auto px-4 py-12 pt-20 md:pt-24 min-h-screen">
    <div class="max-w-7xl mx-auto">
      <!-- Başlık ve Adımlar -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Ödeme</h1>
        <div class="flex items-center justify-between max-w-3xl">
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-medium">1</div>
            <span class="mt-2 text-sm text-gray-600 dark:text-gray-300">Sepet</span>
          </div>
          <div class="flex-1 h-1 bg-primary-200 dark:bg-primary-800 mx-2"></div>
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-medium">2</div>
            <span class="mt-2 text-sm text-gray-600 dark:text-gray-300">Ödeme</span>
          </div>
          <div class="flex-1 h-1 bg-gray-200 dark:bg-gray-700 mx-2"></div>
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-medium">3</div>
            <span class="mt-2 text-sm text-gray-500 dark:text-gray-400">Onay</span>
          </div>
        </div>
      </div>
      
      <!-- Hata Mesajı (Eğer sepetten gelmeden sayfaya erişilmişse) -->
      <div v-if="!hasCheckoutData" class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 text-center space-y-6">
        <div class="text-gray-500 dark:text-gray-400 text-lg">
          <svg class="mx-auto h-16 w-16 mb-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p>Ödeme sayfasına erişebilmek için sepetinizde ürün olmalıdır.</p>
        </div>
        <router-link to="/" class="inline-block px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition duration-300">
          Ana Sayfaya Dön
        </router-link>
      </div>
      
      <!-- Ödeme Formu ve Sipariş Özeti -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Ödeme Formu -->
        <div class="lg:col-span-2">
          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Ödeme Bilgileri</h2>
            
            <!-- Kart Bilgileri -->
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kart Üzerindeki İsim</label>
                <input 
                  v-model="cardName" 
                  type="text" 
                  class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                  placeholder="Kart üzerindeki isim"
                />
                <p v-if="errors.cardName" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.cardName }}</p>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kart Numarası</label>
                <div class="relative">
                  <input 
                    v-model="cardNumber" 
                    type="text" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="**** **** **** ****"
                    maxlength="19"
                    @input="formatCardNumber"
                  />
                  <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center space-x-2">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                      <path d="M4 9h16v6H4z" fill="none" stroke="currentColor" stroke-width="2" />
                    </svg>
                  </div>
                </div>
                <p v-if="errors.cardNumber" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.cardNumber }}</p>
              </div>
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Son Kullanma Tarihi</label>
                  <div class="flex space-x-2">
                    <select 
                      v-model="expiryMonth" 
                      class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                      <option value="" disabled>Ay</option>
                      <option v-for="month in 12" :key="month" :value="month.toString().padStart(2, '0')">
                        {{ month.toString().padStart(2, '0') }}
                      </option>
                    </select>
                    <span class="flex items-center text-gray-500">/</span>
                    <select 
                      v-model="expiryYear" 
                      class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                      <option value="" disabled>Yıl</option>
                      <option v-for="year in years" :key="year" :value="year.toString().slice(-2)">
                        {{ year }}
                      </option>
                    </select>
                  </div>
                  <p v-if="errors.expiry" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.expiry }}</p>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CVV</label>
                  <input 
                    v-model="cvv" 
                    type="text" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="***"
                    maxlength="3"
                    @input="formatCVV"
                  />
                  <p v-if="errors.cvv" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.cvv }}</p>
                </div>
              </div>
            </div>
            
            <!-- Fatura Bilgileri -->
            <div class="mt-8">
              <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Fatura Bilgileri</h3>
              
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad</label>
                    <input 
                      v-model="firstName" 
                      type="text" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Adınız"
                    />
                    <p v-if="errors.firstName" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.firstName }}</p>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Soyad</label>
                    <input 
                      v-model="lastName" 
                      type="text" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Soyadınız"
                    />
                    <p v-if="errors.lastName" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.lastName }}</p>
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta</label>
                  <input 
                    v-model="email" 
                    type="email" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="E-posta adresiniz"
                  />
                  <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.email }}</p>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon</label>
                  <input 
                    v-model="phone" 
                    type="tel" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Telefon numaranız"
                    @input="formatPhone"
                  />
                  <p v-if="errors.phone" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.phone }}</p>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adres</label>
                  <textarea 
                    v-model="address" 
                    rows="3" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Adresiniz"
                  ></textarea>
                  <p v-if="errors.address" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.address }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Şehir</label>
                    <input 
                      v-model="city" 
                      type="text" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Şehir"
                    />
                    <p v-if="errors.city" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.city }}</p>
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Posta Kodu</label>
                    <input 
                      v-model="postalCode" 
                      type="text" 
                      class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                      placeholder="Posta kodu"
                    />
                    <p v-if="errors.postalCode" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.postalCode }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Sipariş Özeti -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 sticky top-20">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Sipariş Özeti</h2>
            
            <!-- Sepet Ürünleri Listesi -->
            <div class="max-h-60 overflow-y-auto mb-4 space-y-3">
              <div v-for="item in checkoutData.items" :key="item.id" class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3">
                  <div class="w-12 h-12 rounded-md overflow-hidden">
                    <img :src="item.featured_image || '/images/tours/default.jpg'" :alt="item.title" class="w-full h-full object-cover">
                  </div>
                  <div>
                    <h3 class="text-sm font-medium text-gray-800 dark:text-white">{{ item.title }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ item.participants }} Kişi</p>
                  </div>
                </div>
                <div class="text-sm font-medium text-gray-800 dark:text-white">
                  {{ formatPrice(calculateItemTotal(item)) }}
                </div>
              </div>
            </div>
            
            <!-- Fiyat Toplamları -->
            <div class="space-y-2 pb-4 border-b border-gray-200 dark:border-gray-700">
              <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Ara Toplam:</span>
                <span>{{ formatPrice(checkoutData.totalAmount) }}</span>
              </div>
              
              <!-- Varsa KDV veya ek ücretler de eklenebilir -->
              
              <div class="flex justify-between font-semibold text-gray-800 dark:text-white pt-2">
                <span>Toplam:</span>
                <span class="text-primary-600 dark:text-primary-400">{{ formatPrice(checkoutData.totalAmount) }}</span>
              </div>
            </div>
            
            <!-- Ödeme Butonları -->
            <div class="mt-6">
              <button 
                @click="processPayment" 
                :disabled="processing"
                class="w-full py-3 px-6 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition duration-300 flex items-center justify-center shadow-md hover:shadow-lg disabled:opacity-70 disabled:cursor-not-allowed"
              >
                <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ processing ? 'İşleniyor...' : 'Ödemeyi Tamamla' }}</span>
              </button>
              
              <button 
                @click="$router.push('/sepet')" 
                class="w-full mt-3 py-2 px-6 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded-lg transition duration-300 flex items-center justify-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Sepete Dön
              </button>
            </div>
            
            <!-- Güvenli Ödeme Bilgisi -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
              <div class="flex items-center justify-center mb-2">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Güvenli Ödeme</span>
              </div>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                Tüm ödemeler güvenli bir şekilde işlenir. Kart bilgileriniz şifrelenir ve güvenli bir şekilde saklanır.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useCartStore } from '@/store/cart';

const router = useRouter();
const toast = useToast();
const cartStore = useCartStore();

// Form verileri
const cardName = ref('');
const cardNumber = ref('');
const expiryMonth = ref('');
const expiryYear = ref('');
const cvv = ref('');
const firstName = ref('');
const lastName = ref('');
const email = ref('');
const phone = ref('');
const address = ref('');
const city = ref('');
const postalCode = ref('');

// Hata mesajları
const errors = ref({});

// İşlem durumu
const processing = ref(false);
const checkoutData = ref(null);
const hasCheckoutData = ref(false);

// Son kullanma tarihi için yıllar
const currentYear = new Date().getFullYear();
const years = Array.from({ length: 10 }, (_, i) => currentYear + i);

// Sayfa yüklendiğinde checkout verilerini kontrol et
onMounted(() => {
  const checkoutDataStr = localStorage.getItem('checkout-data');
  if (checkoutDataStr) {
    try {
      const parsedData = JSON.parse(checkoutDataStr);
      // Zaman kontrolü: 30 dakikadan eski değilse
      const timeElapsed = Date.now() - parsedData.timestamp;
      if (timeElapsed < 30 * 60 * 1000) {
        checkoutData.value = parsedData;
        hasCheckoutData.value = true;
        
        // Kayıtlı kullanıcıysa bilgileri doldur
        prefillUserData();
      } else {
        toast.error('Ödeme oturumunuz zaman aşımına uğradı.');
        router.push('/');
      }
    } catch (error) {
      console.error('Checkout verisi çözülemedi:', error);
      localStorage.removeItem('checkout-data');
      router.push('/');
    }
  } else if (cartStore.totalItems > 0) {
    // Eğer localStorage'da veri yoksa ama sepette ürün varsa
    checkoutData.value = {
      totalAmount: cartStore.totalAmount,
      items: cartStore.items,
      timestamp: Date.now()
    };
    hasCheckoutData.value = true;
    localStorage.setItem('checkout-data', JSON.stringify(checkoutData.value));
    
    // Kayıtlı kullanıcıysa bilgileri doldur
    prefillUserData();
  } else {
    hasCheckoutData.value = false;
  }
});

// Kayıtlı kullanıcı bilgilerini forma doldur
const prefillUserData = () => {
  // Kullanıcı localStorage, vuex veya api'den alınabilir
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  if (user.name) {
    const nameParts = user.name.split(' ');
    if (nameParts.length > 1) {
      firstName.value = nameParts[0];
      lastName.value = nameParts.slice(1).join(' ');
    } else {
      firstName.value = user.name;
    }
  }
  
  if (user.email) {
    email.value = user.email;
  }
  
  if (user.phone) {
    phone.value = user.phone;
  }
  
  // Adres bilgileri (eğer varsa)
  if (user.address) {
    address.value = user.address;
  }
  
  if (user.city) {
    city.value = user.city;
  }
  
  if (user.postal_code) {
    postalCode.value = user.postal_code;
  }
};

// Kart numarasını biçimlendirme
const formatCardNumber = () => {
  // Sadece rakamları al ve boşluk ile gruplandır
  const value = cardNumber.value.replace(/\D/g, '');
  const formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1 ');
  cardNumber.value = formattedValue.substring(0, 19); // Maks 16 karakter + 3 boşluk
};

// CVV biçimlendirme
const formatCVV = () => {
  // Sadece rakamları al
  cvv.value = cvv.value.replace(/\D/g, '').substring(0, 3);
};

// Telefon numarası biçimlendirme
const formatPhone = () => {
  let value = phone.value.replace(/\D/g, '');
  
  // En fazla 10 rakam
  value = value.substring(0, 10);
  
  // (XXX) XXX-XXXX formatına dönüştür
  if (value.length > 0) {
    if (value.length <= 3) {
      phone.value = value;
    } else if (value.length <= 6) {
      phone.value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
    } else {
      phone.value = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
    }
  }
};

// Fiyat formatlaması
const formatPrice = (price) => {
  if (!price && price !== 0) return '0,00 ₺';
  
  const priceValue = parseFloat(price.toString().replace(/[^\d.-]/g, ''));
  return priceValue.toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }) + ' ₺';
};

// Ürün bazında toplam fiyat hesaplama
const calculateItemTotal = (item) => {
  const price = parseFloat(item.price.toString().replace(/[^\d.-]/g, ''));
  return price * item.participants;
};

// Form doğrulama
const validateForm = () => {
  errors.value = {};
  
  if (!cardName.value) {
    errors.value.cardName = 'Kart üzerindeki isim gereklidir';
  }
  
  if (!cardNumber.value) {
    errors.value.cardNumber = 'Kart numarası gereklidir';
  } else if (cardNumber.value.replace(/\s/g, '').length < 16) {
    errors.value.cardNumber = 'Geçerli bir kart numarası giriniz';
  }
  
  if (!expiryMonth.value || !expiryYear.value) {
    errors.value.expiry = 'Son kullanma tarihi gereklidir';
  }
  
  if (!cvv.value) {
    errors.value.cvv = 'CVV gereklidir';
  } else if (cvv.value.length < 3) {
    errors.value.cvv = 'Geçerli bir CVV giriniz';
  }
  
  if (!firstName.value) {
    errors.value.firstName = 'Ad gereklidir';
  }
  
  if (!lastName.value) {
    errors.value.lastName = 'Soyad gereklidir';
  }
  
  if (!email.value) {
    errors.value.email = 'E-posta gereklidir';
  } else if (!/\S+@\S+\.\S+/.test(email.value)) {
    errors.value.email = 'Geçerli bir e-posta adresi giriniz';
  }
  
  if (!phone.value) {
    errors.value.phone = 'Telefon numarası gereklidir';
  }
  
  if (!address.value) {
    errors.value.address = 'Adres gereklidir';
  }
  
  if (!city.value) {
    errors.value.city = 'Şehir gereklidir';
  }
  
  return Object.keys(errors.value).length === 0;
};

// Ödeme işlemini başlat
const processPayment = async () => {
  if (!validateForm()) {
    toast.error('Lütfen tüm alanları doğru şekilde doldurunuz');
    return;
  }
  
  processing.value = true;
  
  try {
    // API isteği (gerçek uygulamada bir API'ye istek atılır)
    await new Promise(resolve => setTimeout(resolve, 2000)); // Simülasyon için 2 saniye bekle
    
    // Başarılı ödeme sonrası işlemler
    // 1. Sepeti temizle
    cartStore.completePayment();
    
    // 2. LocalStorage'dan checkout verisini temizle
    localStorage.removeItem('checkout-data');
    
    // 3. Onay sayfasına yönlendir
    router.push('/onay');
    
    // 4. Bildirim göster
    toast.success('Ödeme işleminiz başarıyla tamamlandı!');
  } catch (error) {
    console.error('Ödeme hatası:', error);
    toast.error('Ödeme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
  } finally {
    processing.value = false;
  }
};
</script> 