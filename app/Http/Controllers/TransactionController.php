<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.Transactions.index', [
            'title' => 'Create Transactions',
            'customers' => User::where('roles', 'Customer')->get(),
            'products' => Product::all(),
        ]);
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
            $data = $request->all();
            $data['userId'] = auth()->user()->id;
            $data['status'] = 'Success';
            try{
                Transaction::create($data);
                return response()->json(['message' => 'Data Transaksi berhasil disimpan'],200);
            }catch(\Exception $e){
                return response($e->getMessage(), 400);
            }
        }else{
            abort(400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function history(Request $request){
        $transactions = Transaction::all();
        if($request->ajax()){

        }else{
            return view('Admin.History.transactions', [
                'title' => 'Riwayat Transaksi'
            ]);
        }
    }
}
