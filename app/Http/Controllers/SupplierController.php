<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        //
        return response()->json(Supplier::where('user_id', $userId)->get());
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:suppliers,email,',
            'phone' => 'required|string|unique:suppliers,phone,',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'fax' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        
        if($validator ->fails()){
            return response()->json([
                'message' => 'supplier validator failed ', 
                'error' => $validator->errors(),
                 ],400);
        }

        Supplier::create(
            $validator->validated()
        );

        return response()->json([
            'message' => 'Supplier successfully registered',
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
        return response()->json(Supplier::find($id));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:suppliers,email,'.$id,
            'phone' => 'required|string|unique:suppliers,phone,'.$id,
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string',
            'fax' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        
        
        if($validator ->fails()){
            return response()->json([
                'message' => 'supplier validator failed ', 
                'error' => $validator->errors(),
            ],400);
        }

        $supplier = Supplier::find($id);

        $supplier->name = $request->input('name');
        $supplier->email = $request->input('email');
        $supplier->phone = $request->input('phone');
        $supplier->address = $request->input('address');
        $supplier->city = $request->input('city');
        $supplier->country = $request->input('country');
        $supplier->fax = $request->input('fax');
        $supplier->postal_code = $request->input('postal_code');
        $supplier->user_id = $request->input('user_id');
        
        $supplier->save();

        return response()->json([
            'message'=>'Supplier Updated successfully',
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
        $supplier = Supplier::find($id);
        $supplier->delete();
    }
}
