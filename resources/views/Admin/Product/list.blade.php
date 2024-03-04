@extends('Layouts.index')
@section('content')
<div class="position-absolute z-3 p-2 d-flex flex-column" style="right: 20px; bottom: 10px;">
    <button class="btn btn-danger shadow-lg border filterbtn d-none" id="resetFilterButton">
        <span class="fw-bold h5"><i class="bi bi-x-circle me-2"></i>Reset</span>
    </button>
    <button class="btn btn-light shadow-lg border filterbtn mt-1" id="filterButton">
        <span class="fw-bold h5"><i class="bi bi-funnel me-2"></i>Filter</span>
    </button>
</div>

<div id="atEmptyData"></div>
    @if (isset($products[0]))
        <div id="cardProductWrapper" class="row d-lg-container d-md-container mt-3">
            @foreach ($products as $key => $product)
                <div class="col-lg-4 col-md-6 col-sm-10 mx-lg-0 mx-auto mb-3" id="productCardWrapper">
                    <div class="card card bg-light shadow-sm mx-lg-0 mx-md-2" id="productCard" >
                        <div id="carouselExampleIndicators{{ $key }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($product->images) as $image)
                                    <div class="carousel-item {{ json_decode($product->images)[0] == $image ? 'active' : '' }} bg-transparent "
                                        data-bs-interval="3000">
                                        <img src="{{ asset($image) }}" class="img-fluid rounded-top displayImg" alt="...">
                                    </div>
                                @endforeach
                            </div>
                            {{-- @if (count(json_decode($product->images)) > 1) --}}
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleIndicators{{ $key }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleIndicators{{ $key }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            {{-- @endif --}}
                        </div>

                        <div class="card-body bg-body rounded">
                            <table class="table table-responsive" id="productable">
                                <tr>
                                    <th>Kode Produk:</th>
                                    <td id="productName">{{ $product->code }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Produk:</th>
                                    <td id="productName">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th>Stok</th>
                                    <td id="productSellPrice">{{$product->stock}}</td>
                                </tr>
                                <tr>
                                    <th>Harga Jual:</th>
                                    <td id="productSellPrice">@currency($product->sellPrice)</td>
                                </tr>
                                <tr>
                                    <th>Kategori</th>
                                    <td id="productCategory">{{ $product->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier</th>
                                    <td id="productSupplier">{{ $product->supplier->name }}</td>
                                </tr>
                            </table>
                            <div class="row d-flex gap-2">
                                <div class="col-5">
                                    <a href="{{ url()->current() . '/' . $product->id }}"
                                        class="btn btn-sm bg-primary bg-opacity-75 text-light w-100 py-2"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip" data-bs-title="Detail Produk"><i
                                            class="bi bi-info-circle"></i></a>
                                </div>
                                <div class="col-5 ms-auto">
                                    <button class="btn btn-sm bg-danger bg-opacity-75 text-light w-100 py-2"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip" data-bs-title="Hapus Produk"
                                        onclick="deleteProduct('{{ url()->current() . '/' . $product->id }}', 'Apakah anda benar - benar yakin akan mengapus data ini ?')"><i
                                            class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="container-fluid bg-light py-3 mx-auto rounded text-center fw-bold d-flex flex-column text-info-emphasis">
            <i class="bi bi-dropbox h1"></i>
            Tidak ada data produk
        </div>
    @endif
@endsection

  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-4" id="staticBackdropLabel">Filter Produk</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="category">Filter Produk Berdasarkan</label>
                <select name="" id="filterByCategory" class="form-select mt-1" onchange="getResult()">
                    <option value="">Semua</option>
                    <option value="productName">Nama</option>
                    <option value="productCategory">Kategori</option>
                    <option value="productSupplier">Supplier</option>
                </select>
            </div>
            <div class="form-group" id="selectResult">
                {{-- <input type="text" name="" id="allType" class="form-control" placeholder="Masukkan Keywords"> --}}
            </div>
        </div>
        <div class="modal-footer">
          {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal('#staticBackdrop')">Tutup</button> --}}
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary" onclick="doFiltering()">Filter</button>
        </div>
      </div>
    </div>
  </div>

@push('scripts')
    <script>
        const host = '{{ url('/') }}';
        const currentUrl = '{{ url()->current() }}';
        let totalProductShow = '/'+'{{count($products)}}';
        const wrapper = $('.card');
        let cardIndex = wrapper.length;

        $().ready(function(){
            getResult();
        });

        let scrollValue = $(document).height();
        $('div').scroll(function() {
            if(wrapper.length < 6){
                scrollValue += 300;
            }
            if ($(this).scrollTop() >= scrollValue) {
                console.log(scrollValue);
                if($(document).width() <= 800){
                    scrollValue+=(scrollValue * 3);
                }else{
                    scrollValue+=scrollValue;
                }
                findData(currentUrl+'/'+cardIndex+'/get').then(function(response) {
                    appendCard(response.data);
                }).catch(function(xhr, error) {
                    swalError(xhr.responseText);
                    console.log(error.message);
                });
            }
        });

        function appendCard(datas){
            datas.map((data, index) => {
                cardIndex+=1;
                $('#cardProductWrapper').append(`
                    <div class="col-lg-4 col-md-6 col-sm-10 mx-lg-0 mx-auto mb-3" id="productCardWrapper">
                        <div class="card card bg-light shadow-sm mx-lg-0 mx-md-2" id="productCard">
                            <div id="carouselExampleIndicators${cardIndex}" class="carousel slide" data-bs-ride="carousel">
                                ${data.images.map((image, indeximage) => `
                                        <div class="carousel-inner">
                                            <div class="carousel-item${indeximage === 0 ? ' active' : ''}" data-bs-interval="3000">
                                                <img src="${image}" class="w-100 h-100 img-fluid rounded-top" alt="...">
                                            </div>
                                        </div>`
                                ).join('')}

                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators${cardIndex}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators${cardIndex}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div class="card-body bg-body rounded">
                                <table class="table table-responsive" id="productable">
                                    <tr>
                                        <th>Kode Produk:</th>
                                        <td id="productName">${data.code}</td>
                                    </tr>
                                    <tr>
                                        <th>Nama Produk:</th>
                                        <td id="productName">${data.name}</td>
                                    </tr>
                                    <tr>
                                        <th>Stok</th>
                                        <td id="productBuyPrice">${data.stock}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga Jual:</th>
                                        <td id="productSellPrice">${formatToRupiah(data.sellPrice)}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td id="productCategory">${data.category}</td>
                                    </tr>
                                    <tr>
                                        <th>Supplier</th>
                                        <td id="productSupplier">${data.supplier}</td>
                                    </tr>
                                </table>
                                <div class="row d-flex gap-2">
                                    <div class="col-lg-3">
                                        <a href="${currentUrl+'/'+data.id}" class="btn btn-sm bg-primary bg-opacity-75 text-light w-100" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Detail Produk"><i class="bi bi-info-circle"></i></a>
                                    </div>
                                    <div class="col-lg-3 ms-auto">
                                        <button class="btn btn-sm bg-danger bg-opacity-75 text-light w-100" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Hapus Produk" onclick="deleteProduct('${currentUrl+'/'+data.id}', 'Apakah anda benar - benar yakin akan mengapus data ini ?')"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        }



        $('#resetFilterButton').on('click', function(){
            $('#selectResult input').val('');
            doFiltering();
        });


        function getResult(){
            let selectedFilterMethod = $('#filterByCategory').val();
            let filterFormWrapper = $('#selectResult');
            $('#selectResult input').val('');
            filterFormWrapper.empty();
            if(selectedFilterMethod !== ''){
                if(selectedFilterMethod === 'productCategory'){
                    filterFormWrapper.append(`
                        <input type="text" name="" id="category" class="form-control" placeholder="Masukkan Keywords">
                    `);
                }else if(selectedFilterMethod === 'productName'){
                    filterFormWrapper.append(`
                        <input type="text" name="" id="name" class="form-control" placeholder="Masukkan Keywords">
                    `);
                }else if(selectedFilterMethod === 'productSupplier'){
                    filterFormWrapper.append(`
                        <input type="text" name="" id="supplier" class="form-control" placeholder="Masukkan Keywords">
                    `);
                }
            }else{
                filterFormWrapper.append(`
                    <input type="text" name="" id="allType" class="form-control" placeholder="Masukkan Keywords">
                `);
            }
        }


        function doFiltering() {
            let keywords = $('#selectResult input').val().toLowerCase();
            let cards = $('#productCardWrapper .card');
            let category = $('#filterByCategory').val();
            $('#staticBackdrop').modal('hide');
            cards.each(function(index, card) {
                let tableData;
                if(category.length < 1){
                    tableData = $(card).find('table#productable').text().toLowerCase();
                }else{
                    tableData = $(card).find(`table#productable tbody tr td#${category}`).text().toLowerCase();
                }
                if (tableData.includes(keywords)) {
                    $(card).parent('#productCardWrapper').show();
                } else {
                    $(card).parent('#productCardWrapper').hide();
                }
            });

            if(keywords.length < 1){
                $('#resetFilterButton').addClass('d-none');
            }else{
                $('#resetFilterButton').removeClass('d-none');
            }

        }




        $('#filterButton').on('click', function(){
            $('#staticBackdrop').modal('show');
        });



        function refreshCard(data) {
            // console.log(data.length);
            const atEmpty = $('#atEmptyData');
            wrapper.empty();
            if (data.length < 1) {
                atEmpty.append(`
                <div
                    class="container-fluid bg-light py-3 mx-auto rounded text-center fw-bold d-flex flex-column text-info-emphasis">
                    <i class="bi bi-dropbox h1"></i>
                    Tidak ada data produk
                </div>
                `);
            } else {
                Swal.close();
                $('#cardProductWrapper').empty();
                appendCard(data);

            };
        }


        function deleteProduct(url, message) {
            swalConfirm("Apakah Anda yakin ingin menghapus?").then(function(result) {
                if (result) {
                    deleteData(url, message).then(function(response) {
                        swalSuccess(response.message);
                    }).catch(function(xhr, error) {
                        swalError(xhr.responseText);
                        console.log(error.message);
                    });
                    setTimeout(() => {
                        findData(currentUrl).then(function(response) {
                            setTimeout(() => {
                                refreshCard(response.data);
                                swalSuccess(response.message)
                            }, 700);
                        }).catch(function(xhr, error) {
                            swalError(xhr.responseText);
                            console.log(error.message);
                        });
                    }, 2000);
                }
            }).catch(function(error) {
                console.log("User cancelled deletion");
            });
        }




    </script>
@endpush
<script></script>
