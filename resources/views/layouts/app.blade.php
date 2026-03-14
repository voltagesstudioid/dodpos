<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'DODPOS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ===== CSS CUSTOM PROPERTIES ===== */
        :root {
            --primary: #4f46e5; --primary-hover: #4338ca; --primary-light: #eef2ff;
            --success: #059669; --success-light: #ecfdf5;
            --danger: #ef4444; --danger-light: #fef2f2;
            --warning: #f59e0b; --warning-light: #fffbeb;
            --surface: #ffffff; --surface-2: #f8fafc; --surface-3: #f1f5f9;
            --border: #e2e8f0; --border-2: #f1f5f9;
            --text-1: #0f172a; --text-2: #374151; --text-3: #64748b; --text-4: #94a3b8;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 30px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --radius: 12px; --radius-sm: 8px; --radius-xs: 6px;
            --transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        }
        /* ===== ANIMATIONS ===== */
        @keyframes fadeSlideIn { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        @keyframes shimmer { 0% { background-position:-200% 0; } 100% { background-position:200% 0; } }
        @keyframes pulse-dot { 0%,100% { transform:scale(1); opacity:1; } 50% { transform:scale(1.4); opacity:0.7; } }
        @keyframes spin { to { transform:rotate(360deg); } }
        @keyframes slideInRight { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
        @keyframes countUp { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:translateY(0); } }
        .animate-in { animation: fadeSlideIn 0.35s ease both; }
        .animate-in-delay-1 { animation-delay: 0.05s; }
        .animate-in-delay-2 { animation-delay: 0.1s; }
        .animate-in-delay-3 { animation-delay: 0.15s; }

        /* ===== RESET ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; min-height: 100vh; display: flex; overflow-x: hidden; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 270px; min-width: 270px;
            background: #ffffff;
            border-right: 1px dashed #cbd5e1;
            display: flex; flex-direction: column;
            min-height: 100vh; position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200; transition: width 0.3s ease, transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }
        .sidebar-logo {
            height: 72px; display: flex; align-items: center;
            padding: 0 1.5rem; gap: 0.85rem;
            background: #ffffff;
            text-decoration: none; flex-shrink: 0;
            position: relative;
        }
        .sidebar-logo::after {
            content: ''; position: absolute; bottom: 0; left: 1.5rem; right: 1.5rem;
            height: 1px; background: linear-gradient(90deg, #f1f5f9, #e2e8f0, #f1f5f9);
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 8px 16px -4px rgba(79,70,229,0.3);
        }
        .sidebar-logo-text { font-weight: 800; font-size: 1.125rem; color: #0f172a; letter-spacing: -0.02em; }
        .sidebar-logo-text span { color: #4f46e5; }
        .sidebar-logo-badge {
            margin-left: auto; font-size: 0.6rem; background: #eef2ff; color: #4338ca;
            padding: 2px 7px; border-radius: 20px; border: 1px solid #c7d2fe;
            white-space: nowrap; font-weight: 700; letter-spacing: 0.04em;
        }
        .sidebar-nav {
            flex: 1; padding: 1rem 0; overflow-y: auto;
            scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        .sidebar-search { padding: 1rem 1.25rem 0.5rem; }
        .sidebar-search input {
            width: 100%; background: #f8fafc; border: 1px solid transparent;
            color: #334155; padding: 0.65rem 1rem; border-radius: 99px; font-size: 0.85rem;
            outline: none; transition: all 0.3s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #94a3b8; font-weight: 500; }
        .sidebar-search input:focus { border-color: #c7d2fe; background: #ffffff; box-shadow: 0 4px 12px rgba(79,70,229,0.06); }

        /* Nav Groups */
        .nav-group { margin-bottom: 0.5rem; }
        .nav-group-header {
            width: 100%; display: flex; align-items: center; justify-content: space-between;
            padding: 0.75rem 1.5rem; background: transparent; border: none;
            color: #94a3b8; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em; cursor: pointer; transition: color 0.2s;
        }
        .nav-group-header:hover { color: #475569; }
        .nav-group-label { display: flex; align-items: center; gap: 0.5rem; }
        .nav-group-arrow { width: 14px; height: 14px; transition: transform 0.25s; flex-shrink: 0; stroke: currentColor; opacity: 0.6; }
        .nav-group.open .nav-group-arrow { transform: rotate(180deg); opacity: 1; }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height 0.35s ease-in-out; }
        .nav-group.open .nav-group-items { max-height: 1000px; padding-bottom: 0.5rem; }

        /* Nav Items */
        .nav-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.6rem 1.25rem 0.6rem 2.8rem;
            color: #475569; text-decoration: none; font-size: 0.9rem; font-weight: 600;
            transition: all 0.2s; border-left: 3px solid transparent; position: relative;
        }
        .nav-item::before {
            content: ''; position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%);
            width: 4px; height: 4px; border-radius: 50%; background: #cbd5e1; transition: all 0.2s;
        }
        .nav-item:hover { color: #0f172a; background: #f8fafc; }
        .nav-item:hover::before { background: #4f46e5; height: 8px; border-radius: 4px; }
        .nav-item.active {
            color: #4f46e5; font-weight: 700; background: #fdfefe;
        }
        .nav-item.active::before { background: #4f46e5; height: 16px; border-radius: 4px; }
        
        .sidebar-nav > .nav-item {
            padding-left: 1.25rem; margin: 0 1rem 0.35rem;
            border-radius: 12px; border-left: none;
        }
        .sidebar-nav > .nav-item::before { display: none; }
        .sidebar-nav > .nav-item:hover { background: #f1f5f9; color: #0f172a; transform: translateX(2px); }
        .sidebar-nav > .nav-item.active { background: #eef2ff; color: #4338ca; box-shadow: 0 4px 10px rgba(79,70,229,0.1); }
        .nav-item-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; opacity: 0.7; transition: all 0.2s; }
        .nav-item:hover .nav-item-icon { opacity: 1; transform: scale(1.1) rotate(-5deg); color: #4f46e5; }
        .nav-item.active .nav-item-icon { opacity: 1; color: #4f46e5; }

        /* Divider line */
        .sidebar-divider { height: 1px; background: linear-gradient(90deg, transparent, #e2e8f0, transparent); margin: 1rem 1.5rem; }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem 1.5rem; border-top: 1px dashed #e2e8f0;
            background: #fcfcfd; flex-shrink: 0;
        }
        .user-card { display: flex; align-items: center; gap: 0.85rem; padding: 0.6rem; border-radius: 12px; transition: all 0.2s; cursor: pointer; border: 1px solid transparent; }
        .user-card:hover { background: #ffffff; border-color: #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .user-avatar {
            width: 38px; height: 38px; background: linear-gradient(135deg, #4f46e5, #8b5cf6);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(79,70,229,0.25);
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { color: #0f172a; font-size: 0.875rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.3; }
        .user-role { color: #64748b; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; margin-top: 2px;}
        .online-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }

        /* Sidebar Overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 199; backdrop-filter: blur(2px); }
        .sidebar-overlay.open { display: block; }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper { flex: 1; margin-left: 270px; min-height: 100vh; display: flex; flex-direction: column; transition: margin-left 0.3s ease; min-width: 0; }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 64px; background: #ffffff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04); flex-shrink: 0; gap: 1rem;
        }
        .topbar-left { display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0; }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
        .topbar-title { font-size: 1rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-btn { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: transparent; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; color: #64748b; transition: all 0.2s; text-decoration: none; }
        .topbar-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #1e293b; }
        .topbar-user { display: flex; align-items: center; gap: 0.625rem; text-decoration: none; padding: 0.375rem 0.875rem; border-radius: 10px; border: 1px solid #e2e8f0; transition: all 0.2s; background: #f8fafc; }
        .topbar-user:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .topbar-avatar { width: 28px; height: 28px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 7px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; }
        .topbar-username { font-size: 0.8125rem; font-weight: 600; color: #1e293b; white-space: nowrap; }
        .mobile-menu-btn { display: none; width: 36px; height: 36px; align-items: center; justify-content: center; background: transparent; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; color: #64748b; flex-shrink: 0; transition: all 0.2s; }
        .mobile-menu-btn:hover { background: #f8fafc; color: #1e293b; }

        /* ===== PAGE CONTENT ===== */
        .page-content { flex: 1; padding: 1.5rem; min-width: 0; }

        /* ===== REUSABLE COMPONENTS ===== */
        .card { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; }
        .panel { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .panel-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .panel-title { font-size: 0.9375rem; font-weight: 700; color: #0f172a; }
        .panel-subtitle { font-size: 0.6875rem; color: #94a3b8; margin-top: 2px; }
        .panel-action { font-size: 0.75rem; color: #6366f1; text-decoration: none; font-weight: 600; transition: color 0.2s; white-space: nowrap; }
        .panel-action:hover { color: #4f46e5; }
        .panel-body { padding: 1.25rem; }

        /* Buttons */
        .btn-primary { background: #4f46e5; color: white; padding: 0.5rem 1.125rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.2s; white-space: nowrap; font-family: inherit; line-height: 1.5; }
        .btn-primary:hover { background: #4338ca; box-shadow: 0 4px 12px rgba(79,70,229,0.3); transform: translateY(-1px); }
        .btn-primary:active { transform: none; }
        .btn-secondary { background: #f8fafc; color: #475569; padding: 0.5rem 1.125rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: 1px solid #e2e8f0; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.2s; white-space: nowrap; font-family: inherit; line-height: 1.5; }
        .btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
        .btn-success { background: #059669; color: white; padding: 0.5rem 1.125rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.2s; white-space: nowrap; font-family: inherit; line-height: 1.5; }
        .btn-success:hover { background: #047857; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
        .btn-danger { background: #ef4444; color: white; padding: 0.5rem 1.125rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.2s; white-space: nowrap; font-family: inherit; line-height: 1.5; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: #f59e0b; color: white; padding: 0.5rem 1.125rem; border-radius: 8px; font-weight: 600; font-size: 0.8125rem; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.375rem; transition: all 0.2s; white-space: nowrap; font-family: inherit; line-height: 1.5; }
        .btn-warning:hover { background: #d97706; }
        .btn-sm { padding: 0.3125rem 0.75rem !important; font-size: 0.75rem !important; border-radius: 6px !important; gap: 0.25rem !important; }
        .btn-xs { padding: 0.1875rem 0.5rem !important; font-size: 0.6875rem !important; border-radius: 5px !important; }

        /* Forms */
        .form-group { margin-bottom: 1.125rem; display: flex; flex-direction: column; gap: 0.3125rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .form-label { font-size: 0.8125rem; font-weight: 600; color: #374151; }
        .form-label .required { color: #ef4444; margin-left: 2px; }
        .form-input { width: 100%; padding: 0.5625rem 0.875rem; border-radius: 8px; border: 1.5px solid #e2e8f0; background: #fff; color: #1e293b; font-size: 0.875rem; outline: none; transition: all 0.2s; font-family: inherit; line-height: 1.5; }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
        .form-input:disabled { background: #f8fafc; color: #94a3b8; cursor: not-allowed; }
        .form-input::placeholder { color: #94a3b8; }
        select.form-input { cursor: pointer; }
        textarea.form-input { resize: vertical; min-height: 80px; }
        .input-error { border-color: #ef4444 !important; }
        .form-error { font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem; }
        .form-hint { font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; }

        /* Tables */
        .table-wrapper { overflow-x: auto; width: 100%; -webkit-overflow-scrolling: touch; }
        .data-table { width: 100%; min-width: 600px; border-collapse: separate; border-spacing: 0; }
        .data-table th { background: #f8fafc; color: #475569; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .data-table td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.8125rem; color: #374151; vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        .data-table tbody tr { transition: background 0.15s; }

        /* Badges */
        .badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.1875rem 0.625rem; border-radius: 999px; font-size: 0.6875rem; font-weight: 600; white-space: nowrap; }
        .badge-indigo { background: #eef2ff; color: #4338ca; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-gray { background: #f1f5f9; color: #475569; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }
        .badge-teal { background: #ccfbf1; color: #0f766e; }
        .badge-green { background: #ecfdf5; color: #059669; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-yellow { background: #fffbeb; color: #d97706; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        /* Alerts */
        .alert { padding: 0.875rem 1.125rem; border-radius: 10px; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: 0.625rem; font-size: 0.875rem; font-weight: 500; line-height: 1.5; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .alert-info { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

        /* Page Header */
        .page-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .page-header-title { font-size: 1.25rem; font-weight: 800; color: #0f172a; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
        .page-header-subtitle { font-size: 0.8125rem; color: #64748b; }
        .page-header-actions { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

        /* Stat Cards */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 1.25rem; display: flex; align-items: flex-start; gap: 1rem; transition: all 0.25s; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.08); }
        .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; flex-shrink: 0; }
        .stat-icon.indigo { background: #eef2ff; }
        .stat-icon.emerald { background: #ecfdf5; }
        .stat-icon.amber { background: #fffbeb; }
        .stat-icon.rose { background: #fff1f2; }
        .stat-icon.blue { background: #eff6ff; }
        .stat-icon.purple { background: #faf5ff; }
        .stat-label { font-size: 0.6875rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.25rem; }
        .stat-value { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; line-height: 1; margin-bottom: 0.375rem; }
        .stat-value.indigo { color: #4f46e5; }
        .stat-value.emerald { color: #059669; }
        .stat-value.amber { color: #d97706; }
        .stat-value.rose { color: #e11d48; }
        .stat-value.blue { color: #2563eb; }
        .stat-value.purple { color: #7c3aed; }

        /* Misc */
        .filter-bar { padding: 0.875rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #f1f5f9; display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-end; }
        .empty-state { text-align: center; padding: 3rem 1.5rem; color: #94a3b8; }
        .empty-state-icon { font-size: 2.5rem; margin-bottom: 0.75rem; }
        .empty-state-title { font-size: 0.9375rem; font-weight: 600; color: #64748b; margin-bottom: 0.375rem; }
        .empty-state-desc { font-size: 0.8125rem; margin-bottom: 1rem; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 0.625rem 0; border-bottom: 1px solid #f1f5f9; gap: 1rem; }
        .detail-row:last-child { border-bottom: none; }
        .detail-key { font-size: 0.8125rem; color: #64748b; font-weight: 500; flex-shrink: 0; }
        .detail-val { font-size: 0.8125rem; font-weight: 600; color: #0f172a; text-align: right; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #f8fafc; font-size: 0.8125rem; }
        .info-row:last-child { border-bottom: none; }
        .info-key { color: #64748b; }
        .info-val { font-weight: 600; color: #1e293b; }
        .text-muted { color: #94a3b8; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        nav[aria-label="pagination"] { padding: 1rem 1.25rem; border-top: 1px solid #f1f5f9; }
        .pagination { display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap; }
        .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.5rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; color: #475569; text-decoration: none; border: 1px solid #e2e8f0; background: #fff; transition: all 0.15s; }
        .pagination .page-item .page-link:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .pagination .page-item.active .page-link { background: #4f46e5; border-color: #4f46e5; color: white; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; cursor: not-allowed; }

        /* Page container alias */
        .page-container { width: 100%; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .mt-1 { margin-top: 0.5rem; } .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; } .mb-3 { margin-bottom: 1.5rem; }
        .gap-1 { gap: 0.5rem; } .gap-2 { gap: 1rem; }
        .flex { display: flex; } .flex-col { flex-direction: column; } .items-center { align-items: center; } .justify-between { justify-content: space-between; } .flex-wrap { flex-wrap: wrap; } .flex-1 { flex: 1; }
        .w-full { width: 100%; } .font-bold { font-weight: 700; } .font-semibold { font-weight: 600; }
        .text-sm { font-size: 0.875rem; } .text-xs { font-size: 0.75rem; }
        .text-green { color: #059669; } .text-red { color: #ef4444; } .text-blue { color: #2563eb; } .text-gray { color: #64748b; }
        .bg-white { background: #fff; } .rounded { border-radius: 8px; } .rounded-lg { border-radius: 12px; }
        .border { border: 1px solid #e2e8f0; } .shadow { box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .p-1 { padding: 0.5rem; } .p-2 { padding: 1rem; } .p-3 { padding: 1.5rem; }
        .px-3 { padding-left: 1.5rem; padding-right: 1.5rem; } .py-2 { padding-top: 1rem; padding-bottom: 1rem; }
        .overflow-x-auto { overflow-x: auto; }
        .inline-flex { display: inline-flex; }
        .space-x-2 > * + * { margin-left: 0.5rem; }
        .hidden { display: none; }
        .action-btns { display: flex; gap: 0.375rem; align-items: center; flex-wrap: wrap; }
        .two-col { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; align-items: start; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } .grid-2 { grid-template-columns: 1fr; } .grid-3 { grid-template-columns: 1fr; } .grid-4 { grid-template-columns: 1fr 1fr; } }

        /* ===== PREMIUM PAGE HEADER ===== */
        .ph { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
        .ph-left { display:flex; align-items:center; gap:0.875rem; }
        .ph-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; }
        .ph-icon.indigo { background:linear-gradient(135deg,#6366f1,#8b5cf6); box-shadow:0 4px 14px rgba(99,102,241,.35); }
        .ph-icon.emerald { background:linear-gradient(135deg,#10b981,#059669); box-shadow:0 4px 14px rgba(16,185,129,.35); }
        .ph-icon.amber { background:linear-gradient(135deg,#f59e0b,#d97706); box-shadow:0 4px 14px rgba(245,158,11,.35); }
        .ph-icon.rose { background:linear-gradient(135deg,#f43f5e,#e11d48); box-shadow:0 4px 14px rgba(244,63,94,.35); }
        .ph-icon.blue { background:linear-gradient(135deg,#3b82f6,#2563eb); box-shadow:0 4px 14px rgba(59,130,246,.35); }
        .ph-icon.teal { background:linear-gradient(135deg,#14b8a6,#0d9488); box-shadow:0 4px 14px rgba(20,184,166,.35); }
        .ph-icon.slate { background:linear-gradient(135deg,#475569,#334155); box-shadow:0 4px 14px rgba(71,85,105,.35); }
        .ph-title { font-size:1.375rem; font-weight:800; color:#0f172a; letter-spacing:-0.03em; margin:0; line-height:1.2; }
        .ph-subtitle { font-size:0.8125rem; color:#64748b; margin-top:3px; }
        .ph-actions { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; }
        .ph-breadcrumb { display:flex; align-items:center; gap:0.4rem; font-size:0.78rem; color:#94a3b8; margin-bottom:0.875rem; }
        .ph-breadcrumb a { color:#94a3b8; text-decoration:none; transition:color 0.15s; }
        .ph-breadcrumb a:hover { color:#475569; }
        .ph-breadcrumb-sep { color:#cbd5e1; }

        /* ===== PREMIUM STAT CARDS ===== */
        .stat-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:1.375rem; display:flex; flex-direction:column; gap:0.75rem; transition:var(--transition); box-shadow:var(--shadow-sm); position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; right:0; width:80px; height:80px; border-radius:50%; opacity:0.07; transform:translate(20px,-20px); }
        .stat-card.indigo::before { background:#4f46e5; }
        .stat-card.emerald::before { background:#059669; }
        .stat-card.amber::before { background:#f59e0b; }
        .stat-card.rose::before { background:#e11d48; }
        .stat-card.blue::before { background:#2563eb; }
        .stat-card:hover { transform:translateY(-3px); box-shadow:var(--shadow-lg); border-color:#e0e7ff; }
        .stat-card-row { display:flex; align-items:center; justify-content:space-between; }
        .stat-icon { width:46px; height:46px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
        .stat-icon.indigo { background:linear-gradient(135deg,#eef2ff,#e0e7ff); }
        .stat-icon.emerald { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
        .stat-icon.amber { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
        .stat-icon.rose { background:linear-gradient(135deg,#fff1f2,#ffe4e6); }
        .stat-icon.blue { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
        .stat-icon.purple { background:linear-gradient(135deg,#faf5ff,#ede9fe); }
        .stat-trend { display:inline-flex; align-items:center; gap:0.2rem; font-size:0.72rem; font-weight:700; padding:0.15rem 0.5rem; border-radius:99px; }
        .stat-trend.up { background:#dcfce7; color:#15803d; }
        .stat-trend.down { background:#fee2e2; color:#b91c1c; }
        .stat-trend.neutral { background:#f1f5f9; color:#64748b; }
        .stat-label { font-size:0.6875rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:0.07em; margin-bottom:0.25rem; }
        .stat-value { font-size:1.875rem; font-weight:800; letter-spacing:-0.03em; line-height:1; margin-bottom:0.125rem; animation: countUp 0.4s ease both; }
        .stat-value.indigo { color:#4f46e5; }
        .stat-value.emerald { color:#059669; }
        .stat-value.amber { color:#d97706; }
        .stat-value.rose { color:#e11d48; }
        .stat-value.blue { color:#2563eb; }
        .stat-value.purple { color:#7c3aed; }

        /* ===== PREMIUM TABLE ===== */
        .tbl-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; gap:1rem; flex-wrap:wrap; }
        .tbl-title { font-size:0.9375rem; font-weight:700; color:#0f172a; }
        .tbl-meta { font-size:0.75rem; color:#94a3b8; margin-top:1px; }
        .filter-bar { padding:0.875rem 1.25rem; background:#f8fafc; border-bottom:1px solid #f1f5f9; display:flex; gap:0.625rem; flex-wrap:wrap; align-items:flex-end; }
        .data-table th { background:linear-gradient(180deg,#f8fafc,#f4f8fc); color:#475569; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; padding:0.75rem 1rem; text-align:left; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
        .data-table td { padding:0.9rem 1rem; border-bottom:1px solid #f4f7fc; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .data-table tbody tr:last-child td { border-bottom:none; }
        .data-table tbody tr:hover td { background:linear-gradient(90deg,#fafbff,#f8f9ff); }
        .data-table tbody tr { transition:background 0.15s; }
        .td-main { font-weight:600; color:#1e293b; }
        .td-sub { font-size:0.72rem; color:#94a3b8; margin-top:2px; }

        /* ===== ACTION BUTTON GROUP ===== */
        .act-grp { display:flex; gap:0.3rem; align-items:center; }
        .act-btn { display:inline-flex; align-items:center; gap:0.25rem; padding:0.3rem 0.625rem; border-radius:6px; font-size:0.72rem; font-weight:600; border:1px solid; cursor:pointer; text-decoration:none; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.4; }
        .act-btn-view { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .act-btn-view:hover { background:#dbeafe; border-color:#93c5fd; }
        .act-btn-edit { background:#fffbeb; color:#92400e; border-color:#fde68a; }
        .act-btn-edit:hover { background:#fef3c7; border-color:#fcd34d; }
        .act-btn-del { background:#fff1f2; color:#be123c; border-color:#fecdd3; }
        .act-btn-del:hover { background:#ffe4e6; border-color:#fda4af; }
        .act-btn-success { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .act-btn-success:hover { background:#d1fae5; }

        /* ===== FORM SECTION CARD ===== */
        .form-card { background:#fff; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; margin-bottom:1.25rem; box-shadow:var(--shadow-sm); }
        .form-card-header { padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.75rem; background:linear-gradient(180deg,#fdfdfe,#f8fafc); }
        .form-card-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:0.9375rem; flex-shrink:0; }
        .form-card-icon.indigo { background:#eef2ff; }
        .form-card-icon.emerald { background:#ecfdf5; }
        .form-card-icon.amber { background:#fffbeb; }
        .form-card-icon.rose { background:#fff1f2; }
        .form-card-title { font-size:0.875rem; font-weight:700; color:#1e293b; }
        .form-card-subtitle { font-size:0.72rem; color:#94a3b8; margin-top:1px; }
        .form-card-body { padding:1.375rem; }
        .form-divider { display:flex; align-items:center; gap:0.75rem; margin:1.25rem 0; }
        .form-divider::before, .form-divider::after { content:''; flex:1; height:1px; background:#f1f5f9; }
        .form-divider-text { font-size:0.72rem; font-weight:600; color:#94a3b8; white-space:nowrap; text-transform:uppercase; letter-spacing:0.06em; }

        /* ===== FLOATING SAVE BAR ===== */
        .floating-bar { position:sticky; bottom:0; left:0; right:0; background:rgba(255,255,255,0.95); backdrop-filter:blur(12px); border-top:1px solid #e2e8f0; padding:0.875rem 1.375rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; z-index:50; box-shadow:0 -4px 20px rgba(0,0,0,0.06); flex-wrap:wrap; }
        .floating-bar-info { font-size:0.8125rem; color:#64748b; }
        .floating-bar-actions { display:flex; gap:0.625rem; align-items:center; }

        /* ===== QUICK ACTION CARDS ===== */
        .qa-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; }
        .qa-card { display:flex; flex-direction:column; gap:0.625rem; padding:1.25rem; border-radius:14px; text-decoration:none; transition:var(--transition); position:relative; overflow:hidden; cursor:pointer; border:1px solid rgba(255,255,255,0.1); }
        .qa-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(0,0,0,0.15); }
        .qa-card-icon { font-size:1.5rem; }
        .qa-card-title { font-size:0.9375rem; font-weight:700; color:white; }
        .qa-card-subtitle { font-size:0.75rem; color:rgba(255,255,255,0.75); }
        .qa-card-arrow { position:absolute; bottom:1rem; right:1rem; width:28px; height:28px; border-radius:50%; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; font-size:0.875rem; transition:var(--transition); }
        .qa-card:hover .qa-card-arrow { background:rgba(255,255,255,0.35); transform:translateX(3px); }
        .qa-indigo { background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); }
        .qa-blue { background:linear-gradient(135deg,#2563eb 0%,#0ea5e9 100%); }
        .qa-emerald { background:linear-gradient(135deg,#059669 0%,#10b981 100%); }
        .qa-amber { background:linear-gradient(135deg,#d97706 0%,#f59e0b 100%); }
        .qa-rose { background:linear-gradient(135deg,#e11d48 0%,#f43f5e 100%); }
        .qa-slate { background:linear-gradient(135deg,#334155 0%,#475569 100%); }
        .qa-teal { background:linear-gradient(135deg,#0d9488 0%,#14b8a6 100%); }

        /* ===== PREMIUM PANEL ===== */
        .panel { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:var(--shadow-sm); }
        .panel-header { display:flex; align-items:center; justify-content:space-between; padding:1rem 1.375rem; border-bottom:1px solid #f1f5f9; gap:1rem; background:linear-gradient(180deg,#fdfdfe,#f8fafc); }
        .panel-title { font-size:0.9375rem; font-weight:700; color:#0f172a; }
        .panel-subtitle { font-size:0.6875rem; color:#94a3b8; margin-top:2px; }
        .panel-action { font-size:0.75rem; color:#6366f1; text-decoration:none; font-weight:600; transition:var(--transition); white-space:nowrap; background:#eef2ff; padding:0.3rem 0.75rem; border-radius:99px; }
        .panel-action:hover { color:#4f46e5; background:#e0e7ff; }
        .panel-body { padding:1.375rem; }

        /* ===== STATUS & BADGES ===== */
        .badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.65rem; border-radius:999px; font-size:0.6875rem; font-weight:600; white-space:nowrap; }
        .badge-indigo { background:#eef2ff; color:#4338ca; }
        .badge-blue { background:#dbeafe; color:#1d4ed8; }
        .badge-success { background:#dcfce7; color:#15803d; }
        .badge-danger { background:#fee2e2; color:#b91c1c; }
        .badge-warning { background:#fef3c7; color:#b45309; }
        .badge-gray { background:#f1f5f9; color:#475569; }
        .badge-purple { background:#f3e8ff; color:#7e22ce; }
        .badge-teal { background:#ccfbf1; color:#0f766e; }
        .badge-green { background:#ecfdf5; color:#059669; }
        .badge-yellow { background:#fffbeb; color:#d97706; }
        .badge-dot { position:relative; padding-left:1.25rem; }
        .badge-dot::before { content:''; position:absolute; left:0.45rem; top:50%; transform:translateY(-50%); width:6px; height:6px; border-radius:50%; background:currentColor; animation:pulse-dot 1.5s ease infinite; }

        /* ===== ALERTS ===== */
        .alert { padding:0.875rem 1.125rem; border-radius:10px; margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:0.625rem; font-size:0.875rem; font-weight:500; line-height:1.5; animation:fadeSlideIn 0.3s ease; }
        .alert-success { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
        .alert-danger { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }
        .alert-warning { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
        .alert-info { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }

        /* ===== CARD & UTILS ===== */
        .card { background:#fff; border-radius:14px; border:1px solid #e2e8f0; box-shadow:var(--shadow-sm); overflow:hidden; }
        .card-premium { background:linear-gradient(135deg,#fff 0%,#fafbff 100%); border-radius:16px; border:1px solid #e2e8f0; box-shadow:0 2px 12px rgba(99,102,241,0.06); overflow:hidden; }
        .page-container { width:100%; animation:fadeSlideIn 0.3s ease; }
        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
        .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem; }
        .mt-1{margin-top:.5rem}.mt-2{margin-top:1rem}.mt-3{margin-top:1.5rem}
        .mb-1{margin-bottom:.5rem}.mb-2{margin-bottom:1rem}.mb-3{margin-bottom:1.5rem}
        .gap-1{gap:.5rem}.gap-2{gap:1rem}.flex{display:flex}.flex-col{flex-direction:column}
        .items-center{align-items:center}.justify-between{justify-content:space-between}.flex-wrap{flex-wrap:wrap}.flex-1{flex:1}
        .w-full{width:100%}.font-bold{font-weight:700}.font-semibold{font-weight:600}
        .text-sm{font-size:.875rem}.text-xs{font-size:.75rem}
        .text-green{color:#059669}.text-red{color:#ef4444}.text-blue{color:#2563eb}.text-gray{color:#64748b}
        .text-muted{color:#94a3b8}.text-right{text-align:right}.text-center{text-align:center}
        .bg-white{background:#fff}.rounded{border-radius:8px}.rounded-lg{border-radius:12px}
        .border{border:1px solid #e2e8f0}.shadow{box-shadow:var(--shadow-sm)}
        .p-1{padding:.5rem}.p-2{padding:1rem}.p-3{padding:1.5rem}
        .px-3{padding-left:1.5rem;padding-right:1.5rem}.py-2{padding-top:1rem;padding-bottom:1rem}
        .overflow-x-auto{overflow-x:auto}.inline-flex{display:inline-flex}
        .space-x-2>*+*{margin-left:.5rem}.hidden{display:none}
        .action-btns{display:flex;gap:.375rem;align-items:center;flex-wrap:wrap}
        .two-col{display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;align-items:start}

        /* ===== FORM COMPONENTS ===== */
        .form-group { margin-bottom:1.125rem; display:flex; flex-direction:column; gap:0.3125rem; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .form-row-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
        .form-label { font-size:0.8125rem; font-weight:600; color:#374151; }
        .form-label .required { color:#ef4444; margin-left:2px; }
        .required { color:#ef4444; }
        .form-input { width:100%; padding:0.5875rem 0.9rem; border-radius:8px; border:1.5px solid #e2e8f0; background:#fff; color:#1e293b; font-size:0.875rem; outline:none; transition:var(--transition); font-family:inherit; line-height:1.5; }
        .form-input:focus { border-color:#6366f1; box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
        .form-input:hover:not(:focus):not(:disabled) { border-color:#c7d2fe; }
        .form-input:disabled { background:#f8fafc; color:#94a3b8; cursor:not-allowed; }
        .form-input::placeholder { color:#94a3b8; }
        select.form-input { cursor:pointer; }
        textarea.form-input { resize:vertical; min-height:80px; }
        .input-error { border-color:#ef4444 !important; background:#fff5f5; }
        .input-error:focus { box-shadow:0 0 0 3px rgba(239,68,68,0.12) !important; }
        .form-error { font-size:0.75rem; color:#ef4444; margin-top:0.25rem; display:flex; align-items:center; gap:0.25rem; }
        .form-hint { font-size:0.75rem; color:#94a3b8; margin-top:0.25rem; }
        .form-prefix { position:relative; }
        .form-prefix-text { position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); font-size:0.8125rem; color:#64748b; font-weight:500; pointer-events:none; }
        .form-prefix .form-input { padding-left:2.25rem; }

        /* Tables */
        .table-wrapper { overflow-x:auto; width:100%; -webkit-overflow-scrolling:touch; }
        .data-table { width:100%; min-width:600px; border-collapse:separate; border-spacing:0; }
        .data-table th { background:linear-gradient(180deg,#f8fafc,#f4f8fc); color:#475569; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; padding:0.75rem 1rem; text-align:left; border-bottom:2px solid #e2e8f0; white-space:nowrap; }
        .data-table td { padding:0.9rem 1rem; border-bottom:1px solid #f4f7fc; font-size:0.8125rem; color:#374151; vertical-align:middle; }
        .data-table tbody tr:last-child td { border-bottom:none; }
        .data-table tbody tr:hover td { background:linear-gradient(90deg,#fafbff,#f8f9ff); }
        .data-table tbody tr { transition:background 0.15s; }
        nav[aria-label="pagination"] { padding:1rem 1.25rem; border-top:1px solid #f1f5f9; }
        .pagination { display:flex; align-items:center; gap:.25rem; flex-wrap:wrap; }
        .pagination .page-item .page-link { display:flex; align-items:center; justify-content:center; min-width:32px; height:32px; padding:0 .5rem; border-radius:6px; font-size:.8125rem; font-weight:500; color:#475569; text-decoration:none; border:1px solid #e2e8f0; background:#fff; transition:var(--transition); }
        .pagination .page-item .page-link:hover { background:#f1f5f9; border-color:#cbd5e1; }
        .pagination .page-item.active .page-link { background:#4f46e5; border-color:#4f46e5; color:white; }
        .pagination .page-item.disabled .page-link { opacity:.4; cursor:not-allowed; }

        /* Buttons */
        .btn-primary { background:linear-gradient(135deg,#4f46e5,#6366f1); color:white; padding:.5rem 1.125rem; border-radius:8px; font-weight:600; font-size:.8125rem; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.5; box-shadow:0 2px 6px rgba(79,70,229,.3); }
        .btn-primary:hover { background:linear-gradient(135deg,#4338ca,#4f46e5); box-shadow:0 6px 16px rgba(79,70,229,.4); transform:translateY(-1px); }
        .btn-primary:active { transform:none; box-shadow:0 2px 6px rgba(79,70,229,.3); }
        .btn-secondary { background:#f8fafc; color:#475569; padding:.5rem 1.125rem; border-radius:8px; font-weight:600; font-size:.8125rem; border:1.5px solid #e2e8f0; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.5; }
        .btn-secondary:hover { background:#f1f5f9; border-color:#cbd5e1; color:#1e293b; }
        .btn-success { background:linear-gradient(135deg,#059669,#10b981); color:white; padding:.5rem 1.125rem; border-radius:8px; font-weight:600; font-size:.8125rem; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.5; box-shadow:0 2px 6px rgba(5,150,105,.3); }
        .btn-success:hover { box-shadow:0 6px 16px rgba(5,150,105,.4); transform:translateY(-1px); }
        .btn-danger { background:linear-gradient(135deg,#ef4444,#f87171); color:white; padding:.5rem 1.125rem; border-radius:8px; font-weight:600; font-size:.8125rem; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.5; }
        .btn-danger:hover { background:linear-gradient(135deg,#dc2626,#ef4444); box-shadow:0 4px 12px rgba(239,68,68,.35); }
        .btn-warning { background:linear-gradient(135deg,#f59e0b,#fbbf24); color:white; padding:.5rem 1.125rem; border-radius:8px; font-weight:600; font-size:.8125rem; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:.375rem; transition:var(--transition); white-space:nowrap; font-family:inherit; line-height:1.5; }
        .btn-warning:hover { background:linear-gradient(135deg,#d97706,#f59e0b); }
        .btn-sm { padding:.3125rem .75rem !important; font-size:.75rem !important; border-radius:6px !important; gap:.25rem !important; }
        .btn-xs { padding:.1875rem .5rem !important; font-size:.6875rem !important; border-radius:5px !important; }

        /* Detail / Info */
        .detail-row { display:flex; justify-content:space-between; align-items:flex-start; padding:0.7rem 0; border-bottom:1px solid #f4f7fc; gap:1rem; }
        .detail-row:last-child { border-bottom:none; }
        .detail-key { font-size:0.8125rem; color:#64748b; font-weight:500; flex-shrink:0; }
        .detail-val { font-size:0.8125rem; font-weight:600; color:#0f172a; text-align:right; }
        .detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .info-row { display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid #f8fafc; font-size:0.8125rem; }
        .info-row:last-child { border-bottom:none; }
        .info-key { color:#64748b; }
        .info-val { font-weight:600; color:#1e293b; }

        /* Empty states */
        .empty-state { text-align:center; padding:3.5rem 1.5rem; color:#94a3b8; }
        .empty-state-icon { font-size:3rem; margin-bottom:1rem; display:block; }
        .empty-state-title { font-size:1rem; font-weight:600; color:#64748b; margin-bottom:0.375rem; }
        .empty-state-desc { font-size:0.8125rem; margin-bottom:1.25rem; }

        /* Page Header legacy */
        .page-header { display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; flex-wrap:wrap; }
        .page-header-title { font-size:1.25rem; font-weight:800; color:#0f172a; letter-spacing:-0.02em; margin-bottom:0.25rem; }
        .page-header-subtitle { font-size:0.8125rem; color:#64748b; }
        .page-header-actions { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; }

        /* Responsive */
        @media (max-width:900px) { .two-col{grid-template-columns:1fr} .grid-2{grid-template-columns:1fr} .grid-3{grid-template-columns:1fr} .grid-4{grid-template-columns:1fr 1fr} }
        @media (max-width:1024px) {
            .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
            .main-wrapper{margin-left:0 !important}.mobile-menu-btn{display:flex !important}
            .topbar-username{display:none}.form-row{grid-template-columns:1fr}.form-row-3{grid-template-columns:1fr}.detail-grid{grid-template-columns:1fr}
        }
        @media (max-width:640px) { .page-content{padding:1rem}.topbar{padding:0 1rem}.stat-grid{grid-template-columns:1fr 1fr}.qa-grid{grid-template-columns:1fr} }
        @media (max-width:480px) { .stat-grid{grid-template-columns:1fr} }
    </style>

    @stack('styles')
</head>
<body>
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <div class="sidebar-logo-icon">🧾</div>
            <span class="sidebar-logo-text">DOD<span>POS</span></span>
            <span class="sidebar-logo-badge">v1.0</span>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-search">
                <input id="sidebar-search" type="text" placeholder="Cari menu…" autocomplete="off">
            </div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-item-icon">🏠</span><span>Dashboard</span>
            </a>

            {{-- Sales Order --}}
            @can('view_sales_order')
            <a href="{{ route('sales-order.index') }}" class="nav-item {{ request()->routeIs('sales-order.*') ? 'active' : '' }}">
                <span class="nav-item-icon">📄</span><span>Sales Order</span>
            </a>
            @endcan

            <div class="sidebar-divider"></div>

            {{-- Operasional --}}
            @php $role = strtolower(auth()->user()?->role ?? ''); @endphp
            @canany(['view_kategori_operasional', 'view_kendaraan_operasional', 'view_pengeluaran_operasional', 'view_riwayat_operasional', 'view_sesi_operasional'])
            @if($role === 'admin2')
            {{-- FLAT for admin2: operasional tanpa group header --}}
            <div class="sidebar-divider"></div>
            @can('view_kategori_operasional')
            <a href="{{ route('operasional.kategori.index') }}" class="nav-item {{ request()->routeIs('operasional.kategori.*') ? 'active' : '' }}"><span class="nav-item-icon">🗂️</span><span>Kategori Opr.</span></a>
            @endcan
            @can('view_kendaraan_operasional')
            <a href="{{ route('operasional.kendaraan.index') }}" class="nav-item {{ request()->routeIs('operasional.kendaraan.*') ? 'active' : '' }}"><span class="nav-item-icon">🚚</span><span>Kendaraan</span></a>
            @endcan
            @can('view_pengeluaran_operasional')
            <a href="{{ route('operasional.pengeluaran.create') }}" class="nav-item {{ request()->routeIs('operasional.pengeluaran.*') ? 'active' : '' }}"><span class="nav-item-icon">💸</span><span>Pengeluaran Opr.</span></a>
            @endcan
            @can('view_riwayat_operasional')
            <a href="{{ route('operasional.riwayat.index') }}" class="nav-item {{ request()->routeIs('operasional.riwayat.*') ? 'active' : '' }}"><span class="nav-item-icon">📜</span><span>Riwayat Opr.</span></a>
            @endcan
            @can('view_sesi_operasional')
            <a href="{{ route('operasional.sesi.index') }}" class="nav-item {{ request()->routeIs('operasional.sesi.*') ? 'active' : '' }}"><span class="nav-item-icon">📊</span><span>Laporan Modal Opr.</span></a>
            @endcan
            @else
            <div class="nav-group {{ request()->routeIs('operasional.*') ? 'open' : '' }}" id="grp-operasional">
                <button class="nav-group-header" onclick="toggleGroup('grp-operasional')" type="button">
                    <span class="nav-group-label">⚙️ OPERASIONAL</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_kategori_operasional')
                    <a href="{{ route('operasional.kategori.index') }}" class="nav-item {{ request()->routeIs('operasional.kategori.*') ? 'active' : '' }}"><span class="nav-item-icon">🗂️</span><span>Kategori</span></a>
                    @endcan
                    @can('view_kendaraan_operasional')
                    <a href="{{ route('operasional.kendaraan.index') }}" class="nav-item {{ request()->routeIs('operasional.kendaraan.*') ? 'active' : '' }}"><span class="nav-item-icon">🚚</span><span>Data Kendaraan</span></a>
                    @endcan
                    @can('view_sesi_operasional')
                    @php $activeOpSession = \App\Models\OperationalSession::where('status','open')->latest()->first(); @endphp
                    @if($activeOpSession)
                        @can('manage_sesi_operasional')
                        <a href="#" onclick="event.preventDefault();if(confirm('Yakin tutup sesi operasional?'))document.getElementById('close-ops-form').submit();" class="nav-item" style="color:#f87171;"><span class="nav-item-icon">🔒</span><span>Tutup Modal Opr.</span></a>
                        <form id="close-ops-form" method="POST" action="{{ route('operasional.close_session') }}" style="display:none;">@csrf</form>
                        @endcan
                    @endif
                    @endcan
                    @can('view_pengeluaran_operasional')
                    <a href="{{ route('operasional.pengeluaran.create') }}" class="nav-item {{ request()->routeIs('operasional.pengeluaran.*') ? 'active' : '' }}"><span class="nav-item-icon">💸</span><span>Pengeluaran</span></a>
                    @endcan
                    @can('view_riwayat_operasional')
                    <a href="{{ route('operasional.riwayat.index') }}" class="nav-item {{ request()->routeIs('operasional.riwayat.*') ? 'active' : '' }}"><span class="nav-item-icon">📜</span><span>Riwayat Operasional</span></a>
                    @endcan
                    @can('view_sesi_operasional')
                    <a href="{{ route('operasional.sesi.index') }}" class="nav-item {{ request()->routeIs('operasional.sesi.*') ? 'active' : '' }}"><span class="nav-item-icon">📊</span><span>Laporan Modal Opr.</span></a>
                    @endcan
                </div>
            </div>
            @endif
            @endcanany

            {{-- SDM Self-Service --}}
            @php $role = strtolower(auth()->user()?->role ?? ''); @endphp
            @if(auth()->check() && $role !== 'supervisor')
            @if(in_array($role, ['admin1', 'admin2', 'admin3', 'admin4']))
            {{-- FLAT for admin1/admin2/admin3/admin4: tanpa group header --}}
            <a href="{{ route('sdm.absensi.self_panel') }}" class="nav-item {{ request()->routeIs('sdm.absensi.self_*') ? 'active' : '' }}"><span class="nav-item-icon">📍</span><span>Absen Saya</span></a>
            <a href="{{ route('sdm.cuti.self_index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.self_*') ? 'active' : '' }}"><span class="nav-item-icon">📝</span><span>Cuti Saya</span></a>
            <a href="{{ route('sdm.penggajian.self_index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.self_*') ? 'active' : '' }}"><span class="nav-item-icon">💸</span><span>Gaji Saya</span></a>
            <a href="{{ route('sdm.potongan.self_index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.self_*') ? 'active' : '' }}"><span class="nav-item-icon">➖</span><span>Potongan Saya</span></a>
            @else
            <div class="nav-group {{ request()->routeIs('sdm.absensi.self_*','sdm.cuti.self_*','sdm.penggajian.self_*','sdm.potongan.self_*') ? 'open' : '' }}" id="grp-sdm-self">
                <button class="nav-group-header" onclick="toggleGroup('grp-sdm-self')" type="button">
                    <span class="nav-group-label">🧑‍🤝‍🧑 SDM Saya</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('sdm.absensi.self_panel') }}" class="nav-item {{ request()->routeIs('sdm.absensi.self_*') ? 'active' : '' }}"><span class="nav-item-icon">📍</span><span>Absen Saya</span></a>
                    <a href="{{ route('sdm.cuti.self_index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.self_*') ? 'active' : '' }}"><span class="nav-item-icon">📝</span><span>Cuti Saya</span></a>
                    <a href="{{ route('sdm.penggajian.self_index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.self_*') ? 'active' : '' }}"><span class="nav-item-icon">💸</span><span>Gaji Saya</span></a>
                    <a href="{{ route('sdm.potongan.self_index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.self_*') ? 'active' : '' }}"><span class="nav-item-icon">➖</span><span>Potongan Saya</span></a>
                </div>
            </div>
            @endif
            @endif

            {{-- SDM / HR (Supervisor) --}}
            @if(auth()->check() && strtolower((string) auth()->user()->role) === 'supervisor')
            <div class="nav-group {{ request()->routeIs('sdm.*') ? 'open' : '' }}" id="grp-sdm">
                <button class="nav-group-header" onclick="toggleGroup('grp-sdm')" type="button">
                    <span class="nav-group-label">🧑‍🤝‍🧑 HR &amp; Payroll</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('sdm.karyawan.index') }}" class="nav-item {{ request()->routeIs('sdm.karyawan.*') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Data Karyawan</span></a>
                    <a href="{{ route('sdm.absensi.index') }}" class="nav-item {{ request()->routeIs('sdm.absensi.index') ? 'active' : '' }}"><span class="nav-item-icon">🕒</span><span>Absensi</span></a>
                    <a href="{{ route('sdm.absensi.monthly') }}" class="nav-item {{ request()->routeIs('sdm.absensi.monthly*') ? 'active' : '' }}"><span class="nav-item-icon">📊</span><span>Rekap Absensi</span></a>
                    <a href="{{ route('sdm.cuti.index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.*') ? 'active' : '' }}"><span class="nav-item-icon">📝</span><span>Cuti</span></a>
                    <a href="{{ route('sdm.libur.index') }}" class="nav-item {{ request()->routeIs('sdm.libur.*') ? 'active' : '' }}"><span class="nav-item-icon">📅</span><span>Kalender Libur</span></a>
                    <a href="{{ route('sdm.potongan.index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.*') ? 'active' : '' }}"><span class="nav-item-icon">✂️</span><span>Potongan & Bonus</span></a>
                    <a href="{{ route('sdm.penggajian.index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Penggajian</span></a>
                    <a href="{{ route('sdm.performa.index') }}" class="nav-item {{ request()->routeIs('sdm.performa.*') ? 'active' : '' }}"><span class="nav-item-icon">📈</span><span>Performa</span></a>
                </div>
            </div>
            @endif

            {{-- Point of Sale --}}
            @canany(['view_pos_kasir', 'view_sesi_kasir', 'view_transaksi', 'view_pelanggan', 'view_hutang_piutang', 'view_daftar_harga'])
            @php $posActive = request()->is('kasir*') || request()->routeIs('transaksi.*','pelanggan.*','hutang.*','harga.*'); @endphp
            @if(in_array($role, ['admin1', 'admin2']))
            {{-- FLAT for admin1/admin2: tampil langsung berdasarkan role, tanpa cek permission --}}
            <div class="sidebar-divider"></div>
            <a href="{{ route('kasir.index') }}" class="nav-item {{ request()->is('kasir*') ? 'active' : '' }}"><span class="nav-item-icon">🖥️</span><span>Kasir / POS</span></a>
            <a href="{{ route('kasir.session') }}" class="nav-item {{ request()->routeIs('kasir.session') ? 'active' : '' }}"><span class="nav-item-icon">📊</span><span>Sesi Kasir</span></a>
            <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"><span class="nav-item-icon">🧾</span><span>Transaksi</span></a>
            <a href="{{ route('pelanggan.index') }}" class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Pelanggan</span></a>
            <a href="{{ route('harga.index') }}" class="nav-item {{ request()->routeIs('harga.*') ? 'active' : '' }}"><span class="nav-item-icon">💲</span><span>Daftar Harga</span></a>
            @else
            <div class="nav-group {{ $posActive ? 'open' : '' }}" id="grp-pos">
                <button class="nav-group-header" onclick="toggleGroup('grp-pos')" type="button">
                    <span class="nav-group-label">🏪 POINT OF SALE</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_pos_kasir')
                    <a href="{{ route('kasir.index') }}" class="nav-item {{ request()->is('kasir*') ? 'active' : '' }}"><span class="nav-item-icon">🖥️</span><span>Kasir / POS</span></a>
                    @endcan
                    @can('view_sesi_kasir')
                    <a href="{{ route('kasir.session') }}" class="nav-item {{ request()->routeIs('kasir.session') ? 'active' : '' }}"><span class="nav-item-icon">📊</span><span>Sesi Kasir</span></a>
                    @php $activeSession = \App\Models\PosSession::where('status','open')->latest()->first(); @endphp
                    @if($activeSession)
                        @can('delete_sesi_kasir')
                        <a href="#" onclick="event.preventDefault();if(confirm('Yakin tutup sesi kasir?'))document.getElementById('close-kasir-form').submit();" class="nav-item" style="color:#f87171;"><span class="nav-item-icon">🔒</span><span>Tutup Kasir</span></a>
                        <form id="close-kasir-form" method="POST" action="{{ route('kasir.close_session') }}" style="display:none;">@csrf</form>
                        @endcan
                    @endif
                    @endcan
                    @can('view_transaksi')
                    <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"><span class="nav-item-icon">🧾</span><span>Transaksi</span></a>
                    @endcan
                    @can('view_pelanggan')
                    <a href="{{ route('pelanggan.index') }}" class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Pelanggan</span></a>
                    @endcan
                    @can('view_hutang_piutang')
                    <a href="{{ route('hutang.index') }}" class="nav-item {{ request()->routeIs('hutang.*') ? 'active' : '' }}"><span class="nav-item-icon">💳</span><span>Hutang &amp; Piutang</span></a>
                    @endcan
                    @can('view_daftar_harga')
                    <a href="{{ route('harga.index') }}" class="nav-item {{ request()->routeIs('harga.*') ? 'active' : '' }}"><span class="nav-item-icon">💲</span><span>Daftar Harga</span></a>
                    @endcan
                </div>
            </div>
            @endif
            @endcanany

            <div class="sidebar-divider"></div>

            {{-- Pasukan Garuda --}}
            @canany(['view_pasgar_pesanan', 'view_pasgar_pelanggan', 'view_pasgar_stok', 'view_pasgar_pengembalian', 'view_pasgar_penjualan', 'view_pasgar_penagihan', 'view_pasgar_setoran', 'view_pasgar_jadwal', 'view_pasgar_kunjungan', 'view_pasgar_anggota', 'view_pasgar_kendaraan'])
            <div class="nav-group {{ request()->routeIs('pasgar.*') ? 'open' : '' }}" id="grp-pasgar">
                <button class="nav-group-header" onclick="toggleGroup('grp-pasgar')" type="button">
                    <span class="nav-group-label">🦅 PASUKAN GARUDA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_pasgar_pesanan')
                    <a href="{{ route('pasgar.loadings.index') }}" class="nav-item {{ request()->routeIs('pasgar.loadings.*') ? 'active' : '' }}"><span class="nav-item-icon">📋</span><span>Pesanan Pasgar</span></a>
                    @endcan
                    @can('view_pasgar_pelanggan')
                    <a href="{{ route('pasgar.pelanggan.index') }}" class="nav-item {{ request()->routeIs('pasgar.pelanggan.*') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Data Pelanggan</span></a>
                    @endcan
                    @can('view_pasgar_stok')
                    <a href="{{ route('pasgar.stok-onhand.index') }}" class="nav-item {{ request()->routeIs('pasgar.stok-onhand.*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Stok On-Hand</span></a>
                    @endcan
                    @can('view_pasgar_pengembalian')
                    <a href="{{ route('pasgar.pengembalian.index') }}" class="nav-item {{ request()->routeIs('pasgar.pengembalian.*') ? 'active' : '' }}"><span class="nav-item-icon">↩️</span><span>Pengembalian Sisa</span></a>
                    @endcan
                    @can('view_pasgar_penjualan')
                    <a href="{{ route('pasgar.penjualan.index') }}" class="nav-item {{ request()->routeIs('pasgar.penjualan.*') ? 'active' : '' }}"><span class="nav-item-icon">🛒</span><span>Penjualan Kanvas</span></a>
                    @endcan
                    @can('view_pasgar_penagihan')
                    <a href="{{ route('pasgar.penagihan.index') }}" class="nav-item {{ request()->routeIs('pasgar.penagihan.*') ? 'active' : '' }}"><span class="nav-item-icon">💳</span><span>Penagihan Piutang</span></a>
                    @endcan
                    @can('view_pasgar_setoran')
                    <a href="{{ route('pasgar.setoran.index') }}" class="nav-item {{ request()->routeIs('pasgar.setoran.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Setoran Harian</span></a>
                    @endcan
                    @can('view_pasgar_jadwal')
                    <a href="{{ route('pasgar.jadwal.index') }}" class="nav-item {{ request()->routeIs('pasgar.jadwal.*') ? 'active' : '' }}"><span class="nav-item-icon">📅</span><span>Jadwal Kunjungan</span></a>
                    @endcan
                    @can('view_pasgar_kunjungan')
                    <a href="{{ route('pasgar.kunjungan.index') }}" class="nav-item {{ request()->routeIs('pasgar.kunjungan.*') ? 'active' : '' }}"><span class="nav-item-icon">📋</span><span>Laporan Kunjungan</span></a>
                    @endcan
                    @can('view_pasgar_anggota')
                    <a href="{{ route('pasgar.anggota.index') }}" class="nav-item {{ request()->routeIs('pasgar.anggota.*') ? 'active' : '' }}"><span class="nav-item-icon">👤</span><span>Daftar Anggota</span></a>
                    @endcan
                    @can('view_pasgar_kendaraan')
                    <a href="{{ route('pasgar.vehicles.index') }}" class="nav-item {{ request()->routeIs('pasgar.vehicles.*') ? 'active' : '' }}"><span class="nav-item-icon">🚗</span><span>Kendaraan</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Modul Kanvas --}}
            @canany(['view_kanvas_loading', 'view_kanvas_rute', 'view_kanvas_setoran'])
            <div class="nav-group {{ request()->routeIs('kanvas.*') ? 'open' : '' }}" id="grp-kanvas">
                <button class="nav-group-header" onclick="toggleGroup('grp-kanvas')" type="button">
                    <span class="nav-group-label">🚐 MODUL KANVAS</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_kanvas_loading')
                    <a href="{{ route('kanvas.loading.index') }}" class="nav-item {{ request()->routeIs('kanvas.loading.*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Loading Armada</span></a>
                    @endcan
                    @can('view_kanvas_rute')
                    <a href="{{ route('kanvas.route.index') }}" class="nav-item {{ request()->routeIs('kanvas.route.*') ? 'active' : '' }}"><span class="nav-item-icon">🗺️</span><span>Journey Plan (Rute)</span></a>
                    @endcan
                    @can('view_kanvas_setoran')
                    <a href="{{ route('kanvas.setoran.index') }}" class="nav-item {{ request()->routeIs('kanvas.setoran.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Validasi Setoran</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Modul Gula --}}
            @canany(['view_gula_stok', 'view_gula_repacking', 'view_gula_loading', 'view_gula_setoran'])
            @php $gulaActive = request()->routeIs('gula.*'); @endphp
            <div class="nav-group {{ $gulaActive ? 'open' : '' }}" id="grp-gula">
                <button class="nav-group-header" onclick="toggleGroup('grp-gula')" type="button">
                    <span class="nav-group-label">🍬 MODUL GULA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_gula_stok')
                    <a href="{{ route('gula.stok.index') }}" class="nav-item {{ request()->routeIs('gula.stok.*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Stok Gudang Induk</span></a>
                    @endcan
                    @can('view_gula_repacking')
                    <a href="{{ route('gula.repacking.index') }}" class="nav-item {{ request()->routeIs('gula.repacking.*') ? 'active' : '' }}"><span class="nav-item-icon">✂️</span><span>Repacking & Susut</span></a>
                    @endcan
                    @can('view_gula_loading')
                    <a href="{{ route('gula.loading.index') }}" class="nav-item {{ request()->routeIs('gula.loading.*') ? 'active' : '' }}"><span class="nav-item-icon">🚚</span><span>Loading Armada</span></a>
                    @endcan
                    @can('view_gula_setoran')
                    <a href="{{ route('gula.setoran.index') }}" class="nav-item {{ request()->routeIs('gula.setoran.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Validasi Setoran</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Modul Mineral --}}
            @canany(['view_mineral_stok', 'view_mineral_loading', 'view_mineral_setoran'])
            @php $mineralActive = request()->routeIs('mineral.*'); @endphp
            <div class="nav-group {{ $mineralActive ? 'open' : '' }}" id="grp-mineral">
                <button class="nav-group-header" onclick="toggleGroup('grp-mineral')" type="button">
                    <span class="nav-group-label">💧 MODUL MINERAL</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_mineral_stok')
                    <a href="{{ route('mineral.stok.index') }}" class="nav-item {{ request()->routeIs('mineral.stok.*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Stok & Mutasi Gudang</span></a>
                    @endcan
                    @can('view_mineral_loading')
                    <a href="{{ route('mineral.loading.index') }}" class="nav-item {{ request()->routeIs('mineral.loading.*') ? 'active' : '' }}"><span class="nav-item-icon">🚚</span><span>Loading Armada</span></a>
                    @endcan
                    @can('view_mineral_setoran')
                    <a href="{{ route('mineral.setoran.index') }}" class="nav-item {{ request()->routeIs('mineral.setoran.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Validasi Setoran</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Minyak --}}
            @canany(['view_minyak_pelanggan', 'view_minyak_setoran'])
            @php $minyakActive = request()->routeIs('minyak.*'); @endphp
            <div class="nav-group {{ $minyakActive ? 'open' : '' }}" id="grp-minyak">
                <button class="nav-group-header" onclick="toggleGroup('grp-minyak')" type="button">
                    <span class="nav-group-label">🛢️ MINYAK</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_minyak_pelanggan')
                    <a href="{{ route('minyak.pelanggan.index') }}" class="nav-item {{ request()->routeIs('minyak.pelanggan.*') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Data Pelanggan</span></a>
                    @endcan
                    @can('view_minyak_setoran')
                    <a href="{{ route('minyak.setoran.index') }}" class="nav-item {{ request()->routeIs('minyak.setoran.*') ? 'active' : '' }}"><span class="nav-item-icon">💰</span><span>Setoran & Retur</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Manajemen Gudang --}}
            @canany(['view_stok_gudang', 'view_penerimaan_barang', 'view_pengeluaran_barang', 'view_opname_stok', 'view_permintaan_barang'])
            @php $gudangActive = request()->routeIs('gudang.*') || request()->routeIs('products.*'); @endphp
            @if(in_array($role, ['admin3', 'admin4']))
            {{-- FLAT for admin3/admin4: tanpa group header --}}
            <div class="sidebar-divider"></div>
            @if($role === 'admin3')
            @can('view_stok_gudang')
            <a href="{{ route('gudang.stok') }}" class="nav-item {{ request()->routeIs('gudang.stok*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Data Stok Gudang</span></a>
            @endcan
            @can('view_penerimaan_barang')
            <a href="{{ route('gudang.inout') }}" class="nav-item {{ request()->routeIs('gudang.inout') ? 'active' : '' }}"><span class="nav-item-icon">🔄</span><span>Masuk &amp; Keluar</span></a>
            <a href="{{ route('gudang.terimapo.index') }}" class="nav-item {{ request()->routeIs('gudang.terimapo.*') ? 'active' : '' }}"><span class="nav-item-icon">📥</span><span>Terima dari PO</span></a>
            <a href="{{ route('gudang.penerimaan') }}" class="nav-item {{ request()->routeIs('gudang.penerimaan*') || request()->routeIs('gudang.terimapo*') ? 'active' : '' }}"><span class="nav-item-icon">📥</span><span>Terima Brg Supplier</span></a>
            @endcan
            @can('view_pengeluaran_barang')
            <a href="{{ route('gudang.pengeluaran') }}" class="nav-item {{ request()->routeIs('gudang.pengeluaran*') || request()->routeIs('gudang.transfer*') ? 'active' : '' }}"><span class="nav-item-icon">📤</span><span>Pengeluaran Gudang Utama</span></a>
            @endcan
            @can('view_opname_stok')
            <a href="{{ route('gudang.opname_sessions.index') }}" class="nav-item {{ request()->routeIs('gudang.opname_sessions.*') || request()->routeIs('gudang.opname_approval.*') ? 'active' : '' }}"><span class="nav-item-icon">🔍</span><span>Opname Stok</span></a>
            @endcan
            @can('view_permintaan_barang')
            <a href="{{ route('gudang.request.index') }}" class="nav-item {{ request()->routeIs('gudang.request.*') ? 'active' : '' }}"><span class="nav-item-icon">🛒</span><span>Permintaan Barang</span></a>
            @endcan
            @elseif($role === 'admin4')
            @can('view_stok_gudang')
            <a href="{{ route('gudang.stok') }}" class="nav-item {{ request()->routeIs('gudang.stok*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Data Stok Gudang</span></a>
            @endcan
            <a href="{{ route('gudang.terima_transfer.index') }}" class="nav-item {{ request()->routeIs('gudang.terima_transfer*') ? 'active' : '' }}"><span class="nav-item-icon">📥</span><span>Terima Transfer Cabang</span></a>
            @can('view_pengeluaran_barang')
            <a href="{{ route('gudang.pengeluaran') }}" class="nav-item {{ request()->routeIs('gudang.pengeluaran*') ? 'active' : '' }}"><span class="nav-item-icon">📤</span><span>Pengeluaran Penjualan</span></a>
            @endcan
            @can('view_opname_stok')
            <a href="{{ route('gudang.opname_sessions.index') }}" class="nav-item {{ request()->routeIs('gudang.opname_sessions.*') || request()->routeIs('gudang.opname_approval.*') ? 'active' : '' }}"><span class="nav-item-icon">🔍</span><span>Opname Stok</span></a>
            @endcan
            @can('view_permintaan_barang')
            <a href="{{ route('gudang.request.index') }}" class="nav-item {{ request()->routeIs('gudang.request.*') ? 'active' : '' }}"><span class="nav-item-icon">🛒</span><span>Permintaan Barang</span></a>
            @endcan
            @endif
            @else
            <div class="nav-group {{ $gudangActive ? 'open' : '' }}" id="grp-gudang">
                <button class="nav-group-header" onclick="toggleGroup('grp-gudang')" type="button">
                    <span class="nav-group-label">🏢 MANAJEMEN GUDANG</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_stok_gudang')
                    <a href="{{ route('gudang.stok') }}" class="nav-item {{ request()->routeIs('gudang.stok*') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Data Stok Gudang</span></a>
                    @endcan
                    @can('view_penerimaan_barang')
                    <a href="{{ route('gudang.terimapo.index') }}" class="nav-item {{ request()->routeIs('gudang.terimapo.*') ? 'active' : '' }}"><span class="nav-item-icon">📥</span><span>Terima dari PO</span></a>
                    <a href="{{ route('gudang.inout') }}" class="nav-item {{ request()->routeIs('gudang.inout') ? 'active' : '' }}"><span class="nav-item-icon">🔄</span><span>Masuk &amp; Keluar</span></a>
                    @endcan
                    @can('view_pengeluaran_barang')
                    <a href="{{ route('gudang.pengeluaran') }}" class="nav-item {{ request()->routeIs('gudang.pengeluaran*') ? 'active' : '' }}"><span class="nav-item-icon">📤</span><span>Pengeluaran Barang</span></a>
                    <a href="{{ route('gudang.transfer.requests') }}" class="nav-item {{ request()->routeIs('gudang.transfer*') ? 'active' : '' }}"><span class="nav-item-icon">🔁</span><span>Transfer Gudang</span></a>
                    @endcan
                    @can('view_opname_stok')
                    <a href="{{ route('gudang.opname_sessions.index') }}" class="nav-item {{ request()->routeIs('gudang.opname_sessions.*') || request()->routeIs('gudang.opname_approval.*') ? 'active' : '' }}"><span class="nav-item-icon">🔍</span><span>Opname Stok</span></a>
                    @endcan
                    @can('view_permintaan_barang')
                    <a href="{{ route('gudang.request.index') }}" class="nav-item {{ request()->routeIs('gudang.request.*') ? 'active' : '' }}"><span class="nav-item-icon">🛒</span><span>Permintaan Barang</span></a>
                    @endcan
                </div>
            </div>
            @endif
            @endcanany

            {{-- Pembelian (Retur tersedia untuk admin4, PO & Hutang hanya supervisor) --}}
            @canany(['view_purchase_order', 'view_retur_pembelian', 'view_hutang_supplier'])
            @php $pembelianActive = request()->routeIs('pembelian.*'); @endphp
            @if($role === 'admin4')
            {{-- FLAT for admin4: hanya retur pembelian --}}
            @can('view_retur_pembelian')
            <div class="sidebar-divider"></div>
            <a href="{{ route('pembelian.retur.index') }}" class="nav-item {{ request()->routeIs('pembelian.retur*') ? 'active' : '' }}"><span class="nav-item-icon">🔄</span><span>Retur Pembelian</span></a>
            @endcan
            @else
            <div class="nav-group {{ $pembelianActive ? 'open' : '' }}" id="grp-pembelian">
                <button class="nav-group-header" onclick="toggleGroup('grp-pembelian')" type="button">
                    <span class="nav-group-label">🛒 PEMBELIAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_purchase_order')
                    <a href="{{ route('pembelian.order') }}" class="nav-item {{ request()->routeIs('pembelian.order*') ? 'active' : '' }}"><span class="nav-item-icon">🛒</span><span>Purchase Order</span></a>
                    @endcan
                    @can('view_retur_pembelian')
                    <a href="{{ route('pembelian.retur.index') }}" class="nav-item {{ request()->routeIs('pembelian.retur*') ? 'active' : '' }}"><span class="nav-item-icon">🔄</span><span>Retur Pembelian</span></a>
                    @endcan
                    @can('view_hutang_supplier')
                    <a href="{{ route('pembelian.hutang.index') }}" class="nav-item {{ request()->routeIs('pembelian.hutang*') ? 'active' : '' }}"><span class="nav-item-icon">💳</span><span>Hutang Supplier</span></a>
                    @endcan
                    @if($role === 'supervisor')
                    <a href="{{ route('pembelian.receipts_followup.index') }}" class="nav-item {{ request()->routeIs('pembelian.receipts_followup*') ? 'active' : '' }}"><span class="nav-item-icon">⚠️</span><span>QC Follow-up</span></a>
                    @endif
                </div>
            </div>
            @endif
            @endcanany

            {{-- Master Data --}}
            @canany(['view_master_produk', 'view_master_kategori', 'view_master_satuan', 'view_master_supplier', 'view_master_gudang'])
            @php $masterActive = request()->routeIs('products.*','master.*'); @endphp
            <div class="nav-group {{ $masterActive ? 'open' : '' }}" id="grp-master">
                <button class="nav-group-header" onclick="toggleGroup('grp-master')" type="button">
                    <span class="nav-group-label">📦 MASTER DATA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_master_produk')
                    <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}"><span class="nav-item-icon">🏷️</span><span>Data Produk</span></a>
                    @endcan
                    @can('view_master_kategori')
                    <a href="{{ route('master.kategori') }}" class="nav-item {{ request()->routeIs('master.kategori*') ? 'active' : '' }}"><span class="nav-item-icon">🗂️</span><span>Kategori</span></a>
                    @endcan
                    @can('view_master_satuan')
                    <a href="{{ route('master.satuan') }}" class="nav-item {{ request()->routeIs('master.satuan*') ? 'active' : '' }}"><span class="nav-item-icon">⚖️</span><span>Satuan Barang</span></a>
                    @endcan
                    @can('view_master_supplier')
                    <a href="{{ route('master.supplier') }}" class="nav-item {{ request()->routeIs('master.supplier*') ? 'active' : '' }}"><span class="nav-item-icon">🏭</span><span>Supplier</span></a>
                    @endcan
                    @can('view_master_gudang')
                    <a href="{{ route('master.gudang') }}" class="nav-item {{ request()->routeIs('master.gudang*') ? 'active' : '' }}"><span class="nav-item-icon">🏢</span><span>Data Gudang</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            {{-- Laporan --}}
            @canany(['view_laporan_penjualan', 'view_laporan_pembelian', 'view_laporan_stok', 'view_laporan_keuangan', 'view_laporan_pelanggan', 'view_laporan_supplier'])
            @php $laporanActive = request()->routeIs('laporan.*'); @endphp
            @if(in_array($role, ['admin1', 'admin2', 'admin3', 'admin4']))
            {{-- FLAT for admin1/admin2/admin3/admin4 --}}
            <div class="sidebar-divider"></div>
            @can('view_laporan_penjualan')
            <a href="{{ route('laporan.penjualan') }}" class="nav-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}"><span class="nav-item-icon">📈</span><span>Lap. Penjualan</span></a>
            @endcan
            @can('view_laporan_pelanggan')
            <a href="{{ route('laporan.pelanggan') }}" class="nav-item {{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Lap. Pelanggan</span></a>
            @endcan
            @can('view_laporan_stok')
            <a href="{{ route('laporan.stok') }}" class="nav-item {{ request()->routeIs('laporan.stok') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Lap. Stok</span></a>
            @endcan
            @else
            <div class="nav-group {{ $laporanActive ? 'open' : '' }}" id="grp-laporan">
                <button class="nav-group-header" onclick="toggleGroup('grp-laporan')" type="button">
                    <span class="nav-group-label">📈 LAPORAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_laporan_penjualan')
                    <a href="{{ route('laporan.penjualan') }}" class="nav-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}"><span class="nav-item-icon">📈</span><span>Lap. Penjualan</span></a>
                    @endcan
                    @can('view_laporan_pembelian')
                    <a href="{{ route('laporan.pembelian') }}" class="nav-item {{ request()->routeIs('laporan.pembelian') ? 'active' : '' }}"><span class="nav-item-icon">📉</span><span>Lap. Pembelian</span></a>
                    @endcan
                    @can('view_laporan_stok')
                    <a href="{{ route('laporan.stok') }}" class="nav-item {{ request()->routeIs('laporan.stok') ? 'active' : '' }}"><span class="nav-item-icon">📦</span><span>Lap. Stok</span></a>
                    @endcan
                    @can('view_laporan_keuangan')
                    <a href="{{ route('laporan.keuangan') }}" class="nav-item {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}"><span class="nav-item-icon">💵</span><span>Lap. Keuangan</span></a>
                    @endcan
                    @can('view_laporan_pelanggan')
                    <a href="{{ route('laporan.pelanggan') }}" class="nav-item {{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}"><span class="nav-item-icon">👥</span><span>Lap. Pelanggan</span></a>
                    @endcan
                    @can('view_laporan_supplier')
                    <a href="{{ route('laporan.supplier') }}" class="nav-item {{ request()->routeIs('laporan.supplier') ? 'active' : '' }}"><span class="nav-item-icon">🏭</span><span>Lap. Supplier</span></a>
                    @endcan
                </div>
            </div>
            @endif
            @endcanany

            {{-- Pengaturan --}}
            @canany(['view_pengguna', 'view_hak_akses', 'view_pengaturan_toko', 'view_backup_restore', 'view_log_aktivitas'])
            @php $settingActive = request()->routeIs('pengguna.*','pengaturan.*','profile.*','activity-log.*'); @endphp
            <div class="nav-group {{ $settingActive ? 'open' : '' }}" id="grp-setting">
                <button class="nav-group-header" onclick="toggleGroup('grp-setting')" type="button">
                    <span class="nav-group-label">🛠️ PENGATURAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @can('view_pengguna')
                    <a href="{{ route('pengguna.index') }}" class="nav-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"><span class="nav-item-icon">👤</span><span>Pengguna</span></a>
                    @endcan
                    @can('view_hak_akses')
                    <a href="{{ route('pengaturan.roles.index') }}" class="nav-item {{ request()->routeIs('pengaturan.roles.*') ? 'active' : '' }}"><span class="nav-item-icon">🔐</span><span>Master Roles</span></a>
                    @endcan
                    @can('view_pengaturan_toko')
                    <a href="{{ route('pengaturan.toko') }}" class="nav-item {{ request()->routeIs('pengaturan.toko') ? 'active' : '' }}"><span class="nav-item-icon">🏪</span><span>Pengaturan Toko</span></a>
                    @endcan
                    @can('view_backup_restore')
                    <a href="{{ route('pengaturan.backup') }}" class="nav-item {{ request()->routeIs('pengaturan.backup') ? 'active' : '' }}"><span class="nav-item-icon">🛡️</span><span>Backup &amp; Restore</span></a>
                    @endcan
                    @can('view_log_aktivitas')
                    <a href="{{ route('activity-log.index') }}" class="nav-item {{ request()->routeIs('activity-log.index') ? 'active' : '' }}"><span class="nav-item-icon">🕵️‍♂️</span><span>Log Aktivitas</span></a>
                    @endcan
                </div>
            </div>
            @endcanany

            <div class="sidebar-divider"></div>

            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"><span class="nav-item-icon">⚙️</span><span>Profil Saya</span></a>
            <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="color:#f87171;"><span class="nav-item-icon">🚪</span><span>Keluar</span></a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">@csrf</form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
                <div class="online-dot" title="Online"></div>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper" id="main-wrapper">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn" onclick="toggleSidebar()" type="button" aria-label="Toggle sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
                <div class="topbar-title">@yield('header', config('app.name', 'DODPOS'))</div>
            </div>
            <div class="topbar-right">
                @if(auth()->check() && strtolower((string) auth()->user()->role) !== 'supervisor')
                    <a href="{{ route('sdm.absensi.self_panel') }}" class="btn-secondary" title="Absen Saya" style="margin-right:0.5rem;">📍 Absen</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="topbar-user" title="Profil saya">
                    <div class="topbar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <span class="topbar-username">{{ Auth::user()->name }}</span>
                </a>
            </div>
        </header>

        <main class="page-content">
            <x-toast />

            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    <script>
        const SIDEBAR_STATE_KEY = 'dodpos_sidebar_state';
        let sidebarState = {};

        try {
            sidebarState = JSON.parse(localStorage.getItem(SIDEBAR_STATE_KEY)) || {};
        } catch (e) {}

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }

        function toggleGroup(id) {
            var g = document.getElementById(id);
            if (g) {
                const isOpen = g.classList.toggle('open');
                sidebarState[id] = isOpen;
                localStorage.setItem(SIDEBAR_STATE_KEY, JSON.stringify(sidebarState));
            }
        }
        var ss = document.getElementById('sidebar-search');
        if (ss) {
            ss.addEventListener('input', function() {
                var q = this.value.toLowerCase().trim();
                document.querySelectorAll('.nav-item').forEach(function(item) {
                    var text = item.textContent.toLowerCase();
                    item.style.display = (!q || text.includes(q)) ? '' : 'none';
                });
                if (q) {
                    document.querySelectorAll('.nav-group').forEach(function(g) { g.classList.add('open'); });
                }
            });
        }
        window.addEventListener('DOMContentLoaded', function(){
            // Restore Sidebar State
            document.querySelectorAll('.nav-group').forEach(function(g) {
                const id = g.id;
                const isBackendOpen = g.classList.contains('open');

                if (isBackendOpen) {
                    sidebarState[id] = true;
                } else if (sidebarState[id] === true) {
                    g.classList.add('open');
                } else if (sidebarState[id] === false) {
                    g.classList.remove('open');
                }
            });
            localStorage.setItem(SIDEBAR_STATE_KEY, JSON.stringify(sidebarState));

            // Auto-scroll to active nav item if needed (deep link enhancement)
            const activeNav = document.querySelector('.nav-item.active');
            if (activeNav) {
                activeNav.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            // Alerts dismissal
            var alerts = document.querySelectorAll('.alert');
            if (alerts.length) {
                setTimeout(function(){
                    alerts.forEach(function(el){
                        el.style.transition = 'opacity .4s ease';
                        el.style.opacity = '0';
                        setTimeout(function(){ el.remove(); }, 400);
                    });
                }, 3500);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
