@extends("client.template")
@section('content')
<div class="container mt-2">
    <div class="row">
        <div class="col-lg-7">

            <div class="card border border-primary" style="width:35rem">
                <img src="{{ asset('resources/image/barang/'.$data['cart_one']->gambar) }}" alt="
                {{ asset('resources/image/barang/'.$data['cart_one']->gambar) }}" class="img-thumbnile">
            </div>
        </div>
        <form action="" method="post">
            @csrf
            <div class="col-auto">
                <div class="card border border-danger" style="width:25rem">
                    <div class="card-header bg-danger text-white">
                        <h4>Detail Pemesanan</h4>
                    </div>
                    <div class="card-body text-dark">
                        <h4>{{ $data['cart_one']->barang }}</h4>
                        <hr>
                        <b>Harga:</b>
                        <h3>Rp.{{ number_format($data['cart_one']->harga) }}</h3>
                        <b>Keterangan:</b>
                        <p>{{ $data['cart_one']->keterangan }}</p>
                        <b>Pilih Ukuran:</b> <br>
                        @foreach ($data['cart_one']->detail_barang_field as $estimate)
                        @if ($estimate->stok >= 1)
                        <div class="form-check form-check">
                            <input class="form-check-input " type="radio" name="ukuran" data-stock="{{$estimate->stok}}"
                                id="inlineRadio1" value="{{$estimate->id}}">
                            <label class="form-check-label" for="inlineRadio1">{{ $estimate->size }} | Stok
                                {{ $estimate->stok }}</label>
                        </div>
                        @endif
                        @endforeach
                        <b>Jumlah</b>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary adding_values" type="button"
                                    id="button-addon1"><i class="fas fa-minus-circle"></i></button>
                            </div>
                            <input type="number" required min="1" class="form-control validate_data"
                                id="value_pemensanan" placeholder="Masukan Jumlah Pemesanan"
                                aria-label="Recipient's username" aria-describedby="button-addon2" value="1"
                                name="jumlah">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary minus_values" type="button" id="button-addon2"><i
                                        class="fas fa-plus-circle"></i></button>
                            </div>
                        </div>
                        <b>Total</b>
                        <h5 class="total_all">Rp.{{ number_format($data['cart_one']->harga) }},00</h5>
                    </div>
                    <div class="card-footer">
                        @if (!empty(auth()->guard("client")->user()))
                        <button type="submit" class="btn btn-danger add_keranjang"><i class="fas fa-cart-plus"></i>
                            Keranjang</button>
                        <button type="submit" class="btn btn-primary add_checkout"><i class="fas fa-cash-register"></i>
                            Chekout</button>
                        @else
                        <a href="{{ route('user.login') }}" class="btn btn-danger"><i class="fas fa-cart-plus"></i>
                            Keranjang</a>
                        <a href="{{ route('user.login') }}" class="btn btn-primary"><i class="fas fa-cash-register"></i>
                            Chekout</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card mt-3 mb-3 boder border-primary text-dark">
        <div class="card-header">
            <h4>Komentar</h4>
        </div>
        <div class="card-body">
            @foreach ($data["list_comment"] as $list_comment)
            <div class="card border border-primary mt-3">
                <div class="card-header bg-danger text-white">
                    <h5>{{$list_comment->name}}</h5>
                    <div class="score">
                        @for ($i = 0; $i < 5; $i++) @if ($i<$list_comment->rate)
                            <i class="fas fa-star text-warning"></i>
                            @else

                            <i class="fas fa-star"></i>
                            @endif
                            @endfor
                    </div>
                </div>
                <div class="card-body">
                    <p>{{$list_comment->komentar}}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@php
$total_all = $data['cart_one']->harga;
@endphp
@endsection

@push("scripts")
<script>
    try {
    
    jQuery(function(){
        var total_all = {{$total_all}};
        var j = jQuery; //Just a variable for using jQuery without conflicts
        var addInput = '#value_pemensanan'; //This is the id of the input you are changing
        var n = 1; //n is equal to 1

  //Set default value to n (n = 1)
    j(addInput).val(n);

  //On click add 1 to n
    j('.minus_values').on('click', function(){
    
    n += 1
    let validate_count = $("input[name='ukuran']:checked").data("stock")
    if (n <= validate_count) {
        j(addInput).val(n);
        let data = total_all * n
        $(".total_all").html("");
        $(".total_all").html(new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR"
        }).format(data));
    }
    })

    j('.adding_values').on('click', function(){
    //If n is bigger or equal to 1 subtract 1 from n
    if (n >= 1) {
        j(addInput).val(--n);
      let data = total_all * n
        $(".total_all").html("");
        $(".total_all").html(new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    }).format(data));
    } else {
        j(addInput).val(1);

    let data = total_all
        $(".total_all").html("");
        $(".total_all").html(new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR"
    }).format(data));
    }
});


// CLICK KERANJANG
$(".add_keranjang").on("click", function () {
    $("form").attr("action", "{{route('process_keranjang')}}");
});

// CLICK CHECKOUT
$(".add_checkout").on("click", function () {
    $("form").attr("action", "{{route('validation_checkout')}}");
});

$("input[name='ukuran']").on("click", function () {
    $(".validate_data").attr("max", $(this).data("stock")); 
});
});
} catch (error) {}
</script>
@endpush