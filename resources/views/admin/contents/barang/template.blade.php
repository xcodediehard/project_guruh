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
            "id"=>"add_merek",
            "action"=>"Add",
            "url"=>route("barang.insert"),
            "content"=>"admin.contents.barang.components.add",
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
                        <th>Merek</th>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th>Ukuran</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Merek</th>
                        <th>Barang</th>
                        <th>Harga</th>
                        <th>Keterangan</th>
                        <th>Ukuran</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                </tfoot>
                <tbody>
                    @foreach ($data['list'] as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->merek_field->merek }}</td>
                        <td>{{ $item->barang }}</td>
                        <td>{{ $item->harga }}</td>

                        <td>
                            @foreach ($item->detail_barang_field as $sizing)
                            {{ $sizing->size }}
                            @endforeach
                        </td>
                        <td>
                            @foreach ($item->detail_barang_field as $stocking)
                            {{ $sizing->stok }}
                            @endforeach
                        </td>
                        <td>{{ $item->keterangan }}</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"success",
                                "id"=>"update_barang".$item->id,
                                "action"=>"Update",
                                "url"=>route("barang.update",["barang"=>$item->id]),
                                "content"=>"admin.contents.barang.components.update",
                                "show_title"=>False
                                ]])
                            </div>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"danger",
                                "id"=>"delete_barang".$item->id,
                                "action"=>"Delete",
                                "url"=>route("barang.delete",["barang"=>$item->id]),
                                "content"=>"admin.contents.barang.components.delete",
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

@push("scripts")
@include('admin.contents.barang.components.scripts')
@endpush