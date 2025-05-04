<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Navbar altında 1 satır boşluk -->
    <div class="h-4"></div>

    <!-- Sayfa İçeriği -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Dekoratif arka plan -->
      <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-50/30 via-primary-100/20 to-primary-50/30 dark:from-gray-900 dark:via-primary-900/10 dark:to-primary-950/20"></div>
        
        <!-- Nokta Deseni -->
        <div class="absolute inset-0 opacity-30 dark:opacity-15">
          <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" preserveAspectRatio="xMidYMid slice">
            <defs>
              <pattern id="contactDots" width="30" height="30" patternUnits="userSpaceOnUse">
                <circle cx="15" cy="15" r="1" fill="#0284c7" fill-opacity="0.3" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#contactDots)" />
          </svg>
        </div>
        
        <!-- Dekoratif Şekiller -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary-300/20 to-primary-400/20 dark:from-primary-600/10 dark:to-primary-700/10 rounded-full filter blur-3xl transform translate-x-1/4 -translate-y-1/4"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-primary-300/20 to-primary-400/20 dark:from-primary-600/10 dark:to-primary-700/10 rounded-full filter blur-3xl transform -translate-x-1/4 translate-y-1/4"></div>
        
        <!-- Orta Desen -->
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] opacity-20 dark:opacity-10">
          <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <path fill="#0284c7" d="M44.8,-76.9C58.9,-68.2,71.9,-58,79.1,-44.2C86.2,-30.4,87.6,-13,87.2,4.3C86.8,21.7,84.7,38.9,75.8,51.8C66.9,64.7,51.4,73.2,35.9,77.9C20.3,82.5,4.8,83.2,-10.5,80.5C-25.8,77.7,-40.9,71.5,-53.6,61.5C-66.3,51.5,-76.6,37.6,-82.5,21.5C-88.4,5.4,-89.9,-12.9,-84.5,-28.8C-79.1,-44.6,-66.9,-58.1,-52.1,-66.7C-37.3,-75.3,-20.5,-79,-2.7,-74.6C15.1,-70.3,30.7,-58,44.8,-76.9Z" transform="translate(100 100) scale(1.1)" />
          </svg>
        </div>
      </div>

      <!-- Hero Section -->
      <div class="relative pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="text-center max-w-3xl mx-auto">
            <div class="mb-6 inline-flex items-center justify-center p-3 bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/30 dark:to-primary-800/30 rounded-full shadow-sm">
              <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-400 dark:to-primary-500">
              Bizimle İletişime Geçin
            </h1>
            <p class="mt-6 text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
              Sorularınız, önerileriniz veya rezervasyonlarınız için bize ulaşın. 
              <span class="font-medium text-primary-600 dark:text-primary-400">Size yardımcı olmaktan mutluluk duyarız.</span>
            </p>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden backdrop-blur-sm border border-gray-100 dark:border-gray-700">
          <div class="grid grid-cols-1 lg:grid-cols-12">
            <!-- İletişim Bilgileri -->
            <div class="lg:col-span-5 bg-gradient-to-br from-primary-600 via-primary-600 to-primary-700 dark:from-primary-700 dark:via-primary-700 dark:to-primary-800 p-8 md:p-12 relative overflow-hidden">
              <!-- Dekoratif arka plan elementleri -->
              <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 rounded-full filter blur-2xl transform translate-x-1/3 translate-y-1/3"></div>
              <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full filter blur-2xl transform -translate-x-1/3 -translate-y-1/3"></div>
              
              <div class="relative">
                <h2 class="text-3xl font-bold text-white mb-8">İletişim Bilgilerimiz</h2>
                
                <div class="space-y-10">
                  <!-- Adres -->
                  <div class="flex items-start group">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-white/10 backdrop-blur group-hover:bg-white/20 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </div>
                    </div>
                    <div class="ml-6">
                      <p class="text-xl font-medium text-white mb-2">Adresimiz</p>
                      <p class="text-lg text-primary-100">
                        Merkez Mahallesi, İstiklal Caddesi No:123<br>
                        Şişli / İstanbul
                      </p>
                    </div>
                  </div>

                  <!-- İletişim -->
                  <div class="flex items-start group">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-white/10 backdrop-blur group-hover:bg-white/20 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                      </div>
                    </div>
                    <div class="ml-6">
                      <p class="text-xl font-medium text-white mb-2">İletişim</p>
                      <a href="tel:+902121234567" class="flex items-center text-lg text-primary-100 hover:text-white transition-colors group-hover:translate-x-1 transform duration-300">
                        +90 (212) 123 45 67
                        <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                      </a>
                      <a href="mailto:info@turprojesi.com" class="flex items-center text-lg text-primary-100 hover:text-white transition-colors group-hover:translate-x-1 transform duration-300 mt-1">
                        info@turprojesi.com
                        <svg class="w-4 h-4 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                      </a>
                    </div>
                  </div>

                  <!-- Çalışma Saatleri -->
                  <div class="flex items-start group">
                    <div class="flex-shrink-0">
                      <div class="flex items-center justify-center w-14 h-14 rounded-xl bg-white/10 backdrop-blur group-hover:bg-white/20 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                    </div>
                    <div class="ml-6">
                      <p class="text-xl font-medium text-white mb-2">Çalışma Saatleri</p>
                      <div class="text-lg">
                        <div class="flex justify-between text-primary-100 border-b border-white/10 py-2">
                          <span>Hafta İçi:</span>
                          <span class="font-medium text-white">09:00 - 18:00</span>
                        </div>
                        <div class="flex justify-between text-primary-100 border-b border-white/10 py-2">
                          <span>Cumartesi:</span>
                          <span class="font-medium text-white">10:00 - 14:00</span>
                        </div>
                        <div class="flex justify-between text-primary-100 py-2">
                          <span>Pazar:</span>
                          <span class="font-medium text-white">Kapalı</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Sosyal Medya -->
                <div class="mt-12">
                  <p class="text-xl font-medium text-white mb-4">Bizi Takip Edin</p>
                  <div class="flex space-x-4">
                    <!-- Facebook -->
                    <a href="#" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/10 backdrop-blur hover:bg-white/20 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 transform">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                      </svg>
                    </a>
                    <!-- Twitter -->
                    <a href="#" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/10 backdrop-blur hover:bg-white/20 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 transform">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                      </svg>
                    </a>
                    <!-- Instagram -->
                    <a href="#" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/10 backdrop-blur hover:bg-white/20 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 transform">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                      </svg>
                    </a>
                    <!-- YouTube -->
                    <a href="#" class="w-12 h-12 flex items-center justify-center rounded-xl bg-white/10 backdrop-blur hover:bg-white/20 transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 transform">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm4.441 16.892c-2.102.144-6.784.144-8.883 0-2.276-.156-2.541-1.27-2.558-4.892.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0 2.277.156 2.541 1.27 2.559 4.892-.018 3.629-.285 4.736-2.559 4.892zm-6.441-7.234l4.917 2.338-4.917 2.346v-4.684z" />
                      </svg>
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- İletişim Formu -->
            <div class="lg:col-span-7 p-8 md:p-12">
              <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Mesajınızı Bırakın</h2>
              <form @submit.prevent="handleSubmit" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ad Soyad <span class="text-red-500">*</span></label>
                    <div class="relative transition-all duration-300 group">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <input 
                        type="text" 
                        id="name"
                        v-model="form.name" 
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all group-hover:border-primary-300 dark:group-hover:border-primary-500"
                        :class="{'border-red-500 dark:border-red-400': errors.name}"
                        placeholder="Adınız Soyadınız"
                      >
                    </div>
                    <p v-if="errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.name }}</p>
                  </div>

                  <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">E-posta <span class="text-red-500">*</span></label>
                    <div class="relative transition-all duration-300 group">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                      </div>
                      <input 
                        type="email" 
                        id="email"
                        v-model="form.email" 
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all group-hover:border-primary-300 dark:group-hover:border-primary-500"
                        :class="{'border-red-500 dark:border-red-400': errors.email}"
                        placeholder="ornek@email.com"
                      >
                    </div>
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.email }}</p>
                  </div>

                  <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefon <span class="text-red-500">*</span></label>
                    <div class="relative transition-all duration-300 group">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                      </div>
                      <input 
                        type="tel" 
                        id="phone"
                        v-model="form.phone" 
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all group-hover:border-primary-300 dark:group-hover:border-primary-500"
                        :class="{'border-red-500 dark:border-red-400': errors.phone}"
                        placeholder="+90 (___) ___ __ __"
                      >
                    </div>
                    <p v-if="errors.phone" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.phone }}</p>
                  </div>

                  <div class="space-y-2">
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konu <span class="text-red-500">*</span></label>
                    <div class="relative transition-all duration-300 group">
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                      </div>
                      <input 
                        type="text" 
                        id="subject"
                        v-model="form.subject" 
                        required
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all group-hover:border-primary-300 dark:group-hover:border-primary-500"
                        :class="{'border-red-500 dark:border-red-400': errors.subject}"
                        placeholder="Mesajınızın konusu"
                      >
                    </div>
                    <p v-if="errors.subject" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.subject }}</p>
                  </div>
                </div>

                <div class="space-y-2">
                  <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mesajınız <span class="text-red-500">*</span></label>
                  <div class="relative transition-all duration-300 group">
                    <div class="absolute top-3 left-4">
                      <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </div>
                    <textarea 
                      id="message"
                      v-model="form.message" 
                      required
                      rows="6"
                      class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-all resize-none group-hover:border-primary-300 dark:group-hover:border-primary-500"
                      :class="{'border-red-500 dark:border-red-400': errors.message}"
                      placeholder="Mesajınızı buraya yazın..."
                    ></textarea>
                  </div>
                  <p v-if="errors.message" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.message }}</p>
                </div>

                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input 
                      type="checkbox" 
                      id="terms"
                      v-model="form.terms" 
                      required
                      class="h-5 w-5 text-primary-600 focus:ring-primary-500 focus:ring-offset-0 border-gray-300 dark:border-gray-600 rounded transition-colors duration-200"
                      :class="{'border-red-500 dark:border-red-400': errors.terms}"
                    >
                  </div>
                  <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">KVKK metnini okudum ve kabul ediyorum <span class="text-red-500">*</span></label>
                    <a href="#" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 ml-1">KVKK Metni</a>
                    <p v-if="errors.terms" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.terms }}</p>
                  </div>
                </div>

                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                  <span class="text-red-500">*</span> işaretli alanlar zorunludur
                </div>

                <div>
                  <button 
                    type="submit"
                    class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-md text-base font-medium text-white bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg"
                  >
                    <span class="relative flex items-center">
                      <span>Mesaj Gönder</span>
                      <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                      </svg>
                    </span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Harita ve Durumlar -->
      <div class="mt-12 grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- Harita -->
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-3xl shadow-lg overflow-hidden">
          <div class="relative w-full h-[400px] lg:h-[500px]">
            <div class="absolute top-0 left-0 w-full h-12 bg-gradient-to-b from-white to-transparent dark:from-gray-800 z-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-full h-12 bg-gradient-to-t from-white to-transparent dark:from-gray-800 z-10 pointer-events-none"></div>
            <div class="absolute left-0 top-0 w-12 h-full bg-gradient-to-r from-white to-transparent dark:from-gray-800 z-10 pointer-events-none"></div>
            <div class="absolute right-0 top-0 w-12 h-full bg-gradient-to-l from-white to-transparent dark:from-gray-800 z-10 pointer-events-none"></div>
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3008.443771803023!2d28.977549375696738!3d41.03461012397401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cab7650656bd63%3A0x8ca058b28c20b6c3!2zxLBzdGlrbGFsIENkLiwgQmV5b8SfbHUvxLBzdGFuYnVs!5e0!3m2!1str!2str!4v1709999999999!5m2!1str!2str" 
              width="100%" 
              height="100%" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy"
              class="w-full h-full"
            ></iframe>
          </div>
        </div>
        
        <!-- Durum Kartları -->
        <div class="lg:col-span-2 grid grid-cols-1 gap-6">
          <!-- SSS Durumu -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 flex items-center">
            <div class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-6 flex-shrink-0">
              <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Sık Sorulan Sorular</h3>
              <p class="text-gray-600 dark:text-gray-400 mb-3">Yaygın sorulara yanıt bulun</p>
              <a href="/sss" class="inline-flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                <span>SSS Sayfasına Git</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </a>
            </div>
          </div>
          
          <!-- Rezervasyon Durumu -->
          <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-lg border border-gray-100 dark:border-gray-700 flex items-center">
            <div class="w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center mr-6 flex-shrink-0">
              <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Tur Rezervasyonu</h3>
              <p class="text-gray-600 dark:text-gray-400 mb-3">Hemen yerinizi ayırtın</p>
              <a href="/tours" class="inline-flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                <span>Turları İncele</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </a>
            </div>
          </div>
          
          <!-- Whatsapp Desteği -->
          <div class="bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 rounded-3xl p-6 shadow-lg text-white relative overflow-hidden">
            <!-- Dekoratif arka plan elementleri -->
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white/10 rounded-full filter blur-xl"></div>
            <div class="absolute -top-6 -left-6 w-32 h-32 bg-white/10 rounded-full filter blur-xl"></div>
            
            <div class="relative">
              <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/20 backdrop-blur mr-3">
                  <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                  </svg>
                </div>
                <h3 class="text-xl font-bold">WhatsApp Desteği</h3>
              </div>
              <p class="mb-5 text-white/90">7/24 WhatsApp üzerinden hızlı destek alın</p>
              <a href="https://wa.me/902121234567" class="inline-block px-6 py-3 bg-white/90 hover:bg-white text-primary-600 rounded-xl font-medium shadow-lg transition-all duration-300 hover:scale-105 transform hover:shadow-xl">
                <div class="flex items-center">
                  <span>Hemen Mesaj Gönder</span>
                  <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                  </svg>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

