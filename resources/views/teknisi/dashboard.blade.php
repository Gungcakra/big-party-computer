<x-layouts.admin heading="Antrian Saya">

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Antrian Masuk</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">0</p>
            <p class="mt-3 text-xs text-slate-400">Menunggu pengerjaan</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Dalam Pengerjaan</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">0</p>
            <p class="mt-3 text-xs text-slate-400">Sedang dikerjakan</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Selesai Hari Ini</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">0</p>
            <p class="mt-3 text-xs text-slate-400">Selesai diperbaiki</p>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">Antrian Perangkat</h2>
        </div>
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <svg class="mb-3 h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm font-medium text-slate-500">Belum ada antrian</p>
            <p class="mt-1 text-xs text-slate-400">Antrian akan muncul setelah admin menugaskan perangkat</p>
        </div>
    </div>

</x-layouts.admin>
