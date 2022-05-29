<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriTransaksiRequest;
use App\Http\Requests\UpdateKategoriTransaksiRequest;
use App\Models\KategoriTransaksi;

class KategoriTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ["title" => "Kategori Transaksi"];
        return view("admin.contents.kategori_transaksi.template", compact('data'));
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
     * @param  \App\Http\Requests\StoreKategoriTransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreKategoriTransaksiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\KategoriTransaksi  $kategoriTransaksi
     * @return \Illuminate\Http\Response
     */
    public function show(KategoriTransaksi $kategoriTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\KategoriTransaksi  $kategoriTransaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(KategoriTransaksi $kategoriTransaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKategoriTransaksiRequest  $request
     * @param  \App\Models\KategoriTransaksi  $kategoriTransaksi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateKategoriTransaksiRequest $request, KategoriTransaksi $kategoriTransaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\KategoriTransaksi  $kategoriTransaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(KategoriTransaksi $kategoriTransaksi)
    {
        //
    }
}
