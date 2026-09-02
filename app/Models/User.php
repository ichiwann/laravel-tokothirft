<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; // Nama tabel di DB
    protected $primaryKey = 'user_id'; // Primary key di DB[cite: 1]

    protected $fillable = [
        'user_username',
        'user_password',
        'user_fullname',
        'user_email',
        'user_nohp',
        'user_alamat',
        'user_profil_url',
        'user_level',
    ];

    protected $hidden = [
        'user_password',
    ];

    // Memberitahu Laravel kolom password yang digunakan
    public function getAuthPassword()
    {
        return $this->user_password;
    }

    // Relasi ke Metode Pembayaran (Satu user punya banyak metode pembayaran)
    public function metodePembayaran()
    {
        return $this->hasMany(MetodePembayaran::class, 'metode_pembayaran_user_id', 'user_id');
    }

    // Relasi ke Pembelian (Satu user punya banyak riwayat pembelian)
    public function pembelian()
    {
        return $this->hasMany(Pembelian::class, 'pembelian_user_id', 'user_id');
    }
}
