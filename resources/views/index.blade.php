@extends('layouts.app')

@section('title', 'Isyarat K-SULI - Belajar Bahasa Isyarat SIBI')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endpush

@section('content')
<div class="-mx-3.5 -mt-5 space-y-0 sm:-mx-6 sm:-mt-6 md:-mx-10">

    <!-- ============================================================
         1. HERO SECTION (Stitch: Platform Pembelajaran SIBI Interaktif)
         ============================================================ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 lg:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            <!-- Kolom Kiri: Headline & Value Proposition -->
            <div class="lg:col-span-7 flex flex-col items-start text-left">
                <!-- Badge Sensor AI -->
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold px-3.5 py-1.5 mb-5 border border-blue-100 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span>Webcam SIBI AI Engine</span>
                    <span class="text-blue-300">•</span>
                    <span class="text-amber-700 font-bold">K-Suli Official</span>
                </div>

                <!-- Headline Utama -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight tracking-tight">
                    Belajar SIBI Langsung Praktik, <br class="hidden sm:inline">
                    <span class="text-blue-600">Pesan Susu Pakai Isyarat.</span>
                </h1>

                <!-- Deskripsi -->
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-4 max-w-xl">
                    Kuasai kosakata Sistem Isyarat Bahasa Indonesia secara langsung lewat panduan AI webcam, dan pesan susu favoritmu langsung di kasir Kedai Susu K-Suli bersama kawan barista Tuli dengan percaya diri.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 mt-8 w-full sm:w-auto">
                    <a class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md transition-all active:scale-95 min-h-[48px]" href="{{ route('penerjemah') }}">
                        <span class="material-symbols-outlined text-[20px]">videocam</span>
                        <span>Mulai Latihan Kamera</span>
                    </a>
                    <a class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 shadow-sm transition-all active:scale-95 min-h-[48px]" href="{{ route('kamus') }}">
                        <span class="material-symbols-outlined text-[20px] text-blue-600">menu_book</span>
                        <span>Jelajahi Kamus</span>
                    </a>
                </div>

                <!-- Small Highlights / Metrics -->
                <div class="grid grid-cols-3 gap-4 sm:gap-6 pt-8 mt-8 border-t border-slate-200/80 w-full max-w-lg">
                    <div>
                        <span class="block text-xl sm:text-2xl font-extrabold text-slate-900">21 Titik</span>
                        <span class="text-xs text-slate-500 font-medium">Landmark Jari Akurat</span>
                    </div>
                    <div>
                        <span class="block text-xl sm:text-2xl font-extrabold text-blue-600">&lt;120 ms</span>
                        <span class="text-xs text-slate-500 font-medium">Respon Deteksi Cepat</span>
                    </div>
                    <div>
                        <span class="block text-xl sm:text-2xl font-extrabold text-amber-600">Poin Belajar</span>
                        <span class="text-xs text-slate-500 font-medium">Kumpulkan Skor &amp; Badge</span>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Card Kamera Interaktif (Stitch Screen Showcase) -->
            <div class="lg:col-span-5 w-full">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-md overflow-hidden flex flex-col transition-all hover:shadow-lg">
                    <!-- Header Frame Webcam -->
                    <div class="px-5 py-3.5 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                            <span class="text-xs font-semibold text-slate-600 ml-1.5">Preview Sensor Kamera SIBI</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            AKTIF
                        </span>
                    </div>

                    <!-- Area Visual Latihan dengan Video Demo -->
                    <div class="relative bg-slate-950 p-6 sm:p-8 flex flex-col items-center justify-between min-h-[360px] text-white overflow-hidden rounded-b-none">
                        <!-- Video Demo SIBI "Terima Kasih" -->
                        <video id="heroDemoVideo" autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover object-center filter brightness-95">
                            <source src="{{ asset('videos/terimakasih.mp4') }}" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video.
                        </video>

                        <!-- Gradient Vignette Overlay untuk Keterbacaan Kontras Tinggi -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/50 to-slate-900/60 pointer-events-none"></div>

                        <!-- Scanning Radar Grid Accent -->
                        <div class="absolute inset-0 pointer-events-none opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>

                        <!-- Center HUD: Target Gestur & Deteksi (Diposisikan Lebih ke Atas) -->
                        <div class="relative z-10 flex flex-col items-center text-center my-auto pt-3 pb-2 -translate-y-2">
                            <span class="text-[10px] uppercase tracking-widest text-blue-200 font-extrabold bg-blue-950/80 px-3 py-1 rounded-full border border-blue-800/80 shadow-xs backdrop-blur-md">
                                Target Gestur:
                            </span>
                            <h3 class="text-2xl lg:text-3xl font-extrabold text-white mt-2 tracking-tight drop-shadow-md">
                                "TERIMA KASIH"
                            </h3>
                            <p class="text-xs text-slate-200 mt-1.5 max-w-xs leading-relaxed drop-shadow-sm font-medium">
                                Arahkan tangan kanan terbuka ke arah dagu lalu gerakkan lembut ke depan.
                            </p>

                            <!-- Realtime Detection Feedback Badge -->
                            <div class="mt-4 flex items-center gap-3 bg-slate-900/90 border border-slate-700/80 px-4 py-2 rounded-xl text-xs backdrop-blur-md shadow-lg">
                                <span class="text-emerald-400 font-bold flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm font-bold">check_circle</span> Terdeteksi (98%)
                                </span>
                                <span class="text-slate-500">|</span>
                                <span class="text-slate-200 font-medium">Level: Pemula</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Card -->
                    <div class="p-4 bg-white flex items-center justify-between text-xs text-slate-600 border-t border-slate-100">
                        <span class="flex items-center gap-1.5 font-medium">
                            <span class="material-symbols-outlined text-sm text-slate-400">info</span>
                            Kompatibel dengan Webcam Laptop &amp; Kamera HP
                        </span>
                        <a href="{{ route('penerjemah') }}" class="font-bold text-blue-600 hover:text-blue-700 transition-colors inline-flex items-center gap-1">
                            <span>Siap Latihan</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ============================================================
         2. FITUR ESENSIAL & TERARAH (3 Feature Cards)
         ============================================================ -->
    <section class="py-16 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                    Fitur Esensial &amp; Terarah
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3.5">
                    Belajar Bahasa Isyarat Lebih Praktis &amp; Terukur
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Metode interaktif yang menyatukan teknologi deteksi cerdas dengan penerapan nyata di ruang publik.
                </p>
            </div>

            <!-- 3 Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1: Deteksi Kamera -->
                <div class="bg-[#f8faff] rounded-2xl p-7 border border-slate-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-5">
                            <span class="material-symbols-outlined text-2xl">videocam</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Deteksi Gestur Kamera Realtime</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Algoritma pelacak tangan cerdas mengenali 21 titik sendi jari secara presisi dengan respons instan tanpa jeda saat kamu berlatih di depan webcam.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center justify-between text-xs font-semibold text-blue-600">
                        <span>Kecepatan deteksi &lt;120ms</span>
                        <a href="{{ route('penerjemah') }}" class="hover:underline inline-flex items-center gap-1">Buka <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                    </div>
                </div>

                <!-- Card 2: Kamus SIBI -->
                <div class="bg-[#f8faff] rounded-2xl p-7 border border-slate-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center mb-5">
                            <span class="material-symbols-outlined text-2xl">menu_book</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Kamus Kosakata SIBI</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Akses perbendaharaan kosakata terstruktur mulai dari abjad alfabetis, angka, sapaan kesopanan, hingga menu pesanan minuman kedai secara visual.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center justify-between text-xs font-semibold text-teal-700">
                        <span>Dilengkapi panduan mimik ekspresi</span>
                        <a href="{{ route('kamus') }}" class="hover:underline inline-flex items-center gap-1">Jelajahi <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                    </div>
                </div>

                <!-- Card 3: Kuis & Poin Nilai -->
                <div class="bg-[#f8faff] rounded-2xl p-7 border border-slate-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-5">
                            <span class="material-symbols-outlined text-2xl">military_tech</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Kuis &amp; Poin Nilai</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Selesaikan kuis tantangan 15 detik, uji ketepatan gerakan jarimu, dan kumpulkan akumulasi poin nilai untuk mengukur kemahiran SIBI.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-200/80 flex items-center justify-between text-xs font-semibold text-amber-700">
                        <span>Kumpulkan poin nilai tertinggi</span>
                        <a href="{{ route('quiz') }}" class="hover:underline inline-flex items-center gap-1">Ikuti Kuis <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         3. ALUR 3 LANGKAH PRAKTIS
         ============================================================ -->
    <section class="py-16 lg:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-600 bg-slate-100 px-3.5 py-1.5 rounded-full border border-slate-200">
                Alur Mudah
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3.5">
                3 Langkah Praktis Mulai Menguasai SIBI
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mt-2">
                Langsung coba dari perangkatmu kapan saja tanpa proses registrasi yang membingungkan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Langkah 1 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow-sm">1</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Pelajari Kosakata di Kamus</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Buka Kamus SIBI untuk memahami bentuk tangan, mimik wajah, dan arti kosakata—mulai dari abjad dasar hingga sapaan pemesanan di kedai.
                </p>
            </div>

            <!-- Langkah 2 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow-sm">2</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Coba Praktik di Kamera AI</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Uji kelenturan gerakan tanganmu langsung di depan webcam. AI akan melacak 21 titik sendi jari secara realtime dan memberikan panduan koreksi instan.
                </p>
            </div>

            <!-- Langkah 3 -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-full bg-amber-500 text-white font-extrabold text-lg flex items-center justify-center mb-4 shadow-sm">3</div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Ikuti Kuis &amp; Dapatkan Skor</h3>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Tantang ketangkasanmu lewat kuis interaktif berbatas waktu, raih skor akurasi tertinggi, dan kumpulkan poin nilai belajar SIBI!
                </p>
            </div>
        </div>
    </section>

    <!-- ============================================================
         4. CARA KERJA KUIS GESTUR SIBI
         ============================================================ -->
    <section class="py-16 lg:py-20 bg-white border-t border-slate-200" id="cara-kuis">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-100">
                    Sistem Kuis Interaktif
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3.5">
                    Cara Kerja Kuis Gestur SIBI
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Uji ketangkasan dan akurasi isyaratmu langsung melalui kamera dalam format kuis interaktif tanpa registrasi rumit.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Langkah Kuis Alur Kiri -->
                <div class="lg:col-span-7 flex flex-col gap-5">
                    <!-- Card Langkah 1 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-[#f8faff] border border-slate-200 flex items-start gap-4 transition-all hover:shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">1</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-base font-bold text-slate-900">Pilih Tingkat Kesulitan</h3>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100">Personalisasi</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Pilih mode tantangan sesuai kemampuan: Pemula (kata dasar &amp; mimik), Menengah (kalimat sapaan), atau Mahir (percakapan cepat di kasir).
                            </p>
                        </div>
                    </div>

                    <!-- Card Langkah 2 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-[#f8faff] border border-slate-200 flex items-start gap-4 transition-all hover:shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-base shrink-0">2</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-base font-bold text-slate-900">Muncul Kosakata &amp; Timer 15 Detik</h3>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100">Tantangan</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Sistem menampilkan kartu tantangan kata acak, misalnya <em>"Terima Kasih"</em> atau <em>"Susu Cokelat"</em>, dengan hitungan mundur 15 detik yang interaktif.
                            </p>
                        </div>
                    </div>

                    <!-- Card Langkah 3 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-[#f8faff] border border-slate-200 flex items-start gap-4 transition-all hover:shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-base shrink-0">3</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-base font-bold text-slate-900">Peragakan di Depan Kamera</h3>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Sensor Aktif</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Pengguna memperagakan gestur tangan SIBI ke webcam. AI memindai 21 titik koordinat persendian jari secara instan tanpa merekam wajah demi privasi aman.
                            </p>
                        </div>
                    </div>

                    <!-- Card Langkah 4 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-[#f8faff] border border-slate-200 flex items-start gap-4 transition-all hover:shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-base shrink-0">4</div>
                        <div class="flex-grow">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-base font-bold text-slate-900">Skor Akurasi &amp; Poin Nilai</h3>
                                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200">Poin &amp; Nilai</span>
                            </div>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Jika akurasi mencapai &gt;85%, sistem otomatis memberikan poin nilai penuh dan mencatat skor kemahiran ke dalam papan evaluasi latihan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Simulasi Kartu Kuis Mini -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-md p-6 relative overflow-hidden transition-all hover:shadow-lg">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                                <span class="text-xs font-bold text-slate-700 uppercase tracking-wide">Simulasi Kuis Langsung</span>
                            </div>
                            <div class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-sm">timer</span> 00:11
                            </div>
                        </div>

                        <div class="bg-[#f8faff] border border-slate-200 rounded-xl p-6 text-center mb-5">
                            <span class="text-[11px] font-bold tracking-wider uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                Tantangan Aktif
                            </span>
                            <h4 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-3 mb-1 tracking-tight">"TERIMA KASIH"</h4>
                            <p class="text-xs text-slate-500 font-medium">Kategori: Sapaan Sopan &amp; Kasir</p>

                            <!-- Mini Landmark Tracker Visual -->
                            <div class="mt-5 py-3 px-4 bg-white border border-slate-200 rounded-lg flex items-center justify-between text-xs shadow-xs">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-lg">fingerprint</span>
                                    <span class="font-medium text-slate-700">21 Titik Sendi</span>
                                </div>
                                <span class="text-emerald-700 font-bold bg-emerald-50 px-2.5 py-0.5 rounded border border-emerald-200">Akurasi 94%</span>
                            </div>
                        </div>

                        <!-- Reward Card Trigger -->
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex items-center justify-between hover:bg-blue-100/50 transition-colors">
                            <div class="flex items-center gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                    <span class="material-symbols-outlined text-xl">grade</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900">Hasil Evaluasi: Nilai A (Akurasi 94%)</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Perolehan: <span class="font-mono font-bold text-blue-600">+150 Poin Nilai Latihan</span></p>
                                </div>
                            </div>
                            <a href="{{ route('quiz') }}" class="text-xs bg-white text-blue-700 font-bold px-3 py-1.5 rounded-full border border-blue-200 shadow-xs hover:bg-blue-50 transition-all">
                                Mulai Kuis
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================================
         5. PAPAN PERINGKAT & KLASEMEN (Top 3 Podium + Challenger List)
         ============================================================ -->
    <section class="py-16 lg:py-20 bg-[#f8faff] border-t border-slate-200" id="peringkat">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-100 px-3.5 py-1.5 rounded-full border border-amber-200 shadow-xs">
                    <span class="material-symbols-outlined text-sm">emoji_events</span>
                    <span>Klasemen Latihan Mingguan</span>
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 mt-3.5 tracking-tight">
                    Papan Peringkat Sahabat Isyarat
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2">
                    Apresiasi nyata untuk teman-teman yang aktif belajar SIBI. Kuasai kata lebih banyak dan pantau perolehan akumulasi poin nilai tertinggi!
                </p>

                @php
                    $leaderboardList = collect($leaderboard ?? []);
                    $top1 = $leaderboardList->get(0);
                    $top2 = $leaderboardList->get(1);
                    $top3 = $leaderboardList->get(2);
                    $remaining = $leaderboardList->slice(3);
                @endphp

                @if ($leaderboardList->count() > 0)
                <div class="mt-4 flex items-center justify-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3.5 py-1 rounded-full border border-emerald-200 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Terhubung ke Database: <strong>{{ $leaderboardList->count() }} Pemain Tercatat</strong></span>
                    </span>
                </div>
                @endif
            </div>

            @if ($leaderboardList->count() > 0)
                <!-- ================= TOP 3 PODIUM CARDS ================= -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7 items-end mb-10 pt-4">
                    
                    <!-- JUARA 2 (Silver) - Left on desktop -->
                    @if ($top2)
                    @php 
                        $name2 = !empty($top2->guest?->name) ? $top2->guest->name : 'Guest_' . $top2->guest_id; 
                        $time2 = $top2->created_at ? $top2->created_at->diffForHumans() : 'Sesi Kuis SIBI';
                    @endphp
                    <div class="order-2 md:order-1 relative rounded-3xl p-6 border border-slate-200 bg-gradient-to-b from-slate-100/70 via-white to-white shadow-md hover:shadow-xl transition-all flex flex-col items-center text-center">
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-slate-600 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">military_tech</span> Peringkat 2
                        </div>
                        
                        <div class="relative mt-2 mb-3">
                            <div class="w-16 h-16 rounded-full bg-slate-200 p-1 shadow-sm flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-slate-700 font-extrabold text-xl">
                                    {{ strtoupper(substr($name2, 0, 1)) }}
                                </div>
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-slate-600 text-white flex items-center justify-center text-xs font-black border-2 border-white shadow-xs">2</span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base sm:text-lg truncate max-w-full">{{ $name2 }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $time2 }}</p>

                        <div class="mt-3.5 px-4 py-2 rounded-2xl bg-slate-100/80 border border-slate-200 w-full">
                            <span class="block text-xl font-black text-slate-800">{{ number_format($top2->score) }}</span>
                            <span class="text-[10px] font-bold text-slate-500 tracking-wide uppercase">Total Poin</span>
                        </div>

                        <span class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300 shadow-2xs">
                            <span class="material-symbols-outlined text-sm text-slate-500">workspace_premium</span> Nilai A (Perak)
                        </span>
                    </div>
                    @endif

                    <!-- JUARA 1 (Gold Champion) - Center & Elevated on desktop -->
                    @if ($top1)
                    @php 
                        $name1 = !empty($top1->guest?->name) ? $top1->guest->name : 'Guest_' . $top1->guest_id; 
                        $time1 = $top1->created_at ? $top1->created_at->diffForHumans() : 'Skor Tertinggi';
                    @endphp
                    <div class="order-1 md:order-2 md:-mt-6 relative rounded-3xl p-7 border-2 border-amber-400 bg-gradient-to-b from-amber-400/20 via-amber-100/20 to-white shadow-xl hover:shadow-2xl transition-all flex flex-col items-center text-center">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-gradient-to-r from-amber-500 via-amber-400 to-amber-600 text-amber-950 text-[11px] font-black uppercase tracking-wider shadow-md flex items-center gap-1.5 border border-amber-300">
                            <span class="material-symbols-outlined text-sm text-amber-900">emoji_events</span> JUARA 1
                        </div>

                        <div class="relative mt-2 mb-3.5">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-amber-400 via-amber-300 to-amber-500 p-1.5 shadow-lg flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-amber-800 font-black text-2xl">
                                    {{ strtoupper(substr($name1, 0, 1)) }}
                                </div>
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-black border-2 border-white shadow-md">1</span>
                        </div>

                        <h3 class="font-extrabold text-slate-900 text-lg sm:text-xl truncate max-w-full">{{ $name1 }}</h3>
                        <p class="text-xs font-semibold text-amber-800 mt-0.5">{{ $time1 }}</p>

                        <div class="mt-4 px-4 py-2.5 rounded-2xl bg-amber-500/15 border border-amber-300/80 w-full shadow-xs">
                            <span class="block text-2xl sm:text-3xl font-black text-amber-950 font-mono">{{ number_format($top1->score) }}</span>
                            <span class="text-[10px] font-extrabold text-amber-800 tracking-wide uppercase">Poin Tertinggi</span>
                        </div>

                        <span class="mt-3.5 inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-black bg-gradient-to-r from-amber-400 to-amber-300 text-amber-950 border border-amber-400 shadow-xs">
                            <span class="material-symbols-outlined text-sm text-amber-900">crown</span> Nilai A+ (Emas)
                        </span>
                    </div>
                    @endif

                    <!-- JUARA 3 (Bronze) - Right on desktop -->
                    @if ($top3)
                    @php 
                        $name3 = !empty($top3->guest?->name) ? $top3->guest->name : 'Guest_' . $top3->guest_id; 
                        $time3 = $top3->created_at ? $top3->created_at->diffForHumans() : 'Sesi Kuis SIBI';
                    @endphp
                    <div class="order-3 relative rounded-3xl p-6 border border-amber-300/60 bg-gradient-to-b from-amber-50/70 via-white to-white shadow-md hover:shadow-xl transition-all flex flex-col items-center text-center">
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-3.5 py-0.5 rounded-full bg-amber-800 text-white text-[10px] font-bold uppercase tracking-wider shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">military_tech</span> Peringkat 3
                        </div>

                        <div class="relative mt-2 mb-3">
                            <div class="w-16 h-16 rounded-full bg-amber-100 p-1 shadow-sm flex items-center justify-center">
                                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-amber-900 font-extrabold text-xl">
                                    {{ strtoupper(substr($name3, 0, 1)) }}
                                </div>
                            </div>
                            <span class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-amber-800 text-white flex items-center justify-center text-xs font-black border-2 border-white shadow-xs">3</span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-base sm:text-lg truncate max-w-full">{{ $name3 }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $time3 }}</p>

                        <div class="mt-3.5 px-4 py-2 rounded-2xl bg-amber-50/80 border border-amber-200/80 w-full">
                            <span class="block text-xl font-black text-amber-900 font-mono">{{ number_format($top3->score) }}</span>
                            <span class="text-[10px] font-bold text-amber-700 tracking-wide uppercase">Total Poin</span>
                        </div>

                        <span class="mt-3 inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-2xs">
                            <span class="material-symbols-outlined text-sm text-amber-700">military_tech</span> Nilai A- (Perunggu)
                        </span>
                    </div>
                    @endif

                </div>

                <!-- ================= DAFTAR PENANTANG (Rank 4+) ================= -->
                @if ($remaining->count() > 0)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/80 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-blue-600 text-lg">format_list_numbered</span>
                            <span class="font-bold text-sm text-slate-800">Daftar Penantang &amp; Posisi Berikutnya</span>
                        </div>
                        <span class="text-xs text-slate-500 font-medium">{{ $remaining->count() }} Sahabat Berkompetisi</span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($remaining as $item)
                        @php
                            $guestName = !empty($item->guest?->name) ? $item->guest->name : 'Guest_' . $item->guest_id;
                            $timeItem = $item->created_at ? $item->created_at->diffForHumans() : 'Sesi Kuis SIBI';
                            $rankNumber = $loop->iteration + 3;
                        @endphp
                        <div class="p-4 sm:px-6 flex items-center justify-between gap-4 hover:bg-blue-50/40 hover:pl-7 transition-all duration-200">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 font-extrabold text-xs flex items-center justify-center border border-slate-200 shrink-0 shadow-2xs">
                                    #{{ sprintf('%02d', $rankNumber) }}
                                </span>
                                
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-blue-500 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                                    {{ strtoupper(substr($guestName, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 truncate leading-snug">{{ $guestName }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $timeItem }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                                <div class="text-right">
                                    <span class="text-sm sm:text-base font-black text-slate-900 block font-mono">{{ number_format($item->score) }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Poin</span>
                                </div>
                                <span class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="material-symbols-outlined text-xs">verified</span>
                                    Terverifikasi
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            @else
                <!-- EMPTY STATE -->
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm max-w-xl mx-auto">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-4xl">emoji_events</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Belum ada skor kuis tercatat</h3>
                    <p class="text-sm text-slate-500 mt-1">Jadilah orang pertama yang menyelesaikan kuis dan menempati puncak klasemen!</p>
                    <a href="{{ route('quiz') }}" class="mt-5 inline-flex items-center gap-2 px-6 py-3 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold shadow-md transition-all active:scale-95">
                        <span class="material-symbols-outlined text-base">play_arrow</span>
                        <span>Mulai Kuis Sekarang</span>
                    </a>
                </div>
            @endif

            <!-- ================= GAMIFICATION ENCOURAGEMENT CTA ================= -->
            <div class="mt-10 sm:mt-12 rounded-3xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 p-6 sm:p-8 text-white shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4 text-center sm:text-left">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 text-white shadow-inner">
                        <span class="material-symbols-outlined text-3xl">sports_esports</span>
                    </div>
                    <div>
                        <h3 class="text-lg sm:text-xl font-extrabold tracking-tight">Ingin Namamu Masuk Papan Peringkat?</h3>
                        <p class="text-xs sm:text-sm text-blue-100 mt-1 max-w-lg leading-relaxed">
                            Uji ketangkasan gerakan tanganmu di kuis 15 detik, kumpulkan skor akurasi tertinggi, dan jadilah Sahabat Isyarat nomor satu!
                        </p>
                    </div>
                </div>
                <a href="{{ route('quiz') }}" class="shrink-0 inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-white text-blue-700 font-extrabold text-sm shadow-md hover:bg-amber-100 hover:text-amber-900 transition-all active:scale-95 min-h-[48px]">
                    <span class="material-symbols-outlined text-xl">play_arrow</span>
                    <span>Mulai Kuis &amp; Cetak Skor</span>
                </a>
            </div>

        </div>
    </section>

    <!-- ============================================================
         6. LOKASI FISIK KAFE KASULI (Kedai Susu Tuli)
         ============================================================ -->
    <section class="bg-white px-4 py-16 sm:px-6 lg:px-8 border-t border-slate-200" id="lokasi-kasuli">
        <div class="mx-auto max-w-7xl">
            <div class="grid grid-cols-1 gap-8 rounded-3xl bg-[#f8faff] border border-slate-200 p-6 sm:p-8 lg:grid-cols-12 lg:items-center lg:gap-10">
                <div class="space-y-4 lg:col-span-7">
                    <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                        <span class="material-symbols-outlined text-base">storefront</span>
                        <span>Kedai Nyata Ramah Isyarat</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight">
                        Ngopi Santai Sambil Belajar Isyarat Langsung di Kafe Kasuli
                    </h2>
                    <p class="text-sm sm:text-base leading-relaxed text-slate-600">
                        Jl. Manggis No. 95, Krajan, Jemberlor, Kec. Patrang, Kabupaten Jember, Jawa Timur 68118. Datang langsung dan praktikkan kosakata SIBI bersama kawan barista Tuli kami!
                    </p>

                    <div class="grid gap-3 pt-2 sm:grid-cols-2">
                        <div class="flex items-center gap-3 rounded-xl bg-white border border-slate-200 p-3.5 shadow-xs">
                            <span class="material-symbols-outlined text-2xl text-blue-600">schedule</span>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Jam Operasional</p>
                                <p class="text-sm font-bold text-slate-900">Setiap Hari 08:00 - 21:00</p>
                            </div>
                        </div>
                        <a href="mailto:halo@kafekasuli.id" class="flex items-center gap-3 rounded-xl bg-white border border-slate-200 p-3.5 shadow-xs transition-colors hover:border-blue-300">
                            <span class="material-symbols-outlined text-2xl text-blue-600">mail</span>
                            <div>
                                <p class="text-xs text-slate-500 font-medium">Email &amp; Kolaborasi</p>
                                <p class="text-sm font-bold text-slate-900">halo@kafekasuli.id</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl overflow-hidden border border-slate-200 shadow-sm lg:col-span-5">
                    <iframe
                        class="aspect-[4/3] w-full border-0 sm:aspect-video"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3080.3465049241577!2d113.7049635737323!3d-8.15977288174995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695fab97f52b3%3A0x797ab6971af1cce8!2sK-SULI!5e1!3m2!1sid!2sid!4v1792154915929!5m2!1sid!2sid"
                        title="Peta lokasi K-SULI"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="strict-origin-when-cross-origin"></iframe>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/index.js') }}"></script>
@endpush