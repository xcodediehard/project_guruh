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
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status Transaksi</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Status Transaksi</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Detail</th>

                </tfoot>
                <tbody>
                    @foreach ($data['list'] as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            <div class="btn btn-{{$item->color}}" role="alert">
                                {{$item->transaction_status}}
                            </div>
                        </td>
                        <td>{{$item->nama}}</td>
                        <td>{{$item->alamat}} ({{$item->telpon}})</td>
                        <td>{{$item->tanggal_pesan}} </td>
                        <td>Rp.{{number_format($item->gross_amount)}}</td>
                        <td>
                            <div class="mt-2">
                                @include('components.modal',[
                                "modal"=>[
                                "color"=>"primary",
                                "id"=>"delete_komentar".$item->id,
                                "action"=>"Detail",
                                "url"=>"",
                                "content"=>"admin.contents.informasi_transaksi.components.detail_transaksi",
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