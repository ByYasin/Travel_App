<template>
  <div class="bg-white dark:bg-gray-900 min-h-screen">
    <!-- Yeni tasarım: Sade tur başlığı ve detayları -->
    <div class="container mx-auto px-4 py-8">
      <div v-if="loading" class="flex justify-center items-center h-96">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
      </div>
      
      <div v-else-if="error" class="text-center py-16">
        <div class="text-red-500 text-xl">{{ error }}</div>
        <button @click="fetchTour" class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
          Yeniden Dene
        </button>
      </div>
      
      <div v-else class="space-y-8">
        <!-- Tur Başlığı ve Temel Bilgiler -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex flex-col md:flex-row md:items-start gap-6">
            <!-- Tur Görseli - Daha profesyonel görünüm -->
            <div class="md:w-1/3 rounded-lg overflow-hidden h-[350px]">
              <img :src="tour.featured_image || '/images/tours/default.jpg'" 
                  :alt="tour.title" 
                  class="w-full h-full object-cover">
            </div>
            
            <!-- Tur Detayları - Sadeleştirilmiş tasarım -->
            <div class="md:w-2/3">
              <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">{{ tour.title }}</h1>
              
              <div class="flex items-center gap-6 mb-5 flex-wrap">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                  </svg>
                  <span class="ml-1 text-gray-700 dark:text-gray-300">{{ averageRating }} ({{ reviewCount }} değerlendirme)</span>
                </div>
                
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 text-gray-700 dark:text-gray-300">{{ tour.duration || '3 Gün' }}</span>
                </div>
                
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                  </svg>
                  <span class="ml-1 text-gray-700 dark:text-gray-300">{{ tour.location || 'Kapadokya, Türkiye' }}</span>
                </div>
              </div>
              
              <!-- Fiyat ve Rezervasyon - Profesyonel vurgu -->
              <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-5 flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                <div>
                  <div class="font-medium text-gray-500 dark:text-gray-400 text-sm">Kişi Başı Fiyat</div>
                  <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ formatPrice(tour.price) }}</div>
                </div>
                
                <div class="flex gap-2">
                  <!-- Favori butonu -->
                  <form 
                    @submit.prevent="toggleFavorite" 
                    class="ml-2"
                  >
                    <input type="hidden" name="_token" :value="csrfToken">
                    <input type="hidden" name="tour_id" :value="tour.id">
                    <button 
                      type="submit"
                      class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                      :class="[isFavorited ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:border-red-800 dark:hover:bg-red-900/30' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700']"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" :fill="isFavorited ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                      {{ isFavorited ? 'Favorilerde' : 'Favorilere Ekle' }}
                    </button>
                  </form>
                  
                <button @click="scrollToBooking" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition duration-300 w-full sm:w-auto text-center">
                  Rezervasyon Yap
                </button>
              </div>
              
              <!-- Tur Açıklaması -->
              <div>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ tour.shortDescription || "Kapadokya'nın büyüleyici peribacaları, sıcak hava balonları ve eşsiz doğasını keşfedin. Kapadokya, doğanın ve kültürün harmanlandığı eşsiz bir destinasyondur." }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
        
        <!-- Tur Detayları ve Rezervasyon -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Tur İçeriği -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Tur Hakkında -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
              <h2 class="text-2xl font-bold mb-5 text-gray-800 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tur Hakkında
              </h2>
              <div class="prose dark:prose-invert max-w-none prose-lg">
                <p v-if="tour.description">{{ tour.description }}</p>
                <p v-else>
                  Kapadokya'nın büyüleyici peribacaları, sıcak hava balonları ve eşsiz doğasını keşfedeceğiniz bu turda, bölgenin en önemli doğal ve tarihi yerlerini rehberlerimiz eşliğinde ziyaret edeceksiniz. Göreme Açık Hava Müzesi, Uçhisar Kalesi, Derinkuyu Yeraltı Şehri ve muhteşem vadiler ilk durağımız olacak.
                </p>
                <p>
                  Kapadokya'nın muhteşem gün doğumu manzarası eşliğinde yapacağınız balon turu (opsiyonel) ile bölgeyi bir de kuş bakışı izleyebilirsiniz. Doğal oluşumların yanı sıra, bölgenin zengin gastronomisini de keşfedecek, yerel lezzetleri tadacaksınız.
                </p>
                <h3 class="flex items-center font-bold">
                  <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Bu Turda Ne Yapacaksınız?
                </h3>
                <ul>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Göreme Açık Hava Müzesi'nde fresklerle süslü kaya kiliselerini keşfedin</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Üç Güzeller ve Paşabağı'ndaki peri bacalarını görün</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Yerel bir çanak-çömlek atölyesinde çömlek yapımını öğrenin</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Uçhisar Kalesi'nden panoramik Kapadokya manzarasını izleyin</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Derinkuyu veya Kaymaklı Yeraltı Şehirlerini ziyaret edin</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Güvercinlik Vadisi'nde güzel bir yürüyüş yapın</span>
                  </li>
                  <li class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Ortahisar'da yerel bir restoranda Kapadokya lezzetlerini tadın</span>
                  </li>
                </ul>
              </div>
            </div>
      
            <!-- Dahil Olanlar ve Olmayanlar -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Dahil Olanlar -->
              <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                  </svg>
                  Tura Dahil Olanlar
                </h3>
                <ul class="space-y-3">
                  <li v-for="(item, index) in parsedIncluded" :key="'included-'+index" class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ item }}</span>
                  </li>
                  <li v-if="!parsedIncluded.length" class="text-gray-500 dark:text-gray-400 italic">
                    Dahil olanlar belirtilmemiş
                  </li>
                </ul>
              </div>
              
              <!-- Dahil Olmayanlar -->
              <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white flex items-center">
                  <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                  </svg>
                  Tura Dahil Olmayanlar
                </h3>
                <ul class="space-y-3">
                  <li v-for="(item, index) in parsedNotIncluded" :key="'not-included-'+index" class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">{{ item }}</span>
                  </li>
                  <li v-if="!parsedNotIncluded.length" class="text-gray-500 dark:text-gray-400 italic">
                    Dahil olmayanlar belirtilmemiş
                  </li>
                </ul>
              </div>
            </div>
            
            <!-- Değerlendirmeler -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="reviews">
              <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                Tur Değerlendirmeleri
              </h2>
              
              <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                  <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                  </svg>
                  <span class="ml-1 text-lg font-bold text-gray-900 dark:text-white">{{ reviewStats.averageRating }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-gray-600 dark:text-gray-400">({{ reviewStats.count }} değerlendirme)</span>
                </div>
              </div>
              
              <!-- Yorum Ekleme Formu -->
              <div class="mb-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                  </svg>
                  {{ userHasReview ? 'Değerlendirmenizi Düzenleyin' : 'Değerlendirmenizi Yazın' }}
                </h3>
                
                <div class="space-y-4">
                  <div class="flex items-center gap-2 mb-4">
                    <template v-for="star in 5" :key="star">
                      <button @click="reviewData.rating = star" 
                              class="focus:outline-none"
                              :class="{'text-yellow-400': star <= reviewData.rating, 'text-gray-300 dark:text-gray-600': star > reviewData.rating}">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                        </svg>
                      </button>
                    </template>
                    <span class="ml-2 text-gray-600 dark:text-gray-400">
                      {{ reviewData.rating }} / 5
                    </span>
                  </div>
                  <div class="relative">
                    <textarea v-model="reviewData.comment" 
                              rows="4" 
                              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Deneyiminizi paylaşın..."></textarea>
                    <button @click="showEmojiPicker = !showEmojiPicker"
                            class="absolute right-2 bottom-2 p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                    <!-- Emoji Picker -->
                    <div v-if="showEmojiPicker" 
                         class="absolute bottom-full right-0 mb-2 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50">
                      <div class="mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                        <div class="flex gap-2 mb-2">
                          <button v-for="category in emojiCategories" 
                                  :key="category.name"
                                  @click="currentEmojiCategory = category.name"
                                  class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                                  :class="{'bg-gray-100 dark:bg-gray-700': currentEmojiCategory === category.name}">
                            {{ category.icon }}
                          </button>
                        </div>
                      </div>
                      <div class="grid grid-cols-8 gap-2 max-h-[200px] overflow-y-auto">
                        <button v-for="emoji in currentEmojis" 
                                :key="emoji"
                                @click="addEmoji(emoji); showEmojiPicker = false"
                                class="text-2xl hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded">
                          {{ emoji }}
                        </button>
                      </div>
                    </div>
                  </div>
                  <button @click="submitReview" 
                          class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    {{ userHasReview ? 'Değerlendirmeyi Güncelle' : 'Değerlendirme Gönder' }}
                  </button>
                </div>
              </div>
              
              <!-- Mevcut Yorumlar -->
              <div class="space-y-6">
                <div v-if="reviews.length > 0">
                  <div v-for="(review, index) in reviews" :key="review.id" 
                       class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-4">
                    <div class="flex items-start gap-4">
                      <!-- Kullanıcı Avatarı -->
                      <div class="h-10 w-10 rounded-full bg-blue-600 flex items-center justify-center text-white overflow-hidden">
                        <span class="text-sm font-medium">{{ getUserInitials(review.user.name) }}</span>
                      </div>
                      
                      <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                          <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ review.user.name }}</h4>
                            <div class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                              <span>{{ formatDate(review.created_at) }}</span>
                              <span>•</span>
                              <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                </svg>
                                <span class="ml-1">{{ review.rating }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="flex items-center">
                            <button @click="likeReview(review)" 
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium transition-all duration-200 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                                    :class="{
                                      'text-blue-600 dark:text-blue-400': review.userLiked,
                                      'text-gray-600 dark:text-gray-400': !review.userLiked
                                    }">
                              <svg class="w-5 h-5" :fill="review.userLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.405a17.9 17.9 0 01-.87 1.897L7 11v9" />
                              </svg>
                              <span>{{ review.userLiked ? 'Beğenildi' : 'Beğen' }}</span>
                              <span v-if="review.likes_count > 0" class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ review.likes_count }})</span>
                            </button>
                          </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300">{{ review.comment }}</p>
                        
                        <!-- Yorum Cevaplar -->
                        <div v-if="review.replies && review.replies.length > 0" class="mt-4 space-y-4 pl-6 border-l-2 border-gray-200 dark:border-gray-700">
                          <div v-for="(reply, replyIndex) in review.replies" :key="reply.id" class="pt-4">
                            <div class="flex items-start gap-3">
                              <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white overflow-hidden">
                                <span class="text-xs font-medium">{{ getUserInitials(reply.user.name) }}</span>
                              </div>
                              <div class="flex-1">
                                <div class="flex items-center justify-between">
                                  <div class="flex items-center gap-1">
                                    <h5 class="font-medium text-gray-900 dark:text-white text-sm">{{ reply.user.name }}</h5>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(reply.created_at) }}</span>
                                  </div>
                                  <button @click="likeReply(reply)"
                                          class="flex items-center gap-1 px-2 py-1 text-xs font-medium transition-all duration-200 rounded hover:bg-gray-100 dark:hover:bg-gray-800"
                                          :class="{
                                            'text-blue-600 dark:text-blue-400': reply.userLiked,
                                            'text-gray-600 dark:text-gray-400': !reply.userLiked
                                          }">
                                    <svg class="w-4 h-4" :fill="reply.userLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.405a17.9 17.9 0 01-.87 1.897L7 11v9" />
                                    </svg>
                                    <span>{{ reply.userLiked ? 'Beğenildi' : 'Beğen' }}</span>
                                    <span v-if="reply.likes_count > 0" class="ml-1 text-xs text-gray-500 dark:text-gray-400">({{ reply.likes_count }})</span>
                                  </button>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">{{ reply.content }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Cevap Butonu ve Form -->
                        <div class="mt-3">
                          <button @click="review.showReplyForm = !review.showReplyForm" 
                                  class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">
                            {{ review.showReplyForm ? 'İptal' : 'Cevapla' }}
                          </button>
                          <div v-if="review.showReplyForm" class="mt-3">
                            <div class="relative">
                              <textarea v-model="review.replyContent" 
                                        rows="2" 
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Bir cevap yazın..."></textarea>
                              <button @click="submitReply(review)" 
                                      class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-4 rounded-lg text-sm transition-colors">
                                Cevap Gönder
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-8 text-gray-500 dark:text-gray-400">
                  Henüz değerlendirme yok. İlk değerlendirmeyi siz yapın!
                </div>
              </div>
              
              <!-- Daha Fazla Yükle -->
              <div v-if="hasMoreReviews" class="mt-8 text-center">
                <button @click="loadMoreReviews" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                  Daha Fazla Değerlendirme Gör
                </button>
              </div>
            </div>
          </div>
          
          <!-- Rezervasyon Formu -->
          <div id="booking" class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-24">
              <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Rezervasyon
              </h3>
              
              <!-- Tarih ve Kişi Sayısı -->
              <div class="space-y-4 mb-6">
                <div>
                  <label class="flex text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Tarih Seçin
                  </label>
                  <input v-model="bookingDate" type="date" 
                         class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                  <label class="flex text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 items-center">
                    <svg class="w-4 h-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Kişi Sayısı
                  </label>
                  <select v-model="participants" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option v-for="n in 10" :key="n" :value="n">{{ n }} Kişi</option>
                  </select>
                </div>
              </div>
              
              <!-- Fiyat Hesaplama -->
              <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-600 dark:text-gray-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                    </svg>
                    Kişi Başı
                  </span>
                  <span class="text-gray-900 dark:text-white font-medium">₺{{ tour.price }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                  <span class="text-gray-600 dark:text-gray-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kişi Sayısı
                  </span>
                  <span class="text-gray-900 dark:text-white font-medium">{{ participants }} Kişi</span>
                </div>
                <div class="flex justify-between items-center text-lg font-bold border-t border-gray-200 dark:border-gray-600 pt-4">
                  <span class="text-gray-900 dark:text-white flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Toplam
                  </span>
                  <span class="text-blue-600 dark:text-blue-400">₺{{ calculateTotal() }}</span>
                </div>
              </div>
              
              <!-- Rezervasyon Butonu -->
              <button @click="bookNow" 
                      class="w-full py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Hemen Rezervasyon Yap</span>
              </button>
              
              <!-- Yardım Bilgisi -->
              <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
                <div class="mb-2 flex items-center justify-center">
                  <svg class="w-4 h-4 mr-1 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Sorularınız mı var?</span>
                </div>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                  Bize ulaşın
                </a> 
                veya 
                <a href="/sss" class="text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  SSS
                </a> 
                sayfamızı ziyaret edin.
              </div>
            </div>
            
            <!-- Tur Bilgileri Özeti -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
              <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tur Özeti
              </h3>
              <ul class="space-y-3">
                <li class="flex items-center gap-3">
                  <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Süre</div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ tour.duration || '3 Gün' }}</div>
                  </div>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Grup Boyutu</div>
                    <div class="font-medium text-gray-900 dark:text-white">Max {{ tour.max_participants || '15' }} Kişi</div>
                  </div>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Konaklama</div>
                    <div class="font-medium text-gray-900 dark:text-white">3-4 Yıldızlı Otel</div>
                  </div>
                </li>
                <li class="flex items-center gap-3">
                  <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Rehber</div>
                    <div class="font-medium text-gray-900 dark:text-white">Profesyonel Türkçe Rehber</div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useCartStore } from '@/store/cart';
