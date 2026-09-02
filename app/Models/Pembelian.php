<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';
    protected $primaryKey = 'pembelian_id';

    protected $fillable = [
        'pembelian_user_id',
        'pembelian_metode_pembayaran_id',
        'pembelian_tanggal',
        'pembelian_total_harga',
        'pembelian_status',
    ];

    // Relasi ke User / Pembeli
    public function user()
    {
        return $this->belongsTo(User::class, 'pembelian_user_id', 'user_id');
    }

    // Relasi ke detail items (User controller)
    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_detail_pembelian_id', 'pembelian_id');
    }

    // Relasi ke detail items (Admin controller)
    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_detail_pembelian_id', 'pembelian_id');
    }

    // Relasi ke Metode Pembayaran
    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'pembelian_metode_pembayaran_id', 'metode_pembayaran_id');
    }
}
