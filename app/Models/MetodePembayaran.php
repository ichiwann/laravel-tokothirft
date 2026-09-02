<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayaran';
    protected $primaryKey = 'metode_pembayaran_id';

    protected $fillable = [
        'metode_pembayaran_user_id',
        'metode_pembayaran_jenis',
        'metode_pembayaran_nomor',
    ];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'metode_pembayaran_user_id', 'user_id');
    }
}
