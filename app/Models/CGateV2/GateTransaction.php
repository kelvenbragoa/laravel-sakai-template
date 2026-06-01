<?php

namespace App\Models\CGateV2;

use Illuminate\Database\Eloquent\Model;

class GateTransaction extends Model
{
    //
    protected $guarded = [];
    
    protected $connection = 'cgatev2';
    protected $table = 'gate_transactions';    
    public $timestamps = true;

    public function history()
    {
        return $this->hasMany(GateTransactionHistory::class, 'gate_transaction_id', 'id');
    }
    

}
