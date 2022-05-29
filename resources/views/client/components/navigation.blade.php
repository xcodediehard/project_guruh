<nav class="navbar navbar-expand-lg navbar-dark bg-danger">
    <a class="navbar-brand" href="{{route('home')}}">Eltoro</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{route('home')}}">Home <span class="sr-only">(current)</span></a>
            </li>
            @if (!empty(auth()->guard("client")->user()))
            <li class="nav-item active">
                <a class="nav-link" href="{{route('keranjang')}}">Keranjang <span class="sr-only">(current)</span></a>
            </li>
            @endif
        </ul>
        <hr>
        <div class="input-group mr-2">
            <input type="text" class="form-control" placeholder="Cari sepatu yang cocok buat anda.."
                aria-label="Recipient's username" aria-describedby="button-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-light" type="button" id="button-addon2">Cari</button>
            </div>
        </div>
        <hr>
        @if (!empty(auth()->guard("client")->user()))
        <a href="{{ route('user.logout') }}" class="btn btn-outline-light mr-2">Logout</a>
        @else
        <a href="{{ route('user.register') }}" class="btn btn-outline-light mr-2">Register</a>
        <a href="{{ route('user.login') }}" class="btn btn-outline-light">Login</a>
        @endif
    </div>
</nav>