@extends('layouts.app')

@section('title', 'Manajemen Role - Inventaris')

@section('content')
<x-page-header 
    title="Master Hak Akses" 
    subtitle="Definisikan batasan akses dan wewenang untuk setiap grup pengguna." 
    emoji="🛡️" 
/>

<!-- Add Role Action Row -->
<div class="mb-5 relative z-30 flex flex-col sm:flex-row items-center justify-between gap-4 w-full">
    <!-- Search Section -->
    <div class="w-full sm:w-80">
        <form method="GET" action="{{ route('roles.index') }}" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-300 group-focus-within:text-indigo-400 transition-colors"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari role..." 
                   class="w-full pl-11 pr-4 py-3 bg-white/60 backdrop-blur-md border border-white hover:border-indigo-100 rounded-xl text-sm font-bold text-gray-700 focus:bg-white focus:ring-4 focus:ring-indigo-50 focus:border-indigo-200 outline-none transition-all shadow-sm">
        </form>
    </div>

    <!-- Add Button -->
    <a href="{{ route('roles.create') }}" class="w-full sm:w-auto group relative inline-flex items-center justify-center px-8 py-3.5 text-[14px] font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-[0_10px_25px_rgba(79,70,229,0.35)] hover:shadow-[0_15px_35px_rgba(79,70,229,0.45)] hover:-translate-y-1 border border-white/20 whitespace-nowrap">
        <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300 text-lg"></i> Tambah Role Baru
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($roles as $role)
    <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2.5rem] p-8 shadow-[0_10px_30px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(79,70,229,0.08)] transition-all duration-500 group relative overflow-hidden flex flex-col justify-between">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 -z-10"></div>
        
        <div>
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl flex items-center justify-center text-indigo-500 shadow-inner group-hover:scale-110 transition-transform duration-300 border border-white">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                @if(in_array($role->slug, ['admin', 'staff']))
                <span class="px-3 py-1 bg-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-indigo-200">System Core</span>
                @endif
            </div>

            <h3 class="text-2xl font-black text-gray-800 mb-2 truncate group-hover:text-indigo-600 transition-colors">{{ $role->name }}</h3>
            <p class="text-sm font-semibold text-gray-500 mb-6 leading-relaxed bg-gray-50/50 p-4 rounded-2xl border border-gray-100/50">
                {{ $role->description ?: 'Tidak ada deskripsi untuk role ini.' }}
            </p>
        </div>

        <div class="flex items-center justify-between gap-3 pt-4 border-t border-dashed border-gray-100">
            <div class="text-[11px] font-bold text-gray-400">
                Slug: <span class="font-mono text-indigo-400">{{ $role->slug }}</span>
            </div>
            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('roles.edit', $role) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white transition-all shadow-sm hover:rotate-6" title="Edit Role">
                    <i class="fas fa-edit text-sm"></i>
                </a>
                @if(!in_array($role->slug, ['admin', 'staff']))
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this, 'Role')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-50 border border-gray-100 text-red-500 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all hover:scale-110 hover:rotate-12 shadow-sm" title="Hapus Permanen">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
<script>
    function confirmDelete(btn, type) {
        confirmAction({
            title: 'Hapus ' + type + '?',
            text: 'Tindakan ini permanen. Pengguna dengan role ini mungkin kehilangan akses fitur tertentu.',
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
