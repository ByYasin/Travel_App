import axios from 'axios';

/**
 * API istekleri için merkezi servis.
 * Tüm rotaların düzgün şekilde /api/ önekini kullanmasını sağlar.
 */
const apiService = {
  /**
   * API yolunu standardize eder
   * @param {string} path - API yolu (/api/ öneki olmadan)
   * @returns {string} - Standardize edilmiş API yolu
   */
  getApiUrl(path) {
    // /api/ ile başlamıyorsa ekle
    if (!path.startsWith('/api/')) {
      // reviews endpoint'leri için her zaman /api öneki ekleyin
      if (path.includes('/reviews/') || path.includes('/replies/')) {
        return path.startsWith('/') ? `/api${path}` : `/api/${path}`;
      }
      // Diğer endpoint'ler için mevcut mantığı koruyun
      return path.startsWith('/') ? `/api${path}` : `/api/${path}`;
    }
    return path;
  },

  /**
   * GET isteği yapar
   * @param {string} url - İstek yapılacak URL
   * @param {Object} params - URL parametreleri
   * @returns {Promise} - Axios promise
   */
  async get(url, params = {}) {
    try {
      const response = await axios.get(this.getApiUrl(url), { params });
      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  },

  /**
   * POST isteği yapar
   * @param {string} url - İstek yapılacak URL
   * @param {Object} data - Gönderilecek veri
   * @returns {Promise} - Axios promise
   */
  async post(url, data = {}) {
    try {
      const response = await axios.post(this.getApiUrl(url), data);
      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  },

  /**
   * PUT isteği yapar
   * @param {string} url - İstek yapılacak URL
   * @param {Object} data - Gönderilecek veri
   * @returns {Promise} - Axios promise
   */
  async put(url, data = {}) {
    try {
      const response = await axios.put(this.getApiUrl(url), data);
      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  },

  /**
   * DELETE isteği yapar
   * @param {string} url - İstek yapılacak URL
   * @returns {Promise} - Axios promise
   */
  async delete(url) {
    try {
      const response = await axios.delete(this.getApiUrl(url));
      return response.data;
    } catch (error) {
      this.handleError(error);
      throw error;
    }
  },

  /**
   * Hata yönetimi
   * @param {Error} error - Hata nesnesi
   */
  handleError(error) {
    console.error('API İsteği Başarısız:', error);
    
    // Burada merkezi hata yönetimi yapabilirsiniz
    // Örneğin: global bir toast gösterme, oturum süresi dolmuşsa yönlendirme vb.
    
    if (error.response) {
      // Sunucudan gelen hata yanıtı
      console.error('Hata Detayları:', error.response.data);
      console.error('Durum Kodu:', error.response.status);
    } else if (error.request) {
      // İstek yapıldı ama yanıt alınamadı
      console.error('Sunucudan yanıt alınamadı');
    } else {
      // İstek yapılırken bir şeyler yanlış gitti
      console.error('İstek Hatası:', error.message);
    }
  }
};

export default apiService; 