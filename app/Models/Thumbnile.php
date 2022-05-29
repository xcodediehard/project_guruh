<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbnile extends Model
{
    use HasFactory;

    protected $fillable = ['id_admin', 'image_thumbnile'];
}
