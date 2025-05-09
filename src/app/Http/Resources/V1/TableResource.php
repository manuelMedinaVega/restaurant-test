<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'table',
            'id' => $this->id,
            'attributes' => [
                'number' => $this->number,
                'capacity' => $this->capacity,
                'location' => $this->location,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'links' => [
                'self' => route('tables.show', ['table' => $this->id]),
            ],
        ];
    }
}
