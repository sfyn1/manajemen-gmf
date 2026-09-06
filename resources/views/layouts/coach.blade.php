<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coach Portal') — Gintung Master Fitness</title>

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
    $coachModel = auth()->user()?->coach;
    $pendingVerificationCount = $coachModel ? \App\Models\AttendanceVerification::where('coach_id', $coachModel->id)->whereIn('status', ['pending', 'rejected'])->count() : 0;
@endphp

{{-- TOPBAR HEADER --}}
<header class="sticky top-0 z-40 bg-[#0f1418] border-b border-[#262c33]/70 text-white shadow-xl">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 h-18 py-3 flex items-center justify-between">
        
        {{-- Brand Logo --}}
        <a href="{{ route('coach.dashboard') }}" class="flex items-center gap-3 group">
            @if(file_exists(public_path('images/gmf.png')))
                <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-10 h-10 object-contain shrink-0">
            @else
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#ff5722] to-emerald-500 flex items-center justify-center text-white font-black text-sm shrink-0 shadow-lg shadow-[#ff5722]/30">
                    GMF
                </div>
            @endif
            <div>
                <p class="text-white font-extrabold font-display text-sm sm:text-base tracking-tight leading-none uppercase group-hover:text-[#ff5722] transition-colors">Gintung Master</p>
                <p class="text-emerald-400 text-[10px] font-bold tracking-widest uppercase mt-0.5">Coach Portal</p>
            </div>
        </a>

        {{-- Coach User & Logout Button --}}
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-[#1e242b] border border-[#262c33]">
                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="text-xs font-semibold text-slate-200">{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-rose-400 hover:bg-white/5 rounded-xl transition-colors flex items-center gap-1.5 text-xs font-semibold" title="Keluar">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</header>

{{-- MAIN CONTENT AREA --}}
<main class="flex-1 max-w-5xl w-full mx-auto p-4 sm:p-6 lg:p-8 pb-36 sm:pb-44">
    @if(session('success'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
        class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
        <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
        <span class="font-medium">{{ session('success') }}</span>
        <button @click="show=false" class="ml-auto text-emerald-500 hover:text-emerald-700">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,8000)"
        class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 shadow-sm">
        <span class="material-symbols-outlined text-rose-600 text-[20px]">error</span>
        <span class="font-medium">{{ session('error') }}</span>
        <button @click="show=false" class="ml-auto text-rose-500 hover:text-rose-700">✕</button>
    </div>
    @endif

    @yield('content')
</main>

{{-- FLOATING BOTTOM NAVIGATION BAR (COACH MOBILE-FIRST) --}}
<div x-data="{ hideNav: false }"
     @modal-open.window="hideNav = true"
     @modal-close.window="hideNav = false"
     x-show="!hideNav"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-6"
     class="fixed bottom-4 inset-x-4 max-w-lg mx-auto z-40">
    <nav class="bg-[#0f1418]/95 backdrop-blur-xl border border-[#262c33] text-white rounded-3xl px-2.5 py-2 shadow-2xl shadow-black/60 flex items-center justify-between">
        @php
        $navItems = [
            [
                'route' => 'coach.dashboard',
                'icon' => 'dashboard',
                'label' => 'Dashboard',
                'badge' => 0
            ],
            [
                'route' => 'coach.attendance.index',
                'icon' => 'fact_check',
                'label' => 'Verifikasi',
                'badge' => $pendingVerificationCount
            ],
            [
                'route' => 'coach.history.index',
                'icon' => 'history',
                'label' => 'Riwayat',
                'badge' => 0
            ],
            [
                'route' => 'coach.commission.index',
                'icon' => 'account_balance_wallet',
                'label' => 'Komisi',
                'badge' => 0
            ],
        ];
        @endphp

        @foreach($navItems as $item)
        @php $isActive = request()->routeIs(rtrim($item['route'], '.index') . '*'); @endphp
        <a href="{{ route($item['route']) }}"
            class="flex flex-col items-center justify-center py-1.5 px-3 rounded-2xl transition-all duration-200 relative group flex-1
            {{ $isActive ? 'bg-[#ff5722] text-white font-bold shadow-lg shadow-[#ff5722]/40' : 'text-slate-400 hover:text-slate-200 hover:bg-white/5' }}">
            
            <div class="relative">
                <span class="material-symbols-outlined text-[22px] transition-transform duration-200 {{ $isActive ? 'scale-110 text-white' : '' }}">
                    {{ $item['icon'] }}
                </span>

                @if($item['badge'] > 0)
                <span class="absolute -top-1 -right-2 w-4 h-4 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border border-[#0f1418] animate-bounce">
                    {{ $item['badge'] }}
                </span>
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
