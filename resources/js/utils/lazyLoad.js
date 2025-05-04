// Gelişmiş Lazy Load Utility

// IntersectionObserver desteğini kontrol et
const isIntersectionObserverSupported = 'IntersectionObserver' in window;

// Görsel yükleme işlemi
function loadImage(element, src, srcset = null) {
  if (src) {
    element.src = src;
    
    // srcset varsa ekle (responsive görseller için)
    if (srcset) {
      element.srcset = srcset;
    }
    
    // Yükleme tamamlandığında loaded sınıfı ekle
    element.onload = () => {
      element.classList.add('loaded');
      // Yükleme sonrası blur efekti veya fade-in
      setTimeout(() => {
        element.style.opacity = '1';
        if (element.parentElement && element.parentElement.classList.contains('lazy-wrapper')) {
          element.parentElement.classList.add('loaded');
        }
      }, 50);
    };
  }
}

// Background image yükleme
function loadBackgroundImage(element, src) {
  if (src) {
    // Önce görsel önbelleğe alınır, sonra arka plan olarak ayarlanır
    const img = new Image();
    img.onload = () => {
      element.style.backgroundImage = `url(${src})`;
      element.classList.add('loaded');
      setTimeout(() => {
        element.style.opacity = '1';
      }, 50);
    };
    img.src = src;
  }
}

// Daha verimli IntersectionObserver - tek bir observer tüm elementleri izler
let globalImageObserver = null;
let globalBackgroundObserver = null;

// Tüm lazy-load elementlerini izleyecek observers kurulumu
function setupObservers() {
  if (!isIntersectionObserverSupported) return;
  
  // Görsel elementleri için observer
  if (!globalImageObserver) {
    globalImageObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const element = entry.target;
          const src = element.getAttribute('data-src');
          const srcset = element.getAttribute('data-srcset');
          
          loadImage(element, src, srcset);
          element.removeAttribute('data-src');
          element.removeAttribute('data-srcset');
          
          globalImageObserver.unobserve(element);
        }
      });
    }, {
      rootMargin: '200px 0px', // Daha geniş görüş alanı
      threshold: 0.01
    });
  }
  
  // Background görselleri için observer
  if (!globalBackgroundObserver) {
    globalBackgroundObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const element = entry.target;
          const src = element.getAttribute('data-background');
          
          loadBackgroundImage(element, src);
          element.removeAttribute('data-background');
          
          globalBackgroundObserver.unobserve(element);
        }
      });
    }, {
      rootMargin: '200px 0px',
      threshold: 0.01
    });
  }
}

// Tüm lazy-load elementlerini belge yüklendiğinde gözlemlemeye başla
export function setupLazyLoading() {
  setupObservers();
  
  // Observer desteklenmiyorsa, tüm görselleri hemen yükle
  if (!isIntersectionObserverSupported) {
    document.querySelectorAll('img[data-src]').forEach(img => {
      if (img.getAttribute('data-src')) {
        img.setAttribute('src', img.getAttribute('data-src'));
      }
    });
    document.querySelectorAll('[data-background]').forEach(element => {
      if (element.getAttribute('data-background')) {
        element.style.backgroundImage = `url(${element.getAttribute('data-background')})`;
      }
    });
    return;
  }

  // Tüm data-src özniteliği olan görselleri izle
  document.querySelectorAll('img[data-src]').forEach(img => {
    // Default bir placeholder göster
    if (!img.src || img.src === '') {
      img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }
    
    // Yükleme sırasında stil
    img.style.opacity = '0';
    img.style.transition = 'opacity 0.3s ease-in';
    
    globalImageObserver.observe(img);
  });

  // Tüm data-background özniteliği olan elementleri izle
  document.querySelectorAll('[data-background]').forEach(element => {
    element.style.opacity = '0';
    element.style.transition = 'opacity 0.3s ease-in';
    
    globalBackgroundObserver.observe(element);
  });
}

