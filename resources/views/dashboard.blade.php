@extends('layouts.app')

@section('title', 'Dashboard - Inventaris Aset')

@section('content')
<x-page-header 
    title="Selamat Datang, {{ auth()->user()->name }}" 
    subtitle="Ringkasan performa dan akses sistem untuk role {{ auth()->user()->role_display_name }}." 
    emoji="👋"
>
    <x-slot name="actions">
        <div class="bg-white/90 backdrop-blur-md px-6 py-4 rounded-2xl shadow-sm text-sm font-black text-indigo-700 flex items-center gap-3 transform group-hover:scale-105 transition-transform duration-500 cursor-default border-2 border-indigo-50">
            <div class="w-10 h-10 flex items-center justify-center bg-indigo-50 rounded-xl shadow-inner">
                <i class="far fa-calendar-alt text-indigo-500 text-lg group-hover:animate-bounce-sm"></i>
            </div>
            {{ now()->format('d F Y') }}
        </div>
    </x-slot>
</x-page-header>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-10">
    <!-- Total Aset -->
    <a href="{{ route('assets.index') }}" class="block glass-card rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.2)] transition-all duration-500 hover:-translate-y-2 group border-2 border-transparent hover:border-indigo-100 relative overflow-hidden cursor-pointer">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50/50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <i class="fas fa-boxes text-2xl group-hover:animate-bounce-sm"></i>
                </div>
                <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 text-xs font-black rounded-xl border border-indigo-100 shadow-sm">Total</span>
            </div>
            <div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight group-hover:text-indigo-700 transition-colors font-tabular">{{ $stats['total_aset'] }} <span class="text-lg text-gray-400 font-bold ml-1">Unit</span></h3>
                <p class="text-[13px] font-bold text-gray-500 mt-2">Seluruh Aset 🏷️</p>
            </div>
        </div>
    </a>

    <!-- Aset Aktif -->
    <a href="{{ route('assets.index', ['status' => 'active']) }}" class="block glass-card rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(16,185,129,0.2)] transition-all duration-500 hover:-translate-y-2 group border-2 border-transparent hover:border-emerald-100 relative overflow-hidden cursor-pointer">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-emerald-50/50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <i class="fas fa-check-circle text-2xl group-hover:animate-bounce-sm"></i>
                </div>
                <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-xs font-black rounded-xl border border-emerald-100 shadow-sm">Aktif</span>
            </div>
            <div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight group-hover:text-emerald-600 transition-colors">{{ $stats['aset_aktif'] }} <span class="text-lg text-gray-400 font-bold ml-1">Unit</span></h3>
                <p class="text-[13px] font-bold text-gray-500 mt-2">Condition Prima ✨</p>
            </div>
        </div>
    </a>

    <!-- Maintenance -->
    <a href="{{ route('assets.index', ['status' => 'maintenance']) }}" class="block glass-card rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(245,158,11,0.2)] transition-all duration-500 hover:-translate-y-2 group border-2 border-transparent hover:border-amber-100 relative overflow-hidden cursor-pointer">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-amber-50/50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-amber-100 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <i class="fas fa-tools text-2xl group-hover:animate-spin"></i>
                </div>
                <span class="px-3 py-1.5 bg-amber-50 text-amber-600 text-xs font-black rounded-xl border border-amber-100 shadow-sm">MT</span>
            </div>
            <div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight group-hover:text-amber-600 transition-colors">{{ $stats['aset_maintenance'] }} <span class="text-lg text-gray-400 font-bold ml-1">Unit</span></h3>
                <p class="text-[13px] font-bold text-gray-500 mt-2">Dalam Perawatan 🔧</p>
            </div>
        </div>
    </a>

    <!-- Rusak -->
    <a href="{{ route('assets.index', ['status' => 'broken']) }}" class="block glass-card rounded-3xl p-6 hover:shadow-[0_20px_40px_-15px_rgba(239,68,68,0.2)] transition-all duration-500 hover:-translate-y-2 group border-2 border-transparent hover:border-red-100 relative overflow-hidden cursor-pointer">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-50/50 rounded-full group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-start mb-6">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-red-100 text-red-600 group-hover:bg-red-500 group-hover:text-white transition-colors duration-500 shadow-sm">
                    <i class="fas fa-heart-broken text-2xl group-hover:animate-pulse"></i>
                </div>
                <span class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-black rounded-xl border border-red-100 shadow-sm">Rusak</span>
            </div>
            <div>
                <h3 class="text-4xl font-black text-gray-800 tracking-tight group-hover:text-red-500 transition-colors">{{ $stats['aset_rusak'] }} <span class="text-lg text-gray-400 font-bold ml-1">Unit</span></h3>
                <p class="text-[13px] font-bold text-gray-500 mt-2">Perlu Revisi ⚠️</p>
            </div>
        </div>
    </a>

    <!-- Total Nilai -->
    <a href="{{ route('assets.index') }}" class="block bg-gradient-to-br from-indigo-600 via-purple-600 to-violet-700 rounded-3xl p-8 md:p-6 shadow-2xl shadow-purple-500/20 hover:shadow-purple-500/40 transition-all duration-500 hover:-translate-y-2 group relative overflow-hidden sm:col-span-2 md:col-span-2 lg:col-span-1 xl:col-span-1 border border-white/10 cursor-pointer">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000 ease-in-out animate-pulse"></div>
        <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-pink-400 opacity-20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md text-white border border-white/20 shadow-inner group-hover:bg-white/20 transition-colors">
                    <i class="fas fa-wallet text-2xl group-hover:animate-wiggle"></i>
                </div>
                <span class="px-4 py-2 bg-white/10 backdrop-blur-md text-white border border-white/10 text-xs font-black rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.1)]">Valuasi 💎</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold tracking-widest uppercase text-purple-200 mb-2 opacity-80">Total Nilai Kapital</p>
                <h3 class="text-xl lg:text-3xl font-black text-white tracking-tight drop-shadow-sm break-all font-tabular" title="Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}">
                    <span class="text-base opacity-80 font-bold mr-1">Rp</span>{{ number_format($stats['total_nilai'], 0, ',', '.') }}
                </h3>
            </div>
        </div>
    </a>
