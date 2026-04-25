@extends('layouts.app')
@section('title', 'Edit User - AsetKu')

@section('content')
<x-page-header 
    title="Kustomisasi Profil User" 
    subtitle="Edit detail akun, role, dan kredensial keamanan pengguna." 
    emoji="👤"
>
    <x-slot name="actions">
        <a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/60 hover:bg-white text-indigo-600 rounded-xl text-sm font-black transition-all shadow-sm border border-indigo-50 group/back">
            <i class="fas fa-arrow-left mr-2 group-hover/back:-translate-x-1 transition-transform"></i> Kembali ke Daftar User
        </a>
    </x-slot>
</x-page-header>

<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 md:p-8 shadow-sm max-w-2xl">
    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="space-y-2">
            <label for="name" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" placeholder="Masukkan nama pengguna..."
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400">
            </div>
            @error('name') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="email" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Email <span class="text-rose-500">*</span></label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-envelope"></i>
                </div>
                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" placeholder="email@contoh.com"
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400">
            </div>
            @error('email') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="role" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Peran & Hak Akses <span class="text-rose-500">*</span></label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors z-10">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <select name="role" id="role" required class="block w-full pl-11 pr-10 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl appearance-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-gray-700 cursor-pointer">
                    @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ old('role', $user->role) == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>
            @if(auth()->id() === $user->id_users)
                <p class="text-amber-500 text-xs font-bold mt-1"><i class="fas fa-exclamation-triangle"></i> Peringatan: Anda sedang mengubah role Anda sendiri.</p>
            @endif
            @error('role') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="border-t border-gray-100 pt-6 mt-6">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-4"><i class="fas fa-key text-indigo-500 mr-2"></i>Ubah Password</h3>
            <div class="p-4 bg-amber-50/50 border-l-4 border-amber-400 rounded-r-xl mb-4">
                <p class="text-xs font-bold text-amber-800 flex items-start gap-2">
                    <i class="fas fa-info-circle mt-0.5"></i>
                    <span>Abaikan / kosongkan jika tidak ingin mengubah password lama pengguna ini.</span>
                </p>
            </div>
            
            <div class="space-y-4">
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" autocomplete="new-password" placeholder="Minimal 8 karakter..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400">
                    </div>
                    @error('password') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Konfirmasi Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password baru..."
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('users.index') }}" class="px-5 py-3 rounded-xl font-bold text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-700 transition-all shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Perbarui Profil
            </button>
        </div>
    </form>
</div>
@endsection
