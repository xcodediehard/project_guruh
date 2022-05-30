@extends('client.template')
@section('content')
<div class="container mt-4">
    <div class="card ">
        <div class="card-header">
            <h4>Detail Pesanan dan Data Diri</h4>
        </div>
        <div class="card-body text-dark">
            <ul class="list-group">
                <li class="list-group-item active" aria-current="true">Detail Pemesanan</li>
                @foreach ($data["list_checkout"] as $item)
                <li class="list-group-item d-flex justify-content-start">
                    <img src="{{ asset('resources/image/barang/'.$item->gambar) }}"
                        alt="{{ asset('resources/image/barang/'.$item->gambar) }}" class="img-thumbnail"
                        style="width:8rem">
                    <div class="detail-payment ml-2 ">
                        <h4 class="card-title">{{$item->barang}}</h4>
                        <b> ({{$item->jumlah}} pcs <i class="fas fa-times"></i> Rp. {{number_format($item->harga)}})</b>
                        <hr>
                        <h3>Rp.{{number_format($item->pembayaran)}}</h3>
                    </div>
                </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-end">
                    <h4>Total : Rp.{{ number_format($data["list_checkout"]->sum("pembayaran")) }}</h4>
                </li>
            </ul>
            <div class="card my-3">
                <div class="card-header">Data Diri</div>
                <div class="card-body"></div>
                <div class="card-footer"></div>
            </div>
        </div>
        <div class=" card-footer text-dark">
            <button type="submit" class="btn btn-danger">Bayar</button>
        </div>
    </div>
</div>
@endsection