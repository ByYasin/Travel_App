<template>
  <AdminLayout pageTitle="Kullanıcı Yönetimi">
    <div class="container mx-auto">
      <!-- Üst Toolbar -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex gap-2">
          <button 
            @click="showAddModal = true" 
            class="admin-btn-primary flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Yeni Kullanıcı
          </button>
        </div>
        <div class="flex gap-2">
          <div class="relative">
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Kullanıcı ara..."
              class="admin-input pl-10"
            >
            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <select 
            v-model="roleFilter" 
            class="admin-select"
          >
            <option value="all">Tüm Roller</option>
            <option value="admin">Admin</option>
            <option value="user">Kullanıcı</option>
          </select>
        </div>
      </div>
          
      <!-- Kullanıcı Tablosu -->
      <div class="admin-panel">
        <table class="admin-table admin-table-striped">
          <thead>
            <tr>
              <th scope="col">
                Kullanıcı
              </th>
              <th scope="col">
                Email
              </th>
              <th scope="col">
                Roller
              </th>
              <th scope="col">
                Durum
              </th>
              <th scope="col">
                Kayıt Tarihi
              </th>
              <th scope="col" class="text-right">
                İşlemler
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="6" class="text-center">
                <div class="flex justify-center py-4">
                  <div class="w-8 h-8 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
              </td>
            </tr>
            <tr v-else-if="users.length === 0">
              <td colspan="6" class="text-center text-gray-500 dark:text-gray-400 py-4">
                Kullanıcı bulunamadı.
              </td>
            </tr>
            <tr v-for="user in users" :key="user.id">
              <td>
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center"
                    :class="{
                      'bg-blue-100 dark:bg-blue-900/30': user.gender === 'male' || user.gender === 'other',
                      'bg-pink-100 dark:bg-pink-900/30': user.gender === 'female'
                    }"
                  >
                    <img 
                      v-if="user.avatar" 
                      :src="user.avatar" 
                      :alt="user.name" 
                      class="h-full w-full object-cover"
                    >
                    <div v-else class="flex items-center justify-center h-full w-full text-gray-400"
                      :class="{
                        'text-blue-500 dark:text-blue-400': user.gender === 'male' || user.gender === 'other',
                        'text-pink-500 dark:text-pink-400': user.gender === 'female'
                      }"
                    >
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                      {{ user.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      Kullanıcı ID: {{ user.id }}
                    </div>
                  </div>
                </div>
              </td>
              <td>
                {{ user.email }}
              </td>
              <td>
                <span 
                  v-for="role in user.roles" 
                  :key="role" 
                  :class="[
                    'admin-badge mr-1',
                    role === 'admin' ? 'admin-badge-red' : 'admin-badge-green'
                  ]"
                >
                  {{ role === 'admin' ? 'Yönetici' : 'Kullanıcı' }}
                </span>
              </td>
              <td>
                <span 
                  :class="[
                    'admin-badge',
                    user.is_active ? 'admin-badge-green' : 'admin-badge-red'
                  ]"
                >
                  {{ user.is_active ? 'Aktif' : 'Pasif' }}
                </span>
              </td>
              <td>
                {{ user.created_at }}
              </td>
              <td class="text-right">
                <button 
                  @click="editUser(user)" 
                  class="admin-btn-sm admin-btn-secondary mr-2"
                >
                  Düzenle
                </button>
                <button 
                  @click="confirmDelete(user)" 
                  class="admin-btn-sm admin-btn-danger"
                >
                  Sil
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
            
      <!-- Sayfalama -->
      <div v-if="totalPages > 1" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mt-4">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Toplam <span class="font-medium text-gray-900 dark:text-white">{{ totalItems }}</span> kullanıcı
          </div>
          
          <div class="flex space-x-1">
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
      
      <!-- Hata ve Başarı Mesajları -->
      <div v-if="error" class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mt-4 dark:bg-red-900/30 dark:text-red-400 dark:border-red-500 rounded">
        <p class="font-medium">Hata</p>
        <p>{{ error }}</p>
      </div>
      
      <div v-if="success" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4 dark:bg-green-900/30 dark:text-green-400 dark:border-green-500 rounded">
        <p class="font-medium">Başarılı</p>
        <p>{{ success }}</p>
      </div>
            
      <!-- Kullanıcı Ekleme/Düzenleme Modal -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="admin-panel max-w-xl w-full p-0 relative z-10">
          <div class="admin-panel-header">
            <h2 class="admin-panel-title">
              {{ showEditModal ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle' }}
            </h2>
          </div>
          
          <div class="admin-panel-body overflow-y-auto max-h-[70vh]">
            <form @submit.prevent="showEditModal ? updateUser() : createUser()" class="admin-form">
              <!-- Temel Bilgiler -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Ad Soyad -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label" for="name">
                    Ad Soyad <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    id="name" 
                    v-model="form.name" 
                    required 
                    class="admin-input"
                  >
                  <p v-if="formErrors.name" class="mt-1 text-sm text-red-600">{{ formErrors.name[0] }}</p>
                </div>
                
                <!-- Email -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label" for="email">
                    Email <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="email" 
                    id="email" 
                    v-model="form.email" 
                    required 
                    class="admin-input"
                  >
                  <p v-if="formErrors.email" class="mt-1 text-sm text-red-600">{{ formErrors.email[0] }}</p>
                </div>
              </div>
              
              <!-- Şifre Alanları -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Şifre (yeni kullanıcı) -->
                <div v-if="!showEditModal" class="admin-form-group mb-0">
                  <label class="admin-label" for="password_new">
                    Şifre <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="password" 
                    id="password_new" 
                    v-model="form.password" 
                    required 
                    class="admin-input"
                  >
                  <p v-if="formErrors.password" class="mt-1 text-sm text-red-600">{{ formErrors.password[0] }}</p>
                </div>
                
                <!-- Şifre (düzenleme) -->
                <div v-if="showEditModal" class="admin-form-group mb-0">
                  <label class="admin-label" for="password_edit">
                    Şifre <span class="text-gray-500 text-xs">(Değiştirmek için doldurun)</span>
                  </label>
                  <input 
                    type="password" 
                    id="password_edit" 
                    v-model="form.password" 
                    class="admin-input"
                    placeholder="••••••••"
                  >
                  <p v-if="formErrors.password" class="mt-1 text-sm text-red-600">{{ formErrors.password[0] }}</p>
                </div>
                
                <!-- Cinsiyet -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label">Cinsiyet</label>
                  <div class="flex space-x-4 mt-1">
                    <label class="admin-radio-label">
                      <input 
                        type="radio" 
                        v-model="form.gender" 
                        value="male" 
                        class="admin-radio mr-1"
                      >
                      Erkek
                    </label>
                    <label class="admin-radio-label">
                      <input 
                        type="radio" 
                        v-model="form.gender" 
                        value="female" 
                        class="admin-radio mr-1"
                      >
                      Kadın
                    </label>
                    <label class="admin-radio-label">
                      <input 
                        type="radio" 
                        v-model="form.gender" 
                        value="other" 
                        class="admin-radio mr-1"
                      >
                      Diğer
                    </label>
                  </div>
                </div>
              </div>
              
              <!-- İletişim Bilgileri -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Doğum Tarihi -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label" for="birthdate">Doğum Tarihi</label>
                  <input 
                    type="date" 
                    id="birthdate" 
                    v-model="form.birthdate" 
                    class="admin-input"
                    placeholder="GG.AA.YYYY"
                    max="2099-12-31"
                  >
                </div>
                
                <!-- Telefon -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label" for="phone">Telefon</label>
                  <input 
                    type="tel" 
                    id="phone" 
                    v-model="form.phone" 
                    class="admin-input"
                    placeholder="0(5XX) XXX XX XX"
                  >
                </div>
              </div>
              
              <!-- Adres -->
              <div class="admin-form-group mb-4">
                <label class="admin-label" for="address">Adres</label>
                <textarea 
                  id="address" 
                  v-model="form.address" 
                  class="admin-input"
                  rows="2"
                ></textarea>
              </div>
              
              <!-- Roller ve Durum -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <!-- Roller -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label mb-2">Roller</label>
                  <div class="flex space-x-4">
                    <label class="admin-checkbox-label">
                      <input 
                        type="checkbox" 
                        v-model="form.roles" 
                        value="admin" 
                        class="admin-checkbox mr-2"
                      >
                      Admin
                    </label>
                    <label class="admin-checkbox-label">
                      <input 
                        type="checkbox" 
                        v-model="form.roles" 
                        value="user" 
                        class="admin-checkbox mr-2"
                      >
                      Kullanıcı
                    </label>
                  </div>
                </div>
                
                <!-- Durum -->
                <div class="admin-form-group mb-0">
                  <label class="admin-label mb-2">Durum</label>
                  <div>
                    <label class="admin-checkbox-label">
                      <input 
                        type="checkbox" 
                        v-model="form.is_active" 
                        class="admin-checkbox mr-2"
                      >
                      Aktif
                    </label>
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="admin-panel-footer flex justify-end space-x-3">
            <button 
              type="button" 
              @click="closeModals" 
              class="admin-btn-secondary"
            >
              İptal
            </button>
            <button 
              @click="showEditModal ? updateUser() : createUser()" 
              class="admin-btn-primary"
              :disabled="loading"
            >
              {{ showEditModal ? 'Güncelle' : 'Ekle' }}
            </button>
          </div>
        </div>
      </div>
                
      <!-- Silme Onay Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="showDeleteModal = false"></div>
        <div class="admin-panel max-w-md w-full p-6 relative z-10">
          <div class="text-center">
            <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">
              Kullanıcıyı Sil
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              <span class="font-semibold">{{ userToDelete?.name }}</span> kullanıcısını silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
            </p>
            <div class="mt-6 flex justify-center space-x-3">
              <button 
                @click="showDeleteModal = false" 
                class="admin-btn-secondary"
              >
                İptal
              </button>
              <button 
                @click="deleteUser" 
                class="admin-btn-danger"
                :disabled="loading"
              >
                Sil
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch, watchEffect } from 'vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import axios from 'axios';

// State
const users = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const roleFilter = ref('all');
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const userToDelete = ref(null);
const error = ref(null);
const success = ref(null);
const formErrors = ref({});
const availableRoles = ref([]);  // Veritabanındaki roller

// Sayfalama için değişkenler
const currentPage = ref(1);
const perPage = ref(10);
const totalItems = ref(0);
const totalPages = ref(0);

// Form state
const form = ref({
  id: null,
  name: '',
  email: '',
  password: '',
  roles: ['user'],
  is_active: true,
  gender: '',
  birthdate: '',
  phone: '',
  address: ''
});

// Kullanıcıları getir
const fetchUsers = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const response = await axios.get('/admin/users-data', {
      params: {
        page: currentPage.value,
        per_page: perPage.value,
        search: searchQuery.value,
        role: roleFilter.value !== 'all' ? roleFilter.value : null
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json'
      }
    });

    console.log('Kullanıcılar API yanıtı:', response.data);

    if (response.data) {
      users.value = response.data.data;
      totalItems.value = response.data.total;
      currentPage.value = response.data.current_page;
      totalPages.value = response.data.last_page;
    }
  } catch (err) {
    console.error('Kullanıcılar yüklenirken hata oluştu:', err);
    if (err.response) {
      error.value = `Kullanıcılar yüklenirken bir hata oluştu (${err.response.status}): ${err.response.data?.message || 'Bilinmeyen hata'}`;
    } else if (err.request) {
      error.value = 'Sunucudan yanıt alınamadı. Lütfen internet bağlantınızı kontrol edin.';
    } else {
      error.value = 'İstek oluşturulurken bir hata oluştu: ' + err.message;
    }
  } finally {
    loading.value = false;
  }
};

