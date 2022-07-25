@extends('client.template')
@section('content')
<form action="{{ route('process_checkout') }}" method="post">
    @csrf
    <div class="container mt-2 text-dark" style="margin-bottom: 150px">
        <input type="hidden" name="paket">
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
                    <label for="exampleFormControlSelect1">Provinsi</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="provinsi">
                        @foreach ($data["user"]["provinsi"] as $provinsi_item)
                        <option value="{{$provinsi_item->id}}">{{$provinsi_item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group city">
                    <label for="exampleFormControlSelect1">Kota</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="city">
                        <option value="empty">Pilih Provinsi Terlebih Dahulu</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Detail Alamat</label>
                    <textarea class="form-control" name="alamat" id="exampleFormControlTextarea1"
                        rows="3">{{$data["user"]["alamat"]}}</textarea>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Kurir</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="kurir">
                        @foreach ($data["user"]["kurir"] as $kurir_item_key =>$kurir_item_val)
                        <option value="{{$kurir_item_key}}">{{$kurir_item_val}}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="paket_destination">
                <div class="form-group paket">
                    <label for="exampleFormControlSelect1">Paket</label>
                    <select class="form-control" id="exampleFormControlSelect1" name="paket">
                        <option value="empty">Lengkapi Kota,Kurir dan Checklist Keranjang</option>
                    </select>
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
                                        data-value="{{ $cart_item->pembayaran }}" data-count="{{ $cart_item->jumlah }}"
                                        type="checkbox" id="inlineCheckbox1" name="cart[]"
                                        value="{{ $cart_item->keranjang }}" style="transform: scale(4);padding: 10px;">
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
    // TOTALS
    function total_cost(add_cost) {
        var total = 0;
        $(".total_price").html("");
        $('input:checkbox:checked').each(function(){ // iterate through each checked element.
            total += isNaN(parseInt($(this).data("value"))) ? 0 : parseInt($(this).data("value"));
        });     
        $(".total_price").html("");
        let cost_all = parseInt(total)+parseInt(add_cost)
        let price =`
        <div class="card bg-primary">
                <div class="card-body">
                    <b>Price : ${new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(cost_all)}</b>
                </div>
            </div>
        `
        $(".total_price").html(price);
    }
    // END TOTALS
    // CHEKCER
        function check_cost() {
            let kurir = $("select[name='kurir']").find("option:selected").val();
            let city = $("select[name='city']").find("option:selected").val();
            let count = $("input:checkbox:checked").data("count");
            
            if (typeof kurir != 'undefined' && typeof city != 'undefined' && city != 'empty' && typeof count != 'undefined') {
                $("select[name='paket']").find("option").remove().end();
                weight = parseInt(count) * 1400
                $.ajax({
                    type: "POST",
                    url: "/api/cost",
                    data: {
                        "city_destination":city,"weight":weight,"courier":kurir
                    },
                    dataType: "json",
                    success: function (response) {
                        $.each(response[0].costs, function (index, value) { 
                            let cost_value =value.cost[0].value;
                            let day = value.cost[0].etd
                            let the_day = day.includes("HARI")==true?day:day+" HARI";
                            let description =value.description;
                            let service = value.service
                            let cost_current = new Intl.NumberFormat("id-ID", {style: "currency",currency: "IDR"}).format(cost_value)
                            $("select[name='paket']").append(` <option value="${cost_value}">${service} ${cost_current} / estimasi ${the_day} </option>`);
                            $("input[name='paket_destination']").val(description);
                            total_cost(cost_value)
                        });
                    }
                });
            }
        }
    // END CHECKER


    // CALL PROVINCE
    $("select[name='provinsi']").change(function (e) { 
        e.preventDefault();
        $("select[name='city']").find("option").remove().end();
        let provinsi = $(this).val();
        $.ajax({
            type: "GET",
            url: `/api/getcity/${provinsi}`,
            dataType: "json",
            success: function (response) {
                $.each(response, function (indexInArray, valueOfElement) { 
                    $("select[name='city']").append(` <option value="${indexInArray}">${valueOfElement}</option>`);
                });
            }
        });
    });

    // END CALL PROVICE

    // CALL CITY
    $("select[name='city']").change(function (e) { 
        e.preventDefault();
        check_cost()
    });
    // END CALL CITY

    // CALL KURIR
    $("select[name='kurir']").change(function (e) { 
        e.preventDefault();
        check_cost()
    });
    // END CALL KURIR
    
    // CALL PAKET
    $("select[name='paket']").change(function (e) { 
        e.preventDefault();

        let cost = $(this).val();
        total_cost(cost)
    });
    // END CALL PAKET

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
});
</script>
@endpush