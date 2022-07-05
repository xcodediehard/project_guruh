@extends('auth.template')
@section('content')
<!-- Outer Row -->
<div class="row justify-content-center">

    <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <img src="{{ asset('illustration/forgot_password.png') }}"
                        alt="{{ asset('illustration/forgot_password.png') }}" class="w-50 mx-auto">
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                                <p class="mb-4">We get it, stuff happens. Just enter your email address below
                                    and we'll send you a link to reset your password!</p>
                            </div>
                            <form class="user" method="POST" action="{{ route('auth.process_forgot_password') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user  
                                    @include('components.invalid',['error'=>'confirm_password'])
                                    " id="exampleInputEmail" aria-describedby="emailHelp"
                                        placeholder="Enter Email Address..." name="email" required>
                                    @include('components.alert',['error'=>'email'])
                                </div>
                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Reset Password
                                </button>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="{{ route('user.register') }}">Create an Account!</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="{{ route('user.login') }}">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection