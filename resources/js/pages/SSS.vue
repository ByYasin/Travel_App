<template>
  <div class="min-h-screen">
    <PageHeader 
      title="Sıkça Sorulan Sorular" 
      subtitle="Size yardımcı olmak için en çok sorulan soruları bir araya getirdik"
      background-image="https://images.pexels.com/photos/3184296/pexels-photo-3184296.jpeg?auto=compress&cs=tinysrgb&w=1200"
    />

    <!-- Ana İçerik -->
    <PageSection variant="light">
      <!-- Arama Bölümü -->
      <div class="max-w-2xl mx-auto mb-10">
        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Soru ara..."
            class="w-full px-6 py-4 text-lg rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-300"
          />
          <svg class="w-6 h-6 absolute right-6 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>

      <!-- Kategori Filtreleme -->
      <div class="max-w-3xl mx-auto mb-10">
        <div class="flex flex-wrap justify-center gap-3">
          <button 
            @click="selectedCategory = ''" 
            class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-sm"
            :class="selectedCategory === '' 
              ? 'bg-blue-600 text-white dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600' 
              : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
          >
            Tümü
          </button>
          <button 
            v-for="category in uniqueCategories" 
            :key="category"
            @click="selectedCategory = category"
            class="px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-sm"
            :class="selectedCategory === category 
              ? 'bg-blue-600 text-white dark:bg-blue-500 hover:bg-blue-700 dark:hover:bg-blue-600' 
              : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700'"
          >
            {{ getCategoryDisplayName(category) }}
          </button>
        </div>
      </div>

      <!-- SSS Listesi -->
      <div class="max-w-3xl mx-auto space-y-4">
        <div v-if="filteredFaqs.length === 0" class="text-center py-10">
          <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-1">Sonuç Bulunamadı</h3>
          <p class="text-gray-500 dark:text-gray-400">Aramanızla eşleşen soru bulunamadı.</p>
        </div>
        
        <transition-group 
          name="list" 
          tag="div" 
          class="space-y-4">
          <div v-for="(faq, index) in filteredFaqs" 
               :key="faq.question" 
               class="transform transition-all duration-300 hover:-translate-y-1">
            <div 
              class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-lg transition-all duration-300"
              :class="{'border-l-4 border-blue-500 dark:border-blue-400': faq.isOpen}">
              <button
                @click="toggleFaq(faq)"
                class="w-full px-8 py-6 flex items-center justify-between focus:outline-none group"
              >
                <span class="text-xl font-medium text-gray-900 dark:text-white group-hover:text-primary-500 dark:group-hover:text-primary-400 transition-colors duration-200">
                  {{ faq.question }}
                </span>
                <div class="ml-4 flex-shrink-0">
                  <div class="w-12 h-12 rounded-full bg-blue-500/90 dark:bg-blue-600/90 flex items-center justify-center transform transition-all duration-200 shadow-md hover:bg-blue-600/90 dark:hover:bg-blue-500/90"
                       :class="{ 'rotate-180': faq.isOpen }">
                    <svg class="w-7 h-7 text-white dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
                    </svg>
                  </div>
                </div>
              </button>
              <transition
                enter-active-class="transition-all duration-300 ease-out"
                leave-active-class="transition-all duration-200 ease-in"
                enter-from-class="opacity-0 max-h-0"
                enter-to-class="opacity-100 max-h-[1000px]"
                leave-from-class="opacity-100 max-h-[1000px]"
                leave-to-class="opacity-0 max-h-0"
              >
                <div
                  v-show="faq.isOpen"
                  class="px-8 pb-6 text-lg text-gray-600 dark:text-gray-300 border-t border-gray-100 dark:border-gray-700 overflow-hidden"
                >
                  <p>{{ faq.answer }}</p>
                  <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm">
                      {{ getCategoryDisplayName(faq.category) }}
                    </span>
                  </div>
                </div>
              </transition>
            </div>
          </div>
        </transition-group>
      </div>
    </PageSection>

    <!-- İletişim CTA -->
    <PageSection variant="accent">
      <div class="text-center mb-8">
        <h3 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-500 dark:to-indigo-400 inline-block text-transparent bg-clip-text mb-3">
          Aradığınız Cevabı Bulamadınız mı?
        </h3>
        <p class="text-gray-700 dark:text-gray-300 max-w-2xl mx-auto text-lg">
          Uzman destek ekibimiz size özel çözümler sunmak için her zaman hazır
        </p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- İletişim Formu -->
        <router-link to="/contact" class="group bg-white dark:bg-gray-800 border border-indigo-50 dark:border-gray-700 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden transform hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-indigo-600/5 dark:from-blue-500/5 dark:to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 relative z-10">İletişim Formu</h4>
          <p class="text-gray-600 dark:text-gray-400 mb-4 relative z-10">7/24 form ile mesaj gönderin, en kısa sürede dönüş sağlayalım</p>
          <span class="text-blue-600 dark:text-blue-400 font-medium flex items-center relative z-10">
            Forma Git
            <svg class="w-5 h-5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </span>
        </router-link>
        
        <!-- Telefon -->
        <a href="tel:+902121234567" class="group bg-white dark:bg-gray-800 border border-indigo-50 dark:border-gray-700 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden transform hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-indigo-600/5 dark:from-blue-500/5 dark:to-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
          </div>
          <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 relative z-10">Telefon</h4>
          <p class="text-gray-600 dark:text-gray-400 mb-4 relative z-10">Hafta içi 09:00-18:00 saatleri arasında bizi arayabilirsiniz</p>
          <span class="text-blue-600 dark:text-blue-400 font-medium flex items-center relative z-10">
            +90 (212) 123 45 67
            <svg class="w-5 h-5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </span>
        </a>
        
        <!-- WhatsApp -->
        <a href="https://wa.me/902121234567" class="group bg-white dark:bg-gray-800 border border-indigo-50 dark:border-gray-700 p-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden transform hover:-translate-y-1">
          <div class="absolute inset-0 bg-gradient-to-r from-green-600/5 to-emerald-600/5 dark:from-green-500/5 dark:to-emerald-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-4 relative z-10">
            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </div>
          <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 relative z-10">WhatsApp</h4>
          <p class="text-gray-600 dark:text-gray-400 mb-4 relative z-10">Anlık mesajlaşma ile hızlı destek alın</p>
          <span class="text-green-600 dark:text-green-400 font-medium flex items-center relative z-10">
            Mesaj Gönder
            <svg class="w-5 h-5 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </span>
        </a>
      </div>
    </PageSection>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import PageHeader from '@/components/PageHeader.vue';
