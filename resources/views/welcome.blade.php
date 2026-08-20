<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gintung Master Fitness — Center Training & Gymnasium</title>
    <meta name="description" content="Gintung Master Fitness — Pusat kebugaran dan fitness center terbaik di Gintung. Fasilitas lengkap, pelatih profesional, dan program membership terjangkau.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#08090d] text-slate-100 antialiased selection:bg-[#f05a2a] selection:text-white" x-data="{ mobileMenu: false }">

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- NAVBAR ─────────────────────────────────────────────────────────────────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-[#08090d]/90 backdrop-blur-md border-b border-slate-800/80 transition-all" id="navbar">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if(file_exists(public_path('images/gmf.png')))
                    <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#f05a2a] to-[#e13b12] flex items-center justify-center font-black text-xs text-white shadow-lg shadow-[#f05a2a]/20">
                        GMF
                    </div>
                @endif
                <div>
                    <span class="text-white font-extrabold font-display text-base tracking-tight block leading-none">Gintung Master</span>
                    <span class="text-[#f05a2a] font-bold text-xs tracking-wider uppercase block leading-none mt-1">Fitness Center</span>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="#about" class="text-slate-300 hover:text-[#f05a2a] text-sm font-medium transition-colors">Keunggulan</a>
                <a href="#classes" class="text-slate-300 hover:text-[#f05a2a] text-sm font-medium transition-colors">Jadwal Kelas</a>
                <a href="#gallery" class="text-slate-300 hover:text-[#f05a2a] text-sm font-medium transition-colors">Galeri & Informasi</a>
                <a href="#packages" class="text-slate-300 hover:text-[#f05a2a] text-sm font-medium transition-colors">Paket Membership</a>
                <a href="#contact" class="text-slate-300 hover:text-[#f05a2a] text-sm font-medium transition-colors">Kontak</a>
            </div>

            {{-- CTA Actions --}}
            <div class="hidden md:flex items-center gap-3">
                @guest
                <a href="{{ route('login') }}"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-200 hover:text-white bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 rounded-xl transition-all">
                    Masuk
                </a>
                <a href="{{ route('register.step1') }}"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] rounded-xl transition-all shadow-md shadow-[#f05a2a]/25 hover:shadow-lg hover:shadow-[#f05a2a]/35">
                    Daftar Member
                </a>
                @else
                @php
                    $roleRoute = match(auth()->user()->role) {
                        'owner' => 'owner.dashboard',
                        'admin' => 'admin.dashboard',
                        'coach' => 'coach.dashboard',
                        'member' => 'member.dashboard',
                        default => 'home'
                    };
                @endphp
                <a href="{{ route($roleRoute) }}"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] rounded-xl transition-all shadow-md shadow-[#f05a2a]/25 flex items-center gap-2">
                    <span>Dashboard {{ ucfirst(auth()->user()->role) }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @endguest
            </div>

            {{-- Mobile Menu Trigger --}}
            <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Mobile Nav Dropdown --}}
        <div x-show="mobileMenu" x-transition class="md:hidden py-4 border-t border-slate-800 space-y-3">
            <a href="#about" @click="mobileMenu=false" class="block px-3 py-2 text-slate-300 hover:text-white text-sm font-medium">Keunggulan</a>
            <a href="#classes" @click="mobileMenu=false" class="block px-3 py-2 text-slate-300 hover:text-white text-sm font-medium">Jadwal Kelas</a>
            <a href="#gallery" @click="mobileMenu=false" class="block px-3 py-2 text-slate-300 hover:text-white text-sm font-medium">Galeri & Informasi</a>
            <a href="#packages" @click="mobileMenu=false" class="block px-3 py-2 text-slate-300 hover:text-white text-sm font-medium">Paket Membership</a>
            <div class="flex gap-3 pt-2">
                @guest
                <a href="{{ route('login') }}" class="flex-1 py-2.5 text-center text-sm font-semibold border border-slate-700 rounded-xl text-slate-200">Masuk</a>
                <a href="{{ route('register.step1') }}" class="flex-1 py-2.5 text-center text-sm font-semibold bg-[#f05a2a] rounded-xl text-white">Daftar</a>
                @else
                <a href="{{ route($roleRoute ?? 'home') }}" class="flex-1 py-2.5 text-center text-sm font-semibold bg-[#f05a2a] rounded-xl text-white">Dashboard →</a>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- HERO SECTION ───────────────────────────────────────────────────────────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section class="min-h-screen flex items-center relative overflow-hidden pt-28 pb-16 bg-gradient-to-b from-[#08090d] via-[#0f121a] to-[#08090d]">
    
    {{-- Background Glow --}}
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-[#f05a2a]/10 blur-[120px] rounded-full pointer-events-none"></div>

    @php
        $heroItem = isset($contents['hero']) ? $contents['hero']->first() : null;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7">
                
                {{-- Static Badge (Tanpa . kedip / animate-pulse) --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900/90 border border-slate-700/80 text-slate-200 text-xs font-semibold mb-8 backdrop-blur-sm">
                    <span class="text-[#f05a2a]">GMF</span>
                    <span>Pusat Kebugaran & Gymnasium Modern Gintung</span>
                </div>

                {{-- Hero Title --}}
                <h1 class="text-4xl sm:text-6xl lg:text-6xl font-extrabold font-display leading-[1.1] tracking-tight mb-6">
                    @if($heroItem && $heroItem->title)
                        <span class="bg-gradient-to-r from-white via-slate-100 to-[#f05a2a] bg-clip-text text-transparent">{{ $heroItem->title }}</span>
                    @else
                        <span class="bg-gradient-to-r from-white via-slate-100 to-[#f05a2a] bg-clip-text text-transparent">Mulai Perjalanan Fitness Anda Bersama Gintung Master Fitness</span>
                    @endif
                </h1>

                <p class="text-slate-400 text-lg leading-relaxed mb-10 max-w-2xl font-normal">
                    @if($heroItem && $heroItem->body)
                        {{ $heroItem->body }}
                    @else
                        Fasilitas gymnasium terlengkap, instruktur bersertifikat, kelas grup variatif, serta sistem integrasi digital untuk kenyamanan latihan harian Anda.
                    @endif
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap gap-4 mb-14">
                    <a href="{{ route('register.step1') }}"
                        class="inline-flex items-center gap-2.5 px-8 py-4 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-bold text-base rounded-2xl transition-all shadow-lg shadow-[#f05a2a]/25 hover:shadow-xl hover:shadow-[#f05a2a]/35">
                        <span>Bergabung Sekarang</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="#packages"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-slate-800/80 hover:bg-slate-800 text-slate-200 hover:text-white font-semibold text-base rounded-2xl border border-slate-700/80 transition-all">
                        Pilihan Paket
                    </a>
                </div>

                {{-- Statistics Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-6 border-t border-slate-800/80">
                    <div>
                        <p class="text-3xl font-extrabold text-[#f05a2a] font-display">100+</p>
                        <p class="text-slate-400 text-xs font-medium mt-1">Member Aktif</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-[#f05a2a] font-display">10+</p>
                        <p class="text-slate-400 text-xs font-medium mt-1">Sesi Kelas/Minggu</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-[#f05a2a] font-display">5+</p>
                        <p class="text-slate-400 text-xs font-medium mt-1">Pelatih Profesional</p>
                    </div>
                    <div>
                        <p class="text-3xl font-extrabold text-[#f05a2a] font-display">100%</p>
                        <p class="text-slate-400 text-xs font-medium mt-1">Digital QR System</p>
                    </div>
                </div>
            </div>

            {{-- Right Visual Showcase (Menggunakan Foto Hero CMS jika ada) --}}
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-full max-w-md p-4 rounded-3xl bg-slate-900/80 border border-slate-800 shadow-2xl backdrop-blur-xl overflow-hidden">
                    @if($heroItem && $heroItem->image_path)
                        <div class="relative h-72 w-full rounded-2xl overflow-hidden mb-4 border border-slate-800">
                            <img src="{{ asset('storage/' . $heroItem->image_path) }}" alt="{{ $heroItem->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-3 left-3 right-3 text-xs text-slate-200 font-semibold truncate">
                                {{ $heroItem->title }}
                            </div>
                        </div>
                    @endif

                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            @if(file_exists(public_path('images/gmf.png')))
                                <img src="{{ asset('images/gmf.png') }}" alt="GMF" class="w-10 h-10 object-contain">
                            @endif
                            <div>
                                <h3 class="text-white font-bold text-base font-display">Gintung Master Fitness</h3>
                                <p class="text-slate-400 text-xs">Official Gym & Fitness Center</p>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#f05a2a]/20 text-[#f05a2a] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white text-xs">Presensi QR Presisi</p>
                                <p class="text-[11px] text-slate-400">Masuk gym cepat dan terintegrasi otomatis</p>
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-slate-950/60 border border-slate-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#f05a2a]/20 text-[#f05a2a] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-white text-xs">Jadwal Kelas Terintegrasi Real-time</p>
                                <p class="text-[11px] text-slate-400">Informasi kelas langsung terhubung dari sistem Admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- FEATURES SECTION ───────────────────────────────────────────────────────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="about" class="py-24 bg-[#08090d] border-t border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#f05a2a] text-xs font-bold uppercase tracking-widest block mb-2">Fasilitas & Standar</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-display text-white">Mengapa Memilih <span class="text-[#f05a2a]">Gintung Master Fitness?</span></h2>
            <p class="text-slate-400 text-sm mt-3 max-w-xl mx-auto">Pengalaman latihan optimal didukung peralatan berkualitas dan sistem pelayanan terintegrasi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $features = [
                    ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'title' => 'Peralatan Modern', 'desc' => 'Dukungan perlengkapan cardio, plate-loaded machines, dan free weights yang terawat baik.'],
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Pelatih Bersertifikat', 'desc' => 'Instruktur profesional yang siap mendampingi program latihan dan kelas grup secara terarah.'],
                    ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Sistem Integrasi QR', 'desc' => 'Kemudahan absensi masuk gym dan reservasi jadwal kelas secara digital melalui portal member.'],
                    ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'title' => 'Jadwal Kelas Fleksibel', 'desc' => 'Pilihan kelas grup yang disesuaikan dengan aktivitas operasional harian Anda.'],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Tarif Kompetitif', 'desc' => 'Pilihan paket membership harian, bulanan reguler, hingga paket khusus pelajar.'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Area Nyaman & Steril', 'desc' => 'Ruang gym yang bersih, tersirkulasi dengan baik, dan aman untuk kenyamanan sesi latihan Anda.'],
                ];
            @endphp

            @foreach($features as $f)
            <div class="p-6 rounded-2xl bg-slate-900/60 border border-slate-800 hover:border-[#f05a2a]/40 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-[#f05a2a]/15 text-[#f05a2a] flex items-center justify-center mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-base mb-2 font-display">{{ $f['title'] }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed font-normal">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- CLASSES PROGRAM (Terintegrasi Real-time dengan Jadwal Kelas Admin Page) ─── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="classes" class="py-24 bg-[#0c0e14] border-t border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#f05a2a] text-xs font-bold uppercase tracking-widest block mb-2">Program Latihan Real-Time</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-display text-white">Kelas Grup Pilihan</h2>
            <p class="text-slate-400 text-sm mt-3 max-w-xl mx-auto">Daftar kelas kebugaran grup yang resmi terdaftar di Gintung Master Fitness.</p>
        </div>

        @if($classTypes && $classTypes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classTypes as $class)
            <div class="p-7 rounded-2xl bg-slate-900 border border-slate-800 flex flex-col justify-between hover:border-[#f05a2a]/40 transition-all">
                <div>
                    @if($class->photo_path)
                        <div class="relative h-44 w-full rounded-xl overflow-hidden mb-5 border border-slate-800">
                            <img src="{{ asset('storage/' . $class->photo_path) }}" alt="{{ $class->name }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-white font-display">{{ $class->name }}</h3>
                        <span class="px-2.5 py-1 rounded-full bg-[#f05a2a]/15 text-[#f05a2a] text-xs font-semibold">
                            Aktif
                        </span>
                    </div>

                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        {{ $class->description ?? 'Program kelas kebugaran terarah yang dirancang untuk meningkatkan kesehatan fisik, ketahanan otot, dan stamina Anda.' }}
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-800/80 space-y-2.5">
                    <p class="text-xs font-bold text-slate-300 uppercase tracking-wider">Jadwal Kelas Terdaftar:</p>
                    @if($class->schedules && $class->schedules->count() > 0)
                        <div class="space-y-2.5">
                            @foreach($class->schedules as $sched)
                            @php
                                $coachName = 'Instruktur GMF';
                                if ($sched->coach) {
                                    $coachName = $sched->coach->full_name 
                                        ?? ($sched->coach->user ? $sched->coach->user->name : ($sched->coach->name ?? 'Instruktur GMF'));
                                }
                                $bookedCount = $sched->bookings ? $sched->bookings->whereIn('status', ['booked', 'attended'])->count() : 0;
                            @endphp
                            <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800/80 space-y-2">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-[#f05a2a]">{{ $sched->day_label }}</span>
                                    <span class="text-slate-200 font-medium">{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }} WIB</span>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-300 pt-1.5 border-t border-slate-800/60">
                                    <div class="flex items-center gap-1.5 font-medium text-slate-300">
                                        <svg class="w-3.5 h-3.5 text-[#f05a2a] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>Coach: {{ $coachName }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 font-bold text-white bg-slate-900 px-2 py-0.5 rounded border border-slate-700/60" title="Jumlah pendaftar dari total kuota">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>{{ $bookedCount }}/{{ $sched->max_capacity }}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-2.5 rounded-lg bg-slate-950/40 border border-slate-800 text-slate-500 text-xs italic">
                            Jadwal sesi kelas akan segera di-update oleh admin.
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="max-w-md mx-auto p-8 rounded-2xl bg-slate-900 border border-slate-800 text-center">
            <p class="text-slate-400 text-sm font-medium mb-4">Jadwal kelas belum tersedia. Kelas baru akan segera diterbitkan oleh Admin.</p>
            <a href="{{ route('register.step1') }}" class="inline-block px-5 py-2.5 bg-[#f05a2a] text-white text-xs font-bold rounded-xl">Daftar Member Sekarang</a>
        </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- GALLERY & CMS CONTENT SECTION (Terintegrasi 100% dengan Admin CMS) ─────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="gallery" class="py-24 bg-[#08090d] border-t border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#f05a2a] text-xs font-bold uppercase tracking-widest block mb-2">Galeri & Informasi Terbaru</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-display text-white">Kegiatan & Dokumen <span class="text-[#f05a2a]">GMF</span></h2>
            <p class="text-slate-400 text-sm mt-3 max-w-xl mx-auto">Dokumentasi suasana fasilitas, kegiatan latihan, serta penawaran informasi terbaru dari Admin CMS.</p>
        </div>

        @php
            // Ambil semua konten CMS aktif selain yang khusus Hero utama
            $allCmsContents = collect();
            if (isset($contents) && count($contents) > 0) {
                foreach ($contents as $secName => $secItems) {
                    foreach ($secItems as $itm) {
                        $allCmsContents->push($itm);
                    }
                }
            }
        @endphp

        @if($allCmsContents->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allCmsContents as $cms)
            <div class="rounded-2xl bg-slate-900 border border-slate-800 overflow-hidden hover:border-[#f05a2a]/40 transition-all duration-300 flex flex-col justify-between">
                <div>
                    @if($cms->image_path)
                        <div class="relative h-56 w-full bg-slate-950 overflow-hidden">
                            <img src="{{ asset('storage/' . $cms->image_path) }}" alt="{{ $cms->title }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                            <div class="absolute top-3 left-3">
                                <span class="px-2.5 py-1 rounded-md bg-black/60 backdrop-blur-md text-[#f05a2a] text-[10px] font-bold uppercase tracking-wider border border-slate-700/60">
                                    {{ $cms->section }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="h-32 bg-slate-950 flex items-center justify-center border-b border-slate-800 text-slate-600 text-xs">
                            Tidak Ada Gambar
                        </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-lg font-bold text-white mb-2 font-display">{{ $cms->title }}</h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            {{ $cms->body ?? 'Informasi resmi Gintung Master Fitness.' }}
                        </p>
                    </div>
                </div>

                <div class="px-6 pb-6 pt-2 border-t border-slate-800/40 text-xs text-slate-500">
                    Dipublikasikan via CMS Admin
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="max-w-md mx-auto p-8 rounded-2xl bg-slate-900 border border-slate-800 text-center">
            <p class="text-slate-400 text-sm font-medium">Belum ada foto galeri yang diunggah dari CMS Admin.</p>
        </div>
        @endif
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- PRICING PACKAGES ───────────────────────────────────────────────────────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<section id="packages" class="py-24 bg-[#0c0e14] border-t border-slate-800/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[#f05a2a] text-xs font-bold uppercase tracking-widest block mb-2">Pilihan Keanggotaan</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold font-display text-white">Paket Membership <span class="text-[#f05a2a]">GMF</span></h2>
            <p class="text-slate-400 text-sm mt-3 max-w-xl mx-auto">Pilih paket keanggotaan yang sesuai dengan kebutuhan dan preferensi jadwal Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            @php
                $packageMeta = [
                    'monthly_regular' => ['popular' => true, 'features' => ['Akses penuh fasilitas gym 30 hari', 'Booking sesi kelas grup', 'Kartu member digital & QR Code', 'Akses dashboard member']],
                    'daily'           => ['popular' => false, 'features' => ['Akses gym 1 hari kunjungan', 'Kode QR akses valid 24 jam', 'Cocok untuk uji coba/trial', 'Akses fasilitas gym dasar']],
                    'monthly_student' => ['popular' => false, 'features' => ['Akses gym 30 hari khusus pelajar', 'Booking kelas grup', 'Tarif khusus kartu pelajar/KTM', 'Kartu member digital']],
                ];
            @endphp

            @foreach($packages as $pkg)
            @php $meta = $packageMeta[$pkg->type] ?? ['popular' => false, 'features' => []]; @endphp
            <div class="relative rounded-3xl p-8 transition-all duration-300 flex flex-col justify-between
                {{ $meta['popular'] ? 'bg-gradient-to-b from-slate-900 to-slate-950 border-2 border-[#f05a2a] shadow-xl shadow-[#f05a2a]/10' : 'bg-slate-900/60 border border-slate-800 hover:border-slate-700' }}">

                @if($meta['popular'])
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                    <span class="px-3.5 py-1 bg-[#f05a2a] text-white text-[11px] font-bold uppercase tracking-wider rounded-full shadow-md">Paket Favorit</span>
                </div>
                @endif

                <div>
                    <h3 class="text-xl font-bold text-white mb-2 font-display">{{ $pkg->name }}</h3>
                    <p class="text-slate-400 text-xs mb-6 leading-relaxed">{{ $pkg->description }}</p>

                    <div class="mb-6 pb-6 border-b border-slate-800">
                        <span class="text-3xl sm:text-4xl font-extrabold text-white font-display">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                        <span class="text-slate-500 text-xs block mt-1 font-medium">Masa berlaku {{ $pkg->duration_days }} hari</span>
                    </div>

                    <ul class="space-y-3.5 mb-8">
                        @foreach($meta['features'] as $feature)
                        <li class="flex items-start gap-3 text-xs text-slate-300">
                            <svg class="w-4 h-4 text-[#f05a2a] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ $feature }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <a href="{{ route('register.step1') }}"
                    class="block w-full py-3.5 text-center font-semibold text-sm rounded-xl transition-all duration-200
                    {{ $meta['popular'] ? 'bg-[#f05a2a] hover:bg-[#ff6f4d] text-white shadow-md shadow-[#f05a2a]/20' : 'bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700' }}">
                    Pilih Paket Ini
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- FOOTER ─────────────────────────────────────────────────────────────────── --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<footer id="contact" class="border-t border-slate-800 bg-[#06070a] py-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start gap-10">
            <div class="max-w-xs">
                <div class="flex items-center gap-3 mb-4">
                    @if(file_exists(public_path('images/gmf.png')))
                        <img src="{{ asset('images/gmf.png') }}" alt="GMF Logo" class="h-8 w-auto object-contain">
                    @endif
                    <span class="text-white font-extrabold font-display text-base">Gintung Master Fitness</span>
                </div>
                <p class="text-slate-500 text-xs leading-relaxed">Sistem Informasi Manajemen Fitness Center & Gymnasium Gintung.</p>
            </div>
            <div class="grid grid-cols-2 gap-12 text-xs">
                <div>
                    <h4 class="text-white font-bold mb-4 font-display">Navigasi</h4>
                    <ul class="space-y-2.5 text-slate-400">
                        <li><a href="#about" class="hover:text-white transition-colors">Keunggulan</a></li>
                        <li><a href="#classes" class="hover:text-white transition-colors">Jadwal Kelas</a></li>
                        <li><a href="#gallery" class="hover:text-white transition-colors">Galeri & Informasi</a></li>
                        <li><a href="#packages" class="hover:text-white transition-colors">Paket Membership</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4 font-display">Portal Akses</h4>
                    <ul class="space-y-2.5 text-slate-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Masuk Akun</a></li>
                        <li><a href="{{ route('register.step1') }}" class="hover:text-white transition-colors">Pendaftaran Member</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-slate-800/80 mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>© {{ date('Y') }} Gintung Master Fitness Center. Hak Cipta Dilindungi.</p>
            <p>Sistem Informasi Manajemen Fitness</p>
        </div>
    </div>
</footer>

</body>
</html>
