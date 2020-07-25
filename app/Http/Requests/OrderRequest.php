<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer' => 'required|string|max:255',
            'comment' => 'required|string|max:255',
            'shipping_company' => 'required|in:USPS,FedEx,DHL,UPS',
            'status' => 'in:In progress,Completed,Canceled',
            'shipping_cost' => 'numeric|nullable',
            'tracking_number' => 'required|string|max:255',
            'shipped' => 'date|nullable',
            'packing_selection' => 'required|in:Bubbles Pack,Carton',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_postal_code' => 'required|string|max:255',
            'state_region' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'phone' => 'required|regex:/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/',
            'user_id' => 'exists:users,id',
            'order_products' => 'required|array',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json($errors, 403));
    }
}
