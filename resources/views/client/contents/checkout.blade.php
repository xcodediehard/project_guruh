@extends('client.template')
@section('content')
<div class="container mt-4">
    <div class="card ">
        <div class="card-header">
            <h4>Detail Pesanan dan Data Diri</h4>
        </div>
        <div class="card-body text-dark">
            <ul class="list-group mb-2">
                <li class="list-group-item active font-weight-bold" aria-current="true">Data Pengiriman</li>
                <li class="list-group-item d-flex justify-content-start">
                    <p>{{ $data["client"]["name"] }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-start">
                    <p>{{ $data["client"]["telpon"] }}</p>
                </li>
                <li class="list-group-item d-flex justify-content-start">
                    <p>{{ $data["client"]["alamat"] }}</p>
                </li>
            </ul>
            <ul class="list-group">
                <li class="list-group-item active font-weight-bold" aria-current="true">Detail Pemesanan</li>
                <form action="{{ route('payment') }}" method="post">
                    @csrf
                    <input type="hidden" class="detail_pemesanan" name="detail_pemesanan">
                </form>
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
                <li class="list-group-item d-flex justify-content-start">
                    <div class="detail-payment ml-2 ">
                        <h4 class="card-title">
                            Pengiriman
                            {{$data["list_packet"]->provinsi.", ".$data["list_packet"]->city.", ".$data["list_packet"]->alamat." Via ".strtoupper($data["list_packet"]->kurir." ".$data["list_packet"]->destination)}}
                        </h4>
                        <b> (Rp. {{number_format($data["list_packet"]->paket)}})</b>
                        <hr>
                        <h3>Rp.{{number_format($data["list_packet"]->paket)}}</h3>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-end">
                    <h4>Total :
                        Rp.{{ number_format($data["list_checkout"]->sum("pembayaran")+$data["list_packet"]->paket) }}
                    </h4>
                </li>
            </ul>
        </div>
        <div class=" card-footer text-dark">
            <button type="submit" class="btn btn-danger btn-lg btn-block" id="pay-button">Lakukan Pembayaran</button>
        </div>
    </div>
</div>
@endsection
@push("scripts")
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
</script>
<script>
    const payButton = document.querySelector('#pay-button');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        snap.pay('{{ $data["snap"] }}', {
            // Optional
            onSuccess: function(result) {
                // console.log("success")
                // console.log(result.order_id)
                $(".detail_pemesanan").attr("value",result.order_id);
                $("form").submit();
                // console.log(result)
            },
            // Optional
            onPending: function(result) {
                // console.log("pendding")
                // console.log(result.order_id)
                $(".detail_pemesanan").attr("value",result.order_id);
                $("form").submit();
            },
            // Optional
            onError: function(result) {
                // console.log("error")
                // console.log(result.order_id)
                $(".detail_pemesanan").attr("value",result.order_id);
                $("form").submit();
            }
        });
    });
</script>
@endpush