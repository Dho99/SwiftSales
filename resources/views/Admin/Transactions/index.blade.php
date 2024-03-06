@extends('Layouts.index')
@section('plugins')
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2-bootstrap-5-theme.min.css') }}" />
@endsection
@section('content')
    <div class="d-flex row">
        <div class="col-lg-8 col-md-7 order-lg-1 order-md-1 order-2">

            <div class="col bg-light rounded py-3 mb-2 d-flex justify-content-center shadow-sm">
                <div class="row container">
                    <h5 class="fw-bold text-center mb-2">Cari data Produk</h5>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="selectCustomer">Produk</label>
                            <select name="" id="selectProduct" class="form-select" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mt-4" id="productCheckoutWrapper">
                    </div>
                </div>
            </div>

            <div class="col bg-light rounded py-3 mb-2 d-flex justify-content-center shadow-sm">
                <div class="row container">
                    <h5 class="fw-bold text-center mb-2">Kalkulasi Transaksi</h5>
                    <div class="col-lg-12">
                        <table class="table table-responsive border" id="trxTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                        <div class="my-3">
                            <div class="row">
                                <div class="col-4">
                                    <label for="">Terbayar</label>
                                    <input type="number" class="form-control w-100" id="pay" >
                                </div>
                                <div class="col-4 ms-auto">
                                    <label for="">Jumlah</label>
                                    <input type="number" class="form-control w-100" readonly id="bill">
                                </div>
                                <div class="col-4 ms-auto">
                                    <label for="">Kembalian</label>
                                    <input type="number" class="form-control w-100" readonly id="charge">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mx-0 p-0">
                        <div class="col-lg-3">
                            <button class="btn btn-secondary w-100">Batal</button>
                        </div>
                        <div class="col-lg-3 d-flex ms-auto">
                            <button class="btn btn-primary w-100 disabled" id="prosesTransaksi">Proses</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-5 order-lg-2 order-md-2 order-1">
            <div class="col bg-light rounded shadow-sm mb-2">
                <div class="container py-3">
                    <h5 class="fw-bold text-center">Cari data Pelanggan</h5>
                    <div class="form-group">
                        <label for="selectCustomer">Customer</label>
                        <select name="" id="selectCustomer" class="form-select" style="width: 100%;">
                            <option value=""></option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col bg-light rounded shadow-sm mb-2">
                <div class="container py-3">
                    <div class="d-flex flex-row mb-3">
                        <h5 class="fw-bold ms-auto">Basket</h5>
                        <button class="btn btn-sm btn-danger ms-auto disabled" id="trashBtn" onclick="emptyCart();"><i
                                class="bi bi-trash"></i></button>
                    </div>
                    <div id="checkoutBarWrapper" class="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/Select2/js/select2.min.js') }}"></script>
    <script>
        $().ready(function() {
            $('#selectCustomer , #selectProduct').select2({
                theme: 'bootstrap-5',
                tags: true
            });
        });

        let productIdArray = [];
        let quantityArray = [];
        let total = [];
        let subtotal = 0;


        $('#pay').on('input', function(){
            let val = parseInt($(this).val());
            let total = subtotal;
            let charge = $('#charge');

            chargeVal = val -= total;
            if(chargeVal < 0){
                charge.val(0)
            }else{
                charge.val(chargeVal);
            }
        });



        let transactions = {};

        const productUrl = '/products/';

        const wrapper = $('#productCheckoutWrapper');

        $('#selectProduct').on('change', function() {
            let val = $(this).val();
            let url = productUrl + val;
            // console.log(val);
            if (val) {
                wrapper.empty();
                findData(url).then(function(response) {
                    showProductDetails(response.data);
                }).catch(function(xhr, error) {
                    swalError(xhr.responseText);
                    console.log(error.message);
                });
            }else{
                console.log(null);
            }
        });

        function showProductDetails(data) {
            wrapper.append(`
                <div class="card d-flex justify-content-center">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="${data.images[0]}" class="img-fluid rounded-start h-100"
                                alt="Product Image">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">${data.code} | ${data.name}</h5>
                                <div class="card-text my-3">
                                    <table class="table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Harga :</th>
                                                <td>${formatToRupiah(data.sellPrice)}</td>
                                            </tr>
                                            <tr>
                                                <th>Stok :</th>
                                                <td>${data.stock}</td>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="card-text row d-flex mx-auto">
                                    <div class="col-lg-5 col-md-5 col-6">
                                        <button class="btn btn-danger w-100"><i class="bi bi-trash3 me-2"></i>Hapus</button>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-6 ms-auto">
                                        <button class="btn btn-primary w-100" onclick="addToCart('${data.id}')"><i class="bi bi-cart-plus me-2"></i>Tambah</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }

        const coWrapper = $('#checkoutBarWrapper');

        function reset() {
            wrapper.empty();
            coWrapper.empty();
            $('#selectProduct').val('').change();
            $('#pay').removeClass('is-invalid');
            $('#trxTable tbody, #trxTable tfoot').empty();
            transactions = {};
            $('#prosesTransaksi').addClass('disabled');

        }


        function addToCart(id) {
            // reset();
            let url = productUrl + id;
            findData(url).then(function(response) {
                appendToCart(response.data);
                $('#selectProduct').val('').change();
                $('#trashBtn, #prosesTransaksi').removeClass('disabled');
                wrapper.empty();
            }).catch(function(xhr, error) {
                swalError(xhr.responseText);
            });
        }

        // let idProductArr = [];

        function appendToCart(data) {
            if (data.id in transactions) {
                addQty(data.id);
            } else {
                if(data.stock < 1){
                    swalError('Stok Produk sedang Kosong, Tidak dapat menambahkan ke keranjang');
                }else{
                    transactions[data.id] = {
                        productId: data.id,
                        code: data.code,
                        name: data.name,
                        sellPrice: data.sellPrice,
                        qty: 1,
                        stock: data.stock
                    };
                    addToTable();
                    coWrapper.append(`
                        <div class="container bg-body shadow rounded row m-0 p-2 mb-3" id="productCheckout${data.id}">
                            <img src="${data.images[0]}" class="col-5 img-fluid ps-0 rounded" alt="">
                            <div class="col-7 ps-0">
                                <div>${data.code} | ${data.name}</div>
                                <div>${formatToRupiah(data.sellPrice)}</div>
                                <div class="input-group mb-2">
                                    <button class="btn btn-outline-danger" onclick="reduceQty('${data.id}')"><i class="bi bi-dash-circle"></i></button>
                                    <input type="number" class="form-control text-center" placeholder="Username" aria-label="" value="1" id="inputQty${data.id}" oninput="checkInputValue('${data.id}')">
                                    <button class="btn btn-outline-primary" onclick="addQty('${data.id}')"><i class="bi bi-plus-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    `);
                }
            }
        }

        function checkInputValue(id){
            let inputEl = $(`#inputQty${id}`);
            let inputVal = parseInt(inputEl.val());
            if(inputVal < 1 || isNaN(inputVal)){
                inputEl.val(1);
                inputVal = 1;
            }
            if(inputVal > transactions[id].stock){
                swalError('Stok produk tidak mencukupi');
                inputVal = transactions[id].stock;
                inputEl.val(inputVal);
            }
            transactions[id].qty = inputVal;
            addToTable();
        }



        function addQty(id) {
            let productQty = $(`#inputQty${id}`);
            let cVal = parseInt(productQty.val());
            let nVal = cVal += 1;
            if(transactions[id].stock < nVal){
                swalError('Stok produk tidak mencukupi');
                nVal = transactions[id].stock;
            }
            productQty.val(nVal);
            transactions[id].qty = nVal;
            addToTable();
        }

        function reduceQty(id) {
            let productQty = $(`#inputQty${id}`);
            let cVal = parseInt(productQty.val());
            let nVal;
            if (cVal <= 0) {
                nVal = 0;
                swalConfirm('Apakah anda ingin menghapus produk ini dari daftar Checkout ?').then(function(result) {
                    if (result) {
                        swalSuccess('Produk berhasil di hapus dari daftar Checkout');
                        $(`#productCheckout${id}`).remove();
                        delete transactions[id];
                        addToTable();
                        $('#prosesTransaksi').addClass('disabled');
                        reset();
                    }
                }).catch(function(error) {
                    nVal = 1;
                });
            } else {
                nVal = cVal -= 1;
                transactions[id].qty = nVal;
                // console.log(transactions);
                addToTable();
            }
            productQty.val(nVal);
        }

        function emptyCart() {
            transactions = {};
            swalSuccess('Cart berhasil dikosongkan');
            coWrapper.empty();
            $('#trashBtn, #prosesTransaksi').addClass('disabled');
            addToTable();
            reset();
        }



        function addToTable() {
            subtotal = 0;
            productIdArray = [];
            quantityArray = [];
            total = [];
            let grandTotal = 0;
            let i = 0;


            let table = $('#trxTable tbody');
            let tfoot = $('#trxTable tfoot');
            table.empty();
            tfoot.empty();


            for (let key in transactions) {
                grandTotal += transactions[key].qty * transactions[key].sellPrice;

                productIdArray.push(transactions[key].productId);
                quantityArray.push(transactions[key].qty);
                total.push(transactions[key].qty * transactions[key].sellPrice);

                table.append(`
                <tr>
                    <td>${i+=1}</td>
                    <td>${transactions[key].code}</td>
                    <td>${transactions[key].name}</td>
                    <td>${formatToRupiah(transactions[key].sellPrice)}</td>
                    <td>${transactions[key].qty}</td>
                    <td>${formatToRupiah(transactions[key].qty * transactions[key].sellPrice)}</td>
                    </tr>
                `);
            }


            tfoot.append(`
                <tr>
                    <th colspan="4"></th>
                    <th>Grand Total</th>
                    <th>${formatToRupiah(grandTotal)}</th>
                </tr>
                `);

            subtotal = grandTotal;
            $('#bill').val(subtotal);
        }

        $('#prosesTransaksi').on('click', function(event) {
            event.preventDefault();
            let custIdVal = $('#selectCustomer');
            if(custIdVal.val().length < 1){
                custIdVal.addClass('is-invalid');
                swalError('Data Pelanggan tidak boleh kosong');
            }else if($('#pay').val() < subtotal){
                $('#pay').addClass('is-invalid');
            }else{
                const url = '/transactions';
                let formData = new FormData();
                formData.append('customerId', custIdVal.val());
                formData.append('productId', JSON.stringify(productIdArray));
                formData.append('quantity', JSON.stringify(quantityArray));
                formData.append('total', JSON.stringify(total));
                formData.append('subtotal', subtotal);

                storeData(url, formData).then(function(response){
                    swalSuccess(response.message);
                    $('#prosesTransaksi').addClass('disabled');
                    setTimeout(() => {
                        swalConfirmWithoutDelete('Apakah anda ingin mencetak Struk / Invoice ?').then(function(result){
                            if(result){
                                window.location.href = '/transactions/print/'+response.id;
                            }
                        });

                        reset();
                    }, 1000);
                }).catch(function(xhr, error){
                    swalError(xhr.responseText);
                });
            }

        });
    </script>
@endpush
