<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return true;
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')
                    ->where('user_id', $this->user()->id)
            ],

            'payment_method' => [
                'required',
                Rule::in(['MIDTRANS', 'COD'])
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.exists' => 'Alamat tidak valid / bukan milik user',
        ];
    }
}
