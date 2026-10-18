# Design System Enforcement (DESIGN.md)

Setiap kali menerima prompt atau instruksi dari pengguna:
1. **Periksa DESIGN.md Terlebih Dahulu**: Sebelum mengeksekusi perubahan kode apa pun (terutama yang menyentuh antarmuka, Blade templates, CSS, komponen, warna, tata letak, atau interaksi pengguna), agen **WAJIB membaca dan meninjau file [DESIGN.md](file:///d:/KULIAH/SEMESTER%204/SEMPRO%20COYYYYYYYYYY/penerjemah-sibi/DESIGN.md)**.
2. **Kepatuhan Token & Gaya**:
   - Selalu gunakan token warna yang ditentukan (Primary `#0058be`, Secondary `#00687a`, Tertiary `#825100`, Surface `#f8f9ff`, On-Surface `#0b1c30`, dll.).
   - Tipografi: Gunakan `font-heading` (`Outfit`) untuk judul/brand dan `font-sans` (`Inter`) untuk konten body dan teks UI.
   - Ikon: Gunakan Google Material Symbols Outlined (`material-symbols-outlined`), jangan menambah library icon lain.
   - Aksesibilitas: Minimal touch target 44px (`min-h-[44px]` atau `min-h-11`), pastikan kontras teks tinggi dan sediakan fallback `prefers-reduced-motion`.
3. **Larangan**: Jangan membuat gaya ad-hoc, warna acak, atau template generik yang menyimpang dari panduan `DESIGN.md`.