// Dinamik olarak eklenen yeni elementleri izle
export function observeLazyElement(element) {
  if (!element) return;
  
  setupObservers();
  
  if (element.tagName === 'IMG' && element.hasAttribute('data-src')) {
    // Default placeholder
    if (!element.src || element.src === '') {
      element.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    }
    
    element.style.opacity = '0';
    element.style.transition = 'opacity 0.3s ease-in';
    
    if (isIntersectionObserverSupported) {
      globalImageObserver.observe(element);
    } else {
      loadImage(element, element.getAttribute('data-src'), element.getAttribute('data-srcset'));
    }
  } else if (element.hasAttribute('data-background')) {
    element.style.opacity = '0';
    element.style.transition = 'opacity 0.3s ease-in';
    
    if (isIntersectionObserverSupported) {
      globalBackgroundObserver.observe(element);
    } else {
      loadBackgroundImage(element, element.getAttribute('data-background'));
    }
  }
  
  // Eğer konteyner içinde lazy-load elementleri varsa
  if (element.querySelectorAll) {
    // İç içe görselleri ara ve izle
    element.querySelectorAll('img[data-src]').forEach(img => {
      observeLazyElement(img);
    });
    
    // İç içe background elementlerini ara ve izle
    element.querySelectorAll('[data-background]').forEach(bgElement => {
      observeLazyElement(bgElement);
    });
  }
}

// Vue direktifi
const lazyLoad = {
  mounted: (el, binding) => {
    function loadImageDirective() {
      const imageElement = el;
      const src = binding.value;
      
      if (src) {
        imageElement.style.opacity = '0';
        imageElement.style.transition = 'opacity 0.3s ease-in';
        
        // Görsel için onload event
        imageElement.onload = () => {
          setTimeout(() => {
            imageElement.style.opacity = '1';
          }, 50);
        };
        
        // Eğer zaten src varsa, üzerine yazmayalım
        if (!imageElement.src || imageElement.src === 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7') {
          imageElement.src = src;
        }
      }
    }

    function handleIntersect(entries, observer) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          loadImageDirective();
          observer.unobserve(el);
        }
      });
    }

    // IntersectionObserver API varsa, gözlem yapalım
    if (window.IntersectionObserver) {
      const lazyImageObserver = new IntersectionObserver(handleIntersect, {
        rootMargin: '200px 0px',
        threshold: 0.01
      });
      
      lazyImageObserver.observe(el);
    } else {
      // Eski tarayıcılar için doğrudan yükleyelim
      loadImageDirective();
    }
  },
  beforeMount: (el, binding) => {
    // Resim henüz yüklenmemişse default placeholder göster
    if (!el.src || el.src === '') {
      el.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // 1x1 şeffaf GIF
    }
  }
};

// İçerik için lazy loading direktifi
const lazyContent = {
  mounted: (el, binding) => {
    // İçerik yükleme mantığı
    function loadContent() {
      // Binding değeri HTML veya URL olabilir
      const content = binding.value;
      
      if (typeof content === 'string') {
        if (content.startsWith('http') || content.startsWith('/')) {
          // URL ise fetch ile içeriği getir
          fetch(content)
            .then(response => response.text())
            .then(html => {
              el.innerHTML = html;
              el.style.opacity = '1';
            })
            .catch(error => console.error('Lazy content loading error:', error));
        } else {
          // Direkt HTML ise içeriğe yerleştir
          el.innerHTML = content;
          el.style.opacity = '1';
        }
      }
    }
    
    function handleIntersect(entries, observer) {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          loadContent();
          observer.unobserve(el);
        }
      });
    }
    
    // Stil ayarları
    el.style.opacity = '0';
    el.style.transition = 'opacity 0.3s ease-in';
    
    // IntersectionObserver ile izle
    if (window.IntersectionObserver) {
      const lazyContentObserver = new IntersectionObserver(handleIntersect, {
        rootMargin: '200px 0px',
        threshold: 0.01
      });
      
      lazyContentObserver.observe(el);
    } else {
      // Observer desteklenmiyorsa doğrudan yükle
      loadContent();
    }
  }
};

// Vue plugin
export default {
  install: (app) => {
    app.directive('lazy', lazyLoad);
    app.directive('lazy-content', lazyContent);
    
    // Sayfa yüklendiğinde global lazy loading başlat
    if (typeof window !== 'undefined') {
      window.addEventListener('load', () => {
        setupLazyLoading();
      });
      
      // Sayfa scroll olduğunda otomatik yeniden kontrol et (bazı durumlarda gerekebilir)
      window.addEventListener('scroll', () => {
        if (globalImageObserver || globalBackgroundObserver) {
          // Boş - observers zaten işlem yapacak
        }
      }, { passive: true }); // Passive event listener performans için
    }
  }
}; 