<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignCourierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'courier_id' => ['required', 'exists:users,id']
        ];
    }
    public function messages()
    {
        return [
            'courier_id.required' => 'Kurir wajib dipilih',
            'courier_id.exists' => 'Kurir tidak ditemukan'
        ];
    }
}
