<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <i class="fas fa-home"></i>
        </div>
        <div class="sidebar-brand-text mx-3">ELTORO ADMIN</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="index.html">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        staff
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="{{ route('staff.view') }}">Staff</a>
                {{-- <a class="collapse-item" href="{{ route('thumbnile.view') }}">Thumbnile</a> --}}
                <a class="collapse-item" href="{{ route('merek.view') }}">Merek</a>
                <a class="collapse-item" href="{{ route('barang.view') }}">Barang</a>
                <a class="collapse-item" href="{{ route('komentar.view') }}">Komentar</a>
                {{-- <a class="collapse-item" href="{{ route('promo.view') }}">Promo</a> --}}
                {{-- <a class="collapse-item" href="{{ route('kategori_transaksi.view') }}">Kategori Transaksi</a> --}}
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Informasi
    </div>

    <!-- Nav Item - Charts -->
    <li class="nav-item active">
        <a class="nav-link" href="charts.html">
            <i class="fas fa-fw fa-users"></i>
            <span>Pengguna</span></a>
    </li>

    <!-- Nav Item - Tables -->
    <li class="nav-item ">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-money-bill-wave"></i>
            <span>Informasi Transaksi</span></a>
    </li>
    <!-- Nav Item - Tables -->
    <li class="nav-item ">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-box"></i>
            <span>Informasi Pengiriman</span></a>
    </li>
    <!-- Nav Item - Tables -->
    <li class="nav-item ">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-list"></i>
            <span>Informasi Complete</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->