@extends('Auth.login')
@section('nonauth')
    <main class="d-flex w-100 vh-100 bg-dark bg-opacity-75 overflow-auto">
        <div
            class="container row d-flex bg-secondary bg-opacity-75 rounded-3 w-75 justify-content-center m-auto py-5 px-3 loginwrap gap-4 shadow-lg">
            <div class="col-lg-4 col-md-12 col-sm-12 d-flex">
                <img src="{{ asset('assets/images/Logo/swiftsales-logo.png') }}" class="img-fluid m-auto" alt="">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card w-100 shadow py-3 ms-lg-5 ms-0">
                    <div class="card-body">
                        <h5 class="card-title text-center h4 fw-bold">Daftarkan Akun</h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success  d-flex h-auto" role="alert">
                                <div>
                                    <i class="bi bi-exclamation-octagon me-3"></i>
                                    {{session('success')}}
                                </div>
                            </div>
                            <script>
                                setTimeout(() => {
                                    window.location.href = '/';
                                }, 1000);
                            </script>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex h-auto" role="alert">
                                <div>
                                    <i class="bi bi-exclamation-octagon me-3"></i>
                                    Proses pendaftaran akun gagal, Mohon periksa kembali
                                </div>
                            </div>
                        @endif
                        <form action="{{ route('registered') }}" method="POST" class="px-4">
                            @csrf
                            <div class="form-group">
                                <div class="mt-3">
                                    <label for="username">Nama</label>
                                    <input type="text" name="name" placeholder="Nama Lengkap" id="name"
                                        value="{{old('name')}}"
                                        class="form-control @if ($errors->has('name')) is-invalid @endif">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label for="telephone">No. Telephone</label>
                                    <input type="text" name="telephone" placeholder="Nomor Telephone" id="telephone"
                                        value="{{old('telephone')}}"
                                        class="form-control @if ($errors->has('telephone')) is-invalid @endif">
                                    @if ($errors->has('telephone'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('telephone') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label for="username">Email</label>
                                    <input type="email" name="email" placeholder="Email Aktif" id="email"
                                        value="{{old('email')}}"
                                        class="form-control @if ($errors->has('email')) is-invalid @endif">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" placeholder="Masukkan Password" id="password"
                                        class="form-control @if ($errors->has('password')) is-invalid @endif">
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-3">
                                    <label for="password">Ulangi Password</label>
                                    <input type="password" name="password" placeholder="Masukkan kembali Password" id="passwordRepeatInput"
                                        class="form-control">
                                    <div class="invalid-feedback" id="repeatPassword"></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-5 col-md-4 col-12 order-lg-1 order-2 ms-lg-0 ms-md-0 ms-auto">
                                        <a href="{{ route('login') }}" class="btn btn-secondary mb-0 mt-3 w-100">Kembali</a>
                                    </div>
                                    <div class="col-lg-6 col-md-4 col-12 ms-lg-auto ms-md-auto ms-0 order-lg-2 order-md-2">
                                        <button type="button" id="submitBtn" class="btn btn-primary mb-0 mt-3 w-100">Daftar
                                            Sekarang</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/jquery/jquery-3.7.1.min.js')}}"></script>
    <script>
        let inputPassword = $('#password');
        let repeatpassword = $('#passwordRepeatInput');

        $('#passwordRepeatInput').on('change', function(){
            if(inputPassword.val !== '' && inputPassword.val() !== repeatpassword.val())
            {
                $(this).addClass('is-invalid');
                $('#repeatPassword').text('Password tidak sama, periksa kembali!');
                $('#submitBtn').attr('type', 'button');
            }else{
                $(this).removeClass('is-invalid');
                $('#repeatPassword').empty();
                $('#submitBtn').attr('type', 'submit');
            }
        });
    </script>
@endpush
