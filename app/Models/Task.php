<?php

namespace App\Models;

use App\Traits\FingerPrint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, FingerPrint, SoftDeletes;

    protected $table='task';

    protected $fillable =['task', 'executeDate', 'executed', 'comments'];

    public function created_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
