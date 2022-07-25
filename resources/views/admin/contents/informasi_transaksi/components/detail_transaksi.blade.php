<ul class="list-group">
    <li class="list-group-item active" aria-current="true">Tanggal Pesan : {{$item->tanggal_pesan}}</li>
    <li class="list-group-item">
        <label for="">
            Nama:
        </label>
        <p>{{$item->nama}}</p>
    </li>
    <li class="list-group-item">
        <label for="">Alamat Pengiriman</label>
        <p>{{$item->alamat}}</p>
    </li>
    <li class="list-group-item">
        <label for="">Telpon</label>
        <p>{{$item->telpon}}</p>
    </li>
    <li class="list-group-item">
        <label for="">Biaya</label>
        <p>Rp.{{number_format($item->gross_amount)}}</p>
    </li>
    <li class="list-group-item">
        <label for="">Payment Type</label>
        <p>{{strtoupper(str_replace("_"," ",$item->payment_type))}} ( {{strtoupper($item->va_numbers[0]->bank)}} /
            {{$item->va_numbers[0]->va_number}})</p>
    </li>

    <li class="list-group-item">
        <label for="">Detail Pesanan</label>
        <ul>
            @foreach ($item->detail_transaksi as $items)
            <li>{{$items->barang}} / {{$items->jumlah}}</li>
            @endforeach
        </ul>
    </li>

</ul>