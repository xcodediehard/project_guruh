<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePromoRequest;
use App\Http\Requests\UpdatePromoRequest;
use App\Models\Barang;
use App\Models\Promo;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "title" => "Promo",
            "list" => Promo::latest()->get(),
            "list_barang" => Barang::latest()->get()
        ];
        return view("admin.contents.promo.template", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePromoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePromoRequest $request)
    {
        $format = [
            "id_barang" => $request->barang,
            "promo" => $request->promo,
            "code" => $request->code,
            "keterangan" => $request->keterangan,
            "diskon" => $request->diskon,
            "date_from" => $request->date_from,
            "date_start" => $request->date_start,
        ];
        $post = Promo::create($format);
        if ($post) {
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('promo.view');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('promo.view');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePromoRequest  $request
     * @param  \App\Models\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePromoRequest $request, Promo $promo)
    {

        $promo["id_barang"] = $request->barang;
        $promo["promo"] = $request->promo;
        $promo["code"] = $request->code;
        $promo["keterangan"] = $request->keterangan;
        $promo["diskon"] = $request->diskon;
        $promo["date_from"] = $request->date_from;
        $promo["date_start"] = $request->date_start;

        $post = $promo->save();

        if ($post) {
            session()->flash('success', 'Data berhasil diedit');
            return redirect()->route('promo.view');
        } else {
            session()->flash('error', 'Data gagal diedit');
            return redirect()->route('promo.view');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promo  $promo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promo $promo)
    {
        //
        $post = $promo->delete();

        if ($post) {
            session()->flash('success', 'Data berhasil dihapus');
            return redirect()->route('promo.view');
        } else {
            session()->flash('error', 'Data gagal dihapus');
            return redirect()->route('promo.view');
        }
    }
}
