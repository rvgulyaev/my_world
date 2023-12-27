<?php namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait FingerPrint
{
    protected static function boot()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        });

        static::created(function ($model) {
            $model->created_by = auth()->user()->id;
            $model->updated_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        static::updated(function ($model) {
            $model->updated_by = auth()->user()->id;
        });

        parent::boot();
    }

    public function updated_by_user()
    {
        return $this->belongsTo('App\Models\User', 'updated_by', 'id');
    }

    public function created_by_user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }
}