<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 relative overflow-hidden">
    <!-- Dekoratif arka plan -->
    <div class="absolute inset-0 -z-10 overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 to-indigo-50/30 dark:from-gray-900 dark:to-gray-900"></div>
      <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-96 h-96 bg-blue-300/10 dark:bg-blue-900/5 rounded-full filter blur-3xl"></div>
      <div class="absolute bottom-0 left-0 translate-y-1/4 -translate-x-1/4 w-96 h-96 bg-indigo-300/10 dark:bg-indigo-900/5 rounded-full filter blur-3xl"></div>
    </div>

    <!-- Ana İçerik -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
      <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">Profilim</h1>
      
      <!-- Sekme Menüsü -->
      <div class="border-b border-gray-200 dark:border-gray-700 mb-8">
        <ul class="flex flex-wrap -mb-px">
          <li class="mr-2">
            <button 
              @click="changeTab('reservations')" 
              class="inline-block p-4 font-medium rounded-t-lg"
              :class="activeTab === 'reservations' 
                ? 'text-primary-600 border-b-2 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
                : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300'"
            >
              Rezervasyonlarım
            </button>
          </li>
          <li class="mr-2">
            <button 
              @click="changeTab('favorites')" 
              class="inline-block p-4 font-medium rounded-t-lg"
              :class="activeTab === 'favorites' 
                ? 'text-primary-600 border-b-2 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
                : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300'"
            >
              Favorilerim
            </button>
          </li>
          <li class="mr-2">
            <button 
              @click="changeTab('profile')" 
              class="inline-block p-4 font-medium rounded-t-lg"
              :class="activeTab === 'profile' 
                ? 'text-primary-600 border-b-2 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
                : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300'"
            >
              Profil Bilgilerim
            </button>
          </li>
          <li>
            <button 
              @click="changeTab('password')" 
              class="inline-block p-4 font-medium rounded-t-lg"
              :class="activeTab === 'password' 
                ? 'text-primary-600 border-b-2 border-primary-600 dark:text-primary-500 dark:border-primary-500' 
                : 'text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300'"
            >
              Şifre Değiştir
            </button>
          </li>
        </ul>
      </div>
      
      <!-- Rezervasyonlarım Sekmesi -->
      <div v-if="activeTab === 'reservations'" class="animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Rezervasyonlarım</h2>
          
          <!-- Rezervasyon Filtreleme -->
          <div class="flex flex-wrap gap-4 mb-6">
            <button 
              @click="reservationFilter = 'all'" 
              class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
              :class="reservationFilter === 'all' 
                ? 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300' 
                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
            >
              Tüm Rezervasyonlar
            </button>
            <button 
              @click="reservationFilter = 'upcoming'" 
              class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
              :class="reservationFilter === 'upcoming' 
                ? 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300' 
                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
            >
              Yaklaşan Turlar
            </button>
            <button 
              @click="reservationFilter = 'past'" 
              class="px-4 py-2 text-sm font-medium rounded-lg transition-colors"
              :class="reservationFilter === 'past' 
                ? 'bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-300' 
                : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'"
            >
              Geçmiş Turlar
            </button>
          </div>
          
          <!-- Rezervasyon Listesi -->
          <div v-if="isLoading" class="flex items-center justify-center py-12">
            <div class="w-12 h-12 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Rezervasyonlar yükleniyor...</span>
          </div>

          <div v-else-if="filteredReservations.length > 0" class="space-y-6">
            <div 
              v-for="(reservation, index) in filteredReservations" 
              :key="index"
              class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden"
            >
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                <!-- Tur Resmi -->
                <div class="md:col-span-1">
                  <img 
                    :src="reservation.tour?.featured_image || reservation.tour?.image || '/images/tours/default.jpg'" 
                    :alt="reservation.tour?.title || 'Tur #' + reservation.tour_id" 
                    class="h-48 md:h-full w-full object-cover"
                  >
                </div>
                
                <!-- Rezervasyon Detayları -->
                <div class="md:col-span-3 p-4 md:p-6 flex flex-col">
                  <div class="flex flex-wrap justify-between items-start mb-2">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ reservation.tour?.title || 'Tur #' + reservation.tour_id }}</h3>
                    <span 
                      class="px-3 py-1 text-xs font-medium rounded-full"
                      :class="getStatusClass(reservation.status)"
                    >
                      {{ getStatusText(reservation.status) }}
                    </span>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Rezervasyon Tarihi</p>
                      <p class="text-gray-900 dark:text-white">{{ formatDate(reservation.reservation_date) }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Kişi Sayısı</p>
                      <p class="text-gray-900 dark:text-white">{{ reservation.participant_count || 1 }} Kişi</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">İşlem Numarası</p>
                      <p class="text-gray-900 dark:text-white">{{ reservation.transaction_id || 'TR' + reservation.id }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Toplam Tutar</p>
                      <p class="font-medium text-gray-900 dark:text-white">{{ formatPrice(reservation.total_price) }}</p>
                    </div>
                  </div>
                  
                  <div class="mt-auto flex flex-wrap gap-3">
                    <router-link 
                      :to="`/tour/${reservation.tour_id}`" 
                      class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    >
                      Tur Detayları
                    </router-link>
                    <button 
                      v-if="reservation.status === 'confirmed' && isUpcoming(reservation.reservation_date)"
                      @click="openCancelModal(reservation)"
                      class="px-4 py-2 text-sm font-medium rounded-lg border border-red-300 dark:border-red-700 text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                    >
                      İptal Et
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Rezervasyon Yoksa -->
          <div v-else class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Rezervasyon Bulunamadı</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">{{ reservationFilter !== 'all' ? 'Bu kategoride henüz bir rezervasyonunuz bulunmuyor.' : 'Henüz bir rezervasyonunuz bulunmuyor.' }}</p>
            <router-link 
              to="/tours" 
              class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
            >
              Turları Keşfet
            </router-link>
          </div>
        </div>
      </div>
      
      <!-- Profil Bilgilerim Sekmesi -->
      <div v-if="activeTab === 'profile'" class="animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Profil Bilgilerim</h2>
          
          <form @submit.prevent="updateProfile" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ad Soyad</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <input 
                    type="text" 
                    id="name" 
                    v-model="profileForm.name" 
                    class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                  >
                </div>
              </div>
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-posta Adresi</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <input 
                    type="email" 
                    id="email" 
                    v-model="profileForm.email" 
                    class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                  >
                </div>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cinsiyet</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <select 
                    id="gender" 
                    v-model="profileForm.gender"
                    required
                    class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                  >
                    <option value="" disabled>Seçiniz</option>
                    <option value="male">Erkek</option>
                    <option value="female">Kadın</option>
                    <option value="other">Diğer</option>
                  </select>
                </div>
              </div>
              <div>
                <label for="birthdate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doğum Tarihi</label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                  </div>
                  <input 
                    type="text" 
                    id="birthdate" 
                    readonly
                    :value="formatBirthdate(profileForm.birthdate)"
                    @click="openDatePicker" 
                    placeholder="GG/AA/YYYY"
                    class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent cursor-pointer"
                  >
                  <!-- Takvim Popover -->
                  <div v-if="showDatePicker" class="absolute z-10 mt-1 w-64 bg-white dark:bg-gray-800 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700">
                    <!-- Takvim Başlık -->
                    <div class="p-2 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                      <button @click="prevMonth" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                      </button>
                      <div class="flex space-x-1">
                        <select v-model="currentMonth" @change="updateCalendar" class="py-1 px-2 text-sm rounded border-0 bg-transparent text-gray-800 dark:text-gray-200 focus:ring-0">
                          <option v-for="(month, index) in months" :key="index" :value="index">{{ month }}</option>
                        </select>
                        <select v-model="currentYear" @change="updateCalendar" class="py-1 px-2 text-sm rounded border-0 bg-transparent text-gray-800 dark:text-gray-200 focus:ring-0">
                          <option v-for="year in yearOptions" :key="year" :value="year">{{ year }}</option>
                        </select>
                      </div>
                      <button @click="nextMonth" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                      </button>
                    </div>
                    
                    <!-- Takvim Günleri -->
                    <div class="p-2">
                      <!-- Haftanın Günleri -->
                      <div class="grid grid-cols-7 mb-1">
                        <div v-for="day in weekdays" :key="day" class="text-center text-xs font-medium text-gray-500 dark:text-gray-400 py-1">
                          {{ day }}
                        </div>
                      </div>
                      
                      <!-- Takvim Günleri -->
                      <div class="grid grid-cols-7 gap-1">
                        <div v-for="(day, index) in calendarDays" :key="index" 
                          class="text-center text-sm py-1 rounded-full cursor-pointer"
                          :class="getDayClasses(day)"
                          @click="selectDate(day)"
                        >
                          {{ day.getDate() }}
                        </div>
                      </div>
                    </div>
                    
                    <!-- Takvim Alt Butonlar -->
                    <div class="p-2 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                      <button @click="clearDate" class="px-3 py-1 text-xs text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                        Temizle
                      </button>
                      <button @click="closeDatePicker" class="px-3 py-1 text-xs bg-primary-600 text-white rounded hover:bg-primary-700">
                        Kapat
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div>
              <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefon</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <input 
                  type="tel" 
                  id="phone" 
                  v-model="profileForm.phone"
                  class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                >
              </div>
            </div>
            
            <div>
              <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adres</label>
              <div class="relative">
                <div class="absolute top-3 left-3 flex items-start pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <textarea 
                  id="address" 
                  v-model="profileForm.address" 
                  rows="3" 
                  class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent resize-none"
                ></textarea>
              </div>
            </div>
            
            <button 
              type="submit" 
              class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
              :disabled="profileUpdating"
            >
              <span v-if="profileUpdating">Güncelleniyor...</span>
              <span v-else>Profili Güncelle</span>
            </button>
          </form>
        </div>
      </div>
      
      <!-- Favorilerim Sekmesi -->
      <div v-if="activeTab === 'favorites'" class="animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Favorilerim</h2>
          
          <!-- Favoriler Listesi -->
          <div v-if="loadingFavorites" class="flex items-center justify-center py-12">
            <div class="w-12 h-12 border-4 border-primary-500 border-t-transparent rounded-full animate-spin"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Favoriler yükleniyor...</span>
          </div>
          
          <div v-else-if="favorites.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="(favorite, index) in favorites" :key="index" class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
              <div class="relative h-48">
                <img :src="favorite.image" :alt="favorite.title" class="w-full h-full object-cover">
                <div class="absolute top-2 right-2">
                  <button @click="removeFavorite(favorite.id)" class="p-2 bg-white dark:bg-gray-800 rounded-full shadow-md text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ favorite.title }}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">{{ favorite.description }}</p>
                <div class="flex items-center justify-between">
                  <span class="text-primary-600 dark:text-primary-400 font-bold">{{ favorite.formatted_price || favorite.price }}</span>
                  <router-link :to="`/tour/${favorite.id}`" class="px-3 py-1.5 bg-primary-600 text-white text-sm font-medium rounded hover:bg-primary-700 transition-colors">
                    Detaylar
                  </router-link>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Favoriler Yoksa -->
          <div v-else class="text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Henüz Favori Turunuz Yok</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6">Beğendiğiniz turları favorilere ekleyerek daha sonra kolayca bulabilirsiniz.</p>
            <router-link 
              to="/tours" 
              class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
            >
              Turları Keşfet
            </router-link>
          </div>
        </div>
      </div>
      
      <!-- Şifre Değiştir Sekmesi -->
      <div v-if="activeTab === 'password'" class="animate-fade-in">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Şifre Değiştir</h2>
          
          <form @submit.prevent="updatePassword" class="space-y-6">
            <div>
              <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mevcut Şifre</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input 
                  type="password" 
                  id="current_password" 
                  v-model="passwordForm.current_password" 
                  class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                >
              </div>
            </div>
            <div>
              <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Yeni Şifre</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                  </svg>
                </div>
                <input 
                  type="password" 
                  id="new_password" 
                  v-model="passwordForm.new_password" 
                  class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                >
              </div>
            </div>
            <div>
              <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Yeni Şifre Tekrar</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                </div>
                <input 
                  type="password" 
                  id="new_password_confirmation" 
                  v-model="passwordForm.new_password_confirmation" 
                  class="w-full pl-10 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent"
                >
              </div>
            </div>
            
            <button 
              type="submit" 
              class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
              :disabled="passwordUpdating"
            >
              <span v-if="passwordUpdating">Güncelleniyor...</span>
              <span v-else>Şifreyi Güncelle</span>
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Rezervasyon iptal modalı -->
    <div v-if="showCancelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 p-8 rounded-lg max-w-md w-full">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Rezervasyon İptal</h2>
        
        <form @submit.prevent="submitCancellation" class="space-y-6">
          <div>
            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İptal Sebebi</label>
            <textarea 
              id="reason" 
              v-model="cancellation.reason" 
              rows="3" 
              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent resize-none"
            ></textarea>
          </div>
          <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Açıklama</label>
            <textarea 
              id="description" 
              v-model="cancellation.description" 
              rows="3" 
              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent resize-none"
            ></textarea>
          </div>
          <div>
            <label for="confirmed" class="flex items-center">
              <input 
                type="checkbox" 
                id="confirmed" 
                v-model="cancellation.confirmed" 
                class="mr-2"
              >
              <span class="text-sm text-gray-700 dark:text-gray-300">Rezervasyonu iptal etmek istediğinizden emin misiniz?</span>
            </label>
          </div>
          
          <button 
            type="submit" 
            class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
            :disabled="!canSubmit"
          >
            <span v-if="!canSubmit">İptal İşlemi İçin Bilgileri Girin</span>
            <span v-else>İptal Et</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/store/auth'
import axios from 'axios'
import { useToast } from 'vue-toastification'

const router = useRouter()
const route = useRoute()

const authStore = useAuthStore()
const toast = useToast()

// Loading durumları
const isLoading = ref(false)
const reservationsLoaded = ref(false)

// Props
const props = defineProps({
  activeTab: {
    type: String,
    default: 'reservations'
  }
})

// Active tab state (initialized from props)
const activeTab = ref(props.activeTab)
const reservationFilter = ref('all')
const showCancelModal = ref(false)
const selectedReservation = ref(null)
const cancellation = ref({
  reason: '',
  description: '',
  confirmed: false
})

// Favoriler için değişkenler
const favorites = ref([])
const loadingFavorites = ref(false)

// Mock veri - Normalde API'den gelecek
const reservations = ref([
  {
    id: 1,
    tour: {
      id: 1,
      title: 'Kapadokya Balon Turu',
      image: 'https://images.pexels.com/photos/2325446/pexels-photo-2325446.jpeg?auto=compress&cs=tinysrgb&w=1200'
    },
    reservationDate: '2023-06-15',
    tourDate: '2023-07-20',
    passengers: 2,
    totalPrice: 5998,
    status: 'confirmed'
  },
  {
    id: 2,
    tour: {
      id: 2,
      title: 'Pamukkale Turu',
      image: 'https://images.pexels.com/photos/3894868/pexels-photo-3894868.jpeg?auto=compress&cs=tinysrgb&w=1200'
    },
    reservationDate: '2023-05-10',
    tourDate: '2023-05-20',
    passengers: 3,
    totalPrice: 5997,
    status: 'completed'
  },
  {
    id: 3,
    tour: {
      id: 3,
      title: 'Efes Antik Kenti Turu',
      image: 'https://images.pexels.com/photos/11421498/pexels-photo-11421498.jpeg?auto=compress&cs=tinysrgb&w=1200'
    },
    reservationDate: '2023-06-01',
    tourDate: '2023-08-05',
    passengers: 2,
    totalPrice: 2998,
    status: 'pending'
  }
])

// Profil formu
const profileForm = ref({
  name: '',
  email: '',
  gender: '',
  birthdate: null,
  phone: '',
  address: ''
})

// Şifre formu
const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

// Form durumları
const profileUpdating = ref(false)
const passwordUpdating = ref(false)

// Rezervasyon listesi için yükleme fonksiyonu
const loadReservations = async () => {
  try {
    isLoading.value = true;
    reservationsLoaded.value = false;
    
    if (!authStore.isLoggedIn) {
      toast.error('Rezervasyonlarınızı görmek için giriş yapmalısınız');
      router.push('/login?redirect=/profile?tab=reservations');
      return;
    }
    
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie');
    
    console.log('Rezervasyonlar getiriliyor...');
    
    // API'den rezervasyonları al
    const response = await axios.get('/api/reservations');
    
    console.log('API yanıtı (reservations):', response.data);
    
    if (response.data && (response.data.data || Array.isArray(response.data))) {
      // API'den gelen verileri işle
      const reservationData = response.data.data || response.data;
      
      console.log('İşlenmemiş rezervasyonlar:', reservationData);
      
      if (!reservationData || reservationData.length === 0) {
        console.warn('API yanıtında hiç rezervasyon yok');
        reservations.value = [];
      } else {
        // Tüm rezervasyonları al - filtreleme yapmıyoruz
        // Eksik alanları doldur ve normalize et
        reservations.value = reservationData.map(reservation => {
          console.log(`Rezervasyon işleniyor:`, reservation);
          
          // Tour bilgisi kontrolü
          if (!reservation.tour && reservation.tour_id) {
            reservation.tour = { 
              id: reservation.tour_id,
              title: `Tur #${reservation.tour_id}`,
              featured_image: '/images/tours/default.jpg'
            };
          }
          
          // Katılımcı sayısı kontrolü
          if (!reservation.participant_count) {
            reservation.participant_count = 1;
          }
          
          // İşlem numarası kontrolü
          if (!reservation.transaction_id) {
            reservation.transaction_id = `TR${reservation.id}`;
          }
          
          return reservation;
        });
        
        // Tarihe göre sırala (en yeni üstte)
        reservations.value.sort((a, b) => {
          const dateA = new Date(a.created_at || a.reservation_date);
          const dateB = new Date(b.created_at || b.reservation_date);
          return dateB - dateA;
        });
      }
      
      console.log('İşlenmiş rezervasyonlar:', reservations.value);
      reservationsLoaded.value = true;
    } else {
      console.warn('API yanıtı beklenen formatta değil:', response.data);
      reservations.value = [];
    }
  } catch (error) {
    console.error('Rezervasyonlar yüklenirken hata oluştu:', error);
    
    if (error.response?.status === 401) {
      toast.error('Oturum süreniz dolmuş olabilir. Lütfen tekrar giriş yapın.');
      router.push('/login?redirect=/profile?tab=reservations');
    } else {
      toast.error("Rezervasyonlar yüklenirken bir hata oluştu");
    }
  } finally {
    isLoading.value = false;
  }
};

// Filtrelenmiş rezervasyonlar
const filteredReservations = computed(() => {
  if (reservationFilter.value === 'all') {
    return reservations.value
  } else if (reservationFilter.value === 'upcoming') {
    return reservations.value.filter(r => isUpcoming(r.reservation_date))
  } else if (reservationFilter.value === 'past') {
    return reservations.value.filter(r => !isUpcoming(r.reservation_date))
  }
  return reservations.value
})

// İlk yüklemede rezervasyonları getir
onMounted(() => {
  // Tab değerini URL'den al (varsa)
  const params = new URLSearchParams(window.location.search)
  const tabParam = params.get('tab')
  if (tabParam) {
    activeTab.value = tabParam
  }
  
  // URL'deki parametre 'reservations' ise, rezervasyonları yükle
  if (activeTab.value === 'reservations') {
    loadReservations()
  }
})

// Tab değiştirildiğinde çalışacak method
const changeTab = (tab) => {
  activeTab.value = tab
  
  // URL'yi güncelle (history modu)
  router.push({ path: '/profile', query: { tab } })
  
  // Eğer sekme rezervasyonlar ise ve henüz yüklenmemişse
  if (tab === 'reservations' && reservations.value.length === 0) {
    loadReservations()
  }
  
  // Eğer sekme favoriler ise ve henüz yüklenmemişse
  if (tab === 'favorites' && favorites.value.length === 0) {
    loadFavorites()
  }
}

// Yardımcı fonksiyonlar
const formatDate = (dateString) => {
  if (!dateString) return ''
  
  const date = new Date(dateString)
  if (isNaN(date.getTime())) return dateString
  
  return date.toLocaleDateString('tr-TR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const formatPrice = (price) => {
  if (!price) return '0,00 ₺'
  
  return parseFloat(price).toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }) + ' ₺'
}

const getStatusText = (status) => {
  const statusMap = {
    'confirmed': 'Onaylandı',
    'pending': 'Bekliyor',
    'cancelled': 'İptal Edildi',
    'completed': 'Tamamlandı'
  }
  
  return statusMap[status] || status
}

const getStatusClass = (status) => {
  const classMap = {
    'confirmed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    'completed': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
  }
  
  return classMap[status] || ''
}

const isUpcoming = (dateString) => {
  if (!dateString) return false
  
  const date = new Date(dateString)
  const today = new Date()
  today.setHours(0, 0, 0, 0)
  
  return date >= today
}

// İşlem fonksiyonları
const cancelReservation = (id) => {
  if (confirm('Bu rezervasyonu iptal etmek istediğinize emin misiniz?')) {
    // API çağrısı burada yapılacak
    console.log(`Rezervasyon #${id} iptal edildi`)
    
    // Örnek olarak, frontend tarafında güncelleme yapıyoruz
    const index = reservations.value.findIndex(r => r.id === id)
    if (index !== -1) {
      reservations.value[index].status = 'cancelled'
    }
  }
}

// Kullanıcı bilgilerini getir
const fetchUserProfile = async () => {
  try {
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie')
    
    const response = await axios.get('/user')
    
    // Form alanlarını doldur
    profileForm.value = {
      name: response.data.name || '',
      email: response.data.email || '',
      gender: response.data.gender || '',
      birthdate: response.data.birthdate || null,
      phone: response.data.phone || '',
      address: response.data.address || ''
    }
    
  } catch (error) {
    console.error('Kullanıcı bilgileri alınamadı:', error)
    toast.error('Profil bilgileri yüklenirken bir hata oluştu')
  }
}

// Profil güncelleme fonksiyonu
const updateProfile = async () => {
  profileUpdating.value = true
  
  try {
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie')
    
    // Profil güncelleme isteği
    await axios.put('/user/profile-information', profileForm.value)
    
    toast.success('Profil bilgileriniz başarıyla güncellendi')
    
    // Güncel kullanıcı bilgilerini getir
    await fetchUserProfile()
    
  } catch (error) {
    console.error('Profil güncelleme hatası:', error)
    
    if (error.response) {
      console.error('API hata yanıtı:', error.response.data)
      const errorMessage = error.response.data.message || 'Profil güncellenirken bir hata oluştu.'
      toast.error(errorMessage)
    } else {
      toast.error('Profil güncellenirken bir hata oluştu.')
    }
  } finally {
    profileUpdating.value = false
  }
}

// Şifre değiştirme fonksiyonu
const updatePassword = async () => {
  // Basit validasyon
  if (passwordForm.value.new_password !== passwordForm.value.new_password_confirmation) {
    toast.error('Yeni şifreler eşleşmiyor.')
    return
  }
  
  passwordUpdating.value = true
  
  try {
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie')
    
    // Şifre değiştirme isteğini API'ye gönder
    await axios.put('/user/password', passwordForm.value)
    
    toast.success('Şifreniz başarıyla güncellendi.')
    
    // Formu temizle
    passwordForm.value.current_password = ''
    passwordForm.value.new_password = ''
    passwordForm.value.new_password_confirmation = ''
  } catch (error) {
    console.error('Şifre güncelleme hatası:', error)
    
    // API hata mesajlarını göster
    if (error.response && error.response.data && error.response.data.message) {
      toast.error(error.response.data.message)
    } else {
      toast.error('Şifre güncellenirken bir hata oluştu.')
    }
  } finally {
    passwordUpdating.value = false
  }
}

// Rezervasyon iptal modalını aç
const openCancelModal = (reservation) => {
  selectedReservation.value = reservation
  cancellation.value = {
    reason: '',
    description: '',
    confirmed: false
  }
  showCancelModal.value = true
}

// İptal formunun gönderilip gönderilemeyeceğini kontrol et
const canSubmit = computed(() => {
  return cancellation.value.reason && cancellation.value.confirmed
})

// Rezervasyon iptal işlemi
const submitCancellation = async () => {
  if (!canSubmit.value) return
  
  if (confirm('Rezervasyonu iptal etmek istediğinizden emin misiniz?')) {
    try {
      // CSRF token yenilemesi
      await axios.get('/sanctum/csrf-cookie')
      
      // API'ye iptal isteği gönder
      const response = await axios.put(`/api/reservations/${selectedReservation.value.id}`, {
        status: 'cancelled',
        cancellation_reason: cancellation.value.reason,
        cancellation_description: cancellation.value.description
      })
      
      // Rezervasyon listesini güncelle
      const index = reservations.value.findIndex(r => r.id === selectedReservation.value.id)
      if (index !== -1) {
        reservations.value[index].status = 'cancelled'
      }
      
      // Modalı kapat
      showCancelModal.value = false
      
      // Kullanıcıya bildirim göster
      toast.success('Rezervasyonunuz başarıyla iptal edilmiştir. Ödemeniz 3-5 iş günü içinde iade edilecektir.')
    } catch (error) {
      console.error('Rezervasyon iptali sırasında hata:', error)
      toast.error('Rezervasyon iptal edilirken bir hata oluştu.')
    }
  }
}

// Favori kaldırma fonksiyonu
const removeFavorite = async (id) => {
  try {
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie')
    
    const response = await axios.delete(`/favorites/remove/${id}`)
    
    if (response.data.success) {
      // Favorilerden kaldır
      favorites.value = favorites.value.filter(fav => fav.id !== id)
      toast.success("Tur favorilerden kaldırıldı")
    } else {
      toast.error("Favoriden kaldırma işlemi başarısız oldu")
    }
  } catch (error) {
    console.error('Favori kaldırma hatası:', error)
    toast.error(error.response?.data?.message || "Favoriden kaldırma işlemi sırasında bir hata oluştu")
  }
}

// Favori turları getir
const loadFavorites = async () => {
  if (!authStore.isLoggedIn) {
    toast.error('Favorilerinizi görmek için giriş yapmalısınız')
    router.push('/login?redirect=/profile?tab=favorites')
    return
  }
  
  try {
    loadingFavorites.value = true
    
    // CSRF token yenilemesi
    await axios.get('/sanctum/csrf-cookie')
    
    // API'den favorileri al
    const response = await axios.get('/api/favorites')
    
    // Gelen verileri favorites listesine aktar
    favorites.value = response.data || []
    
    console.log('Favoriler yüklendi:', favorites.value)
  } catch (error) {
    console.error('Favoriler yüklenirken hata oluştu:', error)
    toast.error("Favoriler yüklenirken bir hata oluştu")
    
    if (error.response?.status === 401) {
      toast.error('Oturum süreniz dolmuş olabilir. Lütfen tekrar giriş yapın.')
      router.push('/login?redirect=/profile?tab=favorites')
    }
  } finally {
    loadingFavorites.value = false
  }
}

// Sayfa yüklendiğinde kullanıcı bilgilerini getir
onMounted(() => {
  // Route'dan aktif tabı belirle
  updateActiveTabFromRoute()
  
  // Kullanıcı bilgilerini getir
  fetchUserProfile()
  
  // Eğer aktif tab rezervasyonlar ise rezervasyonları yükle
  if (activeTab.value === 'reservations') {
    loadReservations()
  }
  
  // Eğer aktif tab favoriler ise favorileri yükle
  if (activeTab.value === 'favorites') {
    loadFavorites()
  }
})

// Route değişikliklerini izle (path ve query)
watch([() => route.path, () => route.query], () => {
  updateActiveTabFromRoute()
})

// Route'dan aktif tabı belirleme fonksiyonu
const updateActiveTabFromRoute = () => {
  // Önce URL path'e göre kontrol et
  if (route.path === '/profile/reservations') {
    activeTab.value = 'reservations'
  } else if (route.path === '/profile/favorites') {
    activeTab.value = 'favorites'
  } else if (route.path === '/profile') {
    // URL query'den tab parametresini kontrol et
    if (route.query.tab && ['reservations', 'profile', 'password', 'favorites'].includes(route.query.tab)) {
      activeTab.value = route.query.tab
    } else {
      // Hiçbir tab belirtilmemişse, props'tan veya varsayılan değeri kullan
      activeTab.value = props.activeTab || 'reservations'
    }
  }
  
  console.log('Active tab updated:', activeTab.value, 'Route:', route.path, route.query)
  
  // Geçerli tab için gerekli verileri yükle
  if (activeTab.value === 'reservations' && reservations.value.length === 0) {
    loadReservations()
  }
  
  if (activeTab.value === 'favorites' && favorites.value.length === 0) {
    loadFavorites()
  }
}

const showDatePicker = ref(false)
const currentDate = ref(new Date())
const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())

