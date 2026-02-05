<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Fast Print - Produk')</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/corporate-ui-dashboard.css') }}"
        rel="stylesheet" />
    <link href="{{ asset('assets/css/custom-admin.css') }}" rel="stylesheet" />
    @stack('styles')
</head>

<body class="g-sidenav-show bg-gray-100">
    <!-- Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 bg-slate-900 fixed-start" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand d-flex align-items-center m-0" href="{{ route('produk.index') }}">
                <span class="font-weight-bold text-lg text-white">Fast Print</span>
            </a>
        </div>
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('produk.index') ? 'active' : '' }}"
                        href="{{ route('produk.index') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-primary text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-boxes text-white text-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-white">Semua Produk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('produk.bisa-dijual') ? 'active' : '' }}"
                        href="{{ route('produk.bisa-dijual') }}">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-success text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-white">Bisa Dijual</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder text-white opacity-6">Aksi</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('produk.sync') }}"
                        onclick="event.preventDefault(); document.getElementById('sync-form').submit();">
                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-info text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-sync-alt text-white text-sm"></i>
                        </div>
                        <span class="nav-link-text ms-1 text-white">Sync dari API</span>
                    </a>
                    <form id="sync-form" action="{{ route('produk.sync') }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </aside>
    <!-- End Sidebar -->

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg mx-4 px-0 shadow-none border-radius-xl" id="navbarBlur"
            navbar-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="#">Fast Print</a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('page-title',
                            'Produk')</li>
                    </ol>
                    <h6 class="font-weight-bolder mb-0">@yield('page-title', 'Produk')</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <div class="container-fluid py-4 px-4">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text">{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                    <span class="alert-text">{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/corporate-ui-dashboard.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
