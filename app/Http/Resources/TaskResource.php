<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'task' => $this->task,
            'executeDate' => $this->executeDate ? Carbon::parse($this->executeDate)->format('d.m.Y') : "Не определено",
            'executed' => $this->executed,
            'comments' => $this->comments,
            'created_by_id' => $this->created_by,
            'created_by' => $this->created_by ? $this->created_by_user->name : "Не определено",
            'updated_by' => $this->updated_by ? $this->updated_by_user->name : "Не определено",
            'created_at' => $this->created_at ? Carbon::parse($this->created_at)->format('d.m.Y') : "Не определено",
            'updated_at' => $this->updated_at ? Carbon::parse($this->updated_at)->format('d.m.Y') : "Не определено",
        ];
    }
}
