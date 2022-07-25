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
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">Name : {{ $data["client"]["name"] }}</li>
                            <li class="list-group-item">Telpon : {{ $data["client"]["telpon"] }}</li>
                            <li class="list-group-item">Alamat : {{ $data["client"]["alamat"] }},
                                {{ $data["client"]["city"] }},{{ $data["client"]["provinsi"] }}</li>
                        </ul>
                    </div>
                </form>
            </div>
            <ul class="list-group">
                <li class="list-group-item active font-weight-bold" aria-current="true">Detail Pemesanan</li>

                <li class="list-group-item d-flex justify-content-start">
                    <img src="{{ asset('resources/image/barang/'.$data["list_checkout"]->gambar) }}"
                        alt="{{ asset('resources/image/barang/'.$data["list_checkout"]->gambar) }}"
                        class="img-thumbnail" style="width:8rem">
                    <div class="detail-payment ml-2 ">
                        <h4 class="card-title">{{$data["list_checkout"]->barang}}</h4>
                        <b> ({{$data["list_checkout"]->jumlah}} pcs <i class="fas fa-times"></i> Rp.
                            {{number_format($data["list_checkout"]->harga)}})</b>
                        <hr>
                        <h3>Rp.{{number_format($data["list_checkout"]->total)}}</h3>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-end">
                    <h4>Total : Rp.{{ number_format($data["list_checkout"]->total) }}</h4>
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
    
    const payButton = document.querySelector('#pay-button');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        snap.pay('{{ $snap }}', {
            // Optional
            onSuccess: function(result) {
                // console.log("success")
                console.log(result.order_id)
                // $(".detail_pemesanan").attr("value",result.order_id);
                // $("form").submit();
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