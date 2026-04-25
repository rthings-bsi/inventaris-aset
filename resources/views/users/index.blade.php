@extends('layouts.app')
@section('title', 'Manajemen User - AsetKu')

@section('content')
<x-page-header 
    title="Manajemen User" 
    subtitle="Kelola akses staf dan otorisasi. Anda dapat menambahkan akun baru dan mengatur peran." 
    emoji="👥" 
/>

<!-- Action Row -->
<div class="mb-5 relative z-30 flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
    <!-- Search Section -->
    <div class="w-full sm:w-80">
        <form method="GET" action="{{ route('users.index') }}" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-300 group-focus-within:text-indigo-400 transition-colors"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama atau email..." 
                   class="w-full pl-11 pr-4 py-3 bg-white/60 backdrop-blur-md border border-white hover:border-indigo-100 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all shadow-sm">
        </form>
    </div>

    <!-- Add Button -->
    <a href="{{ route('users.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black text-sm rounded-xl hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 transition-all border border-white/20 group">
        <i class="fas fa-plus-circle group-hover:rotate-90 transition-transform"></i> Tambah User Baru
    </a>
</div>

<div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] p-6 shadow-sm mb-6 relative z-20">
    <div class="mb-6">
        <h2 class="text-lg font-black text-gray-800"><i class="fas fa-list-ul text-indigo-500 mr-2"></i>Daftar Pengguna Sistem</h2>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hidden md:block">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] uppercase tracking-wider text-gray-500 font-black">
                    <th class="p-4 rounded-tl-2xl">Nama & Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Tgl Dibuat</th>
                    <th class="p-4 text-right rounded-tr-2xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($users as $user)
                <tr class="hover:bg-indigo-50/30 transition-colors group">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-black shadow-inner border border-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-black {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-emerald-100 text-emerald-700 border-emerald-200' }} border">
                            <i class="fas {{ $user->isAdmin() ? 'fa-shield-alt' : 'fa-user-tie mb-0.5' }} mr-1"></i> {{ $user->role_display_name }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-500 font-medium">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td class="p-4">
                        <div class="flex items-center justify-end gap-2 transition-all duration-300">
                            <a href="{{ route('users.edit', $user) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Edit Profile/Role">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            @if(auth()->id() !== $user->id_users)
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this, 'User')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all hover:scale-110 hover:rotate-12 shadow-sm" title="Hapus Permanen">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            @else
                                <button disabled class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gray-50 text-gray-300 cursor-not-allowed shadow-sm" title="Tidak dapat menghapus diri sendiri">
                                    <i class="fas fa-ban text-xs"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center bg-white">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 text-gray-300 mb-3">
                            <i class="fas fa-user-slash text-2xl"></i>
                        </div>
                        <p class="text-gray-500 font-bold">Belum ada pengguna sistem yang terdaftar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile view -->
    <div class="md:hidden space-y-4">
        @forelse($users as $user)
        <div class="p-4 bg-white rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 font-black shadow-inner border border-white">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between items-center bg-gray-50 rounded-xl p-3 mb-4">
                <p class="text-[11px] font-black uppercase text-gray-500">Role Status:</p>
                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                    <i class="fas {{ $user->isAdmin() ? 'fa-shield-alt' : 'fa-user-tie mb-0.5' }} mr-1 text-[8px]"></i> {{ $user->role_display_name }}
                </span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="flex-1 flex items-center justify-center gap-2 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-xl text-xs font-bold transition-colors">
                    <i class="fas fa-pen"></i> Edit
                </a>
                @if(auth()->id() !== $user->id_users)
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 bg-rose-50 text-rose-600 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-bold transition-colors">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                @else
                    <button disabled class="flex-1 flex items-center justify-center gap-2 py-2 bg-gray-50 text-gray-400 rounded-xl text-xs font-bold cursor-not-allowed">
                        <i class="fas fa-ban"></i> Hapus
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center bg-white rounded-2xl border border-gray-100">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-50 text-gray-300 mb-3">
                <i class="fas fa-user-slash text-2xl"></i>
            </div>
            <p class="text-gray-500 font-bold">Belum ada pengguna.</p>
        </div>
        @endforelse
    </div>

    @if($users->hasPages())
        <div class="mt-6 pt-4 border-t border-gray-50">
            {{ $users->links() }}
        </div>
    @endif
</div>
<script>
    function confirmDelete(btn, type) {
        confirmAction({
            title: 'Hapus ' + type + '?',
            text: 'Tindakan ini permanen. Akses pengguna ini akan segera dihentikan.',
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
