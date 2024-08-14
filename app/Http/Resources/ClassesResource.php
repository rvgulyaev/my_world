<?php

namespace App\Http\Resources;

use App\Models\ClassesGroups;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassesResource extends JsonResource
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
            'has_group' => $this->has_group,
            'class_group_id' => $this->class_group_id,
            'class_group' => ($this->class_group_id > 0) ? ClassesGroups::where('id', $this->class_group_id)->select('name')->first()->name : 'Вне группы',
            'order' => $this->order,
        ];
    }
}
