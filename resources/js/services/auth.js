import axios from 'axios';

const API_URL = '';

// CSRF token ayarla - Sanctum için gerekli
axios.defaults.withCredentials = true;

// Her istekten önce CSRF token'ı almak için
async function initCsrf() {
  try {
    console.log('CSRF token alınmaya çalışılıyor...');
    await axios.get('/sanctum/csrf-cookie');
    console.log('CSRF token başarıyla alındı');
  } catch (error) {
    console.error('CSRF token alınamadı:', error.message, error.response?.status);
    
    if (error.response?.status === 404) {
      console.error('CSRF token endpoint bulunamadı. Sanctum doğru yapılandırılmamış olabilir.');
    }
  }
}

class AuthService {
  async login(credentials) {
    try {
      console.log('Auth Service: Login isteği gönderiliyor');
      
      // Önce CSRF token alalım
      await initCsrf();
      
      // Login isteği
      const response = await axios.post(`/login`, credentials);
      
      // Response kontrol
      if (!response || !response.data) {
        console.error('Auth Service: Login cevabı geçersiz', response);
        throw new Error('Sunucu yanıtı geçersiz. Lütfen daha sonra tekrar deneyin.');
      }
      
      // User bilgisini localStorage'a kaydet - token artık cookie'de
      if (response.data.user) {
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      
      console.log('Auth Service: Login başarılı');
      return response.data;
    } catch (error) {
      console.error('Auth Service: Login hatası oluştu', error.response?.status || error.message);
      
      // Hata mesajlarını anlaşılır hale getir
      if (error.response?.status === 401) {
        throw { message: 'E-posta veya şifre hatalı. Lütfen bilgilerinizi kontrol ediniz.' };
      } else if (error.response?.status >= 500) {
        throw { message: 'Sunucu hatası. Lütfen daha sonra tekrar deneyin.' };
      } else if (error.response?.data?.message) {
        throw { message: error.response.data.message };
      }
      
      throw { message: 'Giriş yapılırken bir hata oluştu. Lütfen daha sonra tekrar deneyin.' };
    }
  }

  async register(user) {
    try {
      console.log('Auth Service: Register isteği gönderiliyor');
      
      // Önce CSRF token alalım
      await initCsrf();
      
      // Register isteği
      const response = await axios.post(`/register`, user);
      
      // User bilgisini localStorage'a kaydet
      if (response.data.user) {
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      
      console.log('Auth Service: Register başarılı');
      return response.data;
    } catch (error) {
      console.error('Auth Service: Register hatası', error.response || error);
      throw error.response?.data || { message: 'Bir hata oluştu' };
    }
  }

  async logout() {
    try {
      console.log('Auth Service: Logout isteği gönderiliyor');
      
      // Logout API isteği
      await axios.post(`${API_URL}/logout`);
      console.log('Auth Service: Logout başarılı');
    } catch (error) {
      console.error('Auth Service: Çıkış sırasında hata', error);
    } finally {
      // User ve token bilgilerini localStorage'dan temizle
      localStorage.removeItem('user');
      localStorage.removeItem('token');
      
      // Tüm oturum verilerini temizle
      document.cookie.split(";").forEach(cookie => {
        const eqPos = cookie.indexOf("=");
        const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
      });
      
      // Sayfayı login sayfasına yenile
      window.location.href = '/login';
    }
  }

  getCurrentUser() {
    return JSON.parse(localStorage.getItem('user'));
  }

  isLoggedIn() {
    // Sanctum ile oturum durumunu kontrol etmek için user bilgisine bakıyoruz
    const user = this.getCurrentUser();
    return !!user;
  }

  // Kullanıcı bilgilerini günceller
  async refreshUserData() {
    try {
      console.log('Auth Service: Kullanıcı bilgileri yenileniyor');
      
      // Önce CSRF token alalım
      await initCsrf();
      
      // Cache'i önlemek için timestamp ekle
      const timestamp = new Date().getTime();
      const response = await axios.get(`${API_URL}/user?_t=${timestamp}`);
      
      // User bilgisini localStorage'a kaydet
      if (response.data) {
        // Daha önce kayıtlı kullanıcıyı al
        const oldUser = JSON.parse(localStorage.getItem('user') || '{}');
        
        // Veri değişikliği var mı kontrol et
        const isDataChanged = JSON.stringify(oldUser) !== JSON.stringify(response.data);
        
        if (isDataChanged) {
          console.log('Auth Service: Kullanıcı verileri değişmiş, güncelleniyor', {
            'Eski rol': oldUser.role_id,
            'Yeni rol': response.data.role_id
          });
        }
        
        // Güncel veriyi kaydet
        localStorage.setItem('user', JSON.stringify(response.data));
        
        // Değişiklik varsa güncellendiğine dair event gönder
        if (isDataChanged) {
          window.dispatchEvent(new CustomEvent('user-updated', { detail: response.data }));
        }
        
        return response.data;
      }
      
      // Kullanıcı bilgisi alınamadıysa null dön
      return null;
    } catch (error) {
      console.error('Auth Service: Kullanıcı bilgileri alınamadı', error);
      
      // 401 hatası alındıysa kullanıcı giriş yapmamış demektir
      if (error.response?.status === 401) {
        // User bilgisini localStorage'dan temizle
        localStorage.removeItem('user');
        
        // Kullanıcının yeniden login olması gerektiğine dair event gönder
        window.dispatchEvent(new CustomEvent('auth-expired'));
      }
      
      throw error;
    }
  }

  // Axios ayarlamaları
  setupInterceptors() {
    console.log('Auth Service: Interceptor ayarlanıyor');
    
    // 401 hatası alındığında otomatik çıkış yapma
    axios.interceptors.response.use(
      response => response,
      error => {
        // 401 (Unauthorized) veya 419 (CSRF token mismatch) hatalarında
        if (error.response && (error.response.status === 401 || error.response.status === 419)) {
          console.log('Auth Service: 401/419 hatası, oturum geçersiz');
          
          // User bilgisini localStorage'dan temizle
          localStorage.removeItem('user');
          
          // CSRF token hatası olabilir, yeniden CSRF token al
          if (error.response.status === 419) {
            console.log('Auth Service: CSRF token hatası, yenileniyor');
            initCsrf();
          }
        }
        return Promise.reject(error);
      }
    );
  }
}

export default new AuthService(); 