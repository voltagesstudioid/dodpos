<x-app-layout>
    <x-slot name="header">HR & Payroll</x-slot>

    <div class="hr-wrapper">
        <div class="hr-container">
            {{-- Header Section --}}
            <div class="hr-header">
                <div class="hr-header-content">
                    <span class="hr-eyebrow">{{ $eyebrow ?? '' }}</span>
                    <h1 class="hr-title">
                        @if(isset($icon))
                            <span class="hr-icon {{ $iconBg ?? 'bg-primary' }}">
                                {!! $icon !!}
                            </span>
                        @endif
                        {{ $title ?? '' }}
                    </h1>
                    <p class="hr-description">{{ $description ?? '' }}</p>
                </div>
                @if(isset($actions))
                    <div class="hr-header-actions">
                        {{ $actions }}
                    </div>
                @endif
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="hr-alert hr-alert-success">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="hr-alert hr-alert-error">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Main Content --}}
            {{ $slot }}
        </div>
    </div>

    @push('styles')
    <style>
        /* ===== HR MODERN UI SYSTEM ===== */
        .hr-wrapper {
            background: #f8fafc;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .hr-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .hr-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .hr-header-content { flex: 1; min-width: 300px; }
        .hr-eyebrow {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        .hr-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.025em;
        }
        .hr-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .hr-icon.bg-primary { background: #e0e7ff; color: #4f46e5; }
        .hr-icon.bg-teal { background: #ccfbf1; color: #0d9488; }
        .hr-icon.bg-indigo { background: #e0e7ff; color: #4f46e5; }
        .hr-icon.bg-green { background: #dcfce7; color: #16a34a; }
        .hr-icon.bg-orange { background: #ffedd5; color: #ea580c; }
        .hr-description {
            font-size: 0.9375rem;
            color: #64748b;
            margin: 0;
            line-height: 1.5;
        }
        .hr-header-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        /* Alerts */
        .hr-alert {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .hr-alert-success {
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .hr-alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Cards */
        .hr-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .hr-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .hr-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
        }
        .hr-card-body { padding: 1.5rem; }

        /* Stats Grid */
        .hr-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .hr-stat {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .hr-stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.5rem;
        }
        .hr-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }
        .hr-stat-change {
            font-size: 0.75rem;
            color: #64748b;
        }
        .hr-stat-change.positive { color: #16a34a; }
        .hr-stat-change.negative { color: #dc2626; }

        /* Filter Bar */
        .hr-filter {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
            flex-wrap: wrap;
            padding: 1.25rem 1.5rem;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }
        .hr-filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
            min-width: 200px;
            flex: 1;
        }
        .hr-filter-group.sm { min-width: 120px; flex: 0; }
        .hr-filter-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .hr-input,
        .hr-select {
            height: 40px;
            padding: 0 0.875rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            background: #ffffff;
            color: #111827;
            transition: all 0.15s;
            width: 100%;
        }
        .hr-input:focus,
        .hr-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .hr-search-wrapper {
            position: relative;
        }
        .hr-search-wrapper svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
        .hr-search-wrapper .hr-input { padding-left: 2.25rem; }

        /* Buttons */
        .hr-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            height: 40px;
            padding: 0 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            border: 1px solid transparent;
            text-decoration: none;
            white-space: nowrap;
        }
        .hr-btn-primary {
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
        }
        .hr-btn-primary:hover {
            background: #4338ca;
            border-color: #4338ca;
        }
        .hr-btn-secondary {
            background: #ffffff;
            color: #374151;
            border-color: #d1d5db;
        }
        .hr-btn-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
        .hr-btn-ghost {
            background: transparent;
            color: #6b7280;
            border-color: transparent;
        }
        .hr-btn-ghost:hover {
            background: #f3f4f6;
            color: #374151;
        }
        .hr-btn-success {
            background: #10b981;
            color: #ffffff;
            border-color: #10b981;
        }
        .hr-btn-success:hover {
            background: #059669;
            border-color: #059669;
        }

        /* Table */
        .hr-table-wrapper { overflow-x: auto; }
        .hr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }
        .hr-table thead th {
            padding: 0.875rem 1rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        .hr-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
            vertical-align: middle;
        }
        .hr-table tbody tr:hover { background: #f9fafb; }
        .hr-table tbody tr:last-child td { border-bottom: none; }

        /* User Cell */
        .hr-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .hr-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
            flex-shrink: 0;
        }
        .hr-avatar.sm { width: 28px; height: 28px; font-size: 0.75rem; }
        .hr-user-info { min-width: 0; }
        .hr-user-name {
            font-weight: 600;
            color: #111827;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .hr-user-meta {
            font-size: 0.75rem;
            color: #6b7280;
        }

        /* Badges */
        .hr-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .hr-badge-green {
            background: #dcfce7;
            color: #15803d;
        }
        .hr-badge-yellow {
            background: #fef9c3;
            color: #a16207;
        }
        .hr-badge-red {
            background: #fee2e2;
            color: #b91c1c;
        }
        .hr-badge-gray {
            background: #f3f4f6;
            color: #4b5563;
        }
        .hr-badge-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        /* Status Dot */
        .hr-status {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }
        .hr-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .hr-status-dot.active { background: #10b981; }
        .hr-status-dot.inactive { background: #9ca3af; }

        /* Actions */
        .hr-actions {
            display: flex;
            gap: 0.375rem;
            justify-content: flex-end;
        }
        .hr-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
        }
        .hr-action:hover {
            background: #f3f4f6;
            color: #374151;
        }

        /* Empty State */
        .hr-empty {
            text-align: center;
            padding: 3rem 1.5rem;
        }
        .hr-empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
        }
        .hr-empty-title {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }
        .hr-empty-text {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hr-container { padding: 1rem; }
            .hr-header { flex-direction: column; }
            .hr-title { font-size: 1.5rem; }
            .hr-filter { flex-direction: column; }
            .hr-filter-group { width: 100%; }
            .hr-stats { grid-template-columns: 1fr; }
        }
    </style>
    @endpush
</x-app-layout>