@extends('layouts.app')

@section('title', 'Kamus SIBI - SIBI Learn')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kamus.css') }}">
@endpush

@section('content')
<div class="space-y-8 mt-2 md:mt-6">
    <!-- Header & Search Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight">Kamus SIBI</h1>
            <p class="text-sm sm:text-base text-on-surface-variant mt-1">Koleksi lengkap kosakata bahasa isyarat SIBI terstruktur.</p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full md:w-80 lg:w-96">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input id="searchInput" 
                   class="w-full bg-surface-container-highest border-none rounded-full py-3 pl-12 pr-4 text-sm sm:text-base focus:ring-2 focus:ring-primary shadow-sm outline-none" 
                   placeholder="Cari kosakata..." 
                   type="text">
        </div>
    </div>

    <!-- Filters Category -->
    <div class="flex gap-2.5 overflow-x-auto pb-2 scrollbar-none">
        <button class="filter-btn active px-5 py-2.5 rounded-full bg-primary text-on-primary text-xs sm:text-sm font-semibold whitespace-nowrap shadow-sm transition-all" data-category="all">Semua</button>
        @if(isset($categories) && count($categories) > 0)
            @foreach($categories as $catItem)
                <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="{{ $catItem }}">{{ $catItem }}</button>
            @endforeach
        @else
            <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Kerja">Kata Kerja</button>
            <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Benda">Kata Benda</button>
            <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Sifat">Kata Sifat</button>
            <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Ungkapan">Ungkapan</button>
        @endif
    </div>

    <!-- Dictionary Cards Grid -->
    <div id="dictionaryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($kamus as $item)
            @php
                $catLower = strtolower($item->category ?? '');
                $badgeClass = match(true) {
                    str_contains($catLower, 'kerja') => 'bg-primary/10 text-primary',
                    str_contains($catLower, 'benda') => 'bg-emerald-500/10 text-emerald-700',
                    str_contains($catLower, 'sifat') => 'bg-amber-500/10 text-amber-700',
                    str_contains($catLower, 'ungkapan') => 'bg-purple-500/10 text-purple-700',
                    default => 'bg-primary/10 text-primary',
                };
                $videoUrl = $item->video_path ? (str_starts_with($item->video_path, 'http') ? $item->video_path : asset($item->video_path)) : null;
                $isVideo = $videoUrl && preg_match('/\.(mp4|webm|ogg|mov|m4v)$/i', $videoUrl);
            @endphp
            <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" 
                 data-word="{{ strtolower($item->word) }}" 
                 data-category="{{ $item->category ?? 'Kosakata' }}"
                 data-title="{{ $item->word }}"
                 data-desc="{{ $item->description ?? '' }}"
                 data-media="{{ $videoUrl ?? '' }}"
                 data-is-video="{{ $isVideo ? 'true' : 'false' }}">
                <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                    @if($videoUrl && !$isVideo)
                        <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                             src="{{ $videoUrl }}" 
                             alt="Kamus SIBI {{ $item->word }}">
                    @elseif($isVideo)
                        <video class="w-full h-full object-cover" muted preload="metadata">
                            <source src="{{ $videoUrl }}">
                        </video>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-primary/10 to-primary/5 text-primary">
                            <span class="material-symbols-outlined text-5xl">sign_language</span>
                            <span class="text-xs font-bold mt-1 opacity-80">{{ $item->word }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                        <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform">play_circle</span>
                    </div>
                </div>
                <div class="p-4 bg-white/80">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-lg font-bold text-on-surface truncate">{{ $item->word }}</h3>
                        <span class="text-xs px-2.5 py-1 rounded-full {{ $badgeClass }} font-semibold whitespace-nowrap">{{ $item->category ?? 'Kosakata' }}</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">{{ $item->description ?: 'Kosakata bahasa isyarat SIBI.' }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-6xl mb-2 text-outline">menu_book</span>
                <p class="text-lg font-semibold">Belum ada kosakata di database</p>
                <p class="text-xs text-on-surface-variant/80 mt-1">Kosakata yang ditambahkan ke tabel database akan muncul di sini secara otomatis.</p>
            </div>
        @endforelse
    </div>

    <!-- Empty Search State -->
    <div id="noResults" class="hidden text-center py-16 text-on-surface-variant">
        <span class="material-symbols-outlined text-6xl mb-2 text-outline">search_off</span>
        <p class="text-lg font-semibold">Tidak ditemukan kosakata yang cocok</p>
        <p class="text-xs text-on-surface-variant/80 mt-1">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
    </div>
</div>

<!-- Modal Detail & Video Preview -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4">
    <div class="bg-surface liquid-glass border border-white/80 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300">
        <!-- Modal Media Preview Header -->
        <div class="relative aspect-video bg-inverse-surface overflow-hidden flex items-center justify-center">
            <div id="modalMediaContainer" class="w-full h-full flex items-center justify-center">
                <!-- Injected via JS -->
            </div>
            <button id="modalCloseBtn" class="absolute top-3 right-3 bg-black/40 hover:bg-black/70 text-white rounded-full p-1.5 transition-colors">
                <span class="material-symbols-outlined text-xl">close</span>
            </button>
        </div>
        <!-- Modal Info Body -->
        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h3 id="modalTitle" class="text-2xl font-extrabold text-on-surface tracking-tight"></h3>
                <span id="modalCategory" class="text-xs px-3 py-1 rounded-full bg-primary/10 text-primary font-semibold"></span>
            </div>
            <p id="modalDesc" class="text-sm text-on-surface-variant leading-relaxed"></p>
            <div class="pt-2 flex justify-end">
                <button id="modalCloseActionBtn" class="px-5 py-2.5 bg-primary text-on-primary font-semibold text-sm rounded-full shadow-md hover:bg-primary/90 transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/kamus.js') }}"></script>
@endpush
