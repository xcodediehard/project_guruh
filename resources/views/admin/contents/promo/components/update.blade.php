@csrf
@method("PUT")
<div class="form-group">
    <label for="exampleFormControlSelect1">Barang</label>
    <select class="form-control  @include('components.invalid',['error'=>'barang'])" name="barang"
        id="exampleFormControlSelect1">
        @foreach ($data['list_barang'] as $item_barang)
        <option value="{{ $item_barang->id }}" @if ($item_barang->id == $item->id_barang)
            selected
            @endif>{{ $item_barang->barang }}</option>
        @endforeach
    </select>
    @include('components.alert',['error'=>'barang'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Promo</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'promo'])" id="exampleInputEmail1"
        aria-describedby="emailHelp" name="promo" value="{{ $item->promo }}">
    @include('components.alert',['error'=>'promo'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Code Promo</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'code_promo'])"
        id="exampleInputEmail1" aria-describedby="emailHelp" name="code_promo" value="{{ $item->code }}">
    @include('components.alert',['error'=>'code_promo'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Potongan Diskon (Rp)</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Rp</span>
        </div>
        <input type="int" class="form-control  @include('components.invalid',['error'=>'diskon'])"
            id="exampleInputEmail1" aria-describedby="emailHelp" name="diskon" value="{{ $item->diskon }}">
    </div>
    @include('components.alert',['error'=>'diskon'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Tanggal / Mulai Promo</label>
    <input type="date" class="form-control  @include('components.invalid',['error'=>'date_start'])"
        id="exampleInputEmail1" aria-describedby="emailHelp" name="date_start" value="{{ $item->date_from}}">
    @include('components.alert',['error'=>'date_start'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Tanggal / Berhenti Promo</label>
    <input type="date" class="form-control  @include('components.invalid',['error'=>'date_stop'])"
        id="exampleInputEmail1" aria-describedby="emailHelp" name="date_stop" value="{{ $item->date_to }}">
    @include('components.alert',['error'=>'date_stop'])
</div>

<div class="form-group">
    <label for="exampleFormControlTextarea1">Keterangan</label>
    <textarea class="form-control @include('components.invalid',['error'=>'keterangan'])"
        id="exampleFormControlTextarea1" rows="3" name="keterangan">{{$item->keterangan}}</textarea>
    @include('components.alert',['error'=>'keterangan'])
</div>