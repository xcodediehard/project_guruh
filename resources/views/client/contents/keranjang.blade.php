@extends('client.template')
@section('content')
<form action="{{ route('process_checkout') }}" method="post">
    @csrf
    <div class="container mt-2" style="margin-bottom: 150px">
        <div class="col">
            <div class="row">
                @foreach ($data['cart_list'] as $cart_item)
                <div class="card mb-3 border border-primary" style="max-width: 60rem;">
                    <div class="row no-gutters">
                        <div class="col-md-2 d-flex align-items-center justify-content-center my-4">
                            <div class="card-header">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input checkbox_data"
                                        data-value="{{ $cart_item->pembayaran }}" type="checkbox" id="inlineCheckbox1"
                                        name="cart[]" value="{{ $cart_item->keranjang }}"
                                        style="transform: scale(4);padding: 10px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center my-4">
                            <img src="{{ asset('resources/image/barang/'.$cart_item->gambar) }}"
                                alt="{{ asset('resources/image/barang/'.$cart_item->gambar) }}" class="w-100">
                        </div>
                        <divs class="col-md-6">
                            <div class="card-body text-dark">
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
                                <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
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
        </div>
      `
      $(".total_price").html(price);
    $('input:checkbox').change(function ()
{
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