</div>

<!-- Recent Assets -->
<div class="glass-card rounded-[2rem] shadow-sm overflow-hidden relative border-t-2 border-l-2 border-white/80 group/table -mt-2">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-50/40 rounded-full filter blur-[4rem] opacity-50 -z-10 animate-blob pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-50/40 rounded-full filter blur-[4rem] opacity-50 -z-10 animate-blob pointer-events-none" style="animation-delay: 3s;"></div>
    
    <!-- Table Header -->
    <div class="px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white/40">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 flex items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-50 to-purple-50 text-indigo-500 shadow-sm border border-white group-hover/table:scale-105 transition-transform">
                <i class="fas fa-clock text-2xl animate-spin-slow" style="animation: spin 8s linear infinite;"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">Inventaris Terbaru 📋</h2>
                <p class="text-[13px] font-bold text-gray-500 mt-1">Daftar aset yang baru saja dicatat hari ini.</p>
            </div>
        </div>
        <a href="{{ route('assets.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-indigo-100 text-indigo-600 rounded-xl text-sm font-black hover:bg-indigo-50 hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-50 transition-all duration-300 hover:scale-105 hover:-translate-y-1 group/btn w-full sm:w-auto">
            Buka Semua Data <i class="fas fa-arrow-right ml-2 text-xs group-hover/btn:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <!-- Table Content -->
    @if($recent_assets->count() > 0)
    <div class="overflow-x-auto p-4 sm:p-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 whitespace-nowrap">Aset & Identitas</th>
                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 whitespace-nowrap">Category</th>
                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 whitespace-nowrap">Location Departemen</th>
                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 whitespace-nowrap">Status</th>
                    <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 whitespace-nowrap text-right">Nilai Kapital</th>
                </tr>
            </thead>
            <tbody class="space-y-2">
                @foreach($recent_assets as $asset)
                <tr class="group/row hover:bg-white hover:shadow-[0_4px_15px_rgba(0,0,0,0.03)] transition-all duration-300 rounded-2xl border-b border-gray-50 last:border-0 relative">
                    <!-- Gabungan Photo/Ikon & Nama -->
                    <td class="px-6 py-4 whitespace-nowrap xl:w-1/3">
                         <div class="flex items-center gap-4 group-hover/row:translate-x-2 transition-transform duration-300">
                             <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-50 to-purple-50 text-indigo-500 flex items-center justify-center shadow-sm border border-white group-hover/row:scale-110 group-hover/row:rotate-6 transition-all duration-300 flex-shrink-0">
                                 @if($asset->photo)
                                     <img src="{{ asset('storage/' . $asset->photo) }}" alt="Photo" class="w-full h-full object-cover rounded-xl border border-white">
                                 @else
                                     <i class="fas fa-cube text-lg group-hover/row:animate-bounce-sm"></i>
                                 @endif
                             </div>
                             <div class="flex flex-col truncate">
                                 <span class="text-sm font-black text-gray-800 truncate">{{ $asset->asset_name }}</span>
                                 <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md border border-gray-200">{{ $asset->asset_code }}</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-50 text-gray-600 font-medium">
                                        <i class="fas fa-map-marker-alt text-indigo-400 mr-1.5"></i>
                                        {{ $asset->location ? $asset->location->location_name : '-' }}
                                    </span>
                                 </div>
                             </div>
                         </div>
                    </td>
                    <!-- Category -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 font-bold border border-indigo-100">
                            <i class="fas fa-tag mr-1.5 opacity-50"></i> {{ $asset->category ? $asset->category->category_name : '-' }}
                        </span>
                    </td>
                    <!-- Location -->
                    <td class="px-6 py-4 whitespace-nowrap text-[13px] font-bold text-gray-600">
                        <div class="flex items-center opacity-80 group-hover/row:opacity-100 group-hover/row:text-indigo-600 transition-colors">
                            <i class="fas fa-building text-indigo-300 mr-2 group-hover/row:animate-bounce-sm"></i> 
                            {{ $asset->location ? $asset->location->location_name : '-' }}
                        </div>
                    </td>
                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($asset->status == 'active')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm group-hover/row:scale-105 transition-transform">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span> Aktif
                            </span>
                        @elseif($asset->status == 'maintenance')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-100 shadow-sm group-hover/row:scale-105 transition-transform">
                                <i class="fas fa-wrench mr-1.5 opacity-70"></i> Servis
                            </span>
                        @elseif($asset->status == 'broken')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider bg-red-50 text-red-700 border border-red-100 shadow-sm group-hover/row:scale-105 transition-transform">
                                <i class="fas fa-exclamation-triangle mr-1.5 opacity-70"></i> Rusak
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-[11px] font-black uppercase tracking-wider bg-gray-100 text-gray-600 border border-gray-200 shadow-sm group-hover/row:scale-105 transition-transform">
                                <i class="fas fa-trash-alt mr-1.5 opacity-70"></i> Dihapus
                            </span>
                        @endif
                    </td>
                    <!-- Nilai -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-800 text-right group-hover/row:text-indigo-600 transition-colors font-tabular">
                        Rp {{ number_format($asset->acquisition_cost, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-20 px-4 text-center relative overflow-hidden bg-white/40 border-t border-white/50">
        <div class="relative w-32 h-32 mb-6 group/empty cursor-default">
            <div class="absolute inset-0 bg-indigo-200 rounded-full animate-ping opacity-20"></div>
            <div class="relative w-full h-full bg-gradient-to-tr from-white to-indigo-50 rounded-full flex items-center justify-center animate-float shadow-xl border-4 border-white shadow-indigo-100/50">
                <i class="fas fa-folder-open text-5xl text-indigo-300 group-hover/empty:rotate-12 transition-transform duration-500"></i>
            </div>
            <div class="absolute -top-2 -right-2 text-2xl animate-bounce" style="animation-delay: 0.5s;">✨</div>
        </div>
        <h3 class="text-2xl font-black text-gray-800 mb-2 tracking-tight">Belum Ada Data 🏜️</h3>
        <p class="text-sm font-bold text-gray-500 max-w-sm leading-relaxed mb-6">Sistem belum mencatat adanya aset baru yang masuk. Silakan tambahkan entri pertama Anda untuk memulai.</p>
        <a href="{{ route('assets.create') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white rounded-2xl text-sm font-black hover:shadow-lg hover:shadow-indigo-300 transition-all duration-300 hover:scale-105 hover:-translate-y-1 group/btn">
            <i class="fas fa-plus mr-2"></i> Buat Entri Baru
        </a>
    </div>
    @endif
</div>

<!-- Analytics Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-10 mb-8">
    <!-- Category Chart -->
    <div class="glass-card rounded-[2rem] p-8 border-t-2 border-l-2 border-white/80 hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all animate-slide-down">
        <h3 class="text-sm font-black text-indigo-800 uppercase tracking-widest mb-6 flex items-center gap-2">
            <i class="fas fa-chart-pie text-indigo-400"></i> Distribusi Category
        </h3>
        <div class="relative h-64 w-full flex items-center justify-center">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <!-- Condition Chart -->
    <div class="glass-card rounded-[2rem] p-8 border-t-2 border-l-2 border-white/80 hover:shadow-[0_15px_40px_rgba(79,70,229,0.1)] transition-all animate-slide-down" style="animation-delay: 0.1s;">
        <h3 class="text-sm font-black text-indigo-800 uppercase tracking-widest mb-6 flex items-center gap-2">
            <i class="fas fa-chart-bar text-indigo-400"></i> Mutu Fisik Keseluruhan
        </h3>
        <div class="relative h-64 w-full flex items-center justify-center">
            <canvas id="conditionChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' },
                        color: '#6b7280'
                    }
                }
            }
        };

        // Category Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chart_data['category']['labels']) !!},
                datasets: [{
                    data: {!! json_encode($chart_data['category']['data']) !!},
                    backgroundColor: [
                        '#6366f1', '#a855f7', '#ec4899', '#f43f5e', '#facc15', '#10b981', '#3b82f6'
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                ...chartOptions,
                cutout: '75%',
                plugins: {
                    ...chartOptions.plugins,
                    tooltip: {
                        backgroundColor: 'rgba(79, 70, 229, 0.95)',
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                }
            }
        });

        // Condition Chart
        const konCtx = document.getElementById('conditionChart').getContext('2d');
        new Chart(konCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_data['condition']['labels']) !!},
                datasets: [{
                    label: 'Jumlah Aset',
                    data: {!! json_encode($chart_data['condition']['data']) !!},
                    backgroundColor: '#8b5cf6',
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(139, 92, 246, 0.95)',
                        titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Plus Jakarta Sans', sans-serif" },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af', font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' } },
                        grid: { color: 'rgba(243, 244, 246, 1)', drawBorder: false }
                    },
                    x: {
                        ticks: { color: '#6b7280', font: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' } },
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    });
</script>
@endsection