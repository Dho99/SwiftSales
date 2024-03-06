@extends('Auth.login')
@section('nonauth')
    <div class="py-5 d-flex m-auto" style="width: 95%;">
            <div class="mx-3 container-fluid printable" style="font-size: 15px">
                <div class="row">
                    <div class="col-4">
                        <img src="{{asset('assets/images/Logo/swiftsales-logo.png')}}" class=" ms-auto" width="200" alt="">
                    </div>
                    <div class="col-6 d-flex ms-auto">
                        <h4 class="fw-bold ms-auto">INVOICE</h4>
                    </div>
                </div>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom header">
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <div class="d-flex flex-column align-items-start">
                        <span class="fw-bold">Waktu Transaksi</span>
                        <span>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('d F Y H:i') }}</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-1">
                    <div class="d-flex flex-column align-items-start">
                        <span class="fw-bold">Nama Pelanggan</span>
                        <span>{{ $transaction['customer'] }}</span>
                    </div>
                </div>

                <div class="d-flex flex-column align-items-end mb-3">
                    <span class="fw-bold">INVOICE CODE:</span>
                    <span>{{ $transaction['code'] }}</span>
                </div>

                <table class="table table-responsive table-bordered table-hover" style="border-color:rgb(194, 194, 194);">
                    <thead>
                        <tr>
                            <th class="bg-primary bg-opacity-50">No</th>
                            <th class="bg-primary bg-opacity-50">Nama Produk</th>
                            <th class="bg-primary bg-opacity-50">Jumlah</th>
                            <th class="bg-primary bg-opacity-50">Harga</th>
                            <th class="bg-primary bg-opacity-50">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction['product'] as $key => $product)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$transaction['product'][$key]->name}}</td>
                            <td>{{$transaction['qty'][$key]}}</td>
                            <td>@currency($transaction['product'][$key]->sellPrice)</td>
                            <td>@currency($transaction['total'][$key])</td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex flex-column align-items-end">
                    <table>
                        <tr>
                            <td><span>SUB TOTAL</span></td>
                            <td><span class="mx-3">:</span></td>
                            <td><span>@currency($transaction['subtotal'])</span></td>
                        </tr>
                        <tr>
                            <td><span >TAX</span></td>
                            <td><span class="mx-3">:</span></td>
                            <td><span>Rp. 0</span></td>
                        </tr>
                        {{-- <tr>
                            <td><span class="fw-bold">TOTAL</span></td>
                            <td><span class="mx-3">:</span></td>
                            <td><span class="fw-bold text-decoration-underline">Rp. {{ number_format($transaction->total_price, 0, ',', ',') }}</span></td>
                        </tr> --}}
                    </table>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            print();
        });

        window.onafterprint = function(event) {
            window.location.href = '/transactions';
        };
    </script>
    @endpush
@endsection
