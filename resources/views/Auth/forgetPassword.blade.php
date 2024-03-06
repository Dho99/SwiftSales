@extends('Auth.login')

@section('nonauth')
    <div class="vh-100 d-flex align-items-center bg-secondary bg-opacity-25">
        <div class="container  bg-dark bg-opacity-75 shadow rounded py-3 px-4 w-75">
            <div class="row">
                <div class="col-12">
                    <img src="{{asset('assets/images/Logo/swiftsales-logo.png')}}" class="img-fluid d-flex m-auto" width="250" alt="">
                    <div class="text-light mt-2 mb-0 fw-bold text-center">Reset Password</div>

                    @if (Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif

                    <form action="{{ route('forget.password.post') }}" method="POST">
                        @csrf
                        <div class="form-group row mt-2">
                            <label for="email_address" class="col-md-4 col-form-label text-md-right text-light">E-Mail
                                Address</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Masukkan alamat Email Terdaftar"
                                    id="email_address" name="email" required autofocus>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    Kirim Link Verifikasi
                                </button>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </form>

                </div>

            </div>
            </main>
        </div>
    </div>
@endsection
