@extends('layouts.app')

@section('title', 'Penerjemah SIBI - SIBI Learn')

@section('content')
<div class="max-w-5xl mx-auto flex flex-col space-y-8 mt-2 md:mt-6">
    <!-- Header -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl md:text-4xl font-extrabold text-on-surface tracking-tight">Penerjemah SIBI</h1>
        <p class="text-base text-on-surface-variant max-w-xl mx-auto">
            Arahkan kamera ke area pinggang hingga kepala untuk hasil deteksi bahasa isyarat yang optimal.
        </p>
    </div>

    <!-- Camera Feed Area -->
    <div class="relative w-full aspect-video rounded-3xl overflow-hidden liquid-glass bg-inverse-surface flex items-center justify-center shadow-2xl border-4 border-white/40">
        <!-- Live Video Element (Hidden until started) -->
        <video id="webcamVideo" class="absolute inset-0 w-full h-full object-cover hidden" autoplay playsinline muted></video>

        <!-- Placeholder Background -->
        <img id="placeholderImg" class="absolute inset-0 w-full h-full object-cover opacity-40" 
             src="{{ asset('images/laptop-practice.jpg') }}" 
             alt="Camera Feed Background">

        <!-- Status Placeholder UI -->
        <div id="cameraStatusOverlay" class="z-10 flex flex-col items-center gap-3 text-center px-4">
            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center backdrop-blur-md">
                <span class="material-symbols-outlined text-4xl text-white opacity-80" data-icon="videocam_off">videocam_off</span>
            </div>
            <p class="text-lg font-bold text-white">Kamera Belum Aktif</p>
            <p class="text-sm text-gray-300 max-w-sm">Klik tombol <strong>"Start Camera"</strong> di bawah untuk mengaktifkan pendeteksi bahasa isyarat secara langsung.</p>
        </div>

        <!-- Real-time HUD (Shown when camera is active) -->
        <div id="cameraHud" class="hidden absolute inset-0 pointer-events-none p-4 sm:p-6 flex flex-col justify-between">
            <div class="flex justify-between items-center">
                <div class="bg-surface/90 backdrop-blur-md px-4 py-1.5 rounded-full flex items-center gap-2 shadow-md">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs sm:text-sm font-semibold text-on-surface">Kamera Aktif & AI Tracking</span>
                </div>
                <div class="bg-surface/90 backdrop-blur-md px-4 py-1.5 rounded-xl shadow-md text-xs sm:text-sm font-bold text-primary">
                    FPS: 30
                </div>
            </div>

            <!-- Hand Guide Wireframe overlay -->
            <div class="border-2 border-dashed border-primary/60 rounded-2xl mx-auto w-3/4 h-3/5 flex items-center justify-center">
                <span class="text-white/60 text-xs sm:text-sm font-medium tracking-wide bg-black/40 px-3 py-1 rounded-full">Area Peragaan Tangan</span>
            </div>

            <div class="text-center">
                <span class="text-xs text-white/80 bg-black/50 px-3 py-1 rounded-full backdrop-blur-sm">Pastikan pencahayaan ruangan cukup terang</span>
            </div>
        </div>
    </div>

    <!-- Controls & Results -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between liquid-glass p-5 sm:p-6 rounded-3xl border border-white/60 shadow-lg">
        <div class="flex flex-wrap gap-3 w-full sm:w-auto justify-center sm:justify-start">
            <button id="btnStartCam" type="button" class="flex items-center justify-center gap-2 bg-primary text-on-primary px-5 py-3 rounded-xl text-sm font-semibold hover:bg-surface-tint active:scale-95 transition-all shadow-md">
                <span class="material-symbols-outlined text-lg" data-icon="videocam">videocam</span> 
                <span>Start Camera</span>
            </button>
            <button id="btnStopCam" type="button" class="flex items-center justify-center gap-2 bg-error-container text-on-error-container px-5 py-3 rounded-xl text-sm font-semibold hover:bg-error hover:text-on-error active:scale-95 transition-all shadow-sm">
                <span class="material-symbols-outlined text-lg" data-icon="stop_circle">stop_circle</span> 
                <span>Stop</span>
            </button>
            <button id="btnResetCam" type="button" class="flex items-center justify-center bg-surface-container-high text-on-surface p-3 rounded-xl hover:bg-surface-dim active:scale-95 transition-all" title="Reset">
                <span class="material-symbols-outlined text-lg" data-icon="refresh">refresh</span>
            </button>
        </div>

        <!-- Result Box -->
        <div class="w-full sm:flex-1 bg-surface-container-lowest p-4 sm:p-5 rounded-2xl border border-surface-container-highest shadow-sm min-h-[64px] flex items-center justify-center sm:justify-between gap-4">
            <span class="text-xs text-on-surface-variant font-medium hidden sm:inline">Hasil Terjemahan:</span>
            <p id="translationResult" class="text-lg sm:text-xl font-extrabold text-primary tracking-wide text-center sm:text-right">
                TERIMA KASIH
            </p>
        </div>
    </div>

    <!-- Panduan / Tips Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-4">
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">wb_sunny</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">Cahaya Cukup</h3>
            <p class="text-sm text-on-surface-variant">Pastikan tangan dan wajah Anda terlihat jelas dengan pencahayaan yang merata.</p>
        </div>
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">center_focus_strong</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">Posisi Stabil</h3>
            <p class="text-sm text-on-surface-variant">Letakkan perangkat Anda pada posisi stabil sejajar dada untuk deteksi yang akurat.</p>
        </div>
        <div class="bg-surface-container-low p-5 rounded-2xl border border-outline-variant/15 space-y-2">
            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-xl">pan_tool</span>
            </div>
            <h3 class="font-bold text-on-surface text-base">Gerakan Jelas</h3>
            <p class="text-sm text-on-surface-variant">Peragakan isyarat SIBI secara perlahan dan tepat sesuai panduan kamus.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const video = document.getElementById('webcamVideo');
        const placeholder = document.getElementById('placeholderImg');
        const statusOverlay = document.getElementById('cameraStatusOverlay');
        const hud = document.getElementById('cameraHud');
        const btnStart = document.getElementById('btnStartCam');
        const btnStop = document.getElementById('btnStopCam');
        const btnReset = document.getElementById('btnResetCam');
        const resultText = document.getElementById('translationResult');

        let streamInstance = null;
        const mockWords = ['TERIMA KASIH', 'HALO', 'SELAMAT PAGI', 'MAAF', 'SAMA-SAMA', 'BELAJAR'];

        btnStart.addEventListener('click', async () => {
            try {
                if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                    streamInstance = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = streamInstance;
                    video.classList.remove('hidden');
                }
            } catch (err) {
                console.log("Webcam access optional/simulated:", err);
            }
            placeholder.classList.add('opacity-80');
            placeholder.classList.remove('opacity-40');
            statusOverlay.classList.add('hidden');
            hud.classList.remove('hidden');
            resultText.innerText = 'Mendeteksi isyarat...';
            setTimeout(() => {
                resultText.innerText = 'TERIMA KASIH (Akurasi: 96%)';
            }, 1500);
        });

        btnStop.addEventListener('click', () => {
            if (streamInstance) {
                streamInstance.getTracks().forEach(track => track.stop());
                streamInstance = null;
            }
            video.classList.add('hidden');
            placeholder.classList.remove('opacity-80');
            placeholder.classList.add('opacity-40');
            statusOverlay.classList.remove('hidden');
            hud.classList.add('hidden');
            resultText.innerText = '-';
        });

        btnReset.addEventListener('click', () => {
            const randomWord = mockWords[Math.floor(Math.random() * mockWords.length)];
            resultText.innerText = randomWord;
        });
    });
</script>
@endpush
