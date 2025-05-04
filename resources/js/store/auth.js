import { defineStore } from 'pinia'
import axios from 'axios'
import authService from '@/services/auth'
import { ref, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
    error: null,
  }),
  
  persist: {
    key: 'auth',
    storage: localStorage,
    paths: ['user'],
  },
  
  getters: {
    isLoggedIn: (state) => !!state.user,
    isAdmin: (state) => state.user?.role?.name === 'admin',
    userData: (state) => state.user,
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.error = null
      
      try {
        console.log('Auth store login işlemi başlatılıyor')
        
        // Auth service aracılığıyla login işlemini yap
        const response = await authService.login(credentials);
        
        console.log('Login response:', response);
        
        // Kullanıcı bilgisini al
        await this.fetchUser();
        
        this.initAuth();
        
        return true;
      } catch (err) {
        console.error('Auth store login hatası:', err)
        
        // Hata durumunda store'daki kullanıcı bilgilerini temizle
        this.user = null 
        
        // Hata mesajını belirle (auth service'den gelen özel mesajları kullan)
        if (err.message) {
          this.error = err.message;
        } else if (err.response?.data?.message) {
          this.error = err.response.data.message;
        } else {
          this.error = 'Giriş sırasında bir hata oluştu';
        }
        
        // Hata durumunu yukarıya gönder 
        throw err;
      } finally {
        this.loading = false
      }
    },

    async register(userData) {
      this.loading = true
      this.error = null
      try {
        console.log('Auth store register işlemi başlatılıyor')
        
        // CSRF token al
        await axios.get('/sanctum/csrf-cookie');
        
        // Kayıt ol
        const response = await axios.post('/register', userData)
        
        console.log('Register response:', response);
        
        // Kullanıcı bilgisini al
        await this.fetchUser();
        
        this.initAuth();
        
        return true;
      } catch (err) {
        console.error('Auth store register hatası:', err)
        
        this.error = err.response?.data?.message || 'Kayıt sırasında bir hata oluştu'
        
        throw err
      } finally {
        this.loading = false
      }
    },

    async logout() {
      this.loading = true
      try {
        console.log('Auth Store: Çıkış işlemi başlatılıyor')
        await axios.post('/logout')
        this.setUser(null)
      } catch (err) {
        console.error('Auth Store: Çıkış sırasında hata:', err)
      } finally {
        // Hata olsa bile kullanıcı bilgilerini temizle
        console.log('Auth Store: User store değeri temizleniyor')
        this.loading = false
      }
    },

    async fetchUser() {
      try {
        this.loading = true
        const response = await axios.get('/user')
        console.log('Fetch user response:', response)
        this.setUser(response.data)
        return response.data
      } catch (err) {
        console.error('Fetch user error:', err)
        if (err.response?.status === 401) {
          this.setUser(null)
        }
        return null
      } finally {
        this.loading = false
      }
    },

    async refreshUserData() {
      if (!this.user) {
        console.log('RefreshUserData: Kullanıcı giriş yapmamış, çıkılıyor');
        return;
      }
      
      try {
        const timestamp = new Date().getTime() // Önbelleği önlemek için
        console.log('Auth Store: refreshUserData çağırıldı');
        
        const response = await axios.get(`/user?t=${timestamp}`);
        
        // Mevcut veri ile karşılaştır, değişiklik varsa güncelle
        const newUserData = response.data;
        const currentUserData = this.user;
        
        if (JSON.stringify(newUserData) !== JSON.stringify(currentUserData)) {
          console.log('User data changed, updating...');
          this.setUser(newUserData);
        }
      } catch (err) {
        console.error('Auth Store: refreshUserData hatası:', err);
        
        // 401 hatası ise kullanıcı oturumunu temizle
        if (err.response?.status === 401) {
          console.log('Auth Store: 401 hatası, kullanıcı oturumu sonlandırılıyor');
          this.setUser(null);
          window.dispatchEvent(new Event('auth:session-expired'));
        }
        
        this.error = 'Kullanıcı bilgileri alınamadı, lütfen tekrar giriş yapın'
        throw err
      }
    },

    // Kimlik doğrulama durumunu başlat
    initAuth() {
      console.log('Auth store initialized');
      
      // Eski localStorage verisini kontrol et ve gerekirse temizle
      const legacyUser = localStorage.getItem('user');
      if (legacyUser) {
        try {
          const userData = JSON.parse(legacyUser);
          if (!this.user) {
            this.user = userData; // Eğer store boşsa eski veriden aktar
          }
          localStorage.removeItem('user'); // Eski veriyi temizle
          console.log('Eski localStorage formatından veri aktarıldı');
        } catch (e) {
          console.error('Eski localStorage verisi çözümlenemedi:', e);
          localStorage.removeItem('user');
        }
      }
      
      // Kullanıcı güncellendiğinde store'daki verileri güncelle
      window.addEventListener('user-updated', (event) => {
        console.log('Auth Store: Kullanıcı verileri güncellendi, rol:', event.detail?.role_id);
        
        // Store'daki kullanıcı bilgilerini güncelle
        this.user = event.detail;
        
        // Admin rolü değişmişse bildiri göster
        if (this.isAdmin) {
          console.log('Auth Store: Kullanıcı artık admin yetkisine sahip.');
        }
      });
      
      // Storage değişikliklerini dinle (farklı sekmeler arası senkronizasyon)
      window.addEventListener('storage', (event) => {
        if (event.key === 'auth') {
          if (event.newValue) {
            try {
              const data = JSON.parse(event.newValue);
              // Başka bir sekmede kullanıcı giriş yapmışsa güncelle
              if (data.user && !this.user) {
                this.user = data.user;
                console.log('Auth Store: Farklı bir sekmede kullanıcı girişi algılandı');
              }
            } catch (error) {
              console.error('Storage event veri çözme hatası:', error);
            }
          } else {
            // Başka bir sekmede çıkış yapıldıysa temizle
            this.user = null;
            console.log('Auth Store: Farklı bir sekmede kullanıcı çıkışı algılandı');
          }
        }
      });
      
      // Auth expire olduğunda (401 hatası)
      window.addEventListener('auth-expired', () => {
        console.log('Auth Store: Oturum süresi doldu, logout yapılıyor');
        this.user = null;
      });
    },
    
    // Kullanıcı bilgilerini ayarlar
    setUser(userData) {
      console.log('setUser çağrıldı:', userData);
      this.user = userData;
    },
    
    // Otomatik veri yenileme
    startAutoRefresh() {
      // Önceki interval'ı temizle
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval);
      }
      
      // Her 5 dakikada bir kullanıcı verilerini güncelle
      this.refreshInterval = setInterval(() => {
        if (this.isLoggedIn) {
          this.refreshUserData().catch(err => {
            console.error('Auth Store: Otomatik güncelleme hatası', err);
          });
        }
      }, 5 * 60 * 1000); // 5 dakikada bir
      
      console.log('Auth Store: Otomatik veri güncelleme başlatıldı');
    },
    
    // Uygulamadan çıkarken interval'ı temizle
    stopAutoRefresh() {
      if (this.refreshInterval) {
        clearInterval(this.refreshInterval);
        this.refreshInterval = null;
        console.log('Auth Store: Otomatik veri güncelleme durduruldu');
      }
    }
  }
}) 