import { useAuthStore } from '@/store/auth';
import { useToast } from 'vue-toastification';
import axios from 'axios';
import favoriteService from '@/services/favoriteService';
import apiService from '@/services/apiService';

const route = useRoute();
const router = useRouter();
const cartStore = useCartStore();
const authStore = useAuthStore();
const toast = useToast();
const tour = ref({});
const loading = ref(true);
const error = ref(null);
const isFavorited = ref(false);

// API ile ilgili değişkenler
const reviews = ref([]);
const reviewStats = ref({
  averageRating: 0,
  count: 0
});
const hasMoreReviews = ref(false);
const reviewData = ref({
  rating: 5,
  comment: ''
});
const showEmojiPicker = ref(false);
const currentEmojiCategory = ref('smileys');
const participants = ref(2); // Varsayılan kişi sayısı
const bookingDate = ref(new Date().toISOString().substr(0, 10)); // Bugünün tarihi
const userHasReview = ref(false); // Kullanıcının değerlendirmesi var mı?
const userReview = ref(null); // Kullanıcının mevcut değerlendirmesi

// Ortalama puan hesaplama
const averageRating = computed(() => {
  return reviewStats.value.averageRating || tour.value.average_rating || '0';
});

// Değerlendirme sayısı
const reviewCount = computed(() => {
  return reviewStats.value.count || tour.value.review_count || '0';
});

