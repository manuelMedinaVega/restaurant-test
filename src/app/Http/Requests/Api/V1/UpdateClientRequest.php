<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
        $clientId = $this->route('client')->id;

        return [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.name' => ['required', 'string', 'max:255'],
            'data.attributes.email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],
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
