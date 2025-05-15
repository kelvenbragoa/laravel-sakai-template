<?php

namespace App\Models;

use App\Trait\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
class UserApplication extends Model
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


    public function application_name(){
        return $this->hasOne('App\Models\Application', 'id', 'application_id');
    }

    public function userApplicationPermissions()
    {
        return $this->hasMany(UserApplicationPermission::class, 'application_id', 'application_id');
    }
}
