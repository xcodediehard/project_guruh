<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $fillable = ['id_pelanggan', 'id_detail_barang', 'jumlah'];

    public function field_detail_barang()
    {
        return $this->belongsTo(DetailBarang::class, "id_detail_barang", "id");
    }
}
