@csrf
<div class="form-group">
    <label for="exampleFormControlTextarea1">Keterangan</label>
    <textarea class="form-control @include('components.invalid',['error'=>'katerangan'])"
        id="exampleFormControlTextarea1" rows="3" name="katerangan"></textarea>
    @include('components.alert',['error'=>'katerangan'])
</div>