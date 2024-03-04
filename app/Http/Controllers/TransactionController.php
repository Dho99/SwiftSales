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
    public function index(Request $request)
    {
        // if(isset($startDate) && isset($endDate)){
        //     $startDate = $startDate;
        //     $endDate = $endDate;
        // }else{
        $endDate = now();
        //     $endDate = now()->subdays(30)->endOfDay();
        // }
        $startDate = now()->subdays(30)->endOfDay();
        $transactions = Transaction::whereBetween('created_at', [$startDate, $endDate])->with('user', 'customer')->get();
        if($request->ajax()){
            return response()->json(['transactions' => $transactions]);
        }else{
            return view('Admin.History.transactions', [
                'title' => 'Riwayat Transaksi'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Transactions.index', [
            'title' => 'Create Transactions',
            'customers' => User::where('roles', 'Customer')->get(),
            'products' => Product::all(),
        ]);
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
            $data['code'] = 'TRXN'.mt_rand(0, 9999999999);
            try{

                foreach(json_decode($data['productId']) as $key => $product){
                    $getModel = Product::where('id', $product)->first();
                    $currentStock = $getModel->stock;
                    $calculateStock = $currentStock -= json_decode($data['quantity'])[$key];
                    $getModel->update(['stock' => $calculateStock]);
                }
                $writeTransaction = Transaction::create($data);
                return response()->json(['message' => 'Data Transaksi berhasil disimpan', 'id' => $writeTransaction->id],200);
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
    public function show(Request $request, Transaction $transaction)
    {
        $transaction->load('customer');
        $transaction->load('user');
        $transaction->load('product');

        $productArr = [];
        $productArr['code'] = $transaction->code;
        $productArr['customer'] = $transaction->customer->name;
        $productArr['user'] = $transaction->user->name;
        $productArr['status'] = $transaction->status;
        $productArr['subtotal'] = $transaction->subtotal;
        $productArr['product'] = Product::whereIn('id', json_decode($transaction->productId))->get();
        $productArr['created_at'] = $transaction->created_at;
        $productArr['qty'] = json_decode($transaction->quantity);
        $productArr['total'] = json_decode($transaction->total);

        if($request->ajax()){
            return response()->json(['data' => $productArr], 200);
        }else{
            return view('errors.404');
        }
    }

    public function print(Transaction $transaction)
    {
        $transaction->load('customer');
        $transaction->load('user');
        $transaction->load('product');

        $productArr = [];
        $productArr['code'] = $transaction->code;
        $productArr['customer'] = $transaction->customer->name;
        $productArr['user'] = $transaction->user->name;
        $productArr['status'] = $transaction->status;
        $productArr['subtotal'] = $transaction->subtotal;
        $productArr['product'] = Product::whereIn('id', json_decode($transaction->productId))->get();
        $productArr['created_at'] = $transaction->created_at;
        $productArr['qty'] = json_decode($transaction->quantity);
        $productArr['total'] = json_decode($transaction->total);

        return view('Admin.Transactions.invoice', [
            'title' => 'Invoice Transaksi',
            'transaction' => $productArr
        ]);
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

    }
}
