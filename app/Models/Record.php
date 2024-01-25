<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\FingerPrint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Record extends Model
{
    use HasFactory, FingerPrint, Filterable;

    protected $table='record';

    protected $fillable = ['client_id', 'educationDate', 'user_id', 'start_time', 'end_time', 'class_id', 'room_id', 'is_present'];

    protected $filterFields = [
        'educationDate',
        'user_id',
      ];
    
    public function created_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    public function updated_by_user()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function client() : BelongsTo {
        return $this->belongsTo(Client::class);
    }

    public function classes() : BelongsTo {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
