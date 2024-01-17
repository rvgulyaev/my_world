<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'specialist' => $this->specialist,
            'phone' => $this->phone,
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('d.m.Y') : "",
            'banned_at' => $this->banned_at ? Carbon::parse($this->banned_at)->format('d.m.Y H:i:s') : "",
            'roles' => $this->getRoleNames(),
        ];
    }
}
