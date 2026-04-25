@extends('layouts.app')

@section('title', 'Tambah Location - Inventaris')

@section('content')
<x-page-header 
    title="Tambah Location Baru" 
    subtitle="Definisikan area atau titik fisik untuk melacak keberadaan aset Anda." 
    emoji="✨"
>
    <x-slot name="actions">
        <a href="{{ route('locations.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/60 hover:bg-white text-indigo-600 rounded-xl text-sm font-black transition-all shadow-sm border border-indigo-50 group/back">
            <i class="fas fa-arrow-left mr-2 group-hover/back:-translate-x-1 transition-transform"></i> Kembali ke Daftar
        </a>
    </x-slot>
</x-page-header>

<div class="glass-card rounded-[2rem] p-8 border-t-2 border-l-2 border-white/80 shadow-sm relative overflow-hidden text-gray-800">
    <form action="{{ route('locations.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-y-6">
            <div class="group/input relative">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest">Nama Location <span class="text-pink-500">*</span></label>
                <input type="text" name="location_name" value="{{ old('location_name') }}" required class="w-full px-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all font-bold outline-none @error('location_name') border-red-300 bg-red-50 focus:border-red-500 @enderror" placeholder="Contoh: Gedung A Lantai 2">
                @error('location_name') <p class="text-red-500 text-xs font-bold mt-2"><i class="fas fa-info-circle"></i> {{ $message }}</p> @enderror
            </div>

            <div class="group/input relative">
                <label class="block text-xs font-black text-indigo-900 mb-2 uppercase tracking-widest">Description</label>
                <textarea name="description" rows="4" class="w-full px-5 py-3.5 bg-gray-50/50 border-2 border-indigo-100 rounded-2xl focus:border-indigo-400 focus:shadow-lg focus:shadow-indigo-100 transition-all font-bold outline-none resize-none @error('description') border-red-300 bg-red-50 focus:border-red-500 @enderror" placeholder="Notes tentang location ini...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs font-bold mt-2"><i class="fas fa-info-circle"></i> {{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-dashed border-indigo-100">
            <a href="{{ route('locations.index') }}" class="px-8 py-3.5 bg-white border-2 border-indigo-100 text-gray-500 rounded-xl font-black hover:bg-gray-50 transition-all">Batalkan</a>
            <button type="submit" class="px-10 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-black shadow-md hover:-translate-y-1 transition-all"><i class="fas fa-save mr-2"></i> Simpan</button>
        </div>
    </form>
</div>
@endsection
