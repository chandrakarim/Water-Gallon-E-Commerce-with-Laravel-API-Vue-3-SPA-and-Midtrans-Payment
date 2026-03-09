<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class PayOrderRequest extends FormRequest
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
            'payment_proof' => ['required', 'image','mimes:jpg,jpeg,png','max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_proof.required' => 'Bukti transfer wajib diupload',
            'payment_proof.image' => 'File harus berupa gambar',
            'payment_proof.mimes' => 'Format gambar harus jpg, jpeg, png',
            'payment_proof.max' => 'Ukuran gambar maksimal 2MB',
        ];
    }
}
