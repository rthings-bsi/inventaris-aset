@extends('layouts.app')

@section('title', 'Direktori Aset - Inventaris')

@section('content')
<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    @keyframes gradientBlob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(20px, -30px) scale(1.05); }
        66% { transform: translate(-15px, 15px) scale(0.95); }
    }
    .blob { animation: gradientBlob 12s ease-in-out infinite alternate; }
</style>

<!-- Header Section -->
<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight flex items-center gap-3">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700">Direktori Aset</span> 📦
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl">Pusat inventarisasi dan pemantauan seluruh aset perusahaan.</p>
        </div>

    </div>
</div>

<!-- Filter & Search Container and Add Asset Action -->
<div class="mb-5 relative z-30 flex flex-row items-center justify-between gap-2 sm:gap-4 w-full">
    <!-- Filter Section (Left) -->
    <div class="relative flex items-center gap-3 flex-shrink-0" id="filter-container">
        @php
            $isFiltered = request('search') || request('category') || request('status');
        @endphp
        
        <!-- Modern Pill Filter Button -->
        <button type="button" id="filter-btn" class="group relative inline-flex items-center gap-2.5 px-4 py-2.5 rounded-xl border transition-all duration-300 {{ $isFiltered ? 'bg-gradient-to-r from-indigo-600 to-purple-600 border-indigo-500 text-white shadow-lg shadow-indigo-100' : 'bg-white border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50/30 shadow-sm' }}">
            <i class="fas fa-sliders-h text-[14px] {{ $isFiltered ? 'text-white' : 'text-indigo-600' }} group-hover:scale-110 transition-transform"></i>
            <span class="text-[13px] font-black uppercase tracking-widest">Filter</span>
            
            @if($isFiltered)
                <span class="flex h-2 w-2 rounded-full bg-white animate-pulse"></span>
            @endif
        </button>

        @if($isFiltered)
            <!-- Reset Button -->
            <a href="{{ route('assets.index') }}" class="group inline-flex items-center justify-center w-10 h-10 rounded-xl text-rose-500 bg-rose-50 hover:bg-rose-100 border border-rose-100 transition-all shadow-sm" title="Reset Filter">
                <i class="fas fa-times group-hover:rotate-90 transition-transform text-sm"></i>
            </a>
        @endif

        <!-- Refined Dropdown Menu -->
        <div id="filter-dropdown" class="hidden absolute left-0 top-full mt-3 w-[20rem] md:w-[22rem] bg-white/95 backdrop-blur-xl rounded-[2rem] shadow-[0_20px_50px_rgba(79,70,229,0.1)] border border-indigo-50/50 overflow-hidden transform transition-all opacity-0 translate-y-[-10px]">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h4 class="text-[11px] font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500 uppercase tracking-[0.2em]">Opsi Filter</h4>
                    <i class="fas fa-filter text-indigo-100 text-sm"></i>
                </div>

                <form method="GET" action="{{ route('assets.index') }}" class="space-y-5">
                    <!-- Search Input -->
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-300 group-focus-within/input:text-indigo-400 transition-colors text-sm"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari kata kunci..." autocomplete="off"
                               class="w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-100 rounded-2xl text-[14px] font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all">
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Status Select -->
                        <div class="relative">
                            <select name="status" class="w-full px-5 py-3.5 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] font-bold text-gray-600 hover:bg-white focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 transition-all appearance-none outline-none cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="broken" {{ request('status') == 'broken' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-[10px]"></i>
                        </div>

                        <!-- Category Select -->
                        <div class="relative">
                            <select name="category" class="w-full px-5 py-3.5 bg-gray-50/50 border border-gray-100 rounded-2xl text-[13px] font-bold text-gray-600 hover:bg-white focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 transition-all appearance-none outline-none cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-300 pointer-events-none text-[10px]"></i>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-indigo-100 transition-all hover:-translate-y-0.5 uppercase tracking-widest text-[11px] flex items-center justify-center gap-2">
                        Terapkan Filter <i class="fas fa-arrow-right text-[10px]"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Right Actions -->
    @if(auth()->check() && (auth()->user()->hasPermission('asset.create') || auth()->user()->hasPermission('asset.bulk-delete')))
    <div class="flex items-center gap-1.5 sm:gap-3 justify-end flex-shrink-0">
        <!-- Management Data Button (Export/Bulk) -->
        <button type="button" onclick="document.getElementById('management-modal').classList.remove('hidden')" class="group relative inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 text-[11px] sm:text-[13px] font-black text-indigo-700 transition-all duration-300 bg-white rounded-lg sm:rounded-xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] hover:shadow-[0_5px_15px_rgba(79,70,229,0.15)] hover:-translate-y-0.5 hover:bg-indigo-50 border border-gray-200 hover:border-indigo-200 whitespace-nowrap" title="Management Data">
            <i class="fas fa-database mr-1.5 sm:mr-2 group-hover:scale-110 text-indigo-500 transition-transform duration-300"></i> Management Data
        </button>

        @if(auth()->user()->hasPermission('asset.create'))
        <!-- Add Asset Button -->
        <a href="{{ route('assets.create') }}" class="group relative inline-flex items-center justify-center px-3.5 sm:px-5 py-2 sm:py-2.5 text-[11px] sm:text-[13px] font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg sm:rounded-xl shadow-[0_5px_15px_rgba(79,70,229,0.35)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.45)] hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-indigo-300 border border-transparent whitespace-nowrap">
            <i class="fas fa-plus mr-1.5 sm:mr-2 group-hover:rotate-90 transition-transform duration-300"></i> Add Asset
        </a>
        @endif
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('filter-btn');
        const dropdown = document.getElementById('filter-dropdown');
        const container = document.getElementById('filter-container');

        if(btn && dropdown && container) {
            btn.addEventListener('click', function() {
                if(dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('hidden');
                    setTimeout(() => {
                        dropdown.classList.remove('opacity-0', 'translate-y-[-10px]');
                        dropdown.classList.add('opacity-100', 'translate-y-0');
                    }, 10);
                } else {
                    dropdown.classList.remove('opacity-100', 'translate-y-0');
                    dropdown.classList.add('opacity-0', 'translate-y-[-10px]');
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 300);
                }
            });

            document.addEventListener('click', function(e) {
                if(!container.contains(e.target) && !dropdown.classList.contains('hidden')) {
                    dropdown.classList.remove('opacity-100', 'translate-y-0');
                    dropdown.classList.add('opacity-0', 'translate-y-[-10px]');
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 300);
                }
            });
        }
    });
