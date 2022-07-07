<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKomentarRequest;
use App\Http\Requests\UpdateKomentarRequest;
use App\Models\Komentar;
use Illuminate\Support\Facades\DB;

class KomentarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list_komentar = DB::select('SELECT a.id as id_komentar, a.*, d.*, f.barang FROM  komentars a LEFT JOIN detail_transaksis b ON a.id_detail_transaksi = b.id LEFT JOIN transaksis c ON b.payment_id = c.payment_id LEFT JOIN pelanggans d ON c.id_pelanggan = d.id LEFT JOIN detail_barangs e ON b.id_detail_barang = e.id LEFT JOIN barangs f ON e.id_barang = f.id');
        $data = [
            "title" => "Komentar",
            "list" => $list_komentar
        ];
        return view("admin.contents.komentar.template", compact('data'));
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
     * @param  \App\Http\Requests\StoreKomentarRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKomentarRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function show(Komentar $komentar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function edit(Komentar $komentar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKomentarRequest  $request
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKomentarRequest $request, Komentar $komentar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Komentar  $komentar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Komentar $komentar)
    {
        //
    }
}
