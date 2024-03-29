<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\Request;


class StockHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Admin.History.stock-in', [
            'title' => 'StockIn History',
            'histories' => StockHistory::with('user', 'product')->get()
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockHistory $stockHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockHistory $stockHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockHistory $stockHistory)
    {
        //
    }
}
