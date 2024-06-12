<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Carbon\Carbon;
use App\Http\Requests\ObjectRequest;
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
    public function store(ObjectRequest $request)
    {
        //
         $sale = Sale::create(
            [
                'client_id' =>  $request->input('client_id'),
                'total_amount' => $request->input('total_amount'),
                'amount_paid' =>  $request->input('amount_paid'),
                'paid' => $request->input('paid'),
                'user_id' => $request->input('user_id'),
            ]
        );

        foreach($request->input('table') as $item){
             // Find the product by ID
             $product = Product::find($item['product']['id']);
             if ($product) {
                 // Update product quantity
                 $product->quantity -= $item['quantity'];
                 $product->save();
 
                 // Create sale item
                 SaleItem::create([
                     'quantity' => $item['quantity'],
                     'unit_price' => $item['unit_price'],
                     'subtotal' => $item['unit_price'] * $item['quantity'],
                     'sale_id' => $sale->id,
                     'product_id' => $item['product']['id'],
                 ]);
             } else {
                 return response()->json([
                     'message' => 'Product not found: ' . $item['product']['id']
                 ], 404);
             }
        }

        return response()->json([
            'message'=> 'Sale and Sale Items are add successfully'
        ],201);
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
            'table'=> $sale->saleItems->map(function($item) {
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
    // public function update(ObjectRequest $request, $id)
    // {
    //     // update sale 
    //     $sale = Sale::find($id);
         
    //     $sale-> client_id = $request->input('client_id');
    //     $sale->total_amount = $request->input('total_amount');
    //     $sale->amount_paid =  $request->input('amount_paid');
    //     $sale->paid = $request->input('paid');
    //     $sale->user_id = $request->input('user_id');
    //     $sale->save();

    //     foreach($request->input('table') as $item){
    //          // Find the product by ID
    //          $product = Product::find($item['product']['id']);
    //          if ($product) {
    //              // Update product quantity
    //              $product->quantity -= $item['quantity'];
    //              $product->save();
 
    //              // update sale item
    //             $saleItem = SaleItem::find($item['id']);

    //                  $saleItem->quantity = $item['quantity'];
    //                  $saleItem->unit_price = $item['unit_price'];
    //                  $saleItem->subtotal = $item['unit_price'] * $item['quantity'];
    //                  $saleItem->sale_id = $sale->id;
    //                  $saleItem->product_id = $item['product']['id'];
    //                  $saleItem->save();
    //          } else {
    //              return response()->json([
    //                  'message' => 'Product not found: ' . $item['product']['id']
    //              ], 404);
    //          }
    //     }

    //     return response()->json([
    //         'message'=> 'Sale and Sale Items are Updated successfully'
    //     ],201);
    // }
    public function update(ObjectRequest $request, $id)
    {
        // Update sale 
        $sale = Sale::find($id);
        
        if (!$sale) {
            return response()->json([
                'message' => 'Sale not found.'
            ], 404);
        }
    
        $sale->client_id = $request->input('client_id');
        $sale->total_amount = $request->input('total_amount');
        $sale->amount_paid = $request->input('amount_paid');
        $sale->paid = $request->input('paid');
        $sale->user_id = $request->input('user_id');
        $sale->save();
    
        $existingSaleItemIds = $sale->saleItems()->pluck('id')->toArray();
    
        foreach ($request->input('table') as $item) {
            if (isset($item['id'])) {
                // Update existing sale item
                $saleItem = SaleItem::find($item['id']);
                if ($saleItem) {
                    $product = Product::find($item['product']['id']);
                    if ($product) {
                        // Revert the quantity before update
                        $product->quantity += $saleItem->quantity;
                        $product->quantity -= $item['quantity'];
                        $product->save();
    
                        $saleItem->quantity = $item['quantity'];
                        $saleItem->unit_price = $item['unit_price'];
                        $saleItem->subtotal = $item['unit_price'] * $item['quantity'];
                        $saleItem->sale_id = $sale->id;
                        $saleItem->product_id = $item['product']['id'];
                        $saleItem->save();
    
                        // Remove this id from the existingSaleItemIds array
                        $existingSaleItemIds = array_diff($existingSaleItemIds, [$saleItem->id]);
                    } else {
                        return response()->json([
                            'message' => 'Product not found: ' . $item['product']['id']
                        ], 404);
                    }
                } else {
                    return response()->json([
                        'message' => 'Sale item not found: ' . $item['id']
                    ], 404);
                }
            } else {
                // Add new sale item
                $product = Product::find($item['product']['id']);
                if ($product) {
                    $product->quantity -= $item['quantity'];
                    $product->save();
    
                    $saleItem = new SaleItem();
                    $saleItem->quantity = $item['quantity'];
                    $saleItem->unit_price = $item['unit_price'];
                    $saleItem->subtotal = $item['unit_price'] * $item['quantity'];
                    $saleItem->sale_id = $sale->id;
                    $saleItem->product_id = $item['product']['id'];
                    $saleItem->save();
                } else {
                    return response()->json([
                        'message' => 'Product not found: ' . $item['product']['id']
                    ], 404);
                }
            }
        }
    
        // Remove sale items that are no longer in the request
        foreach ($existingSaleItemIds as $saleItemId) {
            $saleItem = SaleItem::find($saleItemId);
            if ($saleItem) {
                $product = Product::find($saleItem->product_id);
                if ($product) {
                    // Revert the product quantity
                    $product->quantity += $saleItem->quantity;
                    $product->save();
                }
                $saleItem->delete();
            }
        }
    
        return response()->json([
            'message' => 'Sale and Sale Items are updated successfully'
        ], 201);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }
}