// JSON verilerini array'e dönüştürme
const parsedIncluded = computed(() => {
  try {
    if (tour.value.included && typeof tour.value.included === 'string') {
      return JSON.parse(tour.value.included);
    } else if (Array.isArray(tour.value.included)) {
      return tour.value.included;
    } else if (tour.value.includes && typeof tour.value.includes === 'string') {
      return JSON.parse(tour.value.includes);
    } else if (Array.isArray(tour.value.includes)) {
      return tour.value.includes;
    }
    return [];
  } catch (error) {
    console.error('Dahil olanlar ayrıştırılamadı:', error);
    return [];
  }
});

// JSON verilerini array'e dönüştürme
const parsedNotIncluded = computed(() => {
  try {
    if (tour.value.not_included && typeof tour.value.not_included === 'string') {
      return JSON.parse(tour.value.not_included);
    } else if (Array.isArray(tour.value.not_included)) {
      return tour.value.not_included;
    } else if (tour.value.excludes && typeof tour.value.excludes === 'string') {
      return JSON.parse(tour.value.excludes);
    } else if (Array.isArray(tour.value.excludes)) {
      return tour.value.excludes;
    }
    return [];
  } catch (error) {
    console.error('Dahil olmayanlar ayrıştırılamadı:', error);
    return [];
  }
});

