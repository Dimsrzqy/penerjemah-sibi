@extends('layouts.app')

@section('title', 'Quiz Interaktif SIBI - SIBI Learn')

@section('content')
<div class="max-w-4xl mx-auto flex flex-col space-y-6 mt-2 md:mt-4">
    <!-- Top Progress Bar -->
    <div class="flex justify-between items-center bg-surface-container-low px-5 sm:px-6 py-4 rounded-2xl shadow-sm border border-outline-variant/15">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container font-bold shadow-sm">
                G
            </div>
            <div>
                <span class="text-xs text-on-surface-variant font-medium block">Pemain</span>
                <span class="text-sm font-bold text-on-surface">Guest_892</span>
            </div>
        </div>

        <div class="flex flex-col items-end gap-1.5">
            <span id="questionProgressText" class="text-xs sm:text-sm font-semibold text-on-surface-variant">Soal <span id="currentQNum">3</span> dari 10</span>
            <div class="w-28 sm:w-40 h-2.5 bg-surface-container-highest rounded-full overflow-hidden">
                <div id="progressBarFill" class="h-full bg-primary w-[30%] rounded-full transition-all duration-300"></div>
            </div>
        </div>
    </div>

    <!-- Target Word Instruction -->
    <div class="text-center py-2 sm:py-4 space-y-1">
        <h2 class="text-base sm:text-lg font-medium text-on-surface-variant">Peragakan isyarat untuk kata:</h2>
        <p id="targetWord" class="text-4xl sm:text-5xl font-extrabold text-primary uppercase tracking-wider">
            MAAF
        </p>
    </div>

    <!-- Camera Interaction Area -->
    <div class="relative w-full aspect-video rounded-3xl overflow-hidden bg-inverse-surface shadow-2xl border-4 border-surface-container-high">
        <!-- Live Video (Hidden until started) -->
        <video id="quizVideo" class="absolute inset-0 w-full h-full object-cover hidden" autoplay playsinline muted></video>

        <img id="quizPlaceholder" class="absolute inset-0 w-full h-full object-cover opacity-60" 
             src="{{ asset('images/laptop-practice.jpg') }}" 
             alt="Quiz Camera Safe Zone Guide">

        <!-- HUD: Detecting Status -->
        <div class="absolute top-4 sm:top-6 left-4 sm:left-6 bg-surface/90 backdrop-blur-md px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-full flex items-center gap-2 shadow-md">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span id="hudDetectStatus" class="text-xs sm:text-sm font-semibold text-on-surface">Mendeteksi...</span>
        </div>

        <!-- HUD: Score -->
        <div class="absolute top-4 sm:top-6 right-4 sm:right-6 bg-surface/90 backdrop-blur-md px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl flex items-center gap-2 shadow-md">
            <span class="material-symbols-outlined text-amber-600 text-lg sm:text-xl" data-icon="star">star</span>
            <span id="quizScoreDisplay" class="text-xs sm:text-sm font-bold text-on-surface">Skor: 240</span>
        </div>

        <!-- Target Box Guide -->
        <div class="absolute inset-0 border-2 border-dashed border-white/40 m-6 sm:m-12 rounded-2xl pointer-events-none flex items-center justify-center">
            <span class="text-white/50 text-xs sm:text-sm font-semibold tracking-wide bg-black/30 px-3 py-1 rounded-full backdrop-blur-sm">
                Posisikan Tangan Anda di Sini
            </span>
        </div>

        <!-- Success overlay banner (shown on correct gesture) -->
        <div id="successNotice" class="hidden absolute bottom-6 left-1/2 -translate-x-1/2 bg-emerald-600 text-white px-6 py-2 rounded-full font-bold text-sm shadow-xl flex items-center gap-2 animate-bounce">
            <span class="material-symbols-outlined text-lg">check_circle</span>
            Gerakan Tepat! +80 Poin
        </div>
    </div>

    <!-- Footer Controls -->
    <div class="flex flex-wrap justify-center sm:justify-between items-center gap-4 pt-2">
        <button id="btnToggleCamQuiz" type="button" class="text-xs sm:text-sm text-on-surface-variant hover:text-primary font-semibold flex items-center gap-2 px-4 py-2 rounded-full bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-base">videocam</span>
            <span>Buka / Tutup Kamera</span>
        </button>

        <div class="flex gap-3">
            <button id="btnNextQuestion" type="button" class="bg-primary text-on-primary text-xs sm:text-sm font-semibold flex items-center gap-2 px-6 py-3 rounded-full hover:bg-surface-tint active:scale-95 transition-all shadow-md">
                <span>Soal Selanjutnya</span>
                <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const questions = [
            { word: 'HALO', scoreIncrement: 80 },
            { word: 'TERIMA KASIH', scoreIncrement: 80 },
            { word: 'MAAF', scoreIncrement: 80 },
            { word: 'MAKAN', scoreIncrement: 80 },
            { word: 'RUMAH', scoreIncrement: 80 },
            { word: 'TEMAN', scoreIncrement: 80 },
            { word: 'BELAJAR', scoreIncrement: 80 },
            { word: 'KABAR BAIK', scoreIncrement: 80 },
            { word: 'SAMA-SAMA', scoreIncrement: 80 },
            { word: 'SELAMAT', scoreIncrement: 80 }
        ];

        let currentIndex = 2; // starts at question 3 (index 2)
        let score = 240;

        const targetWordEl = document.getElementById('targetWord');
        const currentQNumEl = document.getElementById('currentQNum');
        const progressBarFill = document.getElementById('progressBarFill');
        const scoreDisplay = document.getElementById('quizScoreDisplay');
        const btnNext = document.getElementById('btnNextQuestion');
        const successNotice = document.getElementById('successNotice');
        const video = document.getElementById('quizVideo');
        const btnToggleCam = document.getElementById('btnToggleCamQuiz');

        let streamInstance = null;

        btnToggleCam.addEventListener('click', async () => {
            if (!streamInstance) {
                try {
                    streamInstance = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = streamInstance;
                    video.classList.remove('hidden');
                } catch (e) {
                    console.log("Webcam not available or denied:", e);
                }
            } else {
                streamInstance.getTracks().forEach(t => t.stop());
                streamInstance = null;
                video.classList.add('hidden');
            }
        });

        btnNext.addEventListener('click', () => {
            // Trigger quick success feedback
            successNotice.classList.remove('hidden');
            score += 80;
            scoreDisplay.innerText = `Skor: ${score}`;

            setTimeout(() => {
                successNotice.classList.add('hidden');
                currentIndex = (currentIndex + 1) % questions.length;
                const nextQ = questions[currentIndex];
                targetWordEl.innerText = nextQ.word;
                currentQNumEl.innerText = currentIndex + 1;
                const progressPct = ((currentIndex + 1) / questions.length) * 100;
                progressBarFill.style.width = `${progressPct}%`;
            }, 600);
        });
    });
</script>
@endpush
