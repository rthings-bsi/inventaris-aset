@props(['title', 'subtitle', 'icon' => null, 'emoji' => null])

<div class="relative glass-header rounded-[2rem] p-8 md:p-10 mb-8 overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border-2 border-white group">
    <!-- Optimized Blobs -->
    <div class="absolute -top-20 -left-20 w-80 h-80 bg-gradient-to-br from-indigo-200/60 to-purple-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none"></div>
    <div class="absolute -bottom-20 right-20 w-64 h-64 bg-gradient-to-br from-pink-200/60 to-rose-200/60 rounded-full mix-blend-multiply filter blur-[3rem] opacity-70 blob pointer-events-none" style="animation-delay: 2s;"></div>

    <div class="relative z-10 w-full flex flex-col md:flex-row justify-between items-start md:items-center gap-6 text-left">
        <div class="flex-1">
            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight flex items-center gap-3">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-700 to-purple-700">
                    @if($icon) <i class="{{ $icon }} text-indigo-500 mr-1.5 align-middle"></i>@endif
                    {{ $title }}
                </span> 
                @if($emoji) <span class="text-2xl md:text-3xl filter drop-shadow-sm group-hover:scale-110 transition-transform duration-500">{{ $emoji }}</span> @endif
            </h1>
            <p class="text-gray-600 mt-2 font-semibold text-lg max-w-xl leading-relaxed">{{ $subtitle }}</p>
        </div>
        
        @if(isset($actions))
            <div class="flex-shrink-0 w-full md:w-auto">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
