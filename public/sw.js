// Service Worker - Cache and Network Strategies

const CACHE_NAME = 'tural-cache-v1';
const STATIC_ASSETS = [
  '/',
  '/favicon.ico',
  '/images/payment/visa.png',
  '/images/payment/mastercard.png',
  '/images/payment/troy.png',
  '/images/tours/efes.jpg',
  '/images/tours/pamukkale.jpg',
  '/images/tours/goreme.jpg'
];


self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Önbelleğe statik varlıklar kaydediliyor');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});


self.addEventListener('activate', event => {
  const currentCaches = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
    }).then(cachesToDelete => {
      return Promise.all(cachesToDelete.map(cacheToDelete => {
        return caches.delete(cacheToDelete);
      }));
    }).then(() => self.clients.claim())
  );
});


self.addEventListener('fetch', event => {
 
  if (event.request.method !== 'GET') return;
  
  
  if (event.request.url.includes('/api/')) {
    return;
  }
  
  
  if (
    event.request.url.match(/\.(jpeg|jpg|png|gif|svg|ico)$/) ||
    event.request.url.match(/\.(css|js)$/)
  ) {
    event.respondWith(cacheFirst(event.request));
    return;
  }
  
  
  event.respondWith(networkFirst(event.request));
});


async function cacheFirst(request) {
  const cachedResponse = await caches.match(request);
  if (cachedResponse) {
    return cachedResponse;
  }
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    console.error('Fetch hatası:', error);
   
    return new Response('Offline', { status: 408, headers: { 'Content-Type': 'text/plain' } });
  }
}


async function networkFirst(request) {
  try {
    const networkResponse = await fetch(request);
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    return networkResponse;
  } catch (error) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    return new Response('Offline', { status: 408, headers: { 'Content-Type': 'text/plain' } });
  }
} 