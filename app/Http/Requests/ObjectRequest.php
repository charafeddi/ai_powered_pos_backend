<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // // Check if the user has the necessary permissions to create a sale
        // // You can replace the following line with your own logic to check the user's role or permissions
        // if (!auth()->user()->can('create-sale')) {
        //     return false;
        // }

        // If the user is authenticated and has the necessary permissions, then the request is authorized
        return true;    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'total_amount' => 'required|numeric',
            'amount_paid' => 'required|numeric',
            'paid' => 'boolean',
            'user_id' => 'required|integer|exists:users,id',
            'table' => 'required|array',
            'table.*.product.id' => 'required|integer|exists:products,id',
            'table.*.product.product_code' => 'required|string|max:255',
            'table.*.product.designation' => 'required|string|max:255',
            'table.*.quantity' => 'required|integer',
            'table.*.unit_price' => 'required|numeric',
        ];
    }


}
