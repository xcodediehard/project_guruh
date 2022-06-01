<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private function uuid_generator()
    {
        return (string) Str::uuid();
    }
    public function run()
    {
        //
        $pelanggan = [
            "name" => "Guruh Adi",
            "email" => "guruhadmi@gmail.com",
            "alamat" => "Himalaya",
            "telpon" => "085745789123",
            "forgot_password" => $this->uuid_generator(),
            "password" => bcrypt("12345678")
        ];

        Pelanggan::create($pelanggan);
    }
}
