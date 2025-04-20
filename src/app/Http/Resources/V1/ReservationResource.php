<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'reservation',
            'id' => $this->id,
            'attributes' => [
                'date' => $this->date,
                'time' => Carbon::parse($this->time)->format('H:i'),
                'numberOfPeople' => $this->number_of_people,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'client' => [
                    'data' => [
                        'type' => 'client',
                        'id' => $this->client_id,
                        'attributes' => (new ClientResource($this->whenLoaded('client')))->resolve(),
                    ],
                    'links' => [
                        'self' => route('clients.show', ['client' => $this->client_id]),
                    ],
                ],
                'table' => [
                    'data' => [
                        'type' => 'tables',
                        'id' => $this->table_id,
                        'attributes' => (new TableResource($this->whenLoaded('table')))->resolve(),
                    ],
                    'links' => [
                        'self' => route('tables.show', ['table' => $this->table_id]),
                    ],
                ],
            ],
            'links' => [
                'self' => route('reservations.show', ['reservation' => $this->id]),
            ],
        ];
    }
}
