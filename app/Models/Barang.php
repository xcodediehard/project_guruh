<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = ['id_merek', 'barang', 'gambar', 'harga', 'keterangan'];

    public function merek_field()
    {
        return $this->belongsTo(Merek::class, 'id_merek', 'id');
    }
    public function detail_barang_field()
    {
        return $this->hasMany(DetailBarang::class, 'id_barang', 'id');
    }
}
