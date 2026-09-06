<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Member Portal') — Gintung Master Fitness</title>

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
<body class="antialiased bg-[#f8f9fa] font-sans text-[#191c1d] min-h-screen flex flex-col">

@php
    $currentMember = auth()->user()?->member;
    $isExpiredMember = $currentMember ? $currentMember->isExpired() : false;
@endphp

{{-- TOPBAR HEADER --}}
<header class="sticky top-0 z-40 bg-[#0f1418] border-b border-[#262c33]/70 text-white shadow-xl">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 h-18 py-3 flex items-center justify-between">
        
        {{-- Brand Logo --}}
        <a href="{{ route('member.dashboard') }}" class="flex items-center gap-3 group">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-10 h-10 object-contain shrink-0">
            @else
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#e13b12] to-[#ff5722] flex items-center justify-center text-white font-black text-sm shrink-0 shadow-lg shadow-[#ff5722]/30">
                    GMF
                </div>
            @endif
            <div>
                <p class="text-white font-extrabold font-display text-sm sm:text-base tracking-tight leading-none uppercase group-hover:text-[#ff5722] transition-colors">Gintung Master</p>
                <p class="text-[#ff5722] text-[10px] font-bold tracking-widest uppercase mt-0.5">Member Club</p>
            </div>
        </a>

        {{-- Member User & Logout Button --}}
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-[#1e242b] border border-[#262c33]">
                <div class="w-6 h-6 rounded-full bg-[#ff5722] text-white flex items-center justify-center text-[10px] font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-xs font-semibold text-slate-200">{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-white/5 rounded-xl transition-colors flex items-center gap-1.5 text-xs font-semibold" title="Keluar">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</header>

{{-- MAIN CONTENT AREA --}}
<main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6 lg:p-8 pb-36 sm:pb-44">
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
        class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
        <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('success') }}</span>
        <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,8000)"
        class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
        <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-medium">{{ session('error') }}</span>
        <button @click="show=false" class="ml-auto text-rose-500 hover:text-rose-700">✕</button>
    </div>
    @endif

    @yield('content')
</main>

{{-- FLOATING BOTTOM NAVIGATION BAR --}}
<div x-data="{ hideNav: false }"
     @modal-open.window="hideNav = true"
     @modal-close.window="hideNav = false"
     x-show="!hideNav"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-6"
     class="fixed bottom-4 inset-x-4 max-w-xl mx-auto z-40">
    <nav class="bg-[#0f1418]/95 backdrop-blur-xl border border-[#262c33] text-white rounded-3xl px-2.5 py-2 shadow-2xl shadow-black/60 flex items-center justify-between">
        @php
        $navItems = [
            ['route' => 'member.dashboard',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'locked' => false],
            ['route' => 'member.booking.index',       'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Booking', 'locked' => $isExpiredMember],
            ['route' => 'member.class-history.index', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Riwayat', 'locked' => $isExpiredMember],
            ['route' => 'member.invoice.index',       'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Tagihan', 'locked' => $isExpiredMember],
            ['route' => 'member.profile.index',       'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Profil', 'locked' => false],
        ];
        @endphp

        @foreach($navItems as $item)
        @php $isActive = request()->routeIs(rtrim($item['route'], '.index') . '*'); @endphp
        <a href="{{ route($item['route']) }}"
            class="flex flex-col items-center justify-center py-1.5 px-3 rounded-2xl transition-all duration-200 relative group flex-1
            {{ $isActive ? 'bg-[#ff5722] text-white font-bold shadow-lg shadow-[#ff5722]/40' : ($item['locked'] ? 'text-slate-500 opacity-60' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5') }}">
            
            <div class="relative">
                <svg class="w-5 h-5 transition-transform duration-200 {{ $isActive ? 'scale-110 text-white' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>

                @if($item['locked'])
                <span class="absolute -top-1 -right-2 text-[10px]">🔒</span>
                @endif
            </div>

            <span class="text-[10px] tracking-tight mt-1 whitespace-nowrap {{ $isActive ? 'font-bold' : 'font-medium' }}">
                {{ $item['label'] }}
            </span>
        </a>
        @endforeach
    </nav>
</div>

@stack('scripts')
</body>
</html>
