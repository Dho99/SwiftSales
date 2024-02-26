@extends('Layouts.index')
@section('plugins')
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2-bootstrap-5-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Trix/css/trix.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/Dropzone/css/dropzone.min.css') }}">
@endsection
@section('content')
    <div class="alert alert-danger d-none" role="alert">
        Mohon periksa kembali data yang diperlukan !
    </div>

    <div class="row">
        <div class="form-group col-lg-5 col-md-6 col-sm-12">
            <label for="code">Kode Produk</label>
            <input required type="text" class="form-control" value="{{ $product->code }}" name="code" id="code"
                placeholder="Masukkan Kode Produk">
        </div>
        <div class="form-group col-lg-7 col-md-6 col-sm-12">
            <label for="name">Nama Produk</label>
            <input required type="text" class="form-control" value="{{ $product->name }}" name="name" id="name"
                placeholder="Masukkan Nama Produk">
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="supplier">Supplier</label>
            <select name="supplierId" id="supplier" class="form-select" style="width: 100%;">
                <option value="">Pilih Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $product->supplierId === $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="categoryId">Kategori</label>
            <select name="categoryId" id="category" class="form-select" style="width: 100%;">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->categoryId === $category->id ? 'selected' : '' }}>
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="expiredDate">Tanggal Kadaluarsa</label>
            <input required type="date" class="form-control" name="expiredDate" value="{{ $product->expiredDate }}"
                id="expiredDate">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="buyPrice">Harga Beli</label>
            <input required type="text" class="form-control" name="buyPrice" value="{{ $product->buyPrice }}"
                id="buyPrice">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="buyPrice">Harga Jual</label>
            <input required type="text" class="form-control" name="sellPrice" value="{{ $product->sellPrice }}"
                id="sellPrice">
            <div class="invalid-feedback">
                Periksa kembali harga jual produk
            </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="buyPrice" class="mb-2">Gambar Produk</label>
            <div class="row my-3 gap-2">
                @foreach ($imageArray as $item)
                    <div class="col-lg-2 flex flex-column" id="productImg{{\Str::slug($item)}}">
                        <img src="{{ asset($item) }}" alt="Product Image" class="img-fluid rounded border" alt="" id="">
                        <button type="button" id="deleteImgBtn" class="badge btn btn-danger rounded d-flex m-auto"
                            onclick="deleteProductImage('{{ $item }}')">Hapus Gambar</button>
                    </div>
                @endforeach
            </div>
            <div class="text-danger mb-3" id="imgTxt">Perubahan gambar akan terlihat ketika sudah disimpan</div>
            <form action="{{ route('uploadImage', ['dirname' => 'productImage']) }}" class="dropzone" id="myDropzone">
                @csrf
            </form>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="buyPrice" class="mb-2">Deskripsi Produk</label>
            <div id="productDescription">
                {!! $product->description !!}
            </div>
            <div id="trixProductDescription">
                <input id="x" type="hidden" name="content" value="{{ $product->description }}">
                <trix-editor input="x"></trix-editor>
            </div>
        </div>
        <div class="col-12 row d-flex m-0 p-0 gap-2">
            <div class="col-lg-3 col-6 col-12 order-lg-1 order-2">
                <a href="{{ $beforeThisUrl }}" class="btn btn-secondary w-100">Kembali</a>
            </div>
            <div class="col-lg-3 col-6 col-12 order-lg-2 order-1 ms-auto">
                <button id="submitbutton" class="btn btn-primary w-100">Edit</button>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/Select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Trix/js/trix.umd.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Dropzone/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/Trix/js/attachments.js') }}"></script>
    {{-- <x-trix-attachment></x-trix-attachment> --}}
    <x-edit-or-create-script></x-edit-or-create>
        <script>
            uploadedFile = {!! $product->images !!}


            $().ready(function() {
                $('input').attr('readonly', 'readonly');
                $('select').attr('disabled', 'disabled');
                $('#submitbutton').text('Edit');
                $('#myDropzone').hide();
                $('#trixProductDescription, #imgTxt').hide();
                $('button#deleteImgBtn').addClass('d-none');
            });

            $('#submitbutton').on('click', function() {
                $(this).attr('onclick', 'update("{{url()->current()}}")').text('Simpan');
                $('input').removeAttr('readonly');
                $('select').removeAttr('disabled');
                $('#productDescription').hide();
                $('button#deleteImgBtn').removeClass('d-none');
                $('#trixProductDescription, #imgTxt, #myDropzone').show();
            });

            function deleteProductImage(path) {
                let findIndex = uploadedFile.findIndex(function(item) {
                    return item === path; // Mencocokkan nilai dengan path yang diberikan
                });
                let pathLowerCase = path.toLowerCase();
                let removedWhitespace = pathLowerCase.replace(/^\s+|\s+$/g, '').replace(' ','-').replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
                uploadedFile.splice(findIndex, 1);
                $(`#productImg${removedWhitespace}`).hide();
            }

            function update(url){
                const method = 'PUT';
                if(uploadedFile.length < 1){
                    swalError('Image tidak boleh kosong');
                    console.log(uploadedFile.length);
                }else{
                    // console.log(url);
                    let formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('code', $('#code').val());
                    formData.append('name', $('#name').val());
                    formData.append('supplierId', $('#supplier').val());
                    formData.append('categoryId', $('#category').val());
                    formData.append('expiredDate', $('#expiredDate').val());
                    formData.append('buyPrice', $('#buyPrice').cleanVal());
                    formData.append('sellPrice', $('#sellPrice').cleanVal());
                    formData.append('images', JSON.stringify(uploadedFile));
                    formData.append('description', $('#x').val());

                    storeData(url, formData).then(function(response){
                        swalSuccess(response.message);
                        setTimeout(() => {
                            window.location.href = '{{$beforeThisUrl}}';
                        }, 1000);
                    }).catch(function(xhr, error){
                        swalError(xhr.responseText);
                        console.log(error);
                    });
                }
            }
        </script>
    @endpush
