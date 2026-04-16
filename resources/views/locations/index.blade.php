@extends('layouts.app')

@section('title', 'Data Location - Inventaris')

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

<!-- Header Section -->
<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight flex items-center gap-3">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700">Data Location</span> 📍
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl">Kelola location penempatan aset Anda di sini.</p>
        </div>
    </div>
</div>

<!-- Search -->
<div class="mb-5 relative z-30 flex items-center justify-between gap-4 w-full">
    <form method="GET" action="{{ route('locations.index') }}" class="w-full max-w-sm">
        <div class="relative flex items-center px-4 py-2 bg-white rounded-2xl shadow-sm border border-gray-100 focus-within:border-indigo-300 focus-within:ring-2 focus-within:ring-indigo-50 transition-all">
            <i class="fas fa-search text-gray-400 text-sm"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari location..." class="w-full pl-3 pr-2 border-none focus:ring-0 text-[14px] bg-transparent outline-none text-gray-800 font-medium">
            @if(request('search'))
                <a href="{{ route('locations.index') }}" class="text-gray-400 hover:text-red-500 transition-colors"><i class="fas fa-times text-sm"></i></a>
            @endif
        </div>
    </form>
    
    <div class="flex items-center justify-end flex-shrink-0">
        <a href="{{ route('locations.create') }}" class="group relative inline-flex items-center justify-center px-5 py-2.5 text-[13px] font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-[0_5px_15px_rgba(79,70,229,0.35)] hover:shadow-[0_10px_25px_rgba(79,70,229,0.45)] hover:-translate-y-0.5 whitespace-nowrap border border-transparent">
            <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i> Add Location
        </a>
    </div>
</div>

<!-- Table Card -->
<div class="glass-card rounded-[2rem] relative border-t-2 border-l-2 border-white/80 mb-8 shadow-sm">
    <div class="absolute inset-0 overflow-hidden rounded-[2rem] pointer-events-none -z-10">
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100 rounded-full filter blur-3xl opacity-30 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-100 rounded-full filter blur-3xl opacity-30 animate-float"></div>
    </div>

    @if($locations->count() > 0)
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto px-4 pt-1 pb-4 md:px-6 md:pt-2 md:pb-6">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Nama Location</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100">Description</th>
                    <th class="px-6 py-4 text-[10px] font-black text-indigo-400 uppercase tracking-widest border-b-2 border-dashed border-indigo-100 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="space-y-2">
                @foreach($locations as $location)
                <tr class="group hover:bg-white/80 transition-all duration-300 rounded-2xl border-b border-gray-100 hover:shadow-[0_2px_15px_rgba(79,70,229,0.05)] relative">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <h4 class="text-[15px] font-black text-gray-800">{{ $location->location_name }}</h4>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-[13px] font-medium text-gray-600 truncate max-w-xs block">{{ $location->description ?: '-' }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2 transition-all duration-300">
                            <a href="{{ route('locations.edit', $location) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-purple-500 hover:bg-purple-500 hover:text-white hover:border-purple-500 transition-all hover:scale-110 hover:rotate-6 shadow-sm" title="Modifikasi Entri">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this, 'Location')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all hover:scale-110 hover:rotate-12 shadow-sm" title="Hapus Permanen">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4 px-4 pt-4 pb-4">
        @foreach($locations as $location)
        <div class="bg-white rounded-[1.25rem] p-4 border border-indigo-50 shadow-[0_5px_15px_-5px_rgba(79,70,229,0.08)] flex flex-col gap-3 relative overflow-hidden group">
            <div class="flex gap-3 items-start justify-between border-b border-gray-100 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 shadow-inner flex-shrink-0">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-black text-gray-800 leading-tight">{{ $location->location_name }}</h4>
                    </div>
                </div>
                <!-- Actions -->
                <div class="flex gap-1.5 flex-shrink-0">
                    <a href="{{ route('locations.edit', $location) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-purple-50 text-purple-500 hover:bg-purple-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                </div>
            </div>
            <!-- Body -->
            <div class="pt-1">
                <span class="text-gray-400 font-bold text-[9px] uppercase tracking-wider mb-0.5 block">Description</span>
                <span class="font-bold text-gray-700 text-[12px] line-clamp-3">{{ $location->description ?: '-' }}</span>
            </div>
            
            <div class="mt-2 text-right border-t border-gray-100 pt-3">
                <form action="{{ route('locations.destroy', $location) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete(this, 'Location')" class="text-[10px] uppercase tracking-wider font-bold text-red-400 hover:text-red-600 flex items-center justify-end w-full gap-1.5 transition-colors">
                        <i class="fas fa-trash-alt"></i> Hapus Permanen
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="px-6 py-4 border-t border-indigo-50 bg-white/50 backdrop-blur-sm rounded-b-[2rem]">
        {{ $locations->links() }}
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-24 text-center relative z-10 px-4">
        <h3 class="text-2xl font-black text-indigo-900 mb-3 tracking-tight">Data Tidak Ditemukan</h3>
        <p class="text-[15px] font-semibold text-gray-500 max-w-md mx-auto mb-8 leading-relaxed">Sistem belum memiliki entri location yang dicari.</p>
        <a href="{{ route('locations.create') }}" class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black transition-all group border border-transparent">
            <i class="fas fa-plus mr-3"></i> Tambah Location
        </a>
    </div>
    @endif
</div>
<script>
    function confirmDelete(btn, type) {
        confirmAction({
            title: 'Hapus ' + type + '?',
            text: 'Tindakan ini permanen. Seluruh data aset yang terkait mungkin akan terdampak.',
            confirmText: 'Ya, Hapus',
            icon: 'error'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.closest('form').submit();
            }
        });
    }
</script>
@endsection
