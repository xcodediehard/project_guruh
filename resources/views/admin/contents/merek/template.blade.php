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
            "url"=>route('merek.insert'),
            "content"=>"admin.contents.merek.components.add",
            "show_title"=>True
            ]])
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>no</th>
                        <th>Merek</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>no</th>
                        <th>Merek</th>
                        <th>Aksi</th>
                    <tr>
                </tfoot>
                <tbody>
                    @foreach ($data["list"] as $item)

                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $item->merek }}</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"success",
                                "id"=>"update_merek".$item->id,
                                "action"=>"Update",
                                "url"=>route('merek.update',["merek"=>$item->id]),
                                "content"=>"admin.contents.merek.components.update",
                                "show_title"=>False
                                ]])
                            </div>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"danger",
                                "id"=>"delete_merek".$item->id,
                                "action"=>"Delete",
                                "url"=>route('merek.delete',["merek"=>$item->id]),
                                "content"=>"admin.contents.merek.components.delete",
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