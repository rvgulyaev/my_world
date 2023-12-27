<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'fio' => $this->fio,
            'burndate' => $this->burndate,
            'diagnos' => $this->diagnos,
            'contras' => $this->contras,
            'mother' => $this->mother,
            'mother_phone' => $this->mother_phone,
            'father' => $this->father,
            'father_phone' => $this->father_phone,
            'adress' => $this->adress,
            'created_by' => $this->created_by ? $this->created_by_user->name : "Не определено",
            'updated_by' => $this->updated_by ? $this->updated_by_user->name : "Не определено",
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d.m.Y H:i:s') : "Не определено",
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('d.m.Y H:i:s') : "Не определено",
        ];
    }
}
