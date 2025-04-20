<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.name' => ['required', 'string', 'max:255'],
            'data.attributes.email' => ['required', 'email', 'max:255', 'unique:clients,email'],
            'data.attributes.phone' => ['nullable', 'string', 'max:20'],
            'data.attributes.address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function mappedAttributes()
    {
        return [
            'name' => $this->input('data.attributes.name'),
            'email' => $this->input('data.attributes.email'),
            'phone' => $this->input('data.attributes.phone'),
            'address' => $this->input('data.attributes.address'),
        ];
    }
}
