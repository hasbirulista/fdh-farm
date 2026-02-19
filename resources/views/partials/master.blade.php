@php
    use Illuminate\Support\Facades\Auth;
    $role = Auth::user()->role;
@endphp
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/feather-icons"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" href="{{ asset('log.png') }}" type="image/png">
    <!-- Sidebar full height CSS -->
    <style>
        /* Sidebar full height hanya untuk layar lebar */
        @media (min-width: 768px) {
            #sidebarMenu {
                bottom: 0;
                left: 0;
                min-height: 100vh;
                overflow-y: auto;
            }

            main {
                margin-left: 250px;
                /* Sesuaikan dengan lebar sidebar */
            }
        }

        .card {
            border: none;
        }

        .card:hover {
            transform: translateY(-3px);
            transition: 0.2s ease-in-out;
            background-color: #f8f9fa;
        }

        /* Navbar selalu di atas */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        /* Sidebar di bawah navbar */
        #sidebarMenu {
            position: relative;
            z-index: 1040;
        }

        /* Header dashboard */
        .dashboard-header {
            position: relative;
            z-index: 10;
        }

        /* Semua card dashboard */
        .dashboard-card {
            position: relative;
            z-index: 10;
            border: none;
        }

        /* Hover effect */
        .dashboard-card:hover {
            background-color: #f8f9fa;
            transform: translateY(-3px);
            transition: all 0.2s ease-in-out;
        }

        /* Icon spacing */
        .dashboard-card svg {
            margin-bottom: 8px;
        }

        /* Mobile spacing safety */
        @media (max-width: 767.98px) {
            .dashboard-card {
                margin-bottom: 0.5rem;
            }
        }

        /* =========================
        USER DROPDOWN MOBILE FIX
        ========================= */

        .user-dropdown {
            border-radius: 8px;
            padding: 6px 0;
            min-width: 160px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            z-index: 2000;
        }

        /* MOBILE */
        @media (max-width: 767.98px) {
            .user-navbar {
                position: relative;
            }

            .user-dropdown {
                position: absolute !important;
                top: calc(100% + 10px);
                /* JARAK DARI NAVBAR */
                right: 0;
                left: auto;
            }
        }
    </style>
    <!-- Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#323638">

    <title>{{ $page }}</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
                <a class="navbar-brand ms-3 ">FDH Company</a>
            </div>
            <div class="navbar-brand d-lg-none">
                <a class="px-3 text-white text-decoration-none">FDH Company</a>
            </div>
            <div class="navbar-nav ms-auto">
            </div>
            <ul class="navbar-nav ms-auto user-navbar">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nama }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                        <li><a class="dropdown-item" href="/dashboard/profile">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn dropdown-item text-danger">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row ">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block navbar-light bg-light sidebar nav-pills collapse">
                <div class="position-sticky pt-3">
                    @if ($role === 'owner')
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $page === 'Dashboard' ? 'active' : '' }} sidebartxt"
                                    href="/dashboard">
                                    <i class="fi fi-rr-stats ms-1"></i>
                                    Dashboard
                                </a>
                            </li>
                        </ul>
                    @endif
                    @if (in_array($role, ['owner', 'anak_kandang', 'kepala_kandang']))
                        <ul class="nav flex-column mt-1">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle sidebartxt {{ $page === 'Kandang' ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <i class="fi fi-rr-cabin ms-1"></i>
                                    Kandang
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark w-100">
                                    <li>
                                        <a class="dropdown-item" href="/dashboard/kandang">
                                            Dashboard
                                        </a>
                                    </li>
                                    {{-- PRODUKSI (SEMUA ROLE KANDANG) --}}
                                    <li>
                                        <a class="dropdown-item" href="/dashboard/kandang/produksi">
                                            Produksi
                                        </a>
                                    </li>

                                    {{-- STOK PAKAN (OWNER & KEPALA KANDANG SAJA) --}}
                                    @if (in_array($role, ['owner', 'kepala_kandang']))
                                        <li>
                                            <a class="dropdown-item" href="/dashboard/pakan/distribusi">
                                                Distribusi Pakan
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    @endif
                    @if (in_array($role, ['owner', 'kepala_gudang']))
                        <ul class="nav flex-column mt-1">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle sidebartxt {{ $page === 'Gudang' ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <i class="fi fi-rr-warehouse-alt ms-1"></i>
                                    FDH Farm
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark w-100">
                                    <li><a class="dropdown-item" href="/dashboard/gudang">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="/dashboard/gudang/barang-masuk">Barang Masuk</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/dashboard/gudang/barang-keluar">Barang
                                            Keluar</a></li>
                                    <li><a class="dropdown-item" href="/dashboard/gudang/pengeluaran">Pengeluaran</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif

                    @if (in_array($role, ['owner', 'admin_toko']))
                        <ul class="nav flex-column mt-1">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle sidebartxt {{ $page === 'Egg Grow' ? 'active' : '' }}"
                                    href="#" data-bs-toggle="dropdown">
                                    <i class="fi fi-rr-shop ms-1"></i>
                                    Egg Grow
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark w-100">
                                    <li><a class="dropdown-item" href="/dashboard/egg-grow">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="/dashboard/egg-grow/barang-masuk">Barang
                                            Masuk</a></li>
                                    <li><a class="dropdown-item" href="/dashboard/egg-grow/pelanggan">Pelanggan</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/dashboard/egg-grow/transaksi">Transaksi</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/dashboard/egg-grow/follow-up">Follow Up</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="/dashboard/egg-grow/pengeluaran">Pengeluaran</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif

                    @if ($role === 'owner')
                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link sidebartxt {{ $page === 'User' ? 'active' : '' }}"
                                    href="/dashboard/users">
                                    <i class="fi fi-rr-users ms-1"></i>
                                    User
                                </a>
                            </li>
                        </ul>
                    @endif
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        feather.replace();
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registration) {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, function(err) {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }
    </script>
</body>

</html>
