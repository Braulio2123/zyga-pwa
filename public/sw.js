const CACHE_VERSION = 'v2';
const STATIC_CACHE = `zyga-static-${CACHE_VERSION}`;

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
];

self.addEventListener('install', (event) => {
    event.waitUntil((async () => {
        const cache = await caches.open(STATIC_CACHE);

        await Promise.allSettled(
            STATIC_ASSETS.map(async (asset) => {
                try {
                    await cache.add(new Request(asset, { cache: 'reload' }));
                } catch (error) {
                    console.warn('[ZYGA SW] No se pudo precachear:', asset, error);
                }
            })
        );

        await self.skipWaiting();
    })());
});

self.addEventListener('activate', (event) => {
    event.waitUntil((async () => {
        const keys = await caches.keys();

        await Promise.all(
            keys
                .filter((key) => key !== STATIC_CACHE)
                .map((key) => caches.delete(key))
        );

        await self.clients.claim();
    })());
});

self.addEventListener('fetch', (event) => {
    const { request } = event;

    if (request.method !== 'GET') {
        return;
    }

    const url = new URL(request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    // Nunca cachear llamadas a la API.
    if (url.pathname.startsWith('/api/')) {
        return;
    }

    // Nunca cachear documentos HTML para evitar vistas viejas o sesiones inconsistentes.
    if (isNavigationRequest(request)) {
        return;
    }

    // Solo cachear assets estáticos.
    if (!isStaticAsset(url.pathname)) {
        return;
    }

    event.respondWith(staleWhileRevalidate(request));
});

function isNavigationRequest(request) {
    return (
        request.mode === 'navigate' ||
        request.destination === 'document' ||
        request.headers.get('accept')?.includes('text/html')
    );
}

function isStaticAsset(pathname) {
    if (STATIC_ASSETS.includes(pathname)) {
        return true;
    }

    return /\.(css|js|png|jpg|jpeg|svg|webp|ico|woff|woff2)$/i.test(pathname);
}

async function staleWhileRevalidate(request) {
    const cache = await caches.open(STATIC_CACHE);
    const cached = await cache.match(request);

    const networkPromise = fetch(request)
        .then((response) => {
            if (response && response.ok && response.type !== 'opaque') {
                cache.put(request, response.clone()).catch(() => {});
            }

            return response;
        })
        .catch(() => null);

    if (cached) {
        networkPromise.catch(() => {});
        return cached;
    }

    const networkResponse = await networkPromise;

    if (networkResponse) {
        return networkResponse;
    }

    return new Response('', {
        status: 504,
        statusText: 'Offline or unreachable asset',
    });
}
