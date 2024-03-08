<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function authUser()
    {
        return auth()->user();
    }

    public function customerLists(Request $request){
        $customers = User::where('roles', 'Customer')->get();
        if($request->ajax()){
            return response()->json(['customers' => $customers], 200);
        }else{
            return view('Admin.Customer.customer-list',[
                'title' => 'Daftar Pelanggan',
            ]);
        }
    }

    public function index(Request $request)
    {
        if(auth()->user()->roles === 'Admin'){
            $users = User::whereNot('id', auth()->user()->id)->get();
            $title = 'Daftar Pengguna';
        }else{
            $users = User::whereNot('id', auth()->user()->id)->where('roles', 'Customer')->get();
            $title = 'Daftar Customer';
        }
        if($request->ajax()){
            return response()->json(['users' => $users], 200);
        }else{
            return view('Admin.Users.lists',[
                'title' => $title,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user)
    {
        if($request->ajax()){
            return response()->json(['data' => $user], 200);
        }else{
            return view('User.details', [
                'title' => 'Info Akun',
                'user' => $user,
            ]);

        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'email' => 'required',
            'profilePhoto' => 'required',
            'name' => 'required',
            'telephone' => 'required',
            'password' => 'required',
        ]);

        $data['password'] = Hash::make($data['password']);

        // if (auth()->user()->email !== $data['email'] || auth()->user()->roles !== 'Admin') {
        //     return response('Tindakan illegal', 403);
        // } else {
            try{
                User::where('email', $request->email)->update($data);
                return response()->json(['message' => 'Data Akun berhasil diperbarui']);
            }catch(\Exception $e){
                return response($e->getMessage(), 400);
            }
        // }
    }


    public function updateCustomer(Request $request, User $user)
    {
        if($request->ajax()){
            $data = $request->validate([
                'roles' => 'required'
            ]);
            $user->update($data);
            return response()->json(['message' => 'Data Akun berhasil diperbarui']);
        }else{
            abort(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $image = $user->profilePhoto;
        $roles = $user->roles;
        if(File::exists($image)){
            Storage::delete($image);
        }
        try{
            $user->delete();
            return response()->json(['message' => 'Data '.$roles.' berhasil dihapus'], 200);
        }catch(\Exception $e){
            return response($e->getMessage(), 400);
        }

    }

    public function register()
    {
        return view('User.register', [
            'title' => 'Daftarkan Akun'
        ]);
    }

    public function storeRegisteredAccount(Request $request)
    {
        $request->flashOnly(['name', 'email', 'telephone']);

        $data = $request->all();
        $data['code'] = mt_rand(0, 999999);

        if(isset($request->roles)){
            $data['roles'] = $request->roles;
        }else{
            $data['roles'] = 'Customer';
        }

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|unique:users',
            'password' => 'required',
            'roles' => 'required',
            'code' => 'required',
        ]);

        if($validator->fails()){
            return response($validator->errors(), 500);
        }

        $validated = $validator->validated();
        $validated['profilePhoto'] = '';
        $validated['address'] = $request->address;
        try{
            $user = User::create($validated);
            if($request->ajax()){
                return response()->json(['message' => 'Data berhasil Ditambahkan', 'data' => $user], 201);
            }else{
                return back()->with('success', 'Data anda berhasil Terdaftar, silakan Login');
            }
        }catch(\Exception $e){
            if($request->ajax()){
                return response($e->getMessage(), 400);
            }else{
                return back()->withErrors($e->getMessage());
            }
        }
    }
}
