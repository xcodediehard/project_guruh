@csrf
@method("PUT")
<div class="form-group">
    <label for="exampleInputEmail1">Merek</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'merek'])" id="exampleInputEmail1"
        value="{{ $item->merek }}" aria-describedby="emailHelp" name="merek">
    @include('components.alert',['error'=>'merek'])
</div>