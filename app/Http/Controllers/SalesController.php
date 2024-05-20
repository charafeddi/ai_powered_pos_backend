<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sale;
use Carbon\Carbon;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
{
    $sales = Sale::where('user_id', $userId)->get();
    $message = [];

    foreach ($sales as $sale) {
        $message[] = [
            'id' =>$sale->id,
            'name' => $sale->client->name,
            'total_amount' => $sale->total_amount,
            'amount_paid' => $sale->amount_paid ?? 0,
            'paid' => $sale->paid ,
            'date' => Carbon::parse($sale->created_at)->toFormattedDateString()
        ];
    }

    return response()->json($message);
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 
        $sale = Sale::find($id);
        $message =[
            'recipient' => $sale->client,
            'table'=> $sale->salesItem->map(function($item) {
                return [
                    'id' => $item->id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'product_id' => $item->product_id,
                    'product' => $item->product, // assuming the relationship is defined as "product" in the SalesItem model
                ];
            }),
            'sale'=>[
                'id' =>$sale->id,
                'total_amount' => $sale->total_amount,
                'amount_paid' => $sale->amount_paid ?? 0,
                'date' => Carbon::parse($sale->created_at)->toFormattedDateString()
            ]
                
    
        ];
                
        return response()->json( $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
