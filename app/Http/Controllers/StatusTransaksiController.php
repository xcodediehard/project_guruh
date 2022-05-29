<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusTransaksiRequest;
use App\Http\Requests\UpdateStatusTransaksiRequest;
use App\Models\StatusTransaksi;

class StatusTransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreStatusTransaksiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStatusTransaksiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StatusTransaksi  $statusTransaksi
     * @return \Illuminate\Http\Response
     */
    public function show(StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StatusTransaksi  $statusTransaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStatusTransaksiRequest  $request
     * @param  \App\Models\StatusTransaksi  $statusTransaksi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatusTransaksiRequest $request, StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StatusTransaksi  $statusTransaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(StatusTransaksi $statusTransaksi)
    {
        //
    }
}
