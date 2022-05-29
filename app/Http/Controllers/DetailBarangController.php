<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetailBarangRequest;
use App\Http\Requests\UpdateDetailBarangRequest;
use App\Models\DetailBarang;

class DetailBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ["title" => "Detail Barang"];
        return view("admin.contents.detail_barang.template", compact('data'));
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
     * @param  \App\Http\Requests\StoreDetailBarangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDetailBarangRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DetailBarang  $detailBarang
     * @return \Illuminate\Http\Response
     */
    public function show(DetailBarang $detailBarang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DetailBarang  $detailBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailBarang $detailBarang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDetailBarangRequest  $request
     * @param  \App\Models\DetailBarang  $detailBarang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDetailBarangRequest $request, DetailBarang $detailBarang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetailBarang  $detailBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailBarang $detailBarang)
    {
        //
    }
}
