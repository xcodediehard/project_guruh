@extends('client.template')
@section('content')
<form action="{{ route('process_checkout') }}" method="post">
    @csrf
    <div class="container mt-2 text-dark" style="margin-bottom: 150px">
        <div class="card my-3">
            <div class="card-header">Validation Formulir Penerima</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama Penerima</label>
                    <input type="text" class="form-control" name="name" id="exampleInputEmail1"
                        aria-describedby="emailHelp" value="{{$data["user"]["nama"]}}">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Telpon Penerima</label>
                    <input type="text" class="form-control" name="telpon" id="exampleInputEmail1"
                        aria-describedby="emailHelp" value="{{$data["user"]["telpon"]}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Alamat Penerima</label>
                    <textarea class="form-control" name="alamat" id="exampleFormControlTextarea1"
                        rows="3">{{$data["user"]["alamat"]}}</textarea>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                @foreach ($data['cart_list'] as $cart_item)
                <div class="card mb-3 border border-primary" style="max-width: 70rem;">
                    @if ($cart_item->available_stok == 'full')
                    <h4 class="position-absolute ml-1 mt-1  font-weight-bolder text-danger">Not Available
                        Stock
                    </h4>
                    @endif
                    <div class="row no-gutters">
                        <div class="col-md-2 d-flex align-items-center justify-content-center my-4">
                            @if ($cart_item->available_stok == 'avail')
                            <div class="card-header">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input checkbox_data"
                                        data-value="{{ $cart_item->pembayaran }}" type="checkbox" id="inlineCheckbox1"
                                        name="cart[]" value="{{ $cart_item->keranjang }}"
                                        style="transform: scale(4);padding: 10px;">
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center my-4" style="@if ($cart_item->available_stok == 'full')
                            filter: blur(4px);
                        @endif">
                            <img src="{{ asset('resources/image/barang/'.$cart_item->gambar) }}"
                                alt="{{ asset('resources/image/barang/'.$cart_item->gambar) }}" class="w-100">
                        </div>
                        <divs class="col-md-6">
                            <div class="card-body text-dark" style="@if ($cart_item->available_stok == 'full')
                                filter: blur(4px);
                            @endif">
                                <h5 class="card-title"><b>{{ $cart_item->barang }}</b></h5>
                                <p class="card-text">
                                    <b>Size &emsp;&emsp;: </b> {{ $cart_item->detail_size}} <br>
                                    <b>Harga &emsp;: </b> Rp.{{ number_format($cart_item->harga) }} <br>
                                    <b>Jumlah&emsp;: </b> {{ $cart_item->jumlah}} pasang<br>
                                    <hr>
                                <div class="text-primary">
                                    <b>Total:</b>
                                    <h5> <b>Rp.{{ number_format($cart_item->pembayaran) }}</b></h5>
                                </div>
                                </p>
                            </div>
                        </divs>
                        <div class="col-md-2 d-flex justify-content-end ">
                            <div class="card-footer bg-white">
                                <a href="{{ route('delete_keranjang', ["keranjang"=>$cart_item->keranjang]) }}"
                                    class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @include("client.components.checkoutbar")
</form>
@endsection
@push("scripts")
<script>
    let price =`
    <div class="card bg-primary">
            <div class="card-body">
                <b>Price : ${new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(0)}</b>
            </div>
        </div>`
    $(".total_price").html(price);
    $(".checkout_button").hide();
    $('input:checkbox').change(function (){
        if($("input:checkbox").is(":checked")){
            $(".checkout_button").show();
        }else{
            $(".checkout_button").hide();
        }
        var total = 0;
        $('input:checkbox:checked').each(function(){ // iterate through each checked element.
            total += isNaN(parseInt($(this).data("value"))) ? 0 : parseInt($(this).data("value"));
        });     
        $(".total_price").html("");
        let price =`
        <div class="card bg-primary">
                <div class="card-body">
                    <b>Price : ${new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(total)}</b>
                </div>
            </div>
        `
        $(".total_price").html(price);

});
</script>
@endpush