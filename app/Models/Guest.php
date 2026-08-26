<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terhubung dengan model ini.
     *
     * @var string
     */
    protected $table = 'guests';

    /**
     * Primary key dari tabel.
     * Sesuai dengan diagram: guest_id
     *
     * @var string
     */
    protected $primaryKey = 'guest_id';

    /**
     * Menentukan apakah primary key bersifat auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'session_token',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (misal saat response JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        // 'session_token',
    ];

    /**
     * Definisi type casting untuk atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relasi: 1 Guest memiliki banyak QuizScore (1 to Many).
     *
     * @return HasMany<QuizScore, $this>
     */
    public function quizScores(): HasMany
    {
        return $this->hasMany(QuizScore::class, 'guest_id', 'guest_id');
    }
}
