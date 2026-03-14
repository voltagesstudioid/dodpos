<x-app-layout>
    <x-slot name="header">{{ $title }}</x-slot>

    <style>
        .cs-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
        }
        .cs-icon {
            font-size: 4rem;
            margin-bottom: 1.25rem;
            line-height: 1;
        }
        .cs-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .cs-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: #eef2ff;
            color: #6366f1;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.3rem 0.875rem;
            border-radius: 999px;
            margin-bottom: 1rem;
        }
        .cs-badge-dot {
            width: 6px; height: 6px;
            background: #6366f1;
            border-radius: 50%;
            animation: bdot 1.5s ease-in-out infinite;
        }
        @keyframes bdot {
            0%,100%{opacity:1;transform:scale(1)}
            50%{opacity:0.4;transform:scale(1.4)}
        }
        .cs-desc {
            font-size: 0.875rem;
            color: #94a3b8;
            max-width: 380px;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .cs-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #6366f1;
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(99,102,241,0.3);
        }
        .cs-btn:hover { background: #4f46e5; transform: translateY(-1px); }
    </style>

    <div class="cs-wrap">
        <div class="cs-icon">{{ $icon }}</div>
        <h1 class="cs-title">{{ $title }}</h1>
        <div class="cs-badge">
            <span class="cs-badge-dot"></span>
            Segera Hadir
        </div>
        <p class="cs-desc">
            Halaman <strong>{{ $title }}</strong> sedang dalam tahap pengembangan.
            Fitur ini akan segera tersedia dalam pembaruan berikutnya.
        </p>
        <a href="{{ route('dashboard') }}" class="cs-btn">
            ← Kembali ke Dashboard
        </a>
    </div>
</x-app-layout>
