<?php

namespace App\Models;

use App\Trait\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GateHasPermission extends Model
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

    public function gate()
    {
        return $this->belongsTo(Gate::class, 'gate_id', 'id');
    }
    public function gate_permission()
    {
        return $this->belongsTo(GatePermission::class, 'gate_permission_id', 'id');
    }
}
