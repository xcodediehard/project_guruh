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
            "url"=>"#",
            "content"=>"admin.contents.thumbnile.components.add",
            "show_title"=>True
            ]])
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Merek</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Merek</th>
                        <th>Aksi</th>
                    <tr>
                </tfoot>
                <tbody>
                    <tr>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"success",
                                "id"=>"update_merek",
                                "action"=>"Update",
                                "url"=>"#",
                                "content"=>"admin.contents.thumbnile.components.update",
                                "show_title"=>False
                                ]])
                            </div>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"danger",
                                "id"=>"delete_merek",
                                "action"=>"Delete",
                                "url"=>"#",
                                "content"=>"admin.contents.thumbnile.components.delete",
                                "show_title"=>False
                                ]])
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push("scripts")
@include('admin.contents.thumbnile.components.scripts')
@endpush