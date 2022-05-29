<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\Merek;
use Illuminate\Support\Facades\File;

class BarangController extends Controller
{
    private function insert_detail_barang($stok, $size, $id)
    {
        for ($i = 0; $i < count($stok); $i++) {
            $format = [
                'id_barang' => $id,
                'size' => $size[$i],
                'stok' => $stok[$i],
            ];
            DetailBarang::create($format);
        }

        return True;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "title" => "Barang",
            "list" => Barang::latest()->get(),
            "list_merek" => Merek::latest()->get()
        ];
        return view("admin.contents.barang.template", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreBarangRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBarangRequest $request)
    {
        //
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $format = [
            "barang" => $request->barang,
            "id_merek" => $request->merek,
            "gambar" => $filename,
            "harga" => $request->harga,
            "keterangan" => $request->katerangan,
        ];
        $post = Barang::create($format);
        $detail = $this->insert_detail_barang($request->stok, $request->size, $post->id);
        if ($detail == True) {
            $path_upload = 'resources/image/barang/';
            $file->move($path_upload, $filename);
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('barang.view');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('barang.view');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBarangRequest  $request
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBarangRequest $request, Barang $barang)
    {
        $barang["barang"] = $request->barang;
        $barang["id_merek"] = $request->merek;
        $barang["harga"] = $request->harga;
        $barang["keterangan"] = $request->keterangan;
        if ($request->file('gambar') != null) {
            $file = $request->file('gambar');
            $filename = $file->getClientOriginalName();
            $barang["gambar"] = $filename;
        }
        $post = $barang->save();

        if ($post) {
            if ($request->file('gambar') != null) {
                $path_delete = 'resources/image/barang/' . $filename;
                if (File::exists($path_delete)) {
                    File::delete($path_delete);
                }
                $path_upload = 'resources/image/barang/';
                $file->move($path_upload, $filename);
            }
            session()->flash('success', 'Data berhasil diedit');
            return redirect()->route('barang.view');
        } else {
            session()->flash('error', 'Data gagal diedit');
            return redirect()->route('barang.view');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Barang $barang)
    {
        $path_delete = 'resources/image/barang/' . $barang->gambar;
        $post = $barang->delete();

        if ($post) {
            if (File::exists($path_delete)) {
                File::delete($path_delete);
            }
            session()->flash('success', 'Data berhasil dihapus');
            return redirect()->route('barang.view');
        } else {
            session()->flash('error', 'Data gagal dihapus');
            return redirect()->route('barang.view');
        }
    }
}
