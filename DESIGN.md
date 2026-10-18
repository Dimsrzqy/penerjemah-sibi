# 🎨 Panduan Desain & Design System: Isyarat Inklusif & K-Suli
> **STATUS:** Dokumen Acuan Desain Utama (Source of Truth) — *Diperbarui dari Stitch Design System "Platform Pembelajaran SIBI Interaktif"*  
> **ATURAN WAJIB:** Setiap kali ada prompt atau pengerjaan yang melibatkan UI, layout, styling, komponen Blade, atau CSS, **file ini WAJIB dibaca dan dipatuhi terlebih dahulu** sebelum melakukan eksekusi kode.

---

## 1. Identitas Brand & Filosofi Desain

- **Nama Brand:** K-Suli × Isyarat Inklusif (Platform Pembelajaran SIBI & Kedai Susu Tuli).
- **Misi:** Menghubungkan dunia Teman Tuli dan Teman Dengar melalui perpaduan kehangatan kedai kopi/susu inklusif dan teknologi edukasi bahasa isyarat SIBI berbasis *computer vision* (MediaPipe Hands).
- **Aesthetic Tone:** **Modern Tactile-Clean**
  - **Empati & Kehangatan Komunitas:** Ramah, ceria, dan tidak kaku ala medis/klinis.
  - **Visual-First (Sangat Jelas):** Pengguna Tuli mengandalkan 100% konfirmasi visual. Tidak boleh ada status yang hanya mengandalkan audio.
  - **Aksesibilitas Tinggi (WCAG AAA):** Kontras teks tegas, target sentuh minimal 48px, dan transisi visual halus tanpa membuat mata lelah.

---

## 2. Sistem Warna (Color Tokens)

Palet warna resmi diadopsi langsung dari tema **Stitch Design System "Isyarat Inklusif & K-Suli"**:

### 2.1 Warna Inti (Core Palette)
| Token | Hex Code | Peran & Penggunaan |
| :--- | :--- | :--- |
| **Primary Blue** | `#0043bf` / `#1e5be8` | Edukasi struktural, tombol CTA utama, link aktif, border fokus, indikator navigasi |
| **Primary Container** | `#1e5be8` | Container aksen aktif, hover tombol primer (`#0036a0`) |
| **Secondary Warm Yellow** | `#febf2d` / `#ffc02e` | Semangat ceria K-Suli, perayaan poin, badge prestasi, aksen kehangatan kedai |
| **Tertiary Teal** | `#005952` / `#0d9488` | Komunitas inklusif, sesi latihan bersama, tag kamus isyarat |
| **Background / Canvas** | `#f8faff` / `#f9f9ff` | Latar belakang kanvas lembut (mengurangi silau saat membaca video isyarat) |
| **Surface Lowest** | `#ffffff` | Kartu konten, modul latihan, dialog, input |
| **On-Surface (Dark Slate)** | `#161c27` | Teks heading & paragraf utama (kontras tinggi, tidak hitam pekat) |
| **On-Surface-Variant** | `#434655` | Teks keterangan pendukung, subtitle, placeholder |
| **Outline / Border** | `#737686` / `#c3c5d7` | Garis tepi kartu dan divider halus |

### 2.2 Status & Umpan Balik (Feedback Tokens)
Setiap indikator status visual **wajib** dipasangkan dengan teks dan ikon simbolis:
- **Success (`#16a34a` / `#10b981`):** Gestur terdeteksi akurat (>85%), kuis lulus, verifikasi berhasil.
- **Warning (`#d97706` / `#f59e0b`):** Timer kuis berjalan, gestur kurang pas, pencahayaan minim.
- **Error (`#ba1a1a` / `#dc2626`):** Gestur salah, kamera terputus, validasi gagal.

### 2.3 Gamifikasi & Papan Peringkat
- **Peringkat 1 (Emas):** Badge Amber Cerah `#fbbf24` / `#f59e0b`, teks putih, label *"Nilai A+ (Emas)"*.
- **Peringkat 2 (Perak):** Badge Slate Elegan `#94a3b8`, label *"Nilai A (Perak)"*.
- **Peringkat 3 (Perunggu):** Badge Warm Bronze `#d97706` / `#b45309`, label *"Nilai A- (Perunggu)"*.

---

## 3. Tipografi (Typography)

### 3.1 Font Family Resmi
1. **Primary & Heading:** `'Plus Jakarta Sans', sans-serif`  
   Digunakan di seluruh display, headline, tombol, dan teks navigasi. Memiliki keterbacaan tinggi (*high scanability*) dengan kurva geometris modern.
2. **Fallback:** `'Inter', sans-serif`

### 3.2 Skala Tipografi
- **Display Hero:** `text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900`.
- **Section Heading (H2):** `text-3xl font-extrabold text-slate-900`.
- **Card Title (H3):** `text-xl font-bold text-slate-900`.
- **Body Regular:** `text-sm sm:text-base text-slate-600 leading-relaxed`.
- **Badge / Label:** `text-xs font-bold uppercase tracking-wider`.

---

## 4. Bentuk (Shapes) & Elevasi (Depth)

### 4.1 Corner Radius
- **Kartu Konten & Video Player:** `rounded-2xl` (16px) hingga `rounded-3xl` (24px).
- **Tombol & Input Form:** `rounded-xl` (12px) atau `rounded-full` (9999px).
- **Badge & Status Pill:** `rounded-full` dengan padding `px-3 py-1`.

### 4.2 Sistem Elevasi
- **Level 0 (Flat Canvas):** `#f8faff` / `#f9f9ff`.
- **Level 1 (Card & Section):** Latar `#ffffff`, border `1px solid #e2e8f0` (`border-slate-200`), bayangan `shadow-sm` hingga `shadow-md`.
- **Level 2 (Hover State):** Hover lift `hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200`.
- **Level 3 (Modal & HUD Overlay):** Translucent frosted glass `bg-white/90 backdrop-blur-md border border-slate-200`.

---

## 5. Standar Komponen UI

### 5.1 Tombol (Buttons)
- **Primary Action (CTA):**
  `inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-md transition-all active:scale-95 min-h-[48px]`
- **Secondary / Outline Action:**
  `inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-sm border border-slate-200 shadow-sm transition-all min-h-[48px]`
- **Pill Button (Mulai Belajar):**
  `inline-flex items-center gap-2 px-5 py-2 rounded-full bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold shadow-sm transition-all min-h-[40px]`

### 5.2 Kartu Kamera & Gestur Interaktif (Stitch Hero Card)
- **Frame Header:** `px-5 py-3.5 bg-slate-50/90 border-b border-slate-200 flex items-center justify-between` dengan 3 titik dot lampu OS (merah, kuning, hijau) dan badge status `AKTIF`.
- **Sensor Area:** Latar gradien gelap `from-slate-900 to-slate-800` dengan target gestur besar, ilustrasi ikon tangan, dan persentase akurasi real-time (>85%).
- **Mode Cermin (Mirror Mode):** Video webcam dan kanvas landmark tetap wajib menggunakan `.mirror-mode` (`transform: scaleX(-1)`).

### 5.3 Step Indicators (Langkah Alur)
- Node nomor bulat: `w-12 h-12 rounded-full bg-blue-600 text-white font-extrabold text-lg flex items-center justify-center shadow-sm`.

### 5.4 Dynamic Island Morphing Navbar (iPhone Frosted Glass)
- **Kondisi Normal (Scroll = 0):** Header penuh `w-full bg-white/90 backdrop-blur-md border-b border-slate-200/80` dengan logo K-Suli di kiri, pill navigasi di tengah, dan tombol aksi di kanan.
- **Kondisi Mengambang (Scroll > 40px):**
  - Latar header dan garis tepi memudar transparan (`opacity: 0`).
  - Logo kiri dan tombol kanan meluncur keluar dengan animasi mulus (`translate-x`, `scale-90`, `opacity-0`).
  - **Pill Navigasi Tengah:** Bertransformasi menjadi **Dynamic Island Capsule** (`backdrop-filter: blur(28px) saturate(190%)`, latar `rgba(255, 255, 255, 0.78)`, border `1px solid rgba(255, 255, 255, 0.9)`, refleksi atas `inset 0 1.5px 1.5px 0 rgba(255, 255, 255, 0.95)` dan floating drop shadow `0 20px 40px -12px rgba(0, 67, 191, 0.16)`).
  - **Kurva Transisi:** Animasi pegas alami menggunakan kurva `cubic-bezier(0.16, 1, 0.3, 1)` durasi 400ms-500ms.
  - **Mobile:** Bertransisi menjadi kapsul mini mengambang di tengah yang dapat diketuk untuk membuka menu navigasi cepat.

---

## 6. Aksesibilitas (A11y) & Mobile First

1. **Ukuran Target Sentuh:** Wajib minimal **48px × 48px** untuk kenyamanan pengguna di smartphone.
2. **Kontras Bebas Silau:** Menghindari background putih menusuk mata dengan menggunakan warna lembut `#f8faff`.
3. **Ikonografi Konsisten:** Selalu gunakan **Google Material Symbols Outlined** dengan teks pendamping.
4. **Respon Cepat:** Indikator animasi berdenyut (`animate-pulse`) pada elemen aktif untuk memberi umpan balik instan.

---

## 7. Checklist Eksekusi UI
- [ ] Apakah komponen menggunakan font `Plus Jakarta Sans`?
- [ ] Apakah warna primer konsisten pada `bg-blue-600` (`#0043bf` / `#1e5be8`)?
- [ ] Apakah kartu berlatar `#ffffff` dengan border halus `border-slate-200`?
- [ ] Apakah tombol memiliki target sentuh minimal 48px?
- [ ] Apakah logo resmi K-Suli SVG dimuat dengan benar?
