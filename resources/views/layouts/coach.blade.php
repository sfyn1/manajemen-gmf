<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelatih Portal') — Gintung Master Fitness</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-slate-50 font-sans text-slate-800" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden bg-slate-100">

    {{-- SIDEBAR COACH --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out bg-slate-900 border-r border-slate-800"
        :class="sidebarOpen ? 'w-64' : 'w-20'">

        {{-- Brand Logo --}}
        <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-800 shrink-0">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-9 h-9 object-contain shrink-0">
            @else
                <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-black text-xs shrink-0">
                    GMF
                </div>
            @endif
            <div x-show="sidebarOpen" x-transition.opacity class="overflow-hidden whitespace-nowrap">
                <p class="text-white font-extrabold font-display text-sm tracking-tight leading-none">Gintung Master Fitness</p>
                <div class="flex items-center gap-1.5 mt-1">
                </div>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 space-y-1 px-3">
            @php
            $navItems = [
                ['route' => 'coach.dashboard',        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                ['route' => 'coach.attendance.index', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Verifikasi Kehadiran'],
                ['route' => 'coach.history.index',    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Riwayat Mengajar'],
                ['route' => 'coach.commission.index', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Komisi Mengajar'],
            ];
            @endphp
            @foreach($navItems as $item)
            @php $isActive = request()->routeIs(rtrim($item['route'], '.index') . '*'); @endphp
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group
                {{ $isActive ? 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20 font-semibold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200' }}"
                title="{{ $item['label'] }}">
                <svg class="w-5 h-5 shrink-0 transition-colors {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                </svg>
                <span x-show="sidebarOpen" x-transition.opacity class="text-sm whitespace-nowrap">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- User Card --}}
        <div class="border-t border-slate-800 p-3 shrink-0 bg-slate-950/40">
            <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition.opacity>
                <div class="w-9 h-9 rounded-xl bg-emerald-950 border border-emerald-800 text-emerald-400 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-slate-200 text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-emerald-400/80 text-[11px] truncate">Pelatih / Coach</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="!sidebarOpen" class="flex justify-center">@csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>

        {{-- Toggle Sidebar --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="absolute -right-3 top-20 w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#f05a2a] transition-all shadow-md z-10">
            <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
    </aside>

    {{-- Main Container --}}
    <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
        
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 shrink-0 shadow-sm z-30">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base font-bold text-slate-900 font-display">@yield('page-title', 'Dashboard Pelatih')</h1>
                    <p class="text-xs text-slate-500 font-medium">@yield('page-subtitle', 'Gintung Master Fitness Center')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-slate-900 text-emerald-400 flex items-center justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin">
            @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
                class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0116 0z"/></svg>
                <span class="font-medium">{{ session('success') }}</span>
                <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">✕</button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
                class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">{{ session('error') }}</span>
                <button @click="show=false" class="ml-auto text-rose-500 hover:text-rose-700">✕</button>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
