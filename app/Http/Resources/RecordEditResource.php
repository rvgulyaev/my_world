<?php

namespace App\Http\Resources;

use App\Models\Classes;
use App\Models\Client;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordEditResource extends JsonResource
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
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user' => User::where('id', $this->user_id)->select('name', 'id')->first(),
            'client' => Client::where('id', $this->client_id)->select('fio as name', 'id')->first(),
            'class' => Classes::where('id', $this->class_id)->select('name', 'id')->first(),  
            'room' => Room::where('id', $this->room_id)->select('name', 'id')->first(),            
        ];
    }
}
