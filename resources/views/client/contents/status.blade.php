@extends('client.template')
@section('content')
<div class="container mt-2 text-dark" style="margin-bottom: 150px">
    <div class="col">
        <div class="row">
            @foreach ($data['list_transaksi'] as $list_transaksi)
            <div class="card mb-3 border border-primary" style="width: 70rem;">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="card-body text-dark">
                            <h5 class="card-title">Virtual Account ({{strtoupper($list_transaksi["bank"])}}):
                                {{$list_transaksi["va_number"]}}</h5>
                            <h5 class="font-weight-bold"> Status : {{$list_transaksi["transaction_status"]}}</h5>
                            <h5 class="font-weight-bold text-danger"> Rp. {{number_format($list_transaksi["biaya"])}}
                            </h5>
                            <p class="card-text">
                                {{$list_transaksi["nama"] ." (".$list_transaksi["telpon"].") - ".$list_transaksi["alamat"]}}
                            </p>
                            <button class="btn btn-primary" type="button" data-toggle="collapse"
                                data-target="#detrail{{$list_transaksi["va_number"]}}" aria-expanded="false"
                                aria-controls="detrail">
                                Detail Pemesanan
                            </button>
                            @if ($list_transaksi["transaction_status"] == "settlement" && $list_transaksi["keterangan"]
                            != "validation")
                            <button class="btn btn-primary validation_button" type="button" data-toggle="collapse"
                                data-target="#validation{{$list_transaksi["va_number"]}}" aria-expanded="false"
                                aria-controls="validation">
                                Validasi dan Komentar
                            </button>
                            <div class="collapse mt-3" id="validation{{$list_transaksi["va_number"]}}">
                                <div class="card">
                                    <div class="card-header">
                                        <b>Jika anda sudah menerima barang silahkan validasi dan berikan komentar serta
                                            rating, Terima kasih.</b>
                                    </div>
                                    <form action="{{ route('send_comment') }}" method="post">
                                        @csrf
                                        @foreach ($list_transaksi["detail_barang"] as $detail_barang)
                                        <input type="hidden" name="barang[]" value="{{$detail_barang["code"]}}">
                                        @endforeach
                                        <input type="hidden" name="comentar_number"
                                            value="{{$detail_barang["comentar_number"]}}">
                                        <div class="card-body">
                                            <h5>Rating dan Komentar</h5>
                                            <div class="star-score-{{$list_transaksi["va_number"]}}">
                                                <i class="fas fa-star" data-star="1"></i>
                                                <i class="fas fa-star" data-star="2"></i>
                                                <i class="fas fa-star" data-star="3"></i>
                                                <i class="fas fa-star" data-star="4"></i>
                                                <i class="fas fa-star" data-star="5"></i>
                                                <input type="hidden" name="rating_score" class="rating-value" value="3">
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="exampleFormControlTextarea1">Pesan</label>
                                                <textarea class="form-control" name="comment"
                                                    id="exampleFormControlTextarea1" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Kirim Validasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                            <div class="collapse mt-3" id="detrail{{$list_transaksi["va_number"]}}">
                                <div class="card card-body">
                                    <ul class="list-group">
                                        @foreach ($list_transaksi["detail_barang"] as $detail_barang)
                                        <li class="list-group-item">
                                            <b>{{$detail_barang["barang"]." -  size (".$detail_barang["size"].")"}}</b>
                                            <hr>
                                            <p>{{$detail_barang["jumlah"]." pcs x Rp".number_format($detail_barang["harga"]).""}}
                                            </p>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push("scripts")
<script>
    $(document).ready(function() {
    $(".validation_button").on("click", function () {
        get_data = $(this).data("target");
        current_validation = get_data.split("validation")[1]
        var $star_rating = $(`.star-score-${current_validation} .fas`);

            var SetRatingStar = function() {
            return $star_rating.each(function() {
                if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('star'))) {
                return $(this).attr("class","fas fa-star text-warning");
                } else {
                return $(this).attr("class","fas fa-star");
                }
            });
        };

        $star_rating.on('click', function() {
            $star_rating.siblings('input.rating-value').val($(this).data('star'));
            return SetRatingStar();
        });
            SetRatingStar();
    });
    

});
</script>
@endpush