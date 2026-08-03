<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Gintung Master Fitness</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/echo.js'])
    @stack('styles')
</head>
<body class="antialiased bg-slate-50 font-sans text-slate-800" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden bg-slate-100">

        {{-- ── SIDEBAR ───────────────────────────────────────────────────── --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 ease-in-out bg-slate-900 border-r border-slate-800"
            :class="sidebarOpen ? 'w-64' : 'w-20'">

            {{-- Brand Header --}}
            <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-800 shrink-0">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="w-9 h-9 object-contain shrink-0">
                @else
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#e13b12] to-[#f05a2a] flex items-center justify-center text-white font-black text-xs shrink-0 shadow-sm">
                        GMF
                    </div>
                @endif
                <div x-show="sidebarOpen" x-transition.opacity class="overflow-hidden whitespace-nowrap">
                    <p class="text-white font-extrabold font-display text-sm tracking-tight leading-none">Gintung Master Fitness</p>
                    <div class="flex items-center gap-1.5 mt-1">
                    </div>
                </div>
            </div>

            {{-- Navigation Items --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 space-y-1 px-3">
                @php
                    $navItems = [
                        ['route' => 'admin.dashboard',         'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard'],
                        ['route' => 'admin.approval.index',    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Approval Center', 'badge' => \App\Models\Member::pending()->count() + \App\Models\MembershipRenewal::pending()->count() + \App\Models\AttendanceVerification::pending()->whereNotNull('submitted_at')->count()],
                        ['route' => 'admin.membership.index',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Membership'],
                        ['route' => 'admin.scan-qr.index',     'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => 'Scan QR Absensi'],
                        ['route' => 'admin.coaches.index',     'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Kelola Pelatih'],
                        ['route' => 'admin.schedules.index',   'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Jadwal Kelas'],
                        ['route' => 'admin.packages.index',    'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'label' => 'Paket Fitness'],
                        ['route' => 'admin.products.index',    'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'label' => 'Produk & Kasir'],
                        ['route' => 'admin.payroll.index',     'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Gaji & Komisi'],
                        ['route' => 'admin.landing-content.index', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Konten Landing'],
                    ];
                @endphp

                @foreach($navItems as $item)
                @php $isActive = request()->routeIs(rtrim($item['route'], '.index') . '*'); @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group relative
                    {{ $isActive ? 'bg-[#f05a2a] text-white shadow-md shadow-[#f05a2a]/20 font-semibold' : 'text-slate-400 hover:bg-slate-800/80 hover:text-slate-200' }}"
                    title="{{ $item['label'] }}">

                    <svg class="w-5 h-5 shrink-0 transition-colors {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-200' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                    </svg>

                    <span x-show="sidebarOpen" x-transition.opacity class="text-sm whitespace-nowrap overflow-hidden">
                        {{ $item['label'] }}
                    </span>

                    {{-- Badge Notifikasi --}}
                    @if(isset($item['badge']) && $item['badge'] > 0)
                    <span class="ml-auto shrink-0 min-w-[20px] h-5 px-1.5 bg-[#e13b12] text-white text-[11px] font-bold rounded-full flex items-center justify-center"
                        x-show="sidebarOpen">
                        {{ $item['badge'] }}
                    </span>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-[#e13b12] rounded-full ring-2 ring-slate-900"
                        x-show="!sidebarOpen">
                    </span>
                    @endif
                </a>
                @endforeach
            </nav>

            {{-- Bottom User Profile & Logout --}}
            <div class="border-t border-slate-800 p-3 shrink-0 bg-slate-950/40">
                <div class="flex items-center gap-3" x-show="sidebarOpen" x-transition.opacity>
                    <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-[#f05a2a] font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-slate-200 text-xs font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-slate-500 text-[11px] truncate">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors" title="Keluar dari Sistem">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
                <form method="POST" action="{{ route('logout') }}" x-show="!sidebarOpen" class="flex justify-center">
                    @csrf
                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-slate-800 rounded-lg transition-colors" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Sidebar Toggle Button --}}
            <button @click="sidebarOpen = !sidebarOpen"
                class="absolute -right-3 top-20 w-6 h-6 bg-slate-800 border border-slate-700 rounded-full flex items-center justify-center text-slate-300 hover:text-white hover:bg-[#f05a2a] transition-all shadow-md z-10">
                <svg class="w-3.5 h-3.5 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
        </aside>

        {{-- Mobile Backdrop --}}
        <div x-show="sidebarOpen && window.innerWidth < 1024"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-40 lg:hidden"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"></div>

        {{-- ── MAIN CONTENT AREA ────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300"
            :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">

            {{-- Topbar --}}
            <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 shrink-0 shadow-sm z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h1 class="text-base font-bold text-slate-900 font-display leading-tight">@yield('page-title', 'Dashboard Admin')</h1>
                        <p class="text-xs text-slate-500 font-medium">@yield('page-subtitle', 'Gintung Master Fitness Center')</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Bell Notifications Dropdown --}}
                    <div class="relative" x-data="realtimeBell()" x-init="init()">
                        <button @click="showDropdown = !showDropdown" class="relative p-2 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span x-show="hasNew" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#f05a2a] rounded-full animate-ping"></span>
                            <span x-show="hasNew" class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#f05a2a] rounded-full"></span>
                        </button>
                        <div x-show="showDropdown" @click.outside="showDropdown = false"
                            class="absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden animate-fadeIn">
                            <div class="p-3.5 bg-slate-900 text-white flex justify-between items-center">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-300">Notifikasi Realtime</p>
                                <button @click="clearAll()" class="text-xs text-[#f05a2a] hover:underline font-semibold">Bersihkan</button>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 scrollbar-thin">
                                <template x-if="notifications.length === 0">
                                    <div class="p-6 text-center text-slate-400 text-sm">Tidak ada notifikasi baru</div>
                                </template>
                                <template x-for="n in notifications" :key="n.id">
                                    <a :href="n.link || '{{ route('admin.approval.index') }}'" class="block p-3.5 hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0">
                                        <div class="flex items-start gap-2.5">
                                            <span class="w-2 h-2 rounded-full bg-[#f05a2a] mt-1.5 shrink-0"></span>
                                            <div>
                                                <p class="text-xs font-semibold text-slate-800" x-text="n.message"></p>
                                                <p class="text-[10px] text-slate-400 mt-0.5" x-text="n.time"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Admin Header Avatar --}}
                    <div class="flex items-center gap-2.5 pl-2 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-xl bg-slate-900 text-[#f05a2a] flex items-center justify-center font-bold text-xs shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:inline-block text-xs font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            {{-- Main Content Window --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scrollbar-thin">

                {{-- Alert Messages --}}
                @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3 animate-slideUp shadow-sm">
                    <svg class="w-5 h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

@php
    $initialNotifs = collect();

    $pendingM = \App\Models\Member::pending()->latest()->take(5)->get();
    foreach($pendingM as $m) {
        $initialNotifs->push([
            'id' => 'm_' . $m->id,
            'message' => 'Pendaftaran member baru: ' . $m->full_name,
            'time' => $m->created_at->diffForHumans(),
            'link' => route('admin.approval.index')
        ]);
    }

    $pendingR = \App\Models\MembershipRenewal::pending()->with('member')->latest()->take(5)->get();
    foreach($pendingR as $r) {
        $initialNotifs->push([
            'id' => 'r_' . $r->id,
            'message' => 'Perpanjangan membership: ' . ($r->member?->full_name ?? 'Member'),
            'time' => $r->created_at->diffForHumans(),
            'link' => route('admin.approval.index')
        ]);
    }

    $pendingV = \App\Models\AttendanceVerification::pending()->whereNotNull('submitted_at')->with('coach.user')->latest('submitted_at')->take(5)->get();
    foreach($pendingV as $v) {
        $initialNotifs->push([
            'id' => 'v_' . $v->id,
            'message' => 'Verifikasi kehadiran coach: ' . ($v->coach?->user?->name ?? 'Coach'),
            'time' => $v->submitted_at ? $v->submitted_at->diffForHumans() : 'Baru saja',
            'link' => route('admin.approval.index')
        ]);
    }
@endphp

@push('scripts')
<script>
function realtimeBell() {
    return {
        showDropdown: false,
        hasNew: {{ $initialNotifs->count() > 0 ? 'true' : 'false' }},
        notifications: @json($initialNotifs),
        init() {
            if (window.Echo) {
                window.Echo.private('admin.notifications')
                    .listen('.member.registered', (e) => {
                        this.addNotif('Member baru mendaftar: ' + e.name, '{{ route("admin.approval.index") }}');
                    })
                    .listen('.payment.submitted', (e) => {
                        this.addNotif('Bukti pembayaran diterima: ' + e.name, '{{ route("admin.approval.index") }}');
                    });
            }
        },
        addNotif(msg, link = '{{ route("admin.approval.index") }}') {
            this.hasNew = true;
            this.notifications.unshift({
                id: Date.now(),
                message: msg,
                time: 'Baru saja',
                link: link
            });
        },
        clearAll() {
            this.notifications = [];
            this.hasNew = false;
            this.showDropdown = false;
        },
    };
}
</script>
@endpush

    @stack('scripts')
</body>
</html>
