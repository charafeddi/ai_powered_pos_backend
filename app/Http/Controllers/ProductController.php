<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        // get all the product for the user
        $products=Product::where('user_id', $userId)->get();
        
        $total = 0;
        foreach($products as $product){
            $total += ($product->prix_achat * $product->quantity) - ($product->prix_achat * $product->quantity * ($product->discount/100));
        }

        $message = [
            'data' => $products,
            'total' => $total
        ];

        return response()->json($message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'products' => 'required|array',
        'products.*.designation' => 'required|string|max:255',
        'products.*.product_code' => 'required|string|max:255|unique:products',
        'products.*.quantity' => 'required|integer|min:0',
        'products.*.prix_achat' => 'required|numeric|min:0',
        'products.*.prix_vente' => 'nullable|numeric|min:0',
        'products.*.discount' => 'nullable|numeric|min:0|max:100',
        'products.*.product_type_id' => 'nullable|exists:products_types,id',
        'products.*.supplier_id' => 'nullable|exists:suppliers,id',
        'products.*.product_unit_id' => 'nullable|exists:products_unit,id',
        'products.*.user_id' => 'required|exists:users,id'
    ]);

    if($validator ->fails()){
        return response()->json(
            [
                'message' => 'validator failed',
                'error' => $validator->errors()], 400);
    }

    // Check if the request contains a single product or a list of products
    if (is_array($request->input('products'))) {
        // Create a list of products
        foreach ($request->input('products') as $product) {
            Product::create([
                'designation' => $product['designation'],
                'product_code' => $product['product_code'],
                'quantity' => $product['quantity'],
                'prix_achat' => $product['prix_achat'],
                'prix_vente' => $product['prix_vente'] ?? null,
                'discount' => $product['discount'] ?? null,
                'product_type_id' => $product['product_type_id'] ?? null,
                'supplier_id' => $product['supplier_id'] ?? null,
                'product_unit_id' => $product['product_unit_id'] ?? null,
                'user_id' => $product['user_id']
            ]);
        }
    } else {
        // Create a single product
        Product::create([
            'designation' => $request->input('products.designation'),
            'product_code' => $request->input('products.product_code'),
            'quantity' => $request->input('products.quantity'),
            'prix_achat' => $request->input('products.prix_achat'),
            'prix_vente' => $request->input('products.prix_vente') ?? null,
            'discount' => $request->input('products.discount') ?? null,
            'product_type_id' => $request->input('products.product_type_id') ?? null,
            'supplier_id' => $request->input('products.supplier_id') ?? null,
            'product_unit_id' => $request->input('products.product_unit_id') ?? null,
            'user_id' => $request->input('products.user_id')
        ]);
    }

    return response()->json([
        'message' => 'Product(s) created successfully'
    ], 201);
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
        return response()->json(Product::find($id));
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
        // make validator for the product and then update the product
        $validator = Validator::make($request->all(), [
            'designation' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'product_type_id' => 'required|exists:products_types,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'product_unit_id' => 'nullable|exists:products_unit,id',
            'user_id' => 'required|exists:users,id'
        ]);
        
        if($validator ->fails()){
            return response()->json([
                'message' => 'product validator failed ', 
                'error' => $validator->errors()
                 ],400);
        }

        $product = Product::find($id);

        $product->designation = $request->input('designation');
        $product->product_code = $request->input('product_code');
        $product->prix_vente = $request->input('prix_vente');
        $product->prix_achat = $request->input('prix_achat');
        $product->quantity = $request->input('quantity');
        $product->discount = $request->input('discount');
        $product->supplier_id = $request->input('supplier_id');
        $product->product_type_id = $request->input('product_type_id');
        $product->product_unit_id = $request->input('product_unit_id');
        $product->user_id = $request->input('user_id');
        
        $product->save();
        return response()->json([
            'message'=>'Product Updated successfully',
          ],201);
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
        $product = Product::find($id);
        $product->delete();
    }
}
