<template>
  <div class="container mx-auto px-4 py-12 pt-20 md:pt-24 min-h-screen">
    <div class="max-w-3xl mx-auto">
      <!-- Onay Mesajı -->
      <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
          <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Siparişiniz Onaylandı!</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">
          Ödemeniz başarıyla alındı. Rezervasyon detaylarınız aşağıda yer almaktadır.
        </p>
        
        <!-- Sipariş Numarası -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6 inline-block">
          <p class="text-gray-500 dark:text-gray-400 text-sm">Sipariş Numarası</p>
          <p class="text-lg font-semibold text-gray-800 dark:text-white">{{ orderNumber }}</p>
        </div>
        
        <!-- Sepet Özeti -->
        <div class="mb-8">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4 text-left">Sipariş Özeti</h2>
          
          <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Tur
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Kişi Sayısı
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Toplam
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="item in orderItems" :key="item.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                    {{ item.title }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white text-right">
                    {{ item.participants }} kişi
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white text-right">
                    {{ formatPrice(item.totalPrice) }}
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300 text-right">
                    Toplam:
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-primary-600 dark:text-primary-400 text-right">
                    {{ formatPrice(orderTotal) }}
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        
        <!-- Bilgilendirme Mesajı -->
        <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4 mb-8 text-left">
          <div class="flex items-start">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Bilgilendirme</h3>
              <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                <p>Rezervasyon detaylarınız {{ maskEmail(customerEmail) }} adresine gönderildi. Lütfen e-posta kutunuzu kontrol ediniz.</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Butonlar -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <router-link to="/" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center justify-center shadow-sm hover:shadow-md">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Ana Sayfaya Dön
          </router-link>
          
          <router-link to="/profile/reservations" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white rounded-lg transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Rezervasyonlarım
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';

const router = useRouter();
const toast = useToast();

// Örnek sipariş verileri (bu veriler normalde API'den gelir)
const orderNumber = ref(generateOrderNumber());
const orderItems = ref([]);
const orderTotal = ref(0);
const customerEmail = ref('');

// Sipariş numarası oluşturma
function generateOrderNumber() {
  const prefix = 'TUR';
  const timestamp = new Date().getTime().toString().slice(-6);
  const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
  return `${prefix}-${timestamp}-${random}`;
}

// E-posta maskeleme (örn: jo***@example.com)
function maskEmail(email) {
  if (!email) return '';
  const parts = email.split('@');
  if (parts.length !== 2) return email;
  
  const name = parts[0];
  const domain = parts[1];
  
  // İlk 2 karakteri göster, gerisini maskele
  const maskedName = name.substring(0, 2) + '*'.repeat(Math.max(1, name.length - 2));
  
  return `${maskedName}@${domain}`;
}

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

// Sayfa yüklendiğinde
onMounted(() => {
  // localStorage'dan checkout verilerini kontrol et
  // Gerçek uygulamada bu veriler backend'den alınır
  const user = JSON.parse(localStorage.getItem('user') || '{}');
  if (user.email) {
    customerEmail.value = user.email;
  } else {
    customerEmail.value = 'kullanici@example.com';
  }

  try {
    const lastOrderData = JSON.parse(localStorage.getItem('last-order-data') || null);
    
    if (lastOrderData) {
      // Sipariş öğelerini hazırla
      orderItems.value = lastOrderData.items.map(item => ({
        ...item,
        totalPrice: calculateItemTotal(item)
      }));
      
      // Toplam tutarı hesapla
      orderTotal.value = lastOrderData.totalAmount;
    } else {
      // Simüle edilmiş veriler (gerçek uygulamada API'den gelir)
      orderItems.value = [
        {
          id: 1,
          title: "Kapadokya Turu",
          participants: 2,
          totalPrice: 4000
        }
      ];
      orderTotal.value = 4000;
      
      // Bir bildirim göster
      toast.info('Sipariş detayları bulunamadı. Örnek veriler gösteriliyor.');
    }
  } catch (error) {
    console.error('Sipariş verisi çözümlenirken hata oluştu:', error);
    
    // Hata durumunda ana sayfaya yönlendir
    router.push('/');
    toast.error('Bir hata oluştu, ana sayfaya yönlendiriliyorsunuz.');
  }
});
</script> 