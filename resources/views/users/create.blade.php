@extends('layouts.app')
@section('title', 'Tambah User - AsetKu')

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('users.index') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm transition-all hover:scale-105 border border-white">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Otorisasi User <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Baru</span></h1>
        <p class="text-sm font-bold text-gray-500 mt-0.5">Berikan akses ke staf atau administrator.</p>
    </div>
</div>

<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 md:p-8 shadow-sm max-w-2xl">
    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="space-y-2">
            <label for="name" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Nama Lengkap <span class="text-rose-500">*</span></label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-user"></i>
                </div>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Masukkan nama pengguna..."
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
                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="email@contoh.com"
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
                        <option value="{{ $role->slug }}" {{ old('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>
            @error('role') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="p-4 bg-indigo-50/50 border-l-4 border-indigo-400 rounded-r-xl">
            <p class="text-xs font-bold text-indigo-800 flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <span>Password default untuk role staff adalah <strong>password</strong>. Untuk admin, password defaultnya sama namun dapat disesuaikan pada saat edit profil.</span>
            </p>
        </div>
        
        <div class="space-y-2">
            <label for="password" class="block text-sm font-black text-gray-700 uppercase tracking-wider">Password Kustom (Opsional)</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                    <i class="fas fa-lock"></i>
                </div>
                <input type="password" name="password" id="password" placeholder="Biarkan kosong untuk password default..."
                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-gray-800 placeholder-gray-400">
            </div>
            @error('password') <p class="text-rose-500 text-xs font-bold flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('users.index') }}" class="px-5 py-3 rounded-xl font-bold text-gray-500 bg-white border border-gray-200 hover:bg-gray-50 hover:text-gray-700 transition-all shadow-sm">
                Batal
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-[0_8px_20px_rgba(79,70,229,0.3)] hover:-translate-y-0.5 transition-all flex items-center gap-2">
                <i class="fas fa-save"></i> Daftarkan User
            </button>
        </div>
    </form>
</div>
@endsection
