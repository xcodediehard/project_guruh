@extends('client.template')
@section('content')
<div class="container mt-4">
    <div class="card ">
        <div class="card-header">
            <h4>Detail Pesanan dan Data Diri</h4>
        </div>
        <div class="card-body text-dark">
            <div class="card">
                <div class="bg-primary card-header text-white font-weight-bold">
                    <h4>Check Data Pengiriman</h4>
                </div>
                <form action="{{ route('payment_checkout') }}" method="post">
                    @csrf
                    <input type="hidden" class="detail_pemesanan" name="detail_pemesanan">
                    <input type="hidden" class="book" name="book" value="{{$data["list_checkout"]["code"]}}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nama Penerima</label>
                            <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                                aria-describedby="emailHelp" value="{{ $data["client"]["name"] }}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Telpon Penerima</label>
                            <input type="text" class="form-control" name="telpon" id="exampleInputEmail1"
                                aria-describedby="emailHelp" value="{{ $data["client"]["telpon"] }}">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Alamat Penerima</label>
                            <textarea class="form-control" name="alamat" id="exampleFormControlTextarea1"
                                rows="3">{{ $data["client"]["alamat"] }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <ul class="list-group">
                <li class="list-group-item active font-weight-bold" aria-current="true">Detail Pemesanan</li>

                <li class="list-group-item d-flex justify-content-start">
                    <img src="{{ asset('resources/image/barang/'.$data["list_checkout"]["gambar"]) }}"
                        alt="{{ asset('resources/image/barang/'.$data["list_checkout"]["gambar"]) }}"
                        class="img-thumbnail" style="width:8rem">
                    <div class="detail-payment ml-2 ">
                        <h4 class="card-title">{{$data["list_checkout"]["barang"]}}</h4>
                        <b> ({{$data["list_checkout"]["jumlah"]}} pcs <i class="fas fa-times"></i> Rp.
                            {{number_format($data["list_checkout"]["harga"])}})</b>
                        <hr>
                        <h3>Rp.{{number_format($data["list_checkout"]["total"])}}</h3>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-end">
                    <h4>Total : Rp.{{ number_format($data["list_checkout"]["total"]) }}</h4>
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

        snap.pay('{{ $snap }}', {
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