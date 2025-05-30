<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        try {
            $precheckdata = DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')->orderBy('number','desc')->paginate(100);

            return response()->json([
                'data' => $precheckdata
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Database connection failed or query error: ' . $th->getMessage()
            ], 500);
        }
        
    }

    public function checkappointment(Request $request)
    {
        try {
            $precheckId = $request->input('appointment_number');

            if (!$precheckId) {
                return response()->json(['error' => 'Invalid input'], 400);
            }

            DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')
                ->where('number', $precheckId)
                ->update(['status' => 'Pre-Check Completed','updated_by'=> auth()->user()->user_full_name,]);
                // ->update([
                //     'status' => 'Pending',
                //     'updated_by'=> auth()->user()->user_full_name,
                // ]);


            return response()->json(
                [
                    'message' => 'Pre-check status updated successfully',
                ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Database connection failed or query error: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $precheckdata = DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')->orderBy('number','desc')->where('number',$id)->first();

            return response()->json([
                'data' => $precheckdata
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Database connection failed or query error: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
