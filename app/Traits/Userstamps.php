<?php

namespace App\Traits;

trait Userstamps
{
    /**
     * Boot the Userstamps trait for the model.
     *
     * @return void
     */
    public static function bootUserstamps()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                if (! $model->isDirty('created_by')) {
                    $model->created_by = auth()->id();
                }
                if (! $model->isDirty('updated_by')) {
                    $model->updated_by = auth()->id();
                }
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                if (! $model->isDirty('updated_by')) {
                    $model->updated_by = auth()->id();
                }
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
