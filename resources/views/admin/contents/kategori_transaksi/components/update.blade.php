<div class="form-group">
    <label for="exampleInputEmail1">Kategori Transaksi</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'kategori_transaksi'])"
        id="exampleInputEmail1" aria-describedby="emailHelp" name="kategori_transaksi">
    @include('components.alert',['error'=>'kategori_transaksi'])
</div>