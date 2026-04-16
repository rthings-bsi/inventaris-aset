@extends('layouts.app')

@section('title', 'Detail Aset - Inventaris')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }
    .glass-header {
        background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(243, 232, 255, 0.9) 100%);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }

</style>

<!-- Page Header -->
<div class="relative glass-header rounded-[2rem] p-5 sm:p-8 md:p-10 mb-6 sm:mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full animate-fade-in-up">
        <a href="{{ route('assets.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-indigo-400 hover:text-indigo-600 mb-4 transition-colors group/back">
            <i class="fas fa-arrow-left mr-2 group-hover/back:-translate-x-1 transition-transform"></i> Kembali ke Rekap
        </a>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5 sm:gap-6">
            <div class="w-full">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight flex items-center gap-2 sm:gap-3 flex-wrap">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700">Detail Informasi Aset</span> <span class="text-2xl sm:text-3xl flex-shrink-0">🔍</span>
                </h1>
                <p class="text-gray-600 mt-2 font-semibold text-sm sm:text-lg max-w-xl border-l-[3px] border-indigo-200 pl-3 sm:border-none sm:pl-0">Spesifikasi dan riwayat data untuk <span class="text-indigo-600 font-bold border-b border-indigo-200">{{ $asset->asset_name }}</span>.</p>
            </div>
            @if(auth()->check() && auth()->user()->hasPermission('asset.edit'))
            <div class="flex gap-3 w-full sm:w-auto mt-2 sm:mt-0">
                <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-6 sm:px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] hover:shadow-[0_15px_30px_-10px_rgba(79,70,229,0.7)] hover:-translate-y-1 transition-all group/btn w-full justify-center border border-transparent text-sm sm:text-base">
                    <i class="fas fa-magic mr-2 group-hover/btn:rotate-12 transition-transform"></i> Modifikasi Data
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- LEFT COLUMN -->
    <div class="lg:col-span-1 flex flex-col gap-8">

        <!-- Photo Aset -->
        <div class="glass-card rounded-[2rem] overflow-hidden group/card hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all border-t-2 border-l-2 border-white/80 animate-slide-down">
            <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-6 py-4 sm:py-5 border-b border-indigo-100/50">
                <h2 class="text-xs sm:text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-camera-retro text-indigo-400"></i> Dokumentasi Visual
                </h2>
            </div>
            <div class="p-5 sm:p-6 flex justify-center bg-white/40">
                @if($asset->photo)
                    <div class="relative w-full aspect-square rounded-2xl overflow-hidden shadow-lg border-4 border-white group-hover/card:shadow-xl transition-shadow duration-500 bg-gray-50">
                        <img src="{{ asset('storage/' . $asset->photo) }}"
                             alt="{{ $asset->asset_name }}"
                             class="w-full h-full object-cover group-hover/card:scale-105 group-hover/card:-rotate-1 transition-transform duration-700">
                    </div>
                @else
                    <div class="w-full aspect-square bg-white rounded-2xl border-2 border-dashed border-indigo-100 flex flex-col items-center justify-center group-hover/card:border-indigo-300 transition-colors duration-500">
                        <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center mb-4 shadow-inner group-hover/card:animate-bounce-sm text-indigo-300">
                            <i class="fas fa-image text-2xl"></i>
                        </div>
                        <p class="text-[11px] font-black tracking-widest uppercase text-indigo-300">Tidak Ada Dokumentasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Aset -->
        <div class="glass-card rounded-[2rem] overflow-hidden group/card hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all border-t-2 border-l-2 border-white/80 animate-slide-down" style="animation-delay: 0.1s;">
            <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-6 py-4 sm:py-5 border-b border-indigo-100/50">
                <h2 class="text-xs sm:text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-heartbeat text-indigo-400"></i> Status Operasional
                </h2>
            </div>
            <div class="p-5 sm:p-6 text-center bg-white/40">
                @if($asset->status == 'active')
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-50 text-emerald-700 font-black rounded-xl border border-emerald-100 shadow-sm group-hover/card:scale-105 transition-transform text-sm tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Siap Digunakan
                    </span>
                @elseif($asset->status == 'maintenance')
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-amber-50 text-amber-700 font-black rounded-xl border border-amber-100 shadow-sm group-hover/card:scale-105 transition-transform text-sm tracking-wide">
                        <i class="fas fa-tools text-amber-500"></i> Tahap Perawatan
                    </span>
                @elseif($asset->status == 'broken')
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-red-50 text-red-700 font-black rounded-xl border border-red-100 shadow-sm group-hover/card:scale-105 transition-transform text-sm tracking-wide">
                        <i class="fas fa-exclamation-triangle text-red-500"></i> Tidak Berfungsi
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-600 font-black rounded-xl border border-gray-200 shadow-sm group-hover/card:scale-105 transition-transform text-sm tracking-wide">
                        <i class="fas fa-archive text-gray-500"></i> Dihapuskan
                    </span>
                @endif

                <div class="mt-6 pt-5 sm:pt-6 border-t border-dashed border-indigo-100 grid grid-cols-2 gap-2 sm:gap-3 text-center">
                    <div class="bg-white/60 rounded-xl p-2.5 sm:p-3 border border-indigo-50 shadow-sm">
                        <p class="text-[9px] text-indigo-400 font-black mb-1 sm:mb-1.5 uppercase tracking-widest leading-[1.2]">Waktu Registrasi</p>
                        <p class="text-xs font-black text-indigo-700">{{ $asset->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="bg-white/60 rounded-xl p-2.5 sm:p-3 border border-purple-50 shadow-sm">
                        <p class="text-[9px] text-purple-400 font-black mb-1 sm:mb-1.5 uppercase tracking-widest leading-[1.2]">Pembaruan Akhir</p>
                        <p class="text-xs font-black text-purple-700">{{ $asset->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kalkulator Penyusutan (Depreciation) -->
        @php
            $acquisition_cost = $asset->acquisition_cost;
            $umur_ekonomis = 5; // Asumsi 5 tahun
            $nilai_sisa = $acquisition_cost * 0.10; // 10% salvage value
            
            $tanggal_beli = \Carbon\Carbon::parse($asset->acquisition_date);
            $sekarang = \Carbon\Carbon::now();
            $total_bulan_berjalan = $tanggal_beli->diffInMonths($sekarang);
            
            // Maksimal penyusutan adalah 5 tahun (60 bulan)
            $bulan_efektif = min($total_bulan_berjalan, $umur_ekonomis * 12);
            
            $penyusutan_per_bulan = ($acquisition_cost - $nilai_sisa) / ($umur_ekonomis * 12);
            $total_penyusutan = $penyusutan_per_bulan * $bulan_efektif;
            $nilai_sekarang = $acquisition_cost - $total_penyusutan;
            
            $persentase_nilai = $acquisition_cost > 0 ? ($nilai_sekarang / $acquisition_cost) * 100 : 0;
            $warna_progress = $persentase_nilai > 50 ? 'bg-emerald-400' : ($persentase_nilai > 20 ? 'bg-amber-400' : 'bg-red-400');
        @endphp

        <div class="bg-gradient-to-br from-indigo-700 via-purple-700 to-violet-800 rounded-[2rem] shadow-xl shadow-purple-500/20 overflow-hidden group/card hover:shadow-purple-500/40 transition-all animate-slide-down relative border border-white/10" style="animation-delay: 0.2s;">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl group-hover/card:scale-150 transition-transform duration-1000 animate-pulse pointer-events-none"></div>
            <div class="bg-white/10 px-5 sm:px-6 py-4 sm:py-5 border-b border-white/10 backdrop-blur-md flex flex-wrap gap-2 justify-between items-center">
                <h2 class="text-xs sm:text-sm font-black text-purple-100 uppercase tracking-widest flex items-center gap-2 drop-shadow-sm w-full sm:w-auto">
                    <i class="fas fa-chart-line text-purple-300"></i> Valuasi & Penyusutan
                </h2>
                <span class="text-[9px] font-bold text-white uppercase bg-white/20 px-2 py-1 rounded-lg backdrop-blur-sm border border-white/20">Straight-Line 5Y</span>
            </div>
            <div class="p-5 sm:p-6 relative z-10">
                <!-- Nilai Beli -->
                <div class="mb-4">
                    <p class="text-[10px] font-black tracking-widest uppercase text-purple-200 opacity-90 mb-1">Nilai Perolehan Awal</p>
                    <p class="text-xl font-bold text-white tracking-tight drop-shadow-md flex items-center">
                        <span class="text-xs opacity-80 mr-1.5">Rp</span>{{ number_format($acquisition_cost, 0, ',', '.') }}
                    </p>
                </div>
                
                <!-- Nilai Sekarang -->
                <div class="mb-6 p-4 bg-white/10 rounded-2xl border border-white/10 backdrop-blur-md">
                    <p class="text-[10px] font-black tracking-widest uppercase text-emerald-300 mb-1 flex items-center gap-1.5">
                        <i class="fas fa-coins"></i> Estimasi Nilai Saat Ini
                    </p>
                    <p class="text-3xl font-black text-white tracking-tight drop-shadow-md flex items-center">
                        <span class="text-sm opacity-80 mr-1.5">Rp</span>{{ number_format($nilai_sekarang, 0, ',', '.') }}
                    </p>
                    <div class="mt-3">
                        <div class="flex justify-between text-[9px] font-bold text-purple-200 mb-1.5 uppercase tracking-widest">
                            <span>Kesehatan Valuasi</span>
                            <span>{{ number_format($persentase_nilai, 1) }}%</span>
                        </div>
                        <div class="w-full bg-black/20 rounded-full h-2 overflow-hidden border border-white/5">
                            <div class="{{ $warna_progress }} h-2 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)] transition-all duration-1000 ease-out" style="width: {{ $persentase_nilai }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Detail Penyusutan -->
                <div class="grid grid-cols-2 gap-3 mt-4 border-t border-white/10 pt-4">
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-purple-300 mb-1">Total Susut</p>
                        <p class="text-xs font-black text-red-300">- Rp {{ number_format($total_penyusutan, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold uppercase tracking-widest text-purple-300 mb-1">Masa Pakai</p>
                        <p class="text-xs font-black text-white">{{ min($total_bulan_berjalan, 60) }} / 60 Bulan</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Trail (Log Aktivitas) -->
        <div class="glass-card rounded-[2rem] overflow-hidden group/card hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all border-t-2 border-l-2 border-white/80 animate-slide-down" style="animation-delay: 0.3s;">
            <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-6 py-4 sm:py-5 border-b border-indigo-100/50 flex flex-wrap gap-2 justify-between items-center">
                <h2 class="text-xs sm:text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-history text-indigo-400"></i> Audit Trail
                </h2>
                <span class="text-[9px] font-bold text-indigo-400 uppercase bg-white px-2 py-1 rounded-lg shadow-sm border border-indigo-50">Log Sistem</span>
            </div>
            <div class="p-5 sm:p-6 bg-white/40 max-h-64 overflow-y-auto">
                <div class="space-y-4">
                    @php
                        // Memanggil logs yang tersimpan via Activitylog
                        $activities = $asset->activities()->latest()->take(5)->get();
                    @endphp
                    
                    @forelse($activities as $activity)
                    <div class="flex gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-400 flex-shrink-0">
                            @if($activity->description == 'created')
                                <i class="fas fa-plus text-xs"></i>
                            @elseif($activity->description == 'updated')
                                <i class="fas fa-pen text-xs"></i>
                            @elseif($activity->description == 'deleted')
                                <i class="fas fa-trash text-xs text-red-400"></i>
                            @else
                                <i class="fas fa-bolt text-xs"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-800 uppercase tracking-wide">
                                Aksi: {{ $activity->description }}
                            </p>
                            <p class="text-[10px] text-gray-500 mt-0.5">
                                {{ $activity->created_at->diffForHumans() }}
                                @if($activity->causer)
                                    oleh <span class="text-indigo-600 font-bold border-b border-indigo-100">{{ $activity->causer->name }}</span>
                                @endif
                            </p>
                            @if($activity->changes()->count() > 0 && $activity->description == 'updated')
                                <div class="mt-2 bg-indigo-50/50 rounded-lg p-2 border border-indigo-100/50">
                                    <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Perubahan Atribut:</p>
                                    <ul class="text-[10px] text-gray-600 space-y-1">
                                        @foreach($activity->changes()['attributes'] as $key => $value)
                                            <li class="flex items-start gap-1">
                                                <i class="fas fa-caret-right text-indigo-300 mt-0.5"></i> 
                                                <span class="font-semibold">{{ $key }}</span> dimodifikasi
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-ghost text-gray-300 text-3xl mb-2"></i>
                        <p class="text-xs font-bold text-gray-400">Belum ada riwayat tercatat</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="lg:col-span-2 flex flex-col gap-8">

        <!-- Informasi Utama -->
        <div class="glass-card rounded-[2rem] overflow-hidden group/card hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all border-t-2 border-l-2 border-white/80 animate-slide-down" style="animation-delay: 0.1s;">
            <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-8 py-5 sm:py-6 border-b border-indigo-100/50 flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-indigo-100 flex items-center justify-center text-indigo-500 group-hover/card:animate-wiggle flex-shrink-0">
                    <i class="fas fa-list-ul text-lg sm:text-xl"></i>
                </div>
                <h2 class="text-base sm:text-lg font-black mb-0 sm:mb-2 uppercase tracking-widest leading-tight"><span class="text-indigo-800">Spesifikasi & Info Lengkap</span> 📋</h2>
            </div>
            
            <div class="p-5 sm:p-8 bg-white/40">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">

                    <!-- Kode Aset (dengan QR) -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item flex flex-col sm:flex-row justify-between items-center sm:col-span-2 gap-5 sm:gap-6">
                        <div class="w-full text-center sm:text-left">
                            <p class="text-[11px] sm:text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 flex items-center justify-center sm:justify-start gap-2">
                                <i class="fas fa-qrcode text-indigo-300"></i> Referensi Kode & Tag Fisik
                            </p>
                            <p class="text-xl sm:text-2xl font-black text-gray-800 font-mono group-hover/item:text-indigo-600 transition-colors break-words">{{ $asset->asset_code }}</p>
                            <button type="button" onclick="printQRCode()" class="mt-4 text-[12px] sm:text-[11px] font-black text-indigo-600 hover:text-indigo-800 bg-white border border-indigo-200 px-4 py-2.5 sm:py-2 rounded-xl shadow-[0_2px_10px_rgba(79,70,229,0.1)] hover:-translate-y-0.5 transition-all flex items-center justify-center sm:justify-start gap-2 uppercase tracking-wide w-full sm:w-auto mx-auto sm:mx-0">
                                <i class="fas fa-print"></i> Cetak Label QR
                            </button>
                        </div>
                        <div id="print-qr-area" class="bg-white p-4 rounded-2xl border border-indigo-100 shadow-[0_4px_15px_rgba(0,0,0,0.05)] group-hover/item:scale-105 transition-transform flex flex-col items-center justify-center gap-3">
                            {!! QrCode::size(100)->color(79, 70, 229)->generate(route('assets.show', $asset->id)) !!}
                            <span class="text-[10px] font-black uppercase tracking-widest text-indigo-500 border-t border-dashed border-indigo-100 pt-2 w-full text-center">{{ $asset->asset_code }}</span>
                        </div>
                    </div>

                    <!-- Nama Aset -->
                    <div class="p-5 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fas fa-tag text-indigo-300"></i> Identitas Aset
                        </p>
                        <p class="text-lg font-black text-gray-800 group-hover/item:text-indigo-600 transition-colors">{{ $asset->asset_name }}</p>
                    </div>

                    <!-- Category -->
                    <div class="p-5 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-layer-group text-indigo-300"></i> Category
                        </p>
                        <span class="inline-block bg-indigo-50 text-indigo-700 text-xs font-black tracking-wide uppercase px-3 py-1.5 rounded-lg border border-indigo-100">
                            {{ $asset->category ? $asset->category->category_name : '-' }}
                        </span>
                    </div>

                    <!-- Condition -->
                    <div class="p-5 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fas fa-stethoscope text-indigo-300"></i> Mutu Fisik
                        </p>
                        <p class="text-base font-bold text-gray-700 group-hover/item:text-indigo-600 transition-colors">{{ $asset->condition }}</p>
                    </div>

                    <!-- Tanggal Perolehan -->
                    <div class="p-5 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-indigo-300"></i> Tanggal Pembelian
                        </p>
                        <p class="text-base font-bold text-gray-700 group-hover/item:text-indigo-600 transition-colors">{{ $asset->acquisition_date->format('d F Y') }}</p>
                    </div>

                    <!-- Location -->
                    <div class="p-5 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item">
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-indigo-300 group-hover/item:animate-bounce-sm"></i> Penempatan Unit
                        </p>
                        <p class="text-base font-bold text-gray-700 group-hover/item:text-indigo-600 transition-colors">{{ $asset->location ? $asset->location->location_name : '-' }}</p>
                    </div>

                    <!-- Penanggung Jawab / Sistem Borroweran (Check-in/Check-out) -->
                    <div class="p-6 rounded-2xl bg-white/80 border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:bg-indigo-50/50 hover:border-indigo-100 transition-colors group/item sm:col-span-2 relative overflow-hidden">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                <i class="fas fa-exchange-alt text-indigo-300"></i> Sistem Serah Terima (Check-in/Check-out)
                            </p>
                            @if($asset->user_id)
                                <span class="bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full flex items-center gap-1.5 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Sedang Dipinjam
                                </span>
                            @else
                                @php
                                    $pendingLoan = $asset->loans()->where('status', 'pending')->first();
                                @endphp
                                @if($pendingLoan)
                                    <span class="bg-purple-100 text-purple-700 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full flex items-center gap-1.5 border border-purple-200">
                                        <i class="fas fa-hourglass-half animate-pulse"></i> Sedang Proses Request
                                    </span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full flex items-center gap-1.5 border border-emerald-200">
                                        <i class="fas fa-check-circle"></i> Tersedia di Gudang
                                    </span>
                                @endif
                            @endif
                        </div>

                        @if($asset->user_id)
                            <!-- Tampilan Sedang Dipinjam -->
                            <div class="flex flex-col sm:flex-row items-center gap-4 bg-white p-4 rounded-xl border border-amber-100 shadow-sm relative overflow-hidden">
                                <div class="absolute right-0 top-0 bottom-0 w-2 bg-amber-400"></div>
                                <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-500 border border-amber-100">
                                    <i class="fas fa-user-tie text-2xl group-hover/item:animate-wiggle"></i>
                                </div>
                                <div class="flex flex-col flex-1 w-full text-center sm:text-left">
                                    <p class="text-[10px] font-bold text-amber-500 uppercase tracking-widest mb-1">Dipinjam Oleh:</p>
                                    <p class="text-xl font-black text-gray-800 leading-tight">{{ $asset->user ? $asset->user->name : '-' }}</p>
                                    @php
                                        $activeLoan = $asset->loans()->where('status', 'borrowed')->latest()->first();
                                    @endphp
                                    @if($activeLoan)
                                        <p class="text-[10px] text-gray-500 font-bold mt-1">Sejak: {{ $activeLoan->loan_date->format('d M Y H:i') }} ({{ $activeLoan->loan_date->diffForHumans() }})</p>
                                        @if($activeLoan->notes)
                                            <p class="text-[10px] text-gray-600 mt-1 italic border-l-2 border-amber-200 pl-2">"{{ $activeLoan->notes }}"</p>
                                        @endif
                                    @endif
                                </div>
                                @if(auth()->user()->hasPermission('loan.manage'))
                                <div class="w-full sm:w-auto mt-4 sm:mt-0">
                                    <form id="kembali-form" action="{{ route('assets.kembali', $asset) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="handleKembali()" class="w-full sm:w-auto bg-amber-500 hover:bg-amber-600 text-white text-xs font-black px-6 py-3 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                                            <i class="fas fa-undo"></i> Proses Check-in
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        @else
                            @php
                                $pendingLoan = $asset->loans()->where('status', 'pending')->first();
                            @endphp
                            @if($pendingLoan)
                                <!-- Notifikasi Pending -->
                                <div class="bg-purple-50/50 border border-purple-100 rounded-xl p-5 flex items-center gap-4">
                                    <div class="w-10 h-10 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center flex-shrink-0 border border-purple-200 shadow-sm">
                                        <i class="fas fa-hourglass-half text-lg animate-pulse"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-black text-purple-800 uppercase tracking-widest mb-1 border-b border-purple-200 pb-1 w-max">Dalam Antrean Persetujuan</h4>
                                        <p class="text-[11px] font-bold text-gray-600 leading-snug mt-1">Unit ini sudah diajukan peminjamannya oleh user lain dan sedang menunggu proses persetujuan oleh Administrator.</p>
                                    </div>
                                </div>
                            @else
                                <!-- Notifikasi Tersedia -->
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5 flex items-center justify-between gap-4">
                                    <div>
                                        <h4 class="text-xs font-black text-emerald-800 uppercase tracking-widest mb-1 flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> Status Ketersediaan</h4>
                                        <p class="text-[11px] font-bold text-gray-600 leading-snug">Unit saat ini berada di gudang dan siap digunakan. Pengajuan wewenang operasional melalui menu <span class="font-black text-indigo-500">Peminjaman Aset</span>.</p>
                                    </div>
                                @if(auth()->user()->hasPermission('loan.create'))
                                    <a href="{{ route('loans.create') }}" class="flex-shrink-0 bg-white border border-emerald-200 text-emerald-600 hover:bg-emerald-50 text-[10px] font-black uppercase tracking-widest px-4 py-3 rounded-xl shadow-sm hover:shadow-md transition-all flex items-center gap-2 group">
                                        <i class="fas fa-handshake group-hover:scale-110 transition-transform"></i> Pinjam Aset
                                    </a>
                                @endif
                                </div>
                            @endif
                        @endif

                        <!-- Riwayat Borroweran Khusus -->
                        @if($asset->loans()->count() > 0)
                            <div class="mt-6 pt-4 border-t border-dashed border-gray-200">
                                <p class="text-[10px] font-black tracking-widest text-gray-400 uppercase mb-3 flex items-center gap-2">
                                    <i class="fas fa-history text-gray-300"></i> Riwayat Serah Terima
                                </p>
                                <div class="max-h-32 overflow-y-auto pr-2 space-y-2">
                                    @foreach($asset->loans()->latest()->take(5)->get() as $loan)
                                        <div class="flex justify-between items-center text-[10px] font-bold bg-white p-2 rounded-lg border border-gray-50">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full {{ $loan->status == 'borrowed' ? 'bg-amber-100 text-amber-500' : 'bg-emerald-100 text-emerald-500' }} flex items-center justify-center">
                                                    <i class="fas {{ $loan->status == 'borrowed' ? 'fa-hourglass-half' : 'fa-check' }} text-[8px]"></i>
                                                </div>
                                                <span class="text-gray-700">{{ $loan->user ? $loan->user->name : '-' }}</span>
                                            </div>
                                            <div class="text-gray-400 text-right">
                                                <span>{{ $loan->loan_date->format('d M Y') }}</span>
                                                @if($loan->status == 'returned' && $loan->return_date)
                                                    <span class="mx-1 text-gray-300">-</span>
                                                    <span>{{ $loan->return_date->format('d M Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <div class="bg-indigo-50/30 border border-indigo-100/50 rounded-2xl p-6 hover:bg-indigo-50/50 transition-colors shadow-inner">
                            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <i class="fas fa-quote-left text-indigo-300"></i> Notes Operasional Log
                            </p>
                            @if($asset->description)
                                <p class="text-gray-700 text-base font-semibold leading-relaxed">{{ $asset->description }}</p>
                            @else
                                <p class="text-gray-400 text-sm font-semibold italic flex items-center gap-2">
                                    <i class="fas fa-info-circle"></i> Tidak ada notes log historis pada sistem.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if(auth()->check() && auth()->user()->hasPermission('asset.delete'))
        <div class="bg-white/90 backdrop-blur-xl border border-red-100 rounded-[2rem] shadow-[0_8px_30px_rgba(239,68,68,0.06)] overflow-hidden group/card hover:shadow-[0_15px_40px_rgba(239,68,68,0.15)] transition-all animate-slide-down relative" style="animation-delay: 0.2s;">
            <div class="absolute top-0 left-0 w-full sm:w-1 h-1 sm:h-full bg-red-500"></div>
            <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:justify-between gap-5 sm:gap-6 bg-red-50/20 text-center sm:text-left">
                <div class="w-full">
                    <h3 class="text-lg sm:text-xl font-black text-red-600 flex flex-col sm:flex-row items-center gap-2 sm:gap-3 mb-2 tracking-wide uppercase justify-center sm:justify-start">
                        <i class="fas fa-exclamation-triangle group-hover/card:animate-pulse text-2xl sm:text-xl"></i>
                        Zona Kritis Administratif
                    </h3>
                    <p class="text-xs sm:text-[13px] font-bold text-gray-500 max-w-md mx-auto sm:mx-0 leading-relaxed">Aksi ini bersifat destruktif. Validasi ulang sebelum menghapus data identitas aset ini secara permanen dari basis data.</p>
                </div>
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="handleDeleteDetail(this)"
                            class="inline-flex items-center gap-2 text-red-600 bg-white border-2 border-red-200 px-6 py-3.5 rounded-xl font-black shadow-sm hover:bg-red-50 hover:border-red-300 hover:text-red-700 transition-all w-full sm:w-auto justify-center group/del">
                        <i class="fas fa-trash-alt group-hover/del:scale-110 transition-transform"></i> Hapus Entri
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>

<script>
    function printQRCode() {
        const qrContent = document.getElementById('print-qr-area').innerHTML;
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);
        
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Label QR</title>
                <style>
                    @page { size: A4; margin: 0; }
                    body {
                        margin: 0;
                        padding: 0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                        background-color: white;
                    }
                    .qr-container {
                        text-align: center;
                        padding: 40px;
                        border: 2px dashed #4f46e5;
                        border-radius: 20px;
                        background: white;
                    }
                    .qr-container svg {
                        width: 250px;
                        height: 250px;
                        margin-bottom: 20px;
                    }
                    .qr-container span {
                        font-size: 20px;
                        font-weight: 900;
                        color: #4f46e5;
                        text-transform: uppercase;
                        letter-spacing: 2px;
                        border-top: 2px dashed #e0e7ff;
                        padding-top: 15px;
                        margin-top: 15px;
                        display: block;
                    }
                </style>
            </head>
            <body>
                <div class="qr-container">
                    ${qrContent}
                </div>
            </body>
            </html>
        `);
        doc.close();
        
        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                setTimeout(() => { document.body.removeChild(iframe); }, 1000);
            }, 250);
        };
    }

    function handleKembali() {
        confirmAction({
            title: 'Kembalikan Aset?',
            text: 'Aset akan dikembalikan ke gudang dan status peminjaman akan diselesaikan.',
            confirmText: 'Ya, Kembalikan',
            icon: 'question'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('kembali-form').submit();
            }
        });
    }

    function handleDeleteDetail(btn) {
        confirmAction({
            title: 'Hapus Aset Permanen?',
            text: 'Data identitas aset ini akan dihapus selamanya dari sistem.',
            confirmText: 'Ya, Hapus Sekarang',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endsection
