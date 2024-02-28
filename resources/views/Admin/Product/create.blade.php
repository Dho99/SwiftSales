@extends('Layouts.index')
@section('plugins')
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2-bootstrap-5-theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Trix/css/trix.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/Dropzone/css/dropzone.min.css') }}">
@endsection
@section('content')
<div class="bg-light rounded p-4">
    <div class="alert alert-danger d-none" role="alert">
        Mohon periksa kembali data yang diperlukan !
    </div>

    <div class="row">
        <div class="form-group col-lg-5 col-md-6 col-sm-12">
            <label for="code">Kode Produk</label>
            <input required type="text" class="form-control" name="code" id="code"
                placeholder="Masukkan Kode Produk">
        </div>
        <div class="form-group col-lg-7 col-md-6 col-sm-12">
            <label for="name">Nama Produk</label>
            <input required type="text" class="form-control" name="name" id="name"
                placeholder="Masukkan Nama Produk">
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="supplier">Supplier</label>
            <select name="supplierId" id="supplier" class="form-select" style="width: 100%;">
                <option value="">Pilih Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="categoryId">Kategori</label>
            <select name="categoryId" id="category" class="form-select" style="width: 100%;">
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4 col-md-4 col-sm-12">
            <label for="expiredDate">Tanggal Kadaluarsa</label>
            <input required type="date" class="form-control" name="expiredDate" id="expiredDate">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="buyPrice">Harga Beli</label>
            <input required type="text" class="form-control" name="buyPrice" id="buyPrice">
        </div>
        <div class="form-group col-lg-6 col-md-6 col-sm-12">
            <label for="buyPrice">Harga Jual</label>
            <input required type="text" class="form-control" name="sellPrice" id="sellPrice">
            <div class="invalid-feedback">
                Periksa kembali harga jual produk
            </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="buyPrice" class="mb-2">Gambar Produk</label>
            <form action="{{ route('uploadImage', ['dirname' => 'productImage']) }}" class="dropzone" id="myDropzone">
                {{-- @csrf --}}
            </form>
        </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12">
            <label for="buyPrice" class="mb-2">Deskripsi Produk</label>
            <input id="x" type="hidden" name="content">
            <trix-editor input="x"></trix-editor>
        </div>
        <x-create-button :beforeThisUrl="$beforeThisUrl" :storeFormUrl="$storeFormUrl"></x-create-button>
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

@endpush
