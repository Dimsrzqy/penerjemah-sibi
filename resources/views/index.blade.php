@extends('layouts.app')

@section('title', 'SIBI Learn - Platform Belajar Bahasa Isyarat & K-Suli')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
<div class="space-y-20 md:space-y-28">
    <!-- Hero Section -->
    <section class="flex flex-col md:flex-row items-center gap-10 md:gap-14 mt-4 md:mt-8">
        <div class="flex-1 space-y-6">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[46px] font-extrabold text-on-surface leading-tight tracking-tight">
                Belajar Bahasa Isyarat Kini Lebih Mudah dan Menyenangkan
            </h1>
            <p class="text-base sm:text-lg text-on-surface-variant leading-relaxed">
                Mari tingkatkan keakraban dengan teman tuli di sekitar kita. Lewat K-Suli, Anda bisa langsung berlatih bahasa isyarat di depan kamera dan mengetahui apakah gerakan Anda sudah tepat. Belajar mandiri jadi lebih seru dan praktis!
            </p>
            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('penerjemah') }}" class="bg-primary text-on-primary text-sm sm:text-base font-semibold px-7 py-3.5 rounded-full hover:bg-surface-tint active:scale-95 duration-150 transition-all shadow-md inline-block">
                    Coba Penerjemah
                </a>
                <a href="{{ route('quiz') }}" class="bg-surface-container-highest text-primary text-sm sm:text-base font-semibold px-7 py-3.5 rounded-full hover:bg-surface-dim active:scale-95 duration-150 transition-all border border-primary/20 inline-block">
                    Mulai Kuis
                </a>
            </div>
        </div>
        <div class="flex-1 relative w-full aspect-[4/3] sm:aspect-video rounded-3xl overflow-hidden shadow-xl border border-white/60 group">
            <img class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                src="{{ asset('images/kasulli.webp') }}"
                alt="SIBI Learn Hero Illustration">
        </div>
    </section>

    <!-- About / Tech Explanation -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-14 items-center">
        <div class="rounded-3xl overflow-hidden liquid-glass p-3 shadow-lg border border-white/60">
            <img class="w-full h-auto rounded-2xl shadow-sm"
                src="{{ asset('images/laptop-practice.jpg') }}"
                alt="K-Suli AI Laptop Detection Practice">
        </div>
        <div class="space-y-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-on-surface">Layaknya Teman Berlatih Pribadi Anda</h2>
            <p class="text-base text-on-surface-variant leading-relaxed">
                Sering ragu apakah gerakan tangan Anda sudah benar? Cukup nyalakan kamera, dan biarkan sistem kami membimbing Anda. K-Suli akan langsung membaca peragaan Anda dan mencocokkannya dengan kosakata yang tepat. Belajar jadi lebih percaya diri tanpa perlu repot mengunduh aplikasi tambahan.
            </p>
            <ul class="space-y-3.5 text-base text-on-surface-variant font-medium">
                <li class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-xl" data-icon="check_circle">check_circle</span>
                    <span>Deteksi Secara Langsung</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-xl" data-icon="check_circle">check_circle</span>
                    <span>Berbasis Browser Web</span>
                </li>
                <li class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary text-xl" data-icon="check_circle">check_circle</span>
                    <span>Mudah Digunakan Siapa Saja</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- K-Suli Banner -->
    <section class="relative w-full rounded-3xl overflow-hidden flex flex-col md:flex-row bg-tertiary-container text-on-tertiary-container shadow-2xl">
        <div class="flex-1 p-7 sm:p-10 md:p-12 flex flex-col justify-center space-y-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-white leading-snug">Mari Berkunjung ke Kedai Susu Tuli (K-Suli)</h2>
            <p class="text-sm sm:text-base text-amber-100 opacity-95 leading-relaxed">
                Nikmati suasana hangat di K-Suli, ruang nyaman tempat bertemunya teman tuli dan teman dengar. Jadikan setiap pesanan kopi dan obrolan sebagai momen seru untuk langsung mempraktikkan bahasa isyarat yang baru saja Anda pelajari!
            </p>
        </div>
        <div class="flex-1 relative h-64 md:h-auto min-h-[260px] sm:min-h-[300px]">
            <img class="absolute inset-0 w-full h-full object-cover"
                src="{{ asset('images/kedai-susu-tuli.jpg') }}"
                alt="Kedai Susu Tuli Interior">
        </div>
    </section>

    <!-- Leaderboard -->
    <section class="space-y-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface text-center">Peringkat Teratas</h2>
        <div class="bg-surface-container rounded-3xl p-5 sm:p-8 max-w-3xl mx-auto shadow-lg border border-outline-variant/20">
            <div class="space-y-3.5">
                @php
                    $scores = $leaderboard ?? [];
                @endphp

                @forelse ($scores as $item)
                    @php
                        $guestName = $item->guest->name ?? ('Guest_' . ($item->guest_id ?? $item->quiz_scores_id));
                        $level = max(1, (int)($item->score / 100));
                    @endphp

                    @if ($loop->iteration === 1)
                        <!-- Top 1 -->
                        <div class="flex items-center justify-between p-4 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined gold-rank text-4xl" data-icon="emoji_events">emoji_events</span>
                                <div>
                                    <p class="text-base sm:text-lg font-bold text-on-surface">{{ $guestName }}</p>
                                    <p class="text-xs text-on-surface-variant font-medium">Level {{ $level }}</p>
                                </div>
                            </div>
                            <div class="text-primary font-bold text-base sm:text-lg">{{ number_format($item->score) }} pts</div>
                        </div>
                    @elseif ($loop->iteration === 2)
                        <!-- Top 2 -->
                        <div class="flex items-center justify-between p-4 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined silver-rank text-3xl" data-icon="emoji_events">emoji_events</span>
                                <div>
                                    <p class="text-base sm:text-lg font-semibold text-on-surface">{{ $guestName }}</p>
                                    <p class="text-xs text-on-surface-variant font-medium">Level {{ $level }}</p>
                                </div>
                            </div>
                            <div class="text-primary font-bold text-base sm:text-lg">{{ number_format($item->score) }} pts</div>
                        </div>
                    @elseif ($loop->iteration === 3)
                        <!-- Top 3 -->
                        <div class="flex items-center justify-between p-4 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 shadow-sm hover:-translate-y-0.5 transition-transform">
                            <div class="flex items-center gap-4">
                                <span class="material-symbols-outlined bronze-rank text-3xl" data-icon="emoji_events">emoji_events</span>
                                <div>
                                    <p class="text-base sm:text-lg font-semibold text-on-surface">{{ $guestName }}</p>
                                    <p class="text-xs text-on-surface-variant font-medium">Level {{ $level }}</p>
                                </div>
                            </div>
                            <div class="text-primary font-bold text-base sm:text-lg">{{ number_format($item->score) }} pts</div>
                        </div>
                    @else
                        <!-- Rank 4+ -->
                        <div class="flex items-center justify-between p-3.5 px-5 hover:bg-surface-container-low/60 rounded-xl transition-colors {{ $loop->iteration > 4 ? 'border-t border-outline-variant/20' : '' }}">
                            <div class="flex items-center gap-5">
                                <span class="text-xs sm:text-sm font-bold text-on-surface-variant w-6 text-center">{{ $loop->iteration }}</span>
                                <p class="text-sm sm:text-base font-medium">{{ $guestName }}</p>
                            </div>
                            <div class="text-sm sm:text-base font-semibold text-on-surface">{{ number_format($item->score) }} pts</div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-8 space-y-3">
                        <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto">
                            <span class="material-symbols-outlined text-2xl" data-icon="emoji_events">emoji_events</span>
                        </div>
                        <div class="space-y-1">
                            <p class="text-base font-semibold text-on-surface">Belum ada skor di tabel quiz_scores</p>
                            <p class="text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto">
                                Selesaikan kuis untuk mencatat skor pertama Anda di papan peringkat!
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('quiz') }}" class="bg-primary text-on-primary text-xs sm:text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-surface-tint active:scale-95 transition-all shadow-md inline-block">
                                Mulai Kuis
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/index.js') }}"></script>
@endpush