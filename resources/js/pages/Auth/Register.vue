<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 p-6">
    <div class="w-full max-w-md p-8 bg-white dark:bg-gray-800 rounded-xl shadow-xl transition-all duration-300 transform hover:scale-[1.01]">
      <div class="text-center mb-8">
        <div class="h-20 w-20 mx-auto mb-4 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
          <svg class="h-10 w-10 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
          Yeni Hesap Oluşturun
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          Zaten bir hesabınız var mı? 
          <router-link :to="{ name: 'login' }" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
            Giriş Yapın
          </router-link>
        </p>
      </div>

      <div v-if="error" class="mb-6 p-4 rounded-md bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-500 text-red-700 dark:text-red-300 text-sm shadow-sm" role="alert">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <div v-html="error"></div>
        </div>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-6">
        <!-- Ad Soyad -->
        <div>
          <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            Adınız Soyadınız
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <input
              id="name"
              name="name"
              type="text"
              autocomplete="name"
              required
              v-model="form.name"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              placeholder="Adınız Soyadınız"
            />
          </div>
        </div>

        <!-- E-posta -->
        <div>
          <label for="email-address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            E-posta Adresi
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
              </svg>
            </div>
            <input
              id="email-address"
              name="email"
              type="email"
              autocomplete="email"
              required
              v-model="form.email"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              placeholder="E-posta Adresi"
            />
          </div>
        </div>

        <!-- Şifre -->
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            Şifre
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <input
              id="password"
              name="password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              required
              v-model="form.password"
              class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              placeholder="Şifre"
            />
            <div 
              class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
              @click="togglePasswordVisibility('password')"
            >
              <svg v-if="showPassword" class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              <svg v-else class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Şifre Tekrar -->
        <div>
          <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
            Şifre Tekrar
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <input
              id="password_confirmation"
              name="password_confirmation"
              :type="showPasswordConfirmation ? 'text' : 'password'"
              autocomplete="new-password"
              required
              v-model="form.password_confirmation"
              class="block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 text-gray-900 dark:text-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
              placeholder="Şifre Tekrar"
            />
            <div 
              class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer"
              @click="togglePasswordVisibility('confirmation')"
            >
              <svg v-if="showPasswordConfirmation" class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              <svg v-else class="h-5 w-5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
              </svg>
            </div>
          </div>
        </div>

        <!-- Kullanım Koşulları -->
        <div class="p-4 bg-gray-50 dark:bg-gray-800/40 rounded-lg border border-gray-200 dark:border-gray-700">
          <div class="flex items-start">
            <div class="flex items-center h-5">
              <input
                id="terms"
                name="terms"
                type="checkbox"
                v-model="form.terms"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700 transition-colors"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="terms" class="font-medium text-gray-700 dark:text-gray-300">
                Kullanım koşullarını kabul ediyorum
              </label>
              <p class="text-gray-500 dark:text-gray-400 mt-1 text-xs">
                Kişisel verilerinizin gizlilik politikamız doğrultusunda işleneceğini kabul ediyorum.
              </p>
            </div>
          </div>
        </div>

        <!-- Kayıt Butonu -->
        <button
          type="submit"
          :disabled="loading"
          class="w-full flex justify-center items-center py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg transform transition-all duration-200 hover:-translate-y-0.5"
        >
          <span v-if="loading" class="mr-2">
            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </span>
          <span class="text-base font-medium">Hesap Oluştur</span>
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/auth';

const router = useRouter();
const authStore = useAuthStore();

const loading = computed(() => authStore.loading);
const error = ref('');
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false
});

// Şifre görünürlüğünü değiştir
const togglePasswordVisibility = (field) => {
  if (field === 'password') {
    showPassword.value = !showPassword.value;
  } else if (field === 'confirmation') {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
  }
};

const handleRegister = async () => {
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
    await authStore.register(form.value);
    router.push('/');
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