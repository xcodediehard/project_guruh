<div class="form-group">
    <label for="exampleInputEmail1">Nama</label>
    <input type="text" class="form-control  @include('components.invalid',['error'=>'name'])" id="exampleInputEmail1"
        aria-describedby="emailHelp" name="name">
    @include('components.alert',['error'=>'name'])
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Email</label>
    <input type="email" class="form-control  @include('components.invalid',['error'=>'email'])" id="exampleInputEmail1"
        aria-describedby="emailHelp" name="email">
    @include('components.alert',['error'=>'email'])
</div>
<div class="form-group">
    <label for="exampleFormControlSelect1">Is Owner</label>
    <select class="form-control  @include('components.invalid',['error'=>'owner'])" name="owner"
        id="exampleFormControlSelect1">
        <option>No</option>
        <option>Yes</option>
    </select>
    @include('components.alert',['error'=>'owner'])
</div>
<div class="form-group">
    <label for="exampleFormControlSelect1">Is Active</label>
    <select class="form-control  @include('components.invalid',['error'=>'activate'])" name="activate"
        id="exampleFormControlSelect1">
        <option>Yes</option>
        <option>No</option>
    </select>
    @include('components.alert',['error'=>'activate'])
</div>