import PageSection from '@/components/PageSection.vue';

const searchQuery = ref('');
const selectedCategory = ref('');

const faqs = ref([
  {
    question: 'Tur rezervasyonu nasıl yapabilirim?',
    answer: 'Tur rezervasyonu yapmak için istediğiniz turu seçtikten sonra "Rezervasyon Yap" butonuna tıklayarak ödeme adımlarını takip edebilirsiniz. Online ödeme sonrası rezervasyonunuz anında onaylanacaktır.',
    isOpen: false,
    category: 'rezervasyon'
  },
  {
    question: 'Ödeme seçenekleri nelerdir?',
    answer: 'Kredi kartı (Visa, Mastercard, Troy) ile güvenli ödeme yapabilirsiniz. Tüm ödemeleriniz SSL sertifikası ile güvence altındadır.',
    isOpen: false,
    category: 'ödeme'
  },
  {
    question: 'Rezervasyon iptal politikanız nedir?',
    answer: 'Tur başlangıç tarihinden 72 saat öncesine kadar yapılan iptallerde %100 iade yapılmaktadır. Daha sonraki iptallerde iade yapılamamaktadır.',
    isOpen: false,
    category: 'rezervasyon'
  },
  {
    question: 'Tur fiyatlarına neler dahildir?',
    answer: 'Tur fiyatlarına ulaşım, konaklama, rehberlik hizmetleri ve programda belirtilen tüm aktiviteler dahildir. Ekstra harcamalar ve bahşişler fiyata dahil değildir.',
    isOpen: false,
    category: 'tur'
  },
  {
    question: 'Çocuklar için yaş indirimi var mı?',
    answer: '0-6 yaş arası çocuklar ücretsiz, 7-12 yaş arası çocuklar %50 indirimli olarak tura katılabilirler.',
    isOpen: false,
    category: 'tur'
  },
  {
    question: 'Grup indirimi yapıyor musunuz?',
    answer: '10 kişi ve üzeri grup rezervasyonlarında %15 indirim uygulanmaktadır. Detaylı bilgi için lütfen bizimle iletişime geçin.',
    isOpen: false,
    category: 'ödeme'
  },
  {
    question: 'Tur rehberleri hangi dillerde hizmet vermektedir?',
    answer: 'Tur rehberlerimiz Türkçe, İngilizce, Almanca ve Rusça dillerinde hizmet vermektedir. Özel dil talepleri için lütfen rezervasyon sırasında belirtiniz.',
    isOpen: false,
    category: 'tur'
  },
  {
    question: 'Özel tur düzenleyebilir miyim?',
    answer: 'Evet, özel gruplar için talep üzerine özel tur düzenlenebilmektedir. İstediğiniz tarih, süre ve rota için lütfen bizimle iletişime geçin.',
    isOpen: false,
    category: 'rezervasyon'
  },
  {
    question: 'Turlarınız için sigorta sağlıyor musunuz?',
    answer: 'Evet, tüm turlarımız için seyahat sigortası seçeneği sunuyoruz. Rezervasyon esnasında ek ücret ile talep edebilirsiniz.',
    isOpen: false,
    category: 'sigorta'
  },
  {
    question: 'Bagaj limitiniz nedir?',
    answer: 'Kişi başı bir büyük valiz ve bir el bagajı taşıma hakkınız bulunmaktadır. Büyük valiz en fazla 23 kg, el bagajı ise en fazla 8 kg olmalıdır.',
    isOpen: false,
    category: 'tur'
  }
]);

