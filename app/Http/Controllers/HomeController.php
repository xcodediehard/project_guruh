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
use App\Models\City;
use App\Models\DetailTransaksi;
use App\Models\Komentar;
use App\Models\Provice;
use App\Models\StatusTransaksi;
use App\Models\Transaksi;
use Kavist\RajaOngkir\Facades\RajaOngkir;
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
                "provinsi" => Provice::all(),
                "kurir" => (object) [
                    "jne" => "JNE",
                    "pos" => "POS",
                    "tiki" => "TIKI"
                ]
            ]
        ];
        return view("client.contents.keranjang", compact("data"));
    }

    public function getCities($id)
    {
        $city = City::where('province_id', $id)->pluck('name', 'city_id');
        return response()->json($city);
    }

    public function check_ongkir(Request $request)
    {
        $cost = RajaOngkir::ongkosKirim([
            'origin'        => 135, // ID kota/kabupaten asal
            'destination'   => $request->city_destination, // ID kota/kabupaten tujuan
            'weight'        => $request->weight, // berat barang dalam gram
            'courier'       => $request->courier // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
        ])->get();


        return response()->json($cost);
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
                "client" => session("list_client"),
                "list_packet" => session("list_packet")
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
            "alamat" => "required",
            "provinsi" => "required|exists:provices,id",
            "city" => "required|exists:cities,id",
            "kurir" => "required",
            "paket" => "required",
            "paket_destination" => "required"
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

        $address = DB::select("SELECT a.name as city_name ,b.name as province_name FROM cities a LEFT JOIN provices b ON a.province_id=b.id WHERE a.id = $request->city");

        $address_list = (object)[
            "provinsi" => $address[0]->province_name,
            "city" => $address[0]->city_name,
            "alamat" => $request->alamat,
            "paket" => $request->paket,
            "destination" => $request->paket_destination,
            "kurir" => $request->kurir
        ];
        $name_destination = $request->kurir . "(" . $request->paket_destination . ") ";
        $item_detination = [
            "id" => rand(),
            "price" => $request->paket,
            "quantity" => 1,
            "name" => $name_destination,
        ];

        $item_details = collect($collection_checkout)->map(function ($data_item) {
            $item_detail = [
                "id" => rand(),
                "price" => $data_item->harga,
                "quantity" => $data_item->jumlah,
                "name" => $data_item->barang,
            ];
            return $item_detail;
        });

        $list_item = $item_details->toArray();
        array_push($list_item, $item_detination);
        $cost_all = $collection_checkout->sum("pembayaran") + $request->paket;
        $order = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $cost_all,
            ],
            "item_details" => $list_item,
            'customer_details' => [
                'first_name' => $request->name,
                'email' => auth()->guard("client")->user()->email,
                'phone' => $request->telpon,
            ]
        ];


        if (!empty(session("list_cart"))) {
            session()->forget("list_cart");
            session()->forget("list_packet");
            session()->forget("list_client");
            session()->forget("order_list");
            session(['list_cart' => $collection_checkout, "list_packet" => $address_list, 'list_client' => $request->only(["name", "telpon", "alamat"]), "order_list" => $order]);
        } else {
            session(['list_cart' => $collection_checkout, "list_packet" => $address_list, 'list_client' => $request->only(["name", "telpon", "alamat"]), "order_list" => $order]);
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
                "alamat" => session("list_packet")->provinsi . ", " . session("list_packet")->city . ", " . session("list_client")["alamat"],
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
            $status_pengiriman = StatusTransaksi::insert([
                [
                    "id_kategori_transaksi" => "1",
                    "id_transaksi" => $transaksi->id,
                    "status" => "0",
                    "keterangan" => strtoupper(session("list_packet")->kurir) . " paket " . session("list_packet")->destination
                ],
                [
                    "id_kategori_transaksi" => "2",
                    "id_transaksi" => $transaksi->id,
                    "status" => "0",
                    "keterangan" => ""
                ]
            ]);
            if ($transaksi && $detail_transaksi && $status_pengiriman) {
                return redirect()->route("status_transaksi");
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
                "biaya" => $listStatusTransaksi->gross_amount,
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



    // CHECKOUT
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


    public function pre_checkout(Request $request)
    {

        $provinsi = collect(DB::select("SELECT * FROM provices WHERE province_id = " . $request->provinsi))->first();
        $city = collect(DB::select("SELECT * FROM `cities` WHERE city_id =" . $request->city))->first();

        $remap = $request->merge(["city_name" => $city->name, "province_name" => $provinsi->name]);
        // dd($remap->paket_destination);

        $detail_barang = (object)session("data_cart_to_checkout")["list_checkout"];

        $item_detail = [
            [
                "id" => rand(),
                "price" => $detail_barang->harga,
                "quantity" => $detail_barang->jumlah,
                "name" => $detail_barang->barang,
            ],
            [
                "id" => rand(),
                "price" => $remap->paket,
                "quantity" => 1,
                "name" => $remap->paket_destination,
            ],
        ];

        $total = collect($item_detail)->map(function ($response) {
            return ($response["price"] * $response["quantity"]);
        })->sum();
        $order = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $total,
            ],
            "item_details" => $item_detail,
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
                'alamat' => $remap->alamat,
                "provinsi" => $remap->province_name,
                "city" => $remap->city_name
            ],
            "list_checkout" => $detail_barang,
            "order" => $order
        ];
        session()->forget("data_cart_to_checkout");
        session(["data_cart_to_checkout" => $data]);
        return redirect()->route("validation_payment");
    }

    public function validation_payment()
    {
        $midtrans = new CreateSnapTokenService(session("data_cart_to_checkout")["order"]);
        $snapToken = $midtrans->getSnapToken();
        $data = session("data_cart_to_checkout");
        $snap = $snapToken;
        $address = [
            "provinsi" => Provice::all(),
            "kurir" => (object) [
                "jne" => "JNE",
                "pos" => "POS",
                "tiki" => "TIKI"
            ]
        ];
        return view("client.contents.validation_checkout", compact('data', "snap", "address"));
    }

    public function cart_to_checkout()
    {

        $data = session("data_cart_to_checkout");
        $address = [
            "provinsi" => Provice::all(),
            "kurir" => (object) [
                "jne" => "JNE",
                "pos" => "POS",
                "tiki" => "TIKI"
            ]
        ];
        return view("client.contents.pre_checkout", compact('data', "address"));
    }

    public function validation_checkout(Request $request)
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
            $data = [
                "title" => "Checkout",
                "client" => [
                    'name' => auth()->guard("client")->user()->name,
                    'telpon' => auth()->guard("client")->user()->telpon,
                    'alamat' => auth()->guard("client")->user()->alamat,
                ],
                "list_checkout" => $list_checkout,
            ];
            session()->forget("data_cart_to_checkout");
            session(["data_cart_to_checkout" => $data]);
            return redirect()->route("cart_to_checkout");
        } else {
            return redirect()->back();
        }
    }

    // END CHECKOUT

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
