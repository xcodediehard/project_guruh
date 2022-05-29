<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    use HasFactory;

    protected $fillable = ['id_barang', 'size', 'stok'];

    public function field_barang()
    {
        return $this->belongsTo(Barang::class, "id_barang", "id");
    }
}
