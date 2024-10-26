<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderRequest;
use App\Models\Order;
use App\Traits\ResponseHandler;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseHandler;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $order = Order::all();
        return response()->json($this->success($order));
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
    public function store(OrderRequest $request)
    {
        $order = Order::create($request->all());
        return response()->json($this->success($order));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
