const ZYGA_CACHE = 'zyga-shell-v1';
const STATIC_ASSETS = [
  '/manifest.webmanifest',
  '/favicon.ico',
  '/icons/apple-touch-icon.png',
  '/icons/icon-192.png',
  '/icons/icon-512.png',
  '/images/logo.png',
  '/css/auth-client.css',
  '/css/auth-custom.css',
  '/css/register.css',
  '/css/user-client-portal.css',
  '/css/admin-custom.css',
  '/js/user-client-portal.js',
  '/js/user-pwa.js'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(ZYGA_CACHE).then(async (cache) => {
      for (const asset of STATIC_ASSETS) {
        try {
          await cache.add(asset);
        } catch (error) {
          console.warn('[ZYGA SW] asset not cached:', asset, error);
        }
      }
    })
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => Promise.all(
      keys.filter((key) => key !== ZYGA_CACHE).map((key) => caches.delete(key))
    ))
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;

  const request = event.request;
  const acceptsHtml = request.headers.get('accept')?.includes('text/html');

  if (acceptsHtml) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          const clone = response.clone();
          caches.open(ZYGA_CACHE).then((cache) => cache.put(request, clone)).catch(() => {});
          return response;
        })
        .catch(() => caches.match(request))
    );
    return;
  }

  event.respondWith(
    caches.match(request).then((cached) => {
      if (cached) return cached;
      return fetch(request).then((response) => {
        if (!response || response.status !== 200 || response.type === 'opaque') {
          return response;
        }
        const clone = response.clone();
        caches.open(ZYGA_CACHE).then((cache) => cache.put(request, clone)).catch(() => {});
        return response;
      });
    })
  );
});
