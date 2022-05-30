@extends('client.template')
@section('content')
<div class="row mt-2">
    <div class="col-2 ml-2 container">
        <ul class="list-group">
            <li class="list-group-item bg-danger text-white " aria-current="true">Merek Sepatu</li>
            @foreach ($data["category_list"] as $category_item)
            <li class="list-group-item text-dark">{{$category_item->merek}}</li>
            @endforeach
        </ul>
    </div>
    <div class="col">
        <div class="row">
            @foreach ($data['cart_list'] as $cart_item)
            <a href="{{ route('cart', ['title'=>str_replace(" ","+",$cart_item->barang)]) }}" class="btn btn-light">
                <div class="card m-2 border border-primary" style="width:18rem">
                    <img src="{{ asset('resources/image/barang/'.$cart_item->gambar) }}"
                        alt="{{ asset('resources/image/barang/'.$cart_item->gambar) }}" class="img-thumbnile">
                    <div class="card-body text-dark">
                        <h4>{{ $cart_item->barang }}</h4>
                        <hr>
                        <h5>Rp.{{ number_format($cart_item->harga) }}</h5>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection