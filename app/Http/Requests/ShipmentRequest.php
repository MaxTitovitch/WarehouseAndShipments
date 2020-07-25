<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ShipmentRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tracking_number' => 'required|string|max:255',
            'shipping_company' => 'required|in:USPS,FedEx,DHL,UPS',
            'comment' => 'required|string|max:255',
            'received' => 'date|nullable',
            'shipped' => 'date|nullable',
            'user_id' => 'exists:users,id',
            'product_shipments' => 'required|array',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json($errors, 403));
    }
}
