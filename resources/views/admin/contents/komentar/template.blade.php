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
            "url"=>route("komentar.insert"),
            "content"=>"admin.contents.komentar.components.add",
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
                        <th>Name</th>
                        <th>Barang</th>
                        <th>Komentar</th>
                        <th>Rate</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Barang</th>
                        <th>Komentar</th>
                        <th>Rate</th>
                        <th>Aksi</th>
                    <tr>
                </tfoot>
                <tbody>
                    @foreach ($data['list'] as $item)

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->barang }}</td>
                        <td>{{ $item->komentar }}</td>
                        <td>{{ $item->rate }}</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"success",
                                "id"=>"update_komentar".$item->id,
                                "action"=>"Update",
                                "url"=>route("komentar.update",["komentar"=>$item->id]),
                                "content"=>"admin.contents.komentar.components.update",
                                "show_title"=>False
                                ]])
                            </div>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"danger",
                                "id"=>"delete_komentar".$item->id,
                                "action"=>"Delete",
                                "url"=>route("komentar.delete",["komentar"=>$item->id]),
                                "content"=>"admin.contents.komentar.components.delete",
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
@include('admin.contents.komentar.components.scripts')
@endpush