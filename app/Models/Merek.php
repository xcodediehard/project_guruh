<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merek extends Model
{
    use HasFactory;
    protected $fillable = ["merek", "id_admin"];

    public function barang_list()
    {
        return $this->hasMany(Barang::class, "id_merek", "id");
    }
}
