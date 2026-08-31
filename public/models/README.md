# Panduan Model Deep Learning TensorFlow.js (SIBI)

Folder ini disiapkan untuk menyimpan file model Deep Learning TensorFlow.js yang telah Anda latih.

### Format Model yang Didukung
Aplikasi Penerjemah SIBI mendukung format model TensorFlow.js:

1. **Model Layers / Graph Model Standar (TensorFlow.js)**:
   - File utama: `model.json`
   - File bobot: `group1-shard1of1.bin` (atau beberapa file `.bin`)
   - File label metadata (opsional): `metadata.json` (berisi daftar nama kelas/isyarat)

   *Lokasi penyimpanan default*:
   ```
   public/models/sibi_model/model.json
   public/models/sibi_model/metadata.json
   public/models/sibi_model/group1-shard1of1.bin
   ```

2. **Model Teachable Machine (Google)**:
   - Jika Anda melatih model menggunakan Google Teachable Machine (Image Project), Anda bisa mengekspornya ke format **TensorFlow.js** lalu menyalin URL model (misal `https://teachablemachine.withgoogle.com/models/xxxx/`) atau mengunduh file `model.json` dan `metadata.json` ke folder ini.

---

### Cara Menggunakan Model Anda Sendiri:
1. Letakkan file model Anda di folder `public/models/sibi_model/`.
2. Buka halaman **Penerjemah** di browser.
3. Klik tombol **"Pengaturan Model" (ikon gear)** di halaman penerjemah.
4. Pilih sumber model lokal `/models/sibi_model/model.json` atau masukkan URL model Teachable Machine Anda.
5. Klik **"Muat Model"** dan aktifkan kamera.
