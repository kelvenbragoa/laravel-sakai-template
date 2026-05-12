<?php

namespace App\Models\CGateV2;

use Illuminate\Database\Eloquent\Model;

class GateTransactionHistory extends Model
{
    //
    protected $guarded = [];
    
    protected $connection = 'cgatev2';
    protected $table = 'gate_transaction_history';    
    public $timestamps = true;
}
