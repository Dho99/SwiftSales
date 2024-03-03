<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


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

    public function index()
    {

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
        ]);

        if (auth()->user()->email !== $data['email'] || auth()->user()->roles === 'Admin') {
            return response('Tindakan illegal', 403);
        } else {
            try{
                User::where('email', $request->email)->update($data);
                return response()->json(['message' => 'Data Akun berhasil diperbarui']);
            }catch(\Exception $e){
                return response($e->getMessage(), 400);
            }
        }
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
}
