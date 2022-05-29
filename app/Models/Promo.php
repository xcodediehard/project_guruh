<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = ['id_barang', 'promo', 'code', 'keterangan', 'diskon', 'date_from', 'date_to'];
}
