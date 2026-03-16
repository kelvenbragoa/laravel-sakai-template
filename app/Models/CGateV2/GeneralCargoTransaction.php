<?php

namespace App\Models\CGateV2;

use Illuminate\Database\Eloquent\Model;

class GeneralCargoTransaction extends Model
{
    //
    protected $connection = 'cgatev2_general_cargo';
    protected $table = 'general_cargo_transactions';    
    public $timestamps = true;
}
