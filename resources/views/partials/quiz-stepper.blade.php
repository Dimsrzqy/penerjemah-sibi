{{-- 
    Partial: Quiz Step Indicator Bar
    Desain Konsisten untuk Langkah 1 (Identitas Tamu) dan Langkah 2 (Pilih Level)
    Memastikan ukuran bar stepper 100% identik di semua langkah.
--}}
<nav aria-label="Alur Pendaftaran Kuis" class="w-full bg-white/95 backdrop-blur-md rounded-2xl p-2 sm:p-2.5 shadow-xs border border-slate-200/80">
    <div class="grid grid-cols-3 gap-1.5 sm:gap-2.5 items-stretch">
        
        <!-- ============================================== -->
        <!-- LANGKAH 1: IDENTITAS TAMU                      -->
        <!-- ============================================== -->
        <div id="stepperStep1Container" class="min-w-0">
            <!-- 1A. State Aktif (Saat Langkah 1) -->
            <div id="stepper1Active" class="flex items-center gap-2 sm:gap-3 bg-primary text-white p-2 sm:p-2.5 rounded-xl shadow-xs transition-all h-full">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white text-primary flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[16px] sm:text-[19px]" style="font-variation-settings: 'FILL' 1;">badge</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-1 sm:gap-1.5">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-blue-100 font-bold hidden xs:inline">Langkah 1</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-blue-500/30 text-white text-[9px] sm:text-[10px] font-bold">Aktif</span>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-white truncate">Identitas Tamu</span>
                </div>
            </div>

            <!-- 1B. State Selesai (Saat Langkah 2, klik untuk ganti nama) -->
            <div id="step1DoneIndicator" class="hidden flex items-center gap-2 sm:gap-3 p-2 sm:p-2.5 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group border border-slate-200/60 sm:border-transparent h-full" title="Klik untuk ganti pemain">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-xs transition-transform group-hover:scale-105">
                    <span class="material-symbols-outlined text-[16px] sm:text-[19px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-1 sm:gap-1.5">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-emerald-700 font-bold hidden xs:inline">Langkah 1</span>
                        <span class="text-[9px] sm:text-[10px] text-slate-400">• Selesai</span>
                    </div>
                    <span id="step1PlayerName" class="text-xs sm:text-sm font-bold text-slate-800 truncate">-</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- LANGKAH 2: TINGKAT KESULITAN                   -->
        <!-- ============================================== -->
        <div id="stepperStep2Container" class="min-w-0">
            <!-- 2A. State Upcoming (Saat Langkah 1) -->
            <div id="stepper2Upcoming" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-2.5 bg-surface-container-low text-on-surface-variant rounded-xl opacity-75 border border-slate-200/60 h-full">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-surface-container text-outline flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[16px] sm:text-[19px]">tune</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-outline font-semibold hidden xs:inline">Langkah 2</span>
                    <span class="text-xs sm:text-sm font-medium text-on-surface truncate">Tingkat Kesulitan</span>
                </div>
            </div>

            <!-- 2B. State Aktif (Saat Langkah 2) -->
            <div id="stepper2Active" class="hidden flex items-center gap-2 sm:gap-3 bg-primary text-white p-2 sm:p-2.5 rounded-xl shadow-xs transition-all h-full">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-white text-primary flex items-center justify-center shrink-0 shadow-xs">
                    <span class="material-symbols-outlined text-[16px] sm:text-[19px]">tune</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <div class="flex items-center gap-1 sm:gap-1.5">
                        <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-blue-100 font-bold hidden xs:inline">Langkah 2</span>
                        <span class="px-1.5 py-0.2 rounded-full bg-blue-500/30 text-white text-[9px] sm:text-[10px] font-bold">Aktif</span>
                    </div>
                    <span class="text-xs sm:text-sm font-bold text-white truncate">Tingkat Kesulitan</span>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- LANGKAH 3: PRAKTIK KAMERA KUIS                 -->
        <!-- ============================================== -->
        <div id="stepperStep3Container" class="min-w-0">
            <div id="stepper3Upcoming" class="flex items-center gap-2 sm:gap-3 p-2 sm:p-2.5 bg-surface-container-low text-on-surface-variant rounded-xl opacity-75 border border-slate-200/60 h-full">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl bg-surface-container text-outline flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[16px] sm:text-[19px]">videocam</span>
                </div>
                <div class="flex flex-col min-w-0">
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-wider text-outline font-semibold hidden xs:inline">Langkah 3</span>
                    <span class="text-xs sm:text-sm font-medium text-on-surface truncate">Praktik Kamera</span>
                </div>
            </div>
        </div>

    </div>
</nav>
