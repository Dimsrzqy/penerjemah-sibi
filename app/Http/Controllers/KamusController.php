<?php

namespace App\Http\Controllers;

use App\Models\Kamus;
use Illuminate\View\View;

class KamusController extends Controller
{
    /**
     * Tampilkan halaman utama Kamus SIBI yang terhubung dengan tabel database.
     */
    public function index(): View
    {
        try {
            $kamus = Kamus::orderBy('word', 'asc')->get();

            if ($kamus->isEmpty()) {
                $kamus = collect($this->getDefaultKamus());
            }
        } catch (\Throwable $e) {
            $kamus = collect($this->getDefaultKamus());
        }

        // Ambil daftar kategori unik dari data kamus
        $categories = $kamus->pluck('category')
            ->filter()
            ->unique()
            ->values();

        return view('kamus', compact('kamus', 'categories'));
    }

    /**
     * Data default kamus jika tabel belum terisi data.
     */
    private function getDefaultKamus(): array
    {
        return [
            (object)[
                'kamus_id' => 1,
                'word' => 'Adik',
                'category' => 'Kata Benda',
                'description' => 'Gerakan isyarat SIBI untuk menunjukkan kata Adik.',
                'video_path' => 'videos/adik.mp4',
            ],
            (object)[
                'kamus_id' => 2,
                'word' => 'Apa',
                'category' => 'Ungkapan',
                'description' => 'Gerakan isyarat SIBI untuk kata tanya Apa.',
                'video_path' => 'videos/apa.mp4',
            ],
            (object)[
                'kamus_id' => 3,
                'word' => 'Makan',
                'category' => 'Kata Kerja',
                'description' => 'Gerakan mendekatkan tangan ke mulut menirukan makan.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBftmkxrqoUjGInkhXSuG0zgEYMn1briscxM-wIxvW9Aywmv7G4dLqcAsoIIaXQ2WeBATtcZE6cbSs7glZ22ApR5igs3jMshZYxjHceE29ip57bgajRuvUJgLK8GJZpqFvtGdIu1j6HvM3GxaD44JHOYnDzJOdl588C4XpKGWT1V8OgMdYgEfjvQ2ZOTUZkCq1LMxoO7dn9osa4jXYpkllfjWHC_gF-vz8mtGw5rMH7OVLVN9c0gJ7G',
            ],
            (object)[
                'kamus_id' => 4,
                'word' => 'Maaf',
                'category' => 'Kata Sifat',
                'description' => 'Telapak tangan terbuka memutar perlahan di dada.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD7twOiPdRGVmwC9bskNu92kHS41oisXBERt7kgN85UYZikrWal5jZoQAJgZltwUHIkLchyLtmijr4sNYYIm5pgcp4PrCN1xLCpCgHrsKKwC88_QVZJzXIJo24HrNutSl7M00B-ZD_ro2Zz4SApcOT5Ht-S0LU_Qjy0Wbw8PCvL2andqJzMndViPSjiAUi0TW4HoQZTLuxHLugp2XTtMKaIq8n31Rm5drcT9-FOVdsDVvI1Tz9v10e3',
            ],
            (object)[
                'kamus_id' => 5,
                'word' => 'Rumah',
                'category' => 'Kata Benda',
                'description' => 'Kedua ujung jari bertemu membentuk atap segitiga.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDmRswgf5Heve0-Oor8MzSKz069xs4AsEYFnJm2gKcMRxaHePATeTVvvNZ6cvc1UnO6N9lJBMNdFdckE95mSKAZsJO2i3HvNNSRfngfDcnkDn7ioZFhEb23O2k9SiuC3pB8Re3WR7saJvnSeN3MCEYnOCDpfiskalH8a7PnGl1R6Qx9GPR_1F7gr7O1ZnFxurAJQ2CL1zSvxpBu4JCXT_VZLlASrnMQbjrYEXVXvs33OhdnHo9JVg8H',
            ],
            (object)[
                'kamus_id' => 6,
                'word' => 'Teman',
                'category' => 'Kata Benda',
                'description' => 'Kedua jari telunjuk saling mengait secara bersahabat.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCLvjqFafe4f4il5muBusBMAK6OD41Ol0erlPfpZKGhrElo5a5ES4Q5NAABetUjOafJvVgFTXZfmINXiewoJBhN_H7Vjeb0lxrpKtxyNpGQkAeVnokBhXpnuucHdofNsY8cEdeFuzcQkyI2XWThSIHvKYq5H6czDlBVb0G_C-DtcTgdVXBr2_tlDp0AZL_8_VT-6PZtqHpzUKKfxXwKh1SiVy2tfH7NVsIYXWdWPnVXCKUWVpgUCej_',
            ],
            (object)[
                'kamus_id' => 7,
                'word' => 'Terima Kasih',
                'category' => 'Ungkapan',
                'description' => 'Tangan diletakkan di bibir lalu digerakkan maju ke depan.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuD7twOiPdRGVmwC9bskNu92kHS41oisXBERt7kgN85UYZikrWal5jZoQAJgZltwUHIkLchyLtmijr4sNYYIm5pgcp4PrCN1xLCpCgHrsKKwC88_QVZJzXIJo24HrNutSl7M00B-ZD_ro2Zz4SApcOT5Ht-S0LU_Qjy0Wbw8PCvL2andqJzMndViPSjiAUi0TW4HoQZTLuxHLugp2XTtMKaIq8n31Rm5drcT9-FOVdsDVvI1Tz9v10e3',
            ],
            (object)[
                'kamus_id' => 8,
                'word' => 'Belajar',
                'category' => 'Kata Kerja',
                'description' => 'Mengambil dari telapak tangan kiri dan ditempelkan ke dahi.',
                'video_path' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBftmkxrqoUjGInkhXSuG0zgEYMn1briscxM-wIxvW9Aywmv7G4dLqcAsoIIaXQ2WeBATtcZE6cbSs7glZ22ApR5igs3jMshZYxjHceE29ip57bgajRuvUJgLK8GJZpqFvtGdIu1j6HvM3GxaD44JHOYnDzJOdl588C4XpKGWT1V8OgMdYgEfjvQ2ZOTUZkCq1LMxoO7dn9osa4jXYpkllfjWHC_gF-vz8mtGw5rMH7OVLVN9c0gJ7G',
            ],
        ];
    }
}
