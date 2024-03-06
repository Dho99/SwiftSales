<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $supplier = Supplier::all();
            return response()->json(['data' => $supplier], 200);
        }else{
            return view('Admin.Supplier.list', [
                'title' => 'Daftar Supplier'
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
        if($request->ajax()){
            $data = $request->validate([
                'name' => 'required|unique:suppliers,name',
                'telephone' => 'required|unique:suppliers,telephone',
                'address' => 'required'
            ]);

            try{
                Supplier::create($data);
                return response()->json(['message' => 'Data Supplier baru berhasil Dibuat'], 201);
            }catch(\Exception $e){
                return response($e->getMessage(), 500);
            }
        }else{
            abort(400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier, Request $request)
    {
        if($request->ajax()){
            return response()->json(['data' => $supplier], 200);
        }else{
            return view('errors.404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        if($request->ajax()){
            $supplier->update($request->all());
            return response()->json(['message' => 'Data Supplier berhasil diperbarui'], 200);
        }else{
            abort(400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier, Request $request)
    {
        if($request->ajax()){
            $sName = $supplier->name;
            $supplier->delete();
            return response()->json(['message' => 'Data Supplier '.$sName.' berhasil dihapus'], 200);
        }else{
            abort(400);
        }
    }
}
