@props(['header' => ''])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DODPOS') }}{{ $header ? ' — '.$header : '' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ===== RESET ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 270px; min-width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex; flex-direction: column;
            min-height: 100vh;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 200;
            transition: width 0.3s ease, transform 0.3s ease;
            box-shadow: 4px 0 24px rgba(0,0,0,0.2);
        }

        .sidebar-logo {
            height: 72px; display: flex; align-items: center;
            padding: 0 1.5rem; gap: 0.85rem;
            background: rgba(255,255,255,0.02);
            text-decoration: none; flex-shrink: 0;
            position: relative;
        }
        .sidebar-logo::after {
            content: ''; position: absolute; bottom: 0; left: 1.5rem; right: 1.5rem;
            height: 1px; background: linear-gradient(90deg, rgba(255,255,255,0.05), rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 8px 16px -4px rgba(99,102,241,0.4);
        }
        .sidebar-logo-text { font-weight: 800; font-size: 1.15rem; color: #fff; letter-spacing: -0.02em; }
        .sidebar-logo-text span { color: #818cf8; }
        .sidebar-logo-badge {
            margin-left: auto; font-size: 0.65rem;
            background: rgba(99,102,241,0.15); color: #c7d2fe;
            padding: 3px 8px; border-radius: 20px; border: 1px solid rgba(99,102,241,0.25);
            white-space: nowrap; font-weight: 700; letter-spacing: 0.05em;
        }

        .sidebar-nav {
            flex: 1; padding: 0.875rem 0;
            overflow-y: auto; scrollbar-width: thin; scrollbar-color: #1f2937 transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 3px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }

        .sidebar-search { padding: 1rem 1.25rem 0.5rem; }
        .sidebar-search input {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1px solid transparent; color: #f8fafc;
            padding: 0.65rem 1rem; border-radius: 99px;
            font-size: 0.85rem; outline: none; transition: all 0.3s; font-family: inherit;
        }
        .sidebar-search input::placeholder { color: #64748b; font-weight: 500; }
        .sidebar-search input:focus { border-color: rgba(99,102,241,0.5); background: rgba(255,255,255,0.08); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

        /* Nav Groups */
        .nav-group { margin-bottom: 0.5rem; }
        .nav-group-header {
            width: 100%; display: flex; align-items: center;
            justify-content: space-between; padding: 0.75rem 1.5rem;
            background: transparent; border: none;
            color: #64748b; font-size: 0.7rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.08em; cursor: pointer;
            transition: color 0.2s;
        }
        .nav-group-header:hover { color: #94a3b8; }
        .nav-group-label { display: flex; align-items: center; gap: 0.5rem; }
        .nav-group-arrow { width: 14px; height: 14px; transition: transform 0.25s; flex-shrink: 0; opacity: 0.7; }
        .nav-group.open .nav-group-arrow { transform: rotate(180deg); opacity: 1; }
        .nav-group-items { max-height: 0; overflow: hidden; transition: max-height 0.35s ease; }
        .nav-group.open .nav-group-items { max-height: 1500px; padding-bottom: 0.5rem; }

        /* Nav Items */
        .nav-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.6rem 1.25rem 0.6rem 2.8rem;
            color: #94a3b8; text-decoration: none;
            font-size: 0.9rem; font-weight: 600;
            transition: all 0.2s; border-left: 3px solid transparent; position: relative;
        }
        .nav-item::before {
            content: ''; position: absolute; left: 1.5rem; top: 50%; transform: translateY(-50%);
            width: 4px; height: 4px; border-radius: 50%; background: #475569; transition: all 0.2s;
        }
        .nav-item:hover { color: #f1f5f9; background: rgba(255,255,255,0.03); }
        .nav-item:hover::before { background: #818cf8; height: 8px; border-radius: 4px; }
        .nav-item.active {
            color: #fff; background: linear-gradient(90deg, rgba(99,102,241,0.15) 0%, transparent 100%); font-weight: 700;
        }
        .nav-item.active::before { background: #818cf8; height: 16px; border-radius: 4px; }
        
        .sidebar-nav > .nav-item {
            padding-left: 1.25rem; margin: 0 1rem 0.35rem;
            border-radius: 12px; border-left: none;
        }
        .sidebar-nav > .nav-item::before { display: none; }
        .sidebar-nav > .nav-item:hover { background: rgba(255,255,255,0.05); color: #fff; transform: translateX(2px); }
        .sidebar-nav > .nav-item.active { background: rgba(99,102,241,0.2); color: #e0e7ff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .nav-item-icon { width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; flex-shrink: 0; opacity: 0.7; transition: all 0.2s; }
        .nav-item:hover .nav-item-icon { opacity: 1; transform: scale(1.1) rotate(-5deg); color: #818cf8; }
        .nav-item.active .nav-item-icon { opacity: 1; color: #818cf8; }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 1rem 1.5rem; border-top: 1px dashed rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.15); flex-shrink: 0;
        }
        .user-card { display: flex; align-items: center; gap: 0.85rem; padding: 0.6rem; border-radius: 12px; transition: all 0.2s; cursor: pointer; border: 1px solid transparent; }
        .user-card:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.05); }
        .user-avatar {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #3b82f6);
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 800; font-size: 1rem; flex-shrink: 0; box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .user-info { flex: 1; min-width: 0; }
        .user-name { color: #f8fafc; font-size: 0.85rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.3; }
        .user-role { color: #94a3b8; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500; margin-top: 2px; }
        .online-dot { width: 8px; height: 8px; background: #10b981; border-radius: 50%; flex-shrink: 0; box-shadow: 0 0 0 2px rgba(16,185,129,0.2); }

        /* Sidebar Overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 199; backdrop-filter: blur(2px); }
        .sidebar-overlay.open { display: block; }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper { flex: 1; margin-left: 270px; min-height: 100vh; display: flex; flex-direction: column; transition: margin-left 0.3s ease; min-width: 0; }

        /* ===== TOPBAR ===== */
        .topbar {
            height: 64px; background: #fff; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04); flex-shrink: 0; gap: 1rem;
        }
        .topbar-left { display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0; }
        .topbar-right { display: flex; align-items: center; gap: 0.5rem; flex-shrink: 0; }
        .topbar-title { font-size: 1rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .topbar-btn {
            width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
            background: transparent; border: 1px solid #e2e8f0; border-radius: 8px;
            cursor: pointer; color: #64748b; transition: all 0.2s; text-decoration: none;
        }
        .topbar-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #1e293b; }
        .topbar-user {
            display: flex; align-items: center; gap: 0.625rem; text-decoration: none;
            padding: 0.375rem 0.875rem; border-radius: 10px; border: 1px solid #e2e8f0;
            transition: all 0.2s; background: #f8fafc;
        }
        .topbar-user:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .topbar-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;
        }
        .topbar-username { font-size: 0.8125rem; font-weight: 600; color: #1e293b; white-space: nowrap; }
        .mobile-menu-btn {
            display: none; width: 36px; height: 36px; align-items: center; justify-content: center;
            background: transparent; border: 1px solid #e2e8f0; border-radius: 8px;
            cursor: pointer; color: #64748b; flex-shrink: 0;
        }

        /* ===== PAGE CONTENT ===== */
        .page-content { flex: 1; padding: 1.5rem; min-width: 0; }

        /* ===== COMMON COMPONENTS ===== */
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
        .badge-indigo  { background: #eef2ff; color: #4338ca; }
        .badge-blue    { background: #dbeafe; color: #1d4ed8; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger  { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-gray    { background: #f1f5f9; color: #475569; }
        .badge-purple  { background: #f3e8ff; color: #7e22ce; }
        .badge-teal    { background: #ccfbf1; color: #0f766e; }

        /* Alerts */
        .alert { padding: 0.875rem 1.125rem; border-radius: 10px; margin-bottom: 1.25rem; display: flex; align-items: flex-start; gap: 0.625rem; font-size: 0.875rem; font-weight: 500; line-height: 1.5; }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-danger  { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .alert-warning { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .alert-info    { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }

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
        .stat-icon.indigo  { background: #eef2ff; }
        .stat-icon.emerald { background: #ecfdf5; }
        .stat-icon.amber   { background: #fffbeb; }
        .stat-icon.rose    { background: #fff1f2; }
        .stat-icon.blue    { background: #eff6ff; }
        .stat-icon.purple  { background: #faf5ff; }
        .stat-label { font-size: 0.6875rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em; margin-bottom: 0.25rem; }
        .stat-value { font-size: 1.75rem; font-weight: 800; letter-spacing: -0.03em; line-height: 1; margin-bottom: 0.375rem; }
        .stat-value.indigo  { color: #4f46e5; }
        .stat-value.emerald { color: #059669; }
        .stat-value.amber   { color: #d97706; }
        .stat-value.rose    { color: #e11d48; }
        .stat-value.blue    { color: #2563eb; }
        .stat-value.purple  { color: #7c3aed; }

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
        .text-emerald { color: #10b981; }
        .text-indigo { color: #4f46e5; }
        .text-danger { color: #ef4444; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        nav[aria-label="pagination"] { padding: 1rem 1.25rem; border-top: 1px solid #f1f5f9; }
        .pagination { display: flex; align-items: center; gap: 0.25rem; flex-wrap: wrap; }
        .pagination .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 0.5rem; border-radius: 6px; font-size: 0.8125rem; font-weight: 500; color: #475569; text-decoration: none; border: 1px solid #e2e8f0; background: #fff; transition: all 0.15s; }
        .pagination .page-item .page-link:hover { background: #f1f5f9; border-color: #cbd5e1; }
        .pagination .page-item.active .page-link { background: #4f46e5; border-color: #4f46e5; color: white; }
        .pagination .page-item.disabled .page-link { opacity: 0.4; cursor: not-allowed; }

        /* Badge extras */
        .badge-green  { background: #ecfdf5; color: #059669; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }
        .badge-yellow { background: #fffbeb; color: #d97706; padding: 0.2rem 0.625rem; border-radius: 999px; font-size: 0.72rem; font-weight: 600; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-wrapper { margin-left: 0 !important; }
            .mobile-menu-btn { display: flex !important; }
            .topbar-username { display: none; }
            .form-row { grid-template-columns: 1fr; }
            .form-row-3 { grid-template-columns: 1fr; }
            .detail-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) { .page-content { padding: 1rem; } .topbar { padding: 0 1rem; } .stat-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .stat-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
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

            @php
                $role = auth()->user()?->role ?? '';
                $isSupervisor = $role === 'supervisor';
                $hasAnyRole = function (array $roles) use ($role, $isSupervisor) {
                    return $isSupervisor || in_array($role, $roles, true);
                };
            @endphp

            @if($hasAnyRole(['admin_sales', 'admin1']))
            <a href="{{ route('sales-order.index') }}" class="nav-item {{ request()->routeIs('sales-order.*') ? 'active' : '' }}">
                <span class="nav-item-icon">📄</span><span>Sales Order</span>
            </a>
            @endif

            <div class="sidebar-divider"></div>

            @if($hasAnyRole(['admin2']))
            <div class="nav-group {{ request()->routeIs('operasional.*') ? 'open' : '' }}" id="grp-operasional">
                <button class="nav-group-header" onclick="toggleGroup('grp-operasional')">
                    <span class="nav-group-label">⚙️ OPERASIONAL</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('operasional.kategori.index') }}" class="nav-item {{ request()->routeIs('operasional.kategori.*') ? 'active' : '' }}"><div class="nav-item-icon">🗂️</div><span>Kategori</span></a>
                    <a href="{{ route('operasional.kendaraan.index') }}" class="nav-item {{ request()->routeIs('operasional.kendaraan.*') ? 'active' : '' }}"><div class="nav-item-icon">🚚</div><span>Data Kendaraan</span></a>
                    @php $activeOpSession = \App\Models\OperationalSession::where('status','open')->latest()->first(); @endphp
                    @if($activeOpSession)
                    <a href="#" onclick="event.preventDefault();if(confirm('Yakin tutup sesi operasional?'))document.getElementById('close-ops-session-form').submit();" class="nav-item" style="color:#f87171;"><div class="nav-item-icon">🔒</div><span>Tutup Modal Opr.</span></a>
                    <form id="close-ops-session-form" method="POST" action="{{ route('operasional.close_session') }}" style="display:none;">@csrf</form>
                    @endif
                    <a href="{{ route('operasional.pengeluaran.create') }}" class="nav-item {{ request()->routeIs('operasional.pengeluaran.*') ? 'active' : '' }}"><div class="nav-item-icon">💸</div><span>Pengeluaran</span></a>
                    <a href="{{ route('operasional.riwayat.index') }}" class="nav-item {{ request()->routeIs('operasional.riwayat.*') ? 'active' : '' }}"><div class="nav-item-icon">📜</div><span>Riwayat Operasional</span></a>
                    <a href="{{ route('operasional.sesi.index') }}" class="nav-item {{ request()->routeIs('operasional.sesi.*') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Laporan Modal Opr.</span></a>
                </div>
            </div>
            @endif

            @if(auth()->check() && strtolower((string) auth()->user()->role) !== 'supervisor')
            @if($role === 'admin1')
            {{-- FLAT (no group header) for admin1 --}}
            <a href="{{ route('sdm.absensi.self_panel') }}" class="nav-item {{ request()->routeIs('sdm.absensi.self_*') ? 'active' : '' }}"><div class="nav-item-icon">📍</div><span>Absen Saya</span></a>
            <a href="{{ route('sdm.cuti.self_index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.self_*') ? 'active' : '' }}"><div class="nav-item-icon">📝</div><span>Cuti Saya</span></a>
            <a href="{{ route('sdm.penggajian.self_index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.self_*') ? 'active' : '' }}"><div class="nav-item-icon">💸</div><span>Gaji Saya</span></a>
            <a href="{{ route('sdm.potongan.self_index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.self_*') ? 'active' : '' }}"><div class="nav-item-icon">➖</div><span>Potongan Saya</span></a>
            @else
            <div class="nav-group {{ request()->routeIs('sdm.absensi.self_*','sdm.cuti.self_*','sdm.penggajian.self_*','sdm.potongan.self_*') ? 'open' : '' }}" id="grp-sdm-self">
                <button class="nav-group-header" onclick="toggleGroup('grp-sdm-self')">
                    <span class="nav-group-label">🧑‍🤝‍🧑 SDM Saya</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('sdm.absensi.self_panel') }}" class="nav-item {{ request()->routeIs('sdm.absensi.self_*') ? 'active' : '' }}"><div class="nav-item-icon">📍</div><span>Absen Saya</span></a>
                    <a href="{{ route('sdm.cuti.self_index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.self_*') ? 'active' : '' }}"><div class="nav-item-icon">📝</div><span>Cuti Saya</span></a>
                    <a href="{{ route('sdm.penggajian.self_index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.self_*') ? 'active' : '' }}"><div class="nav-item-icon">💸</div><span>Gaji Saya</span></a>
                    <a href="{{ route('sdm.potongan.self_index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.self_*') ? 'active' : '' }}"><div class="nav-item-icon">➖</div><span>Potongan Saya</span></a>
                </div>
            </div>
            @endif
            @endif

            @if(auth()->check() && strtolower((string) auth()->user()->role) === 'supervisor')
            <div class="nav-group {{ request()->routeIs('sdm.*') ? 'open' : '' }}" id="grp-sdm">
                <button class="nav-group-header" onclick="toggleGroup('grp-sdm')">
                    <span class="nav-group-label">🧑‍🤝‍🧑 HR &amp; Payroll</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('sdm.karyawan.index') }}" class="nav-item {{ request()->routeIs('sdm.karyawan.*') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Data Karyawan</span></a>
                    <a href="{{ route('sdm.absensi.index') }}" class="nav-item {{ request()->routeIs('sdm.absensi.index') ? 'active' : '' }}"><div class="nav-item-icon">🕒</div><span>Absensi</span></a>
                    <a href="{{ route('sdm.absensi.monthly') }}" class="nav-item {{ request()->routeIs('sdm.absensi.monthly*') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Rekap Absensi</span></a>
                    <a href="{{ route('sdm.cuti.index') }}" class="nav-item {{ request()->routeIs('sdm.cuti.*') ? 'active' : '' }}"><div class="nav-item-icon">📝</div><span>Cuti</span></a>
                    <a href="{{ route('sdm.libur.index') }}" class="nav-item {{ request()->routeIs('sdm.libur.*') ? 'active' : '' }}"><div class="nav-item-icon">📅</div><span>Kalender Libur</span></a>
                    <a href="{{ route('sdm.potongan.index') }}" class="nav-item {{ request()->routeIs('sdm.potongan.*') ? 'active' : '' }}"><div class="nav-item-icon">➖</div><span>Potongan & Bonus</span></a>
                    <a href="{{ route('sdm.penggajian.index') }}" class="nav-item {{ request()->routeIs('sdm.penggajian.*') ? 'active' : '' }}"><div class="nav-item-icon">💸</div><span>Penggajian</span></a>
                    <a href="{{ route('sdm.performa.index') }}" class="nav-item {{ request()->routeIs('sdm.performa.*') ? 'active' : '' }}"><div class="nav-item-icon">📈</div><span>Performa</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['kasir', 'admin1', 'admin2']))
            @if($role === 'admin1')
            {{-- FLAT for admin1: no group header --}}
            <div class="sidebar-divider"></div>
            <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"><div class="nav-item-icon">🧾</div><span>Transaksi</span></a>
            <a href="{{ route('pelanggan.index') }}" class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Pelanggan</span></a>
            <a href="{{ route('harga.index') }}" class="nav-item {{ request()->routeIs('harga.*') ? 'active' : '' }}"><div class="nav-item-icon">💲</div><span>Daftar Harga</span></a>
            @else
            <div class="nav-group {{ request()->is('kasir*') || request()->routeIs('transaksi.*','pelanggan.*','hutang.*','harga.*') ? 'open' : '' }}" id="grp-pos">
                <button class="nav-group-header" onclick="toggleGroup('grp-pos')">
                    <span class="nav-group-label">🏪 POINT OF SALE</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    @if($hasAnyRole(['kasir', 'admin2', 'supervisor']))
                    <a href="{{ route('kasir.index') }}" class="nav-item {{ request()->is('kasir*') ? 'active' : '' }}"><div class="nav-item-icon">🖥️</div><span>Kasir / POS</span></a>
                    <a href="{{ route('kasir.session') }}" class="nav-item {{ request()->routeIs('kasir.session') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Sesi Kasir</span></a>
                    @php $activeSession = \App\Models\PosSession::where('status','open')->latest()->first(); @endphp
                    @if($activeSession)
                    <a href="#" onclick="event.preventDefault();if(confirm('Yakin tutup sesi kasir?'))document.getElementById('close-session-form').submit();" class="nav-item" style="color:#f87171;"><div class="nav-item-icon">🔒</div><span>Tutup Kasir</span></a>
                    <form id="close-session-form" method="POST" action="{{ route('kasir.close_session') }}" style="display:none;">@csrf</form>
                    @endif
                    @endif
                    <a href="{{ route('transaksi.index') }}" class="nav-item {{ request()->routeIs('transaksi.*') ? 'active' : '' }}"><div class="nav-item-icon">🧾</div><span>Transaksi</span></a>
                    <a href="{{ route('pelanggan.index') }}" class="nav-item {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Pelanggan</span></a>
                    @if($hasAnyRole(['kasir', 'admin2', 'supervisor']))
                    <a href="{{ route('hutang.index') }}" class="nav-item {{ request()->routeIs('hutang.*') ? 'active' : '' }}"><div class="nav-item-icon">💳</div><span>Hutang &amp; Piutang</span></a>
                    @endif
                    <a href="{{ route('harga.index') }}" class="nav-item {{ request()->routeIs('harga.*') ? 'active' : '' }}"><div class="nav-item-icon">💲</div><span>Daftar Harga</span></a>
                </div>
            </div>
            @endif
            @endif

            @if($hasAnyRole(['pasgar']))
            <div class="nav-group {{ request()->routeIs('pasgar.*') ? 'open' : '' }}" id="grp-pasgar">
                <button class="nav-group-header" onclick="toggleGroup('grp-pasgar')">
                    <span class="nav-group-label">🦅 PASUKAN GARUDA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('pasgar.loadings.index') }}" class="nav-item {{ request()->routeIs('pasgar.loadings.*') ? 'active' : '' }}"><div class="nav-item-icon">📋</div><span>Pesanan Pasgar</span></a>
                    <a href="{{ route('pasgar.pelanggan.index') }}" class="nav-item {{ request()->routeIs('pasgar.pelanggan.*') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Data Pelanggan</span></a>
                    <a href="{{ route('pasgar.stok-onhand.index') }}" class="nav-item {{ request()->routeIs('pasgar.stok-onhand.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Stok On-Hand</span></a>
                    <a href="{{ route('pasgar.pengembalian.index') }}" class="nav-item {{ request()->routeIs('pasgar.pengembalian.*') ? 'active' : '' }}"><div class="nav-item-icon">↩️</div><span>Pengembalian Sisa</span></a>
                    <a href="{{ route('pasgar.penjualan.index') }}" class="nav-item {{ request()->routeIs('pasgar.penjualan.*') ? 'active' : '' }}"><div class="nav-item-icon">🛒</div><span>Penjualan Kanvas</span></a>
                    <a href="{{ route('pasgar.penagihan.index') }}" class="nav-item {{ request()->routeIs('pasgar.penagihan.*') ? 'active' : '' }}"><div class="nav-item-icon">💳</div><span>Penagihan Piutang</span></a>
                    <a href="{{ route('pasgar.setoran.index') }}" class="nav-item {{ request()->routeIs('pasgar.setoran.*') ? 'active' : '' }}"><div class="nav-item-icon">💰</div><span>Setoran Harian</span></a>
                    <a href="{{ route('pasgar.jadwal.index') }}" class="nav-item {{ request()->routeIs('pasgar.jadwal.*') ? 'active' : '' }}"><div class="nav-item-icon">📅</div><span>Jadwal Kunjungan</span></a>
                    <a href="{{ route('pasgar.kunjungan.index') }}" class="nav-item {{ request()->routeIs('pasgar.kunjungan.*') ? 'active' : '' }}"><div class="nav-item-icon">📋</div><span>Laporan Kunjungan</span></a>
                    <a href="{{ route('pasgar.anggota.index') }}" class="nav-item {{ request()->routeIs('pasgar.anggota.*') ? 'active' : '' }}"><div class="nav-item-icon">👤</div><span>Daftar Anggota</span></a>
                    <a href="{{ route('pasgar.vehicles.index') }}" class="nav-item {{ request()->routeIs('pasgar.vehicles.*') ? 'active' : '' }}"><div class="nav-item-icon">🚗</div><span>Kendaraan</span></a>
                </div>
            </div>
            @endif

            <div class="sidebar-divider"></div>

            @if($hasAnyRole(['sales_kanvas', 'admin3']))
            <div class="nav-group {{ request()->routeIs('kanvas.*') ? 'open' : '' }}" id="grp-kanvas">
                <button class="nav-group-header" onclick="toggleGroup('grp-kanvas')">
                    <span class="nav-group-label">🚐 MODUL KANVAS</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('kanvas.dashboard') }}" class="nav-item {{ request()->routeIs('kanvas.dashboard') ? 'active' : '' }}"><div class="nav-item-icon">📈</div><span>Dashboard Kanvas</span></a>
                    <a href="{{ route('kanvas.loading.index') }}" class="nav-item {{ request()->routeIs('kanvas.loading.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Loading Armada</span></a>
                    <a href="{{ route('kanvas.route.index') }}" class="nav-item {{ request()->routeIs('kanvas.route.*') ? 'active' : '' }}"><div class="nav-item-icon">🗺️</div><span>Journey Plan (Rute)</span></a>
                    <a href="{{ route('kanvas.setoran.index') }}" class="nav-item {{ request()->routeIs('kanvas.setoran.*') ? 'active' : '' }}"><div class="nav-item-icon">💰</div><span>Validasi Setoran</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['sales_gula', 'admin3']))
            <div class="nav-group {{ request()->routeIs('gula.*') ? 'open' : '' }}" id="grp-gula">
                <button class="nav-group-header" onclick="toggleGroup('grp-gula')">
                    <span class="nav-group-label">🍬 MODUL GULA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('gula.dashboard') }}" class="nav-item {{ request()->routeIs('gula.dashboard') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Dashboard Gula</span></a>
                    <a href="{{ route('gula.stok.index') }}" class="nav-item {{ request()->routeIs('gula.stok.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Stok Gudang Induk</span></a>
                    <a href="{{ route('gula.repacking.index') }}" class="nav-item {{ request()->routeIs('gula.repacking.*') ? 'active' : '' }}"><div class="nav-item-icon">✂️</div><span>Repacking & Susut</span></a>
                    <a href="{{ route('gula.loading.index') }}" class="nav-item {{ request()->routeIs('gula.loading.*') ? 'active' : '' }}"><div class="nav-item-icon">🚚</div><span>Loading Armada</span></a>
                    <a href="{{ route('gula.setoran.index') }}" class="nav-item {{ request()->routeIs('gula.setoran.*') ? 'active' : '' }}"><div class="nav-item-icon">💰</div><span>Validasi Setoran</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['sales_mineral', 'admin3']))
            <div class="nav-group {{ request()->routeIs('mineral.*') ? 'open' : '' }}" id="grp-mineral">
                <button class="nav-group-header" onclick="toggleGroup('grp-mineral')">
                    <span class="nav-group-label">💧 MODUL MINERAL</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('mineral.dashboard') }}" class="nav-item {{ request()->routeIs('mineral.dashboard') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Dashboard Mineral</span></a>
                    <a href="{{ route('mineral.stok.index') }}" class="nav-item {{ request()->routeIs('mineral.stok.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Stok & Mutasi Gudang</span></a>
                    <a href="{{ route('mineral.loading.index') }}" class="nav-item {{ request()->routeIs('mineral.loading.*') ? 'active' : '' }}"><div class="nav-item-icon">🚚</div><span>Loading Armada</span></a>
                    <a href="{{ route('mineral.setoran.index') }}" class="nav-item {{ request()->routeIs('mineral.setoran.*') ? 'active' : '' }}"><div class="nav-item-icon">💰</div><span>Validasi Setoran</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['sales_minyak', 'admin3']))
            <div class="nav-group {{ request()->routeIs('minyak.*') ? 'open' : '' }}" id="grp-minyak">
                <button class="nav-group-header" onclick="toggleGroup('grp-minyak')">
                    <span class="nav-group-label">🛢️ MINYAK</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('minyak.dashboard') }}" class="nav-item {{ request()->routeIs('minyak.dashboard') ? 'active' : '' }}"><div class="nav-item-icon">📊</div><span>Dashboard Tangki</span></a>
                    <a href="{{ route('minyak.pelanggan.index') }}" class="nav-item {{ request()->routeIs('minyak.pelanggan.*') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Data Pelanggan</span></a>
                    <a href="{{ route('minyak.setoran.index') }}" class="nav-item {{ request()->routeIs('minyak.setoran.*') ? 'active' : '' }}"><div class="nav-item-icon">💰</div><span>Setoran & Retur</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['gudang', 'admin3', 'admin4']))
            <div class="nav-group {{ request()->routeIs('gudang.*') || request()->routeIs('products.*') ? 'open' : '' }}" id="grp-gudang">
                <button class="nav-group-header" onclick="toggleGroup('grp-gudang')">
                    <span class="nav-group-label">🏢 MANAJEMEN GUDANG</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Stok Barang Induk</span></a>
                    <a href="{{ route('gudang.inout') }}" class="nav-item {{ request()->routeIs('gudang.inout') ? 'active' : '' }}"><div class="nav-item-icon">🔄</div><span>Masuk & Keluar</span></a>
                    <a href="{{ route('gudang.terimapo.index') }}" class="nav-item {{ request()->routeIs('gudang.terimapo.*') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Terima PO (Supplier)</span></a>
                    <a href="{{ route('gudang.penerimaan') }}" class="nav-item {{ request()->routeIs('gudang.penerimaan*') ? 'active' : '' }}"><div class="nav-item-icon">📥</div><span>Penerimaan Lainnya</span></a>
                    <a href="{{ route('gudang.pengeluaran') }}" class="nav-item {{ request()->routeIs('gudang.pengeluaran*') ? 'active' : '' }}"><div class="nav-item-icon">📤</div><span>Pengeluaran (Mutasi)</span></a>
                    <a href="{{ route('gudang.transfer') }}" class="nav-item {{ request()->routeIs('gudang.transfer*') ? 'active' : '' }}"><div class="nav-item-icon">🔄</div><span>Transfer Cabang</span></a>
                    <a href="{{ route('gudang.opname_sessions.index') }}" class="nav-item {{ request()->routeIs('gudang.opname_sessions.*') || request()->routeIs('gudang.opname_approval.*') ? 'active' : '' }}"><div class="nav-item-icon">🔍</div><span>Opname Stok</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['admin4']))
            <div class="nav-group {{ request()->routeIs('pembelian.*') ? 'open' : '' }}" id="grp-pembelian">
                <button class="nav-group-header" onclick="toggleGroup('grp-pembelian')">
                    <span class="nav-group-label">🛒 PEMBELIAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('pembelian.order') }}" class="nav-item {{ request()->routeIs('pembelian.order*') ? 'active' : '' }}"><div class="nav-item-icon">🛒</div><span>Purchase Order</span></a>
                    <a href="{{ route('pembelian.retur.index') }}" class="nav-item {{ request()->routeIs('pembelian.retur*') ? 'active' : '' }}"><div class="nav-item-icon">🔄</div><span>Retur Pembelian</span></a>
                    <a href="{{ route('pembelian.hutang.index') }}" class="nav-item {{ request()->routeIs('pembelian.hutang*') ? 'active' : '' }}"><div class="nav-item-icon">💳</div><span>Hutang Supplier</span></a>
                    @if($isSupervisor)
                    <a href="{{ route('pembelian.receipts_followup.index') }}" class="nav-item {{ request()->routeIs('pembelian.receipts_followup*') ? 'active' : '' }}"><div class="nav-item-icon">⚠️</div><span>QC Follow-up</span></a>
                    @endif
                </div>
            </div>
            @endif

            @if($hasAnyRole(['admin4', 'gudang']))
            <div class="nav-group {{ request()->routeIs('products.*','master.*') ? 'open' : '' }}" id="grp-master">
                <button class="nav-group-header" onclick="toggleGroup('grp-master')">
                    <span class="nav-group-label">📦 MASTER DATA</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}"><div class="nav-item-icon">🏷️</div><span>Data Produk</span></a>
                    <a href="{{ route('master.kategori') }}" class="nav-item {{ request()->routeIs('master.kategori*') ? 'active' : '' }}"><div class="nav-item-icon">🗂️</div><span>Kategori</span></a>
                    <a href="{{ route('master.satuan') }}" class="nav-item {{ request()->routeIs('master.satuan*') ? 'active' : '' }}"><div class="nav-item-icon">⚖️</div><span>Satuan Barang</span></a>
                    <a href="{{ route('master.supplier') }}" class="nav-item {{ request()->routeIs('master.supplier*') ? 'active' : '' }}"><div class="nav-item-icon">🏭</div><span>Supplier</span></a>
                    <a href="{{ route('master.gudang') }}" class="nav-item {{ request()->routeIs('master.gudang*') ? 'active' : '' }}"><div class="nav-item-icon">🏢</div><span>Data Gudang</span></a>
                </div>
            </div>
            @endif

            @if($hasAnyRole(['admin_sales', 'admin1', 'admin2', 'admin4']))
            @if($role === 'admin1')
            {{-- FLAT for admin1: no group header --}}
            <div class="sidebar-divider"></div>
            <a href="{{ route('laporan.penjualan') }}" class="nav-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}"><div class="nav-item-icon">📈</div><span>Lap. Penjualan</span></a>
            <a href="{{ route('laporan.pelanggan') }}" class="nav-item {{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Lap. Pelanggan</span></a>
            @else
            <div class="nav-group {{ request()->routeIs('laporan.*') ? 'open' : '' }}" id="grp-laporan">
                <button class="nav-group-header" onclick="toggleGroup('grp-laporan')">
                    <span class="nav-group-label">📈 LAPORAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('laporan.penjualan') }}" class="nav-item {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}"><div class="nav-item-icon">📈</div><span>Lap. Penjualan</span></a>
                    <a href="{{ route('laporan.pembelian') }}" class="nav-item {{ request()->routeIs('laporan.pembelian') ? 'active' : '' }}"><div class="nav-item-icon">📉</div><span>Lap. Pembelian</span></a>
                    <a href="{{ route('laporan.stok') }}" class="nav-item {{ request()->routeIs('laporan.stok') ? 'active' : '' }}"><div class="nav-item-icon">📦</div><span>Lap. Stok</span></a>
                    <a href="{{ route('laporan.keuangan') }}" class="nav-item {{ request()->routeIs('laporan.keuangan') ? 'active' : '' }}"><div class="nav-item-icon">💵</div><span>Lap. Keuangan</span></a>
                    <a href="{{ route('laporan.pelanggan') }}" class="nav-item {{ request()->routeIs('laporan.pelanggan') ? 'active' : '' }}"><div class="nav-item-icon">👥</div><span>Lap. Pelanggan</span></a>
                    <a href="{{ route('laporan.supplier') }}" class="nav-item {{ request()->routeIs('laporan.supplier') ? 'active' : '' }}"><div class="nav-item-icon">🏭</div><span>Lap. Supplier</span></a>
                </div>
            </div>
            @endif
            @endif

            <div class="sidebar-divider"></div>

            @php $settingActive = request()->routeIs('pengguna.*','pengaturan.*','profile.*','activity-log.*'); @endphp
            @if($isSupervisor)
            <div class="nav-group {{ $settingActive ? 'open' : '' }}" id="grp-setting">
                <button class="nav-group-header" onclick="toggleGroup('grp-setting')">
                    <span class="nav-group-label">🛠️ PENGATURAN</span>
                    <svg class="nav-group-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="nav-group-items">
                    <a href="{{ route('pengguna.index') }}" class="nav-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"><div class="nav-item-icon">👤</div><span>Pengguna</span></a>
                    <a href="{{ route('pengaturan.roles.index') }}" class="nav-item {{ request()->routeIs('pengaturan.roles.*') ? 'active' : '' }}"><div class="nav-item-icon">🔐</div><span>Master Roles</span></a>
                    <a href="{{ route('pengaturan.toko') }}" class="nav-item {{ request()->routeIs('pengaturan.toko') ? 'active' : '' }}"><div class="nav-item-icon">🏪</div><span>Pengaturan Toko</span></a>
                    <a href="{{ route('pengaturan.backup') }}" class="nav-item {{ request()->routeIs('pengaturan.backup') ? 'active' : '' }}"><div class="nav-item-icon">🛡️</div><span>Backup &amp; Restore</span></a>
                    <a href="{{ route('activity-log.index') }}" class="nav-item {{ request()->routeIs('activity-log.*') ? 'active' : '' }}"><div class="nav-item-icon">🧾</div><span>Log Aktivitas</span></a>
                </div>
            </div>
            @endif

            <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"><div class="nav-item-icon">⚙️</div><span>Profil Saya</span></a>
            <a href="{{ route('logout') }}" class="nav-item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" style="color:#f87171;"><div class="nav-item-icon">🚪</div><span>Keluar</span></a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">@csrf</form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-card">
                @php
                    $sidebarPhotoUrl = Auth::user()->profile_photo_path ? route('profile.photo', Auth::id()) : null;
                @endphp
                <div class="user-avatar" style="overflow:hidden;">
                    @if($sidebarPhotoUrl)
                        <img src="{{ $sidebarPhotoUrl }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->email }}</div>
                </div>
                <div class="online-dot" title="Online"></div>
            </div>
        </div>
    </aside>

    <!-- Main -->
    <div class="main-wrapper" id="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn" id="mobile-menu-btn" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div>
                    <div class="topbar-title">{{ $header ?? config('app.name') }}</div>
                </div>
            </div>
            <div class="topbar-right">
                <a href="{{ route('sdm.absensi.self_panel') }}" class="btn-secondary" title="Absen Saya" style="margin-right:0.5rem;">📍 Absen</a>
                <a href="{{ route('profile.edit') }}" class="topbar-user" title="Profil saya">
                    @php
                        $topbarPhotoUrl = Auth::user()->profile_photo_path ? route('profile.photo', Auth::id()) : null;
                    @endphp
                    <div class="topbar-avatar" style="overflow:hidden;">
                        @if($topbarPhotoUrl)
                            <img src="{{ $topbarPhotoUrl }}" alt="Foto Profil" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <span class="topbar-username">{{ Auth::user()->name }}</span>
                </a>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success" role="alert">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" role="alert">❌ {{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning" role="alert">⚠️ {{ session('warning') }}</div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <script>
        const SIDEBAR_STATE_KEY = 'dodpos_sidebar_state';
        let sidebarState = {};

        try {
            sidebarState = JSON.parse(localStorage.getItem(SIDEBAR_STATE_KEY)) || {};
        } catch (e) {}

        // Sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebar-overlay').classList.toggle('open');
        }

        // Nav group accordion
        function toggleGroup(id) {
            const grp = document.getElementById(id);
            if (!grp) return;
            const isOpen = grp.classList.toggle('open');
            sidebarState[id] = isOpen;
            localStorage.setItem(SIDEBAR_STATE_KEY, JSON.stringify(sidebarState));
        }

        // Sidebar search filter
        const searchInput = document.getElementById('sidebar-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase().trim();
                document.querySelectorAll('.nav-item span').forEach(span => {
                    const item = span.closest('.nav-item');
                    if (!item) return;
                    const match = span.textContent.toLowerCase().includes(q);
                    item.style.display = match || q === '' ? '' : 'none';
                });
                if (q) {
                    document.querySelectorAll('.nav-group').forEach(g => g.classList.add('open'));
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
            
            // Auto dismiss alerts
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
</body>
</html>