// Fiyat formatlaması
const formatPrice = (price) => {
  if (!price) return '0,00 ₺';
  
  const priceValue = parseFloat(price.toString().replace(/[^\d.-]/g, ''));
  return priceValue.toLocaleString('tr-TR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }) + ' ₺';
};

// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Favori durumunu kontrol et
const checkFavoriteStatus = async () => {
  try {
    if (!authStore.isLoggedIn || !tour.value?.id) {
      isFavorited.value = false;
      return;
    }
    
    // AJAX yerine direkt form ile kontrol etmek için HTML formdan gelen yanıtı kullanacağız
    // Bu fonksiyon artık sayfa yüklendiğinde tur detayındaki isFavorited değerini kullanacak
    // tour.value objesi backend'den gelirken içinde is_favorited özelliği olacak
    if (tour.value.is_favorited !== undefined) {
      isFavorited.value = tour.value.is_favorited === true;
    } else {
      // Backend'den is_favorited gelmiyorsa, varsayılan olarak false kabul edelim
      isFavorited.value = false;
    }
  } catch (error) {
    console.error('Favori durumu kontrol edilirken hata oluştu:', error);
    isFavorited.value = false;
  }
};

// Favoriye ekle/çıkar
const toggleFavorite = async () => {
  try {
    // Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
    if (!authStore.isLoggedIn) {
      toast.info('Favorilere eklemek için giriş yapmalısınız');
      router.push(`/login?redirect=/tour/${tour.value.id}`);
      return;
    }
    
    // UI'ı hemen güncelle (optimistic update)
    const currentStatus = isFavorited.value;
    isFavorited.value = !currentStatus;
    
    // Form verisi oluştur
    const formData = new FormData();
    formData.append('_token', csrfToken);
    
    // AJAX isteği gönder (fetch API kullanarak)
    const response = await fetch(`/favorites/toggle/${tour.value.id}`, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin'
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    
    if (data.success) {
      // Sunucudan gelen gerçek durumu güncelle
      isFavorited.value = data.is_favorited;
      
      toast.success(data.message);
    } else {
      // Hata durumunda UI'ı eski haline çevir
      isFavorited.value = currentStatus;
      toast.error(data.message || 'Favori işlemi sırasında bir hata oluştu');
    }
  } catch (error) {
    console.error('Favori işlemi sırasında hata oluştu:', error);
    
    // Hata durumunda UI'ı eski haline çevir
    isFavorited.value = !isFavorited.value;
    
    toast.error('Favori işlemi sırasında bir hata oluştu');
  }
};

// Tur verilerini getir
const fetchTour = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    const tourId = route.params.id;
    console.log('Tur ID:', tourId);
    
    // API endpoint'i değişken olarak tutup önce bunu deneyeceğiz
    const mainEndpoint = `/api/tours/${tourId}`;
    let response;
    
    try {
      response = await axios.get(mainEndpoint);
      console.log('API yanıtı başarılı:', response.data);
    } catch (endpointError) {
      console.warn('Ana endpoint başarısız, alternatif endpoint deneniyor:', endpointError);
      // Alternatif endpoint dene (trailing slash olmadan veya farklı bir path)
      const altEndpoint = `/api/tour/${tourId}`;
      response = await axios.get(altEndpoint);
      console.log('Alternatif API yanıtı başarılı:', response.data);
    }
    
    // Başarılı yanıt durumunda veriyi çek
    if (response && response.data) {
      if (response.data.data) {
        // API'den dönen veri { data: {...} } formatında ise
        tour.value = response.data.data;
        console.log('Tur verisi (data içinden):', tour.value);
      } else {
        // API'den dönen veri doğrudan obje ise
        tour.value = response.data;
        console.log('Tur verisi (doğrudan):', tour.value);
      }
      
      // Test amaçlı: API yanıtında veri yoksa veya eksikse, dummy veri kullan
      if (!tour.value || !tour.value.id) {
        // Route'dan gelen ID ile minimum bir tur objesi oluştur
        console.warn('API verisi eksik, dummy veri oluşturuluyor');
        tour.value = {
          id: tourId,
          title: tour.value?.title || 'Kapadokya Turu',
          price: tour.value?.price || 1500,
          featured_image: tour.value?.featured_image || '/images/tours/default.jpg',
          location: tour.value?.location || 'Kapadokya, Türkiye',
          duration: tour.value?.duration || '3 Gün',
          slug: tour.value?.slug || `tour-${tourId}`
        };
      }
      
      // Değerlendirme istatistiklerini hazırla
      reviewStats.value = {
        averageRating: tour.value.average_rating || 4.5,
        count: tour.value.review_count || 12
      };
      
      // API üzerinden değerlendirmeleri getir
      fetchReviews();
      
      // Tour verisi yüklendikten sonra favori durumunu kontrol et
      if (authStore.isLoggedIn) {
        await checkFavoriteStatus();
      }
    } else {
      throw new Error('API yanıtı boş veya geçersiz');
    }
  } catch (err) {
    console.error('Tur verileri alınamadı:', err);
    if (err.response) {
      console.error('API hata yanıtı:', err.response.data);
    }
    
    // Hata durumunda kullanıcıya bilgi ver, ancak yine de dummy veri göster 
    error.value = 'Tur bilgileri tam olarak yüklenemedi, örnek içerik gösteriliyor.';
    
    // Dummy tur verisi oluştur
    const tourId = route.params.id;
    tour.value = {
      id: tourId,
      title: 'Kapadokya Turu',
      price: 1500,
      featured_image: '/images/tours/default.jpg',
      location: 'Kapadokya, Türkiye',
      duration: '3 Gün',
      slug: `tour-${tourId}`
    };
    
    // Dummy değerlendirme istatistiklerini hazırla
    reviewStats.value = {
      averageRating: 4.5,
      count: 12
    };
  } finally {
    loading.value = false;
  }
};

