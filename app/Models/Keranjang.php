<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';
    protected $primaryKey = 'keranjang_id';

    protected $fillable = [
        'keranjang_user_id',
        'keranjang_pakaian_id',
        'keranjang_jumlah',
    ];

    // Relasi ke tabel User
    public function user()
    {
        return $this->belongsTo(User::class, 'keranjang_user_id', 'user_id');
    }

    // Relasi ke tabel Pakaian
    public function pakaian()
    {
        return $this->belongsTo(Pakaian::class, 'keranjang_pakaian_id', 'pakaian_id');
    }
}
