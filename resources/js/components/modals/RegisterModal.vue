<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="items-center justify-center px-4 pt-4 pb-20 text-center min-h-screen md:flex sm:block sm:p-0">
      <!-- Overlay -->
      <div class="fixed inset-0 transition-opacity" aria-hidden="true">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900/80 to-gray-900/60 backdrop-blur-sm"></div>
      </div>

      <!-- Modal -->
      <div class="inline-block transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 px-4 pt-5 pb-4 text-left align-bottom shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6 sm:align-middle w-[95%] mx-auto">
        <!-- Close Button -->
        <div class="absolute top-0 right-0 pt-4 pr-4">
          <button @click="$emit('close')" 
                  class="rounded-full bg-gray-100 dark:bg-gray-700 p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none transition-colors duration-200">
            <span class="sr-only">Kapat</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Content -->
        <div class="sm:flex sm:items-start">
          <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
            <!-- Header -->
            <div class="text-center mb-8">
              <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Hesap Oluştur
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Yeni bir hesap oluşturun
              </p>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 rounded-md bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-500 text-red-700 dark:text-red-300 text-sm shadow-sm">
              <div class="flex items-center">
                <svg class="h-5 w-5 mr-2 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div v-html="error"></div>
              </div>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Ad Soyad -->
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Ad Soyad
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <input type="text" 
                         id="name" 
                         v-model="form.name"
                         class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent dark:text-white transition-colors duration-200"
                         placeholder="Adınız ve soyadınız">
                </div>
              </div>

              <!-- E-posta -->
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  E-posta
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                  </div>
                  <input type="email" 
                         id="email" 
                         v-model="form.email"
                         class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent dark:text-white transition-colors duration-200"
                         placeholder="ornek@email.com">
                </div>
              </div>

              <!-- Şifre -->
              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Şifre
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <input type="password" 
                         id="password" 
                         v-model="form.password"
                         class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent dark:text-white transition-colors duration-200"
                         placeholder="••••••••">
                </div>
              </div>

              <!-- Şifre Tekrar -->
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Şifre Tekrar
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <input type="password" 
                         id="password_confirmation" 
                         v-model="form.password_confirmation"
                         class="w-full pl-10 pr-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent dark:text-white transition-colors duration-200"
                         placeholder="••••••••">
                </div>
              </div>

              <!-- Kullanım Koşulları -->
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input type="checkbox" 
                         id="terms" 
                         v-model="form.terms"
                         class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                </div>
                <div class="ml-3 text-sm">
                  <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">
                    <span>Kullanım koşullarını ve gizlilik politikasını kabul ediyorum</span>
                  </label>
                </div>
              </div>

              <!-- Kayıt Ol Butonu -->
              <button type="submit" 
                      :disabled="loading"
                      class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 group">
                <span v-if="loading" class="mr-2">
                  <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                <span>Kayıt Ol</span>
                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </button>
            </form>

            <!-- Giriş Yap Linki -->
            <div class="mt-8 text-center">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Zaten hesabınız var mı?
                <button @click="$emit('switch-to-login')" 
                        class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
                  Giriş Yap
                </button>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../../store/auth';

const authStore = useAuthStore();
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close', 'switch-to-login']);

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
});

const loading = computed(() => authStore.loading);
const error = ref('');

const handleSubmit = async () => {
  error.value = '';
  
  // Form validasyonu
  if (!form.value.name || !form.value.email || !form.value.password || !form.value.password_confirmation) {
    error.value = 'Lütfen tüm alanları doldurun.';
    return;
  }

  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Şifreler eşleşmiyor.';
    return;
  }

  if (!form.value.terms) {
    error.value = 'Lütfen kullanım koşullarını kabul edin.';
    return;
  }

  try {
    await authStore.register({
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.password_confirmation
    });
    emit('close');
  } catch (err) {
    console.error('Kayıt hatası:', err);
    
    // Validasyon hatalarını göster
    if (err.response && err.response.data) {
      if (err.response.data.message) {
        error.value = err.response.data.message;
      } else if (err.response.data.errors) {
        // Laravel'in validasyon hata formatını işle
        const errorMessages = [];
        for (const field in err.response.data.errors) {
          const fieldErrors = err.response.data.errors[field];
          if (Array.isArray(fieldErrors) && fieldErrors.length > 0) {
            errorMessages.push(fieldErrors[0]);
          }
        }
        error.value = errorMessages.join('<br>');
      }
    } else if (err.message) {
      error.value = err.message;
    } else {
      error.value = 'Kayıt sırasında beklenmeyen bir hata oluştu.';
    }
  }
};
</script> 