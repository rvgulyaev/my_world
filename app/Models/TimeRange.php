<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TimeRange extends Model
{
    use HasFactory;
    protected $table="time_ranges";

    public function record(): HasMany {
        return $this->hasMany(Record::class, 'id', 'time_range');
    }
}
