<template>
  <AdminLayout>
    <div class="container mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
          Rezervasyonlar Yönetimi
        </h1>
        
        <button 
          @click="openAddModal" 
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Yeni Rezervasyon Ekle
        </button>
      </div>
      
      <!-- Hata Mesajları -->
      <div v-if="error" class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-900/30 dark:text-red-400 dark:border-red-500 rounded" role="alert">
        <p class="font-medium">Hata</p>
        <p>{{ error }}</p>
      </div>
      
      <!-- Başarı Mesajları -->
      <div v-if="success" class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-900/30 dark:text-green-400 dark:border-green-500 rounded" role="alert">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          <p class="font-medium">Başarılı</p>
        </div>
        <p>{{ success }}</p>
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
              placeholder="Rezervasyon ara..."
              class="pl-10 pr-4 py-2 w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
              @input="fetchReservations"
            >
          </div>
          
          <div class="flex flex-wrap gap-2">
            <select 
              v-model="selectedStatus"
              @change="fetchReservations"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
              <option value="">Tüm Durumlar</option>
              <option value="confirmed">Onaylandı</option>
              <option value="pending">Beklemede</option>
              <option value="cancelled">İptal Edildi</option>
              <option value="completed">Tamamlandı</option>
            </select>
            
            <select 
              v-model="selectedTour"
              @change="fetchReservations"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
              <option value="">Tüm Turlar</option>
              <option v-for="tour in tours" :key="tour.id" :value="tour.id">
                {{ tour.title }} ({{ formatPrice(tour.price) }})
              </option>
            </select>
            
            <select 
              v-model="sortBy"
              @change="fetchReservations"
              class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
            >
              <option value="">Sıralama</option>
              <option value="created_at-desc">En Yeni</option>
              <option value="created_at-asc">En Eski</option>
              <option value="total_price-asc">Fiyat (Artan)</option>
              <option value="total_price-desc">Fiyat (Azalan)</option>
              <option value="reservation_date-asc">Tarih (Artan)</option>
              <option value="reservation_date-desc">Tarih (Azalan)</option>
            </select>
          </div>
        </div>
      </div>
      
      <!-- Loading -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500"></div>
      </div>
      
      <!-- Reservations Table -->
      <div v-else-if="reservations.length === 0" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Henüz Rezervasyon Bulunmuyor</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Yeni bir rezervasyon ekleyerek başlayabilirsiniz.</p>
        <button 
          @click="openAddModal" 
          class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors inline-flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Yeni Rezervasyon Ekle
        </button>
      </div>
      
      <div v-else class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full whitespace-nowrap">
            <thead>
              <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                <th class="px-6 py-3">Rezervasyon No</th>
                <th class="px-6 py-3">Müşteri</th>
                <th class="px-6 py-3">Tur</th>
                <th class="px-6 py-3">Tarih</th>
                <th class="px-6 py-3">Kişi Sayısı</th>
                <th class="px-6 py-3">Toplam Fiyat</th>
                <th class="px-6 py-3">Durum</th>
                <th class="px-6 py-3">İşlemler</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="reservation in filteredReservations" :key="reservation.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">#{{ reservation.id }}</td>
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center text-white overflow-hidden">
                      <span class="text-sm font-medium">{{ getUserInitials(reservation.user?.name || '') }}</span>
                    </div>
                    <div class="ml-4">
                      <div class="font-medium text-gray-900 dark:text-white">{{ reservation.user?.name || '---' }}</div>
                      <div class="text-xs text-gray-500">{{ reservation.user?.email || '---' }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ reservation.tour?.title || '---' }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ formatDate(reservation.reservation_date) }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ reservation.participant_count }}</td>
                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ formatPrice(reservation.total_price) }}</td>
                <td class="px-6 py-4">
                  <span 
                    class="px-2 py-1 text-xs rounded-full"
                    :class="getStatusClass(reservation.status)"
                  >
                    {{ getStatusLabel(reservation.status) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex space-x-2">
                    <button 
                      @click="editReservation(reservation)"
                      class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" 
                      title="Düzenle"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      v-if="reservation.status !== 'cancelled'"
                      @click="updateReservationStatus(reservation, 'cancelled')"
                      class="p-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" 
                      title="İptal Et"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                    <button 
                      v-if="reservation.status === 'pending'"
                      @click="updateReservationStatus(reservation, 'confirmed')"
                      class="p-1 text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300" 
                      title="Onayla"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                      </svg>
                    </button>
                    <button 
                      v-if="reservation.status === 'confirmed'"
                      @click="updateReservationStatus(reservation, 'completed')"
                      class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" 
                      title="Tamamlandı"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                    <button 
                      @click="confirmDelete(reservation)"
                      class="p-1 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300" 
                      title="Sil"
                    >
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      
      <!-- Sayfalama -->
      <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600 dark:text-gray-400">
            Toplam <span class="font-medium text-gray-900 dark:text-white">{{ totalReservations }}</span> rezervasyon
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
      
      <!-- Yeni Rezervasyon Modal -->
      <div v-if="showAddModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-4 relative z-10">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Yeni Rezervasyon Ekle
          </h2>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="user_id">
                  Kullanıcı <span class="text-red-500">*</span>
                </label>
                <select
                  id="user_id"
                  v-model="formData.user_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                >
                  <option value="">Kullanıcı Seçin</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }} ({{ user.email }})
                  </option>
                </select>
                <p v-if="formErrors.user_id" class="mt-1 text-sm text-red-600">{{ formErrors.user_id[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="tour_id">
                  Tur <span class="text-red-500">*</span>
                </label>
                <select
                  id="tour_id"
                  v-model="formData.tour_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                  @change="calculateTotalPrice"
                >
                  <option value="">Tur Seçin</option>
                  <option v-for="tour in tours" :key="tour.id" :value="tour.id">
                    {{ tour.title }} ({{ formatPrice(tour.price) }})
                  </option>
                </select>
                <p v-if="formErrors.tour_id" class="mt-1 text-sm text-red-600">{{ formErrors.tour_id[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="reservation_date">
                  Rezervasyon Tarihi <span class="text-red-500">*</span>
                </label>
                <input 
                  type="date" 
                  id="reservation_date" 
                  v-model="formData.reservation_date"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                />
                <p v-if="formErrors.reservation_date" class="mt-1 text-sm text-red-600">{{ formErrors.reservation_date[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="participant_count">
                  Katılımcı Sayısı <span class="text-red-500">*</span>
                </label>
                <input 
                  type="number" 
                  id="participant_count" 
                  v-model="formData.participant_count"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                  @input="onParticipantsChange"
                />
                <p v-if="formErrors.participant_count" class="mt-1 text-sm text-red-600">{{ formErrors.participant_count[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1">
                  Toplam Fiyat
                </label>
                <div class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-white">
                  {{ calculateTotalPriceDisplay() }}
                </div>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="status">
                  Durum
                </label>
                <select
                  id="status"
                  v-model="formData.status"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
                <p v-if="formErrors.status" class="mt-1 text-sm text-red-600">{{ formErrors.status[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="payment_method">
                  Ödeme Yöntemi
                </label>
                <select
                  id="payment_method"
                  v-model="formData.payment_method"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option value="">Seçin...</option>
                  <option value="credit_card">Kredi Kartı</option>
                  <option value="bank_transfer">Banka Havalesi</option>
                  <option value="cash">Nakit</option>
                </select>
                <p v-if="formErrors.payment_method" class="mt-1 text-sm text-red-600">{{ formErrors.payment_method[0] }}</p>
              </div>
            </div>
            
            <!-- Yeni Rezervasyon Modal'ına özel istekler alanı ekle -->
            <div class="col-span-1 md:col-span-2 mb-3">
              <label class="block text-gray-700 dark:text-gray-300 mb-1" for="special_requests">
                Özel İstekler
              </label>
              <textarea 
                id="special_requests" 
                v-model="formData.special_requests"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
              ></textarea>
              <p v-if="formErrors.special_requests" class="mt-1 text-sm text-red-600">{{ formErrors.special_requests[0] }}</p>
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
                <span v-else>Ekle</span>
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Rezervasyon Düzenleme Modal -->
      <div v-if="showEditModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full p-4 relative z-10">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            Rezervasyon Düzenle
          </h2>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_user_id">
                  Kullanıcı <span class="text-red-500">*</span>
                </label>
                <select
                  id="edit_user_id"
                  v-model="formData.user_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                >
                  <option value="">Kullanıcı Seçin</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }} ({{ user.email }})
                  </option>
                </select>
                <p v-if="formErrors.user_id" class="mt-1 text-sm text-red-600">{{ formErrors.user_id[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_tour_id">
                  Tur <span class="text-red-500">*</span>
                </label>
                <select
                  id="edit_tour_id"
                  v-model="formData.tour_id"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                  @change="calculateTotalPrice"
                >
                  <option value="">Tur Seçin</option>
                  <option v-for="tour in tours" :key="tour.id" :value="tour.id">
                    {{ tour.title }} ({{ formatPrice(tour.price) }})
                  </option>
                </select>
                <p v-if="formErrors.tour_id" class="mt-1 text-sm text-red-600">{{ formErrors.tour_id[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_reservation_date">
                  Rezervasyon Tarihi <span class="text-red-500">*</span>
                </label>
                <input 
                  type="date" 
                  id="edit_reservation_date" 
                  v-model="formData.reservation_date"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                />
                <p v-if="formErrors.reservation_date" class="mt-1 text-sm text-red-600">{{ formErrors.reservation_date[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_participant_count">
                  Katılımcı Sayısı <span class="text-red-500">*</span>
                </label>
                <input 
                  type="number" 
                  id="edit_participant_count" 
                  v-model="formData.participant_count"
                  min="1"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                  required
                  @input="onParticipantsChange"
                />
                <p v-if="formErrors.participant_count" class="mt-1 text-sm text-red-600">{{ formErrors.participant_count[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1">
                  Toplam Fiyat
                </label>
                <div class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-white">
                  {{ calculateTotalPriceDisplay() }}
                </div>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_status">
                  Durum
                </label>
                <select
                  id="edit_status"
                  v-model="formData.status"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.label }}
                  </option>
                </select>
                <p v-if="formErrors.status" class="mt-1 text-sm text-red-600">{{ formErrors.status[0] }}</p>
              </div>
              
              <div class="mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_payment_method">
                  Ödeme Yöntemi
                </label>
                <select
                  id="edit_payment_method"
                  v-model="formData.payment_method"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                >
                  <option value="">Seçin...</option>
                  <option value="credit_card">Kredi Kartı</option>
                  <option value="bank_transfer">Banka Havalesi</option>
                  <option value="cash">Nakit</option>
                </select>
                <p v-if="formErrors.payment_method" class="mt-1 text-sm text-red-600">{{ formErrors.payment_method[0] }}</p>
              </div>
              </div>
              
            <!-- Yeni Rezervasyon Modal'ına özel istekler alanı ekle -->
              <div class="col-span-1 md:col-span-2 mb-3">
                <label class="block text-gray-700 dark:text-gray-300 mb-1" for="edit_special_requests">
                  Özel İstekler
                </label>
                <textarea 
                  id="edit_special_requests" 
                  v-model="formData.special_requests"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                ></textarea>
                <p v-if="formErrors.special_requests" class="mt-1 text-sm text-red-600">{{ formErrors.special_requests[0] }}</p>
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
                <span v-else>Güncelle</span>
              </button>
            </div>
          </form>
        </div>
      </div>
      
      <!-- Silme Onay Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeModals"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6 relative z-10">
          <div class="text-center">
            <svg class="w-12 h-12 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">
              Rezervasyonu Sil
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
              <span v-if="selectedReservation">
                <strong>#{{ selectedReservation.id }}</strong> no'lu rezervasyonu silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
              </span>
            </p>
            <div class="mt-6 flex justify-center space-x-3">
              <button 
                type="button"
                @click="closeModals" 
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-gray-800 dark:text-gray-200"
              >
                İptal
              </button>
              <button 
                type="button" 
                @click="deleteReservation"
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '@/layouts/AdminLayout.vue';

// State değişkenleri
const reservations = ref([]);
const tours = ref([]);
const users = ref([]);
const loading = ref(false);
const error = ref(null);
const success = ref(null);
const searchQuery = ref('');
const selectedStatus = ref('');
const selectedTour = ref('');
const sortBy = ref('reservation_date-desc');
const formErrors = ref({});
const showAddModal = ref(false);
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const selectedReservation = ref(null);
const currentPage = ref(1);
const perPage = ref(10);
const isEditing = ref(false);

// Form state
const formData = ref({
  user_id: '',
  tour_id: '',
  reservation_date: '',
  participant_count: 1,
  total_price: 0,
  status: 'pending',
  payment_method: '',
  special_requests: ''
});

// Durum seçenekleri
const statusOptions = [
  { value: 'pending', label: 'Beklemede' },
  { value: 'confirmed', label: 'Onaylandı' },
  { value: 'cancelled', label: 'İptal Edildi' },
  { value: 'completed', label: 'Tamamlandı' }
];

// Filtrelenmiş rezervasyonlar - Arama ve filtreleme özellikleri
const filteredReservations = computed(() => {
  try {
    // reservations.value null, undefined veya array değilse boş array döndür
    if (!reservations.value || !Array.isArray(reservations.value)) {
      console.log('Rezervasyonlar iterable değil:', reservations.value);
      return [];
    }
    
    console.log('Filtreleme başladı. Rezervasyon sayısı:', reservations.value.length);
    let result = [...reservations.value];
    
    // Arama sorgusu ile filtrele
    if (searchQuery.value && searchQuery.value.trim() !== '') {
      const query = searchQuery.value.toLowerCase().trim();
      result = result.filter(reservation => {
        if (!reservation) return false;
        return (
          // Kullanıcı adı veya e-postası içinde arama
          (reservation.user?.name && reservation.user.name.toLowerCase().includes(query)) || 
          (reservation.user?.email && reservation.user.email.toLowerCase().includes(query)) ||
          // Tur adı içinde arama
          (reservation.tour?.title && reservation.tour.title.toLowerCase().includes(query)) ||
          // Rezervasyon ID'si ile arama
          (reservation.id && reservation.id.toString().includes(query))
        );
      });
    }
    
    // Durum ile filtrele
    if (selectedStatus.value && selectedStatus.value !== '') {
      result = result.filter(reservation => reservation && reservation.status === selectedStatus.value);
    }
    
    // Tur ile filtrele
    if (selectedTour.value && selectedTour.value !== '') {
      result = result.filter(reservation => reservation && reservation.tour_id == selectedTour.value);
    }
    
    // Sıralama
    if (sortBy.value && sortBy.value !== '') {
      const [field, direction] = sortBy.value.split('-');
      
      result.sort((a, b) => {
        try {
          switch (field) {
            case 'created_at':
              const dateA = new Date(a.created_at || 0);
              const dateB = new Date(b.created_at || 0);
              return direction === 'asc' ? dateA - dateB : dateB - dateA;
            
            case 'reservation_date':
              const resDateA = new Date(a.reservation_date || 0);
              const resDateB = new Date(b.reservation_date || 0);
              return direction === 'asc' ? resDateA - resDateB : resDateB - resDateA;
            
            case 'total_price':
              const priceA = parseFloat(a.total_price || 0);
              const priceB = parseFloat(b.total_price || 0);
              return direction === 'asc' ? priceA - priceB : priceB - priceA;
            
            case 'participant_count':
              const countA = parseInt(a.participant_count || 0);
              const countB = parseInt(b.participant_count || 0);
              return direction === 'asc' ? countA - countB : countB - countA;
            
            default:
              return 0;
          }
        } catch (err) {
          console.error('Sıralama hatası:', err);
          return 0;
        }
      });
    }
    
    // Toplam sayfa sayısını hesapla
    const totalItems = result.length;
    const calculatedTotalPages = Math.ceil(totalItems / perPage.value) || 1;
    totalPages.value = calculatedTotalPages;
    
    // Geçerli sayfa toplam sayfa sayısından büyükse, son sayfaya git
    if (currentPage.value > totalPages.value) {
      currentPage.value = totalPages.value;
    }
    
    // Sayfalama uygula
    const startIndex = (currentPage.value - 1) * perPage.value;
    const endIndex = startIndex + perPage.value;
    const paginatedResult = result.slice(startIndex, endIndex);
    
    console.log('Filtreleme sonrası rezervasyon sayısı:', result.length);
    console.log('Sayfalama sonrası rezervasyon sayısı:', paginatedResult.length);
    console.log('Sayfa:', currentPage.value, '/', totalPages.value);
    
    return paginatedResult;
  } catch (err) {
    console.error('Filtreleme hatası:', err);
    return [];
  }
});

// Toplam rezervasyon sayısı
const totalReservations = computed(() => {
  try {
    if (!reservations.value || !Array.isArray(reservations.value)) {
      return 0;
    }
    
    let count = reservations.value.length;
    
    // Filtreleme varsa, filtrelenmiş sayıyı hesapla
    if (searchQuery.value || selectedStatus.value || selectedTour.value) {
      count = reservations.value.filter(reservation => {
        if (!reservation) return false;
        
        // Arama sorgusu kontrolü
        if (searchQuery.value && searchQuery.value.trim() !== '') {
          const query = searchQuery.value.toLowerCase().trim();
          if (!(
            (reservation.user?.name && reservation.user.name.toLowerCase().includes(query)) || 
            (reservation.user?.email && reservation.user.email.toLowerCase().includes(query)) ||
            (reservation.tour?.title && reservation.tour.title.toLowerCase().includes(query)) ||
            (reservation.id && reservation.id.toString().includes(query))
          )) {
            return false;
          }
        }
        
        // Durum filtresi
        if (selectedStatus.value && selectedStatus.value !== '' && reservation.status !== selectedStatus.value) {
          return false;
        }
        
        // Tur filtresi
        if (selectedTour.value && selectedTour.value !== '' && reservation.tour_id != selectedTour.value) {
          return false;
        }
        
        return true;
      }).length;
    }
    
    return count;
  } catch (err) {
    console.error('Toplam sayı hesaplama hatası:', err);
    return 0;
  }
});

// Sayfa değiştirme fonksiyonu
const changePage = (page) => {
  if (page < 1 || page > totalPages.value) return;
  currentPage.value = page;
  window.scrollTo(0, 0); // Sayfa değiştiğinde en üste kaydır
};

// totalPages değişkeni ekle
const totalPages = ref(1);

// Rezervasyonları getir
const fetchReservations = async () => {
    loading.value = true;
    error.value = null;
  success.value = null;
    
  try {
    console.log('Rezervasyonlar getiriliyor...');
    
    // Web route yapısını kullan - API route'u yerine
    const response = await axios.get('/admin/reservations-list', {
        params: {
          search: searchQuery.value,
          status: selectedStatus.value,
          tour_id: selectedTour.value,
        sort_by: sortBy.value
        },
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      });
    
    console.log('Rezervasyonlar API Yanıtı:', response);
    
    if (response.data && response.data.data) {
      console.log('Rezervasyon verileri alındı (data içinde):', response.data.data.length);
      reservations.value = response.data.data;
    } else if (response.data && Array.isArray(response.data)) {
      console.log('Rezervasyon verileri alındı (doğrudan dizi):', response.data.length);
      reservations.value = response.data;
    } else {
      console.error('Beklenmeyen API yanıtı:', response.data);
      error.value = 'API yanıtı beklenen formatta değil';
      
      // Veritabanından gördüğümüz verilerle demo veri oluştur
      const demoReservations = [
        {
          id: 1,
          user_id: 2,
          tour_id: 11,
          reservation_date: '2025-04-24',
          participant_count: 5,
          total_price: 22500.00,
          status: 'pending',
          payment_method: 'cash',
          payment_id: null,
          special_requests: null,
          created_at: '2025-03-26 21:05:50',
          updated_at: '2025-04-09 21:05:50',
          user: { id: 2, name: 'Demo Kullanıcı', email: 'demo@example.com' },
          tour: { id: 11, title: 'Demo Tur', price: 4500.00 }
        },
        {
          id: 2,
          user_id: 2,
          tour_id: 1,
          reservation_date: '2025-05-28',
          participant_count: 1,
          total_price: 2500.00,
          status: 'confirmed',
          payment_method: 'bank_transfer',
          payment_id: null,
          special_requests: 'Vejetaryen yemek seçenekleri olursa sevinirim.',
          created_at: '2025-03-25 21:05:50',
          updated_at: '2025-04-09 21:05:50',
          user: { id: 2, name: 'Demo Kullanıcı', email: 'demo@example.com' },
          tour: { id: 1, title: 'Demo Tur', price: 2500.00 }
        }
      ];
      
      console.log('Demo verileri kullanılıyor...');
      reservations.value = demoReservations;
    }
  } catch (err) {
    console.error('Rezervasyonlar yüklenirken hata oluştu:', err);
    
    if (err.response) {
      console.error('Hata detayları:', {
        status: err.response.status,
        headers: err.response.headers,
        data: err.response.data
      });
      
      error.value = `Rezervasyonlar yüklenirken bir hata oluştu (${err.response.status}): ${err.response.data?.message || 'Bilinmeyen hata'}`;
    } else if (err.request) {
      console.error('Yanıt alınamadı:', err.request);
      error.value = 'Sunucudan yanıt alınamadı. Lütfen internet bağlantınızı kontrol edin.';
    } else {
      console.error('Hata oluştu:', err.message);
      error.value = 'İstek oluşturulurken bir hata oluştu: ' + err.message;
    }
    
    // Hata durumunda yine demo verileri kullan
    const demoReservations = [
      {
        id: 1,
        user_id: 2,
        tour_id: 11,
        reservation_date: '2025-04-24',
        participant_count: 5,
        total_price: 22500.00,
        status: 'pending',
        payment_method: 'cash',
        payment_id: null,
        special_requests: null,
        created_at: '2025-03-26 21:05:50',
        updated_at: '2025-04-09 21:05:50',
        user: { id: 2, name: 'Demo Kullanıcı', email: 'demo@example.com' },
        tour: { id: 11, title: 'Demo Tur', price: 4500.00 }
      },
      {
        id: 2,
        user_id: 2,
        tour_id: 1,
        reservation_date: '2025-05-28',
        participant_count: 1,
        total_price: 2500.00,
        status: 'confirmed',
        payment_method: 'bank_transfer',
        payment_id: null,
        special_requests: 'Vejetaryen yemek seçenekleri olursa sevinirim.',
        created_at: '2025-03-25 21:05:50',
        updated_at: '2025-04-09 21:05:50',
        user: { id: 2, name: 'Demo Kullanıcı', email: 'demo@example.com' },
        tour: { id: 1, title: 'Demo Tur', price: 2500.00 }
      }
    ];
    
    console.log('Hata sonrası demo verileri kullanılıyor...');
    reservations.value = demoReservations;
  } finally {
    loading.value = false;
  }
};

// Turları getir (dropdown için)
const fetchTours = async () => {
  try {
    console.log('Turlar getiriliyor...');
    
    // Alternatif URL yapısı kullanıyoruz
    // const response = await axios.get('/api/admin/tours', {
    const response = await axios.get('/admin/tours-list', {
      params: { limit: 100 },
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    console.log('Turlar API Yanıtı:', response);
    
    if (response.data && response.data.data) {
      console.log('Tur verileri alındı:', response.data.data.length);
      tours.value = response.data.data;
      return response.data.data;
    } else if (response.data && Array.isArray(response.data)) {
      console.log('Tur verileri (dizi) alındı:', response.data.length);
      tours.value = response.data;
      return response.data;
    } else {
      console.error('Beklenmeyen API yanıtı (tours):', response.data);
      return [];
    }
  } catch (err) {
    console.error('Turlar yüklenirken hata oluştu:', err);
    
    if (err.response) {
      console.error('Turlar hata detayları:', {
        status: err.response.status,
        data: err.response.data
      });
    }
    
    return [];
  }
};

// Kullanıcıları getir (dropdown için)
const fetchUsers = async () => {
  try {
    console.log('Kullanıcılar getiriliyor...');
    
    // Doğrudan /admin/users-list API'sini çağıralım
    const response = await axios.get('/admin/users-list', {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    console.log('Kullanıcılar API Yanıtı:', response);
    
    if (response.data && Array.isArray(response.data)) {
      console.log('Kullanıcı verileri (dizi) alındı:', response.data.length);
      users.value = response.data;
      return response.data;
    } else if (response.data && response.data.data) {
      console.log('Kullanıcı verileri (data içinde) alındı:', response.data.data.length);
      users.value = response.data.data;
      return response.data.data;
    } else {
      // Kullanıcı verileri alınamazsa, demo veriler oluştur
      console.log('Kullanıcı verileri oluşturuluyor...');
      const demoUsers = [
        { id: 1, name: 'Demo Kullanıcı', email: 'demo@example.com' }
      ];
      users.value = demoUsers;
      return demoUsers;
    }
  } catch (err) {
    console.error('Kullanıcılar yüklenirken hata oluştu:', err);
    
    if (err.response) {
      console.error('Kullanıcılar hata detayları:', {
        status: err.response.status,
        data: err.response.data
      });
    }
    
    // Hata durumunda demo veriler göster
    const demoUsers = [
      { id: 1, name: 'Demo Kullanıcı', email: 'demo@example.com' }
    ];
    users.value = demoUsers;
    return demoUsers;
  }
};

// Rezervasyon oluştur
const createReservation = async () => {
  loading.value = true;
  errors.value = {};
  error.value = null;
  success.value = null;
  
  try {
    // Tur fiyatı ve toplam fiyat
    const totalPrice = calculateTotalPrice();
    
    const formData = {
      ...formData.value,
      total_price: totalPrice
    };
    
    console.log('Yeni rezervasyon oluşturuluyor:', formData);
    
    // Web route'a POST isteği kullanıyoruz
    const response = await axios.post('/admin/reservations-store', formData, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    // Modal kapat ve formu sıfırla
    showAddModal.value = false;
    resetForm();
    
    // Başarı mesajı göster
    success.value = 'Rezervasyon başarıyla oluşturuldu';
    
    // Rezervasyonları yeniden yükle
    fetchReservations();
    
    // 3 saniye sonra başarı mesajını kaldır
    setTimeout(() => {
      success.value = null;
    }, 3000);
  } catch (err) {
    console.error('Rezervasyon oluşturma hatası:', err);
    
    if (err.response && err.response.status === 422) {
      // Validasyon hataları
      errors.value = err.response.data.errors || {};
      error.value = 'Lütfen form alanlarını kontrol edin.';
    } else {
      error.value = 'Rezervasyon oluşturulurken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
  } finally {
    loading.value = false;
  }
};

// Rezervasyon güncelle
const updateReservation = async () => {
  if (!selectedReservation.value) return;
  
  loading.value = true;
  errors.value = {};
  error.value = null;
  success.value = null;
  
  try {
    // Tur fiyatı ve toplam fiyat
    const totalPrice = calculateTotalPrice();
    
    const requestData = {
      ...formData.value,
      total_price: totalPrice
    };
    
    console.log(`Rezervasyon güncelleniyor ID: ${selectedReservation.value.id}`, requestData);
    console.log('Form verisi: ', formData.value);
    
    // Web route'a POST isteği kullanıyoruz (PUT değil)
    const response = await axios.post(`/admin/reservations-update/${selectedReservation.value.id}`, requestData, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    console.log('Güncelleme yanıtı:', response.data);
    
    // Modal kapat ve formu sıfırla
    showEditModal.value = false;
    resetForm();
    
    // Başarı mesajı göster
    success.value = 'Rezervasyon başarıyla güncellendi';
    
    // Rezervasyonları yeniden yükle
    fetchReservations();
    
    // 3 saniye sonra başarı mesajını kaldır
    setTimeout(() => {
      success.value = null;
    }, 3000);
  } catch (err) {
    console.error('Rezervasyon güncelleme hatası:', err);
    
    // Daha detaylı hata bilgisi yazdır
    if (err.response) {
      console.error('API Yanıt hatası:', {
        status: err.response.status,
        statusText: err.response.statusText,
        data: err.response.data,
        headers: err.response.headers
      });
      
      const errorDetail = err.response.data?.error || '';
      
      if (err.response.status === 422) {
        // Validasyon hataları
        errors.value = err.response.data.errors || {};
        error.value = 'Lütfen form alanlarını kontrol edin.';
      } else if (err.response.status === 500) {
        error.value = `Sunucu hatası: ${errorDetail}`;
      } else {
        error.value = `Rezervasyon güncellenirken bir hata oluştu (${err.response.status}): ${errorDetail || err.response.data?.message || 'Bilinmeyen hata'}`;
      }
    } else if (err.request) {
      console.error('İstek hatası (yanıt alınamadı):', err.request);
      error.value = 'Sunucudan yanıt alınamadı. Lütfen internet bağlantınızı kontrol edin.';
    } else {
      console.error('İstek oluşturulurken hata:', err.message);
      error.value = `İstek oluşturulurken hata: ${err.message}`;
    }
  } finally {
    loading.value = false;
  }
};

// Toplam fiyatı hesapla
const calculateTotalPrice = () => {
  // Form değerlerini al ve dönüştür, geçersiz değerlerde güvenli varsayılanları kullan
  const tourId = parseInt(formData.value.tour_id) || 0;
  const participants = Math.max(parseInt(formData.value.participant_count || 1), 1);
  
  if (tourId) {
    const tour = tours.value.find(t => t.id === tourId);
    if (tour && tour.price !== undefined && tour.price !== null) {
      // Temiz bir sayı garantile
      const tourPrice = parseFloat(tour.price) || 0;
      const totalPrice = tourPrice * participants;
      
      console.log(`Toplam fiyat hesaplandı: ${tourPrice} x ${participants} = ${totalPrice}`);
      
      // Negatif fiyat olmamalı
      return Math.max(totalPrice, 0);
    }
  }
  
  console.log('Geçerli bir tur veya fiyat bulunamadı, fiyat 0 olarak ayarlandı.');
  return 0;
};

// Toplam fiyatı görüntülemek için
const calculateTotalPriceDisplay = () => {
  return formatPrice(calculateTotalPrice());
};

// Katılımcı sayısı değiştiğinde fiyatı güncelle
const onParticipantsChange = () => {
  // Geçersiz değerler için düzeltme
  if (!formData.value.participant_count || parseInt(formData.value.participant_count) < 1) {
    formData.value.participant_count = 1;
  }
  
  // Hesaplamayı çağır
  calculateTotalPrice();
};

// Rezervasyon durumunu güncelle
const updateReservationStatus = async (reservation, newStatus) => {
  try {
    loading.value = true;
    error.value = null;
    
    console.log(`Rezervasyon #${reservation.id} durumu güncelleniyor: ${newStatus}`);
    
    const response = await axios.post(`/admin/reservations-status/${reservation.id}`, {
      status: newStatus
    }, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    console.log('Status güncelleme yanıtı:', response);
    
    if (response.data) {
      // Rezervasyonu güncellenmiş durumla güncelle
      const index = reservations.value.findIndex(r => r.id === reservation.id);
      if (index !== -1) {
        reservations.value[index].status = newStatus;
      }
      
      success.value = response.data.message || `Rezervasyon durumu "${getStatusLabel(newStatus)}" olarak güncellendi`;
      
      // 3 saniye sonra başarı mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    }
  } catch (err) {
    console.error('Durum güncelleme hatası:', err);
    error.value = 'Rezervasyon durumu güncellenirken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    
    // 3 saniye sonra hata mesajını kaldır
    setTimeout(() => {
      error.value = null;
    }, 3000);
  } finally {
    loading.value = false;
  }
};

// Rezervasyon silme
const deleteReservation = async () => {
  if (!selectedReservation.value) return;
  
  try {
    loading.value = true;
    error.value = null;
    
    console.log(`Rezervasyon #${selectedReservation.value.id} siliniyor`);
    
    // API endpoint'i web.php'deki yeni rotaları kullanalım
    // const response = await axios.delete(`/api/admin/reservations/${selectedReservation.value.id}`);
    const response = await axios.post(`/admin/reservations-delete/${selectedReservation.value.id}`, {}, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    console.log('Silme yanıtı:', response);
    
    // Başarılı silme işlemi sonrası
    if (response.data && response.data.success) {
      // Silinen rezervasyonu listeden kaldır
      reservations.value = reservations.value.filter(r => r.id !== selectedReservation.value.id);
      
      // Modal'ı kapat
      showDeleteModal.value = false;
      
      // Başarı mesajı göster
      success.value = response.data.message || 'Rezervasyon başarıyla silindi';
      
      // 3 saniye sonra başarı mesajını kaldır
      setTimeout(() => {
        success.value = null;
      }, 3000);
    }
  } catch (err) {
    console.error('Silme hatası:', err);
    error.value = 'Rezervasyon silinirken bir hata oluştu: ' + (err.response?.data?.message || err.message);
    
    // 3 saniye sonra hata mesajını kaldır
    setTimeout(() => {
      error.value = null;
    }, 3000);
  } finally {
    loading.value = false;
    selectedReservation.value = null;
  }
};

// Rezervasyon düzenle
const editReservation = async (reservation) => {
  try {
    console.log('Düzenlenen rezervasyon:', reservation);
    selectedReservation.value = reservation;
    
    // Kullanıcı ve tur verilerinin önceden yüklenmesini sağla
    await Promise.all([
      users.value.length === 0 ? fetchUsers() : Promise.resolve(),
      tours.value.length === 0 ? fetchTours() : Promise.resolve()
    ]);
    
    // Form verilerini ayarla
    formData.value = {
      user_id: reservation.user_id,
      tour_id: reservation.tour_id,
      reservation_date: reservation.reservation_date,
      participant_count: reservation.participant_count || 1,
      status: reservation.status,
      payment_method: reservation.payment_method || '',
      special_requests: reservation.special_requests || ''
    };
    
    console.log('Düzenleme form verileri:', formData.value);
    console.log('Mevcut kullanıcılar:', users.value);
    
    // Kullanıcı listesinde aranan kullanıcı ID'si var mı kontrol et
    const userExists = users.value.some(u => u.id === reservation.user_id);
    if (!userExists && reservation.user) {
      console.log('Kullanıcı listesine ekleniyor:', reservation.user);
      users.value.push(reservation.user);
    }
    
    isEditing.value = true;
    formErrors.value = {};
    showEditModal.value = true;
  } catch (err) {
    console.error('Düzenleme modalı açılırken hata:', err);
    error.value = 'Düzenleme modalı açılırken bir hata oluştu: ' + err.message;
  }
};

const confirmDelete = (reservation) => {
  selectedReservation.value = reservation;
  showDeleteModal.value = true;
};

// Form sıfırlama ve model kapatma
const resetForm = () => {
  formData.value = {
    user_id: '',
    tour_id: '',
    reservation_date: '',
    participant_count: 1,
    status: 'pending',
    payment_method: '',
    special_requests: ''
  };
  formErrors.value = {};
  error.value = null;
  selectedReservation.value = null;
  isEditing.value = false;
};

// Tüm modalları kapat
const closeModals = () => {
  showAddModal.value = false;
  showEditModal.value = false;
  showDeleteModal.value = false;
  resetForm();
};

// Kullanıcı baş harflerini al
const getUserInitials = (name) => {
  if (!name) return '';
  
  const nameParts = name.split(' ');
  if (nameParts.length === 1) {
    return nameParts[0].charAt(0).toUpperCase();
  }
  
  return (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
};

// Format fiyat
const formatPrice = (price) => {
  // Null, undefined veya NaN kontrolü
  if (price === null || price === undefined || isNaN(parseFloat(price))) {
    return '₺0,00';
  }
  
  // Negatif değerleri 0'a çevir
  const validPrice = Math.max(parseFloat(price), 0);
  
  // Türk Lirası formatında döndür
  return `₺${validPrice.toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`;
};

// Durum etiketi getir
const getStatusLabel = (status) => {
  switch (status) {
    case 'pending':
      return 'Beklemede';
    case 'confirmed':
      return 'Onaylandı';
    case 'cancelled':
      return 'İptal Edildi';
    case 'completed':
      return 'Tamamlandı';
    default:
      return 'Bilinmiyor';
  }
};

// Durum renk sınıfları
const getStatusClass = (status) => {
  switch (status) {
    case 'pending':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/30 dark:text-yellow-500';
    case 'confirmed':
      return 'bg-green-100 text-green-800 dark:bg-green-800/30 dark:text-green-500';
    case 'cancelled':
      return 'bg-red-100 text-red-800 dark:bg-red-800/30 dark:text-red-500';
    case 'completed':
      return 'bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500';
    default:
      return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
  }
};

// Tarih formatla
const formatDate = (dateString) => {
  if (!dateString) return '---';
  
  try {
    const date = new Date(dateString);
    
    // Geçerli tarih değilse
    if (isNaN(date.getTime())) {
      return '---';
    }
    
    // Türkçe tarih formatı
    return date.toLocaleDateString('tr-TR', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  } catch (err) {
    console.error('Tarih formatı hatası:', err);
    return dateString || '---';
  }
};

// Tur adını al
const getTourName = (tourId) => {
  const tour = tours.value.find(t => t.id === tourId);
  return tour ? tour.title : '---';
};

// Kullanıcı adını al
const getUserName = (userId) => {
  const user = users.value.find(u => u.id === userId);
  return user ? user.name : '---';
};

// Component yüklendiğinde
onMounted(async () => {
  try {
    // Start loading indicator
    loading.value = true;
    error.value = null;
    
    console.log('Admin Reservations component yüklendi');
    
    // CSRF token bilgisini kontrol et
    const csrfToken = getCsrfToken();
    console.log('CSRF Token mevcut:', csrfToken ? 'Evet' : 'Hayır');
    
    // API isteğinin sonucunu almak için bir fonksiyon
    const fetchWithRetry = async (fetchFn, retries = 2) => {
      let lastError;
      for (let i = 0; i <= retries; i++) {
        try {
          return await fetchFn();
        } catch (err) {
          console.error(`Deneme ${i+1}/${retries+1} başarısız:`, err);
          lastError = err;
          // Son deneme değilse bekle
          if (i < retries) {
            await new Promise(resolve => setTimeout(resolve, 1000));
          }
        }
      }
      throw lastError;
    };
    
    // Tüm API isteklerini yap ve sonuçlarını topla
    const results = await Promise.allSettled([
      fetchWithRetry(() => fetchTours()),
      fetchWithRetry(() => fetchUsers()),
      fetchWithRetry(() => fetchReservations())
    ]);
    
    // API isteklerinin sonuçlarını konsola yazdır
    console.log('API isteklerinin sonuçları:', results);
    
    // Sonuçları kontrol et
    const [toursResult, usersResult, reservationsResult] = results;
    
    let errorMessages = [];
    
    if (toursResult.status === 'rejected') {
      console.error('Turlar yüklenemedi:', toursResult.reason);
      errorMessages.push('Turlar yüklenemedi');
    }
    
    if (usersResult.status === 'rejected') {
      console.error('Kullanıcılar yüklenemedi:', usersResult.reason);
      errorMessages.push('Kullanıcılar yüklenemedi');
    }
    
    if (reservationsResult.status === 'rejected') {
      console.error('Rezervasyonlar yüklenemedi:', reservationsResult.reason);
      errorMessages.push('Rezervasyonlar yüklenemedi');
    }
    
    // Veri durumunu konsola yazdır
    console.log('Veri yükleme işlemi sonuçları:');
    console.log('Kullanıcılar:', users.value?.length || 0);
    console.log('Turlar:', tours.value?.length || 0);
    console.log('Rezervasyonlar:', reservations.value?.length || 0);
    
    // Eğer veri yüklenemediyse kullanıcıyı bilgilendirelim
    if (errorMessages.length > 0) {
      error.value = `Veri yükleme hatası: ${errorMessages.join(', ')}`;
    } else if (users.value.length === 0 && tours.value.length === 0 && reservations.value.length === 0) {
      error.value = 'Hiçbir veri yüklenemedi. Lütfen sayfayı yenileyin veya yönetici ile iletişime geçin.';
    } else if (reservations.value.length === 0) {
      // Eğer veri yoksa, ama API çalışıyorsa, bu tamamen normal bir durum olabilir
      console.log('Henüz rezervasyon kaydı bulunmuyor.');
    }
  } catch (err) {
    console.error('Veri yükleme hatası:', err);
    error.value = 'Veriler yüklenirken bir hata oluştu: ' + err.message;
  } finally {
    loading.value = false;
  }
});

// CSRF token alma fonksiyonu
const getCsrfToken = () => {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
};

// Form işlemleri
const openAddForm = () => {
  formData.value = {
    user_id: '',
    tour_id: '',
    reservation_date: '',
    participant_count: 1,
    status: 'pending',
    payment_method: '',
    special_requests: ''
  };
  formErrors.value = {};
  isEditing.value = false;
  showAddModal.value = true;
};

const openEditForm = (reservation) => {
  selectedReservation.value = reservation;
  formData.value = {
    user_id: reservation.user_id,
    tour_id: reservation.tour_id,
    reservation_date: reservation.reservation_date,
    participant_count: reservation.participant_count || 1,
    status: reservation.status,
    payment_method: reservation.payment_method || '',
    special_requests: reservation.special_requests || ''
  };
  formErrors.value = {};
  isEditing.value = true;
  showEditModal.value = true;
};

const closeForm = () => {
  showAddModal.value = false;
  showEditModal.value = false;
  isEditing.value = false;
  selectedReservation.value = null;
  formData.value = {
    user_id: '',
    tour_id: '',
    reservation_date: '',
    participant_count: 1,
    status: 'pending',
    payment_method: '',
    special_requests: ''
  };
  formErrors.value = {};
};

const submitForm = async () => {
  try {
    loading.value = true;
    error.value = null;
    formErrors.value = {};
    
    // Basit validasyon
    if (!formData.value.user_id) {
      formErrors.value.user_id = ['Kullanıcı seçilmelidir'];
      loading.value = false;
      return;
    }
    
    if (!formData.value.tour_id) {
      formErrors.value.tour_id = ['Tur seçilmelidir'];
      loading.value = false;
      return;
    }
    
    if (!formData.value.reservation_date) {
      formErrors.value.reservation_date = ['Rezervasyon tarihi seçilmelidir'];
      loading.value = false;
      return;
    }
    
    console.log('Form gönderiliyor...');
    
    // Form verilerini API'ye göndermeden önce düzelt
    const formDataToSend = {
      ...formData.value,
      user_id: parseInt(formData.value.user_id),
      tour_id: parseInt(formData.value.tour_id),
      participant_count: parseInt(formData.value.participant_count),
      status: formData.value.status // String olarak gönder
    };
    
    console.log('Gönderilen form verileri:', formDataToSend);
    
    if (isEditing.value && selectedReservation.value) {
      // Güncelleme işlemi
      console.log(`Rezervasyon #${selectedReservation.value.id} güncelleniyor`, formDataToSend);
      
      // Web route güncelleme endpointi
      const response = await axios.post(`/admin/reservations-update/${selectedReservation.value.id}`, formDataToSend, {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      console.log('Güncelleme yanıtı:', response);
      
      if (response.data && response.data.status === 'success') {
        // Başarılı güncelleme işlemi
        
        // Güncellenen rezervasyonu listede güncelle
        const index = reservations.value.findIndex(r => r.id === selectedReservation.value.id);
        if (index !== -1) {
          // Değişiklikleri rezervasyon listesine yansıt
          reservations.value[index] = {
            ...reservations.value[index],
            ...formDataToSend,
            // Bunlar referans için
            user: users.value.find(u => u.id === parseInt(formDataToSend.user_id)),
            tour: tours.value.find(t => t.id === parseInt(formDataToSend.tour_id))
          };
        }
        
        // Modalı kapat
        showEditModal.value = false;
        
        // Başarı mesajı göster
        success.value = 'Rezervasyon başarıyla güncellendi';
        
        // Form verilerini sıfırla
        resetForm();
        
        // 3 saniye sonra başarı mesajını kaldır
        setTimeout(() => {
          success.value = null;
        }, 3000);
      }
    } else {
      // Yeni rezervasyon oluşturma
      console.log('Yeni rezervasyon oluşturuluyor', formDataToSend);
      
      // Web route oluşturma endpointi
      const response = await axios.post('/admin/reservations-store', formDataToSend, {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      
      console.log('Oluşturma yanıtı:', response);
      
      if (response.data && response.data.status === 'success') {
        // Başarılı oluşturma işlemi
        
        // Yeni rezervasyonu listeye ekle
        const newReservation = response.data.data;
        
        // Referans olarak kullanıcı ve tur bilgilerini ekle
        newReservation.user = users.value.find(u => u.id === parseInt(formDataToSend.user_id));
        newReservation.tour = tours.value.find(t => t.id === parseInt(formDataToSend.tour_id));
        
        reservations.value.unshift(newReservation);
        
        // Modalı kapat
        showAddModal.value = false;
        
        // Başarı mesajı göster
        success.value = 'Rezervasyon başarıyla oluşturuldu';
        
        // Form verilerini sıfırla
        resetForm();
        
        // 3 saniye sonra başarı mesajını kaldır
        setTimeout(() => {
          success.value = null;
        }, 3000);
      }
    }
  } catch (err) {
    console.error('Form gönderimi hatası:', err);
    
    // API'den dönen validasyon hatalarını göster
    if (err.response && err.response.data && err.response.data.errors) {
      formErrors.value = err.response.data.errors;
      error.value = 'Lütfen form hatalarını düzeltin.';
    } else {
      error.value = 'Rezervasyon ' + (isEditing.value ? 'güncellenirken' : 'oluşturulurken') + 
                    ' bir hata oluştu: ' + (err.response?.data?.message || err.message);
    }
  } finally {
    loading.value = false;
  }
};

// Modals
const openAddModal = async () => {
  try {
    // Ensure we have users and tours data before opening the modal
    if (users.value.length === 0) {
      await fetchUsers();
    }
    
    if (tours.value.length === 0) {
      await fetchTours();
    }
    
    // Form verilerini sıfırla
    formData.value = {
      user_id: '',
      tour_id: '',
      reservation_date: '',
      participant_count: 1,
      status: 'pending',
      payment_method: '',
      special_requests: ''
    };
    
    // Yeni kayıt işlemi için düzenle
    isEditing.value = false;
    selectedReservation.value = null;
    
    formErrors.value = {};
    showAddModal.value = true;
  } catch (err) {
    console.error('Modal açılırken hata:', err);
    error.value = 'Modal açılırken bir hata oluştu';
  }
};
</script> 