// Takvim için gerekli veri
const months = [
  'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 
  'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'
]
const weekdays = ['Pt', 'Sa', 'Ça', 'Pe', 'Cu', 'Ct', 'Pz']

// 100 yıl geriye, 1 yıl ileriye yıl seçenekleri
const yearOptions = computed(() => {
  const currentYearValue = new Date().getFullYear()
  const years = []
  for (let i = currentYearValue - 100; i <= currentYearValue; i++) {
    years.push(i)
  }
  return years.reverse()
})

// Takvim günlerini hesapla
const calendarDays = computed(() => {
  const days = []
  
  // Ayın ilk günü
  const firstDayOfMonth = new Date(currentYear.value, currentMonth.value, 1)
  // Ayın son günü
  const lastDayOfMonth = new Date(currentYear.value, currentMonth.value + 1, 0)
  
  // Ayın ilk gününün haftanın hangi günü olduğunu bul (0: Pazar, 1: Pazartesi, ...)
  let firstDayOfWeek = firstDayOfMonth.getDay()
  // Pazartesi 0 indeksi olacak şekilde ayarla
  firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1
  
  // Önceki ayın son günlerini ekle
  const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0).getDate()
  for (let i = firstDayOfWeek - 1; i >= 0; i--) {
    const date = new Date(currentYear.value, currentMonth.value - 1, prevMonthLastDay - i)
    days.push(date)
  }
  
  // Mevcut ayın günlerini ekle
  for (let i = 1; i <= lastDayOfMonth.getDate(); i++) {
    const date = new Date(currentYear.value, currentMonth.value, i)
    days.push(date)
  }
  
  // Sonraki ayın ilk günlerini ekle (6 satır toplam 42 gün olacak şekilde)
  const remainingDays = 42 - days.length
  for (let i = 1; i <= remainingDays; i++) {
    const date = new Date(currentYear.value, currentMonth.value + 1, i)
    days.push(date)
  }
  
  return days
})

