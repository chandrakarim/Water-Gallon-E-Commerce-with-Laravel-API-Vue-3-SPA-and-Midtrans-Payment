<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipient_name' => 'sometimes|string|max:100',
            'phone'          => 'sometimes|string|max:20',
            'province'       => 'sometimes|string|max:100',
            'city'           => 'sometimes|string|max:100',
            'district'       => 'sometimes|string|max:100',
            'detail'         => 'sometimes|string',
            'label'          => 'sometimes|string|max:50',
            'note'           => 'nullable|string',
        ];
    }
}
