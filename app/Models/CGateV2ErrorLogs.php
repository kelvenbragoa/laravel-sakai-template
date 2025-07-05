<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CGateV2ErrorLogs extends Model
{
    //
    protected $guarded = [];

    public function error_type()
    {
        return $this->belongsTo(ErrorLogType::class, 'error_type_id');
    }
}
