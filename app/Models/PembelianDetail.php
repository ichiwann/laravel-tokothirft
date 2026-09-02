<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $table = 'pembelian_detail';
    protected $primaryKey = 'pembelian_detail_id';

    protected $fillable = [
        'pembelian_detail_pembelian_id',
        'pembelian_detail_pakaian_id',
        'pembelian_detail_jumlah',
        'pembelian_detail_total_harga',
    ];

    // Relasi ke Pembelian
    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_detail_pembelian_id', 'pembelian_id');
    }

    // Relasi ke Pakaian
    public function pakaian()
    {
        return $this->belongsTo(Pakaian::class, 'pembelian_detail_pakaian_id', 'pakaian_id');
    }
}
