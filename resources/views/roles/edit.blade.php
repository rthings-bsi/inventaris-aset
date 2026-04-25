@extends('layouts.app')

@section('title', 'Edit Role - Inventaris')

@section('content')
<x-page-header 
    title="Modifikasi Role" 
    subtitle="Ubah detail peran untuk {!! $role->name !!}." 
    emoji="🛡️"
>
    <x-slot name="actions">
        <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/60 hover:bg-white text-indigo-600 rounded-xl text-sm font-black transition-all shadow-sm border border-indigo-50 group/back">
            <i class="fas fa-arrow-left mr-2 group-hover/back:-translate-x-1 transition-transform"></i> Kembali ke Daftar Role
        </a>
    </x-slot>
</x-page-header>

<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 md:p-8 shadow-sm max-w-2xl">
    <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="space-y-2">
            <label for="name" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Nama Role <span class="text-rose-500">*</span></label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <input type="text" name="name" id="name" required value="{{ old('name', $role->name) }}" placeholder="Misal: Manager, Supervisor, dll."
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-indigo-300">
            </div>
            @error('name') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
            
            @if(in_array($role->slug, ['admin', 'staff']))
            <p class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-lg border border-amber-100 flex items-center gap-2 mt-2">
                <i class="fas fa-exclamation-triangle"></i>
                <span>Role sistem: Nama dapat diubah, namun slug identitas akan tetap <span class="font-mono">{{ $role->slug }}</span> demi keamanan sistem.</span>
            </p>
            @endif
        </div>

        <div class="space-y-2">
            <label for="description" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Deskripsi Role</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 pt-4 flex items-start pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-info-circle"></i>
                </div>
                <textarea name="description" id="description" rows="4" placeholder="Jelaskan tanggung jawab role ini..."
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-indigo-300">{{ old('description', $role->description) }}</textarea>
            </div>
            @error('description') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('roles.index') }}" class="px-5 py-3 rounded-xl font-bold text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-700 transition-all shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Perbarui Role
            </button>
        </div>
    </form>
</div>
@endsection
