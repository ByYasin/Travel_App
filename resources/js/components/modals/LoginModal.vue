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
                Hoş Geldiniz
              </h3>
              <p class="text-gray-600 dark:text-gray-400">
                Hesabınıza giriş yapın
              </p>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- Hata Mesajı -->
              <div v-if="error" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                {{ error }}
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

              <!-- Beni Hatırla -->
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <input type="checkbox" 
                         id="remember" 
                         v-model="form.remember"
                         class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                  <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Beni hatırla
                  </label>
                </div>
                <a href="#" class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                  Şifremi unuttum
                </a>
              </div>

              <!-- Giriş Yap Butonu -->
              <button type="submit" 
                      class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800 transition-all duration-200 group">
                <span>Giriş Yap</span>
                <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
              </button>
            </form>

            <!-- Kayıt Ol Linki -->
            <div class="mt-8 text-center">
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Hesabınız yok mu?
                <button @click="$emit('switch-to-register')" 
                        class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
                  Kayıt Ol
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
import { ref } from 'vue';
import { useAuthStore } from '../../store/auth';

const authStore = useAuthStore();
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  }
});

const emit = defineEmits(['close', 'switch-to-register']);

const form = ref({
  email: '',
  password: '',
  remember: false
});

const error = ref('');

const handleSubmit = async () => {
  // Hata mesajını sıfırla
  error.value = '';
  
  // Form validasyonu
  if (!form.value.email || !form.value.password) {
    error.value = 'Lütfen tüm alanları doldurun.';
    return;
  }

  try {
    const result = await authStore.login({
      email: form.value.email,
      password: form.value.password,
      remember: form.value.remember
    });
    
    // Sadece başarılı giriş durumunda modalı kapat
    if (result && result.token) {
      console.log('Login modal: Giriş başarılı, modal kapatılıyor');
      emit('close');
    }
  } catch (err) {
    console.error('Login error:', err);
    error.value = err.message || 'E-posta veya şifre hatalı. Lütfen bilgilerinizi kontrol ediniz.';
    // Hata durumunda modalı kapatmıyoruz, kullanıcı tekrar deneyebilir
  }
};
</script> 