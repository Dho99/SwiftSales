<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}} | SwiftSales</title>
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/Login/style.css')}}"></link>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/Icons/Bootstrap-icons/font/bootstrap-icons.min.css')}}">
    @yield('plugins')
</head>
<style>
   @import url('https://fonts.googleapis.com/css2?family=Albert+Sans:ital,wght@0,100..900;1,100..900&display=swap');

    *{
        font-family: "Albert Sans", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
    }

</style>
@if(Request::is('login'))
<body>
    <main class="d-flex w-100 vh-100 bg-dark bg-opacity-75 overflow-hidden">
        <div class="container row d-flex bg-secondary bg-opacity-75 rounded-3 w-75 justify-content-center m-auto py-5 px-3 loginwrap gap-4 shadow-lg">
            <div class="col-lg-4 col-md-12 col-sm-12 d-flex">
                <img src="{{asset('assets/images/Logo/swiftsales-logo.png')}}" class="img-fluid m-auto" alt="">
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="card w-100 shadow py-3 ms-lg-5 ms-0">
                    <div class="card-body">
                        <h5 class="card-title text-center h4 fw-bold">Login</h5>
                        @if(session()->has('loginError'))
                          <div class="alert alert-danger d-flex h-auto" role="alert">
                              <div>
                                  <i class="bi bi-exclamation-octagon me-3"></i>
                                  {{session('loginError')}}
                              </div>
                          </div>
                        @endif
                        <form action="/login" method="POST" class="px-4">
                            @csrf
                            <div class="form-group">
                                <div class="mt-3">
                                    <label for="username">Email</label>
                                    <input type="email" name="email" placeholder="Masukkan Email" id="email" value="{{old('email')}}" class="form-control @if(session()->has('loginError')) is-invalid @endif @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mt-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" placeholder="Masukkan Password" id="password" class="form-control">
                                </div>
                                @if(session()->has('loginError'))
                                    <div class="mt-3 w-100 text-center">
                                        <a href="{{route('forget.password.get')}}" class="">Lupa Password ?</a>
                                    </div>
                                @endif
                                <div class="row d-inline mx-auto">
                                    <p class="mt-4 mb-3 text-center">Belum punya akun ? <a href="{{route('register')}}">Daftar Sekarang</a></p>
                                    <button type="submit" class="btn btn-primary mb-0">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </div>
            </div>
        </div>
    </main>
    @else
    @yield('nonauth')
    @stack('scripts')
    @endif
</body>
</html>
