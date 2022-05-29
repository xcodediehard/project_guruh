<div class="m-2">
    @if (session()->has('success'))
    <div class="alert alert-success">
        <strong>{{ session('success') }}</strong>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger">
        <strong>{{ session('error') }}</strong>
    </div>
    @endif
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Terdapat Kesalahan Inputan</strong>
    </div>
    @endif
</div>