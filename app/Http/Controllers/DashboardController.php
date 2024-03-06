<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(){
        $transactions = Transaction::all();
        $products = Product::all();

        return view('Admin.dashboard', [
            'title' => 'Dashboard',
            'subtotalTransactionsAMonth' => Transaction::whereMonth('created_at', Carbon::today())->sum('subtotal'),
            'subtotalTransactionsADay' => Transaction::whereDate('created_at', Carbon::today())->sum('subtotal'),
            'countTransactions' => $transactions->count(),
            'customers' => User::where('roles', 'Customer')->get(),
            'products' => $products->count(),
            'suppliers' => Supplier::count(),
            'suceedTransactions' => $transactions->where('status', 'Success')->count(),
            'canceledTransactions' => $transactions->where('status', 'Canceled')->count()
        ]);
    }

    public function renderChart(Request $request) {
        if ($request->ajax()) {
            $hours = range(0, 23);
            $hoursWith0 = [];
            foreach ($hours as $hour) {
                $hoursWith0[] = sprintf('%02d', $hour);
            }

            $getRecord = Transaction::whereDate('created_at', Carbon::today())->get();
            $transactions = $getRecord->groupBy(function ($item) {
                return $item->created_at->format('H');
            });
            $transactionArr = [];

            foreach ($hoursWith0 as $hour) {
                if ($transactions->has($hour)) {
                    $transactionArr[] = [
                        'count' => $transactions[$hour]->count(),
                        'total' => $transactions[$hour]->sum('subtotal')
                    ];
                } else {
                    $transactionArr[] = [
                        'count' => 0,
                        'total' => 0
                    ];
                }
            }

            return response()->json(['hours' => $hours, 'transactions' => $transactionArr], 200);
        } else {
            abort(400);
        }
    }



}