const uniqueCategories = computed(() => {
  const categories = faqs.value.map(faq => faq.category);
  return [...new Set(categories)];
});

const filteredFaqs = computed(() => {
  let result = faqs.value;
  
  // Önce kategori filtresi uygula
  if (selectedCategory.value) {
    result = result.filter(faq => faq.category === selectedCategory.value);
  }
  
  // Sonra arama filtresi uygula
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(faq => 
      faq.question.toLowerCase().includes(query) || 
      faq.answer.toLowerCase().includes(query)
    );
  }
  
  return result;
});

const getCategoryDisplayName = (category) => {
  const categoryNames = {
    'rezervasyon': 'Rezervasyon',
    'ödeme': 'Ödeme',
    'tur': 'Turlar',
    'sigorta': 'Sigorta'
  };
  
  return categoryNames[category] || category.charAt(0).toUpperCase() + category.slice(1);
};

const toggleFaq = (faq) => {
  // Diğer tüm açık soruları kapat (isteğe bağlı özellik)
  // faqs.value.forEach(item => {
  //   if (item !== faq && item.isOpen) {
  //     item.isOpen = false;
  //   }
  // });
  
  faq.isOpen = !faq.isOpen;
};
</script>

<style scoped>
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

/* SSS Okları için özel stiller */
.rounded-full {
  transform-origin: center;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.shadow-md {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dark .shadow-md {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
}

/* Ok hover efekti */
.rounded-full:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 10px -2px rgba(37, 99, 235, 0.25);
}

.dark .rounded-full:hover {
  box-shadow: 0 6px 10px -2px rgba(30, 64, 175, 0.3);
}

/* Liste animasyonu */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from {
  opacity: 0;
  transform: translateY(30px);
}
.list-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}
</style> 