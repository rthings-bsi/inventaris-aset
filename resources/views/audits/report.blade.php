@extends('layouts.app')

@section('title', 'Laporan Audit - ' . $audit->title)

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }
    .glass-header {
        background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(243, 232, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
</style>

<!-- Page Header -->
<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('audits.index') }}" class="group inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-400 hover:text-indigo-600 transition-colors mb-4">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                Kembali ke Daftar
            </a>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                Laporan Hasil <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700">Audit Aset</span>
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl flex items-center gap-2">
                Analisis perbandingan antara basis data sistem dan hasil pemindaian riil.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('audits.export.excel', $audit) }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-white border-2 border-emerald-100 px-6 py-3.5 text-xs font-black text-emerald-600 shadow-sm hover:bg-emerald-50 transition-all">
                <i class="fas fa-file-excel text-lg"></i>
                Export Excel
            </a>
            <a href="{{ route('audits.export.pdf', $audit) }}" class="inline-flex items-center gap-2.5 rounded-2xl bg-white border-2 border-rose-100 px-6 py-3.5 text-xs font-black text-rose-600 shadow-sm hover:bg-rose-50 transition-all">
                <i class="fas fa-file-pdf text-lg"></i>
                Download PDF
            </a>
        </div>
    </div>
</div>

<!-- Analytics Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
    <div class="glass-card rounded-[2rem] p-8 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-1">Total Sesuai (Found)</p>
            <h2 class="text-4xl font-black text-emerald-700">{{ $foundItems->count() }}</h2>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-gray-400">
                <i class="fas fa-check-circle text-emerald-400"></i> Terverifikasi Ada
            </div>
        </div>
    </div>
    <div class="glass-card rounded-[2rem] p-8 relative overflow-hidden group hover:-translate-y-1 transition-all border-rose-100">
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-1">Total Hilang (Missing)</p>
            <h2 class="text-4xl font-black text-rose-700">{{ $missingAssets->count() }}</h2>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-gray-400">
                <i class="fas fa-times-circle text-rose-400"></i> Tidak Ditemukan
            </div>
        </div>
    </div>
    <div class="glass-card rounded-[2rem] p-8 relative overflow-hidden group hover:-translate-y-1 transition-all border-amber-100">
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-50 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-1">Aset Tidak Terduga</p>
            <h2 class="text-4xl font-black text-amber-700">{{ $unexpectedItems->count() }}</h2>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-gray-400">
                <i class="fas fa-exclamation-triangle text-amber-400"></i> Surplus / Unlisted
            </div>
        </div>
    </div>
    <div class="glass-card rounded-[2rem] p-8 bg-slate-900 shadow-xl shadow-slate-200 relative overflow-hidden group hover:-translate-y-1 transition-all">
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
        <div class="relative z-10">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Stock Sistem</p>
            <h2 class="text-4xl font-black text-white">{{ $foundItems->count() + $missingAssets->count() }}</h2>
            <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-slate-500">
                <i class="fas fa-database text-indigo-400"></i> Database Snapshot
            </div>
        </div>
    </div>
</div>

<div class="space-y-12 mb-24">
    <!-- Section Missing -->
    <div class="space-y-6">
        <div class="flex items-center gap-4 ml-6">
            <div class="w-1.5 h-8 bg-rose-500 rounded-full shadow-[0_0_10px_rgba(244,63,94,0.5)]"></div>
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Aset <span class="text-rose-600 underline decoration-rose-200 underline-offset-4">Hilang / Tidak Ditemukan</span></h3>
        </div>
        <div class="glass-card rounded-[2.5rem] p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4">Informasi Aset</th>
                            <th class="px-6 py-4">Status Sistem</th>
                            <th class="px-6 py-4">Lokasi Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($missingAssets as $asset)
                        <tr class="group hover:bg-rose-50/20 transition-all duration-300">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-gray-800">{{ $asset->asset_name }}</span>
                                    <span class="font-mono text-[9px] font-black text-rose-500 mt-0.5 tracking-wider uppercase">{{ $asset->asset_code }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="rounded-lg bg-gray-100 px-3 py-1 text-[9px] font-black uppercase text-gray-500 border border-gray-200">{{ $asset->status }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-xs font-bold text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-300"></i> {{ $asset->location ? $asset->location->location_name : '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-check-double text-xl"></i>
                                    </div>
                                    <p class="text-sm font-black text-emerald-600 uppercase tracking-widest">Semua aset di sistem terverifikasi ada!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Unexpected -->
    <div class="space-y-6">
        <div class="flex items-center gap-4 ml-6">
            <div class="w-1.5 h-8 bg-amber-500 rounded-full shadow-[0_0_10px_rgba(245,158,11,0.5)]"></div>
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Aset <span class="text-amber-600 underline decoration-amber-200 underline-offset-4">Tidak Terduga / Surplus</span></h3>
        </div>
        <div class="glass-card rounded-[2.5rem] p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4">Kode Dipindai</th>
                            <th class="px-6 py-4">Hasil Verifikasi</th>
                            <th class="px-6 py-4">Waktu Pindai</th>
                            <th class="px-6 py-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($unexpectedItems as $item)
                        <tr class="group hover:bg-amber-50/20 transition-all duration-300">
                            <td class="px-6 py-5 font-black text-amber-600 tracking-wider text-sm uppercase">{{ $item->scanned_code }}</td>
                            <td class="px-6 py-5 text-sm font-bold text-gray-700">Tidak Terdaftar di Database</td>
                            <td class="px-6 py-5 text-xs font-black text-indigo-400">{{ $item->scanned_at->format('H:i:s') }}</td>
                            <td class="px-6 py-5">
                                <span class="rounded-full bg-amber-50 px-3 py-1.5 text-[9px] font-black uppercase text-amber-600 border border-amber-100 shadow-sm">Perlu Investigasi</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest leading-loose">Tidak ada aset asing / surplus yang ditemukan di lapangan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Found -->
    <div class="space-y-6">
        <div class="flex items-center gap-4 ml-6">
            <div class="w-1.5 h-8 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Daftar Aset <span class="text-emerald-600 underline decoration-emerald-200 underline-offset-4">Terverifikasi (Ditemukan)</span></h3>
        </div>
        <div class="glass-card rounded-[2.5rem] p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4">Informasi Aset</th>
                            <th class="px-6 py-4">Waktu Pindai</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($foundItems as $item)
                        <tr class="group hover:bg-emerald-50/10 transition-all duration-300">
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-gray-800">{{ $item->asset->asset_name }}</span>
                                    <span class="font-mono text-[9px] font-black text-emerald-500 mt-0.5 tracking-wider uppercase">{{ $item->asset->asset_code }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-xs font-black text-indigo-400">{{ $item->scanned_at->format('H:i:s') }}</td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('assets.show', $item->asset) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-500 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-external-link-alt text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                                Belum ada data aset yang ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