// Değerlendirmeleri getir
const fetchReviews = async () => {
  try {
    const tourId = route.params.id;
    const response = await apiService.get(`/api/tours/${tourId}/reviews`);
    
    if (response.data && response.data.reviews) {
      reviews.value = response.data.reviews.map(review => ({
        ...review,
        showReplyForm: false,
        replyContent: ''
      }));
      
      reviewStats.value = {
        averageRating: response.data.average_rating,
        count: response.data.review_count
      };
      
      hasMoreReviews.value = reviews.value.length < reviewStats.value.count;

      // Kullanıcının değerlendirmesini kontrol et
      if (authStore.isLoggedIn && authStore.user) {
        const userReviewFound = reviews.value.find(r => r.user_id === authStore.user.id);
        if (userReviewFound) {
          userHasReview.value = true;
          userReview.value = userReviewFound;
          
          // Değerlendirme formunu önceki değerleriyle doldur
          reviewData.value.rating = userReviewFound.rating;
          reviewData.value.comment = userReviewFound.comment;
        } else {
          userHasReview.value = false;
          userReview.value = null;
        }
      }
    } else {
      console.error('API yanıtı beklenmeyen formatta:', response);
      toast.error('Değerlendirmeler yüklenirken bir sorun oluştu.');
    }
  } catch (err) {
    console.error('Değerlendirmeler alınamadı:', err);
    toast.error('Değerlendirmeler yüklenirken bir sorun oluştu. Lütfen sayfayı yenileyin.');
  }
};

