@extends('layouts.app')

@section('title', 'SIBI Learn - Platform Belajar Bahasa Isyarat & K-Suli')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
<div class="-mx-3.5 -mt-5 space-y-0 sm:-mx-6 sm:-mt-6 md:-mx-10">
    <section data-parallax-hero class="relative isolate flex min-h-[560px] items-center justify-center overflow-hidden bg-inverse-surface px-4 py-14 sm:min-h-[640px] sm:px-6 sm:py-16">
        <img
            data-parallax-image
            class="hero-parallax-image absolute inset-0 -z-20 h-full w-full object-cover"
            src="{{ asset('images/kedai-susu-tuli.jpg') }}"
            alt=""
            fetchpriority="high">
        <div class="absolute inset-0 -z-10 bg-inverse-surface/75" aria-hidden="true"></div>

        <div class="mx-auto w-full max-w-3xl text-center text-inverse-on-surface">
            <p class="mx-auto inline-flex items-center gap-2 rounded-full border border-white/20 bg-inverse-surface/50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wide text-tertiary-fixed sm:text-xs">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">sign_language</span>
                Sistem Isyarat Bahasa Indonesia (SIBI) · Kafe Kasuli
            </p>
            <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight sm:text-4xl md:text-5xl">
                Belajar Bahasa Isyarat SIBI
            </h1>
            <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-inverse-on-surface/90 sm:text-base">
                Belajar kosakata dan komunikasi visual bersama Kafe Kasuli. Temukan isyarat yang ingin Anda pelajari melalui kamus.
            </p>

            <form action="{{ route('kamus') }}" method="GET" role="search" class="mx-auto mt-7 flex w-full max-w-2xl flex-col gap-2 rounded-2xl border border-white/20 bg-inverse-surface/70 p-2 shadow-lg backdrop-blur-sm sm:flex-row sm:items-center sm:rounded-full sm:pl-4">
                <label for="homeSearch" class="sr-only">Cari kosakata SIBI</label>
                <span class="material-symbols-outlined hidden shrink-0 text-xl text-inverse-on-surface/70 sm:block" aria-hidden="true">search</span>
                <input
                    id="homeSearch"
                    name="q"
                    type="search"
                    placeholder="Cari kosakata isyarat SIBI..."
                    class="min-h-11 min-w-0 flex-1 border-0 bg-transparent px-3 text-sm text-inverse-on-surface placeholder:text-inverse-on-surface/70 focus:ring-0 sm:px-1">
                <button type="submit" class="inline-flex min-h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-semibold text-on-primary transition-colors hover:bg-surface-tint focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white sm:rounded-full">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">search</span>
                    Cari Isyarat
                </button>
            </form>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-inverse-on-surface/80">
                <span class="mr-1">Contoh pencarian:</span>
                @foreach (['Terima Kasih', 'Belajar', 'Makan', 'Rumah'] as $word)
                    <a href="{{ route('kamus', ['q' => $word]) }}" class="inline-flex min-h-9 items-center rounded-full border border-white/20 bg-inverse-surface/40 px-3 font-medium transition-colors hover:bg-inverse-surface/70 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        {{ $word }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-surface-container-low px-3.5 py-10 sm:px-6 sm:py-14 md:px-10">
        <div class="mx-auto max-w-7xl">
            <div id="tentang-kasuli" class="mb-6 max-w-3xl space-y-2 sm:mb-8">
                <p class="text-sm font-semibold text-primary">Komunitas dan kafe inklusif</p>
                <h2 class="text-2xl font-bold leading-tight text-on-surface sm:text-3xl">Tentang Kafe Kasuli</h2>
                <p class="text-sm leading-relaxed text-on-surface-variant sm:text-base">
                    Kafe Kasuli hadir sebagai ruang untuk mengenal bahasa isyarat dan membangun komunikasi yang lebih akrab antara teman tuli dan teman dengar.
                </p>
            </div>
            <div class="grid grid-cols-1 items-stretch gap-4 lg:grid-cols-12 lg:gap-5">
                <figure class="overflow-hidden rounded-2xl bg-surface-container-lowest lg:col-span-7">
                    <img
                        class="aspect-[16/10] w-full object-cover sm:aspect-[16/9]"
                        src="{{ asset('images/kedai-susu-tuli.jpg') }}"
                        alt="Suasana kebersamaan di kedai ramah bahasa isyarat">
                    <figcaption class="flex items-center gap-3 px-4 py-3 text-sm text-on-surface-variant sm:px-5">
                        <span class="material-symbols-outlined text-xl text-primary" aria-hidden="true">local_cafe</span>
                        Ruang untuk bertemu, belajar, dan berkomunikasi.
                    </figcaption>
                </figure>
                <div class="grid gap-3 lg:col-span-5">
                    <article class="rounded-xl bg-surface-container-lowest p-4 sm:p-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-xl text-primary" aria-hidden="true">volunteer_activism</span>
                            <div>
                                <h3 class="font-bold text-on-surface">Inklusif</h3>
                                <p class="text-sm text-primary">Ruang terbuka untuk semua</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-on-surface-variant">Mengenal bahasa isyarat membantu membuka ruang komunikasi antara teman tuli dan teman dengar.</p>
                    </article>
                    <article class="rounded-xl bg-surface-container-lowest p-4 sm:p-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-tertiary-fixed text-xl text-on-tertiary-fixed" aria-hidden="true">groups</span>
                            <div>
                                <h3 class="font-bold text-on-surface">Belajar bersama</h3>
                                <p class="text-sm text-primary">Latihan percakapan sehari-hari</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-on-surface-variant">Mulai dari kosakata sederhana dan gunakan dalam percakapan sehari-hari.</p>
                    </article>
                    <article class="rounded-xl bg-surface-container-lowest p-4 sm:p-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-surface-container text-xl text-primary" aria-hidden="true">hearing_disabled</span>
                            <div>
                                <h3 class="font-bold text-on-surface">Ramah teman tuli</h3>
                                <p class="text-sm text-primary">Komunikasi melalui visual</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-on-surface-variant">Materi SIBI dan latihan pada platform ini menggunakan panduan visual.</p>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-background px-3.5 py-10 sm:px-6 sm:py-14 md:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto mb-7 flex max-w-2xl flex-col items-center gap-2 text-center sm:mb-9">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-primary">
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">model_training</span>
                    Modul Terintegrasi
                </span>
                <h2 class="text-xl font-bold text-on-surface sm:text-2xl">Fitur Unggulan Pembelajaran SIBI</h2>
                <p class="text-xs leading-relaxed text-on-surface-variant sm:text-sm">
                    Tiga modul utama yang dirancang terstruktur dan mudah dipelajari siapa saja, dari pemula hingga fasih berisyarat.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3 md:gap-5">
                <article class="feature-card flex flex-col rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-5 sm:p-6">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-2xl" aria-hidden="true">videocam</span>
                    </span>
                    <h3 class="text-lg font-bold text-on-surface">Penerjemah</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-on-surface-variant">
                        Ubah teks menjadi panduan visual isyarat dan abjad jari SIBI langsung di halaman penerjemah.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-on-surface-variant">
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Panduan visual</span>
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Kontrol tempo</span>
                    </div>
                    <a href="{{ route('penerjemah') }}" class="mt-5 inline-flex min-h-11 items-center gap-2 font-semibold text-primary hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                        Buka Penerjemah
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </article>

                <article class="feature-card flex flex-col rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-5 sm:p-6">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-tertiary-fixed text-on-tertiary-fixed">
                        <span class="material-symbols-outlined text-2xl" aria-hidden="true">menu_book</span>
                    </span>
                    <h3 class="text-lg font-bold text-on-surface">Kamus SIBI</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-on-surface-variant">
                        Telusuri kosakata SIBI, gunakan pencarian dan kategori, lalu lihat materi yang tersedia.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-on-surface-variant">
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Pencarian kata</span>
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Kategori</span>
                    </div>
                    <a href="{{ route('kamus') }}" class="mt-5 inline-flex min-h-11 items-center gap-2 font-semibold text-primary hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                        Jelajahi Kamus
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </article>

                <article class="feature-card flex flex-col rounded-2xl border border-outline-variant/30 bg-surface-container-lowest p-5 sm:p-6">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-2xl" aria-hidden="true">emoji_events</span>
                    </span>
                    <h3 class="text-lg font-bold text-on-surface">Kuis</h3>
                    <p class="mt-2 flex-1 text-sm leading-relaxed text-on-surface-variant">
                        Peragakan isyarat sesuai tantangan dan lihat skor yang tercatat dari sesi latihan.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2 text-xs text-on-surface-variant">
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Kuis visual</span>
                        <span class="rounded-md bg-surface-container px-2.5 py-1.5">Papan peringkat</span>
                    </div>
                    <a href="{{ route('quiz') }}" class="mt-5 inline-flex min-h-11 items-center gap-2 font-semibold text-primary hover:underline focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                        Ikuti Kuis
                        <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-surface-container-low px-3.5 py-10 sm:px-6 sm:py-14 md:px-10">
        <div class="mx-auto max-w-7xl">
            <div class="mb-6 flex flex-col gap-4 sm:mb-8 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex items-start gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-2xl" aria-hidden="true">leaderboard</span>
                    </span>
                    <div class="space-y-1.5">
                        <p class="text-sm font-semibold text-primary">Papan peringkat</p>
                        <h2 class="text-2xl font-bold text-on-surface sm:text-3xl">Peringkat Skor Sahabat Kasuli</h2>
                        <p class="text-sm text-on-surface-variant sm:text-base">Lihat skor kuis tertinggi dan ikuti tantangannya.</p>
                    </div>
                </div>
                <a href="{{ route('quiz') }}" class="inline-flex min-h-11 w-fit shrink-0 items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-on-primary transition-colors hover:bg-surface-tint focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                    <span class="material-symbols-outlined text-lg" aria-hidden="true">quiz</span>
                    Ikuti Kuis
                </a>
            </div>

            <div class="overflow-hidden rounded-3xl border border-outline-variant/40 bg-surface-container-lowest">
                <div class="flex items-center justify-between gap-3 border-b border-outline-variant/30 px-4 py-4 sm:px-6">
                    <div>
                        <h3 class="font-bold text-on-surface">Skor tertinggi</h3>
                        <p class="mt-0.5 text-xs text-on-surface-variant">Urutan berdasarkan skor kuis</p>
                    </div>
                    @if (count($leaderboard ?? []) > 0)
                        <span class="shrink-0 rounded-lg bg-surface-container px-3 py-1.5 text-xs font-semibold text-on-surface-variant">
                            {{ count($leaderboard) }} skor
                        </span>
                    @endif
                </div>

                <ol class="space-y-2 p-3 sm:p-4">
                    @forelse ($leaderboard ?? [] as $item)
                        @php
                            $guestName = $item->guest?->name ?? 'Pemain';
                            $isTopRank = $loop->iteration === 1;
                        @endphp
                        <li class="flex min-h-16 items-center justify-between gap-3 rounded-2xl px-3 py-3 sm:gap-4 sm:px-4 {{ $isTopRank ? 'bg-primary/5 ring-1 ring-inset ring-primary/15' : 'bg-background' }}">
                            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center gap-0.5 rounded-xl {{ $isTopRank ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant' }}">
                                    @if ($isTopRank)
                                        <span class="material-symbols-outlined text-base" aria-hidden="true">emoji_events</span>
                                    @else
                                        <span class="text-sm font-bold">{{ sprintf('%02d', $loop->iteration) }}</span>
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate font-semibold text-on-surface">{{ $guestName }}</p>
                                    @if ($isTopRank)
                                        <p class="mt-0.5 text-xs font-medium text-primary">Skor tertinggi</p>
                                    @endif
                                </div>
                            </div>
                            <span class="shrink-0 rounded-lg bg-surface-container px-3 py-2 text-sm font-bold text-primary sm:text-base">
                                {{ number_format($item->score) }} <span class="text-xs font-semibold">poin</span>
                            </span>
                        </li>
                    @empty
                        <li class="px-5 py-10 text-center">
                            <span class="material-symbols-outlined text-3xl text-primary" aria-hidden="true">emoji_events</span>
                            <p class="mt-2 font-semibold text-on-surface">Belum ada skor kuis</p>
                            <p class="mt-1 text-sm text-on-surface-variant">Selesaikan kuis untuk mencatat skor pertama Anda.</p>
                        </li>
                    @endforelse
                </ol>
            </div>
        </div>
    </section>

    <section class="bg-background px-3.5 py-10 sm:px-6 sm:py-14 md:px-10">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 rounded-2xl bg-surface-container p-5 sm:p-7 lg:grid-cols-12 lg:items-center lg:gap-8 lg:p-8">
            <div class="space-y-4 lg:col-span-7">
                <p class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-primary">
                    <span class="material-symbols-outlined text-base" aria-hidden="true">pin_drop</span>
                    Kunjungi Ruang Belajar Fisik
                </p>
                <h2 class="text-2xl font-bold leading-tight text-on-surface sm:text-3xl">Ngopi Santai Sambil Belajar Isyarat Langsung di Kafe Kasuli</h2>
                <p class="text-sm leading-relaxed text-on-surface-variant sm:text-base">
                    Jl. Kasuli Inklusif No. 12, Area Belajar &amp; Kedai Ramah Isyarat, Bandung. Terbuka setiap hari untuk umum, kawan tuli, dan komunitas pemelajar.
                </p>

                <div class="grid gap-3 pt-1 sm:grid-cols-2">
                    <div class="flex min-w-0 items-center gap-3 rounded-xl bg-background/70 p-3">
                        <span class="material-symbols-outlined shrink-0 text-xl text-primary" aria-hidden="true">schedule</span>
                        <div class="min-w-0">
                            <p class="text-xs text-on-surface-variant">Jam Operasional</p>
                            <p class="text-sm font-semibold text-on-surface">Setiap Hari 08:00 - 21:00</p>
                        </div>
                    </div>
                    <a href="mailto:halo@kafekasuli.id" class="flex min-h-14 min-w-0 items-center gap-3 rounded-xl bg-background/70 p-3 transition-colors hover:bg-background focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                        <span class="material-symbols-outlined shrink-0 text-xl text-primary" aria-hidden="true">mail</span>
                        <span class="min-w-0">
                            <span class="block text-xs text-on-surface-variant">Email &amp; Kolaborasi</span>
                            <span class="block break-all text-sm font-semibold text-on-surface">halo@kafekasuli.id</span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="rounded-xl bg-background p-5 sm:p-6 lg:col-span-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <span class="material-symbols-outlined text-2xl" aria-hidden="true">chat</span>
                    </span>
                    <div>
                        <h3 class="font-bold text-on-surface">Reservasi Kelas Isyarat</h3>
                        <p class="text-xs text-on-surface-variant">Terhubung dengan Tim Barista Kasuli</p>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-on-surface-variant">
                    Ingin mengadakan workshop komunitas atau belajar privat? Hubungi tim Kafe Kasuli melalui WhatsApp.
                </p>
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-3 text-sm font-semibold text-on-primary transition-colors hover:bg-surface-tint focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                    <span class="material-symbols-outlined text-xl" aria-hidden="true">phone_iphone</span>
                    WhatsApp: 0812-3456-7890
                </a>
                <p class="mt-3 text-center text-xs leading-relaxed text-on-surface-variant">
                    Hubungi kami untuk menanyakan jadwal dan ketersediaan kelas.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/index.js') }}"></script>
@endpush