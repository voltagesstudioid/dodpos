<div class="tr-preview-toolbar">
    <div class="tr-preview-container">
        <div class="tr-preview-title">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 9V2h12v7"></path>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Mode Pratinjau Cetak: {{ $title ?? 'Dokumen' }}
        </div>
        <div class="tr-preview-actions">
            <!-- Jika tidak support print langsung (browser tertentu), window.print() dipanggil lewat js -->
            <button onclick="window.print()" class="tr-btn-print">
                🖨️ Cetak Sekarang
            </button>
            <button onclick="window.close()" class="tr-btn-close">
                Tutup Tab
            </button>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap');

    .tr-preview-toolbar {
        background: #0f172a;
        color: #f8fafc;
        padding: 14px 0;
        position: sticky;
        top: 0;
        z-index: 999999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.25);
        border-bottom: 2px solid #3b82f6;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .tr-preview-container {
        max-width: 100%;
        margin: 0;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .tr-preview-title {
        font-size: 1rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.02em;
    }
    .tr-preview-title svg {
        color: #60a5fa;
    }
    .tr-preview-actions {
        display: flex;
        gap: 12px;
    }
    .tr-btn-print, .tr-btn-close {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 8px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .tr-btn-print {
        background: #3b82f6;
        color: white;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    .tr-btn-print:hover { 
        background: #2563eb; 
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
    }
    
    .tr-btn-close {
        background: #334155;
        color: #cbd5e1;
    }
    .tr-btn-close:hover { 
        background: #475569; 
        color: white;
    }

    @media print {
        .tr-preview-toolbar { display: none !important; }
    }
</style>
