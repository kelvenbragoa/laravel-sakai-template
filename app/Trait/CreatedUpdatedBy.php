<?php

namespace App\Trait;

trait CreatedUpdatedBy
{
    //
    public static function bootCreatedUpdatedBy()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->user_full_name ?? null;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->user_full_name ?? null;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('updated_by')) {
                $model->updated_by = auth()->user()->user_full_name ?? null;
            }
        });
    }
    
}
