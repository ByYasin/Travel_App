<template>
  <AdminLayout>
    <div class="container mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">İletişim Mesajları</h1>
          <div class="flex space-x-2">
            <button class="px-4 py-2 bg-green-600 text-white rounded-lg flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Dışa Aktar
            </button>
            <button class="px-4 py-2 bg-red-600 text-white rounded-lg flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Seçilenleri Sil
            </button>
          </div>
        </div>

        <!-- Filtreleme ve Arama -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <div class="flex-1">
            <div class="relative">
              <input
                type="text"
                v-model="searchQuery"
                placeholder="İsim, e-posta veya mesaj içeriği ara..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
          </div>
          
          <div class="flex gap-4">
            <div class="w-40">
              <select
                v-model="statusFilter"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="">Tüm Durumlar</option>
                <option value="new">Yeni</option>
                <option value="inProgress">İşleniyor</option>
                <option value="completed">Tamamlandı</option>
                <option value="archived">Arşivlendi</option>
              </select>
            </div>
            
            <div class="w-40">
              <select
                v-model="dateFilter"
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="">Tüm Tarihler</option>
                <option value="today">Bugün</option>
                <option value="week">Bu Hafta</option>
                <option value="month">Bu Ay</option>
                <option value="year">Bu Yıl</option>
              </select>
            </div>
          </div>
        </div>

        <!-- İletişim Tablosu -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  <div class="flex items-center">
                    <input
                      type="checkbox"
                      class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                      @change="selectAll"
                      :checked="allSelected"
                    />
                  </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  İsim
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  E-posta
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Konu
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Tarih
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Durum
                </th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  İşlemler
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-if="filteredContacts.length === 0 && !isLoading">
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  <div class="flex flex-col items-center py-6">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <p class="text-lg font-medium">Mesaj Bulunamadı</p>
                    <p class="text-sm mt-1">Arama kriterleriyle eşleşen mesaj bulunamadı.</p>
                  </div>
                </td>
              </tr>

              <tr v-else-if="isLoading" class="animate-pulse">
                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  <div class="flex flex-col items-center py-6">
                    <div class="w-12 h-12 rounded-full bg-gray-300 dark:bg-gray-600 mb-4"></div>
                    <div class="h-5 w-48 bg-gray-300 dark:bg-gray-600 rounded mb-2"></div>
                    <div class="h-4 w-64 bg-gray-300 dark:bg-gray-600 rounded"></div>
                  </div>
                </td>
              </tr>

              <tr 
                v-for="contact in filteredContacts" 
                :key="contact.id" 
                class="hover:bg-gray-50 dark:hover:bg-gray-700"
                :class="{'bg-blue-50 dark:bg-blue-900/10': contact.isSelected}"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <input
                      type="checkbox"
                      class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                      v-model="contact.isSelected"
                    />
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-1">
                      <div class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ contact.name }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ contact.phone }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">{{ contact.email }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 dark:text-white truncate max-w-xs">{{ contact.subject }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(contact.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300': contact.status === 'new',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300': contact.status === 'inProgress',
                      'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300': contact.status === 'completed',
                      'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': contact.status === 'archived'
                    }"
                  >
                    {{ getStatusText(contact.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center justify-end space-x-2">
                    <button @click="viewContact(contact)" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                    <button @click="deleteContact(contact.id)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Paginator -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
              Toplam <span class="font-medium text-gray-900 dark:text-white">{{ contacts.length }}</span> mesaj
            </div>
            
            <div class="flex space-x-1">
              <button class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                Önceki
              </button>
              <button class="px-3 py-1 rounded-md bg-primary-50 dark:bg-primary-900/30 border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300">
                1
              </button>
              <button class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                2
              </button>
              <button class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                3
              </button>
              <button class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300">
                Sonraki
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Mesaj Detay Modal -->
    <div v-if="selectedContact" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true" @click="selectedContact = null"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
          <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                  {{ selectedContact.subject }}
                </h3>
                
                <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                  <div class="flex justify-between items-center mb-2">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      <span class="font-medium text-gray-900 dark:text-white">{{ selectedContact.name }}</span> tarafından
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ formatDate(selectedContact.created_at) }}
                    </div>
                  </div>
                  
                  <div class="flex flex-col sm:flex-row sm:space-x-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <div>
                      <span class="font-medium">E-posta:</span> {{ selectedContact.email }}
                    </div>
                    <div>
                      <span class="font-medium">Telefon:</span> {{ selectedContact.phone }}
                    </div>
                  </div>
                </div>
                
                <div class="mt-4">
                  <p class="text-sm text-gray-900 dark:text-white whitespace-pre-line">
                    {{ selectedContact.message }}
                  </p>
                </div>
                
                <div class="mt-6">
                  <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Durum Güncelle</h4>
                  <div class="flex space-x-2">
                    <button
                      v-for="status in ['new', 'inProgress', 'completed', 'archived']"
                      :key="status"
                      @click="updateContactStatus(selectedContact.id, status)"
                      class="px-3 py-1 text-xs rounded-full"
                      :class="{
                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border-2 border-yellow-300 dark:border-yellow-500': status === 'new' && selectedContact.status === 'new',
                        'bg-yellow-50 text-yellow-800 dark:bg-yellow-900/10 dark:text-yellow-300': status === 'new' && selectedContact.status !== 'new',
                        'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-2 border-blue-300 dark:border-blue-500': status === 'inProgress' && selectedContact.status === 'inProgress',
                        'bg-blue-50 text-blue-800 dark:bg-blue-900/10 dark:text-blue-300': status === 'inProgress' && selectedContact.status !== 'inProgress',
                        'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-2 border-green-300 dark:border-green-500': status === 'completed' && selectedContact.status === 'completed',
                        'bg-green-50 text-green-800 dark:bg-green-900/10 dark:text-green-300': status === 'completed' && selectedContact.status !== 'completed',
                        'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-2 border-gray-300 dark:border-gray-500': status === 'archived' && selectedContact.status === 'archived',
                        'bg-gray-50 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300': status === 'archived' && selectedContact.status !== 'archived',
                      }"
                    >
                      {{ getStatusText(status) }}
                    </button>
                  </div>
                </div>
                
                <div class="mt-6">
                  <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Notlar</h4>
                  <textarea
                    v-model="selectedContact.notes"
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Mesajla ilgili notlar..."
                  ></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="button"
              @click="saveContactChanges"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Kaydet
            </button>
            <button
              type="button"
              @click="selectedContact = null"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Kapat
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

// Yükleme durumu
const isLoading = ref(true);

// Filtreleme değişkenleri
const searchQuery = ref('');
const statusFilter = ref('');
const dateFilter = ref('');

// Seçili mesaj
const selectedContact = ref(null);

// Örnek iletişim mesajları
const contacts = ref([
  {
    id: 1,
    name: 'Mehmet Yılmaz',
    email: 'mehmet.yilmaz@example.com',
    phone: '0532 123 4567',
    subject: 'Kapadokya Turu Hakkında Bilgi',
    message: 'Merhaba,\n\nKapadokya turu hakkında detaylı bilgi almak istiyorum. Tur programı, konaklama ve fiyatlandırma hakkında bilgi verebilir misiniz?\n\nTeşekkürler,\nMehmet Yılmaz',
    created_at: '2023-05-15T14:32:00',
    status: 'new',
    notes: '',
    isSelected: false
  },
  {
    id: 2,
    name: 'Ayşe Demir',
    email: 'ayse.demir@example.com',
    phone: '0533 456 7890',
    subject: 'Rezervasyon İptali',
    message: 'Sayın İlgili,\n\n10 Haziran 2023 tarihli İstanbul Kültür Turu rezervasyonumu iptal etmek istiyorum. İade koşulları hakkında bilgi alabilir miyim?\n\nSaygılarımla,\nAyşe Demir',
    created_at: '2023-05-18T09:15:00',
    status: 'inProgress',
    notes: 'Müşteriye iade koşulları mail ile bildirildi. Yanıt bekleniyor.',
    isSelected: false
  },
  {
    id: 3,
    name: 'Ali Kaya',
    email: 'ali.kaya@example.com',
    phone: '0555 789 1234',
    subject: 'Grup Rezervasyonu',
    message: 'Merhaba,\n\n15 kişilik bir grup için Antalya & Alanya Kıyıları turu düşünüyoruz. 10-15 Temmuz 2023 tarihleri arasında grup indirimi mevcut mu? Ayrıca 10 yaş altı çocuklar için özel fiyatlandırma var mı?\n\nCevaplarınızı bekliyorum.\nAli Kaya',
    created_at: '2023-05-20T16:45:00',
    status: 'completed',
    notes: 'Grup indirimi uygulandı, rezervasyon tamamlandı.',
    isSelected: false
  },
  {
    id: 4,
    name: 'Zeynep Şahin',
    email: 'zeynep.sahin@example.com',
    phone: '0544 321 6789',
    subject: 'Özel Tur Talebi',
    message: 'Merhaba,\n\nKendim ve eşim için Karadeniz bölgesinde özel bir tur planlamak istiyoruz. Standart turlarınız dışında özel tur hizmeti sunuyor musunuz? Yaklaşık 1 haftalık bir program düşünüyoruz.\n\nYardımcı olursanız sevinirim.\nZeynep Şahin',
    created_at: '2023-05-25T11:20:00',
    status: 'archived',
    notes: 'Özel tur teklifi gönderildi. Müşteri farklı bir firma ile anlaştı.',
    isSelected: false
  },
  {
    id: 5,
    name: 'Emre Toprak',
    email: 'emre.toprak@example.com',
    phone: '0542 567 8901',
    subject: 'Ödeme Sorunu',
    message: 'Değerli Yetkili,\n\nDün saat 14:30 civarında Efes Antik Kenti turu için ödeme yapmaya çalıştım fakat işlem tamamlanmadan sayfa hata verdi. Kartımdan para çekildi ancak rezervasyonumu göremiyorum. Yardımcı olabilir misiniz?\n\nSaygılarımla,\nEmre Toprak',
    created_at: '2023-05-27T08:05:00',
    status: 'inProgress',
    notes: 'Ödeme kontrol edildi. Rezervasyon sistemde oluşturulacak.',
    isSelected: false
  }
]);

// Filtrelenmiş mesajlar
const filteredContacts = computed(() => {
  let result = [...contacts.value];
  
  // Arama filtresi
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(contact => 
      contact.name.toLowerCase().includes(query) ||
      contact.email.toLowerCase().includes(query) ||
      contact.subject.toLowerCase().includes(query) ||
      contact.message.toLowerCase().includes(query)
    );
  }
  
  // Durum filtresi
  if (statusFilter.value) {
    result = result.filter(contact => contact.status === statusFilter.value);
  }
  
  // Tarih filtresi
  if (dateFilter.value) {
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    
    result = result.filter(contact => {
      const contactDate = new Date(contact.created_at);
      
      switch(dateFilter.value) {
        case 'today':
          return contactDate >= today;
        case 'week':
          const weekStart = new Date(today);
          weekStart.setDate(today.getDate() - today.getDay());
          return contactDate >= weekStart;
        case 'month':
          const monthStart = new Date(today.getFullYear(), today.getMonth(), 1);
          return contactDate >= monthStart;
        case 'year':
          const yearStart = new Date(today.getFullYear(), 0, 1);
          return contactDate >= yearStart;
        default:
          return true;
      }
    });
  }
  
  return result;
});

