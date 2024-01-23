<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wish extends Model
{
    use HasFactory;

    protected $table="wishes";
    protected $fillable=['class_id', 'client_id', 'prefer_amount_of_classes', 'prefer_day', 'prefer_time'];

    function classes() : BelongsTo {
        return $this->belongsTo(Classes::class);
    }

    function timeRanges() : BelongsTo {
        return $this->belongsTo(TimeRange::class);
    }
}
