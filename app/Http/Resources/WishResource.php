<?php

namespace App\Http\Resources;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WishResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $class = Classes::where('id', $this->class_id)->select('name')->first();
        ($class) ? $class_name = $class->name: $class_name = -1;
        return [
            'id' => $this->id,
            'class' => $class_name,
            'prefer_amount_of_classes' => $this->prefer_amount_of_classes,
            'prefer_time' => $this->prefer_time,
            'prefer_day' => $this->prefer_day
        ];
    }
}
