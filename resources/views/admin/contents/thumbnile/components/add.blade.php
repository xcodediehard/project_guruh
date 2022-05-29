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