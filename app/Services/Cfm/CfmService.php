<?php

namespace App\Services\Cfm;

use Illuminate\Support\Facades\DB;

class CfmService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function crosscheck(array $data){
        $license_plate = $data['license_plate'] ?? null;
        $commtrackCrossCheck = DB::connection('sqlsrv4')->table('CommTrac.Adhoc.vw_cfm_sac')->where('vehicle_short_code', $license_plate)->orWhere('vehicle_name',$license_plate)->orderByRaw('CAST(pat_created_when AS datetime2) DESC')->first();
        return $commtrackCrossCheck;
    }
}
