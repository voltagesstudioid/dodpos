const CACHE_NAME = 'dodpos-pwa-v1';
const ASSETS_TO_CACHE = [
    '/mobile/pos',
    '/manifest.json',
    // In a real app, include your compiled CSS and JS paths here:
    // '/build/assets/app.css',
    // '/build/assets/app.js'
];

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
            );
        })
    );
});

self.addEventListener('fetch', (event) => {
    // Only cache GET requests
    if (event.request.method !== 'GET') return;

    // Cache-First Strategy for Offline POS
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request).then((networkResponse) => {
                // Return fresh network response
                return networkResponse;
            }).catch(() => {
                // If network fails and it's an HTML page, return the pos cache if possible
                if (event.request.headers.get('accept').includes('text/html')) {
                    return caches.match('/mobile/pos');
                }
            });
        })
    );
});
