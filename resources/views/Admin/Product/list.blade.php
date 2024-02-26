@extends('Layouts.index')
@section('content')
    <table id="productable" class="table table-responsive">
        <thead class="bg bg-primary">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Batch</th>
                <th>Nama Produk</th>
                <th>Supplier</th>
                <th>Stok</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->code }}</td>
                    <td>{{ $product->batch }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->supplier->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>@currency($product->buyPrice)</td>
                    <td>@currency($product->sellPrice)</td>
                    <td>
                        <a class="badge btn bg-primary bg-opacity-75 text-light"
                            href="{{ url()->current() . '/' . $product->id }}"><i class="bi bi-info-circle"></i></a>
                        <button class="badge btn bg-danger bg-opacity-75 text-light"
                            onclick="deleteProduct('{{ url()->current() . '/' . $product->id }}', 'Apakah anda benar - benar yakin akan mengapus data ini ?')"><i
                                class="bi bi-trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@push('scripts')
    <script>
        $().ready(function() {
            regulerTable($('#productable'));
        });

        function deleteProduct(url, message) {
            swalConfirm("Apakah Anda yakin ingin menghapus?").then(function(result) {
                if (result) {
                    deleteData(url, message).then(function(response) {
                        swalSuccess(response.message);
                    }).catch(function(xhr, error) {
                        swalError(xhr.responseText);
                        console.log(error.message);
                    });
                }
            }).catch(function(error) {
                console.log("User cancelled deletion");
            });
        }
    </script>
@endpush
