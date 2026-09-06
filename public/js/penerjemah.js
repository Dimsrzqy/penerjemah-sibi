/**
 * ============================================================
 * SIBI LEARN - PENERJEMAH SIBI JAVASCRIPT
 * ============================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const video = document.getElementById('webcamVideo');
    const skeletonCanvas = document.getElementById('skeletonCanvas');
    const canvasCtx = skeletonCanvas ? skeletonCanvas.getContext('2d') : null;
    const standbyImg = document.getElementById('standbyImg');
    const standbyOverlay = document.getElementById('standbyOverlay');
    const cameraHud = document.getElementById('cameraHud');
    const fpsDisplay = document.getElementById('fpsDisplay');
    const handCountDisplay = document.getElementById('handCountDisplay');
    const hudStatusDot = document.getElementById('hudStatusDot');
    const hudStatusText = document.getElementById('hudStatusText');
    const toggleAutoType = document.getElementById('toggleAutoType');

    // Controls
    const btnStart = document.getElementById('btnStart');
    const btnQuickStart = document.getElementById('btnQuickStart');
    const btnStop = document.getElementById('btnStop');
    const btnToggleMirror = document.getElementById('btnToggleMirror');
    const btnToggleSkeleton = document.getElementById('btnToggleSkeleton');

    // Recognition & Predictions
    const liveDetectedText = document.getElementById('liveDetectedText');
    const detectedCategory = document.getElementById('detectedCategory');
    const confidenceBadge = document.getElementById('confidenceBadge');
    const topPredictionsContainer = document.getElementById('topPredictionsContainer');
    const holdProgressBar = document.getElementById('holdProgressBar');

    // Sentence Box & Actions
    const sentenceOutput = document.getElementById('sentenceOutput');
    const charCount = document.getElementById('charCount');
    const btnManualAdd = document.getElementById('btnManualAdd');
    const btnSpeech = document.getElementById('btnSpeech');
    const btnSpace = document.getElementById('btnSpace');
    const btnBackspace = document.getElementById('btnBackspace');
    const btnCopy = document.getElementById('btnCopy');
    const btnClearAll = document.getElementById('btnClearAll');
    const copyBtnText = document.getElementById('copyBtnText');

    // Settings Modal
    const settingsModal = document.getElementById('settingsModal');
    const btnOpenSettings = document.getElementById('btnOpenSettings');
    const btnCloseSettings = document.getElementById('btnCloseSettings');
    const btnCancelSettings = document.getElementById('btnCancelSettings');
    const btnSaveSettings = document.getElementById('btnSaveSettings');
    const landmarkDimSelect = document.getElementById('landmarkDimSelect');
    const modelUrlInput = document.getElementById('modelUrlInput');
    const classesInput = document.getElementById('classesInput');
    const thresholdSlider = document.getElementById('thresholdSlider');
    const thresholdVal = document.getElementById('thresholdVal');

    // State Variables
    let isCameraRunning = false;
    let isMirror = true;
    let showSkeleton = true;
    let streamInstance = null;
    let animFrameId = null;
    let handsDetector = null;
    let isProcessingFrame = false;

    // AI State
    let tfModel = null;
    let modelUrl = '/models/sibi_model/model.json';
    let confidenceThreshold = 0.70;
    let featureDimension = '63'; // '63' | '42' | '126' | 'normalized_wrist'
    let classLabels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'TERIMA KASIH', 'HALO', 'SAMA-SAMA'];

    // Auto-Type Dwell Logic
    let lastStableWord = '';
    let stableWordCount = 0;
    const DWELL_FRAMES_REQUIRED = 18;
    let currentSentence = '';

    // FPS Tracker
    let frameCount = 0;
    let lastFpsTime = performance.now();

    // ----------------------------------------------------
    // 1. Inisialisasi MediaPipe Hands
    // ----------------------------------------------------
    function initMediaPipeHands() {
        if (typeof Hands === 'undefined') {
            console.error('MediaPipe Hands library belum selesai dimuat dari CDN.');
            return;
        }

        try {
            handsDetector = new Hands({
                locateFile: (file) => `https://cdn.jsdelivr.net/npm/@mediapipe/hands/${file}`
            });

            handsDetector.setOptions({
                maxNumHands: 2,
                modelComplexity: 1,
                minDetectionConfidence: 0.5,
                minTrackingConfidence: 0.5
            });

            handsDetector.onResults(onHandResults);
            console.log('MediaPipe Hands detector berhasil diinisialisasi.');
        } catch (e) {
            console.error('Error inisialisasi MediaPipe:', e);
        }
    }

    // ----------------------------------------------------
    // 2. Callback Menggambar Landmark Skeleton
    // ----------------------------------------------------
    function onHandResults(results) {
        isProcessingFrame = false;
        if (!isCameraRunning) return;

        // FPS Tracker
        frameCount++;
        const now = performance.now();
        if (now - lastFpsTime >= 1000) {
            if (fpsDisplay) fpsDisplay.textContent = frameCount;
            frameCount = 0;
            lastFpsTime = now;
        }

        // Sesuaikan resolusi internal canvas dengan video
        if (video && skeletonCanvas && video.videoWidth > 0 && (skeletonCanvas.width !== video.videoWidth || skeletonCanvas.height !== video.videoHeight)) {
            skeletonCanvas.width = video.videoWidth;
            skeletonCanvas.height = video.videoHeight;
        }

        if (!canvasCtx) return;

        // Bersihkan canvas setiap frame
        canvasCtx.save();
        canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);

        const hasHands = results.multiHandLandmarks && results.multiHandLandmarks.length > 0;
        if (handCountDisplay) {
            handCountDisplay.textContent = hasHands ? `${results.multiHandLandmarks.length} Tangan Terdeteksi` : '0 Tangan';
        }

        if (hasHands) {
            // Gambar Garis Skeleton & Titik Sendi (Warna Terang Berkilau)
            if (showSkeleton) {
                for (const landmarks of results.multiHandLandmarks) {
                    if (typeof drawConnectors !== 'undefined' && typeof HAND_CONNECTIONS !== 'undefined') {
                        drawConnectors(canvasCtx, landmarks, HAND_CONNECTIONS, {
                            color: '#00D2FF',
                            lineWidth: 4
                        });
                    }
                    if (typeof drawLandmarks !== 'undefined') {
                        drawLandmarks(canvasCtx, landmarks, {
                            color: '#0058be',
                            fillColor: '#FFFFFF',
                            lineWidth: 2,
                            radius: 4
                        });
                    }
                }
            }

            // Ekstraksi Fitur Landmark
            const features = extractLandmarkFeatures(results.multiHandLandmarks);

            // Prediksi jika model TFJS sudah ada
            if (tfModel && features.length > 0) {
                predictSignGesture(features);
            } else {
                if (liveDetectedText) liveDetectedText.textContent = '-';
                if (detectedCategory) detectedCategory.textContent = 'Landmark Tangan Aktif (Menunggu model.json)';
            }
        } else {
            if (liveDetectedText) liveDetectedText.textContent = '-';
            if (detectedCategory) detectedCategory.textContent = 'Arahkan tangan ke kamera...';
            if (confidenceBadge) confidenceBadge.textContent = '-';
            if (holdProgressBar) holdProgressBar.style.width = '0%';
            stableWordCount = 0;
        }

        canvasCtx.restore();
    }

    // ----------------------------------------------------
    // 3. Ekstraksi Fitur Landmark
    // ----------------------------------------------------
    function extractLandmarkFeatures(multiHandLandmarks) {
        const hand = multiHandLandmarks[0];
        let rawFeatures = [];

        if (featureDimension === '42') {
            for (let i = 0; i < hand.length; i++) {
                rawFeatures.push(hand[i].x, hand[i].y);
            }
        } else if (featureDimension === '126') {
            for (let h = 0; h < 2; h++) {
                if (multiHandLandmarks[h]) {
                    for (let i = 0; i < 21; i++) {
                        rawFeatures.push(multiHandLandmarks[h][i].x, multiHandLandmarks[h][i].y, multiHandLandmarks[h][i].z);
                    }
                } else {
                    for (let i = 0; i < 63; i++) rawFeatures.push(0.0);
                }
            }
        } else if (featureDimension === 'normalized_wrist') {
            const wrist = hand[0];
            for (let i = 0; i < hand.length; i++) {
                rawFeatures.push(hand[i].x - wrist.x, hand[i].y - wrist.y, hand[i].z - wrist.z);
            }
        } else {
            // Default 63 fitur: 21 titik x, y, z
            for (let i = 0; i < hand.length; i++) {
                rawFeatures.push(hand[i].x, hand[i].y, hand[i].z);
            }
        }

        return rawFeatures;
    }

    // ----------------------------------------------------
    // 4. Prediksi TensorFlow.js
    // ----------------------------------------------------
    function predictSignGesture(features) {
        try {
            tf.tidy(() => {
                const inputTensor = tf.tensor2d([features], [1, features.length]);
                const outputTensor = tfModel.predict(inputTensor);
                const scores = outputTensor.dataSync();

                let maxIndex = 0;
                let maxProb = scores[0];
                let predictions = [];

                for (let i = 0; i < scores.length; i++) {
                    const label = classLabels[i] || `Kelas ${i + 1}`;
                    predictions.push({
                        className: label,
                        probability: scores[i]
                    });
                    if (scores[i] > maxProb) {
                        maxProb = scores[i];
                        maxIndex = i;
                    }
                }

                predictions.sort((a, b) => b.probability - a.probability);
                renderTopPredictions(predictions.slice(0, 3));

                const topClass = classLabels[maxIndex] || `Kelas ${maxIndex + 1}`;
                const confPercent = Math.round(maxProb * 100);

                if (maxProb >= confidenceThreshold) {
                    if (liveDetectedText) liveDetectedText.textContent = topClass.toUpperCase();
                    if (detectedCategory) detectedCategory.textContent = 'Isyarat SIBI Terdeteksi';
                    if (confidenceBadge) {
                        confidenceBadge.textContent = `${confPercent}% Cocok`;
                        confidenceBadge.className = 'text-xs font-extrabold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300';
                    }

                    if (toggleAutoType && toggleAutoType.checked) {
                        if (topClass === lastStableWord) {
                            stableWordCount++;
                            const progress = Math.min(100, Math.round((stableWordCount / DWELL_FRAMES_REQUIRED) * 100));
                            if (holdProgressBar) holdProgressBar.style.width = `${progress}%`;

                            if (stableWordCount === DWELL_FRAMES_REQUIRED) {
                                appendWordToSentence(topClass);
                                if (liveDetectedText) {
                                    liveDetectedText.classList.add('scale-110');
                                    setTimeout(() => liveDetectedText.classList.remove('scale-110'), 200);
                                }
                            }
                        } else {
                            lastStableWord = topClass;
                            stableWordCount = 1;
                            if (holdProgressBar) holdProgressBar.style.width = '5%';
                        }
                    }
                } else {
                    if (liveDetectedText) liveDetectedText.textContent = '...';
                    if (detectedCategory) detectedCategory.textContent = 'Menganalisis pose tangan...';
                    if (confidenceBadge) {
                        confidenceBadge.textContent = `${confPercent}% (Di bawah threshold)`;
                        confidenceBadge.className = 'text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300';
                    }
                    if (holdProgressBar) holdProgressBar.style.width = '0%';
                    stableWordCount = 0;
                }
            });
        } catch (err) {
            console.error('Error inferensi model:', err);
        }
    }

    function renderTopPredictions(top3) {
        if (!topPredictionsContainer) return;
        topPredictionsContainer.innerHTML = '';
        top3.forEach(pred => {
            const percent = Math.round(pred.probability * 100);
            const row = document.createElement('div');
            row.className = 'flex items-center justify-between text-xs text-on-surface';
            row.innerHTML = `
                <span class="font-semibold">${pred.className}</span>
                <div class="flex items-center gap-2">
                    <div class="w-16 bg-surface-container-high rounded-full h-1.5 overflow-hidden">
                        <div class="bg-primary h-1.5 rounded-full" style="width: ${percent}%"></div>
                    </div>
                    <span class="font-mono text-[11px] w-7 text-right">${percent}%</span>
                </div>
            `;
            topPredictionsContainer.appendChild(row);
        });
    }

    // ----------------------------------------------------
    // 5. Robust Camera Pipeline & MediaPipe Frame Sender
    // ----------------------------------------------------
    async function startCamera() {
        try {
            if (!handsDetector) initMediaPipeHands();

            streamInstance = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user'
                },
                audio: false
            });

            video.srcObject = streamInstance;
            await video.play();

            isCameraRunning = true;

            // Tampilkan elemen Video dan Canvas Skeleton
            if (video) video.classList.remove('hidden');
            if (skeletonCanvas) skeletonCanvas.classList.remove('hidden');
            if (standbyImg) standbyImg.classList.add('hidden');
            if (standbyOverlay) standbyOverlay.classList.add('hidden');
            if (cameraHud) cameraHud.classList.remove('hidden');

            if (btnStart) btnStart.classList.add('hidden');
            if (btnStop) {
                btnStop.classList.remove('hidden');
                btnStop.classList.add('flex');
            }

            if (detectedCategory) detectedCategory.textContent = 'Mendeteksi titik sendi tangan...';

            startMediaPipeLoop();
        } catch (err) {
            console.error('Gagal mengakses kamera:', err);
            alert('Tidak dapat mengaktifkan kamera. Pastikan izin kamera telah diberikan di browser.');
        }
    }

    function startMediaPipeLoop() {
        const processFrame = async () => {
            if (!isCameraRunning) return;

            if (handsDetector && video && video.readyState >= 2 && !isProcessingFrame) {
                isProcessingFrame = true;
                try {
                    await handsDetector.send({ image: video });
                } catch (e) {
                    console.warn('Gagal memproses frame MediaPipe:', e);
                    isProcessingFrame = false;
                }
            }

            animFrameId = requestAnimationFrame(processFrame);
        };

        animFrameId = requestAnimationFrame(processFrame);
    }

    function stopCamera() {
        isCameraRunning = false;
        if (animFrameId) {
            cancelAnimationFrame(animFrameId);
            animFrameId = null;
        }
        if (streamInstance) {
            streamInstance.getTracks().forEach(t => t.stop());
            streamInstance = null;
        }
        if (video) video.srcObject = null;

        // Reset Tampilan
        if (video) video.classList.add('hidden');
        if (skeletonCanvas) skeletonCanvas.classList.add('hidden');
        if (standbyImg) standbyImg.classList.remove('hidden');
        if (standbyOverlay) standbyOverlay.classList.remove('hidden');
        if (cameraHud) cameraHud.classList.add('hidden');

        if (btnStop) {
            btnStop.classList.add('hidden');
            btnStop.classList.remove('flex');
        }
        if (btnStart) btnStart.classList.remove('hidden');

        if (canvasCtx && skeletonCanvas) {
            canvasCtx.clearRect(0, 0, skeletonCanvas.width, skeletonCanvas.height);
        }
        resetLiveDisplay();
    }

    function resetLiveDisplay() {
        if (liveDetectedText) liveDetectedText.textContent = '-';
        if (detectedCategory) detectedCategory.textContent = 'Kamera belum aktif';
        if (confidenceBadge) confidenceBadge.textContent = '-';
        if (fpsDisplay) fpsDisplay.textContent = '0';
        if (handCountDisplay) handCountDisplay.textContent = '0 Tangan';
        if (holdProgressBar) holdProgressBar.style.width = '0%';
        if (topPredictionsContainer) {
            topPredictionsContainer.innerHTML = '<div class="text-xs text-on-surface-variant italic">Belum ada data inferensi</div>';
        }
    }

    // ----------------------------------------------------
    // 6. Sentence Builder Actions
    // ----------------------------------------------------
    function appendWordToSentence(word) {
        if (!word || word === '-' || word === '...') return;
        if (word.length === 1) {
            currentSentence += word;
        } else {
            if (currentSentence.length > 0 && !currentSentence.endsWith(' ')) {
                currentSentence += ' ';
            }
            currentSentence += word + ' ';
        }
        updateSentenceDisplay();
    }

    function updateSentenceDisplay() {
        if (!sentenceOutput || !charCount) return;
        if (currentSentence.trim() === '') {
            sentenceOutput.innerHTML = '<span class="text-on-surface-variant/60 italic text-sm">Kalimat hasil terjemahan akan tersusun di sini...</span>';
            charCount.textContent = '0 karakter';
        } else {
            sentenceOutput.textContent = currentSentence;
            charCount.textContent = `${currentSentence.length} karakter`;
        }
    }

    if (btnManualAdd) {
        btnManualAdd.addEventListener('click', () => {
            const text = liveDetectedText ? liveDetectedText.textContent.trim() : '';
            if (text && text !== '-' && text !== '...') {
                appendWordToSentence(text);
            }
        });
    }

    if (btnSpace) {
        btnSpace.addEventListener('click', () => {
            currentSentence += ' ';
            updateSentenceDisplay();
        });
    }

    if (btnBackspace) {
        btnBackspace.addEventListener('click', () => {
            currentSentence = currentSentence.slice(0, -1);
            updateSentenceDisplay();
        });
    }

    if (btnClearAll) {
        btnClearAll.addEventListener('click', () => {
            currentSentence = '';
            updateSentenceDisplay();
        });
    }

    if (btnCopy) {
        btnCopy.addEventListener('click', async () => {
            if (!currentSentence.trim()) return;
            try {
                await navigator.clipboard.writeText(currentSentence);
                if (copyBtnText) copyBtnText.textContent = 'Tersalin!';
                setTimeout(() => {
                    if (copyBtnText) copyBtnText.textContent = 'Salin';
                }, 1500);
            } catch (e) {
                console.error('Gagal menyalin teks:', e);
            }
        });
    }

    // Text-To-Speech
    if (btnSpeech) {
        btnSpeech.addEventListener('click', () => {
            const text = currentSentence.trim();
            if (!text) {
                alert('Belum ada kalimat untuk disuarakan.');
                return;
            }

            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95;

                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
                if (idVoice) utterance.voice = idVoice;

                window.speechSynthesis.speak(utterance);
            } else {
                alert('Browser Anda tidak mendukung Text-to-Speech.');
            }
        });
    }

    // ----------------------------------------------------
    // 7. Load Model TFJS
    // ----------------------------------------------------
    async function loadModel() {
        try {
            tfModel = await tf.loadLayersModel(modelUrl);
            if (hudStatusText) hudStatusText.textContent = 'Model TFJS Siap';
            if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
        } catch (e) {
            try {
                tfModel = await tf.loadGraphModel(modelUrl);
                if (hudStatusText) hudStatusText.textContent = 'Model TFJS Siap';
                if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse';
            } catch (err) {
                tfModel = null;
                if (hudStatusText) hudStatusText.textContent = 'MediaPipe Hands Siap';
                if (hudStatusDot) hudStatusDot.className = 'w-2.5 h-2.5 rounded-full bg-emerald-400';
            }
        }
    }

    // ----------------------------------------------------
    // 8. Event Listeners
    // ----------------------------------------------------
    if (btnStart) btnStart.addEventListener('click', startCamera);
    if (btnQuickStart) btnQuickStart.addEventListener('click', startCamera);
    if (btnStop) btnStop.addEventListener('click', stopCamera);

    if (btnToggleMirror) {
        btnToggleMirror.addEventListener('click', () => {
            isMirror = !isMirror;
            if (isMirror) {
                if (video) video.classList.add('mirror-mode');
                if (skeletonCanvas) skeletonCanvas.classList.add('mirror-mode');
            } else {
                if (video) video.classList.remove('mirror-mode');
                if (skeletonCanvas) skeletonCanvas.classList.remove('mirror-mode');
            }
        });
    }

    if (btnToggleSkeleton) {
        btnToggleSkeleton.addEventListener('click', () => {
            showSkeleton = !showSkeleton;
            btnToggleSkeleton.classList.toggle('text-primary');
            btnToggleSkeleton.classList.toggle('text-on-surface-variant');
        });
    }

    if (thresholdSlider) {
        thresholdSlider.addEventListener('input', (e) => {
            if (thresholdVal) thresholdVal.textContent = `${e.target.value}%`;
        });
    }

    if (btnOpenSettings) {
        btnOpenSettings.addEventListener('click', () => {
            if (modelUrlInput) modelUrlInput.value = modelUrl;
            if (landmarkDimSelect) landmarkDimSelect.value = featureDimension;
            if (classesInput) classesInput.value = classLabels.join(', ');
            if (thresholdSlider) thresholdSlider.value = Math.round(confidenceThreshold * 100);
            if (thresholdVal && thresholdSlider) thresholdVal.textContent = `${thresholdSlider.value}%`;
            if (settingsModal) {
                settingsModal.classList.remove('hidden');
                settingsModal.classList.add('flex');
            }
        });
    }

    function closeSettings() {
        if (settingsModal) {
            settingsModal.classList.add('hidden');
            settingsModal.classList.remove('flex');
        }
    }

    if (btnCloseSettings) btnCloseSettings.addEventListener('click', closeSettings);
    if (btnCancelSettings) btnCancelSettings.addEventListener('click', closeSettings);

    if (btnSaveSettings) {
        btnSaveSettings.addEventListener('click', async () => {
            if (modelUrlInput) modelUrl = modelUrlInput.value.trim();
            if (landmarkDimSelect) featureDimension = landmarkDimSelect.value;
            if (classesInput) {
                classLabels = classesInput.value.split(',').map(s => s.trim()).filter(s => s.length > 0);
            }
            if (thresholdSlider) {
                confidenceThreshold = parseInt(thresholdSlider.value, 10) / 100;
            }

            closeSettings();
            await loadModel();
        });
    }

    // Inisialisasi MediaPipe dan Model
    initMediaPipeHands();
    loadModel();
});