// Tarih Seçici fonksiyonları
const openDatePicker = () => {
  showDatePicker.value = true
  
  // Eğer zaten bir tarih seçilmişse, takvimi o tarihe ayarla
  if (profileForm.value.birthdate) {
    const date = new Date(profileForm.value.birthdate)
    if (!isNaN(date.getTime())) {
      currentMonth.value = date.getMonth()
      currentYear.value = date.getFullYear()
    }
  }
  
  // Dışarı tıklama ile kapatma için event listener ekle
  document.addEventListener('click', handleClickOutside)
}

const closeDatePicker = () => {
  showDatePicker.value = false
  
  // Event listener'ı temizle
  document.removeEventListener('click', handleClickOutside)
}

const handleClickOutside = (event) => {
  // Takvim dışına tıklanırsa kapat
  const calendar = document.querySelector('.absolute.z-10')
  const input = document.getElementById('birthdate')
  
  if (calendar && input && !calendar.contains(event.target) && !input.contains(event.target)) {
    closeDatePicker()
  }
}

const formatBirthdate = (date) => {
  if (!date) return ''
  
  if (typeof date === 'string') {
    const dateObj = new Date(date)
    if (isNaN(dateObj.getTime())) return ''
    
    const day = String(dateObj.getDate()).padStart(2, '0')
    const month = String(dateObj.getMonth() + 1).padStart(2, '0')
    const year = dateObj.getFullYear()
    
    return `${day}/${month}/${year}`
  }
  
  return ''
}

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
}

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
}

