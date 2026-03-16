<?php

namespace App\Models\CGateV2;

use Illuminate\Database\Eloquent\Model;

class GateTransaction extends Model
{
    //
    protected $connection = 'cgatev2';
    protected $table = 'gate_transactions';    
    public $timestamps = true;
}
