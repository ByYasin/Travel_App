## [Döviz kurları alınamadı. Lütfen daha sonra tekrar deneyin. Ücretsiz API limitleri aşılmış olabilir.]
Çözüm: Döviz çevirici bileşeninde, kullanılan API servislerine yapılan isteklerde CORS (Cross-Origin Resource Sharing) hatası ve/veya ücretsiz API limitlerinin aşılması nedeniyle bu sorun oluşuyor.

Çözüm aşağıdaki adımları içerir:
1. Laravel backend'inde bir proxy controller oluştur
2. Önbellek (caching) mekanizması ekleyerek API çağrılarını azalt (günde bir kez)
3. Varsayılan döviz kurları ekleyerek API'ler çalışmadığında bile uygulamanın çalışmasını sağla
4. Frontend'de düzgün hata yönetimi ve bilgilendirme mesajları ekle

```php
// CurrencyController.php
public function getExchangeRates(Request $request)
{
    // Cache anahtarı
    $cacheKey = 'exchange_rates_' . date('Y-m-d');
    
    // Cache'de varsa direkt döndür (24 saat geçerli)
    if (Cache::has($cacheKey)) {
        return response()->json(Cache::get($cacheKey));
    }
    
    try {
        $base = $request->input('base', 'EUR');
        $symbols = $request->input('symbols', 'USD,TRY');
        
        // 1. ExchangeRate-API (ücretsiz)
        $response = Http::get("https://api.exchangerate-api.com/v4/latest/{$base}");
        if ($response->successful() && isset($response['rates'])) {
            $data = $response->json();
            Cache::put($cacheKey, $data, now()->addHours(24));
            return response()->json($data);
        }
        
        // 2. Fixer.io API (ücretsiz sürüm)
        $fixerResponse = Http::get('http://data.fixer.io/api/latest', [
            'access_key' => 'ÜCRETSİZ_API_ANAHTARI',
        ]);
        if ($fixerResponse->successful() && isset($fixerResponse['rates'])) {
            $data = $fixerResponse->json();
            return response()->json($fixerResponse->json());
        }
        
        // 3. Currency API (tamamen ücretsiz)
        $currencyApiResponse = Http::get('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/eur.json');
        if ($currencyApiResponse->successful()) {
            return response()->json([
                'base' => 'EUR',
                'date' => date('Y-m-d'),
                'rates' => $currencyApiResponse->json()['eur']
            ]);
        }
        
        // 4. Frankfurter API (tamamen ücretsiz)
        $frankfurterResponse = Http::get("https://api.frankfurter.app/latest", [
            'from' => $base
        ]);
        if ($frankfurterResponse->successful()) {
            return response()->json($frankfurterResponse->json());
        }

        // Tüm API'ler başarısız olursa, varsayılan döviz kurlarını döndür
        return $this->getDefaultRates();

    } catch (\Exception $e) {
        return $this->getDefaultRates();
    }
}

// Varsayılan döviz kurları
private function getDefaultRates()
{
    // Güncel tarih ve yaklaşık kurlar
    $defaultRates = [
        'base' => 'EUR',
        'date' => date('Y-m-d'),
        'rates' => [
            'USD' => 1.07,
            'TRY' => 34.5,
            'GBP' => 0.85,
            'JPY' => 165.5,
            'EUR' => 1.0
        ]
    ];
    
    return response()->json($defaultRates);
}

// routes/api.php
Route::get('/exchange-rates', [CurrencyController::class, 'getExchangeRates']);
```

Frontend tarafında varsayılan kurların kullanılıp kullanılmadığını tespit etme ve kullanıcıya bildirme:

```javascript
// Durum değişkeni ekle
const usingDefaultRates = ref(false)

// API çağrısında
const fetchExchangeRates = async () => {
  try {
    // API çağrısı...
    
    // Varsayılan kurların kullanılıp kullanılmadığını kontrol et
    if (rates.TRY === 34.5 && rates.USD === 1.07) {
      usingDefaultRates.value = true
    }
    
  } catch (err) {
    // Hata mesajı...
  }
}
```

Template içinde bilgilendirme mesajı:

```html
<div v-if="usingDefaultRates" class="text-amber-500 dark:text-amber-400 text-sm">
  Not: Yaklaşık kurlar gösteriliyor (API'lere erişilemedi)
</div>
```

