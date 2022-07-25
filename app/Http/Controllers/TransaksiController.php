<?php

namespace App\Http\Controllers;

use App\Embed\Midtrans\StatusTransactionService;
use App\Http\Requests\StoreTransaksiRequest;
use App\Http\Requests\UpdateTransaksiRequest;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function informasi_transaksi()
    {
        $transaksi = Transaksi::latest()->get();
        $detail_transaksi = collect($transaksi)->map(function ($response) {
            $detail_item = DB::select('SELECT c.barang,a.jumlah FROM detail_transaksis a LEFT JOIN detail_barangs b ON a.id_detail_barang=b.id LEFT JOIN barangs c ON b.id_barang = c.id WHERE a.payment_id = ' . $response->payment_id);
            $statusTransaksi = new StatusTransactionService($response->payment_id);
            $liststatus = (object)$statusTransaksi->getstatus();

            if ($liststatus->transaction_status === 'pending') {
                $color = "warning";
            } elseif ($liststatus->transaction_status === 'expire') {
                $color = "danger";
            } else {
                $color = "primary";
            }
            $result = [
                "id" => $response->id,
                "id_pelanggan" => $response->id_pelanggan,
                "payment_id" => $response->payment_id,
                "keterangan" => $response->keterangan,
                "nama" => $response->nama,
                "telpon" => $response->telpon,
                "alamat" => $response->alamat,
                "biaya" => $response->biaya,
                "tanggal_pesan" => $response->tanggal_pesan,
                "va_numbers" => $liststatus->va_numbers,
                "gross_amount" => $liststatus->gross_amount,
                "payment_type" => $liststatus->payment_type,
                "transaction_status" => $liststatus->transaction_status,
                "color" => $color,
                "detail_transaksi" => $detail_item
            ];
            return (object)$result;
        });
        // dd($detail_transaksi);
        $data = [
            "title" => "Informasi Transaksi",
            "list" => $detail_transaksi
        ];
        return view("admin.contents.informasi_transaksi.template", compact('data'));
    }

    public function informasi_pengiriman()
    {
        $transaksi = Transaksi::latest()->get();
        $detail_transaksi = collect($transaksi)->map(function ($response) {
            $detail_item = DB::select('SELECT c.barang,a.jumlah FROM detail_transaksis a LEFT JOIN detail_barangs b ON a.id_detail_barang=b.id LEFT JOIN barangs c ON b.id_barang = c.id WHERE a.payment_id = ' . $response->payment_id);
            $statusTransaksi = new StatusTransactionService($response->payment_id);
            $liststatus = (object)$statusTransaksi->getstatus();

            if ($liststatus->transaction_status === 'pending') {
                $color = "warning";
            } elseif ($liststatus->transaction_status === 'expire') {
                $color = "danger";
            } else {
                $color = "primary";
            }
            $result = [
                "id" => $response->id,
                "id_pelanggan" => $response->id_pelanggan,
                "payment_id" => $response->payment_id,
                "keterangan" => $response->keterangan,
                "nama" => $response->nama,
                "telpon" => $response->telpon,
                "alamat" => $response->alamat,
                "biaya" => $response->biaya,
                "tanggal_pesan" => $response->tanggal_pesan,
                "va_numbers" => $liststatus->va_numbers,
                "gross_amount" => $liststatus->gross_amount,
                "payment_type" => $liststatus->payment_type,
                "transaction_status" => $liststatus->transaction_status,
                "color" => $color,
                "detail_transaksi" => $detail_item
            ];
            return (object)$result;
        });
        dd($detail_transaksi);
        // $data = [
        //     "title" => "Informasi Transaksi",
        //     "list" => $detail_transaksi
        // ];


        // return view("admin.contents.informasi_transaksi.template", compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransaksiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function show(Transaksi $transaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaksi $transaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTransaksiRequest  $request
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransaksiRequest $request, Transaksi $transaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaksi  $transaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaksi $transaksi)
    {
        //
    }
}
