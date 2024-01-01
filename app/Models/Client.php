<?php

namespace App\Models;

use App\Traits\FingerPrint;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory, FingerPrint;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fio',
        'diagnos',
        'contras',
        'burndate',
        'mother',
        'mother_phone',
        'father',
        'father_phone',
        'adress',
    ];

    function wishes(): HasMany {
        return $this->hasMany(Wish::class);
    }
    
    public function created_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