// Tüm mesajların seçilip seçilmediğini kontrol et
const allSelected = computed(() => {
  return filteredContacts.value.length > 0 && filteredContacts.value.every(contact => contact.isSelected);
});

// Durum metnini getir
const getStatusText = (status) => {
  switch(status) {
    case 'new': return 'Yeni';
    case 'inProgress': return 'İşleniyor';
    case 'completed': return 'Tamamlandı';
    case 'archived': return 'Arşivlendi';
    default: return 'Bilinmiyor';
  }
};

// Tarih formatla
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
  return new Date(dateString).toLocaleDateString('tr-TR', options);
};

// Tüm mesajları seç/kaldır
const selectAll = (event) => {
  const isChecked = event.target.checked;
  filteredContacts.value.forEach(contact => {
    contact.isSelected = isChecked;
  });
};

// Mesaj detaylarını görüntüle
const viewContact = (contact) => {
  selectedContact.value = { ...contact };
};

// Mesaj sil
const deleteContact = (id) => {
  if (confirm('Bu mesajı silmek istediğinize emin misiniz?')) {
    contacts.value = contacts.value.filter(contact => contact.id !== id);
  }
};

// Mesaj durumunu güncelle
const updateContactStatus = (id, status) => {
  if (selectedContact.value && selectedContact.value.id === id) {
    selectedContact.value.status = status;
  }
};

// Mesaj değişikliklerini kaydet
const saveContactChanges = () => {
  if (selectedContact.value) {
    const index = contacts.value.findIndex(c => c.id === selectedContact.value.id);
    if (index !== -1) {
      contacts.value[index] = { ...selectedContact.value };
    }
    selectedContact.value = null;
  }
};

// Component yüklendiğinde
onMounted(() => {
  // API'den veri çekme simülasyonu
  setTimeout(() => {
    isLoading.value = false;
  }, 1000);
});
</script> 