</script>

<!-- Assets Table Card -->
<div class="glass-card rounded-[2rem] relative border-t-2 border-l-2 border-white/80 mb-8 shadow-sm">
    <!-- Blobs (overflow hidden container) -->
    <div class="absolute inset-0 overflow-hidden rounded-[2rem] pointer-events-none -z-10">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100 rounded-full filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-100 rounded-full filter blur-3xl opacity-30 animate-float"></div>
    </div>

    @if($assets->count() > 0)
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto px-4 pt-1 pb-4 md:px-6 md:pt-2 md:pb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    @if(auth()->check() && auth()->user()->hasPermission('asset.bulk-delete'))
                    <th class="px-6 py-4 border-b-2 border-dashed border-indigo-100" style="width: 40px;">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer transition-all hover:scale-110">
                    </th>
                    @endif
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Informasi Aset</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Category</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Penempatan</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Valuasi</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100 text-right">Otorisasi</th>
                </tr>
            </thead>
            <tbody class="space-y-2">
                @foreach($assets as $asset)
                <tr class="group hover:bg-white/80 transition-all duration-300 rounded-2xl border-b border-gray-100 hover:shadow-[0_2px_15px_rgba(79,70,229,0.05)] relative">
                    @if(auth()->check() && auth()->user()->hasPermission('asset.bulk-delete'))
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" class="asset-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer transition-all hover:scale-110">
                    </td>
                    @endif
                    <!-- Kolom Gabungan Info Aset -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4 group-hover:translate-x-1 transition-transform duration-300">
                            <!-- Photo Aset -->
                            @if($asset->photo)
                                <div class="w-14 h-14 rounded-2xl border-2 border-white overflow-hidden shadow-sm group-hover:shadow-md transition-all flex-shrink-0">
                                    <img src="{{ asset('storage/' . $asset->photo) }}" alt="{{ $asset->asset_name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                            @else
                                <div class="w-14 h-14 rounded-2xl border-2 border-dashed border-indigo-100 bg-indigo-50/50 flex items-center justify-center text-indigo-300 shadow-sm transition-all flex-shrink-0">
                                    <i class="fas fa-image text-xl"></i>
                                </div>
                            @endif
                            <!-- Detail Nama & Kode -->
                            <div class="flex flex-col">
                                <h4 class="text-[15px] font-black text-gray-800">{{ $asset->asset_name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="font-mono text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100/50">{{ $asset->asset_code }}</span>
                                    <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500 uppercase tracking-wide">{{ $asset->condition }}</span>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Category -->
                    <td class="px-6 py-4 whitespace-nowrap">
                         <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-[11px] font-black bg-gray-50 text-gray-600 transition-all border border-gray-100 uppercase tracking-wide">
                            {{ $asset->category ? $asset->category->category_name : '-' }}
                        </span>
                    </td>

                    <!-- Location -->
                    <td class="px-6 py-4 whitespace-nowrap text-[13px] font-bold text-gray-600 flex items-center mt-2.5">
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-indigo-400 group-hover:animate-bounce-sm"></i> {{ $asset->location ? $asset->location->location_name : '-' }}
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($asset->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm uppercase tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                            </span>
                        @elseif($asset->status == 'maintenance')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black bg-amber-50 text-amber-700 border border-amber-100 shadow-sm uppercase tracking-wide">
                                <i class="fas fa-tools text-amber-500"></i> Servis
                            </span>
                        @elseif($asset->status == 'broken')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black bg-red-50 text-red-700 border border-red-100 shadow-sm uppercase tracking-wide">
                                <i class="fas fa-exclamation-triangle text-red-500"></i> Rusak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black bg-gray-100 text-gray-600 border border-gray-200 shadow-sm uppercase tracking-wide">
                                <i class="fas fa-archive text-gray-500"></i> Dihapuskan
                            </span>
                        @endif
                    </td>

                    <!-- Nilai -->
                    <td class="px-6 py-4 whitespace-nowrap text-[14px] font-black text-gray-800 font-tabular">
                        <span class="text-xs text-gray-400 font-bold mr-1">Rp</span>{{ number_format($asset->acquisition_cost, 0, ',', '.') }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2 transition-all duration-300">
                            <a href="{{ route('assets.show', $asset) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-indigo-500 hover:bg-indigo-500 hover:text-white hover:border-indigo-500 transition-all hover:scale-110 hover:-rotate-6 shadow-sm" title="Rincian Data">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                            @if(auth()->check() && auth()->user()->hasPermission('asset.edit'))
                            <a href="{{ route('assets.edit', $asset) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-purple-500 hover:bg-purple-500 hover:text-white hover:border-purple-500 transition-all hover:scale-110 hover:rotate-6 shadow-sm" title="Modifikasi Entri">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('asset.delete'))
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="handleDelete(this)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all hover:scale-110 hover:rotate-12 shadow-sm" title="Hapus Permanen">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4 px-4 pt-4 pb-4">
        @foreach($assets as $asset)
        <div class="bg-white rounded-[1.25rem] p-4 border border-indigo-50 shadow-[0_5px_15px_-5px_rgba(79,70,229,0.08)] flex flex-col gap-3 relative overflow-hidden group">
            @if(auth()->check() && auth()->user()->hasPermission('asset.bulk-delete'))
            <div class="absolute top-4 left-4 z-10">
                <input type="checkbox" value="{{ $asset->id }}" class="asset-checkbox w-5 h-5 text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 cursor-pointer shadow-sm">
            </div>
            @endif
            <!-- Header: Photo & Name & Action -->
            <div class="flex gap-3 items-start justify-between border-b border-gray-100 pb-3 {{ auth()->check() && auth()->user()->hasPermission('asset.bulk-delete') ? 'pl-8' : '' }}">
                <div class="flex items-center gap-3">
                    @if($asset->photo)
                        <div class="w-12 h-12 rounded-xl border-2 border-white overflow-hidden shadow-sm flex-shrink-0">
                            <img src="{{ asset('storage/' . $asset->photo) }}" alt="{{ $asset->asset_name }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl border border-dashed border-indigo-100 bg-indigo-50/50 flex items-center justify-center text-indigo-300 flex-shrink-0">
                            <i class="fas fa-image text-lg"></i>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-[14px] font-black text-gray-800 leading-tight">{{ $asset->asset_name }}</h4>
                        <span class="font-mono text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100/50 mt-1 inline-block">{{ $asset->asset_code }}</span>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-1.5 flex-shrink-0">
                    <a href="{{ route('assets.show', $asset) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-eye text-xs"></i>
                    </a>
                    @if(auth()->check() && auth()->user()->hasPermission('asset.edit'))
                    <a href="{{ route('assets.edit', $asset) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-50 text-purple-500 hover:bg-purple-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Body: Category, Location, Status, Nilai -->
            <div class="grid grid-cols-2 gap-y-3 gap-x-2 text-[12px] pt-1">
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Category</span>
                    <span class="font-bold text-gray-700">{{ $asset->category ? $asset->category->category_name : '-' }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Status</span>
                    <div>
                        @if($asset->status == 'active')
                            <span class="text-emerald-600 font-black text-[11px]"><i class="fas fa-check-circle mr-1"></i>Aktif</span>
                        @elseif($asset->status == 'maintenance')
                            <span class="text-amber-600 font-black text-[11px]"><i class="fas fa-tools mr-1"></i>Servis</span>
                        @elseif($asset->status == 'broken')
                            <span class="text-red-600 font-black text-[11px]"><i class="fas fa-exclamation-triangle mr-1"></i>Rusak</span>
                        @else
                            <span class="text-gray-600 font-black text-[11px]"><i class="fas fa-archive mr-1"></i>Dihapuskan</span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Penempatan</span>
                    <span class="font-bold text-gray-700 flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-indigo-400"></i> {{ \Illuminate\Pagination\LengthAwarePaginator::class ? Str::limit($asset->location ? $asset->location->location_name : '-', 15) : ($asset->location ? $asset->location->location_name : '-') }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5">Valuasi</span>
                    <span class="font-black text-indigo-700">Rp{{ number_format($asset->acquisition_cost, 0, ',', '.') }}</span>
                </div>
            </div>
            
            @if(auth()->check() && auth()->user()->hasPermission('asset.delete'))
            <div class="mt-2 text-right border-t border-gray-100 pt-3">
                <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="handleDelete(this)" class="text-[10px] uppercase tracking-wider font-bold text-red-400 hover:text-red-600 flex items-center justify-end w-full gap-1.5 transition-colors">
                        <i class="fas fa-trash-alt"></i> Hapus Permanen
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-indigo-50 bg-white/50 backdrop-blur-sm rounded-b-[2rem]">
        {{ $assets->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="flex flex-col items-center justify-center py-24 text-center relative z-10 px-4">
        <div class="relative w-40 h-40 mb-8">
            <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-30"></div>
            <div class="relative w-full h-full bg-gradient-to-tr from-indigo-50 to-purple-50 rounded-[2.5rem] flex items-center justify-center animate-float shadow-xl shadow-indigo-100/50 border-2 border-white">
                <i class="fas fa-folder-open text-[4rem] text-indigo-300"></i>
            </div>
            <!-- Bouncing dots -->
            <div class="absolute -top-4 -right-2 text-2xl text-purple-300 animate-bounce">✨</div>
        </div>
        <h3 class="text-2xl font-black text-indigo-900 mb-3 tracking-tight">Data Tidak Ditemukan</h3>
        <p class="text-[15px] font-semibold text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">Sistem tidak menemukan arsip aset yang sesuai dengan parameter pencarian Anda.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center w-full sm:w-auto">
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('assets.index') }}" class="px-6 py-3.5 bg-white border-2 border-indigo-100 text-indigo-600 rounded-xl font-black hover:bg-indigo-50 hover:border-indigo-200 transition-all hover:-translate-y-1 shadow-sm text-center">
                    Reset Parameter
                </a>
            @endif
            <a href="{{ route('assets.create') }}" class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black shadow-[0_10px_20px_-10px_rgba(79,70,229,0.5)] hover:shadow-[0_15px_30px_-10px_rgba(79,70,229,0.7)] hover:-translate-y-1 transition-all group flex items-center justify-center border border-transparent">
                <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i> Registrasi Aset Baru
            </a>
        </div>
    </div>
    @endif
</div>

<!-- Management Data Modal -->
<div id="management-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-indigo-50 relative max-h-[90vh] flex flex-col">
        <!-- Header -->
        <div class="px-5 py-4 sm:px-8 sm:py-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50/50 to-purple-50/50 flex justify-between items-center relative shrink-0">
            <h3 class="text-lg sm:text-xl font-black text-gray-800 flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-indigo-100 flex items-center justify-center text-indigo-600 shadow-inner">
                    <i class="fas fa-database text-sm sm:text-base"></i>
                </div>
                Management Data
            </h3>
            <button type="button" onclick="document.getElementById('management-modal').classList.add('hidden')" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-lg flex items-center justify-center transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="px-5 py-5 sm:px-8 sm:py-6 space-y-6 sm:space-y-8 overflow-y-auto">
            <!-- Ekspor Section -->
            <div>
                <h4 class="text-[13px] font-black text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-cloud-download-alt"></i> Ekspor Laporan
                </h4>
                
                <form action="{{ route('assets.export.excel') }}" id="form-export" method="GET" class="space-y-4">
                    <!-- Keep existing filters -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="category" value="{{ request('category') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5">Dari Tanggal (Opsional)</label>
                            <input type="date" name="start_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[14px] font-medium text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[12px] font-bold text-gray-600 mb-1.5">Sampai Tanggal (Opsional)</label>
                            <input type="date" name="end_date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[14px] font-medium text-gray-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-2">
                        <button type="button" onclick="submitExport('excel')" class="flex-1 bg-emerald-50 text-emerald-700 font-bold px-4 py-3 rounded-xl hover:bg-emerald-100 transition-all text-[13px] flex items-center justify-center gap-2 border border-emerald-200 shadow-sm hover:-translate-y-0.5">
                            <i class="fas fa-file-excel"></i> Download Excel
                        </button>
                        <button type="button" onclick="submitExport('pdf')" class="flex-1 bg-rose-50 text-rose-700 font-bold px-4 py-3 rounded-xl hover:bg-rose-100 transition-all text-[13px] flex items-center justify-center gap-2 border border-rose-200 shadow-sm hover:-translate-y-0.5">
                            <i class="fas fa-file-pdf"></i> Download PDF
                        </button>
                    </div>
                </form>
            </div>

            <div class="border-t border-gray-100 border-dashed"></div>

            <!-- Bulk Actions Section -->
            @if(auth()->check() && auth()->user()->hasPermission('asset.bulk-delete'))
            <div>
                <h4 class="text-[13px] font-black text-rose-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-trash-alt"></i> Tindakan Massal (Bulk)
                </h4>
                
                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-4 mb-4">
                    <p class="text-[12px] font-bold text-rose-700 leading-snug">
                        <i class="fas fa-exclamation-circle mr-1"></i> 
                        Hapus data yang dipilih di tabel. Tindakan ini permanen dan tidak dapat dibatalkan.
                    </p>
                </div>

                <div id="selection-status" class="mb-4 hidden text-[13px] font-black text-gray-700 flex items-center gap-2">
                    <span id="selected-count" class="bg-rose-100 text-rose-700 px-3 py-1 rounded-lg">0</span> aset terpilih
                </div>

                <form id="bulk-delete-form" action="{{ route('assets.bulkDestroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div id="bulk-ids-container"></div>
                    <button type="submit" id="bulk-delete-btn" disabled class="w-full bg-white border-2 border-rose-200 text-rose-400 font-black px-5 py-3.5 rounded-xl transition-all text-[13px] flex items-center justify-center gap-2 cursor-not-allowed grayscale">
                        <i class="fas fa-trash-alt"></i> Hapus Aset Terpilih
                    </button>
                </form>
            </div>

            <div class="border-t border-gray-100 border-dashed"></div>
            @endif

            <!-- Impor Section -->
            <div>
                <h4 class="text-[13px] font-black text-indigo-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fas fa-cloud-upload-alt"></i> Impor Data Excel
                </h4>
                
                <form action="{{ route('assets.import.excel') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    @csrf
                    <div class="flex-1 relative">
                        <input type="file" name="file_excel" id="file_excel" accept=".xlsx,.xls,.csv" class="hidden" onchange="document.getElementById('file-name').innerText = this.files[0] ? this.files[0].name : 'Pilih file Excel...'">
                        <label for="file_excel" class="cursor-pointer w-full flex items-center justify-between px-4 py-3 text-[14px] text-gray-500 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-all">
                            <span id="file-name" class="truncate max-w-[150px] sm:max-w-[200px] font-medium">Pilih file Excel...</span>
                            <span class="bg-gray-200 text-gray-600 font-bold px-3 py-1 rounded-lg text-[11px] uppercase tracking-wider ml-2">Browse</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-indigo-500 to-purple-500 text-white font-black px-5 py-3 rounded-xl hover:shadow-md transition-all text-[13px] shadow-sm hover:-translate-y-0.5 flex justify-center items-center whitespace-nowrap border border-transparent">
                        <i class="fas fa-upload mr-1.5"></i> Proses
                    </button>
                </form>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mt-3 sm:mt-2.5 gap-2 sm:gap-0">
                    <p class="text-[11px] font-medium text-gray-400 flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-indigo-300"></i> Pastikan format kolom sesuai template.
                    </p>
                    <a href="{{ route('assets.import.template') }}" class="no-loader text-[11px] font-bold text-indigo-500 hover:text-indigo-700 hover:underline flex items-center gap-1">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function handleDelete(btn) {
        confirmAction({
            title: 'Hapus Aset?',
            text: 'Data ini akan dihapus secara permanen dari basis data.',
            confirmText: 'Ya, Hapus',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Sedang Menghapus',
                    text: 'Mohon tunggu, data sedang diproses...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                btn.closest('form').submit();
            }
        });
    }

    // Bulk Delete Logic
    document.addEventListener('DOMContentLoaded', function() {
        // ... selection logic same as before ...
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.asset-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const bulkIdsContainer = document.getElementById('bulk-ids-container');
        const selectedCountEl = document.getElementById('selected-count');
        const selectionStatus = document.getElementById('selection-status');

        function updateBulkButton() {
            const checkedCount = document.querySelectorAll('.asset-checkbox:checked').length;
            
            if (bulkDeleteBtn) {
                if (checkedCount > 0) {
                    bulkDeleteBtn.disabled = false;
                    bulkDeleteBtn.classList.remove('text-rose-400', 'border-rose-200', 'cursor-not-allowed', 'grayscale');
                    bulkDeleteBtn.classList.add('bg-rose-600', 'text-white', 'border-rose-600', 'shadow-lg', 'shadow-rose-200', 'hover:-translate-y-1', 'active:scale-95');
                    selectionStatus.classList.remove('hidden');
                    selectedCountEl.innerText = checkedCount;
                } else {
                    bulkDeleteBtn.disabled = true;
                    bulkDeleteBtn.classList.add('text-rose-400', 'border-rose-200', 'cursor-not-allowed', 'grayscale');
                    bulkDeleteBtn.classList.remove('bg-rose-600', 'text-white', 'border-rose-600', 'shadow-lg', 'shadow-rose-200', 'hover:-translate-y-1', 'active:scale-95');
                    selectionStatus.classList.add('hidden');
                }
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (selectAll && !this.checked) selectAll.checked = false;
                if (selectAll && document.querySelectorAll('.asset-checkbox:checked').length === checkboxes.length) selectAll.checked = true;
                updateBulkButton();
            });
        });

        if (bulkDeleteForm) {
            bulkDeleteForm.addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.asset-checkbox:checked');
                if (checked.length === 0) {
                    e.preventDefault();
                    return;
                }
                
                e.preventDefault();
                confirmAction({
                    title: 'Hapus Massal?',
                    text: 'Seluruh aset terpilih (' + checked.length + ') akan dihapus secara permanen.',
                    confirmText: 'Ya, Hapus Semua',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Hapus Massal',
                            text: 'Sistem sedang menghapus data terpilih...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        bulkIdsContainer.innerHTML = '';
                        checked.forEach(cb => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = cb.value;
                            bulkIdsContainer.appendChild(input);
                        });
                        bulkDeleteForm.submit();
                    }
                });
            });
        }
    });

    function submitExport(type) {
        var form = document.getElementById('form-export');
        var btnPdf = document.querySelector('button[onclick="submitExport(\'pdf\')"]');
        var btnExcel = document.querySelector('button[onclick="submitExport(\'excel\')"]');
        
        // Visual Feedback
        Swal.fire({
            title: 'Menyiapkan Laporan',
            text: 'Mohon tunggu sebentar, sistem sedang mengolah data...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            timer: 2000,
            timerProgressBar: true,
        });

        if (type === 'pdf') {
            form.action = "{{ route('assets.export.pdf') }}";
            form.target = "_blank";
        } else {
            form.action = "{{ route('assets.export.excel') }}";
            form.target = "_self";
        }
        form.submit();
    }
</script>

@endsection
