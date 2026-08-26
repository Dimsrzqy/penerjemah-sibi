@extends('layouts.app')

@section('title', 'Kamus SIBI - SIBI Learn')

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
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl" data-icon="search">search</span>
            <input id="searchInput" 
                   class="w-full bg-surface-container-highest border-none rounded-full py-3 pl-12 pr-4 text-sm sm:text-base focus:ring-2 focus:ring-primary shadow-sm outline-none" 
                   placeholder="Cari kosakata..." 
                   type="text">
        </div>
    </div>

    <!-- Filters Category -->
    <div class="flex gap-2.5 overflow-x-auto pb-2 scrollbar-none">
        <button class="filter-btn active px-5 py-2.5 rounded-full bg-primary text-on-primary text-xs sm:text-sm font-semibold whitespace-nowrap shadow-sm transition-all" data-category="all">Semua</button>
        <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Kerja">Kata Kerja</button>
        <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Benda">Kata Benda</button>
        <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Kata Sifat">Kata Sifat</button>
        <button class="filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all" data-category="Ungkapan">Ungkapan</button>
    </div>

    <!-- Dictionary Cards Grid -->
    <div id="dictionaryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Makan -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="makan" data-category="Kata Kerja">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuBftmkxrqoUjGInkhXSuG0zgEYMn1briscxM-wIxvW9Aywmv7G4dLqcAsoIIaXQ2WeBATtcZE6cbSs7glZ22ApR5igs3jMshZYxjHceE29ip57bgajRuvUJgLK8GJZpqFvtGdIu1j6HvM3GxaD44JHOYnDzJOdl588C4XpKGWT1V8OgMdYgEfjvQ2ZOTUZkCq1LMxoO7dn9osa4jXYpkllfjWHC_gF-vz8mtGw5rMH7OVLVN9c0gJ7G" 
                     alt="Kamus SIBI Makan">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Makan</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary font-semibold">Kata Kerja</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Gerakan mendekatkan tangan ke mulut menirukan makan.</p>
            </div>
        </div>

        <!-- Card 2: Maaf -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="maaf" data-category="Kata Sifat">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7twOiPdRGVmwC9bskNu92kHS41oisXBERt7kgN85UYZikrWal5jZoQAJgZltwUHIkLchyLtmijr4sNYYIm5pgcp4PrCN1xLCpCgHrsKKwC88_QVZJzXIJo24HrNutSl7M00B-ZD_ro2Zz4SApcOT5Ht-S0LU_Qjy0Wbw8PCvL2andqJzMndViPSjiAUi0TW4HoQZTLuxHLugp2XTtMKaIq8n31Rm5drcT9-FOVdsDVvI1Tz9v10e3" 
                     alt="Kamus SIBI Maaf">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Maaf</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-700 font-semibold">Kata Sifat</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Telapak tangan terbuka memutar perlahan di dada.</p>
            </div>
        </div>

        <!-- Card 3: Rumah -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="rumah" data-category="Kata Benda">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuDmRswgf5Heve0-Oor8MzSKz069xs4AsEYFnJm2gKcMRxaHePATeTVvvNZ6cvc1UnO6N9lJBMNdFdckE95mSKAZsJO2i3HvNNSRfngfDcnkDn7ioZFhEb23O2k9SiuC3pB8Re3WR7saJvnSeN3MCEYnOCDpfiskalH8a7PnGl1R6Qx9GPR_1F7gr7O1ZnFxurAJQ2CL1zSvxpBu4JCXT_VZLlASrnMQbjrYEXVXvs33OhdnHo9JVg8H" 
                     alt="Kamus SIBI Rumah">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Rumah</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-700 font-semibold">Kata Benda</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Kedua ujung jari bertemu membentuk atap segitiga.</p>
            </div>
        </div>

        <!-- Card 4: Teman -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="teman" data-category="Kata Benda">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLvjqFafe4f4il5muBusBMAK6OD41Ol0erlPfpZKGhrElo5a5ES4Q5NAABetUjOafJvVgFTXZfmINXiewoJBhN_H7Vjeb0lxrpKtxyNpGQkAeVnokBhXpnuucHdofNsY8cEdeFuzcQkyI2XWThSIHvKYq5H6czDlBVb0G_C-DtcTgdVXBr2_tlDp0AZL_8_VT-6PZtqHpzUKKfxXwKh1SiVy2tfH7NVsIYXWdWPnVXCKUWVpgUCej_" 
                     alt="Kamus SIBI Teman">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Teman</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-700 font-semibold">Kata Benda</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Kedua jari telunjuk saling mengait secara bersahabat.</p>
            </div>
        </div>

        <!-- Card 5: Terima Kasih -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="terima kasih" data-category="Ungkapan">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7twOiPdRGVmwC9bskNu92kHS41oisXBERt7kgN85UYZikrWal5jZoQAJgZltwUHIkLchyLtmijr4sNYYIm5pgcp4PrCN1xLCpCgHrsKKwC88_QVZJzXIJo24HrNutSl7M00B-ZD_ro2Zz4SApcOT5Ht-S0LU_Qjy0Wbw8PCvL2andqJzMndViPSjiAUi0TW4HoQZTLuxHLugp2XTtMKaIq8n31Rm5drcT9-FOVdsDVvI1Tz9v10e3" 
                     alt="Kamus SIBI Terima Kasih">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Terima Kasih</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-purple-500/10 text-purple-700 font-semibold">Ungkapan</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Tangan diletakkan di bibir lalu digerakkan maju ke depan.</p>
            </div>
        </div>

        <!-- Card 6: Belajar -->
        <div class="dict-card liquid-glass rounded-2xl overflow-hidden group cursor-pointer hover:scale-[1.02] transition-all duration-300 border border-white/70 shadow-md" data-word="belajar" data-category="Kata Kerja">
            <div class="relative aspect-video bg-surface-container-highest overflow-hidden">
                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuBftmkxrqoUjGInkhXSuG0zgEYMn1briscxM-wIxvW9Aywmv7G4dLqcAsoIIaXQ2WeBATtcZE6cbSs7glZ22ApR5igs3jMshZYxjHceE29ip57bgajRuvUJgLK8GJZpqFvtGdIu1j6HvM3GxaD44JHOYnDzJOdl588C4XpKGWT1V8OgMdYgEfjvQ2ZOTUZkCq1LMxoO7dn9osa4jXYpkllfjWHC_gF-vz8mtGw5rMH7OVLVN9c0gJ7G" 
                     alt="Kamus SIBI Belajar">
                <div class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/10 transition-colors">
                    <span class="material-symbols-outlined text-4xl text-white opacity-90 group-hover:scale-110 transition-transform" data-icon="play_circle">play_circle</span>
                </div>
            </div>
            <div class="p-4 bg-white/80">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-on-surface">Belajar</h3>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-primary/10 text-primary font-semibold">Kata Kerja</span>
                </div>
                <p class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">Mengambil dari telapak tangan kiri dan ditempelkan ke dahi.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchInput');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.dict-card');

        let activeCategory = 'all';

        function filterCards() {
            const query = searchInput.value.toLowerCase().trim();
            cards.forEach(card => {
                const word = card.getAttribute('data-word').toLowerCase();
                const cat = card.getAttribute('data-category');
                const matchesSearch = word.includes(query);
                const matchesCategory = (activeCategory === 'all' || cat === activeCategory);

                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        searchInput.addEventListener('input', filterCards);

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => {
                    b.className = 'filter-btn px-5 py-2.5 rounded-full bg-surface-container-high text-on-surface-variant hover:bg-surface-dim text-xs sm:text-sm font-semibold whitespace-nowrap transition-all';
                });
                btn.className = 'filter-btn active px-5 py-2.5 rounded-full bg-primary text-on-primary text-xs sm:text-sm font-semibold whitespace-nowrap shadow-sm transition-all';
                activeCategory = btn.getAttribute('data-category');
                filterCards();
            });
        });
    });
</script>
@endpush
