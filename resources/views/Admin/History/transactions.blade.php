@extends('Layouts.index')
@section('styles')
    <style>
        #filterBtn {
            width: 70px !important;
        }

        @media screen and (width < 990px) {
            #filterBtn {
                width: 100% !important;
            }
        }
    </style>
@endsection
@section('content')
    <div class="mb-2 d-lg-container-fluid container m-auto p-0">
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="bg-light rounded row px-1 py-3 m-1">
                    <div class="col-12">
                        <h5>Jumlah Transaksi</h5>
                    </div>
                    <div class="col-12">
                        <h5 class="fw-bold" id="ownTransactions"></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="bg-light rounded row px-1 py-3 m-1">
                    <div class="col-12">
                        <h5>Total Pendapatan</h5>
                    </div>
                    <div class="col-12">
                        <h5 class="fw-bold" id="ownIncomes"></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="bg-light rounded row px-1 py-3 m-1">
                    <div class="col-12">
                        <h5>Total Profit</h5>
                    </div>
                    <div class="col-12">
                        <h5 class="fw-bold" id="ownProfits"></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container bg-light rounded py-3 px-4">
        <div class="row  border-bottom mb-3">
            <div class="ms-auto col-lg-6 col-12">
                <div class="text-end mb-2">
                    Urutkan berdasarkan Tanggal
                </div>
                <div class="d-lg-flex d-md-block mb-3 ">
                    <div class="input-group">
                        <span class="input-group-text">Dari</span>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Hingga</span>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <button class="btn btn-primary" id="filterBtn">Filter</button>
                </div>
            </div>
        </div>
        <table class="table table-responsive" id="transactionHistories">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah Pembelian</th>
                    <th>Profit</th>
                    <th>Total</th>
                    {{-- <th>Status</th> --}}
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            {{-- <tbody></tbody> --}}
        </table>
    </div>


    <div class="modal modal-lg fade" id="transactionDetailModal" data-bs-keyboard="false" data-bs-backdrop="static"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-danger d-none" id="cancelTransaction">Batalkan
                            Transaksi</button>
                        <a type="button" class="btn btn-primary d-none">Cetak</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const currentUrl = '{{ url()->current() }}';
        let trxmodal = $('#transactionDetailModal');
        const userLevel = '{{ auth()->user()->roles }}'
        const filter = $('#filterHistory');
        const year = filter.attr('name');
        const month = filter.val();

        $().ready(function() {
            findDatas();
        });

        function renderPrintables(data, incomes, profits) {
            $('#ownIncomes').text(formatToRupiah(incomes));
            $('#ownProfits').text(formatToRupiah(profits));
            $('#ownTransactions').text(data.length);
            printableWColumns('#transactionHistories', data, [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "code"
                },
                {
                    data: "customer.name"
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        const quantities = JSON.parse(row.quantity);
                        return quantities.length + ' Varian';
                    }
                },
                {
                    data: null,
                    render: function(data, type, row){
                        return formatToRupiah(row.profit);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return formatToRupiah(row.subtotal);
                    }
                },
                // {
                //     data: null,
                //     render: function(data, type, row) {
                //         return `<button class="badge btn btn-${row.status == 'Success' ? 'success' : 'danger'}">${row.status}</button>`
                //     }
                // },
                {
                    data: null,
                    render: function(data, type, row) {
                        return formatDate(row.created_at);
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                    <button class="badge btn btn-sm btn-primary" onclick="showDetails('${row.id}')"><i class="bi bi-eye"></i></button>
                    `;
                    }
                },
            ]);
        }

        function findDatas() {
            findData(currentUrl).then(function(response) {
                renderPrintables(response.transactions, response.totalTransactions, response.calculateProfits);
                trxmodal.modal('hide');
            }).then(function(xhr, error) {
                swalError(xhr.responseText);
            });
        };

        function showDetails(id) {
            findData(currentUrl + '/' + id).then(function(response) {
                let i = 0;
                const data = response.data;
                trxmodal.modal('show');
                trxmodal.find('#staticBackdropLabel').text('Detail Transaksi ' + data.code);
                trxmodal.find('.modal-body').append(`
                <div class="row">
                    <div class="col-12">
                        <h6>Tanggal Transaksi : <span class="fw-normal">${formatDate(data.created_at)}</span></h6>
                    </div>
                    <div class="col-12">
                        <h6>Kode Transaksi : <span class="fw-normal">${data.code}</span></h6>
                    </div>
                    <div class="col-12">
                        <h6>Nama Pelanggan : <span class="fw-normal">${data.customer}</span></h6>
                    </div>
                    <div class="col-12">
                        <h6>Status : <span class="fw-normal badge btn btn${data.status == 'Success' ? '-success' : '-danger'}">${data.status}</span></h6>
                    </div>
                    <div class="col-12">
                        <table class="table table-responsive table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                            ${data.product.map((product, index) =>
                                `
                                                    <tr>
                                                        <td>${i+=1}</td>
                                                        <td>${product.name}</td>
                                                        <td>${data.qty[index]}</td>
                                                        <td>${formatToRupiah(data.total[index])}</td>
                                                    <tr>
                                                `)}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal</th>
                                    <th id="trxSubtotal">${formatToRupiah(data.subtotal)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                `);
                // if (data.status !== 'Canceled') {
                //     trxmodal.find('#cancelTransaction').removeClass('d-none').attr('onclick',
                //         `deleteTransaction('${id}')`);
                    trxmodal.find('.modal-footer a').removeClass('d-none').attr('href',
                        `/transactions/print/${id}`);
                // }
            });
        }

        trxmodal.on('hidden.bs.modal', function() {
            trxmodal.find('#staticBackdropLabel').text('');
            trxmodal.find('.modal-body').empty();
        });

        // function deleteTransaction(id) {
        //     swalConfirm('Apakah anda yakin akan membatalkan transaksi ini ?').then(function(result) {
        //         if (result) {
        //             deleteData(currentUrl + `/${id}`, null).then(function(response) {
        //                 swalSuccess(response.message);
        //                 setTimeout(() => {
        //                     findDatas();
        //                 }, 700);
        //             }).catch(function(xhr) {
        //                 swalError(xhr.responseText);
        //             })
        //         }
        //     });
        // }

        $('#filterBtn').on('click', function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();


            if (startDate.length > 1 && endDate.length > 1) {
                const formData = new FormData();
                formData.append('startDate', startDate);
                formData.append('endDate', endDate);
                storeDataNoReset(currentUrl + '/filter', formData).then(function(response) {
                    renderPrintables(response.transactions, response.totalTransactions, response.calculateProfits);
                    Swal.close();
                }).catch(function(xhr) {
                    swalError(xhr.responseText);
                });
            }
        });
    </script>
@endpush
