<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\EncryptCast;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'isi_jurnal',
        'skor_mood',
        'skor_kecemasan',
        'skor_stres',
    ];
    protected $casts = [
        'isi_jurnal' => EncryptCast::class,
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
