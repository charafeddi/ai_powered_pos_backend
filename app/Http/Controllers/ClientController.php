<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {
        // get all the client for this user
        return response()->json(Client::where('user_id',$userId)->get());
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
            'postal_code' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        
        if($validator ->fails()){
            return response()->json([
                'message' => 'Customer validator failed ', 
                'error' => $validator->errors(),
                 ],400);
        }

        Client::create(
            $validator->validated()
        );

        return response()->json([
            'message' => 'Customer successfully registered',
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
        // show Client with id 
        return response()->json(Client::find($id));
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
            'postal_code' => 'nullable|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);
        
        
        if($validator ->fails()){
            return response()->json([
                'message' => 'Customer validator failed ', 
                'error' => $validator->errors(),
            ],400);
        }

        $client = Client::find($id);

        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->phone = $request->input('phone');
        $client->address = $request->input('address');
        $client->city = $request->input('city');
        $client->country = $request->input('country');
        $client->postal_code = $request->input('postal_code');
        $client->user_id = $request->input('user_id');
        
        $client->save();

        return response()->json([
            'message'=>'Customer Updated successfully',
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
        $client = Client::find($id);
        $client->delete();
    }
}
