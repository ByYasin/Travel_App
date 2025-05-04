import axios from 'axios';
window.axios = axios;

// Temel axios yapılandırmaları
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.defaults.baseURL = '/api'; // API rotalarında tam yolu kullanıyoruz, bu değer '/api' olarak ayarlandı

// Axios istek ve cevaplarını logla (Debug için, daha fazla bilgi görmek için)
axios.interceptors.request.use(config => {
  // CSRF cookie için özel durum - baseURL'i temizle
  if (config.url === '/sanctum/csrf-cookie') {
    config.baseURL = '';
    console.log('CSRF cookie isteği hazırlandı:', config.url);
  }
  
  console.log('DEBUG - API İsteği:', config.method?.toUpperCase(), (config.baseURL || '') + config.url);
  return config;
}, error => {
  console.error('İstek yapılamadı:', error.message);
  return Promise.reject(error);
});

axios.interceptors.response.use(
  response => {
    console.log('DEBUG - API Cevabı:', response.status, response.config.url, response.data);
    return response;
  },
  error => {
    // Ağ hatası kontrolü (internet bağlantısı yok veya sunucuya erişilemiyor)
    if (!error.response) {
      console.error('Ağ hatası:', error.message);
      // Özel bir ağ hatası mesajı atayalım
      error.networkError = true;
      error.customMessage = 'Sunucuya bağlanılamıyor. Lütfen internet bağlantınızı kontrol edin.';
      
      // Eğer Toast kütüphanesi varsa global olarak bildirim gösterebiliriz
      if (window.showToast) {
        window.showToast({
          type: 'error',
          message: error.customMessage
        });
      }
    } else {
      console.error('DEBUG - API Hatası:', 
        error.response?.status || 'Bağlantı Hatası', 
        error.config?.url,
        error.message,
        error.response?.data
      );
      
      // 422 Validation hatalarını işle (Laravel validation hataları)
      if (error.response && error.response.status === 422) {
        // Laravel'in validation hata yapısını kontrol et
        const validationErrors = error.response.data.errors;
        
        if (validationErrors) {
          // Tüm hata mesajlarını birleştir
          const errorMessages = [];
          
          // Alan adlarını Türkçeleştir
          const fieldNames = {
            'title': 'Başlık',
            'description': 'Açıklama',
            'price': 'Fiyat',
            'duration': 'Süre',
            'start_location': 'Başlangıç Noktası',
            'start_date': 'Başlangıç Tarihi',
            'end_date': 'Bitiş Tarihi',
            'max_participants': 'Maksimum Katılımcı',
            'includes': 'Dahil Olanlar',
            'excludes': 'Dahil Olmayanlar',
            'category_id': 'Kategori',
            'name': 'İsim',
            'email': 'E-posta',
            'password': 'Şifre',
            'phone': 'Telefon',
            'address': 'Adres',
            'image': 'Resim',
            'featured_image': 'Öne Çıkan Resim',
            'slug': 'URL Adresi',
            'is_active': 'Aktif mi',
            'itinerary': 'Tur Programı',
            'included': 'Dahil Olanlar',
            'not_included': 'Dahil Olmayanlar',
            'firstname': 'Ad',
            'lastname': 'Soyad',
            'username': 'Kullanıcı Adı',
            'country': 'Ülke',
            'city': 'Şehir',
            'zip': 'Posta Kodu',
            'state': 'Eyalet/İl',
            'current_password': 'Mevcut Şifre',
            'new_password': 'Yeni Şifre',
            'password_confirmation': 'Şifre Tekrarı',
            'role_id': 'Kullanıcı Rolü',
            'status': 'Durum',
            'message': 'Mesaj',
            'subject': 'Konu',
            'content': 'İçerik'
          };
          
          for (const field in validationErrors) {
            if (validationErrors.hasOwnProperty(field)) {
              // Alan adını Türkçeleştir veya orijinal adını kullan
              const fieldName = fieldNames[field] || field;
              // Her alan için ilk hata mesajını al
              errorMessages.push(`<strong>${fieldName}</strong>: ${validationErrors[field][0]}`);
            }
          }
          
          // Birleştirilmiş hata mesajını error.response.data.message'a ekle
          error.response.data.message = errorMessages.join('<br>');
          
          console.log('Validasyon hatası:', error.response.data.message);
        } else {
          // Genel bir validasyon hatası mesajı ayarla
          error.response.data.message = 'Girdiğiniz bilgileri kontrol ediniz.';
        }
      } else if (error.response && error.response.status === 401) {
        if (window.showToast) {
          window.showToast({
            type: 'error',
            title: 'Oturum Hatası',
            message: 'Oturum süreniz doldu. Lütfen tekrar giriş yapın.'
          });
        }
        setTimeout(() => {
          window.location.href = '/login';
        }, 2000);
      } else if (error.response && error.response.status === 403) {
        if (window.showToast) {
          window.showToast({
            type: 'error',
            title: 'Yetki Hatası',
            message: 'Bu işlemi gerçekleştirmek için yetkiniz yok.'
          });
        }
      } else {
        if (window.showToast) {
          window.showToast({
            type: 'error',
            title: 'Hata',
            message: error.response?.data?.message || 'Beklenmeyen bir hata oluştu'
          });
        }
      }
    }
    
    return Promise.reject(error);
  }
);

// Sanctum için CSRF-cookie endpoint'ini baseURL'den hariç tut
const originalGet = axios.get;
axios.get = function(url, config = {}) {
  // Eğer sanctum/csrf-cookie endpoint'ine istek yapılıyorsa baseURL override edilsin
  if (url === '/sanctum/csrf-cookie') {
    config = { ...config, baseURL: '' }; // Boş baseURL kullanarak tam yolu yok say
    console.log('CSRF cookie isteği yapılıyor, tam URL:', url);
  }
  return originalGet(url, config);
};
