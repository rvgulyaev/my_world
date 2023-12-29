<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $table="wishes";
    protected $fillable=['class_id', 'client_id', 'prefer_amount_of_classes', 'prefer_time'];
}
