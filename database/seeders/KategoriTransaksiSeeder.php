<?php

namespace Database\Seeders;

use App\Models\KategoriTransaksi;
use Illuminate\Database\Seeder;

class KategoriTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = (object)[
            "send" => "Send",
            "validation" => "Validation"
        ];

        foreach ($data as $key => $val) {
            KategoriTransaksi::create([
                "kategori_transaksi" => $val
            ]);
        }
    }
}
