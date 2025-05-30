<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GatePermission extends Model
{
    //
    // use CreatedUpdatedBy, LogsActivity;
    protected $guarded = [];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //     ->logOnly(['*']);
    //     // Chain fluent methods for configuration options
    // }

    public function permission(){
        return $this->hasOne(GatePermission::class, 'id', 'permission_id');
    }
}
