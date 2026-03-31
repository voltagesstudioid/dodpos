// Service Worker for DODPOS Sales PWA
const CACHE_NAME = 'dodpos-sales-v1';
const STATIC_ASSETS = [
    '/',
    '/sales',
    '/sales/dashboard',
    '/sales/menu',
    '/login',
    '/css/app.css',
    '/js/app.js',
];

// API endpoints to cache with network-first strategy
const API_ENDPOINTS = [
    '/api/v1/minyak/dashboard',
    '/api/v1/minyak/pelanggan',
    '/api/v1/minyak/produk',
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .catch((err) => console.log('Cache failed:', err))
    );
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Handle API requests
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(networkFirst(request));
        return;
    }

    // Handle navigation requests
    if (request.mode === 'navigate') {
        event.respondWith(
            caches.match('/sales/dashboard')
                .then((response) => response || fetch(request))
        );
        return;
    }

    // Default: Cache first, then network
    event.respondWith(cacheFirst(request));
});

// Cache first strategy
async function cacheFirst(request) {
    const cache = await caches.open(CACHE_NAME);
    const cached = await cache.match(request);
    
    if (cached) {
        return cached;
    }
    
    try {
        const response = await fetch(request);
        if (response.ok) {
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        console.log('Fetch failed:', error);
        return new Response('Offline', { status: 503 });
    }
}

// Network first strategy
async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cache = await caches.open(CACHE_NAME);
        const cached = await cache.match(request);
        
        if (cached) {
            return cached;
        }
        
        return new Response(JSON.stringify({ error: 'Offline' }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Background sync
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-penjualan') {
        event.waitUntil(syncPenjualan());
    } else if (event.tag === 'sync-setoran') {
        event.waitUntil(syncSetoran());
    } else if (event.tag === 'sync-kunjungan') {
        event.waitUntil(syncKunjungan());
    }
});

async function syncPenjualan() {
    // Sync pending penjualan
    console.log('Syncing penjualan...');
}

async function syncSetoran() {
    console.log('Syncing setoran...');
}

async function syncKunjungan() {
    console.log('Syncing kunjungan...');
}

// Push notifications
self.addEventListener('push', (event) => {
    const options = {
        body: event.data?.text() || 'New notification',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
    };
    
    event.waitUntil(
        self.registration.showNotification('DODPOS Sales', options)
    );
});
