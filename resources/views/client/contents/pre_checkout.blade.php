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
                <form action="{{ route('pre_checkout') }}" method="post">
                    @csrf
                    <input type="hidden" class="book" name="book" data-count="{{$data["list_checkout"]["jumlah"]}}"
                        value="{{$data["list_checkout"]["code"]}}">
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
                            <label for="exampleFormControlSelect1">Provinsi</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="provinsi">
                                @foreach ($address["provinsi"] as $provinsi_item)
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
                            <label for="exampleFormControlSelect1">Kurir</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="kurir">
                                @foreach ($address["kurir"] as $kurir_item_key =>$kurir_item_val)
                                <option value="{{$kurir_item_key}}">{{$kurir_item_val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Alamat Penerima</label>
                            <textarea class="form-control" name="alamat" id="exampleFormControlTextarea1"
                                rows="3">{{ $data["client"]["alamat"] }}</textarea>
                        </div>
                        <input type="hidden" name="paket_destination">
                        <div class="form-group paket">
                            <label for="exampleFormControlSelect1">Paket</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="paket">
                                <option value="empty">Lengkapi Kota,Kurir dan Checklist Keranjang</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger btn-lg btn-block" id="pay-button">Validasi
                        Pengiriman</button>
                </form>
            </div>
        </div>
    </div>
    @endsection
    @push("scripts")
    </script>
    <script>
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
            let count = $("input[name='book']").data("count");
            
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

    </script>
    @endpush