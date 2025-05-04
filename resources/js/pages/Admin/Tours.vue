<template>
  <AdminLayout>
    <div class="container mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          Turlar Yönetimi
        </h1>
        
        <button 
          @click="openAddModal" 
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Yeni Tur Ekle
        </button>
      </div>
      
      <!-- Search & Filter -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input 
              type="text" 
              v-model="searchQuery"
              placeholder="Tur ara..."
              class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
          </div>
          
          <div class="flex flex-wrap gap-2">
            <select 
              v-model="selectedCategory"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
              <option value="all">Tüm Kategoriler</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
            
            <select 
              v-model="sortBy"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
              <option value="">Sıralama</option>
              <option value="newest">En Yeni</option>
              <option value="oldest">En Eski</option>
              <option value="price-asc">Fiyat (Artan)</option>
              <option value="price-desc">Fiyat (Azalan)</option>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Başarı Mesajı -->
      <div v-if="success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/30 dark:text-green-400 dark:border-green-500 rounded" role="alert">
        <p class="font-medium">Başarılı</p>
        <p>{{ success }}</p>
      </div>
      
      <!-- Hata Mesajları -->
      <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/30 dark:text-red-400 dark:border-red-500 rounded" role="alert">
        <p class="font-medium">Hata</p>
        <p>{{ error }}</p>
      </div>
      
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
      </div>
      
      <!-- Turlar Tablosu -->
      <div v-else-if="tours.length === 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
        </svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Henüz Tur Bulunmuyor</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Yeni bir tur ekleyerek başlayabilirsiniz.</p>
        <button 
          @click="openAddModal" 
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors inline-flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Yeni Tur Ekle
        </button>
      </div>
      
      <!-- Tours Table -->
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full whitespace-nowrap">
            <thead>
              <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                <th class="px-6 py-3">Tur Adı</th>
                <th class="px-6 py-3">Kategori</th>
                <th class="px-6 py-3">Fiyat</th>
                <th class="px-6 py-3">Süre</th>
                <th class="px-6 py-3">Durum</th>
                <th class="px-6 py-3">İşlemler</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="tour in filteredTours" :key="tour.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="h-10 w-10 flex-shrink-0 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 relative">
                      <img 
                        v-if="tour.featured_image" 
                        :src="tour.featured_image" 
                        alt="Tour Image" 
                        class="h-full w-full object-cover"
                        loading="lazy"
                        @error="handleImageError($event, tour)"
                      >
                      <div 
                        v-else
                        class="h-full w-full flex items-center justify-center bg-gray-200 dark:bg-gray-600"
                      >
                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-3">
                      <div class="font-medium text-gray-900 dark:text-white text-sm line-clamp-1" :title="tour.title || 'İsimsiz Tur'">
                        {{ tour.title || 'İsimsiz Tur' }}
                      </div>
                      <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1" :title="tour.location || 'Konum belirtilmemiş'">
                        {{ tour.location || 'Konum belirtilmemiş' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ getCategoryName(tour.category_id) }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ formatPrice(tour.price) }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ tour.duration || 'Belirtilmemiş' }}</td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 text-xs rounded-full"
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': tour.status === 'active',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': tour.status === 'inactive',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': tour.status === 'pending'
                    }"
                  >
                    {{ getStatusText(tour.status) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button 
                      @click="editTour(tour)"
                      class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" 
                      title="Düzenle"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      @click="confirmDelete(tour)"
                      class="p-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" 
                      title="Sil"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                    <button 
                      @click="viewTour(tour)"
                      class="p-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300" 
                      title="Görüntüle"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredTours.length === 0">
                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                  Gösterilecek tur bulunamadı.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600 dark:text-gray-400">
              Toplam <span class="font-medium text-gray-900 dark:text-white">{{ totalTours }}</span> tur
            </div>
            
            <!-- Sayfalama -->
            <div class="flex space-x-1">
              <button 
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300"
              >
                Önceki
              </button>
              <button 
                v-for="page in totalPages"
                @click="changePage(page)"
                :class="{'bg-primary-50 dark:bg-primary-900/30 border border-primary-300 dark:border-primary-700 text-primary-700 dark:text-primary-300': currentPage === page}"
                class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300"
              >
                {{ page }}
              </button>
              <button 
                @click="changePage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 rounded-md bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300"
              >
                Sonraki
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Tur Ekleme/Düzenleme Modal -->
      <div v-if="showAddModal || showEditModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-4 relative z-10">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            {{ showEditModal ? 'Tur Düzenle' : 'Yeni Tur Ekle' }}
          </h2>
          <form @submit.prevent="showEditModal ? updateTour() : createTour()" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="_token" :value="getCsrfToken()">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="title">
                  Başlık <span class="text-red-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="title" 
                  name="title"
                  v-model="form.title" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required 
                />
                <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="price">
                  Fiyat <span class="text-red-500">*</span>
                </label>
                <input 
                  type="number" 
                  id="price" 
                  name="price"
                  v-model="form.price" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required 
                />
                <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="category">
                  Kategori
                </label>
                <select
                  id="category"
                  name="category_id"
                  v-model="form.category_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option value="" disabled selected>Kategori Seçin</option>
                  <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                  </option>
                </select>
                <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="duration">
                  Süre <span class="text-red-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="duration" 
                  name="duration"
                  v-model="form.duration" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required 
                />
                <p v-if="errors.duration" class="mt-1 text-sm text-red-600">{{ errors.duration[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="location">
                  Konum <span class="text-red-500">*</span>
                </label>
                <input 
                  type="text" 
                  id="location" 
                  name="location"
                  v-model="form.location" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required 
                />
                <p v-if="errors.location" class="mt-1 text-sm text-red-600">{{ errors.location[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="max_participants">
                  Maksimum Katılımcı
                </label>
                <input 
                  type="number" 
                  id="max_participants" 
                  name="max_participants"
                  v-model="form.max_participants" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                />
                <p v-if="errors.max_participants" class="mt-1 text-sm text-red-600">{{ errors.max_participants[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="status">
                  Durum
                </label>
                <select 
                  id="status" 
                  name="status"
                  v-model="form.status" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option value="active">Aktif</option>
                  <option value="inactive">Pasif</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
              </div>
              
              <div class="col-span-1 md:col-span-2 mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="featured_image">
                  Tur Görseli
                </label>
                
                <!-- Görsel Ekleme Seçenekleri -->
                <div class="flex items-center space-x-3 mb-2">
                  <div>
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="radio" class="form-radio" name="image_type" value="file" v-model="imageType" @change="switchImageType('file')">
                      <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Dosya Yükle</span>
                    </label>
                  </div>
                  <div>
                    <label class="inline-flex items-center cursor-pointer">
                      <input type="radio" class="form-radio" name="image_type" value="url" v-model="imageType" @change="switchImageType('url')">
                      <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">URL Kullan</span>
                    </label>
                  </div>
                </div>
                
                <!-- Dosya Yükleme Alanı -->
                <div v-if="imageType === 'file'" class="flex items-center space-x-2">
                  <label class="flex items-center justify-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Dosya Seç
                    <input 
                      type="file"
                      id="featured_image" 
                      name="featured_image"
                      class="hidden"
                      @change="handleImageUpload"
                    />
                  </label>
                  <span class="text-sm text-gray-500 dark:text-gray-400" v-if="form.featured_image && typeof form.featured_image === 'object'">
                    {{ form.featured_image.name }}
                  </span>
                  <span class="text-sm text-gray-500 dark:text-gray-400" v-else-if="imageType === 'file' && !form.featured_image">
                    Dosya seçilmedi
                  </span>
                </div>
                
                <!-- URL Giriş Alanı -->
                <div v-if="imageType === 'url'">
                  <input 
                    type="text" 
                    id="featured_image_url" 
                    name="featured_image_url"
                    v-model="featuredImageUrl"
                    placeholder="https://example.com/resim.jpg"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  />
                </div>
                
                <p v-if="errors.featured_image" class="mt-1 text-sm text-red-600">{{ errors.featured_image[0] }}</p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="description">
                  Açıklama <span class="text-red-500">*</span>
                </label>
                <textarea 
                  id="description" 
                  name="description"
                  v-model="form.description" 
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                ></textarea>
                <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description[0] }}</p>
              </div>
              
              <!-- Dahil Olanlar ve Olmayanlar -->
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="included">
                  Dahil Olanlar
                </label>
                <textarea 
                  id="included" 
                  name="included_text"
                  v-model="includedText" 
                  rows="3" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  placeholder="Konaklama&#10;Kahvaltı&#10;Rehberlik Hizmeti"
                ></textarea>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-1 gap-4">
              <!-- Dahil Olmayanlar -->
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="not_included">
                  Dahil Olmayanlar
                </label>
                <textarea 
                  id="not_included" 
                  name="not_included_text"
                  v-model="notIncludedText" 
                  rows="3" 
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  placeholder="Uçak Bileti&#10;Öğle Yemeği&#10;Bahşişler"
                ></textarea>
              </div>
            </div>
            
            <!-- Butonlar -->
            <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200 dark:border-gray-700">
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
                <span v-if="loading" class="flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Kaydediliyor...
                </span>
                <span v-else>
                  {{ showEditModal ? 'Güncelle' : 'Ekle' }}
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Tur Silme Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="cancelDelete"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
          <div class="text-center">
            <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">
              Turu Sil
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              <span class="font-semibold">{{ tourToDelete?.title }}</span> turunu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
            </p>
            <div class="mt-6 flex justify-center space-x-3">
              <button 
                type="button"
                @click="cancelDelete" 
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-gray-800 dark:text-gray-200"
              >
                İptal
              </button>
              <button 
                type="button" 
                @click="deleteTour"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                :disabled="loading"
              >
                <span v-if="loading">Siliniyor...</span>
                <span v-else>Sil</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Tur Detay Modal -->
      <div v-if="showDetailModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col relative z-10">
          <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
              Tur Detayları
            </h2>
            <button @click="closeModals" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div v-if="selectedTour" class="overflow-y-auto p-4 flex-grow">
            <div class="space-y-6">
              <!-- Tur Görseli ve Temel Bilgiler -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                  <img 
                    :src="selectedTour.featured_image || 'https://via.placeholder.com/300x200?text=Görsel+Yok'" 
                    :alt="selectedTour.title" 
                    class="w-full h-auto rounded-lg object-cover shadow-md"
                  >
                </div>
                <div class="md:col-span-2 space-y-4">
                  <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ selectedTour.title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 flex items-center mt-2">
                      <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                      </svg>
                      {{ selectedTour.location }}
                    </p>
                  </div>
                  
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Kategori</span>
                      <p class="text-gray-900 dark:text-white font-medium">{{ getCategoryName(selectedTour.category_id) }}</p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Fiyat</span>
                      <p class="text-gray-900 dark:text-white font-medium">{{ formatPrice(selectedTour.price) }}</p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Süre</span>
                      <p class="text-gray-900 dark:text-white font-medium">{{ selectedTour.duration }}</p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Maksimum Katılımcı</span>
                      <p class="text-gray-900 dark:text-white font-medium">{{ selectedTour.max_participants || 'Belirtilmemiş' }}</p>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Durum</span>
                      <span 
                        class="inline-block px-2 py-1 text-xs rounded-full mt-1"
                        :class="{
                          'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedTour.status === 'active',
                          'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedTour.status === 'inactive',
                          'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedTour.status === 'pending'
                        }"
                      >
                        {{ getStatusText(selectedTour.status) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Tur Açıklaması -->
              <div v-if="selectedTour.description" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Açıklama</h4>
                <div class="pr-2">
                  <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ selectedTour.description }}</p>
                </div>
              </div>
              
              <!-- Dahil Olanlar ve Olmayanlar -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                <div v-if="selectedTour.included && selectedTour.included.length > 0">
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Dahil Olanlar</h4>
                  <ul class="space-y-2">
                    <li v-for="(item, index) in selectedTour.included" :key="'inc-' + index" class="flex items-start">
                      <svg class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                      <span class="text-gray-700 dark:text-gray-300">{{ item }}</span>
                    </li>
                  </ul>
                </div>
                
                <div v-if="selectedTour.not_included && selectedTour.not_included.length > 0">
                  <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Dahil Olmayanlar</h4>
                  <ul class="space-y-2">
                    <li v-for="(item, index) in selectedTour.not_included" :key="'ninc-' + index" class="flex items-start">
                      <svg class="h-5 w-5 text-red-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                      <span class="text-gray-700 dark:text-gray-300">{{ item }}</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          
          <!-- İşlemler -->
          <div class="border-t border-gray-200 dark:border-gray-700 p-4 flex justify-end space-x-3">
            <button 
              @click="editTour(selectedTour)"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center"
            >
              <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Düzenle
            </button>
            <button 
              @click="closeModals"
              class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
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
import axios from 'axios';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { adminService } from '@/services/api';

// State
const tours = ref([]);
const categories = ref([]);
const loading = ref(false);
const error = ref(null);
const success = ref(null);
const errors = ref({});
const searchQuery = ref('');
const selectedCategory = ref('all');
const sortBy = ref('');
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const showDetailModal = ref(false);
const tourToDelete = ref(null);
const includedText = ref('');
const notIncludedText = ref('');
const imageType = ref('file');
const featuredImageUrl = ref('');
const selectedTour = ref(null);

// Sayfalama için state değişkenleri
const currentPage = ref(1);
const perPage = ref(10); // Sayfa başına 10 tur göster
const totalPages = ref(0);

// Form state
const form = ref({
  title: '',
  price: '',
  category_id: null,
  duration: '',
  location: '',
  description: '',
  max_participants: '',
  featured_image: '',
  status: 'active'
});

// API URL'leri
const API_BASE_URL = '';
const TOURS_ENDPOINT = `/api/admin/tours`;
const CATEGORIES_ENDPOINT = `/admin/categories-list`;

// Turları getir - yükleme durumu kontrolü eklendi
const fetchTours = async () => {
  // Zaten yükleme yapılıyorsa çık
  if (loading.value) return;
  
  loading.value = true;
  error.value = null;
  
  try {
    // Önbellekleri temizle
    const timestamp = new Date().getTime();
    console.log('Turlar yükleniyor...');
    
    // API isteği yap - URL'e timestamp ekleyerek önbellek sorunlarını önle
    const response = await axios.get(`/admin/tours-list?_t=${timestamp}`, {
      baseURL: '',
      timeout: 10000 // 10 saniyelik timeout ekle
    });
    
    // Yanıt kontrolü
    if (response.status !== 200) {
      throw new Error(`Sunucu ${response.status} hatası döndürdü`);
    }
    
    // Veri formatını kontrol et ve doğru şekilde işle
    const data = response.data;
    
    if (Array.isArray(data)) {
      tours.value = data.map(tour => ({
        ...tour,
        price: tour.price ? parseFloat(tour.price) : 0,
        category_id: tour.category_id ? parseInt(tour.category_id) : null,
        // Dahil olanlar ve olmayanları dizi olarak formatla
        included: Array.isArray(tour.included) ? tour.included : [],
        not_included: Array.isArray(tour.not_included) ? tour.not_included : [],
        // Eksik alanlara varsayılan değer atama
        status: tour.status || 'inactive',
        featured_image: tour.featured_image || 'https://via.placeholder.com/300x200?text=Görsel+Yok'
      }));
      
      // Bilgileri yerel depolamada sakla (önbellek)
      localStorage.setItem('admin_tours_cache', JSON.stringify({
        timestamp: Date.now(),
        data: tours.value
      }));
      
      console.log(`${tours.value.length} tur başarıyla yüklendi`);
    } else {
      console.error('API yanıtı bir dizi değil:', data);
      throw new Error('Sunucudan beklenmeyen veri formatı alındı');
    }
  } catch (err) {
    console.error('Tur yükleme hatası:', err);
    
    // Hata durumunda daha kullanıcı dostu hata mesajları
    if (err.response) {
      // Sunucudan bir yanıt geldi
      if (err.response.status === 404) {
        error.value = 'Tur verileri bulunamadı. Lütfen daha sonra tekrar deneyin.';
      } else if (err.response.status === 500) {
        error.value = 'Sunucu hatası. Teknik ekip bilgilendirildi.';
      } else {
        error.value = `Sunucu hatası: ${err.response.status} - ${err.response.statusText}`;
      }
    } else if (err.request) {
      // İstek yapıldı, yanıt dönmedi
      error.value = 'Sunucudan yanıt alınamadı. Lütfen internet bağlantınızı kontrol edin.';
    } else {
      // İstek oluşturulamadı
      error.value = `Turlar yüklenirken bir hata oluştu: ${err.message}`;
    }
    
    // Önbellekten yüklemeyi dene
    try {
      const cachedData = localStorage.getItem('admin_tours_cache');
      if (cachedData) {
        const parsed = JSON.parse(cachedData);
        // 30 dakikadan eski değilse önbellekten yükle
        const cacheAge = Date.now() - parsed.timestamp;
        if (cacheAge < 30 * 60 * 1000) {
          tours.value = parsed.data;
          console.log('Veriler önbellekten yüklendi', tours.value.length);
          error.value += ' Önbellekten yüklenen veriler gösteriliyor.';
        }
      }
    } catch (cacheError) {
      console.error('Önbellek yükleme hatası:', cacheError);
    }
  } finally {
    loading.value = false;
  }
};

// Kategorileri getir - artık yeni API servisini kullanıyor
const fetchCategories = async (onlyActive = false) => {
  try {
    console.log('Kategoriler yükleniyor...');
    loading.value = true; // Yükleme göstergesini aktif et
    
    // Önbellekten kategorileri kontrol et
    const cacheKey = onlyActive ? 'admin_active_categories_cache' : 'admin_categories_cache';
    const cachedCategories = localStorage.getItem(cacheKey);
    
    if (cachedCategories) {
      const parsed = JSON.parse(cachedCategories);
      const cacheAge = Date.now() - parsed.timestamp;
      
      // 1 saatten yeni önbellek varsa kullan
      if (cacheAge < 60 * 60 * 1000) {
        categories.value = parsed.data;
        console.log('Kategoriler önbellekten yüklendi:', categories.value.length);
        return;
      }
    }
    
    // API servisini kullanarak kategorileri getir
    const data = onlyActive 
      ? await adminService.getCategoriesActive()
      : await adminService.getCategories();
    
    if (Array.isArray(data)) {
      categories.value = data;
      
      // Önbelleğe kaydet
      localStorage.setItem(cacheKey, JSON.stringify({
        timestamp: Date.now(),
        data: categories.value
      }));
      
      console.log('Kategoriler başarıyla yüklendi:', categories.value.length);
    } else {
      throw new Error('Geçersiz kategori verisi alındı');
    }
  } catch (err) {
    console.error('Kategori yükleme hatası:', err);
    
    // Hata mesajı oluştur
    error.value = `Kategoriler yüklenirken bir hata oluştu: ${err.message}`;
    
    // Hata durumunda önbellekten yüklemeyi dene
    try {
      const cacheKey = onlyActive ? 'admin_active_categories_cache' : 'admin_categories_cache';
      const cachedCategories = localStorage.getItem(cacheKey);
      
      if (cachedCategories) {
        const parsed = JSON.parse(cachedCategories);
        categories.value = parsed.data;
        console.log('Kategoriler önbellekten yüklendi (hata sonrası):', categories.value.length);
        error.value += ' Önbellekten yüklenen kategoriler gösteriliyor.';
      } else {
        categories.value = [];
      }
    } catch (cacheError) {
      console.error('Kategori önbellek yükleme hatası:', cacheError);
      categories.value = [];
    }
  } finally {
    loading.value = false; // Yükleme göstergesini kapat
  }
};

// Filtrelenmiş turlar - performans iyileştirmeleri ve hata yakalama ekledim
const filteredTours = computed(() => {
  try {
    // Henüz turlar yüklenmemişse veya boş ise
    if (!tours.value || tours.value.length === 0) {
      return [];
    }
    
    // Memoization için önceki arama değerlerini tut
    const { searchQuery: prevSearch, selectedCategory: prevCategory, sortBy: prevSort } = filteredTours._prevFilters || {};
    
    // Arama parametreleri değişmemişse, hesaplanmış sonucu döndür (performans için)
    if (filteredTours._cachedResult && 
        prevSearch === searchQuery.value && 
        prevCategory === selectedCategory.value && 
        prevSort === sortBy.value) {
      return filteredTours._cachedResult;
    }
    
    // Input değerlerini kaydedelim
    const query = (searchQuery.value || '').toLowerCase().trim();
    const category = selectedCategory.value;
    const sortOrder = sortBy.value;
    
    // Filtreleme işlemini başlatalım - tur dizisini kopyala (referans hatalarını önle)
    let result = [...tours.value];
    
    // Arama sorgusu ile filtrele - regex seçeneği için
    if (query) {
      // Performans için RegExp kullan
      try {
        // Normal arama
        result = result.filter(tour => {
          if (!tour) return false;
          
          // İlgili alanlarda ara
          return (
            (tour.title && tour.title.toLowerCase().includes(query)) || 
            (tour.location && tour.location.toLowerCase().includes(query)) ||
            (tour.description && tour.description.toLowerCase().includes(query))
          );
        });
      } catch (searchError) {
        console.error('Arama filtresi hatası:', searchError);
      }
    }
    
    // Kategori ile filtrele
    if (category && category !== 'all') {
      result = result.filter(tour => 
        tour && (parseInt(tour.category_id) === parseInt(category))
      );
    }
    
    // Sıralama
    if (sortOrder) {
      try {
        switch (sortOrder) {
          case 'newest':
            result.sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0));
            break;
          case 'oldest':
            result.sort((a, b) => new Date(a.created_at || 0) - new Date(b.created_at || 0));
            break;
          case 'price-asc':
            result.sort((a, b) => parseFloat(a.price || 0) - parseFloat(b.price || 0));
            break;
          case 'price-desc':
            result.sort((a, b) => parseFloat(b.price || 0) - parseFloat(a.price || 0));
            break;
          case 'title-asc':
            result.sort((a, b) => (a.title || '').localeCompare(b.title || ''));
            break;
          case 'title-desc':
            result.sort((a, b) => (b.title || '').localeCompare(a.title || ''));
            break;
        }
      } catch (sortError) {
        console.error('Sıralama hatası:', sortError);
      }
    }
    
    // Sayfalama için toplam sayfa sayısını hesapla
    totalPages.value = Math.max(1, Math.ceil(result.length / perPage.value));
    
    // Eğer geçerli sayfa toplam sayfa sayısından büyükse, son sayfaya git
    if (currentPage.value > totalPages.value && totalPages.value > 0) {
      currentPage.value = totalPages.value;
    }
    
    // Hesaplanan parametreleri sakla
    filteredTours._prevFilters = { 
      searchQuery: searchQuery.value,
      selectedCategory: selectedCategory.value,
      sortBy: sortBy.value
    };
    
    // Sayfalama uygulanmış sonucu hesapla
    const startIndex = (currentPage.value - 1) * perPage.value;
    const endIndex = startIndex + perPage.value;
    const paginatedResult = result.slice(startIndex, endIndex);
    
    // Sonucu önbelleğe al (sayfalanmış halini)
    filteredTours._cachedResult = paginatedResult;
    
    return paginatedResult;
  } catch (err) {
    console.error('Filtreleme hatası:', err);
    return [];
  }
});

// Toplam tur sayısı - filtrelenmiş ve sayfalanmamış tam listedeki sayı
const totalTours = computed(() => {
  try {
    if (!tours.value) return 0;
    
    // Filtreleme için kullanılan değerleri al
    const query = (searchQuery.value || '').toLowerCase().trim();
    const category = selectedCategory.value;
    
    // Toplam sayıyı hesaplamak için turları filtrele (sayfalamadan önce)
    let result = [...tours.value];
    
    // Arama sorgusu ile filtrele
    if (query) {
      result = result.filter(tour => {
        if (!tour) return false;
        return (
          (tour.title && tour.title.toLowerCase().includes(query)) || 
          (tour.location && tour.location.toLowerCase().includes(query)) ||
          (tour.description && tour.description.toLowerCase().includes(query))
        );
      });
    }
    
    // Kategori ile filtrele
    if (category && category !== 'all') {
      result = result.filter(tour => 
        tour && (parseInt(tour.category_id) === parseInt(category))
      );
    }
    
    return result.length;
  } catch (err) {
    console.error('Toplam sayısı hesaplama hatası:', err);
    return 0;
  }
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
    resetForm();
    console.log('Form değerleri (modal açılırken):', form.value);
    // Formun içindeki görsel alanını temizle
    const fileInput = document.getElementById('featured_image');
    if (fileInput) {
      fileInput.value = '';
    }
    
    // Sadece aktif kategorileri getir
    fetchCategories(true);
    
    showAddModal.value = true;
  } catch (err) {
    console.error('Modal açılırken hata oluştu:', err);
    error.value = 'Tur ekleme formunu açarken bir hata oluştu: ' + err.message;
    setTimeout(() => {
      error.value = null;
    }, 5000);
  }
};

const editTour = (tour) => {
  console.log('Düzenlenecek tur:', tour);
  
  // Sadece aktif kategorileri getir
  fetchCategories(true);
  
  // Derin kopya oluştur (referansları kopyalama sorunundan kaçınmak için)
  const tourCopy = JSON.parse(JSON.stringify(tour));
  
  // Form değerlerini ayarla
  form.value = {
    id: tourCopy.id,
    title: tourCopy.title,
    price: tourCopy.price,
    category_id: tourCopy.category_id,
    duration: tourCopy.duration,
    location: tourCopy.location,
    description: tourCopy.description,
    max_participants: tourCopy.max_participants || '',
    featured_image: tourCopy.featured_image || '',
    status: tourCopy.status || 'active'
  };
  
  // Dahil olanları ve olmayanları text formatına çevir
  if (tourCopy.included && Array.isArray(tourCopy.included)) {
    includedText.value = tourCopy.included.join('\n');
  } else {
    includedText.value = '';
  }
  
  if (tourCopy.not_included && Array.isArray(tourCopy.not_included)) {
    notIncludedText.value = tourCopy.not_included.join('\n');
  } else {
    notIncludedText.value = '';
  }
  
  console.log('Form verileri (düzenleme için):', form.value);
  console.log('Dahil olanlar:', includedText.value);
  console.log('Dahil olmayanlar:', notIncludedText.value);
  
  errors.value = {};
  showDetailModal.value = false; // Detay modalını kapat
  showEditModal.value = true;
};

const confirmDelete = (tour) => {
  try {
  tourToDelete.value = tour;
  showDeleteModal.value = true;
    console.log('Silme modalı açıldı');
  } catch (err) {
    console.error('Silme modalı açılırken hata oluştu:', err);
    error.value = 'Silme onayı açılırken bir hata oluştu: ' + err.message;
  }
};

// Tur oluşturma işlemi - iyileştirilmiş hata yönetimi ve kullanıcı deneyimi
const createTour = async (event) => {
  // Form submit olayını engelle
  if (event) event.preventDefault();
  
  loading.value = true;
  errors.value = {};
  error.value = null; // İşlem başlamadan önce hata mesajını temizle
  
  try {
    // Form verilerinin geçerliliğini kontrol et - trim hatasını önle
    const requiredFields = ['title', 'price', 'duration', 'location', 'description'];
    let hasError = false;
    
    requiredFields.forEach(field => {
      // Önce değerin string olup olmadığını kontrol et, sonra trim uygula
      if (!form.value[field] || 
          (typeof form.value[field] === 'string' && form.value[field].trim() === '') ||
          (typeof form.value[field] !== 'string' && !form.value[field])) {
        errors.value[field] = [`${field.charAt(0).toUpperCase() + field.slice(1)} alanı zorunludur.`];
        hasError = true;
      }
    });
    
    if (hasError) {
      error.value = 'Lütfen zorunlu alanları doldurun.';
      loading.value = false;
      return;
    }
    
    const formData = new FormData();
    
    // CSRF token ekle
    formData.append('_token', getCsrfToken());
    
    // Temel alanları ekle - doğrudan değerleri ekle, trim işlemini güvenli yap
    formData.append('title', typeof form.value.title === 'string' ? form.value.title.trim() : form.value.title || '');
    formData.append('price', form.value.price || 0);
    formData.append('category_id', form.value.category_id || '');
    formData.append('duration', typeof form.value.duration === 'string' ? form.value.duration.trim() : form.value.duration || '');
    formData.append('location', typeof form.value.location === 'string' ? form.value.location.trim() : form.value.location || '');
    formData.append('max_participants', form.value.max_participants || '');
    formData.append('description', typeof form.value.description === 'string' ? form.value.description.trim() : form.value.description || '');
    formData.append('status', form.value.status || 'active');
    
    // Görsel dosyası veya URL'sini ekle
    if (imageType.value === 'file' && form.value.featured_image) {
      // Artık dosya Base64 string formatında olduğu için doğrudan gönder
      formData.append('featured_image', form.value.featured_image);
    } else if (imageType.value === 'url' && featuredImageUrl.value) {
      formData.append('featured_image', featuredImageUrl.value);
    } else {
      // Varsayılan bir URL ekle
      formData.append('featured_image', 'https://via.placeholder.com/800x600?text=Tur+Görseli');
    }
    
    // Dahil olanlar/olmayanlar
    if (includedText.value) {
      const includedItems = includedText.value.split('\n').filter(item => item.trim() !== '');
      formData.append('included', JSON.stringify(includedItems));
    } else {
      formData.append('included', JSON.stringify([]));
    }
    
    if (notIncludedText.value) {
      const notIncludedItems = notIncludedText.value.split('\n').filter(item => item.trim() !== '');
      formData.append('not_included', JSON.stringify(notIncludedItems));
    } else {
      formData.append('not_included', JSON.stringify([]));
    }
    
    console.log('Form verileri gönderiliyor...');
    
    // Progresif geliştirme: İlk önce önbelleği temizle
    localStorage.removeItem('admin_tours_cache');
    
    // API isteği yap
    const response = await axios.post('/admin/tours-store', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
        'X-Requested-With': 'XMLHttpRequest'
      },
      baseURL: '',
      timeout: 30000 // 30 saniye timeout
    });
    
    // Başarılı yanıt - Controller'dan 201 veya mesaj içinde başarılı olduğunu söylüyorsa
    if (response.status === 201 || (response.data && response.data.message && response.data.message.includes('başarıyla'))) {
      // Önce modalları kapat
      showAddModal.value = false;
      showEditModal.value = false;
      
      // Sonra success mesajını ayarla
      success.value = 'Tur başarıyla oluşturuldu.';
      error.value = null; // Hata mesajını temizle (güvenlik için)
      
      // Eklenen turun verilerini döndüyse doğrudan ekle
      if (response.data.tour) {
        // Backend'den dönen tur verisini diziye ekle
        const newTour = response.data.tour;
        tours.value.unshift(newTour); // Yeni tur en başa eklenir
        
        // Önbelleği güncelle
        localStorage.setItem('admin_tours_cache', JSON.stringify({
          timestamp: Date.now(),
          data: tours.value
        }));
      } else {
        // Yoksa tüm verileri yeniden çek
        await fetchTours();
      }
      
      resetForm();
      
      // Başarı mesajını 3 saniye sonra kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    } else {
      error.value = response.data.message || 'Tur oluşturulurken bir hata oluştu.';
      success.value = null; // Başarı mesajını temizle
    }
  } catch (err) {
    if (err.response && err.response.status === 422) {
      // Validasyon hataları
      errors.value = err.response.data.errors || {};
      error.value = 'Lütfen form alanlarını kontrol edin.';
    } else {
      console.error('Tur oluşturma hatası:', err);
      error.value = 'Tur oluşturulurken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
    success.value = null; // Başarı mesajını temizle
  } finally {
    loading.value = false;
  }
};

// Tur güncelleme işlemi
const updateTour = async (event) => {
  // Form submit olayını engelle
  if (event) event.preventDefault();
  
  if (!form.value.id) {
    error.value = 'Güncellenecek tur ID bulunamadı.';
    return;
  }
  
  loading.value = true;
  errors.value = {};
  error.value = null;
  
  try {
    const formData = new FormData();
    
    // CSRF token ekle
    formData.append('_token', getCsrfToken());
    
    // Temel alanları ekle
    formData.append('id', form.value.id);
    formData.append('title', form.value.title || '');
    formData.append('price', form.value.price || '');
    formData.append('category_id', form.value.category_id || '');
    formData.append('duration', form.value.duration || '');
    formData.append('location', form.value.location || '');
    formData.append('max_participants', form.value.max_participants || '');
    formData.append('description', form.value.description || '');
    formData.append('status', form.value.status || 'active');
    
    // Görsel dosyası veya URL'sini ekle
    if (imageType.value === 'file' && form.value.featured_image) {
      // Artık dosya Base64 string formatında olduğu için doğrudan gönder
      formData.append('featured_image', form.value.featured_image);
    } else if (imageType.value === 'url' && featuredImageUrl.value) {
      formData.append('featured_image', featuredImageUrl.value);
    } else {
      // Varsayılan bir URL ekle
      formData.append('featured_image', 'https://via.placeholder.com/800x600?text=Tur+Görseli');
    }
    
    // Dahil olanlar/olmayanlar
    if (includedText.value) {
      const includedItems = includedText.value.split('\n').filter(item => item.trim() !== '');
      formData.append('included', JSON.stringify(includedItems));
    } else {
      formData.append('included', JSON.stringify([]));
    }
    
    if (notIncludedText.value) {
      const notIncludedItems = notIncludedText.value.split('\n').filter(item => item.trim() !== '');
      formData.append('not_included', JSON.stringify(notIncludedItems));
    } else {
      formData.append('not_included', JSON.stringify([]));
    }
    
    console.log('Güncelleme verileri gönderiliyor...', Object.fromEntries(formData));
    
    // API isteği yap - Backend routes yapılandırmasına göre URL değişebilir
    const response = await axios.post(`/admin/tours-update/${form.value.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      baseURL: ''
    });
    
    // Başarılı yanıt
    if (response.status === 200 || response.data.message?.includes('başarıyla') || response.data.message?.includes('güncellendi')) {
      // Önce modalı kapat
      showEditModal.value = false;
      
      // Sonra başarı mesajını göster
      success.value = 'Tur başarıyla güncellendi.';
      error.value = null;
      
      // Güncel veriyi dizide bul ve güncelle
      if (response.data.tour) {
        const updatedTour = response.data.tour;
        const tourIndex = tours.value.findIndex(t => t.id === updatedTour.id);
        
        if (tourIndex !== -1) {
          // Tours dizisindeki ilgili öğeyi güncelle
          tours.value[tourIndex] = updatedTour;
        }
      } else {
        // Eğer güncel veri dönmediyse, tüm listeyi yeniden çek
        await fetchTours();
      }
      
      resetForm();
      
      // Başarı mesajını 3 saniye sonra kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    } else {
      error.value = response.data.message || 'Tur güncellenirken bir hata oluştu.';
      success.value = null;
    }
  } catch (err) {
    if (err.response && err.response.status === 422) {
      // Validasyon hataları
      errors.value = err.response.data.errors || {};
      error.value = 'Lütfen form alanlarını kontrol edin.';
    } else {
      console.error('Tur güncelleme hatası:', err);
      error.value = 'Tur güncellenirken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
    success.value = null;
  } finally {
    loading.value = false;
  }
};

const deleteTour = async () => {
  // Gerekli bilgi kontrolü ve işlem durumu kontrolü
  if (!tourToDelete.value || !tourToDelete.value.id || loading.value) {
    console.error('Silinecek tur bilgisi eksik veya işlem zaten devam ediyor');
    showDeleteModal.value = false;
    return;
  }
  
  loading.value = true;
  error.value = null;
  
  try {
    const tourId = tourToDelete.value.id;
    console.log('Silinecek tur ID:', tourId);
    
    // Axios kullanarak silme işlemi yap
    const response = await axios.post(`/admin/tours-delete/${tourId}`, {
      _token: getCsrfToken()
    });
    
    console.log('Silme işlemi yanıtı:', response.data);
    
    // Başarılı yanıt
    if (response.status === 200) {
      console.log('Tur başarıyla silindi');
      success.value = 'Tur başarıyla silindi.';
      
      // UI'dan da sil - API'ye istek yapmadan
      tours.value = tours.value.filter(tour => tour.id !== tourId);
    
      // Modal kapat
      tourToDelete.value = null;
      showDeleteModal.value = false;
      
      // 3 saniye sonra başarı mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    } else {
      throw new Error(`Beklenmeyen yanıt: ${response.status}`);
    }
  } catch (err) {
    console.error('Tur silme hatası:', err);
    
    // API yanıt detaylarını kontrol et ve daha detaylı hata mesajı oluştur
    let errorMessage = 'Tur silinirken bir hata oluştu';
    
    if (err.response) {
      // Sunucudan yanıt geldi, ama hata kodu var
      errorMessage += `: ${err.response.status} - ${JSON.stringify(err.response.data)}`;
      
      // 404 hatası için özel mesaj
      if (err.response.status === 404) {
        errorMessage = 'Bu tur zaten silinmiş veya bulunamıyor. Lütfen sayfayı yenileyip tekrar deneyin.';
        
        // 404 hatası durumunda yine de listeden kaldır
        tours.value = tours.value.filter(tour => tour.id !== tourToDelete.value?.id);
        tourToDelete.value = null;
        showDeleteModal.value = false;
      }
    } else if (err.request) {
      // İstek yapıldı, yanıt dönmedi
      errorMessage += ': Sunucudan yanıt alınamadı';
    } else {
      // İstek oluşturulamadı
      errorMessage += ': ' + err.message;
    }
    
    error.value = errorMessage;
    
    // 5 saniye sonra hata mesajını kaldır
    setTimeout(() => {
      error.value = null;
    }, 5000);
  } finally {
    loading.value = false;
  }
};

const closeModals = () => {
  try {
  showAddModal.value = false;
  showEditModal.value = false;
  showDetailModal.value = false;
  resetForm();
    console.log('Modallar kapatıldı');
  } catch (err) {
    console.error('Modal kapatılırken hata oluştu:', err);
    error.value = 'Modal kapatılırken bir hata oluştu: ' + err.message;
  }
};

const resetForm = () => {
  try {
  form.value = {
    id: null,
    title: '',
    price: '',
    category_id: null,
    duration: '',
    location: '',
    max_participants: '',
    description: '',
    featured_image: null,
    status: 'active'
  };
    
  includedText.value = '';
  notIncludedText.value = '';
  featuredImageUrl.value = '';
  imageType.value = 'file';
  errors.value = {};
    console.log('Form sıfırlandı:', form.value);
  } catch (err) {
    console.error('Form sıfırlama hatası:', err);
    error.value = 'Form sıfırlanırken bir hata oluştu: ' + err.message;
  }
};

const cancelDelete = () => {
  tourToDelete.value = null;
  showDeleteModal.value = false;
};

// CSRF token almak için güvenli yöntem
const getCsrfToken = () => {
  try {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!token) {
      console.warn('CSRF token bulunamadı! Sayfanın <head> bölümünde meta[name="csrf-token"] etiketi bulunduğundan emin olun.');
    }
    return token || '';
  } catch (err) {
    console.error('CSRF token alınırken hata oluştu:', err);
    return '';
  }
};

// Görsel yükleme işlemi - geliştirilmiş hata kontrolü ve önizleme
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (!file) {
    form.value.featured_image = null;
    return;
  }
  
  // Dosya boyutu kontrolü - maksimum 5MB
  const maxSize = 5 * 1024 * 1024; // 5MB
  if (file.size > maxSize) {
    errors.value.featured_image = ['Dosya boyutu 5MB\'tan küçük olmalıdır.'];
    // Dosya seçim alanını temizle
    event.target.value = '';
    return;
  }
  
  // Dosya tipi kontrolü - sadece resim dosyaları
  const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
  if (!allowedTypes.includes(file.type)) {
    errors.value.featured_image = ['Sadece JPG, PNG, GIF veya WEBP dosyaları yükleyebilirsiniz.'];
    // Dosya seçim alanını temizle
    event.target.value = '';
    return;
  }
  
  // Dosyayı Base64'e dönüştür
  const reader = new FileReader();
  reader.onload = (e) => {
    try {
      // Base64 formatında string olarak kaydet
      form.value.featured_image = e.target.result;
      
      // Hata mesajını temizle
      if (errors.value.featured_image) {
        delete errors.value.featured_image;
      }
      
      console.log('Görsel dosyası Base64 formatına dönüştürüldü');
    } catch (err) {
      console.error('Base64 dönüşüm hatası:', err);
      errors.value.featured_image = ['Dosya dönüştürülürken bir hata oluştu.'];
    }
  };
  
  reader.onerror = () => {
    console.error('Dosya okuma hatası');
    errors.value.featured_image = ['Dosya okunamadı. Lütfen başka bir dosya seçin.'];
    // Dosya seçim alanını temizle
    event.target.value = '';
  };
  
  reader.readAsDataURL(file);
};

// Görsel tipini değiştirme işlemi
const switchImageType = (type) => {
  imageType.value = type;
  
  // Tip değiştiğinde diğer alanı temizle
  if (type === 'file') {
    featuredImageUrl.value = '';
  } else {
    form.value.featured_image = null;
    const fileInput = document.getElementById('featured_image');
    if (fileInput) fileInput.value = '';
  }
};

// Helpers
const formatPrice = (price) => {
  if (price === null || price === undefined || isNaN(price)) {
    return '₺0';
  }
  return `₺${parseFloat(price).toLocaleString('tr-TR')}`;
};

const getStatusText = (status) => {
  const statusMap = {
    'active': 'Aktif',
    'inactive': 'Pasif',
    'pending': 'Onay Bekliyor'
  };
  return statusMap[status] || status;
};

// Kategori adını getiren yardımcı fonksiyon
const getCategoryName = (categoryId) => {
  if (!categoryId) return 'Kategori Yok';
  
  // Kategoriler henüz yüklenmemişse
  if (!categories.value || categories.value.length === 0) {
    return 'Yükleniyor...';
  }
  
  const category = categories.value.find(c => c.id === parseInt(categoryId));
  return category ? category.name : 'Tanımlanmamış Kategori';
};

// Component yüklendiğinde
onMounted(async () => {
  console.log('Tours component yüklendi');
  try {
    // CSRF token kontrolü
    const token = getCsrfToken();
    console.log('CSRF token bulundu:', token ? 'Evet' : 'Hayır');
    
    // Önce kategorileri, sonra turları getir - liste görünümde tüm kategorileri göster
    console.log('fetchCategories çağrılıyor...');
    await fetchCategories(false);
    console.log('fetchTours çağrılıyor...');
    await fetchTours();
    
    // Form verilerini başlangıç için temizle
    resetForm();
  } catch (error) {
    console.error('Component yüklenirken hata:', error);
    error.value = 'Sayfa yüklenirken bir hata oluştu: ' + error.message;
  }
});

const viewTour = (tour) => {
  selectedTour.value = tour;
  showDetailModal.value = true;
};

// Görsel hatası durumunda çalışacak fonksiyon
const handleImageError = (event, tour) => {
  console.log(`Görsel yükleme hatası: ${tour.title}`);
  // Varsayılan yerel bir resim kullan
  event.target.src = '/images/tours/default.jpg';
  event.target.classList.add('error-image');
  
  // Kaydı güncelle
  if (tour && tour.id) {
    const index = tours.value.findIndex(t => t.id === tour.id);
    if (index !== -1) {
      // Hatalı görseli kaydedilmiş boş görsel olarak işaretle
      tours.value[index].featured_image_error = true;
    }
  }
};

// Filtreleri sıfırlama fonksiyonu
const resetFilters = () => {
  searchQuery.value = '';
  selectedCategory.value = 'all';
  sortBy.value = '';
  currentPage.value = 1;
  console.log('Filtreler sıfırlandı');
};
</script> 