<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\Cfm\CfmService;
use Illuminate\Http\Request;

class CfmSacController extends Controller
{
    //
    public function __construct(private readonly CfmService $cfmService)
    {
        // Middleware can be applied here if needed
    }


    public function crosscheck(Request $request){
        try {
            return $this->cfmService->crosscheck($request->all());
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error crosschecking CFM SAC data',
                'error' => $th->getMessage(),
            ], 400);
        }
    }
}