// Rol verilerini getir
const fetchRoles = async () => {
  try {
    const response = await axios.get('/admin/roles');
    console.log('Roller API yanıtı:', response.data);
    if (response.data) {
      availableRoles.value = response.data;
    }
  } catch (err) {
    console.error('Roller yüklenirken hata oluştu:', err);
  }
};

// Component yüklendiğinde
onMounted(() => {
  fetchUsers();
  fetchRoles();
});

// Arama veya filtre değiştiğinde yeniden veri çek
const handleSearchFilterChange = () => {
  currentPage.value = 1; // İlk sayfaya dön
  fetchUsers();
};

// Watch fonksiyonları ile arama ve filtre izleme
watchEffect(() => {
  if (searchQuery.value) {
    handleSearchFilterChange();
  }
});

watch(roleFilter, () => {
  handleSearchFilterChange();
});

// Methods
const editUser = (user) => {
  // Doğum tarihi için format düzeltmesi
  let formattedBirthdate = '';
  if (user.birthdate) {
    // API'den gelen tarihi YYYY-MM-DD formatına dönüştür
    try {
      const birthDate = new Date(user.birthdate);
      formattedBirthdate = birthDate.toISOString().split('T')[0]; // YYYY-MM-DD formatını al
    } catch (e) {
      console.error('Doğum tarihi formatlanırken hata:', e);
      formattedBirthdate = user.birthdate;
    }
  }
  
  form.value = { 
    id: user.id,
    name: user.name,
    email: user.email,
    password: '',
    roles: user.roles || [],
    is_active: user.is_active,
    gender: user.gender || '',
    birthdate: formattedBirthdate,
    phone: user.phone || '',
    address: user.address || ''
  };
  showEditModal.value = true;
};

