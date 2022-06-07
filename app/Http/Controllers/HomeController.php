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
use App\Models\Komentar;
use App\Models\Transaksi;
use stdClass;

class HomeController extends Controller
{
    public function home()
    {
        $barang = collect(Barang::latest()->get())->map(function ($list_barang) {
            $rate = collect(DB::select("SELECT AVG(c.rate) as rating FROM barangs d RIGHT JOIN detail_barangs a ON d.id = a.id_barang RIGHT JOIN detail_transaksis b ON a.id = b.id_detail_barang RIGHT JOIN komentars c ON b.id = c.id_detail_transaksi where d.id = '" . $list_barang->id . "'"))->first();
            $data = [
                "barang" => $list_barang->barang,
                "gambar" => $list_barang->gambar,
                "harga" => $list_barang->harga,
                "score" => $rate->rating == null ? 1.00 : number_format((float)$rate->rating, 2, '.', '')
            ];
            return (object)$data;
        });
        $data = [
            "title" => "Home",
            "cart_list" => $barang,
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
        $comment = DB::select(" SELECT
                                a.komentar,a.status,a.rate,d.name,f.barang
                                FROM `komentars` a 
                                LEFT JOIN `detail_transaksis` b ON a.id_detail_transaksi=b.id 
                                LEFT JOIN `transaksis` c ON b.payment_id = c.payment_id 
                                LEFT JOIN pelanggans d ON c.id_pelanggan=d.id 
                                LEFT JOIN detail_barangs e ON b.id_detail_barang = e.id 
                                LEFT JOIN `barangs` f ON e.id_barang=f.id 
                                WHERE f.id = '" . $barang->id . "' ORDER BY a.id DESC");
        $data = [
            "title" => "Cart",
            "cart_one" => Barang::where("barang", "LIKE", $search)->first(),
            "category_list" => Merek::latest()->get(),
            "list_comment" => $comment

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
                "keterangan" => "transaction",
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
                return redirect()->route("status_transaksi");
            } else {
                return redirect()->route("home");
            }
        } else {
            return redirect()->route("keranjang");
        }
    }

    public function payment_checkout(Request $request)
    {
        $request->validate([
            "name" => "required",
            "telpon" => "required|max:15",
            "alamat" => "required",
            "book" => "required|exists:detail_barangs,id"
        ]);
        if ($request->detail_pemesanan != "") {
            $order_id = $request->detail_pemesanan;
            $list_checkout = session("data_cart_to_checkout")["list_checkout"];
            $format = [
                "payment_id" => $order_id,
                "keterangan" => "transaction",
                "nama" => $request->name,
                "telpon" => $request->telpon,
                "alamat" => $request->alamat,
                "id_pelanggan" => auth()->guard("client")->user()->id,
                "biaya" => $list_checkout["total"]
            ];

            $detail_pemesanan = [
                "payment_id" => $order_id,
                "id_detail_barang" =>  $list_checkout["code"],
                "jumlah" => $list_checkout["jumlah"]
            ];
            DetailBarang::find($list_checkout["code"])->update([
                "stok" => ($list_checkout["stok"] - $list_checkout["jumlah"])
            ]);

            $transaksi = Transaksi::create($format);
            $detail_transaksi = DetailTransaksi::insert($detail_pemesanan);
            if ($transaksi && $detail_transaksi) {
                return redirect()->route("status_transaksi");
            } else {
                return redirect()->route("home");
            }
        } else {
            return redirect()->route("cart_to_checkout");
        }
    }


    public function status_transaksi()
    {
        $list_detail_transkasi = Transaksi::where("id_pelanggan", "=", auth()->guard("client")->user()->id)->latest()->get();
        $map_detail_transkasi = collect($list_detail_transkasi)->map(function ($list) {
            $statusTransaksi = new StatusTransactionService($list->payment_id);
            $listStatusTransaksi = $statusTransaksi->getstatus();

            $list_detail_pemesanan = DB::select("SELECT a.jumlah as jumlah, b.size as size,c.barang as barang ,c.harga as harga,a.id as barcode, a.payment_id as pay FROM `detail_transaksis` a LEFT JOIN `detail_barangs` b ON a.id_detail_barang = b.id LEFT JOIN `barangs` c ON b.id_barang = c.id WHERE a.payment_id = '" . $list->payment_id . "'");
            $collect_detail = collect($list_detail_pemesanan)->map(function ($detail) {
                $detail_barang = [
                    "comentar_number" => $detail->pay,
                    "code" => $detail->barcode,
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
                "keterangan" => $list->keterangan,
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


    public function pre_checkout(Request $request)
    {
        $request->validate([
            "ukuran" => "required|exists:detail_barangs,id",
            "jumlah" => "required",
        ]);

        $detail_barang = collect(DB::select("SELECT *, IF($request->jumlah > a.stok,'full','avail') as available, ($request->jumlah * b.harga) as gross_amount,a.id as cart_code FROM detail_barangs a LEFT JOIN barangs b ON a.id_barang = b.id WHERE a.id = '" . $request->ukuran . "'"))->first();

        $list_checkout = [
            "barang" => $detail_barang->barang,
            "stok" => $detail_barang->stok,
            "code" => $detail_barang->cart_code,
            "ukuran" => $detail_barang->size,
            "harga" => $detail_barang->harga,
            "jumlah" => $request->jumlah,
            "total" => $detail_barang->gross_amount,
            "gambar" => $detail_barang->gambar
        ];

        if ($detail_barang->available == "avail") {
            $item_detail = [
                "id" => rand(),
                "price" => $detail_barang->harga,
                "quantity" => $request->jumlah,
                "name" => $detail_barang->barang,
            ];
            $order = [
                'transaction_details' => [
                    'order_id' => rand(),
                    'gross_amount' => $detail_barang->gross_amount,
                ],
                "item_details" => array($item_detail),
                'customer_details' => [
                    'first_name' => auth()->guard("client")->user()->name,
                    'email' => auth()->guard("client")->user()->email,
                    'phone' => auth()->guard("client")->user()->telpon,
                ]
            ];
            $data = [
                "title" => "Checkout",
                "client" => [
                    'name' => auth()->guard("client")->user()->name,
                    'telpon' => auth()->guard("client")->user()->telpon,
                    'alamat' => auth()->guard("client")->user()->alamat,
                ],
                "list_checkout" => $list_checkout,
                "order" => $order
            ];
            session()->forget("data_cart_to_checkout");
            session(["data_cart_to_checkout" => $data]);
            return redirect()->route("cart_to_checkout");
        } else {
            return redirect()->back();
        }
    }

    public function cart_to_checkout()
    {
        $midtrans = new CreateSnapTokenService(session("data_cart_to_checkout")["order"]);
        $snapToken = $midtrans->getSnapToken();
        session(["snap" => $snapToken]);
        $data = session("data_cart_to_checkout");
        $snap = session("snap");
        return view("client.contents.pre_checkout", compact('data', "snap"));
    }


    public function send_comment(Request $request)
    {
        $request->validate([
            "barang.*" => "required|exists:detail_transaksis,id",
            "rating_score" => "required",
            "comment" => "required",
            "comentar_number" => "exists:transaksis,payment_id"
        ]);

        foreach ($request->barang as $key => $value) {
            Komentar::create([
                "id_detail_transaksi" => $value,
                "rate" => $request->rating_score,
                "status" => "1",
                "komentar" => $request->comment
            ]);
        }

        $transaksi = Transaksi::where("payment_id", $request->comentar_number)->update(["keterangan" => "validation"]);
        if ($transaksi) {
            return redirect()->route("status_transaksi");
        } else {
            return redirect()->route("status_transaksi");
        }
    }
    public function show_snap()
    {
        $order = "703993411";
        $status_transaction = new StatusTransactionService($order);
        dd($status_transaction->getstatus());
    }
}
