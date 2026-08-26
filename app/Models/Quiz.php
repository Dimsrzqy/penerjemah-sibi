<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * Sesuai dengan diagram: quizzes
     *
     * @var string
     */
    protected $table = 'quizzes';

    /**
     * Primary key dari tabel.
     * Sesuai dengan diagram: quizzes_id
     *
     * @var string
     */
    protected $primaryKey = 'quizzes_id';

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
        'word_target',
        'difficulty',
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
}
