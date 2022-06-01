@extends('client.template')
@section('content')
<div class="container mt-2 text-dark" style="margin-bottom: 150px">
    <div class="col">
        <div class="row">
            @foreach ($data['list_transaksi'] as $list_transaksi)
            <div class="card mb-3 border border-primary" style="width: 70rem;">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="card-body text-dark">
                            <h5 class="card-title">Virtual Account ({{strtoupper($list_transaksi["bank"])}}):
                                {{$list_transaksi["va_number"]}}</h5>
                            <h5 class="font-weight-bold"> Status : {{$list_transaksi["transaction_status"]}}</h5>
                            <h5 class="font-weight-bold text-danger"> Rp. {{number_format($list_transaksi["biaya"])}}
                            </h5>
                            <p class="card-text">
                                {{$list_transaksi["nama"] ." (".$list_transaksi["telpon"].") - ".$list_transaksi["alamat"]}}
                            </p>
                            <button class="btn btn-primary" type="button" data-toggle="collapse"
                                data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Detail Pemesanan
                            </button>
                            <div class="collapse mt-3" id="collapseExample">
                                <div class="card card-body">
                                    <ul class="list-group">
                                        @foreach ($list_transaksi["detail_barang"] as $detail_barang)
                                        <li class="list-group-item">
                                            <b>{{$detail_barang["barang"]." -  size (".$detail_barang["size"].")"}}</b>
                                            <hr>
                                            <p>{{$detail_barang["jumlah"]." pcs x Rp".number_format($detail_barang["harga"]).""}}
                                            </p>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection