@extends('Layouts.index')
@section('content')
    <div class="bg-light container rounded p-3 shadow">
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Jumlah Sebelumnya</th>
                    <th>Jumlah Aktual</th>
                    <th>Nama Pengguna</th>
                    <th>Waktu Pelaksanaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($histories as $history)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $history->product->code }}</td>
                        <td>{{ $history->product->name }}</td>
                        <td>{{ $history->before }} Buah</td>
                        <td>{{ $history->after }} Buah</td>
                        <td>{{ $history->user->name }}</td>
                        <td>{{ $history->created_at->format('d F Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    <script>
        $().ready(function() {
            printable('.table');
        });
    </script>
@endpush
