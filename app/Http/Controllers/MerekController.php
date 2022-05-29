<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerekRequest;
use App\Http\Requests\UpdateMerekRequest;
use App\Models\Merek;

class MerekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "title" => "Merek",
            "list" => Merek::latest()->get()
        ];
        return view("admin.contents.merek.template", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMerekRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMerekRequest $request)
    {
        $format = [
            "merek" => $request->merek,
            "id_admin" => auth()->user()->id
        ];
        $post = Merek::create($format);

        if ($post) {
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('merek.view');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('merek.view');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMerekRequest  $request
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMerekRequest $request, Merek $merek)
    {
        $merek["merek"] = $request->merek;
        $merek["id_admin"] = auth()->user()->id;
        $post = $merek->save();

        if ($post) {
            session()->flash('success', 'Data berhasil diedit');
            return redirect()->route('merek.view');
        } else {
            session()->flash('error', 'Data gagal diedit');
            return redirect()->route('merek.view');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merek $merek)
    {
        $post = $merek->delete();

        if ($post) {
            session()->flash('success', 'Data berhasil dihapus');
            return redirect()->route('merek.view');
        } else {
            session()->flash('error', 'Data gagal dihapus');
            return redirect()->route('merek.view');
        }
    }
}