// Değerlendirme gönder
const submitReview = async () => {
  if (!reviewData.value.comment || reviewData.value.rating === 0) {
    toast.error('Lütfen bir değerlendirme yazısı ve puan girin.');
    return;
  }
  
  try {
    const tourId = route.params.id;
    let response;
    
    if (userHasReview.value && userReview.value) {
      // Mevcut değerlendirmeyi güncelle
      response = await apiService.put(`/api/reviews/${userReview.value.id}`, {
        rating: reviewData.value.rating,
        comment: reviewData.value.comment
      });
      
      // Değerlendirmeyi listede güncelle
      const index = reviews.value.findIndex(r => r.id === userReview.value.id);
      if (index !== -1) {
        reviews.value[index] = {
          ...reviews.value[index],
          rating: reviewData.value.rating,
          comment: reviewData.value.comment
        };
      }
      
      toast.success('Değerlendirmeniz başarıyla güncellendi!');
    } else {
      // Yeni değerlendirme oluştur
      response = await apiService.post(`/api/tours/${tourId}/reviews`, {
        rating: reviewData.value.rating,
        comment: reviewData.value.comment
      });
      
      // Yeni değerlendirmeyi en üste ekle
      reviews.value.unshift({
        ...response.data,
        showReplyForm: false,
        replyContent: '',
        userLiked: false,
        replies: []
      });
      
      // Kullanıcının artık bir değerlendirmesi olduğunu işaretle
      userHasReview.value = true;
      userReview.value = response.data;
      
      // İstatistikleri güncelle
      reviewStats.value.count++;
      reviewStats.value.averageRating = (
        (reviewStats.value.averageRating * (reviewStats.value.count - 1) + reviewData.value.rating) / 
        reviewStats.value.count
      ).toFixed(1);
      
      toast.success('Değerlendirmeniz başarıyla gönderildi!');
    }
  } catch (err) {
    console.error('Değerlendirme gönderilemedi:', err);
    if (err.response?.data?.message) {
      toast.error(err.response.data.message);
    } else {
      toast.error('Değerlendirme gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
    }
  }
};

// Değerlendirmeyi beğen/beğenme
const likeReview = async (review) => {
  if (!authStore.isLoggedIn) {
    toast.warning('Beğenmek için giriş yapmalısınız!');
    return;
  }
  
  try {
    const response = await apiService.post(`/api/reviews/${review.id}/like`);
    
    const { userLiked, likesCount } = response.data;
    
    // Yerel değişkenleri güncelle
    review.userLiked = userLiked;
    review.likes_count = likesCount;
    
    toast.success(userLiked ? 'Değerlendirme beğenildi!' : 'Beğeni kaldırıldı!');
  } catch (err) {
    console.error('Beğeni işlemi yapılamadı:', err);
    toast.error('Beğeni işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
  }
};

// Yanıtı beğen/beğenme
const likeReply = async (reply) => {
  try {
    const response = await apiService.post(`/api/replies/${reply.id}/like`);
    const { userLiked, likesCount } = response.data;
    
    // Yerel değişkenleri güncelle
    reply.userLiked = userLiked;
    reply.likes_count = likesCount;
    
    toast.success(userLiked ? 'Yanıt beğenildi!' : 'Beğeni kaldırıldı!');
  } catch (err) {
    console.error('Yanıt beğeni işlemi yapılamadı:', err);
    toast.error('Beğeni işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
  }
};

// Değerlendirmeye yanıt gönder
const submitReply = async (review) => {
  if (!review.replyContent.trim()) {
    toast.error('Lütfen bir yanıt yazın.');
    return;
  }
  
  try {
    const response = await apiService.post(`/api/reviews/${review.id}/reply`, {
      content: review.replyContent
    });
    
    // Yeni yanıtı listeye ekle
    if (!review.replies) {
      review.replies = [];
    }
    
    review.replies.push({
      ...response.data,
      userLiked: false
    });
    
    // Yanıt formunu temizle ve kapat
    review.replyContent = '';
    review.showReplyForm = false;
    
    toast.success('Yanıtınız başarıyla gönderildi!');
  } catch (err) {
    console.error('Yanıt gönderilemedi:', err);
    toast.error('Yanıt gönderilirken bir hata oluştu. Lütfen tekrar deneyin.');
  }
};

// Daha fazla değerlendirme yükle
const loadMoreReviews = () => {
  // Gerçek bir uygulamada burada sayfalama yapılacaktır
  // Şu an için sadece bir uyarı gösteriyoruz
  alert('Bu fonksiyon henüz tam olarak uygulanmamıştır. Gelecek güncellemelerde eklenecektir.');
};

// Emoji ekleme
const addEmoji = (emoji) => {
  reviewData.value.comment += emoji;
};

// Tarih formatı
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  const diffInMs = now - date;
  const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
  
  if (diffInDays === 0) {
    return 'Bugün';
  } else if (diffInDays === 1) {
    return 'Dün';
  } else if (diffInDays < 7) {
    return `${diffInDays} gün önce`;
  } else if (diffInDays < 30) {
    const weeks = Math.floor(diffInDays / 7);
    return `${weeks} hafta önce`;
  } else if (diffInDays < 365) {
    const months = Math.floor(diffInDays / 30);
    return `${months} ay önce`;
  } else {
    const years = Math.floor(diffInDays / 365);
    return `${years} yıl önce`;
  }
};

// Toplam fiyat hesaplama
const calculateTotal = () => {
  if (!tour.value || !tour.value.price) return '0,00';
  const priceString = tour.value.price.toString();
  const priceValue = parseFloat(priceString.replace(/[^\d.-]/g, ''));
  const totalPrice = (priceValue * participants.value).toFixed(2);
  return totalPrice.replace('.', ',');
};

// Rezervasyon yapma
const bookNow = () => {
  if (!bookingDate.value) {
    alert('Lütfen tarih seçin.');
    return;
  }
  
  // Tur verilerini kontrol et
  if (!tour.value || !tour.value.id) {
    console.error('Geçersiz tur bilgisi:', tour.value);
    alert('Tur bilgileri eksik. Sayfayı yenileyip tekrar deneyin.');
    return;
  }
  
  // Debug: Sepete eklenecek tur bilgilerini kontrol et
  console.log('Sepete eklenecek tur:', tour.value);
  
  // Gerekli tüm tur bilgilerinin eksiksiz olduğundan emin ol
  const tourForCart = {
    id: tour.value.id,
    title: tour.value.title || 'İsimsiz Tur',
    price: tour.value.price || 0,
    featured_image: tour.value.featured_image || '/images/tours/default.jpg',
    slug: tour.value.slug || `tour-${tour.value.id}`
  };
  
  try {
    // Sepete ekle
    const result = cartStore.addToCart(tourForCart, participants.value, bookingDate.value);
    
    // Not: Artık sepete eklendikten sonra event yoluyla drawer otomatik açılacak
    // Event cart.js dosyasında tetikleniyor
  } catch (error) {
    console.error('Sepete ekleme hatası:', error);
    alert('Sepete eklerken bir hata oluştu. Lütfen tekrar deneyin.');
  }
};

// Kullanıcı baş harflerini getir
const getUserInitials = (name) => {
  if (!name) return '?';
  
  const nameParts = name.split(' ');
  if (nameParts.length === 1) {
    return nameParts[0].charAt(0).toUpperCase();
  }
  
  return (nameParts[0].charAt(0) + nameParts[nameParts.length - 1].charAt(0)).toUpperCase();
};

// Rezervasyon bölümüne kaydır
const scrollToBooking = () => {
  const bookingSection = document.querySelector('#booking');
  if (bookingSection) {
    bookingSection.scrollIntoView({ behavior: 'smooth' });
  }
};

// Emoji kategorileri
const emojiCategories = [
  { name: 'smileys', icon: '😊', emojis: ['😊', '😂', '😍', '🥰', '😎', '🤔', '😅', '😇', '🥳', '😋', '🤗', '😉', '😌', '😏', '😴', '🤯'] },
  { name: 'gestures', icon: '👋', emojis: ['👋', '👍', '👎', '👏', '🙌', '🤝', '👊', '✌️', '🤘', '🤙', '👌', '🤌', '🤏', '👈', '👉', '🫵'] },
  { name: 'hearts', icon: '❤️', emojis: ['❤️', '🧡', '💛', '💚', '💙', '💜', '🤎', '🖤', '🤍', '💖', '💗', '💓', '💝', '💘', '💕', '💞'] },
  { name: 'nature', icon: '🌟', emojis: ['🌟', '⭐', '✨', '💫', '☀️', '🌙', '🌺', '🌸', '🌼', '🌻', '🌹', '🍀', '🌈', '🌊', '🔥', '⚡'] },
  { name: 'food', icon: '🍕', emojis: ['🍕', '🍔', '🍟', '🌭', '🍿', '🧂', '🥨', '🥐', '🥖', '🥪', '🥗', '🥙', '🥚', '🍳', '🧇', '🥞'] },
  { name: 'activities', icon: '⚽', emojis: ['⚽', '🏀', '🏈', '⚾', '🎾', '🏐', '🏉', '🎱', '🏓', '🏸', '🏒', '🏑', '🥍', '🏏', '🪃', '⛳'] }
];

const currentEmojis = computed(() => {
  const category = emojiCategories.find(c => c.name === currentEmojiCategory.value);
  return category ? category.emojis : [];
});

// Sayfa yüklendiğinde verileri getir
onMounted(async () => {
  fetchTour();
  
  // Favori durumunu kontrol et
  if (authStore.isLoggedIn && tour.value?.id) {
    await checkFavoriteStatus();
  }
});

// Kullanıcı oturumu değiştiğinde favori durumunu güncelle
watch(() => authStore.isLoggedIn, async (isLoggedIn) => {
  if (isLoggedIn && tour.value?.id) {
    await checkFavoriteStatus();
  } else {
    isFavorited.value = false;
  }
});
</script>

<style scoped>
.shadow-top {
  box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1), 0 -2px 4px -1px rgba(0, 0, 0, 0.06);
}

.dark .shadow-top {
  box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.2), 0 -2px 4px -1px rgba(0, 0, 0, 0.1);
}

/* Animasyonlar */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Sticky header */
.sticky-header {
  position: sticky;
  top: 0;
  z-index: 10;
  backdrop-filter: blur(8px);
}

/* Tur görseli için hover efekti */
.overflow-hidden img {
  transition: transform 0.5s ease;
}

.overflow-hidden:hover img {
  transform: scale(1.05);
}
</style> 