const confirmDelete = (user) => {
  userToDelete.value = user;
  showDeleteModal.value = true;
};

const createUser = async () => {
  loading.value = true;
  error.value = null;
  formErrors.value = {};
  
  try {
    const response = await axios.post('/admin/users-store', form.value, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    if (response.data.message) {
      success.value = response.data.message;
      
      // Listeyi güncelle
      fetchUsers();
      
      // Modalı kapat ve formu sıfırla
      showAddModal.value = false;
      resetForm();
      
      // 3 saniye sonra success mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    }
  } catch (err) {
    console.error('Kullanıcı oluşturma hatası:', err);
    
    if (err.response && err.response.status === 422) {
      // Validasyon hataları
      formErrors.value = err.response.data.errors || {};
      error.value = 'Lütfen form alanlarını kontrol edin.';
    } else {
      error.value = 'Kullanıcı oluşturulurken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
  } finally {
    loading.value = false;
  }
};

const updateUser = async () => {
  if (!form.value.id) return;
  
  loading.value = true;
  error.value = null;
  formErrors.value = {};

  try {
    // Form verilerini hazırla, trim kontrolünü güvenli bir şekilde yap
    const formData = {
      id: form.value.id,
      name: typeof form.value.name === 'string' ? form.value.name.trim() : form.value.name,
      email: typeof form.value.email === 'string' ? form.value.email.trim() : form.value.email,
      password: form.value.password, // Password trimlenmesin
      roles: form.value.roles,
      is_active: form.value.is_active,
      gender: typeof form.value.gender === 'string' ? form.value.gender.trim() : form.value.gender,
      birthdate: typeof form.value.birthdate === 'string' ? form.value.birthdate.trim() : form.value.birthdate,
      phone: typeof form.value.phone === 'string' ? form.value.phone.trim() : form.value.phone,
      address: typeof form.value.address === 'string' ? form.value.address.trim() : form.value.address
    };

    const response = await axios.post(`/admin/users-update/${form.value.id}`, formData, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    if (response.data.message) {
      success.value = response.data.message;
      
      // Listeyi güncelle
      fetchUsers();
      
      // Modalı kapat ve formu sıfırla
      showEditModal.value = false;
      resetForm();
      
      // 3 saniye sonra success mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    }
  } catch (err) {
    console.error('Kullanıcı güncelleme hatası:', err);
    
    if (err.response && err.response.status === 422) {
      // Validasyon hataları
      formErrors.value = err.response.data.errors || {};
      error.value = 'Lütfen form alanlarını kontrol edin.';
    } else {
      error.value = 'Kullanıcı güncellenirken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
  } finally {
    loading.value = false;
  }
};

const deleteUser = async () => {
  if (!userToDelete.value) return;
  
  loading.value = true;
  error.value = null;
  
  try {
    const response = await axios.post(`/admin/users-delete/${userToDelete.value.id}`, {}, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    if (response.data.message) {
      success.value = response.data.message;
      
      // Listeyi güncelle
      fetchUsers();
      
      // Modalı kapat
      showDeleteModal.value = false;
      userToDelete.value = null;
      
      // 3 saniye sonra success mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    }
  } catch (err) {
    console.error('Kullanıcı silme hatası:', err);
    error.value = 'Kullanıcı silinirken bir hata oluştu: ' + (err.response?.data?.message || err.message);
  } finally {
    loading.value = false;
  }
};

const closeModals = () => {
  showAddModal.value = false;
  showEditModal.value = false;
  resetForm();
};

const resetForm = () => {
  form.value = {
    id: null,
    name: '',
    email: '',
    password: '',
    roles: ['user'],
    is_active: true,
    gender: '',
    birthdate: '',
    phone: '',
    address: ''
  };
};

const changePage = (page) => {
  currentPage.value = page;
  fetchUsers();
};
</script> 