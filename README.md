# Tour Project

## Proje Hakkında

Bu proje, tur rezervasyonu ve yönetimi için geliştirilmiş kapsamlı bir web uygulamasıdır. Kullanıcılar sisteme kayıt olabilir, turları inceleyebilir, rezervasyon yapabilir ve değerlendirme yazabilirler. Ayrıca admin paneli ile turlar, rezervasyonlar ve kullanıcılar yönetilebilir. Sadece online ödeme ile alakalı Stripe veya PayTR tarzı bir entegrasyon henüz yapılmadı yapılmama sebebi projenin örnek proje olması amaçlandı. (Fakat Entegrasyonu yapılacak.) Şimdilik iletişim sayfamızla alakalı backend kodu yazılmadı!

## Kullanılan Teknolojiler

### Backend
- **Laravel 12.x** - PHP MVC Framework
- **MySQL** - Veritabanı 
- **Sanctum** - API Kimlik Doğrulama

### Frontend
- **Vue.js 3** - JavaScript Framework
- **Vue Router** - Client-side routing
- **Tailwind CSS** - CSS Framework
- **Axios** - HTTP istekleri için

## Kurulum

### Gereksinimler
- PHP 8.1+
- MySQL 5.7+
- Composer
- Node.js ve NPM

### Adımlar


1. Bağımlılıkları yükleyin
```
composer install
npm install
```

2. `.env` dosyasını ayarlayın
```
cp .env.example .env
```
Dosyada veritabanı bilgilerinizi güncelleyin:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tur_projesi
DB_USERNAME=root
DB_PASSWORD=
```


## Uygulamayı Çalıştırma

### Development Ortamında

1. Laravel backend'i başlatın
```
php artisan serve
```

2. Vue frontend'i çalıştırın
```
npm run dev
```

3. Tarayıcınızda aşağıdaki adresleri kullanarak uygulamaya erişebilirsiniz:
http://localhost:8000

### Üretim Ortamında

1. Frontend varlıklarını derleyin
```
npm run build
```

2. Sunucunuzu yapılandırın (Apache veya Nginx)

3. `.env` dosyasını düzenleyin
```
APP_ENV=production
APP_DEBUG=false
```

## Yetkiler

### Admin Kullanıcısı
- Tüm turları yönetebilir
- Tüm rezervasyonları görebilir ve düzenleyebilir
- Kullanıcıları yönetebilir
- Raporları görüntüleyebilir

### Normal Kullanıcı
- Turları görüntüleyebilir
- Rezervasyon yapabilir
- Değerlendirme yazabilir
- Kendi profilini düzenleyebilir

## Proje Yapısı

- **app/** - Laravel ana kodları
  - **Http/Controllers/** - Controller sınıfları
  - **Models/** - Veritabanı model sınıfları
- **resources/js/** - Vue.js frontend kodları
  - **components/** - Vue bileşenleri
  - **pages/** - Ana sayfa bileşenleri
  - **router/** - Vue Router konfigürasyonu
- **routes/** - API ve web rotaları
- **database/migrations/** - Veritabanı tablo tanımları