const updateCalendar = () => {
  // Ay veya yıl değiştiğinde takvimi güncelle
  // (computed property otomatik güncellenir)
}

const selectDate = (day) => {
  // Seçilen tarihi YYYY-MM-DD formatında ayarla
  const year = day.getFullYear()
  const month = (day.getMonth() + 1).toString().padStart(2, '0')
  const date = day.getDate().toString().padStart(2, '0')
  
  profileForm.value.birthdate = `${year}-${month}-${date}`
  
  // Takvimi kapat
  closeDatePicker()
}

const clearDate = () => {
  profileForm.value.birthdate = ''
  closeDatePicker()
}

const getDayClasses = (day) => {
  const isCurrentMonth = day.getMonth() === currentMonth.value
  const isToday = isDateEqual(day, new Date())
  const isSelected = profileForm.value.birthdate && isDateEqual(day, new Date(profileForm.value.birthdate))
  
  const classes = []
  
  // Mevcut ay dışındaki günler için soluk görünüm
  if (!isCurrentMonth) {
    classes.push('text-gray-400 dark:text-gray-600')
  } else {
    classes.push('text-gray-800 dark:text-gray-200')
  }
  
  // Bugün için vurgu
  if (isToday) {
    classes.push('border border-gray-300 dark:border-gray-500')
  }
  
  // Seçili gün için vurgu
  if (isSelected) {
    classes.push('bg-primary-500 text-white dark:bg-primary-600')
  } else {
    classes.push('hover:bg-gray-100 dark:hover:bg-gray-700')
  }
  
  return classes.join(' ')
}

const isDateEqual = (date1, date2) => {
  return date1.getDate() === date2.getDate() && 
         date1.getMonth() === date2.getMonth() && 
         date1.getFullYear() === date2.getFullYear()
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style> 