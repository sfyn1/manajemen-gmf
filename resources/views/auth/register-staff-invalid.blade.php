<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Undangan Tidak Valid — Gintung Master Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#08090d] text-slate-100 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-[#0f121a] border border-slate-800 rounded-3xl p-8 text-center shadow-2xl">
        <div class="w-16 h-16 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-white mb-3 font-display">Akses Undangan Ditolak</h2>
        <p class="text-slate-400 text-sm leading-relaxed mb-6">
            {{ $message ?? 'Link undangan registrasi tidak valid, telah kedaluwarsa, atau sudah digunakan.' }}
        </p>
        <div class="space-y-3">
            <a href="{{ route('login') }}" class="block w-full py-3 bg-[#f05a2a] hover:bg-[#ff6f4d] text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-[#f05a2a]/20">
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</body>
</html>