Bu çözüm sayesinde:
1. Döviz kurları günde bir kez API'den alınıp önbelleğe kaydedilir
2. API'ler çalışmasa bile kullanıcı yaklaşık kurlarla işlem yapabilir
3. Kullanıcı API çağrısının başarısız olduğu durumda bilgilendirilir
4. Ücretsiz API limitleri aşılsa bile uygulama çalışmaya devam eder

```javascript
// API değişimi
const response = await axios.get('/api/exchange-rates', {
  params: {
    base: 'EUR',
    symbols: 'USD,TRY'
  }
})

// Yedek API kullanımı
if (birinci_api_başarısız) {
  const backupResponse = await axios.get('/api/exchange-rates', {
    params: {
      base: 'EUR',
      symbols: 'USD,TRY'
    }
  })
  // İşleme devam et...
}

// Hesaplamada iyileştirmeler
const rate = exchangeRates.value[fromCurrency.value][toCurrency.value] || 1
```

## [Pinia auth store veya localStorage persistence sorunu]
Çözüm: Pinia store kullanımında verilerin localStorage'da doğru şekilde saklanmaması veya sayfa yenilendiğinde kaybolması sorununu çözmek için aşağıdaki adımları uyguladık:

1. pinia-plugin-persistedstate eklentisini kurduk:
```bash
npm install pinia-plugin-persistedstate
```

2. main.js dosyasında Pinia'ya persist eklentisini ekledik:
```javascript
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate';
import App from './App.vue';
import router from './router';

const app = createApp(App);
const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(pinia);
app.use(router);
app.mount('#app');
```

3. Auth store'u options API formatında persist özelliği ile güncelledik:
```javascript
export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    loading: false,
    error: null,
  }),
  
  persist: {
    key: 'auth',
    storage: localStorage,
    paths: ['user'], // Sadece user state'ini kalıcı yap
  },
  
  getters: {
    isLoggedIn: (state) => !!state.user,
    isAdmin: (state) => state.user?.role?.name === 'admin',
    userData: (state) => state.user,
  },
  
  actions: {
    // Metodlar...
  }
})
```

4. Storage listener ekleyerek farklı sekmelerde senkronizasyon sağladık:
```javascript
// Kimlik doğrulama durumunu başlat
initAuth() {
  // Diğer kodlar...
  
  // Farklı sekmelerde localStorage değişikliklerini dinle
  window.addEventListener('storage', (event) => {
    if (event.key === 'auth') {
      if (event.newValue) {
        try {
          const data = JSON.parse(event.newValue);
          // Başka bir sekmede kullanıcı girişi yapıldıysa güncelle
          if (data.user && !this.user) {
            this.user = data.user;
            console.log('Auth Store: Farklı bir sekmede oturum açıldı');
          }
        } catch (error) {
          console.error('Storage event veri çözme hatası:', error);
        }
      } else {
        // Başka bir sekmede çıkış yapıldıysa temizle
        this.user = null;
        console.log('Auth Store: Farklı bir sekmede oturum kapatıldı');
      }
    }
  });
}
```

Bu değişiklikler sayesinde:
- Pinia store'un içeriği otomatik olarak localStorage'a kaydedilir
- Sayfa yenilendiğinde veriler korunur
- Farklı sekmeler arasında oturum durumu senkronize olur
- Pinia'nın sağladığı reaktiflik özellikleri korunur
- Kod daha temiz ve bakımı kolay hale gelir
- Manuel localStorage yönetimi ihtiyacı ortadan kalkar

Not: Eğer eski localStorage formatından yeni formata geçiş yapıyorsanız, aşağıdaki kod ile bu geçişi sağlayabilirsiniz:

```javascript
// Eski localStorage formatından yeni formata geçiş
const legacyUser = localStorage.getItem('user');
if (legacyUser) {
  try {
    const userData = JSON.parse(legacyUser);
    this.user = userData; // Yeni persist formatına aktar
    localStorage.removeItem('user'); // Eski veriyi temizle
    console.log('Eski localStorage formatından veri aktarıldı');
  } catch (e) {
    console.error('Eski user verisi çözümlenemedi:', e);
    localStorage.removeItem('user');
  }
}
```
