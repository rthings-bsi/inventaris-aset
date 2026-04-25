@extends('layouts.app')

@section('title', 'Hak Akses Role - Inventaris')

@section('content')
<x-page-header 
    title="Otorisasi & Kontrol" 
    subtitle="Konfigurasi granular untuk setiap peran pengguna di ekosistem AsetKu." 
    emoji="🛡️"
/>

<form action="{{ route('settings.roles.update') }}" method="POST" id="permissions-form">
    @csrf
    
    <!-- Role Tabs -->
    <div class="flex flex-wrap gap-2 md:gap-3 mb-8 md:mb-10 bg-white/40 p-2 md:p-2.5 rounded-[1.5rem] md:rounded-[2rem] border border-white/60 shadow-sm backdrop-blur-md w-full md:w-auto md:max-w-fit mx-auto md:mx-0">
        @foreach($roles as $role)
            <button type="button" 
                onclick="showRole('{{ $role->slug }}')"
                id="tab-{{ $role->slug }}"
                class="role-tab flex-1 md:flex-none justify-center px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black text-xs md:text-sm uppercase tracking-widest transition-all duration-300 flex items-center gap-2 md:gap-3 border shadow-sm group"
                data-role="{{ $role->slug }}">
                <i class="fas fa-shield-alt text-xs opacity-50 group-hover:opacity-100 transition-opacity"></i>
                {{ $role->name }}
            </button>
        @endforeach
    </div>

    <!-- Role Permission Contents -->
    @foreach($roles as $role)
        <div id="content-{{ $role->slug }}" class="role-content hidden space-y-10 animate-fade-in-up">
            
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5 md:gap-8">
                @foreach($groupedPermissions as $category => $permissions)
                    <div class="bg-white/60 backdrop-blur-xl border border-white rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-8 shadow-[0_15px_40px_-15px_rgba(0,0,0,0.03)] flex flex-col h-full group hover:shadow-[0_25px_60px_-15px_rgba(79,70,229,0.1)] transition-all duration-500">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl flex items-center justify-center text-indigo-600 border border-indigo-100/50 shadow-inner group-hover:scale-110 group-hover:rotate-3 transition-all duration-300">
                                <i class="fas {{ $category === 'Manajemen Aset' ? 'fa-boxes' : ($category === 'Data Master' ? 'fa-tags' : 'fa-cog') }} text-xl group-hover:animate-jelly"></i>
                            </div>
                            <h3 class="text-xl font-black text-gray-800 tracking-tight">{{ $category }}</h3>
                        </div>

                        <div class="space-y-2 flex-1">
                            @foreach($permissions as $key => $label)
                                <label class="flex items-center justify-between p-3.5 md:p-4 rounded-xl md:rounded-2xl hover:bg-white transition-all cursor-pointer border-2 border-transparent hover:border-indigo-50 hover:shadow-sm group/item">
                                    <div class="flex items-center gap-4">
                                        <div class="w-2.5 h-2.5 rounded-full bg-indigo-100 group-hover/item:bg-indigo-400 group-hover/item:scale-125 transition-all"></div>
                                        <span class="text-gray-600 font-bold text-sm group-hover/item:text-gray-800 transition-colors">{{ $label }}</span>
                                    </div>
                                    
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="permissions[{{ $role->slug }}][{{ $key }}]" value="1" 
                                            class="sr-only peer" {{ in_array($key, $rolePermissions[$role->slug]) ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-500 peer-checked:to-purple-500 shadow-inner"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Bottom Save Action -->
    <div class="flex justify-center mt-8 pb-8 md:mt-12 md:pb-10 px-4 md:px-0">
        <button type="submit" 
            onclick="this.classList.add('opacity-80', 'cursor-wait'); this.innerHTML='<i class=\'fas fa-circle-notch fa-spin\'></i> MENYIMPAN...'"
            class="w-full sm:w-auto justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black px-8 py-3.5 md:px-10 md:py-4 rounded-xl md:rounded-2xl shadow-[0_4px_15px_rgba(79,70,229,0.3)] hover:shadow-[0_8px_25px_rgba(79,70,229,0.4)] hover:-translate-y-1 hover:scale-[1.02] active:scale-95 transition-all duration-300 text-xs md:text-sm flex items-center gap-3 border border-white/20 group">
            <i class="fas fa-save text-base group-hover:animate-jelly"></i> 
            SIMPAN PERUBAHAN AKSES
        </button>
    </div>
</form>

<script>
    function showRole(slug) {
        // Hide all contents
        document.querySelectorAll('.role-content').forEach(el => el.classList.add('hidden'));
        // Show current content
        document.getElementById('content-' + slug).classList.remove('hidden');

        // Update tabs
        document.querySelectorAll('.role-tab').forEach(el => {
            el.classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'text-white', 'border-transparent', 'shadow-[0_4px_15px_rgba(79,70,229,0.3)]', 'scale-105', '-translate-y-1', 'bg-indigo-600', 'shadow-indigo-200', 'border-indigo-600');
            el.classList.add('bg-white', 'text-gray-500', 'border-gray-100', 'hover:border-indigo-200');
            
            const icon = el.querySelector('i');
            if (icon) {
                icon.classList.remove('text-indigo-200');
                icon.classList.add('opacity-50');
            }
        });

        const activeTab = document.getElementById('tab-' + slug);
        activeTab.classList.remove('bg-white', 'text-gray-500', 'border-gray-100', 'hover:border-indigo-200');
        activeTab.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'text-white', 'border-transparent', 'shadow-[0_4px_15px_rgba(79,70,229,0.3)]', 'scale-105', '-translate-y-1');
        
        const activeIcon = activeTab.querySelector('i');
        if (activeIcon) {
            activeIcon.classList.remove('opacity-50');
            activeIcon.classList.add('text-indigo-200');
        }
    }

    // Default to first role or previous selection?
    document.addEventListener('DOMContentLoaded', () => {
        const firstRole = @json($roles->first()?->slug);
        showRole(firstRole);
    });
</script>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
