<?php

namespace App\Models\CGateV2;

use Illuminate\Database\Eloquent\Model;

class CGateExcpetion extends Model
{
    //
    protected $guarded = [];
    
    protected $connection = 'cgatev2';
    protected $table = 'exceptions';    
    public $timestamps = true;

    public function transaction()
    {
        return $this->hasOne(GateTransaction::class, 'id', 'transaction_id');
    }
}
