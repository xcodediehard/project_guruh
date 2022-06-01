<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = ['id_pelanggan', 'payment_id', 'keterangan', 'alamat', 'nama', 'telpon', 'biaya', 'tanggal_pesan'];
}
