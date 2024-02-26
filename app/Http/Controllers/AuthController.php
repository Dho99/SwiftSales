<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index(){
        return view('Auth.login', [
            'title' => 'Login'
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        $request->flashOnly(['email']);

        if(Auth::attempt($credentials)){
            $roles = Auth::user()->roles;
            if($roles == 'Admin'){
                return redirect()->intended('/admin/dashboard');
            }elseif($roles == 'Cashier'){
                return redirect()->intended('/cashier/dashboard');
            }elseif($roles == 'Customer'){
                return redirect()->intended('/dashboard');
            }else{
                abort(401);
            }
        }

        return back()->with('loginError', 'Login gagal, periksa kembali data anda.');
    }



    public function logout(Request $request){
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }



}

