import axios from 'axios';

/**
 * Favoriler için özel servis - Admin paneli tarzında yapılandırılmış
 * Axios'un baseURL konfigürasyonunu bypass ederek düzgün çalışmasını sağlar
 */
const favoriteService = {
  // API endpoint'leri - doğrudan tam yollar
  endpoints: {
    check: '/api/favorites/check',
    toggle: '/api/favorites/toggle'
  },
  
  /**
   * CSRF tokenını yeniler
   */
  async refreshCSRFToken() {
    try {
      await axios.get('/sanctum/csrf-cookie', {
        baseURL: '', // Sanctum için baseURL'i override et
      });
    } catch (error) {
      console.error('CSRF token yenilenirken hata oluştu:', error);
      throw error;
    }
  },
  
  /**
   * API isteği gönderir (Admin paneli tarzında baseURL yok)
   * @param {string} method - HTTP metodu (get, post, put, delete)
   * @param {string} endpoint - Endpoint (/api/favorites/toggle gibi)
   * @param {Object} data - İstek verileri
   * @returns {Promise<AxiosResponse>} - API yanıtı
   */
  async apiRequest(method, endpoint, data = {}) {
    try {
      // CSRF token'ı yenile (istekte kullanılabilmesi için)
      await this.refreshCSRFToken();
      
      // Debug için günlüğe kaydet
      console.log(`API isteği yapılıyor: ${method.toUpperCase()} ${endpoint}`);
      
      // Larvel'e doğrudan istek yapacak şekilde URL oluştur
      const apiUrl = endpoint.startsWith('/') 
        ? endpoint 
        : `/${endpoint}`;
      
      // Yanıt formatını JSON olarak beklemek için Accept başlığı ekliyoruz
      // Bu çok önemli, aksi halde Laravel HTML dönebilir
      const config = {
        method: method.toLowerCase(),
        url: apiUrl, // Göreli URL kullan (axios tarafından base URL ile birleştirilir)
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest' // Laravel'in API isteklerini ayırt etmesi için
        },
        withCredentials: true // Cookie'leri gönder (Sanctum için)
      };
      
      // GET metodu için params kullan, diğerleri için data kullan
      if (method.toLowerCase() === 'get') {
        config.params = data;
      } else {
        config.data = data;
      }
      
      // API isteğini yap ve yanıtı bekle
      const response = await axios(config);
      
      // Yanıtın veri formatını kontrol et
      if (response.headers['content-type'] && !response.headers['content-type'].includes('application/json')) {
        throw new Error('Sunucu JSON yanıtı dönmedi. Yanıt içerik tipi: ' + response.headers['content-type']);
      }
      
      // Başarılı yanıtı döndür
      return response;
    } catch (error) {
      console.error('API isteği sırasında hata oluştu:', error);
      
      // Detaylı hata loglaması
      if (error.response) {
        console.error('Sunucu yanıtı:', error.response.status);
        console.error('İçerik tipi:', error.response.headers['content-type']);
        
        // HTML yanıt kontrolü
        if (error.response.headers['content-type'] && 
            error.response.headers['content-type'].includes('text/html')) {
          console.error('Sunucu HTML yanıtı döndü (JSON bekleniyor). Bu bir API yapılandırma sorunudur.');
        }
      } else if (error.request) {
        console.error('İstek gönderildi ancak yanıt alınamadı:', error.request);
      } else {
        console.error('İstek oluştururken hata:', error.message);
      }
      
      // Hata yansıt
      throw error;
    }
  },
  
  /**
   * Bir tur ID'si için favori durumunu kontrol eder
   * @param {number} tourId - Tur ID
   * @returns {Promise<boolean>} - Favori durumu
   */
  async checkFavoriteStatus(tourId) {
    try {
      if (!tourId) {
        console.warn('Favori kontrolü: Tur ID eksik');
        return false;
      }
      
      // API isteği yap
      const response = await this.apiRequest('get', this.endpoints.check, {
        tour_id: tourId
      });
      
      // Yanıt kontrolü
      if (!response.data || typeof response.data !== 'object') {
        console.error('Favori kontrolü: Geçersiz yanıt formatı', response);
        return false;
      }
      
      return response.data.is_favorited === true;
    } catch (error) {
      console.error('Favori durumu kontrol edilirken hata oluştu:', error);
      return false; // Varsayılan olarak false döndür
    }
  },
  
  /**
   * Favori durumunu değiştirir (ekler/çıkarır)
   * @param {number} tourId - Tur ID
   * @returns {Promise<Object>} - İşlem sonucu
   */
  async toggleFavorite(tourId) {
    try {
      if (!tourId) {
        throw new Error('Tur ID bilgisi eksik');
      }
      
      // API isteği yap - GET metodunu kullan
      const response = await this.apiRequest('get', this.endpoints.toggle, {
        tour_id: tourId
      });
      
      // Yanıt kontrolü
      if (!response.data || typeof response.data !== 'object') {
        throw new Error('Geçersiz yanıt formatı');
      }
      
      return {
        success: response.data.status === 'success',
        message: response.data.message || '',
        action: response.data.action
      };
    } catch (error) {
      console.error('Favori işlemi sırasında hata oluştu:', error);
      
      // Detaylı hata loglaması
      if (error.response) {
        console.error('Sunucu yanıtı:', error.response.status);
      }
      
      // Hata durumunda standart yanıt formatı döndür
      return {
        success: false,
        message: error.response?.data?.message || 'Favori işlemi sırasında bir hata oluştu',
        error: error.message
      };
    }
  }
};

export default favoriteService; 