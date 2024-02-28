@extends('Layouts.index')
@section('plugins')
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/Select2/css/select2-bootstrap-5-theme.min.css') }}" />
@endsection
@section('content')

<div class="container p-3 bg-light shadow-sm rounded mt-1">
    <div class="form-group py-2">
        <label for="selectProduct" class="mb-2">Pilih / Cari Barang</label>
        <div class="d-flex">
            <select name="" class="form-select" style="width: 90%;" id="selectProduct" onchange="searchProduct()">
                <option selected></option>
                @foreach ($products as $product)
                    <option value="{{$product->id}}">{{$product->code}} | {{$product->name}}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" style="width: 10%;" id="searchBtn" disabled><i class="bi bi-search"></i></button>
        </div>
    </div>
</div>

<div id="selectedProduct"></div>

@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/Select2/js/select2.min.js') }}"></script>
    <script>
        $().ready(function() {
            $('#selectProduct').select2({
                theme: 'bootstrap-5',
            });
        });

        function searchProduct(){
            let sValue = $('#selectProduct').val();
            $('#searchBtn').attr('disabled','disabled');
            if(sValue.length >= 1){
                $('#searchBtn').removeAttr('disabled');
            }
        }
    </script>
@endpush
