@extends('auth.template')
@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <img src="{{ asset('illustration/verify.png') }}" alt="{{ asset('illustration/verify.png') }}"
                        class="w-50 mx-auto">
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Let's Verify</h1>
                            </div>
                            <form class="user" method="POST" action="{{ route('user.process_verify') }}">
                                @csrf
                                <input type="hidden" name="unicode" value="{{ $data["code"] }}">
                                <div class="form-group">
                                    <input type="password" name="password" minlength="8" class="form-control form-control-user 
                                            @include('components.invalid',['error'=>'confirm_password'])
                                            " id="password" placeholder="Password">
                                    @include('components.alert',['error'=>'password'])
                                </div>
                                <div class="form-group">
                                    <input type="password" name="confirm_password" class="form-control form-control-user 
                                            @include('components.Invalid',['error'=>'confirm_password'])
                                            " id="confirm_password" placeholder="Konfirmasi Password" minlength="8">
                                    @include('components.alert',['error'=>'confirm_password'])
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Set Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
    var password = document.getElementById("password")
    var confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
@endsection