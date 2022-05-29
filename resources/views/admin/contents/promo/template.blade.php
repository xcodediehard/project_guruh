@extends('admin.template')
@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">{{ $data["title"] }}</h1>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="mt-2">
        @include('components.notify')
    </div>
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">DataTables {{ $data["title"] }}</h6>
        <div class="mt-2">
            @include('components.modal',[
            "modal"=>[
            "color"=>"primary",
            "id"=>"add_promo",
            "action"=>"Add",
            "url"=>route("promo.insert"),
            "content"=>"admin.contents.promo.components.add",
            "show_title"=>True
            ]])
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Promo</th>
                        <th>Code</th>
                        <th>Diskon</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Promo</th>
                        <th>Code</th>
                        <th>Diskon</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Akhir</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                </tfoot>
                <tbody>
                    @foreach ($data["list"] as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->barang }}</td>
                        <td>{{ $item->promo }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->diskon }}</td>
                        <td>{{ $item->date_from }}</td>
                        <td>{{ $item->date_to }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"success",
                                "id"=>"update_promo".$item->id,
                                "action"=>"Update",
                                "url"=>route("promo.update",["promo"=>$item->id]),
                                "content"=>"admin.contents.promo.components.update",
                                "show_title"=>False
                                ]])
                            </div>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"danger",
                                "id"=>"delete_promo".$item->id,
                                "action"=>"Delete",
                                "url"=>route("promo.update",["promo"=>$item->id]),
                                "content"=>"admin.contents.promo.components.delete",
                                "show_title"=>False
                                ]])
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection