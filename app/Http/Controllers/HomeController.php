<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\Keranjang;
use App\Models\Merek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Embed\Midtrans\CreateSnapTokenService;
use App\Embed\Midtrans\StatusTransactionService;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use stdClass;

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
    public function home_by_merek($search)
    {

        $search = str_replace("+", " ", $search);
        $data = [
            "title" => "Home",
            "cart_list" => Merek::where("merek", "LIKE", "%" . $search . "%")->latest()->first(),
            "category_list" => Merek::latest()->get()
        ];
        return view("client.contents.list_by_merek", compact("data"));
    }

    public function process_search(Request $request)
    {
        if ($request->search == "") {
            return redirect()->route("home");
        } else {
            return redirect()->route("home_by_search", ["search" => str_replace(" ", "+", $request->search)]);
        }
    }
    public function home_by_search($search)
    {
        $search = str_replace("+", " ", $search);
        if ($search != "") {
            $search_data = DB::select("SELECT * FROM `barangs` WHERE barang LIKE '%$search%'");
            $data = [
                "title" => "Home",
                "cart_list" => $search_data,
                "category_list" => Merek::latest()->get()
            ];

            return view("client.contents.list_by_name", compact("data"));
        } else {
            return redirect()->route("home");
        }
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
        $post = Keranjang::create($format);
        if ($post) {
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
            c.harga as harga,
            IF(a.jumlah >= b.stok,'full','avail') as available_stok
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
            "category_list" => Merek::latest()->get(),
            "user" => [
                "nama" => auth()->guard("client")->user()->name,
                "alamat" => auth()->guard("client")->user()->alamat,
                "telpon" => auth()->guard("client")->user()->telpon,
            ]
        ];
        return view("client.contents.keranjang", compact("data"));
    }


    public function checkout()
    {
        if (!empty(session("list_cart"))) {
            $midtrans = new CreateSnapTokenService(session("order_list"));
            $snapToken = $midtrans->getSnapToken();
            $data = [
                "title" => "Checkout",
                "list_checkout" => session("list_cart"),
                "snap" => $snapToken,
                "client" => session("list_client")
            ];
            return view("client.contents.checkout", compact("data"));
        } else {
            return redirect()->route("keranjang");
        }
    }
    public function process_checkout(Request $request)
    {
        $request->validate([
            "cart.*" => 'required|exists:keranjangs,id',
            "name" => "required",
            "telpon" => "required",
            "alamat" => "required"
        ]);
        $collection_checkout = collect($request->cart)->map(function ($id) {
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
            WHERE a.id = " . $id . "
            ");
            return $keranjang[0];
        });

        $item_details = collect($collection_checkout)->map(function ($data_item) {
            $item_detail = [
                "id" => rand(),
                "price" => $data_item->harga,
                "quantity" => $data_item->jumlah,
                "name" => $data_item->barang,
            ];
            return $item_detail;
        });

        $order = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $collection_checkout->sum("pembayaran"),
            ],
            "item_details" => $item_details->toArray(),
            'customer_details' => [
                'first_name' => $request->name,
                'email' => auth()->guard("client")->user()->email,
                'phone' => $request->telpon,
            ]
        ];
        if (!empty(session("list_cart"))) {
            session()->forget("list_cart");
            session()->forget("list_client");
            session()->forget("order_list");
            session(['list_cart' => $collection_checkout, 'list_client' => $request->only(["name", "telpon", "alamat"]), "order_list" => $order]);
        } else {
            session(['list_cart' => $collection_checkout, 'list_client' => $request->only(["name", "telpon", "alamat"]), "order_list" => $order]);
        }

        return redirect()->route("checkout");
    }

    public function delete_keranjang(Keranjang $keranjang)
    {
        $detail_barang = DetailBarang::find($keranjang->id_detail_barang);
        $format = [
            "stok" => ($detail_barang->stok + $keranjang->jumlah)
        ];
        $detail_barang->update($format);
        $keranjang->delete();

        return redirect()->route("keranjang");
    }


    public function payment(Request $request)
    {
        if ($request->detail_pemesanan != "") {
            $order_id = $request->detail_pemesanan;
            $format = [
                "payment_id" => $order_id,
                "keterangan" => "",
                "nama" => session("list_client")["name"],
                "telpon" => session("list_client")["telpon"],
                "alamat" => session("list_client")["alamat"],
                "id_pelanggan" => auth()->guard("client")->user()->id,
                "biaya" => session("list_cart")->sum("pembayaran")
            ];

            $detail_pemesanan = [];

            foreach (session("list_cart") as $item_detail) {
                $item = [
                    "payment_id" => $order_id,
                    "id_detail_barang" => $item_detail->id_detail_barang,
                    "jumlah" => $item_detail->jumlah
                ];
                DetailBarang::find($item_detail->id_detail_barang)->update([
                    "stok" => ($item_detail->detail_stok - $item_detail->jumlah)
                ]);
                Keranjang::find($item_detail->keranjang)->delete();
                array_push($detail_pemesanan, $item);
            }

            $transaksi = Transaksi::create($format);
            $detail_transaksi = DetailTransaksi::insert($detail_pemesanan);
            if ($transaksi && $detail_transaksi) {
                return redirect()->route("home");
            } else {
                return redirect()->route("home");
            }
        } else {
            return redirect()->route("keranjang");
        }
    }


    public function status_transaksi()
    {
        $list_detail_transkasi = Transaksi::where("id_pelanggan", "=", auth()->guard("client")->user()->id)->latest()->get();
        $map_detail_transkasi = collect($list_detail_transkasi)->map(function ($list) {
            $statusTransaksi = new StatusTransactionService($list->payment_id);
            $listStatusTransaksi = $statusTransaksi->getstatus();

            $list_detail_pemesanan = DB::select("SELECT a.jumlah as jumlah, b.size as size,c.barang as barang ,c.harga as harga FROM `detail_transaksis` a LEFT JOIN `detail_barangs` b ON a.id_detail_barang = b.id LEFT JOIN `barangs` c ON b.id_barang = c.id WHERE a.payment_id = '" . $list->payment_id . "'");
            $collect_detail = collect($list_detail_pemesanan)->map(function ($detail) {
                $detail_barang = [
                    "barang" => $detail->barang,
                    "size" => $detail->size,
                    "jumlah" => $detail->jumlah,
                    "harga" => $detail->harga,
                ];
                return $detail_barang;
            });

            $record = [
                "nama" => $list->nama,
                "alamat" => $list->alamat,
                "telpon" => $list->telpon,
                "biaya" => $list->biaya,
                "payment_type" => $listStatusTransaksi->payment_type,
                "transaction_status" => $listStatusTransaksi->transaction_status,
                "bank" => $listStatusTransaksi->va_numbers[0]->bank,
                "va_number" => $listStatusTransaksi->va_numbers[0]->va_number,
                "detail_barang" => $collect_detail
            ];
            return $record;
        });


        $data = [
            "title" => "Status Transaksi",
            "list_transaksi" => $map_detail_transkasi
        ];
        return view("client.contents.status", compact("data"));
    }
    public function show_snap()
    {
        $order = "703993411";
        $status_transaction = new StatusTransactionService($order);
        dd($status_transaction->getstatus());
    }
}
