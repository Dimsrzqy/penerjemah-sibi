@extends('layouts.app')

@section('title', 'Kamus SIBI - Isyarat K-SULI')

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
                   name="q"
                   value="{{ is_string(request('q')) ? request('q') : '' }}"
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

<!-- Modal Detail & Video Preview (macOS Window Style) -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 p-4" role="dialog" aria-modal="true" aria-labelledby="modalHeaderTitle">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl max-w-lg w-full overflow-hidden transform scale-95 transition-all duration-300 flex flex-col">
        <!-- macOS Window Titlebar (Traffic Lights & Centered Title) -->
        <div class="px-4 py-3 bg-slate-100 border-b border-slate-200 flex items-center justify-between select-none">
            <!-- Mac Traffic Lights (Merah, Kuning, Hijau) -->
            <div class="flex items-center gap-2 group/lights">
                <!-- Tombol Merah (Close Window) -->
                <button id="modalCloseBtn" 
                        type="button" 
                        title="Tutup (Esc)" 
                        aria-label="Tutup jendela kamus"
                        class="w-3.5 h-3.5 rounded-full bg-[#ff5f56] hover:bg-[#e0443e] active:scale-90 border border-[#e0443e]/40 flex items-center justify-center text-[9px] text-[#7d0000] font-black transition-all cursor-pointer shadow-xs focus:outline-none focus:ring-2 focus:ring-red-400">
                    <span class="opacity-0 group-hover/lights:opacity-100 hover:opacity-100 transition-opacity leading-none select-none">✕</span>
                </button>
                <!-- Tombol Kuning (Minimize / Neutral) -->
                <span title="Kuning" class="w-3.5 h-3.5 rounded-full bg-[#ffbd2e] border border-[#dea123]/40 flex items-center justify-center text-[9px] text-[#995700] font-bold shadow-xs select-none">
                    <span class="opacity-0 group-hover/lights:opacity-100 transition-opacity leading-none select-none">−</span>
                </span>
                <!-- Tombol Hijau (Maximize / Expand) -->
                <span title="Hijau" class="w-3.5 h-3.5 rounded-full bg-[#27c93f] border border-[#1aab29]/40 flex items-center justify-center text-[9px] text-[#006500] font-bold shadow-xs select-none">
                    <span class="opacity-0 group-hover/lights:opacity-100 transition-opacity leading-none select-none">＋</span>
                </span>
            </div>

            <!-- macOS Centered Window Title -->
            <div class="flex items-center gap-1.5 min-w-0 px-2">
                <span class="material-symbols-outlined text-slate-400 text-sm">sign_language</span>
                <span id="modalHeaderTitle" class="text-xs font-bold text-slate-700 truncate max-w-[180px] sm:max-w-[240px]">Kamus SIBI</span>
            </div>

            <!-- Status / Kategori Badge di Sisi Kanan Window -->
            <div class="flex items-center justify-end">
                <span id="modalCategoryPill" class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200 truncate max-w-[110px]">
                    Kosakata
                </span>
            </div>
        </div>

        <!-- Modal Media Preview Header -->
        <div class="relative aspect-video bg-slate-950 overflow-hidden flex items-center justify-center">
            <div id="modalMediaContainer" class="w-full h-full flex items-center justify-center">
                <!-- Injected via JS -->
            </div>
        </div>

        <!-- Modal Info Body -->
        <div class="p-6 space-y-4 bg-white">
            <div class="flex items-center justify-between gap-3">
                <h3 id="modalTitle" class="text-2xl font-extrabold text-slate-900 tracking-tight"></h3>
                <span id="modalCategory" class="text-xs px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-semibold"></span>
            </div>
            <p id="modalDesc" class="text-sm text-slate-600 leading-relaxed"></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/kamus.js') }}"></script>
@endpush
