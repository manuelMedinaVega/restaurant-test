<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'data.relationships' => ['required', 'array'],
            'data.attributes.date' => ['required', 'date'],
            'data.attributes.time' => ['required', 'date_format:H:i'],
            'data.attributes.number_of_people' => ['required', 'integer', 'min:1'],
            'data.relationships.client.data.id' => ['required', 'exists:clients,id'],
            'data.relationships.table.data.id' => ['required', 'exists:tables,id'],
        ];
    }

    public function mappedAttributes()
    {
        return [
            'date' => $this->input('data.attributes.date'),
            'time' => $this->input('data.attributes.time'),
            'number_of_people' => $this->input('data.attributes.number_of_people'),
            'client_id' => $this->input('data.relationships.client.data.id'),
            'table_id' => $this->input('data.relationships.table.data.id'),
        ];
    }
}
