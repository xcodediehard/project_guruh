<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusTransaksi extends Model
{
    use HasFactory;

    protected $fillable = ['id_transaksi', 'id_kategori_transaksi', 'status', 'keterangan'];
}
