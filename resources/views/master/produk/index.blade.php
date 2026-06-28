<x-app-layout>
    <x-slot name="header">Master Produk</x-slot>
    <style>
        /* ── MASTER PRODUK REDESIGN ── */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        /* Hero Header */
        .mp-hero {
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 40%, #24243e 100%);
            border-radius: 20px;
            padding: 2rem 2rem 3.5rem;
            margin-bottom: -2rem;
            position: relative;
            overflow: hidden;
        }
        .mp-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .mp-hero::after {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 320px; height: 320px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(139,92,246,0.3) 0%, transparent 70%);
        }
        .mp-hero-inner { position: relative; z-index: 1; }
        .mp-breadcrumb {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.72rem; font-weight: 600; color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 1rem;
        }
        .mp-breadcrumb-sep { color: rgba(255,255,255,0.25); }
        .mp-hero-title-row { display: flex; align-items: center; gap: 1rem; }
        .mp-hero-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border-radius: 16px; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 32px rgba(139,92,246,0.5);
            flex-shrink: 0;
        }
        .mp-hero-title {
            font-size: 2rem; font-weight: 900; color: #fff;
            letter-spacing: -0.04em; line-height: 1.1;
        }
        .mp-hero-subtitle {
            font-size: 0.875rem; color: rgba(255,255,255,0.55);
            margin-top: 0.5rem; font-weight: 400;
        }

        /* Floating Stat Cards */
        .mp-stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
            position: relative;
            z-index: 10;
            margin-bottom: 1.5rem;
        }
        .mp-stat {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.375rem;
            border: 1px solid rgba(226,232,240,0.8);
            box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
            transition: all 0.3s cubic-bezier(0.34,1.56,.64,1);
            cursor: default;
            position: relative;
            overflow: hidden;
        }
        .mp-stat::before {
            content: '';
            position: absolute;
            bottom: -20px; right: -20px;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.08;
            transition: transform 0.3s ease;
        }
        .mp-stat:hover { transform: translateY(-4px); box-shadow: 0 20px 48px rgba(0,0,0,0.12), 0 4px 12px rgba(0,0,0,0.06); }
        .mp-stat:hover::before { transform: scale(1.3); }
        .mp-stat.s-indigo::before  { background: #4f46e5; }
        .mp-stat.s-emerald::before { background: #059669; }
        .mp-stat.s-amber::before   { background: #d97706; }
        .mp-stat.s-rose::before    { background: #e11d48; }
        .mp-stat.s-blue::before    { background: #2563eb; }

        .mp-stat-icon-wrap {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 0.875rem;
        }
        .s-indigo .mp-stat-icon-wrap  { background: linear-gradient(135deg, #eef2ff, #e0e7ff); color: #4f46e5; }
        .s-emerald .mp-stat-icon-wrap { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #059669; }
        .s-amber .mp-stat-icon-wrap   { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #d97706; }
        .s-rose .mp-stat-icon-wrap    { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #e11d48; }
        .s-blue .mp-stat-icon-wrap    { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #2563eb; }

        .mp-stat-label {
            font-size: 0.65rem; font-weight: 700; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.25rem;
        }
        .mp-stat-value {
            font-size: 2rem; font-weight: 900; letter-spacing: -0.04em; line-height: 1;
        }
        .s-indigo  .mp-stat-value { color: #4f46e5; }
        .s-emerald .mp-stat-value { color: #059669; }
        .s-amber   .mp-stat-value { color: #d97706; }
        .s-rose    .mp-stat-value { color: #e11d48; }
        .s-blue    .mp-stat-value { color: #2563eb; }

        /* Tab Navigation */
        .mp-tab-bar {
            display: flex; gap: 4px;
            background: #f1f5f9;
            border-radius: 14px;
            padding: 4px;
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }
        .mp-tab-btn {
            display: flex; align-items: center; gap: 0.625rem;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            border: none; background: transparent; cursor: pointer;
            font-family: inherit; font-size: 0.8125rem; font-weight: 600;
            color: #64748b; white-space: nowrap;
            transition: all 0.2s; user-select: none;
        }
        .mp-tab-btn:hover { color: #334155; background: rgba(255,255,255,0.6); }
        .mp-tab-btn.active {
            background: #fff; color: #4f46e5;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.05);
        }
        .mp-tab-icon { font-size: 1rem; line-height: 1; }
        .mp-tab-count {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 22px; height: 22px; padding: 0 7px;
            border-radius: 99px; font-size: 0.6875rem; font-weight: 700;
            background: #e2e8f0; color: #64748b;
            transition: all 0.2s;
        }
        .mp-tab-btn.active .mp-tab-count { background: #eef2ff; color: #4f46e5; }

        /* Panel */
        .mp-panel {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
            overflow: hidden;
        }

        /* Toolbar */
        .mp-toolbar {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem 1.375rem;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            background: linear-gradient(180deg, #fdfdfe, #f9fbfd);
        }
        .mp-search-wrap {
            position: relative; flex: 1; min-width: 180px; max-width: 320px;
        }
        .mp-search-wrap svg {
            position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; pointer-events: none; flex-shrink: 0;
        }
        .mp-search-input {
            width: 100%; height: 36px;
            padding: 0 0.875rem 0 2.25rem;
            border-radius: 99px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            font-size: 0.8125rem; font-family: inherit; color: #1e293b;
            outline: none; transition: all 0.2s;
        }
        .mp-search-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .mp-search-input::placeholder { color: #94a3b8; }

        .mp-filter-select {
            height: 36px; padding: 0 0.875rem; border-radius: 99px;
            border: 1.5px solid #e2e8f0; background: #fff;
            font-size: 0.8125rem; font-family: inherit; color: #374151;
            outline: none; cursor: pointer; transition: all 0.2s;
            min-width: 150px;
        }
        .mp-filter-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

        .mp-check-label {
            display: flex; align-items: center; gap: 0.4rem;
            cursor: pointer; font-size: 0.75rem; font-weight: 600;
            color: #e11d48; white-space: nowrap; padding: 0.375rem 0.875rem;
            border-radius: 99px; border: 1.5px solid #fecdd3;
            background: #fff1f2; transition: all 0.2s;
        }
        .mp-check-label:hover { background: #ffe4e6; }
        .mp-check-label input { accent-color: #e11d48; }

        .mp-add-btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0 1.125rem; height: 36px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #fff; font-family: inherit; font-size: 0.8125rem; font-weight: 700;
            border: none; border-radius: 99px; cursor: pointer;
            box-shadow: 0 4px 14px rgba(79,70,229,0.35);
            transition: all 0.25s cubic-bezier(0.34,1.56,.64,1);
            white-space: nowrap; text-decoration: none;
        }
        .mp-add-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(79,70,229,0.4); color: #fff; }
        .mp-add-btn svg { flex-shrink: 0; }

        .mp-import-btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0 1.125rem; height: 36px;
            background: #fff; border: 1.5px solid #e2e8f0;
            color: #475569; font-family: inherit; font-size: 0.8125rem; font-weight: 700;
            border-radius: 99px; cursor: pointer; text-decoration: none;
            transition: all 0.2s; white-space: nowrap;
        }
        .mp-import-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #334155; transform: translateY(-1px); }
        .mp-import-btn svg { flex-shrink: 0; }
        
        .mp-toolbar-actions {
            margin-left: auto;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Table Enhancements */
        .mp-table { width: 100%; min-width: 620px; border-collapse: separate; border-spacing: 0; }
        .mp-table th {
            background: linear-gradient(180deg, #f8fafc, #f3f6fb);
            color: #475569; font-size: 0.6875rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.07em;
            padding: 0.75rem 1rem; text-align: left;
            border-bottom: 2px solid #e8edf5; white-space: nowrap;
        }
        .mp-table td {
            padding: 0.9375rem 1rem; border-bottom: 1px solid #f4f8fc;
            font-size: 0.8125rem; color: #374151; vertical-align: middle;
        }
        .mp-table tbody tr:last-child td { border-bottom: none; }
        .mp-table tbody tr { transition: background 0.15s; }
        .mp-table tbody tr:hover td { background: linear-gradient(90deg, #fafbff, #f8f9ff); }

        /* Product cell */
        .mp-prod-name { font-weight: 700; color: #0f172a; font-size: 0.875rem; }
        .mp-prod-sku {
            display: inline-flex; align-items: center;
            background: #eef2ff; color: #4338ca;
            padding: 0.1rem 0.45rem; border-radius: 5px;
            font-size: 0.625rem; font-weight: 700; margin-top: 3px;
            letter-spacing: 0.03em;
        }
        .mp-prod-barcode { font-family: monospace; font-size: 0.6875rem; color: #94a3b8; }

        /* Stock indicator */
        .mp-stock-wrap { display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .mp-stock-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 0.25rem 0.75rem; border-radius: 99px;
            font-size: 0.72rem; font-weight: 700;
        }
        .mp-stock-badge.ok { background: #dcfce7; color: #15803d; }
        .mp-stock-badge.warn { background: #fef3c7; color: #92400e; }
        .mp-stock-badge.low { background: #fee2e2; color: #b91c1c; }
        .mp-stock-bar { width: 72px; height: 4px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
        .mp-stock-bar-fill { height: 100%; border-radius: 99px; transition: width 0.5s ease; }
        .mp-stock-bar-fill.ok   { background: linear-gradient(90deg, #10b981, #34d399); }
        .mp-stock-bar-fill.warn { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .mp-stock-bar-fill.low  { background: linear-gradient(90deg, #ef4444, #f87171); }
        .mp-stock-min { font-size: 0.625rem; color: #94a3b8; }

        /* Action buttons */
        .mp-act-grp { display: flex; gap: 0.3rem; align-items: center; justify-content: center; }
        .mp-act-btn {
            display: inline-flex; align-items: center; gap: 0.25rem;
            padding: 0.3125rem 0.75rem; border-radius: 8px;
            font-size: 0.72rem; font-weight: 600; border: 1px solid;
            cursor: pointer; font-family: inherit; transition: all 0.2s;
            text-decoration: none; line-height: 1.4;
        }
        .mp-act-edit { background: #fffbeb; color: #92400e; border-color: #fde68a; }
        .mp-act-edit:hover { background: #fef3c7; border-color: #fcd34d; transform: translateY(-1px); }
        .mp-act-del { background: #fff1f2; color: #be123c; border-color: #fecdd3; }
        .mp-act-del:hover { background: #ffe4e6; border-color: #fda4af; transform: translateY(-1px); }

        /* Pagination */
        .mp-pagination {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.375rem; border-top: 1px solid #f1f5f9;
            flex-wrap: wrap; gap: 0.75rem;
        }
        .mp-page-info { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }
        .mp-pages { display: flex; gap: 0.25rem; align-items: center; }
        .mp-page-btn {
            height: 32px; min-width: 32px; padding: 0 0.5rem;
            display: flex; align-items: center; justify-content: center;
            border-radius: 8px; font-size: 0.8125rem; font-weight: 600;
            border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
            cursor: pointer; font-family: inherit; transition: all 0.15s;
        }
        .mp-page-btn:hover:not(:disabled) { background: #f1f5f9; border-color: #cbd5e1; }
        .mp-page-btn.active { background: #4f46e5; border-color: #4f46e5; color: #fff; }
        .mp-page-btn:disabled { opacity: 0.35; cursor: not-allowed; }

        /* Skeleton */
        .mp-skel { background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 400% 100%; animation: mp-shimmer 1.5s ease infinite; border-radius: 6px; }
        @keyframes mp-shimmer { 0% { background-position: 100% 0; } 100% { background-position: -100% 0; } }
        .mp-skel-row { display: flex; gap: 0.75rem; padding: 0.875rem 1rem; border-bottom: 1px solid #f4f8fc; align-items: center; }
        .mp-skel-cell { height: 12px; }

        /* Empty State */
        .mp-empty { text-align: center; padding: 4rem 2rem; }
        .mp-empty-icon {
            width: 72px; height: 72px; border-radius: 20px;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem; margin: 0 auto 1.25rem;
        }
        .mp-empty-title { font-size: 1rem; font-weight: 700; color: #334155; margin-bottom: 0.375rem; }
        .mp-empty-desc { font-size: 0.8125rem; color: #94a3b8; margin-bottom: 1.25rem; }

        /* Toast */
        .mp-toast {
            position: fixed; top: 80px; left: 50%; transform: translateX(-50%) translateY(-16px);
            z-index: 9999; padding: 0.75rem 1.375rem;
            border-radius: 14px; font-size: 0.875rem; font-weight: 600;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            display: flex; align-items: center; gap: 0.625rem;
            pointer-events: none; transition: all 0.4s cubic-bezier(0.34,1.56,.64,1);
            opacity: 0;
        }
        .mp-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }
        .mp-toast.success { background: #059669; color: #fff; }
        .mp-toast.error   { background: #e11d48; color: #fff; }

        /* Unit grid */
        .unit-grid { display: grid; grid-template-columns: 140px 90px 1fr 1fr 1fr auto; gap: 0.5rem; align-items: start; padding: 0.75rem; background: #fafbff; border: 1px solid #eef2ff; border-radius: 10px; margin-bottom: 0.5rem; }
        .unit-grid .form-group { margin-bottom: 0; }
        .unit-grid-label { font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
        .unit-grid-full { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem; margin-top: 0.25rem; padding-top: 0.5rem; border-top: 1px dashed #e2e8f0; }

        /* Panel anim */
        @keyframes mp-panel-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .mp-panel-wrap { animation: mp-panel-in 0.22s ease both; }

        /* Responsive */
        @media (max-width: 900px) {
            .mp-stats-grid { grid-template-columns: repeat(3, 1fr); }
            .mp-hero-title { font-size: 1.5rem; }
        }
        @media (max-width: 640px) {
            .mp-stats-grid { grid-template-columns: 1fr 1fr; }
            .unit-grid { grid-template-columns: 1fr 1fr; }
            .mp-toolbar { gap: 0.5rem; }
            .mp-search-wrap { max-width: 100%; }
        }

        /* ── MODAL ── */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.6);
            z-index: 9990;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 2rem 1rem;
            backdrop-filter: blur(4px);
            overflow-y: auto;
        }
        @keyframes mp-modal-in {
            from { opacity: 0; transform: translateY(-24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.22), 0 8px 24px rgba(0,0,0,0.08);
            width: 100%; max-width: 520px;
            max-height: calc(100vh - 4rem);
            overflow-y: auto;
            animation: mp-modal-in 0.25s cubic-bezier(0.34,1.56,.64,1) both;
            position: relative;
            margin: auto;
            flex-shrink: 0;
        }
        .modal-wide   { max-width: 760px; }
        .modal-narrow { max-width: 460px; }

        .modal-header {
            padding: 1.375rem 1.5rem 1.125rem;
            border-bottom: 1px solid #f0f4f8;
            display: flex; justify-content: space-between; align-items: center;
            background: linear-gradient(180deg, #fff, #fafbfd);
            border-radius: 20px 20px 0 0;
            position: sticky; top: 0; z-index: 1;
        }
        .modal-title {
            font-size: 1.0625rem; font-weight: 800; color: #0f172a;
            display: flex; align-items: center; gap: 0.625rem;
            letter-spacing: -0.02em;
        }
        .modal-title-accent {
            display: inline-flex; align-items: center; justify-content: center;
            width: 30px; height: 30px; border-radius: 8px;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            color: #4f46e5; font-size: 0.875rem; flex-shrink: 0;
        }
        .modal-close {
            width: 34px; height: 34px; border-radius: 9px;
            border: 1.5px solid #e2e8f0; background: #fff;
            cursor: pointer; font-size: 1.1rem;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; transition: all 0.2s; font-family: inherit; line-height: 1;
        }
        .modal-close:hover { background: #fee2e2; border-color: #fca5a5; color: #be123c; }

        .modal-body { padding: 1.375rem 1.5rem; }
        .modal-body .form-card { margin-bottom: 1rem; }
        .modal-body .form-card:last-child { margin-bottom: 0; }
        .modal-body .form-card {
            border: 1px solid #eef2fb;
            border-radius: 14px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .modal-body .form-card-header {
            background: linear-gradient(180deg, #f8faff, #f3f6fd);
            border-bottom: 1px solid #eef2fb;
            padding: 0.875rem 1.125rem;
            font-size: 0.8125rem; font-weight: 700; color: #374151;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .modal-body .form-card-header svg { color: #6366f1; }
        .modal-body .form-card-body { padding: 1.125rem; background: #fff; }

        .modal-footer {
            padding: 1rem 1.5rem;
            background: linear-gradient(180deg, #f9fafb, #f3f4f6);
            border-top: 1px solid #e8edf5;
            border-radius: 0 0 20px 20px;
            display: flex; justify-content: flex-end; gap: 0.75rem;
            position: sticky; bottom: 0;
        }
        .modal-footer .btn-primary {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            box-shadow: 0 4px 14px rgba(79,70,229,0.3);
            border-radius: 10px; padding: 0.5625rem 1.5rem;
            font-weight: 700; font-size: 0.875rem;
        }
        .modal-footer .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(79,70,229,0.4);
        }
        .modal-footer .btn-secondary {
            border-radius: 10px; padding: 0.5625rem 1.25rem;
            border: 1.5px solid #e2e8f0;
        }
        .modal-footer .btn-primary:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }
    </style>

    <div x-data="masterProdukApp()" class="page-container">

        {{-- TOAST --}}
        <div class="mp-toast" :class="[toastType, { show: toastShow }]">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                <path x-show="toastType==='success'" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5l-3-3 1.5-1.5L9 11.5l4.5-4.5L15 8l-6 6z"/>
                <path x-show="toastType==='error'"   d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"/>
            </svg>
            <span x-text="toastMsg"></span>
        </div>

        {{-- HERO HEADER --}}
        <div class="mp-hero">
            <div class="mp-hero-inner">
                <div class="mp-breadcrumb">
                    <span>Master Data</span>
                    <span class="mp-breadcrumb-sep">›</span>
                    <span style="color:rgba(255,255,255,0.8);">Master Produk</span>
                </div>
                <div class="mp-hero-title-row">
                    <div class="mp-hero-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                            <line x1="7" y1="7" x2="7.01" y2="7" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="mp-hero-title">Master Produk</h1>
                        <p class="mp-hero-subtitle">Kelola produk, kategori, satuan, dan penyesuaian stok dalam satu tempat</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="mp-stats-grid">
            <div class="mp-stat s-indigo">
                <div class="mp-stat-icon-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="mp-stat-label">Total Produk</div>
                <div class="mp-stat-value" x-text="stats.total_produk.toLocaleString()">0</div>
            </div>
            <div class="mp-stat s-emerald">
                <div class="mp-stat-icon-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <div class="mp-stat-label">Kategori</div>
                <div class="mp-stat-value" x-text="stats.total_kategori.toLocaleString()">0</div>
            </div>
            <div class="mp-stat s-amber">
                <div class="mp-stat-icon-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div class="mp-stat-label">Satuan</div>
                <div class="mp-stat-value" x-text="stats.total_satuan.toLocaleString()">0</div>
            </div>
            <div class="mp-stat s-rose">
                <div class="mp-stat-icon-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div class="mp-stat-label">Stok Menipis</div>
                <div class="mp-stat-value" x-text="stats.low_stock.toLocaleString()">0</div>
            </div>
            <div class="mp-stat s-blue">
                <div class="mp-stat-icon-wrap">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <div class="mp-stat-label">Penyesuaian Stok</div>
                <div class="mp-stat-value" x-text="stats.total_stok.toLocaleString()">0</div>
            </div>
        </div>

        {{-- TAB NAVIGATION --}}
        <div class="mp-tab-bar" role="tablist">
            <template x-for="(t, i) in tabs" :key="t.key">
                <button class="mp-tab-btn" :class="{ active: activeTab === t.key }"
                    @click="switchTab(t.key)" :aria-selected="activeTab === t.key" role="tab" type="button">
                    <span class="mp-tab-icon" x-text="t.icon"></span>
                    <span x-text="t.label"></span>
                    <span class="mp-tab-count" x-text="t.count"></span>
                </button>
            </template>
        </div>

        {{-- PANELS --}}
        <div x-show="activeTab === 'produk'" x-cloak class="mp-panel-wrap">
            @include('master.produk._tab_produk')
        </div>
        <div x-show="activeTab === 'kategori'" x-cloak class="mp-panel-wrap">
            @include('master.produk._tab_kategori')
        </div>
        <div x-show="activeTab === 'satuan'" x-cloak class="mp-panel-wrap">
            @include('master.produk._tab_satuan')
        </div>
        <div x-show="activeTab === 'stok'" x-cloak class="mp-panel-wrap">
            @include('master.produk._tab_stok')
        </div>

        {{-- ══════ MODAL: KATEGORI ADD ══════ --}}
        <div class="modal-overlay" x-show="modals['kategori-add']" x-cloak
             @keydown.escape.window="modals['kategori-add']=false"
             @click.self="modals['kategori-add']=false"
             x-transition:enter.opacity.duration.200ms>
            <div class="modal modal-narrow" @click.stop>
                <form @submit.prevent="submitKategori($event, 'add')">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title">✦ Tambah Kategori</div>
                        <button type="button" class="modal-close" @click="modals['kategori-add']=false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" required placeholder="Minuman, Makanan Ringan" x-ref="kategoriName">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-input" rows="2" placeholder="Opsional"></textarea>
                            <div class="form-hint">Penjelasan singkat tentang kategori ini</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="modals['kategori-add']=false">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="submitting" x-text="submitting ? 'Menyimpan...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════ MODAL: KATEGORI EDIT ══════ --}}
        <div class="modal-overlay" x-show="modals['kategori-edit']" x-cloak
             @keydown.escape.window="modals['kategori-edit']=false"
             @click.self="modals['kategori-edit']=false"
             x-transition:enter.opacity.duration.200ms>
            <div class="modal modal-narrow" @click.stop>
                <form @submit.prevent="submitKategori($event, 'edit')">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <div class="modal-title">✦ Edit Kategori</div>
                        <button type="button" class="modal-close" @click="modals['kategori-edit']=false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Kategori <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" required x-model="editKategoriName">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-input" rows="2" x-model="editKategoriDesc"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="modals['kategori-edit']=false">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="submitting" x-text="submitting ? 'Menyimpan...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════ MODAL: SATUAN ADD ══════ --}}
        <div class="modal-overlay" x-show="modals['satuan-add']" x-cloak
             @keydown.escape.window="modals['satuan-add']=false"
             @click.self="modals['satuan-add']=false"
             x-transition:enter.opacity.duration.200ms>
            <div class="modal modal-narrow" @click.stop>
                <form @submit.prevent="submitSatuan($event, 'add')">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title">✦ Tambah Satuan</div>
                        <button type="button" class="modal-close" @click="modals['satuan-add']=false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Satuan <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" required placeholder="Dus, Karton, Slop" x-ref="satuanName">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Singkatan <span class="required">*</span></label>
                            <input type="text" name="abbreviation" class="form-input" required placeholder="dus, ktn, slp" maxlength="20">
                            <div class="form-hint">Singkatan pendek, contoh: "dus" untuk "Dus"</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-input" rows="2" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="modals['satuan-add']=false">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="submitting" x-text="submitting ? 'Menyimpan...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════ MODAL: SATUAN EDIT ══════ --}}
        <div class="modal-overlay" x-show="modals['satuan-edit']" x-cloak
             @keydown.escape.window="modals['satuan-edit']=false"
             @click.self="modals['satuan-edit']=false"
             x-transition:enter.opacity.duration.200ms>
            <div class="modal modal-narrow" @click.stop>
                <form @submit.prevent="submitSatuan($event, 'edit')">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <div class="modal-title">✦ Edit Satuan</div>
                        <button type="button" class="modal-close" @click="modals['satuan-edit']=false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama Satuan <span class="required">*</span></label>
                            <input type="text" name="name" class="form-input" required x-model="editSatuanName">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Singkatan <span class="required">*</span></label>
                            <input type="text" name="abbreviation" class="form-input" required maxlength="20" x-model="editSatuanAbbr">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-input" rows="2" x-model="editSatuanDesc"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="modals['satuan-edit']=false">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="submitting" x-text="submitting ? 'Menyimpan...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ══════ MODAL: STOK ADJUSTMENT ══════ --}}
        <div class="modal-overlay" x-show="modals['stok-add']" x-cloak
             @keydown.escape.window="modals['stok-add']=false"
             @click.self="modals['stok-add']=false"
             x-transition:enter.opacity.duration.200ms>
            <div class="modal" style="max-width:520px;" @click.stop>
                <form @submit.prevent="submitAdjustment($event)">
                    @csrf
                    <div class="modal-header">
                        <div class="modal-title">✦ Penyesuaian Stok</div>
                        <button type="button" class="modal-close" @click="modals['stok-add']=false">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" x-data="{
                            search: '',
                            open: false,
                            selectedId: '',
                            get filteredProducts() {
                                if(this.search === '' || this.selectedId !== '') return allProducts;
                                return allProducts.filter(p => (p.name + ' ' + (p.sku || '')).toLowerCase().includes(this.search.toLowerCase()));
                            },
                            select(p) {
                                this.selectedId = p.id;
                                this.search = p.name + ' (' + (p.sku || '') + ')';
                                this.open = false;
                                if (typeof onProdukStokChange === 'function') {
                                    onProdukStokChange(p.id);
                                } else if (this.$parent && typeof this.$parent.onProdukStokChange === 'function') {
                                    this.$parent.onProdukStokChange(p.id);
                                } else {
                                    this.onProdukStokChange(p.id);
                                }
                            }
                        }">
                            <label class="form-label">Produk <span class="required">*</span></label>
                            
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="product_id" :value="selectedId" required x-ref="stokProduk">

                            <div class="custom-select-wrap" @click.away="open = false" style="position:relative;">
                                <div class="form-input" style="padding:0; display:flex; align-items:center; background:#fff; overflow:hidden;">
                                    <input type="text" 
                                           x-model="search" 
                                           @focus="open = true; if(selectedId){ search=''; selectedId=''; }"
                                           @input="open = true; selectedId = ''"
                                           placeholder="Cari produk..." 
                                           style="border:none; box-shadow:none; flex-grow:1; width:100%; outline:none; padding:8px 12px; background:transparent;">
                                    <svg style="width:16px;height:16px;color:#94a3b8;margin-right:12px;flex-shrink:0;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                </div>
                                
                                <div x-show="open" style="position:absolute; top:100%; left:0; right:0; z-index:999; background:#fff; border:1px solid #e2e8f0; border-radius:6px; margin-top:4px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); max-height:250px; display:flex; flex-direction:column;" x-transition style="display:none;">
                                    <div style="overflow-y:auto; flex-grow:1;">
                                        <template x-for="p in filteredProducts" :key="p.id">
                                            <div @click="select(p)"
                                                 style="padding:8px 12px; cursor:pointer; font-size:13px; border-bottom:1px solid #f1f5f9; transition:background 0.15s;"
                                                 :style="selectedId === p.id ? 'background:#f8fafc; font-weight:600; color:#4f46e5;' : 'color:#334155;'"
                                                 @mouseenter="$el.style.backgroundColor='#f8fafc'"
                                                 @mouseleave="$el.style.backgroundColor=selectedId === p.id ? '#f8fafc' : 'transparent'">
                                                <span x-text="p.name + ' (' + (p.sku || '') + ')'"></span>
                                            </div>
                                        </template>
                                        <div x-show="filteredProducts.length === 0" style="padding:12px; text-align:center; color:#94a3b8; font-size:13px;">
                                            Produk tidak ditemukan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gudang <span class="required">*</span></label>
                            <select name="warehouse_id" class="form-input" required>
                                <option value="">Pilih gudang</option>
                                @foreach($warehouses as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tipe <span class="required">*</span></label>
                                <select name="tipe" class="form-input" required>
                                    <option value="masuk">Stok Masuk</option>
                                    <option value="koreksi">Koreksi Stok</option>
                                </select>
                                <div class="form-hint">Masuk: tambah stok &middot; Koreksi: set stok ke jumlah tertentu</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Jumlah <span class="required">*</span></label>
                                <input type="number" name="jumlah" class="form-input" required min="0.001" step="0.001" placeholder="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Satuan</label>
                            <select name="unit_id" class="form-input" x-ref="stokUnit">
                                <option value="">Satuan Dasar</option>
                            </select>
                            <div class="form-hint">Pilih satuan jika bukan satuan dasar</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-input" rows="2" placeholder="Stok masuk dari supplier / Koreksi stok"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" @click="modals['stok-add']=false">Batal</button>
                        <button type="submit" class="btn-primary" :disabled="submitting" x-text="submitting ? 'Menyimpan...' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end page-container --}}

    <script>
    function masterProdukApp() {
        return {
            activeTab: '{{ $tab }}',
            tabs: [
                { key: 'produk',   icon: '📦', label: 'Produk',   count: '{{ $stats["total_produk"] }}' },
                { key: 'kategori', icon: '📂', label: 'Kategori', count: '{{ $stats["total_kategori"] }}' },
                { key: 'satuan',   icon: '⚖', label: 'Satuan',   count: '{{ $stats["total_satuan"] }}' },
                { key: 'stok',     icon: '📊', label: 'Stok',     count: '{{ $stats["total_stok"] }}' },
            ],
            stats: {
                total_produk:   {{ $stats['total_produk'] }},
                total_kategori: {{ $stats['total_kategori'] }},
                total_satuan:   {{ $stats['total_satuan'] }},
                total_stok:     {{ $stats['total_stok'] }},
                low_stock:      {{ $stats['low_stock'] }},
            },
            modals: {
                'produk-add': false, 'produk-edit': false,
                'kategori-add': false, 'kategori-edit': false,
                'satuan-add': false, 'satuan-edit': false,
                'stok-add': false,
            },
            submitting: false,
            toastShow: false,
            toastMsg: '',
            toastType: 'success',
            unitRows: [],

            editProduk: null,
            editUnitRows: [],
            produkData: { data: [], pagination: { current_page: 1, last_page: 1, total: 0, per_page: 15 } },
            produkLoading: false,
            produkSearch: '',
            produkCategory: '',
            produkLowStock: false,
            produkSearchTimer: null,

            editKategoriId: null,
            editKategoriName: '',
            editKategoriDesc: '',
            kategoriData: { data: [], pagination: { current_page: 1, last_page: 1, total: 0, per_page: 15 } },
            kategoriLoading: false,
            kategoriSearch: '',
            kategoriSearchTimer: null,

            editSatuanId: null,
            editSatuanName: '',
            editSatuanAbbr: '',
            editSatuanDesc: '',
            satuanData: { data: [], pagination: { current_page: 1, last_page: 1, total: 0, per_page: 15 } },
            satuanLoading: false,
            satuanSearch: '',
            satuanSearchTimer: null,

            stokData: { data: [], pagination: { current_page: 1, last_page: 1, total: 0, per_page: 15 } },
            stokLoading: false,
            stokSearch: '',
            stokTipe: '',
            stokWarehouse: '',
            stokSearchTimer: null,

            allProducts: [],

            init() {
                this.loadProduk();
                this.loadKategori();
                this.loadSatuan();
                this.loadStok();
                this.loadAllProducts();
            },

            toast(msg, type = 'success') {
                this.toastMsg = msg;
                this.toastType = type;
                this.toastShow = true;
                setTimeout(() => { this.toastShow = false; }, 4000);
            },

            openModal(name) {
                this.modals[name] = true;
                this.$nextTick(() => {
                    const map = { 'produk-add': 'produkName', 'kategori-add': 'kategoriName', 'satuan-add': 'satuanName' };
                    const ref = map[name];
                    if (ref && this.$refs[ref]) this.$refs[ref].focus();
                });
            },

            switchTab(key) {
                this.activeTab = key;
                const url = new URL(window.location);
                url.searchParams.set('tab', key);
                history.replaceState({}, '', url);
            },

            formatRp(val) {
                return 'Rp' + Number(val).toLocaleString('id-ID');
            },

            stockBarClass(stock, minStock) {
                if (minStock <= 0) return 'ok';
                const ratio = stock / minStock;
                if (ratio >= 2) return 'ok';
                if (ratio >= 1) return 'warn';
                return 'low';
            },

            stockBarWidth(stock, minStock) {
                if (minStock <= 0) return 100;
                const ratio = (stock / minStock) * 50;
                return Math.min(ratio, 100);
            },

            stockBadgeClass(stock, minStock) {
                if (minStock <= 0) return 'ok';
                if (stock <= minStock) return 'low';
                if (stock <= minStock * 2) return 'warn';
                return 'ok';
            },

            handleResponse(data, successMsg) {
                if (data.success) {
                    this.toast(data.message || successMsg, 'success');
                    return true;
                } else {
                    this.toast(data.message || 'Terjadi kesalahan', 'error');
                    return false;
                }
            },

            // PRODUK
            async loadProduk(page) {
                this.produkLoading = true;
                try {
                    const params = new URLSearchParams({ page: page || this.produkData.pagination.current_page });
                    if (this.produkSearch) params.set('search', this.produkSearch);
                    if (this.produkCategory) params.set('category_id', this.produkCategory);
                    if (this.produkLowStock) params.set('low_stock', '1');
                    const res = await fetch('{{ route("master.produk.search.products") }}?' + params);
                    const json = await res.json();
                    if (json.data) this.produkData = json;
                    if (json.stats) this.stats.low_stock = json.stats.low_stock;
                } catch (e) {
                    this.toast('Gagal memuat produk', 'error');
                } finally {
                    this.produkLoading = false;
                }
            },

            onProdukSearchInput() {
                clearTimeout(this.produkSearchTimer);
                this.produkSearchTimer = setTimeout(() => this.loadProduk(1), 400);
            },

            async submitProduk($event, mode) {
                this.submitting = true;
                try {
                    const form = $event.target;
                    const fd = new FormData(form);
                    const id = mode === 'edit' ? this.editProduk.id : '';
                    const url = mode === 'add' ? '{{ route("master.produk.store") }}' : '{{ url("master/produk") }}/' + id;
                    if (mode === 'edit') fd.append('_method', 'PUT');
                    const res = await fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    if (this.handleResponse(data, mode === 'add' ? 'Produk berhasil ditambahkan' : 'Produk berhasil diperbarui')) {
                        this.modals[mode === 'add' ? 'produk-add' : 'produk-edit'] = false;
                        this.loadProduk();
                        this.stats.total_produk = mode === 'add' ? this.stats.total_produk + 1 : this.stats.total_produk;
                    }
                } catch (e) {
                    this.toast('Terjadi kesalahan saat menyimpan produk', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            async deleteProduk(id, name) {
                if (!confirm('Hapus produk "' + name + '"? Tindakan ini tidak bisa dibatalkan.')) return;
                try {
                    const res = await fetch('{{ url("master/produk") }}/' + id, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: new URLSearchParams({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
                    });
                    const data = await res.json();
                    if (this.handleResponse(data, 'Produk berhasil dihapus')) {
                        this.loadProduk();
                        this.stats.total_produk = Math.max(0, this.stats.total_produk - 1);
                    }
                } catch (e) {
                    this.toast('Gagal menghapus produk', 'error');
                }
            },

            openEditProduk(id) {
                const p = this.produkData.data.find(x => x.id === id);
                if (!p) return;
                this.editProduk = { ...p };
                this.editUnitRows = (p.units || []).map(uc => ({
                    unit_id: uc.unit_id || '',
                    factor: uc.conversion_factor || '',
                    beli: uc.purchase_price || '',
                    ecer: uc.sell_price_ecer || '',
                    grosir: uc.sell_price_grosir || '',
                    jual1: uc.sell_price_jual1 || '',
                    jual2: uc.sell_price_jual2 || '',
                    jual3: uc.sell_price_jual3 || '',
                    minimal: uc.sell_price_minimal || '',
                }));
                if (this.editUnitRows.length === 0) {
                    this.editUnitRows.push({ unit_id: '', factor: '', beli: '', ecer: '', grosir: '', jual1: '', jual2: '', jual3: '', minimal: '' });
                }
                this.modals['produk-edit'] = true;
            },

            // KATEGORI
            async loadKategori(page) {
                this.kategoriLoading = true;
                try {
                    const params = new URLSearchParams({ page: page || this.kategoriData.pagination.current_page });
                    if (this.kategoriSearch) params.set('search', this.kategoriSearch);
                    const res = await fetch('{{ route("master.produk.search.categories") }}?' + params);
                    const json = await res.json();
                    if (json.data) this.kategoriData = json;
                } catch (e) {
                    this.toast('Gagal memuat kategori', 'error');
                } finally {
                    this.kategoriLoading = false;
                }
            },

            onKategoriSearchInput() {
                clearTimeout(this.kategoriSearchTimer);
                this.kategoriSearchTimer = setTimeout(() => this.loadKategori(1), 400);
            },

            async submitKategori($event, mode) {
                this.submitting = true;
                try {
                    const form = $event.target;
                    const fd = new FormData(form);
                    const id = mode === 'edit' ? this.editKategoriId : '';
                    const url = mode === 'add' ? '{{ route("master.produk.kategori.store") }}' : '{{ url("master/produk/kategori") }}/' + id;
                    if (mode === 'edit') fd.append('_method', 'PUT');
                    const res = await fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    if (this.handleResponse(data, mode === 'add' ? 'Kategori berhasil ditambahkan' : 'Kategori berhasil diperbarui')) {
                        this.modals[mode === 'add' ? 'kategori-add' : 'kategori-edit'] = false;
                        this.loadKategori();
                        this.stats.total_kategori = mode === 'add' ? this.stats.total_kategori + 1 : this.stats.total_kategori;
                    }
                } catch (e) {
                    this.toast('Terjadi kesalahan', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            openEditKategori(id, name, desc) {
                this.editKategoriId = id;
                this.editKategoriName = name;
                this.editKategoriDesc = desc;
                this.modals['kategori-edit'] = true;
            },

            async deleteKategori(id) {
                if (!confirm('Hapus kategori? Produk dengan kategori ini tidak akan terhapus.')) return;
                try {
                    const res = await fetch('{{ url("master/produk/kategori") }}/' + id, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: new URLSearchParams({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
                    });
                    const data = await res.json();
                    if (this.handleResponse(data, 'Kategori berhasil dihapus')) {
                        this.loadKategori();
                        this.stats.total_kategori = Math.max(0, this.stats.total_kategori - 1);
                    }
                } catch (e) {
                    this.toast('Gagal menghapus kategori', 'error');
                }
            },

            // SATUAN
            async loadSatuan(page) {
                this.satuanLoading = true;
                try {
                    const params = new URLSearchParams({ page: page || this.satuanData.pagination.current_page });
                    if (this.satuanSearch) params.set('search', this.satuanSearch);
                    const res = await fetch('{{ route("master.produk.search.units") }}?' + params);
                    const json = await res.json();
                    if (json.data) this.satuanData = json;
                } catch (e) {
                    this.toast('Gagal memuat satuan', 'error');
                } finally {
                    this.satuanLoading = false;
                }
            },

            onSatuanSearchInput() {
                clearTimeout(this.satuanSearchTimer);
                this.satuanSearchTimer = setTimeout(() => this.loadSatuan(1), 400);
            },

            async submitSatuan($event, mode) {
                this.submitting = true;
                try {
                    const form = $event.target;
                    const fd = new FormData(form);
                    const id = mode === 'edit' ? this.editSatuanId : '';
                    const url = mode === 'add' ? '{{ route("master.produk.satuan.store") }}' : '{{ url("master/produk/satuan") }}/' + id;
                    if (mode === 'edit') fd.append('_method', 'PUT');
                    const res = await fetch(url, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();
                    if (this.handleResponse(data, mode === 'add' ? 'Satuan berhasil ditambahkan' : 'Satuan berhasil diperbarui')) {
                        this.modals[mode === 'add' ? 'satuan-add' : 'satuan-edit'] = false;
                        this.loadSatuan();
                        this.stats.total_satuan = mode === 'add' ? this.stats.total_satuan + 1 : this.stats.total_satuan;
                    }
                } catch (e) {
                    this.toast('Terjadi kesalahan', 'error');
                } finally {
                    this.submitting = false;
                }
            },

            openEditSatuan(id, name, abbr, desc) {
                this.editSatuanId = id;
                this.editSatuanName = name;
                this.editSatuanAbbr = abbr;
                this.editSatuanDesc = desc;
                this.modals['satuan-edit'] = true;
            },

            async deleteSatuan(id) {
                if (!confirm('Hapus satuan? Satuan yang masih digunakan tidak bisa dihapus.')) return;
                try {
                    const res = await fetch('{{ url("master/produk/satuan") }}/' + id, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: new URLSearchParams({ _method: 'DELETE', _token: '{{ csrf_token() }}' })
                    });
                    const data = await res.json();
                    if (this.handleResponse(data, 'Satuan berhasil dihapus')) {
                        this.loadSatuan();
                        this.stats.total_satuan = Math.max(0, this.stats.total_satuan - 1);
                    }
                } catch (e) {
                    this.toast('Gagal menghapus satuan', 'error');
                }
            },

            // STOK
            async loadStok(page) {
                this.stokLoading = true;
                try {
                    const params = new URLSearchParams({ page: page || this.stokData.pagination.current_page });
                    if (this.stokSearch) params.set('search', this.stokSearch);
                    if (this.stokTipe) params.set('tipe', this.stokTipe);
                    if (this.stokWarehouse) params.set('warehouse_id', this.stokWarehouse);
                    const res = await fetch('{{ route("master.produk.search.adjustments") }}?' + params);
                    const json = await res.json();
                    if (json.data) this.stokData = json;
                } catch (e) {
                    this.toast('Gagal memuat riwayat stok', 'error');
                } finally {
                    this.stokLoading = false;
                }
            },

            onStokSearchInput() {
                clearTimeout(this.stokSearchTimer);
                this.stokSearchTimer = setTimeout(() => this.loadStok(1), 400);
            },

            async loadAllProducts() {
                try {
                    const res = await fetch('{{ route("master.produk.search.products") }}?per_page=1000');
                    const json = await res.json();
                    this.allProducts = json.data || [];
                } catch (e) {}
            },

            onProdukStokChange(productId) {
                const unitSelect = this.$refs.stokUnit;
                unitSelect.innerHTML = '<option value="">Satuan Dasar</option>';
                if (!productId) return;
                const p = this.allProducts.find(x => x.id == productId);
                if (p && p.units) {
                    p.units.forEach(uc => {
                        if (uc.unit_id) {
                            const opt = document.createElement('option');
                            opt.value = uc.unit_id;
                            opt.textContent = uc.unit_name + ' (1:' + uc.conversion_factor + ')';
                            unitSelect.appendChild(opt);
                        }
                    });
                }
            },

            async submitAdjustment($event) {
                this.submitting = true;
                try {
                    const form = $event.target;
                    const fd = new FormData(form);
                    const res = await fetch('{{ route("master.produk.stok.store") }}', {
                        method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if (this.handleResponse(data, 'Penyesuaian stok berhasil')) {
                        this.modals['stok-add'] = false;
                        this.loadStok();
                        this.stats.total_stok++;
                        this.loadProduk();
                    }
                } catch (e) {
                    this.toast('Terjadi kesalahan', 'error');
                } finally {
                    this.submitting = false;
                }
            },
        };
    }
    </script>
</x-app-layout>
