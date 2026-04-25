@extends('layouts.app')

@section('title', 'Buat Pengajuan Peminjaman - Inventaris')

@section('content')
<!-- Page Header -->
<x-page-header 
    title="Buat Pengajuan Peminjaman" 
    subtitle="Pilih aset yang tersedia dari gudang dan isi form pengajuan operasional Anda." 
    emoji="✨"
>
    <x-slot name="actions">
        <a href="{{ route('loans.index') }}" class="inline-flex items-center text-xs font-black tracking-widest uppercase text-indigo-400 hover:text-indigo-600 transition-colors group bg-white/60 px-4 py-2 rounded-xl border border-indigo-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </x-slot>
</x-page-header>

<!-- Form Card -->
<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-8 shadow-sm mb-6 relative z-20 overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-sm font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
            <i class="fas fa-edit"></i> Form Pengajuan Aset
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
                    <select name="id_assets" required class="w-full pl-5 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100/50 text-sm font-bold text-gray-700 outline-none transition-all appearance-none cursor-pointer shadow-sm hover:border-indigo-300">
                        <option value="">Pilih Aset yang Tersedia...</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id_assets }}">{{ $asset->asset_code }} - {{ $asset->asset_name }} [Status: {{ $asset->condition }}] ({{ $asset->category->category_name ?? 'Tanpa Kategori' }})</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none text-indigo-400">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                @error('id_assets')
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
