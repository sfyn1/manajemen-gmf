{{-- Step indicator untuk wizard pendaftaran member --}}
<div class="mb-10">
    <div class="flex items-center justify-center">
        @php $steps = ['Data Diri', 'Pilih Paket', 'Dokumen', 'Pembayaran']; @endphp
        @foreach($steps as $i => $label)
            @php $stepNum = $i + 1; $isActive = $stepNum === $currentStep; $isDone = $stepNum < $currentStep; @endphp
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $isDone ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/20' : ($isActive ? 'bg-[#f05a2a] text-white shadow-lg shadow-[#f05a2a]/30 ring-2 ring-[#f05a2a]/40 font-extrabold' : 'bg-slate-900 border border-slate-800 text-slate-500') }}">
                        @if($isDone)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $stepNum }}
                        @endif
                    </div>
                    <span class="mt-2 text-[11px] font-medium tracking-tight {{ $isActive ? 'text-[#f05a2a] font-bold' : ($isDone ? 'text-emerald-400' : 'text-slate-500') }}">{{ $label }}</span>
                </div>
                @if($i < count($steps) - 1)
                    <div class="w-12 sm:w-20 md:w-24 h-0.5 mb-5 mx-2 rounded-full {{ $isDone ? 'bg-emerald-500' : 'bg-slate-800' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
