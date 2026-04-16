@extends('layouts.app')

@section('title', 'Buat Pengajuan Peminjaman - Inventaris')

@section('content')
<!-- Page Header -->
<div class="relative glass-header rounded-[2rem] p-5 sm:p-8 mb-6 sm:mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] animate-slide-down text-gray-800 border-2 border-white" style="background: linear-gradient(135deg, rgba(238, 242, 255, 0.9) 0%, rgba(243, 232, 255, 0.9) 100%); backdrop-filter: blur(20px);">
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-indigo-200/50 to-purple-200/50 rounded-full mix-blend-multiply filter blur-2xl animate-pulse"></div>
    <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700 flex items-center gap-2">
                <i class="fas fa-plus-circle text-indigo-500"></i> Buat Pengajuan Peminjaman
            </h1>
            <p class="text-sm font-bold text-gray-500 mt-2">Pilih aset yang tersedia dari gudang dan isi form pengajuan operasional Anda.</p>
        </div>
        <a href="{{ route('loans.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-indigo-400 hover:text-indigo-600 transition-colors group bg-white/60 px-4 py-2 rounded-xl">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white/85 backdrop-blur-[16px] border border-white/80 shadow-[0_10px_30px_rgba(0,0,0,0.02)] rounded-[2rem] overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="bg-gradient-to-r from-indigo-50/50 to-purple-50/50 px-5 sm:px-6 py-4 sm:py-5 border-b border-indigo-100/50">
        <h2 class="text-sm font-black text-indigo-700 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-edit text-indigo-400"></i> Form Pengajuan Aset
        </h2>
    </div>

    <form action="{{ route('loans.store') }}" method="POST" class="p-5 sm:p-8">
        @csrf
        <div class="space-y-6 max-w-3xl">
            <!-- Asset Select -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-3 uppercase flex items-center gap-2">
                    <i class="fas fa-boxes text-indigo-300"></i> Pilih Aset Operasional <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="asset_id" required class="w-full pl-5 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-bold text-gray-700 outline-none transition-all appearance-none cursor-pointer shadow-sm hover:border-indigo-300">
                        <option value="">Pilih Aset yang Tersedia...</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->asset_code }} - {{ $asset->asset_name }} [Status: {{ $asset->condition }}] ({{ $asset->category->category_name ?? 'Tanpa Kategori' }})</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('asset_id')
                    <p class="mt-2 text-xs font-bold text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-xs font-black tracking-widest text-indigo-500 mb-3 uppercase flex items-center gap-2">
                    <i class="fas fa-align-left text-indigo-300"></i> Keperluan Peminjaman (Opsional)
                </label>
                <textarea name="notes" rows="4" placeholder="Jelaskan kebutuhan operasional Anda secara singkat..." class="w-full p-5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-semibold text-gray-700 outline-none transition-all placeholder:text-gray-400 shadow-sm hover:border-indigo-300 resize-none"></textarea>
                @error('notes')
                    <p class="mt-2 text-xs font-bold text-red-500"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-indigo-50/50 flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                <button type="reset" class="px-6 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-black text-sm uppercase tracking-wider hover:bg-gray-50 hover:text-gray-800 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-redo-alt"></i> Reset Form
                </button>
                <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black text-sm uppercase tracking-wider shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 transition-all flex items-center justify-center gap-2 group">
                    Kirim Pengajuan <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
