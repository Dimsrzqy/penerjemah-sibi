<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizScore extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Sesuai dengan diagram: Quiz_Scores
     *
     * @var string
     */
    protected $table = 'quiz_scores';

    /**
     * Primary key dari tabel.
     * Sesuai dengan diagram: Quiz_Scores_Id
     *
     * @var string
     */
    protected $primaryKey = 'quiz_scores_id';

    /**
     * Menentukan apakah primary key auto-increment.
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
        'guest_id',
        'score',
    ];

    /**
     * Definisi type casting untuk atribut model.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'guest_id' => 'integer',
            'score' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relasi: QuizScore milik satu Guest (Many to 1).
     *
     * @return BelongsTo<Guest, $this>
     */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'guest_id');
    }
}