const form = ref({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
  terms: false
})

const errors = reactive({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: '',
  terms: ''
})

// Form doğrulama işlemi
const validateForm = () => {
  let isValid = true;
  
  // Tüm hata mesajlarını temizle
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
  
  // Ad Soyad kontrolü
  if (!form.value.name.trim()) {
    errors.name = 'Ad Soyad alanı zorunludur';
    isValid = false;
  }
  
  // E-posta kontrolü
  if (!form.value.email.trim()) {
    errors.email = 'E-posta alanı zorunludur';
    isValid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
    errors.email = 'Geçerli bir e-posta adresi giriniz';
    isValid = false;
  }
  
  // Telefon kontrolü
  if (!form.value.phone.trim()) {
    errors.phone = 'Telefon alanı zorunludur';
    isValid = false;
  }
  
  // Konu kontrolü
  if (!form.value.subject.trim()) {
    errors.subject = 'Konu alanı zorunludur';
    isValid = false;
  }
  
  // Mesaj kontrolü
  if (!form.value.message.trim()) {
    errors.message = 'Mesaj alanı zorunludur';
    isValid = false;
  } else if (form.value.message.trim().length < 10) {
    errors.message = 'Mesajınız en az 10 karakter olmalıdır';
    isValid = false;
  }
  
  // KVKK onayı kontrolü
  if (!form.value.terms) {
    errors.terms = 'KVKK metnini onaylamanız gerekmektedir';
    isValid = false;
  }
  
  return isValid;
}

const handleSubmit = async () => {
  // Form doğrulama
  if (!validateForm()) {
    toast.error('Lütfen tüm alanları doğru şekilde doldurunuz', {
      timeout: 3000,
      position: 'top-right'
    });
    return;
  }
  
  try {
    // Form gönderme işlemi simülasyonu
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    toast.success('Mesajınız başarıyla gönderildi!', {
      timeout: 3000,
      position: 'top-right'
    })
    
    // Formu sıfırla
    form.value = {
      name: '',
      email: '',
      phone: '',
      subject: '',
      message: '',
      terms: false
    }
  } catch (error) {
    toast.error('Bir hata oluştu. Lütfen tekrar deneyin.', {
      timeout: 3000,
      position: 'top-right'
    })
  }
}
</script>

<style scoped>
.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.scroll-parallax {
  will-change: transform;
}

.animate-blob {
  animation: blob 10s infinite alternate;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

/* Form animasyonları */
input, textarea {
  transition: all 0.3s ease;
}

input:focus, textarea:focus {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.1);
}
</style> 