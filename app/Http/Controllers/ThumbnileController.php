<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThumbnileRequest;
use App\Http\Requests\UpdateThumbnileRequest;
use App\Models\Thumbnile;
use Illuminate\Support\Facades\File;

class ThumbnileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ["title" => "Thumbnile"];
        return view("admin.contents.thumbnile.template", compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreThumbnileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreThumbnileRequest $request)
    {
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $format = [
            "image_thumbnile" => $filename,
        ];
        $post = Thumbnile::create($format);
        if ($post) {
            $path_upload = 'resources/thumbnile/';
            $file->move($path_upload, $filename);
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('thumbnile.view');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('thumbnile.view');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateThumbnileRequest  $request
     * @param  \App\Models\Thumbnile  $thumbnile
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateThumbnileRequest $request, Thumbnile $thumbnile)
    {
        //
        if ($request->file('image') != null) {
            $file = $request->file('image');
            $filename = $file->getClientOriginalName();
            $thumbnile["image_thumbnile"] = $filename;
        }
        $post = $thumbnile->save();

        if ($post) {
            if ($request->file('image') != null) {
                $path_delete = 'resources/thumbnile/' . $filename;
                if (File::exists($path_delete)) {
                    File::delete($path_delete);
                }
                $path_upload = 'resources/thumbnile/';
                $file->move($path_upload, $filename);
            }
            session()->flash('success', 'Data berhasil diedit');
            return redirect()->route('thumbnile.view');
        } else {
            session()->flash('error', 'Data gagal diedit');
            return redirect()->route('thumbnile.view');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Thumbnile  $thumbnile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thumbnile $thumbnile)
    {
        $path_delete = 'resources/thumbnile/' . $thumbnile->gambar;
        $post = $thumbnile->delete();

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
