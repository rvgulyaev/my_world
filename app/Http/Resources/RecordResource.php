<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'educationDate' => $this->educationDate,
            'time_range' => $this->time_range,
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,            
            'class_id' => $this->class_id,            
            'room_id' => $this->room_id,            
        ];
    }
}
