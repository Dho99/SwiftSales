@extends('Layouts.index')
@section('content')
    <div class="bg-light rounded p-3">
        <div class="form-group py-2">
            <label for="selectProduct" class="mb-2">Cari Kode Barang</label>
            <div class="d-flex">
                <input type="text" class="form-control" id="codeInput" placeholder="Masukkan Kode Barang">
                <button class="btn btn-primary" type="button" id="searchBtn"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </div>

    <div id="searchProductWrapper">
        <div class="container bg-light rounded py-3 px-4 mt-2">
            <p>Rekomendasi Produk untuk Stock-in</p>
            <table class="table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        <th>Stok-in Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recomendations as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->stock }}</td>
                        <td>{{ $item->updated_at->format('d F Y H:i') }}</td>
                        <td><button class="badge btn btn-primary" onclick="insertToInput('{{$item->code}}')">Stock-in</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- </div> --}}
@endsection
@push('scripts')
    <script>
        const currentUrl = '{{ url()->current() }}';
        $('#searchBtn').on('click', function() {
            let codeVal = $('#codeInput').val();
            if (codeVal.length < 1) {
                swalError('Masukkan kode produk terlebih dahulu');
            } else {
                findData(currentUrl + '/' + codeVal).then(function(response) {
                    displayData(response.data);
                    $('#codeInput').val('');
                }).catch(function(xhr, error) {
                    swalError(xhr.responseText);
                });
            }
        });

        function insertToInput(code)
        {
            swalConfirmWithoutDelete('Lanjutkan Stock-in produk ini ?').then(function(result){
                if(result){
                    $('#codeInput').val(code);
                    $('#searchBtn').click();
                }
            });
        }

        function displayData(data) {
            let product = data[0];
            let history = data[1];
            $('#searchProductWrapper').empty().append(`
            <div class="bg-light container rounded mt-2 p-3">
                 <h5 class="text-center fw-bold">Hasil Pencarian Produk ${product.name}</h5>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">Nama Produk</label>
                            <input type="text" readonly name="" class="form-control" value="${product.name}" id="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="code">Kode Produk</label>
                            <input type="text" readonly name="" id="productCode" class="form-control" value="${product.code}" id="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="stock">Stok Produk</label>
                            <input type="text" readonly name="" id="oldStock" class="form-control" value="${product.stock}" id="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="newStock">Tambahan Stok</label>
                            <input type="number" name="" class="form-control" value="" id="newStock" oninput="inputCheck()">
                        </div>
                    </div>
                    <div class="col-12 row gap-2 m-0 p-0">
                        <div class="col-lg-3 col-md-3 col-5">
                            <a class="btn btn-secondary w-100" href="/admin/products">Kembali</a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-5 ms-auto">
                            <button class="btn btn-primary w-100" onclick="updateStock()">Simpan</button>
                        </div>
                    </div>
                </div>
                </div>
                <div class="bg-light container rounded mt-2 p-3">
                    <h5 class="text-center fw-bold">Riwayat Penambahan Stok Produk ${product.name}</h5>
                <table class="table table-responsive" id="stockTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Nama Petugas</th>
                            <th>Jumlah Sebelumnya</th>
                            <th>Jumlah Aktual</th>
                            <th>Waktu Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${history.map((h, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${h.product.code}</td>
                                    <td>${h.product.name}</td>
                                    <td>${h.user.name}</td>
                                    <td>${h.before}</td>
                                    <td>${h.after}</td>
                                    <td>${formatDate(h.created_at)}</td>
                                </tr>`
                        ).join('')}
                    </tbody>
                </table>
            </div>
            `);
            regulerTable('#stockTable');
            // $('#newStock').focus();
        }

        function inputCheck(){
            let val = $('#newStock').val();
            if(val < 1){
                $('#newStock').val('');
            }
        };

        function updateStock(){
            let newStock = $('#newStock').val();
            if(newStock < 1){
                $('#newStock').addClass('is-invalid');
                swalError('Jumlah Tambahan stok tidak boleh kurang dari 1');
            }else{
                let formData = new FormData();
                formData.append('oldStock', parseInt($('#oldStock').val()));
                formData.append('newStock', parseInt(newStock));
                formData.append('productCode', $('#productCode').val());

                storeData(currentUrl+'/store', formData).then(function(response){
                    swalSuccess(response.message);
                    $('#searchProductWrapper').empty();
                }).catch(function(xhr, error){
                    swalError(xhr.responseText);
                    console.log(error.message);
                });
            }
        }


    </script>
@endpush

