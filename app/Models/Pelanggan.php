<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pelanggan extends Authenticatable
{
    use Notifiable;
    protected $guard = "client";
    protected $fillable = ['name', 'alamat', 'email', 'telpon', 'forgot_password', 'password'];
}
