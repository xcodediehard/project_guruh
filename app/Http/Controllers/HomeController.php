<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\Keranjang;
use App\Models\Merek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function home()
    {
        $data = [
            "title" => "Home",
            "cart_list" => Barang::latest()->get(),
            "category_list" => Merek::latest()->get()
        ];
        return view("client.contents.home", compact("data"));
    }
    public function cart($title)
    {
        $search = str_replace("+", " ", $title);
        $barang = Barang::where("barang", "LIKE", $search)->first();

        $data = [
            "title" => "Cart",
            "cart_one" => Barang::where("barang", "LIKE", $search)->first(),
            "category_list" => Merek::latest()->get()

        ];
        return view("client.contents.cart", compact("data"));
    }

    public function process_keranjang(Request $request)
    {

        $request->validate([
            "ukuran" => 'required|exists:detail_barangs,id',
            "jumlah" => 'required|numeric'
        ]);

        $format = [
            "id_pelanggan" => auth()->guard("client")->user()->id,
            "id_detail_barang" => $request->ukuran,
            "jumlah" => $request->jumlah,
        ];

        $update_data = DetailBarang::find($request->ukuran);
        $new_stock = $update_data->stok - $request->jumlah;
        $update = $update_data->update([
            "stok" => $new_stock
        ]);
        $post = Keranjang::create($format);
        if ($post && $update) {
            return redirect()->route("keranjang");
        } else {
            return redirect()->back();
        }
    }

    public function keranjang(Request $request)
    {
        $keranjang = DB::select("
        SELECT 
            a.id as keranjang,
            a.id_detail_barang as id_detail_barang,
            a.jumlah as jumlah,
            (a.jumlah * c.harga) as pembayaran,
            b.size as detail_size,
            b.stok as detail_stok,
            c.barang as barang,
            c.gambar as gambar,
            c.harga as harga 
        FROM 
            keranjangs a 
        LEFT JOIN detail_barangs b 
        ON a.id_detail_barang=b.id 
        LEFT JOIN barangs c 
        ON b.id_barang = c.id
        WHERE id_pelanggan = " . auth()->guard('client')->user()->id . "
        ");
        $data = [
            "title" => "Keranjang",
            "cart_list" => $keranjang,
            "category_list" => Merek::latest()->get()
        ];
        return view("client.contents.keranjang", compact("data"));
    }

    public function process_checkout(Request $request)
    {
        dd("checkout");
        // dd($request->all());
    }
}
