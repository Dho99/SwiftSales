<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }} | SwiftSales</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Dashboard/dashboard.css') }}">
    </link>
    <link rel="stylesheet" href="{{ asset('assets/Icons/Bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/Sweetalert/dist/sweetalert2.min.css') }}">
    <script src="{{ asset('assets/plugins/Sweetalert/dist/sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/DataTables/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/plugins/three-dots/dist/three-dots.min.css')}}">
    @yield('plugins')
</head>
<style>
    .attachment__caption {
        display: none !important;
    }

    button.trix-button.trix-button--icon.trix-button--icon-attach {
        display: none !important;
    }
</style>
@yield('styles')

<body class="bg-secondary bg-opacity-25 overflow-hidden">


    @if (auth()->check())
        <div class="d-flex w-100" id="parent">

            <i class="bi bi-x d-none h3 mb-0 position-absolute bg-sb p-1 z-3 shadow rounded text-light"
                id="sidebarCollapser" style="left: 170px; top: 20px;" onclick="showHideSidebar()"></i>
            <div class="w-20 bg-sb d-lg-flex d-none vh-100 shadow-lg overflow-auto" id="sidebar">
                <div class="d-flex flex-column px-3 py-3">
                    <img src="{{ asset('assets/images/Logo/swiftsales-logo.png') }}" class="img-fluid mt-2 mb-5"
                        alt="">

                    <div class="mt-5 pb-4">
                        @if (auth()->user()->roles === 'Admin')
                            <a href="/admin/dashboard"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Dashboard' ? 'active' : '' }}"><i
                                    class="bi bi-house me-3"></i> <span class="fs-5">Dashboard</span>
                            </a>

                            <a href="javascript:void(0)" id="sidedropmenu1"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Daftar Produk' || $title == 'Tambah Produk' || $title == 'Stock-in Produk' ? 'active' : '' }}"><i
                                    class="bi bi-box-seam me-3"></i> <span class="fs-5">Produk</span> <i
                                    class="bi bi-chevron-down ms-auto" id="arrow1"></i></i>
                            </a>
                            <div id="sidedropcontent1" class="ps-2 d-none">
                                <a href="/products"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Daftar Produk' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Daftar Produk</span>
                                </a>
                                <a href="/products/create"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Tambah Produk' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Tambah Produk</span>
                                </a>
                                <a href="/products/stock/in"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Stock-in Produk' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Stock-in Produk</span>
                                </a>
                            </div>

                            <a href="javascript:void(0)" id="sidedropmenu2"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Riwayat Transaksi' || $title == 'StockIn History' ? 'active' : '' }}"><i
                                    class="bi bi-clock me-3"></i> <span class="fs-5">Riwayat</span> <i
                                    class="bi bi-chevron-down ms-auto" id="arrow2"></i></i>
                            </a>
                            <div id="sidedropcontent2" class="ps-2 d-none">
                                <a href="/transactions"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Riwayat Transaksi' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Transaksi</span>
                                </a>
                                <a href="/history/stock/in"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'StockIn History' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Stock-in</span>
                                </a>
                            </div>

                            <a href="javascript:void(0)" id="sidedropmenu3"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Create Transactions' ? 'active' : '' }}"><i
                                    class="bi bi-wallet2 me-3"></i>
                                <span class="fs-5">Transaksi</span> <i class="bi bi-chevron-down ms-auto"
                                    id="arrow3"></i></i>
                            </a>
                            <div id="sidedropcontent3" class="ps-2 d-none">
                                <a href="/transactions/create"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Create Transactions' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Buat Transaksi</span>
                                </a>
                            </div>

                            <a href="/admin/supplier"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Daftar Supplier' ? 'active' : '' }}"><i
                                    class="bi bi-truck me-3"></i> <span class="fs-5">Supplier</span>
                            </a>

                            <a href="/user"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Daftar Pengguna' ? 'active' : '' }}"><i
                                    class="bi bi-person me-3"></i> <span class="fs-5">Pengguna</span>
                            </a>



                        @elseif(auth()->user()->roles === 'Cashier')
                            <a href="/transactions/create"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Create Transactions' ? 'active' : '' }}"><i
                                    class="bi bi-wallet2 me-3"></i><span class="fs-5">Transaksi</span>
                            </a>

                            <a href="/products/stock/in"
                                class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Stock-in Produk' ? 'active' : '' }}"><i
                                class="bi bi-box-seam me-3"></i> <span class="fs-5">Stock-in Produk</span>
                            </a>


                            <a href="javascript:void(0)" id="sidedropmenu2"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Riwayat Transaksi' || $title == 'StockIn History' ? 'active' : '' }}"><i
                                    class="bi bi-clock me-3"></i> <span class="fs-5">Riwayat</span> <i
                                    class="bi bi-chevron-down ms-auto" id="arrow2"></i></i>
                            </a>
                            <div id="sidedropcontent2" class="ps-2 d-none">
                                <a href="/transactions"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Riwayat Transaksi' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Transaksi</span>
                                </a>
                                <a href="/history/stock/in"
                                    class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'StockIn History' ? 'active' : '' }}"><i
                                        class="bi bi-dash me-3"></i> <span class="fs-5">Stock-in</span>
                                </a>

                            </div>

                            <a href="/user"
                                class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Daftar Customer' ? 'active' : '' }}"><i class="bi bi-people-fill me-3"></i><span class="fs-5">Customer</span>
                            </a>
                        @endif


                    </div>
                </div>
            </div>

            <div class="d-flex flex-column w-100">
                <nav class="navbar navbar-expand bg-body-tertiary shadow-sm">
                    <div class="container">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <i class="bi bi-list d-lg-none d-md-block h3 mb-0" onclick="showHideSidebar()"></i>
                            <ul class="navbar-nav ms-auto mb-0">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ auth()->user()->profilePhoto ? auth()->user()->profilePhoto : asset('assets/images/Avatar/alex-suprun-1RAZcvtAxkk-unsplash.jpg') }}"
                                            class="img-fluid avatar rounded m-0 p-0" alt="">
                                    </a>
                                    <ul class="dropdown-menu avatar-dropdown py-0 d-none" id="avatarTop">
                                        <li><a class="dropdown-item" href="/user/{{ auth()->user()->id }}"><i
                                                    class="bi bi-info-circle me-1"></i> Info Akun</a></li>
                                        <li><a class="dropdown-item btn text-light bg-danger w-100 rounded-0 text-start"
                                                href="/logout"><i class="bi bi-box-arrow-in-left me-1"></i> Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>


                    </div>
                </nav>
                <div class="lg-container md-container-fluid h-100 p-3 overflow-auto position-relative">
                    <div class="container bg-light rounded py-3 my-2 d-flex shadow-sm">
                        <h3 class="fw-bold my-auto">{{ $title }}</h3>

                        @if (Request::is('products', 'admin/customer/list'))
                            <x-create-data></x-create-data>
                        @endif
                        @if (Request::is('history/stock/in'))
                            <a href="{{ route('stockinProduct') }}"
                                class="btn btn bg-primary bg-opacity-75 d-inline-flex align-items-center ms-auto text-light"><i
                                    class="bi bi-plus-circle me-1"></i> Tambah Data
                            </a>
                        @endif
                        @if (Request::is('user', 'supplier'))
                            <button
                                class="btn btn bg-primary bg-opacity-75 d-inline-flex align-items-center ms-auto text-light"
                                onclick="createUser()"><i class="bi bi-plus-circle me-1"></i> Tambah Data
                            </button>
                        @endif
                        {{-- @if(Request::is('transactions'))
                            @php
                                $year = \Illuminate\Support\Carbon::now()->format('Y');
                                $months = [];
                                $monthNumbers = [];
                                $monthRange = range(1,12);
                                foreach($monthRange as $month){
                                    $date = \Illuminate\Support\Carbon::createFromDate($year, $month, 1);
                                    $months[] = $date->format('F');
                                    $monthNumbers[] = $date->format('m');
                                }
                            @endphp
                            <select name="{{$year}}" class="form-control form-control-sm w-25 d-flex ms-auto" id="filterHistory">
                                @foreach ($months as $key => $item)
                                    <option value="{{$monthNumbers[$key]}}" {{$monthNumbers[$key] == now()->format('m') ? 'selected' : ''}}>{{$item}}</option>
                                @endforeach
                            </select>
                        @endif --}}
                    </div>
                    <div class="container my-3 py-3 bg-light w-25 position-absolute top-50 start-50 translate-middle rounded d-none" id="loader">
                        <div class="dot-elastic  d-flex mx-auto"></div>
                    </div>
                    @yield('content')

                </div>
            </div>

        </div>
    @else
        @yield('nonauth')
    @endif



    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/DataTables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Sweetalert/template.js') }}"></script>

    @stack('scripts')
    <script>
        const loader = $('#loader');
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        // const currentUrl = '{{url()->current()}}';

        $('a#sidedropmenu1').on('click', function() {
            $('#sidedropcontent1').toggleClass('d-none');
            $('#arrow1').toggleClass('rotateArrow');
        });
        $('a#sidedropmenu2').on('click', function() {
            $('#sidedropcontent2').toggleClass('d-none');
            $('#arrow2').toggleClass('rotateArrow');
        });
        $('a#sidedropmenu3').on('click', function() {
            $('#sidedropcontent3').toggleClass('d-none');
            $('#arrow3').toggleClass('rotateArrow');
        });
        $('a#sidedropmenu4').on('click', function() {
            $('#sidedropcontent4').toggleClass('d-none');
            $('#arrow4').toggleClass('rotateArrow');
        });

        function showHideSidebar() {
            $('#sidebar').toggleClass('d-none position-absolute w-100 z-3');
            $('#sidebarCollapser').toggleClass('d-none');
        };

        $('a.dropdown-toggle').on('click', function() {
            $('#avatarTop').toggleClass('d-none d-block');
        });

        function storeData(url, formData) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        swalBeforeSend();
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, error) {
                        reject(xhr, error);
                    }
                }).done(function() {
                    resetAllInput();
                    Swal.close();
                });
            });
        }

        function storeDataNoReset(url, formData) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    // beforeSend: function() {
                    // },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, error) {
                        reject(xhr, error);
                    }
                }).done(function() {
                    Swal.close();
                });
            });
        }

        function findData(url) {
            // console.log(url);
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    cache: false,
                    beforeSend: function() {
                        loader.removeClass('d-none');
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, error) {
                        reject(xhr);
                    }
                }).done(function() {
                    Swal.close();
                    loader.addClass('d-none');
                });
            });
        }


        function deleteData(url, message) {
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        swalBeforeSend();
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, error) {
                        reject(xhr, error);
                    }
                });
            });
        }

        function resetAllInput() {
            $('input').val('');
            $('select').val('').change();
            $('trix-editor').val('');
            const myDropzone = Dropzone.forElement("#myDropzone");
            myDropzone.removeAllFiles(true);
        }

        function regulerTable(id) {
            let table = new DataTable(id, {
                responsive: true,
                searching: true,
                ordering: false,
                autoWidth: false,
                destroy: true,
            });
        }

        function printable(id) {
            let table = new DataTable(id, {
                responsive: true,
                searching: true,
                ordering: false,
                autoWidth: false,
                destroy: true,
                dom: 'Bflrtip',
                buttons: [
                    'colvis', 'excel', 'print', 'pdf'
                ]
            });
        }

        function printableWColumns(id, data, columns) {
            let table = new DataTable(id, {
                responsive: true,
                searching: true,
                ordering: false,
                autoWidth: false,
                destroy: true,
                dom: 'Bflrtip',
                data: data,
                columns: columns,
                buttons: [
                    'colvis', 'excel', 'print', 'pdf'
                ]
            });
        }

        function reinitializeTable(id, datas, columns) {
            let table = new DataTable(id, {
                responsive: true,
                searching: true,
                ordering: false,
                autoWidth: false,
                destroy: true,
                data: datas,
                columns: columns,
            });
        }

        function formatToRupiah(number) {
            return "Rp. " + number.toLocaleString('id-ID');
        }

        function formatDate(date) {
            let myDate = new Date(date).toLocaleString();
            return myDate;
        }
    </script>
</body>

</html>
