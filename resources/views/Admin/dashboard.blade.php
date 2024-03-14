@extends('Layouts.index')
@section('plugins')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                <div class="row">
                    <div class="col-12">
                        <h6 class="">Profit Perbulan</h6>
                    </div>
                    <div class="col-12">
                        <h4 class="fw-bold">@currency($totalProfitByMonth)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                <div class="row overflow-hidden">
                    <div class="col-12">
                        <h6 class="">Nominal Transaksi Perbulan</h6>
                    </div>
                    <div class="col-12">
                        <h4 class="fw-bold">@currency($subtotalTransactionsAMonth)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                <div class="row">
                    <div class="col-12">
                        <h6 class="">Jumlah Transaksi Harian</h6>
                    </div>
                    <div class="col-12">
                        <h4 class="fw-bold">@currency($subtotalTransactionsADay)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                <div class="row">
                    <div class="col-12">
                        <h6 class="">Jumlah Produk</h6>
                    </div>
                    <div class="col-12">
                        <h4 class="fw-bold">{{ $products }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-12">
            <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                <div class="row">
                    <div class="d-flex col-12">
                        <h5 class="fw-bold">Grafik Penjualan Harian</h5>
                    </div>
                    <div class="col-12">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 m-0 p-0">
            <div class="row m-0 p-0 w-100">
                <div class="col-12">
                    <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                        <div class="col-12">
                            <h6 class="">Pelanggan Terdaftar</h6>
                        </div>
                        <div class="col-12">
                            <h4 class="fw-bold">{{ $customers->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                        <div class="col-lg-12">
                            <h6 class="">Jumlah Supplier</h6>
                        </div>
                        <div class="col-12">
                            <h4 class="fw-bold">{{ $suppliers }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="container bg-light shadow-sm rounded py-3 px-3 my-2">
                        <div class="col-12">
                            <h6 class="">Jumlah Transaksi</h6>
                        </div>
                        <div class="col-12">
                            <h4 class="fw-bold">{{ $countTransactions }}</h4>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- </div> --}}
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/plugins/Chartjs/dist/chart.umd.js') }}"></script>

    <script>
        let labels;
        const datas = [];
        const profits = [];
        const ctx = document.getElementById('myChart');

        function renderChart() {
            new Chart(ctx, {
                type: 'line',
                responsive: true,
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Nominal',
                            data: datas,
                            // fill: true,
                        },
                        {
                            label: 'Profit',
                            data: profits,
                            // fill: true
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        $().ready(function() {
            findData('/admin/render/chart').then(function(response) {
                labels = response.hours;
                response.transactions.map(data => {
                    datas.push(data.total);
                    profits.push(data.profits);
                });
                setTimeout(() => {
                    renderChart();
                }, 300);
            }).catch(function(xhr) {
                swalError(xhr.responseText);
            });
        });
    </script>
@endpush
