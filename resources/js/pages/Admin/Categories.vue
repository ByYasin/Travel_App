<template>
  <AdminLayout>
    <div class="container mx-auto">
      <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">
        Kategori Yönetimi
      </h1>

      <!-- Hata Mesajı -->
      <div v-if="error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-sm dark:bg-red-900 dark:text-red-100 dark:border-red-800">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p>{{ error }}</p>
        </div>
        <div class="mt-2 text-sm opacity-80">
          <p>Lütfen şunları kontrol edin:</p>
          <ul class="list-disc pl-5 mt-1">
            <li>Laravel'de gerekli web.php rotaları eklenmiş mi?</li>
            <li>API endpoint URL'leri doğru mu?</li>
            <li>Oturum açmış ve admin rolüne sahip misiniz?</li>
            <li>CSRF token doğru şekilde gönderiliyor mu?</li>
          </ul>
        </div>
      </div>

      <!-- Üst Toolbar -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex gap-2">
          <button 
            @click="openAddModal" 
            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg flex items-center gap-2 transition-colors shadow-sm"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Yeni Kategori
          </button>
        </div>
        <div class="flex gap-2">
          <div class="relative">
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Kategori ara..." 
              class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
        </div>
      </div>

      <!-- Kategori Tablosu -->
      <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Görsel
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Kategori Adı
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Tur Sayısı
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
            <tr v-if="loading">
              <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                Yükleniyor...
              </td>
            </tr>
            <tr v-else-if="filteredCategories.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                Kategori bulunamadı.
              </td>
            </tr>
            <tr v-for="category in filteredCategories" :key="category.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="w-12 h-12 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                  <img 
                    v-if="category.image" 
                    :src="category.image" 
                    :alt="category.name" 
                    class="w-full h-full object-cover"
                  >
                  <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900 dark:text-white">{{ category.name }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ category.slug }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                {{ category.toursCount !== undefined ? category.toursCount : 0 }} tur
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                    category.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                  ]"
                >
                  {{ category.is_active ? 'Aktif' : 'Pasif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button 
                  @click="editCategory(category)" 
                  class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3"
                >
                  Düzenle
                </button>
                <button 
                  @click="confirmDelete(category)" 
                  class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                >
                  Sil
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Sayfalama -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Toplam <span class="font-medium text-gray-900 dark:text-white">{{ totalCategories }}</span> kategori
          </div>
          
          <div v-if="totalPages > 1" class="flex space-x-1">
            <button 
              @click="changePage(currentPage - 1)"
              :disabled="currentPage === 1"
              class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50"
            >
              Önceki
            </button>
            <button 
              v-for="page in totalPages"
              :key="page"
              @click="changePage(page)"
              :class="{'bg-primary-50 dark:bg-primary-900/30 border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300': currentPage === page}"
              class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300"
            >
              {{ page }}
            </button>
            <button 
              @click="changePage(currentPage + 1)"
              :disabled="currentPage === totalPages"
              class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50"
            >
              Sonraki
            </button>
          </div>
        </div>
      </div>

      <!-- Kategori Ekleme/Düzenleme Modal -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            {{ showEditModal ? 'Kategori Düzenle' : 'Yeni Kategori Ekle' }}
          </h2>
          <form @submit.prevent="showEditModal ? updateCategory($event) : createCategory($event)" method="POST">
            <input type="hidden" name="_token" :value="getCsrfToken()">
            <input v-if="showEditModal" type="hidden" name="_method" value="POST">
            <input v-if="showEditModal" type="hidden" name="id" :value="form.id">
            
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300 mb-2" for="name">
                Kategori Adı <span class="text-red-500">*</span>
              </label>
              <input 
                type="text" 
                id="name" 
                name="name"
                v-model="form.name" 
                required 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
              >
            </div>
            
            <!-- Diğer form alanları -->
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300 mb-2" for="description">
                Açıklama
              </label>
              <textarea 
                id="description" 
                name="description"
                v-model="form.description" 
                rows="3" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
              ></textarea>
            </div>
            
            <div class="mb-4">
              <label class="block text-gray-700 dark:text-gray-300 mb-2" for="image">
                Görsel URL
              </label>
              <input 
                type="text" 
                id="image" 
                name="image"
                v-model="form.image" 
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                placeholder="https://example.com/image.jpg"
              >
            </div>
            
            <div class="mb-6">
              <label class="flex items-center">
                <input 
                  type="checkbox" 
                  id="is_active"
                  name="is_active"
                  v-model="form.is_active" 
                  class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                >
                <span class="ml-2 text-gray-700 dark:text-gray-300">Aktif</span>
              </label>
            </div>
            
            <div class="flex justify-end space-x-3">
              <button 
                type="button" 
                @click="closeModals" 
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
              >
                İptal
              </button>
              <button 
                type="submit" 
                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg shadow-sm"
                :disabled="loading"
              >
                {{ showEditModal ? 'Güncelle' : 'Ekle' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Silme Onay Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="cancelDelete"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
          <div class="text-center">
            <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">
              Kategoriyi Sil
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              <span class="font-semibold">{{ categoryToDelete?.name }}</span> kategorisini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
            </p>
            <form @submit.prevent="deleteCategory($event)" method="POST" class="mt-6">
              <input type="hidden" name="_token" :value="getCsrfToken()">
              <input type="hidden" name="_method" value="POST">
              <input type="hidden" name="id" :value="categoryToDelete?.id">
              
              <div class="flex justify-center space-x-3">
                <button 
                  type="button"
                  @click="cancelDelete" 
                  class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-gray-800 dark:text-gray-200"
                >
                  İptal
                </button>
                <button 
                  type="submit" 
                  class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                  :disabled="loading"
                >
                  Sil
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { adminService } from '@/services/api';

// State
const categories = ref([]);
const loading = ref(false);
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const categoryToDelete = ref(null);
const searchQuery = ref('');
const error = ref(null);
// Sayfalama için değişkenler
const currentPage = ref(1);
const perPage = ref(10); // Sayfa başına 10 kategori
const totalPages = ref(0);

// Form state
const form = ref({
  id: null,
  name: '',
  slug: '',
  description: '',
  image: '',
  is_active: true
});

// API çağrıları için yardımcı fonksiyonlar
const fetchCategories = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    // adminService'i kullan
    const data = await adminService.getCategories();
    
    console.log('Kategoriler yüklendi:', data);
    
    // Veri boş array ise boş bir array ile devam et
    if (!Array.isArray(data)) {
      console.warn('API yanıtı bir dizi değil:', data);
      categories.value = [];
      return;
    }
    
    // Tur sayılarını hesapla
    categories.value = data.map(category => ({
      ...category,
      toursCount: category.tours_count
    }));
  } catch (err) {
    error.value = err.message;
    console.error('Kategori yükleme hatası:', err);
  } finally {
    loading.value = false;
  }
};

// Kategorileri yükle
onMounted(() => {
  console.log('Admin Categories component yüklendi');
  
  // CSRF token bilgisini kontrol et
  const csrfToken = getCsrfToken();
  console.log('CSRF Token mevcut:', csrfToken ? 'Evet' : 'Hayır');
  
  // Oturum durumunu kontrol et
  fetch('/api/user')
    .then(response => {
      console.log('Oturum kontrolü:', response.status, response.ok ? 'Giriş yapılmış' : 'Giriş yapılmamış');
      return fetchCategories();
    })
    .catch(err => {
      console.error('Oturum kontrolü hatası:', err);
      fetchCategories();
    });
});

// Filtrelenmiş kategoriler
const filteredCategories = computed(() => {
  if (!searchQuery.value) return paginatedCategories.value;
  
  const query = searchQuery.value.toLowerCase();
  const filtered = categories.value.filter(cat => 
    cat.name.toLowerCase().includes(query) || 
    cat.description?.toLowerCase().includes(query)
  );
  
  // Toplam sayfa sayısını güncelle
  totalPages.value = Math.ceil(filtered.length / perPage.value);
  
  // İlgili sayfanın kategorilerini döndür
  const startIndex = (currentPage.value - 1) * perPage.value;
  const endIndex = startIndex + perPage.value;
  return filtered.slice(startIndex, endIndex);
});

// Sayfalanmış kategoriler
const paginatedCategories = computed(() => {
  const allCategories = categories.value;
  
  // Toplam sayfa sayısını hesapla
  totalPages.value = Math.ceil(allCategories.length / perPage.value);
  
  // Eğer geçerli sayfa toplam sayfa sayısından büyükse, son sayfaya git
  if (currentPage.value > totalPages.value && totalPages.value > 0) {
    currentPage.value = totalPages.value;
  }
  
  // İlgili sayfanın kategorilerini döndür
  const startIndex = (currentPage.value - 1) * perPage.value;
  const endIndex = startIndex + perPage.value;
  return allCategories.slice(startIndex, endIndex);
});

// Toplam kategori sayısı
const totalCategories = computed(() => {
  if (!searchQuery.value) return categories.value.length;
  
  const query = searchQuery.value.toLowerCase();
  return categories.value.filter(cat => 
    cat.name.toLowerCase().includes(query) || 
    cat.description?.toLowerCase().includes(query)
  ).length;
});

// Sayfa değiştirme fonksiyonu
const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  currentPage.value = page;
  window.scrollTo(0, 0); // Sayfa değiştiğinde en üste kaydır
};

// Methods
const openAddModal = () => {
  try {
    resetForm(); // Formu sıfırla
    showAddModal.value = true;
    console.log('Add modal opened');
  } catch (err) {
    console.error('Modal açma hatası:', err);
    error.value = 'Modal açılırken bir hata oluştu: ' + err.message;
  }
};

const editCategory = (category) => {
  try {
    // Formu mevcut kategori verisiyle doldur
    form.value = { ...category };
    showEditModal.value = true;
    console.log('Edit modal opened for:', category.name);
  } catch (err) {
    console.error('Edit modal açma hatası:', err);
    error.value = 'Modal açılırken bir hata oluştu: ' + err.message;
  }
};

const confirmDelete = (category) => {
  try {
    console.log('Silme onayı için kategori:', category);
    categoryToDelete.value = category;
    showDeleteModal.value = true;
  } catch (err) {
    console.error('Silme onayı açılırken hata oluştu:', err);
    error.value = 'Silme onayı gösterilirken bir hata oluştu';
  }
};

const createCategory = async (e) => {
  e.preventDefault(); // Form submit işlemini engelle
  
  try {
    if (!e.target.checkValidity()) {
      console.error('Form doğrulama başarısız');
      return;
    }
    
    loading.value = true;
    error.value = null;
    
    const formData = new FormData(e.target);
    
    // Form verilerini kontrol et
    if (!formData.get('name')) {
      throw new Error('Kategori adı alanı zorunludur');
    }
    
    // is_active değerini boolean'a dönüştür
    const isActiveValue = formData.get('is_active') === 'on' ? '1' : '0';
    formData.delete('is_active');
    formData.append('is_active', isActiveValue);
    
    console.log('Kategori ekleme başlatılıyor...');
    
    // Form değerlerini göster
    for (const [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }
    
    // Klasik form gönderimi
    const response = await fetch('/admin/categories-store', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });
    
    // Yanıtı kontrol et
    const responseText = await response.text();
    console.log('Sunucu yanıtı:', responseText);
    
    if (!response.ok) {
      throw new Error(`Kategori eklenemedi: ${response.status} - ${responseText}`);
    }
    
    console.log('Kategori başarıyla eklendi');
    
    // Kategorileri yeniden yükle
    await fetchCategories();
    
    resetForm();
    showAddModal.value = false;
  } catch (err) {
    error.value = `Kategori ekleme hatası: ${err.message}`;
    console.error('Kategori ekleme hatası:', err);
  } finally {
    loading.value = false;
  }
};

const updateCategory = async (e) => {
  e.preventDefault();
  
  try {
    if (!e.target.checkValidity()) {
      console.error('Form doğrulama başarısız');
      return;
    }
    
    loading.value = true;
    error.value = null;
    
    const formData = new FormData(e.target);
    const categoryId = form.value.id;
    
    // Form verilerini kontrol et
    if (!formData.get('name')) {
      throw new Error('Kategori adı alanı zorunludur');
    }
    
    // is_active değerini boolean'a dönüştür
    const isActiveValue = formData.get('is_active') === 'on' ? '1' : '0';
    formData.delete('is_active');
    formData.append('is_active', isActiveValue);
    
    console.log('Kategori güncelleme başlatılıyor...', categoryId);
    
    // Form değerlerini göster
    for (const [key, value] of formData.entries()) {
      console.log(`${key}: ${value}`);
    }
    
    // Klasik form gönderimi
    const response = await fetch(`/admin/categories-update/${categoryId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });
    
    // Yanıtı kontrol et
    const responseText = await response.text();
    console.log('Sunucu yanıtı:', responseText);
    
    if (!response.ok) {
      throw new Error(`Kategori güncellenemedi: ${response.status} - ${responseText}`);
    }
    
    console.log('Kategori başarıyla güncellendi');
    
    // Kategorileri yeniden yükle
    await fetchCategories();
    
    resetForm();
    showEditModal.value = false;
  } catch (err) {
    error.value = `Kategori güncelleme hatası: ${err.message}`;
    console.error('Kategori güncelleme hatası:', err);
  } finally {
    loading.value = false;
  }
};

const deleteCategory = async (e) => {
  e.preventDefault();
  
  try {
    if (!categoryToDelete.value) {
      console.error('Silinecek kategori seçilmedi');
      return;
    }
    
    loading.value = true;
    error.value = null;
    
    const formData = new FormData(e.target);
    const categoryId = categoryToDelete.value.id;
    
    console.log('Kategori silme başlatılıyor...', categoryId);
    
    // Klasik form gönderimi
    const response = await fetch(`/admin/categories-delete/${categoryId}`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken(),
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });
    
    // Yanıtı kontrol et
    const responseText = await response.text();
    console.log('Sunucu yanıtı:', responseText);
    
    if (!response.ok) {
      throw new Error(`Kategori silinemedi: ${response.status} - ${responseText}`);
    }
    
    console.log('Kategori başarıyla silindi');
    
    // Kategorileri yeniden yükle
    await fetchCategories();
    
    categoryToDelete.value = null;
    showDeleteModal.value = false;
  } catch (err) {
    error.value = `Kategori silme hatası: ${err.message}`;
    console.error('Kategori silme hatası:', err);
  } finally {
    loading.value = false;
  }
};

const closeModals = () => {
  try {
    showAddModal.value = false;
    showEditModal.value = false;
    resetForm();
    console.log('Modals closed');
  } catch (err) {
    console.error('Modal kapatma hatası:', err);
  }
};

const resetForm = () => {
  form.value = {
    id: null,
    name: '',
    slug: '',
    description: '',
    image: '',
    is_active: true
  };
};

const getCsrfToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
};

const cancelDelete = () => {
  categoryToDelete.value = null;
  showDeleteModal.value = false;
};
</script> 