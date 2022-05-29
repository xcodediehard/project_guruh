@csrf
<div class="form-group">
    <label for="exampleFormControlSelect1">Merek</label>
    <select class="form-control  @include('components.invalid',['error'=>'merek'])" name="merek"
        id="exampleFormControlSelect1">
        @foreach ($data['list_merek'] as $item_merek)
        <option value="{{ $item_merek->id }}">{{ $item_merek->merek }}</option>
        @endforeach
    </select>
    @include('components.alert',['error'=>'merek'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Barang</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'barang'])" id="exampleInputEmail1"
        aria-describedby="emailHelp" name="barang">
    @include('components.alert',['error'=>'barang'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Harga</label>
    <input type="number" class="form-control  @include('components.invalid',['error'=>'harga'])" id="exampleInputEmail1"
        aria-describedby="emailHelp" name="harga">
    @include('components.alert',['error'=>'harga'])
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Keterangan</label>
    <textarea class="form-control @include('components.invalid',['error'=>'katerangan'])"
        id="exampleFormControlTextarea1" rows="3" name="katerangan"></textarea>
    @include('components.alert',['error'=>'katerangan'])
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Image</label>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input @include('components.invalid',['error'=>'image'])"
                id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" name="image">
            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
        </div>
        @include('components.alert',['error'=>'image'])
    </div>
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Add Stok and Size</label>
    <div class="row_data">
        <div class="input-group mb-1">
            <div class="input-group-prepend">
            </div>
            <input type="number" class="form-control m-input border border-primary" placeholder="Size" name="size[]">
            <input type="number" class="form-control m-input border border-danger" placeholder="Stok" name="stok[]">
        </div>
    </div>

    <div class="newinput"></div>
    <button id="rowAdder" type="button" class="btn btn-dark add_data">
        <span class="bi bi-plus-square-dotted">
        </span> ADD
    </button>
</div>