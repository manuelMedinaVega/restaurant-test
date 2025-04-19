<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTableRequest extends FormRequest
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
            'data.attributes.number' => ['required', 'string', 'max:20', 'unique:tables,number'],
            'data.attributes.capacity' => ['required', 'integer', 'min:1'],
            'data.attributes.location' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function mappedAttributes()
    {
        return [
            'number' => $this->input('data.attributes.number'),
            'capacity' => $this->input('data.attributes.capacity'),
            'location' => $this->input('data.attributes.location'),
        ];
    }
}
