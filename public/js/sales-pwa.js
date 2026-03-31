/**
 * DODPOS Sales PWA - Offline Data Management
 */

class SalesPWA {
    constructor() {
        this.dbName = 'DODPOS_Offline';
        this.dbVersion = 1;
        this.db = null;
        this.init();
    }

    async init() {
        try {
            this.db = await this.openDB();
            console.log('SalesPWA: Database initialized');
        } catch (error) {
            console.error('SalesPWA: Failed to init DB', error);
        }
    }

    openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);
            
            request.onerror = () => reject(request.error);
            request.onsuccess = () => resolve(request.result);
            
            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                
                // Stores for offline data
                if (!db.objectStoreNames.contains('pelanggan')) {
                    db.createObjectStore('pelanggan', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('produk')) {
                    db.createObjectStore('produk', { keyPath: 'id' });
                }
                if (!db.objectStoreNames.contains('penjualan_pending')) {
                    const store = db.createObjectStore('penjualan_pending', { keyPath: 'localId' });
                    store.createIndex('syncStatus', 'syncStatus', { unique: false });
                }
                if (!db.objectStoreNames.contains('setoran_pending')) {
                    db.createObjectStore('setoran_pending', { keyPath: 'localId' });
                }
                if (!db.objectStoreNames.contains('kunjungan_pending')) {
                    db.createObjectStore('kunjungan_pending', { keyPath: 'localId' });
                }
            };
        });
    }

    // Save data offline
    async saveOffline(data, storeName) {
        if (!this.db) return;
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.put(data);
            
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    // Get offline data
    async getOfflineData(storeName) {
        if (!this.db) return [];
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readonly');
            const store = transaction.objectStore(storeName);
            const request = store.getAll();
            
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    // Delete offline data after sync
    async deleteOffline(localId, storeName) {
        if (!this.db) return;
        
        return new Promise((resolve, reject) => {
            const transaction = this.db.transaction([storeName], 'readwrite');
            const store = transaction.objectStore(storeName);
            const request = store.delete(localId);
            
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    // Get pending sync count
    async getPendingCount() {
        if (!this.db) return 0;
        
        const stores = ['penjualan_pending', 'setoran_pending', 'kunjungan_pending'];
        let count = 0;
        
        for (const storeName of stores) {
            const data = await this.getOfflineData(storeName);
            count += data.filter(item => item.syncStatus === 'pending').length;
        }
        
        return count;
    }

    // Sync all pending data
    async syncAllData() {
        const division = document.querySelector('meta[name="user-division"]')?.content || 'minyak';
        const stores = ['penjualan_pending', 'setoran_pending', 'kunjungan_pending'];
        
        for (const storeName of stores) {
            const pending = await this.getOfflineData(storeName);
            const toSync = pending.filter(item => item.syncStatus === 'pending');
            
            for (const item of toSync) {
                try {
                    let endpoint = '';
                    if (storeName === 'penjualan_pending') endpoint = `/api/v1/${division}/penjualan`;
                    else if (storeName === 'setoran_pending') endpoint = `/api/v1/${division}/setoran`;
                    else if (storeName === 'kunjungan_pending') endpoint = `/api/v1/${division}/kunjungan`;
                    
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify(item)
                    });
                    
                    if (response.ok) {
                        await this.deleteOffline(item.localId, storeName);
                    }
                } catch (error) {
                    console.error(`Failed to sync ${item.localId}:`, error);
                }
            }
        }
        
        return true;
    }

    // Cache data for offline use
    async cacheData(storeName, dataArray) {
        if (!this.db) return;
        
        // Clear old cache
        const oldData = await this.getOfflineData(storeName);
        for (const item of oldData) {
            await this.deleteOffline(item.id || item.localId, storeName);
        }
        
        // Save new data
        for (const item of dataArray) {
            await this.saveOffline(item, storeName);
        }
    }
}

// Register Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw-sales.js')
            .then((registration) => {
                console.log('SW registered:', registration);
            })
            .catch((error) => {
                console.log('SW registration failed:', error);
            });
    });
}

// Initialize SalesPWA
window.salesPWA = new SalesPWA();

// Online/Offline event handlers
window.addEventListener('online', () => {
    console.log('App is online');
    document.getElementById('offline-indicator')?.classList.remove('show');
    window.salesPWA.syncAllData();
});

window.addEventListener('offline', () => {
    console.log('App is offline');
    document.getElementById('offline-indicator')?.classList.add('show');
});

// Check initial online status
if (!navigator.onLine) {
    document.getElementById('offline-indicator')?.classList.add('show');
}

// Update pending count badge
async function updatePendingCount() {
    const count = await window.salesPWA.getPendingCount();
    const badge = document.getElementById('pending-count');
    const btn = document.getElementById('sync-btn');
    
    if (badge) badge.textContent = count;
    if (btn) btn.classList.toggle('hidden', count === 0);
}

// Call periodically
setInterval(updatePendingCount, 5000);
updatePendingCount();
