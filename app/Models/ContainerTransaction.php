<?php

namespace App\Models;

use App\Trait\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;


class ContainerTransaction extends Model
{
    //
    use CreatedUpdatedBy, LogsActivity;
    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }

    protected $dates = ['created_at', 'updated_at'];

    /**
     * Accessor para converter created_at para o fuso horário local.
     */
    

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Africa/Harare');
    }

    /**
     * Accessor para converter updated_at para o fuso horário local.
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timezone('Africa/Harare');
    }
}
