@extends('Layouts.index')
@section('content')
    <div class="container bg-light rounded py-3 px-4">
        <table class="table table-responsive" id="transactionHistories">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Transaksi</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah Pembelian</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal Transaksi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            {{-- <tbody></tbody> --}}
        </table>
    </div>


    <div class="modal modal-lg fade" id="transactionDetailModal" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <button type="button" class="btn btn-danger d-none" id="cancelTransaction">Batalkan Transaksi</button>
                    <a type="button" class="btn btn-primary d-none">Cetak</a>
                </div>
            </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        const currentUrl = '{{url()->current()}}';
        let trxmodal = $('#transactionDetailModal');
        const userLevel = '{{auth()->user()->roles}}'
        const filter = $('#filterHistory');
        const year = filter.attr('name');
        const month = filter.val();

        filter.on('change', function(){
            findDatas();
        })

        $().ready(function(){
            findDatas();
        });
        function findDatas(){
            findData(currentUrl).then(function(response){

                printableWColumns('#transactionHistories', response.transactions, [
                    {
                        data: null,
                        render: function(data, type, row, meta){
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
                        render: function(data, type, row){
                            const quantities = JSON.parse(row.quantity);
                            return quantities.length + ' Varian';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return formatToRupiah(row.subtotal);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return `<div class="badge btn ${row.status == 'Success' ? 'btn-success' : 'btn-danger'}">${row.status}</div>`
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return formatDate(row.created_at);
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row){
                            return `
                            <button class="badge btn btn-sm btn-primary" onclick="showDetails('${row.id}')"><i class="bi bi-eye"></i></button>
                            `;
                        }
                    },
                ]);
            }).then(function(xhr, error){
                swalError(xhr.responseText);
            });
        };
        // function findData()



        function showDetails(id){
            findData(currentUrl+'/'+id).then(function(response){
                let i = 0;
                // console.log(userLevel);
                const data = response.data;
                trxmodal.modal('show');
                trxmodal.find('#staticBackdropLabel').text('Detail Transaksi '+data.code);
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
                        <h6>Status Transaksi : <span class="badge btn ${data.status == 'Success' ? 'btn-success' : 'btn-danger'}">${data.status}</span></h6>
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
                if(data.status === 'Success'){
                    if(userLevel === 'Admin'){
                        trxmodal.find('#cancelTransaction').removeClass('d-none').attr('onclick', `deleteTransaction('${id}')`);
                        trxmodal.find('.modal-footer a').removeClass('d-none').attr('href', `/admin/transactions/print/${id}`);
                    }
                }else{
                    trxmodal.find('#cancelTransaction').addClass('d-none');
                    trxmodal.find('.modal-footer a').addClass('d-none');
                }
            });
        }

        trxmodal.on('hidden.bs.modal', function(){
            trxmodal.find('#staticBackdropLabel').text('');
            trxmodal.find('.modal-body').empty();
        });

        function deleteTransaction(id){
            swalConfirm('Apakah anda yakin akan membatalkan transaksi ini ?').then(function(result){
                if(result){
                    deleteData(currentUrl+`/${id}`, null).then(function(response){
                        swalSuccess(response.message);
                        setTimeout(() => {
                            findDatas();
                        }, 700);
                    }).catch(function(xhr){
                        swalError(xhr.responseText);
                    })
                }
            });
        }


    </script>
@endpush
