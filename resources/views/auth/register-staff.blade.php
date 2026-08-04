<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Staff ({{ ucfirst($invitation->role) }}) — Gintung Master Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#08090d] text-slate-100 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#0f121a] border border-slate-800 rounded-3xl p-8 shadow-2xl">
        
        {{-- Header Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#f05a2a]/15 text-[#f05a2a] text-xs font-bold uppercase tracking-wider mb-4 border border-[#f05a2a]/30">
                Undangan Staff Resmi
            </div>
            <h1 class="text-2xl font-extrabold text-white font-display">Aktivasi Akun {{ ucfirst($invitation->role) }}</h1>
            <p class="text-slate-400 text-xs mt-2">Lengkapi data diri Anda untuk mengaktifkan akses staff.</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
        @endif

        {{-- Registration Form --}}
        <form method="POST" action="{{ route('register.staff.post') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $invitation->token }}">

            {{-- Email (Readonly) --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Alamat Email Undangan</label>
                <div class="relative">
                    <input type="email" value="{{ $invitation->email }}" readonly
                        class="w-full bg-slate-900/90 border border-slate-700/80 rounded-xl px-4 py-3 text-slate-400 text-sm font-medium cursor-not-allowed outline-none select-none">
                    <span class="absolute right-3 top-3 text-[10px] font-bold text-[#f05a2a] bg-[#f05a2a]/10 px-2 py-0.5 rounded border border-[#f05a2a]/20">
                        {{ strtoupper($invitation->role) }}
                    </span>
                </div>
                <p class="text-[11px] text-slate-500 mt-1">Email dikunci sesuai dengan undangan yang dikirimkan Pemilik Gym.</p>
            </div>

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap <span class="text-[#f05a2a]">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                    placeholder="Masukkan nama lengkap Anda..."
                    class="w-full bg-slate-900 border border-slate-700 focus:border-[#f05a2a] focus:ring-1 focus:ring-[#f05a2a] rounded-xl px-4 py-3 text-sm text-white outline-none transition-all placeholder:text-slate-600">
            </div>

            {{-- WhatsApp / No Telp --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">No. WhatsApp / Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="Contoh: 081234567890"
                    class="w-full bg-slate-900 border border-slate-700 focus:border-[#f05a2a] focus:ring-1 focus:ring-[#f05a2a] rounded-xl px-4 py-3 text-sm text-white outline-none transition-all placeholder:text-slate-600">
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi Baru <span class="text-[#f05a2a]">*</span></label>
                <input type="password" name="password" required
                    placeholder="Minimal 8 karakter..."
                    class="w-full bg-slate-900 border border-slate-700 focus:border-[#f05a2a] focus:ring-1 focus:ring-[#f05a2a] rounded-xl px-4 py-3 text-sm text-white outline-none transition-all placeholder:text-slate-600">
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Konfirmasi Kata Sandi <span class="text-[#f05a2a]">*</span></label>
                <input type="password" name="password_confirmation" required
                    placeholder="Ulangi kata sandi baru..."
                    class="w-full bg-slate-900 border border-slate-700 focus:border-[#f05a2a] focus:ring-1 focus:ring-[#f05a2a] rounded-xl px-4 py-3 text-sm text-white outline-none transition-all placeholder:text-slate-600">
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full py-3.5 bg-gradient-to-r from-[#f05a2a] to-[#e13b12] hover:from-[#ff6f4d] hover:to-[#f05a2a] text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-[#f05a2a]/25 mt-2">
                Aktifkan Akun {{ ucfirst($invitation->role) }} →
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-800/80 pt-4">
            Gintung Master Fitness &copy; {{ date('Y') }}
        </div>
    </div>
</body>
</html>
