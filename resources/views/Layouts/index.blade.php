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

<body class="bg-secondary bg-opacity-25 overflow-hidden">
    <div class="d-flex w-100" id="parent">

        <i class="bi bi-x d-lg-none d-md-block h3 mb-0 position-absolute" id="sidebarCollapser"
            style="left: 180px; top: 20px;" onclick="showHideSidebar()"></i>
        <div class="w-20 bg-sb d-lg-flex d-none vh-100 shadow-lg" id="sidebar">
            <div class="d-flex flex-column px-3 py-3">
                <img src="{{ asset('assets/images/Logo/swiftsales-logo.png') }}" class="img-fluid mt-2 mb-5"
                    alt="">

                <div class="mt-5">

                    <a href="/admin/dashboard"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Dashboard' ? 'active' : '' }}"><i
                            class="bi bi-house me-3"></i> <span class="fs-5">Dashboard</span>
                    </a>

                    <a href="javascript:void(0)" id="sidedropmenu1"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn {{ $title == 'Daftar Produk' || $title == 'Stock-in Produk' ? 'active' : '' }}"><i
                            class="bi bi-box-seam me-3"></i> <span class="fs-5">Produk</span> <i
                            class="bi bi-chevron-down ms-auto" id="arrow1"></i></i>
                    </a>
                    <div id="sidedropcontent1" class="ps-2 d-none">
                        <a href="/admin/products"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Daftar Produk' ? 'active' : '' }}"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Daftar Produk</span>
                        </a>
                        <a href="/admin/products/create"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Tambah Produk' ? 'active' : '' }}"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Tambah Produk</span>
                        </a>
                        <a href="/admin/products/stock/in"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn {{ $title == 'Stock-in Produk' ? 'active' : '' }}"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Stock-in Produk</span>
                        </a>
                    </div>

                    <a href="javascript:void(0)" id="sidedropmenu2"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn"><i
                            class="bi bi-clock me-3"></i> <span class="fs-5">Riwayat</span> <i
                            class="bi bi-chevron-down ms-auto" id="arrow2"></i></i>
                    </a>
                    <div id="sidedropcontent2" class="ps-2 d-none">
                        <a href="javascript:void(0)"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Transaksi</span>
                        </a>
                        <a href="/admin/history/stock/in"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Stock-in</span>
                        </a>
                    </div>

                    <a href="javascript:void(0)" id="sidedropmenu3"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn"><i class="bi bi-wallet2 me-3"></i>
                        <span class="fs-5">Transaksi</span> <i
                            class="bi bi-chevron-down ms-auto" id="arrow3"></i></i>
                    </a>
                    <div id="sidedropcontent3" class="ps-2 d-none">
                        <a href="/admin/transactions"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Buat Transaksi</span>
                        </a>
                        <a href="javascript:void(0)"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Transaksi Dibuat</span>
                        </a>
                        <a href="javascript:void(0)"
                            class="btn rounded text-light text-start d-flex align-items-center mb-1 sidebtn"><i
                                class="bi bi-dash me-3"></i> <span class="fs-5">Tranaksi Dibatalkan</span>
                        </a>
                    </div>

                    <a href="javascript:void(0)"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn"><i
                            class="bi bi-truck me-3"></i> <span class="fs-5">Supplier</span>
                    </a>

                    <a href="javascript:void(0)"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn"><i
                            class="bi bi-person me-3"></i> <span class="fs-5">Pengguna</span>
                    </a>

                    <a href="javascript:void(0)"
                        class="btn rounded text-light w-100 text-start d-flex mb-2 align-items-center sidebtn"><i
                            class="bi bi-journal-medical me-3"></i> <span class="fs-5">Pelanggan</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column w-100">
            <nav class="navbar navbar-expand bg-body-tertiary shadow-sm">
                <div class="container">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <i class="bi bi-list d-lg-none d-md-block h3 mb-0" onclick="showHideSidebar()"></i>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/Avatar/alex-suprun-1RAZcvtAxkk-unsplash.jpg') }}"
                                        class="img-fluid avatar rounded-circle m-0 p-0" alt="">
                                </a>
                                <ul class="dropdown-menu avatar-dropdown py-0 d-none" id="avatarTop">
                                    <li><a class="dropdown-item" href="#"><i
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
            <div class="lg-container md-container-fluid h-100 p-3 overflow-auto">
                <div class="container bg-light rounded py-3 my-2 d-flex shadow-sm">
                    <h3 class="fw-bold my-auto">{{ $title }}</h3>
                    @if (Request::is('admin/products'))
                        <x-create-data></x-create-data>
                    @endif
                </div>

                {{-- <div class="container rounded p-4"> --}}
                @yield('content')
                {{-- </div> --}}
            </div>
        </div>

    </div>




    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/DataTables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Sweetalert/template.js') }}"></script>

    @stack('scripts')
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

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

        function showHideSidebar() {
            $('#sidebar').toggleClass('d-none position-absolute w-100 z-3');
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
                    swalClose();
                });
            });
        }

        function findData(url) {
            // console.log(url);
            return new Promise(function(resolve, reject) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        swalBeforeSend();
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, error) {
                        reject(xhr, error);
                    }
                }).done(function(){
                    Swal.close();
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

        function reinitializeTable(id, datas, columns) {
            let table = new DataTable(id, {
                responsive: true,
                searching: true,
                ordering: false,
                autoWidth: false,
                destroy: true,
                data: datas,
                column: columns,
            });
        }

        function formatToRupiah(number) {
            return "Rp. " + number.toLocaleString('id-ID');
        }

        function formatDate(date){
            let myDate = new Date(date);
            let timeString = (myDate.getMonth() + 1) + '/' + myDate.getDate() + '/' +  myDate.getFullYear();

            return myDate;
        }

        // function closeModal(modalId){
        //     $(modalId).on('hidden.bs.modal', function(){
        //         resetModal();
        //         // console.log('modal-closed'+modalId);
        //     });
        // }
    </script>
</body>

</html>
