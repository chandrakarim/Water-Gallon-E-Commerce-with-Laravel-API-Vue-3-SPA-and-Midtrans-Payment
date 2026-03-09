<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:100'],
            'phone'          => ['required', 'string', 'max:20'],
            'province'       => ['required', 'string', 'max:100'],
            'city'           => ['required', 'string', 'max:100'],
            'district'       => ['required', 'string', 'max:100'],
            'detail'         => ['required', 'string'],
            'label'          => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
        ];
    }
}
