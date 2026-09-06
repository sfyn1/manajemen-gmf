<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Owner Portal') — Gintung Master Fitness</title>

    <!-- Google Fonts & Material Symbols (Stitch Kinetic Performance Core) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased bg-[#f8f9fa] font-sans text-[#191c1d]" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-screen overflow-hidden bg-[#f3f4f5]">

    {{-- SIDEBAR OWNER --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out bg-[#0f1418] border-r border-[#262c33]"
        :class="sidebarOpen ? 'w-64' : 'w-20'">

        {{-- Brand Logo --}}
        <div class="flex items-center gap-3 px-4 h-20 border-b border-[#262c33]/70 shrink-0">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-10 h-10 object-contain shrink-0">
            @else
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#ff5722] to-amber-500 flex items-center justify-center text-white font-black text-sm shrink-0 shadow-lg shadow-[#ff5722]/30">
                    GMF
                </div>
            @endif
            <div x-show="sidebarOpen" x-transition.opacity class="overflow-hidden whitespace-nowrap">
                <p class="text-white font-extrabold font-display text-sm tracking-tight leading-none uppercase">Gintung Master</p>
                <p class="text-amber-400 text-[10px] font-bold tracking-widest uppercase mt-1">Executive Portal</p>
            </div>
        </div>

        {{-- Navigation Menu --}}
        <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 space-y-1.5 px-3">
            @php
            $navItems = [
                ['route' => 'owner.dashboard',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Executive Dashboard'],
                ['route' => 'owner.reports.index','icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan Keuangan'],
                ['route' => 'owner.staff.index',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Manajemen Staf'],
            ];
            @endphp
            @foreach($navItems as $item)
            @php $isActive = request()->routeIs(rtrim($item['route'], '.index') . '*'); @endphp
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition-all duration-200 group
                {{ $isActive ? 'bg-[#ff5722] text-white shadow-lg shadow-[#ff5722]/30 font-semibold' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}"
                title="{{ $item['label'] }}">
                <svg class="w-5 h-5 shrink-0 transition-colors {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                </svg>
                <span x-show="sidebarOpen" x-transition.opacity class="text-sm whitespace-nowrap">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        {{-- User Card --}}
        <div class="border-t border-[#262c33]/70 p-3 shrink-0 bg-[#0a0d10]">
            <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition.opacity>
                <div class="w-9 h-9 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-400 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-white text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-amber-400 text-[10px] uppercase font-bold tracking-wider truncate">Owner / Pemilik</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-400 hover:bg-white/5 rounded-lg transition-colors" title="Keluar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
            <form method="POST" action="{{ route('logout') }}" x-show="!sidebarOpen" class="flex justify-center">@csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-white/5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>

        {{-- Toggle Sidebar --}}
        <button @click="sidebarOpen = !sidebarOpen"
            class="absolute -right-3 top-20 w-6 h-6 bg-[#0f1418] border border-[#262c33] rounded-full flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#ff5722] hover:border-[#ff5722] transition-all shadow-md z-10">
            <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
    </aside>

    {{-- Main Container --}}
    <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
        
        {{-- Topbar --}}
        <header class="h-20 bg-white/90 backdrop-blur-md border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-8 shrink-0 shadow-sm z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base sm:text-lg font-extrabold text-slate-900 font-display">@yield('page-title', 'Executive Console')</h1>
                    <p class="text-xs text-slate-500 font-medium">@yield('page-subtitle', 'Executive Overview Gintung Master Fitness')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-[#0f1418] text-amber-400 flex items-center justify-center font-bold text-xs shadow-sm border border-[#262c33]">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin">
            @if(session('success'))
            <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
                class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
