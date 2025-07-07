<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PreCheckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $searchQuery = request('query');
        $precheck = PreCheck::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('number', 'like', "%{$searchQuery}%");
            })
            ->orderBy('number', 'asc')
            ->paginate(50);

        return response()->json([
            'data' => $precheck
        ]);
        
    }

    public function oldprecheck(Request $request)
    {
        try {
            
            $response = Http::timeout(10)->get(
                'http://102.133.182.163/cdm/api/v1/precheck/listTransactions',
                $request->query()
            );

            // return $response->body();

            // return response()->json($response->body(), $response->status());

            $body = $response->body();

            // Remove o "null" ou qualquer coisa extra depois do JSON válido
            $body = preg_replace('/null$/', '', $body);

            $json = json_decode($body, true);

            return response()->json($json, $response->status());

        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro na requisição oldprecheck: ' . $e->getMessage());

            return response()->json([
                'error' => 'Não foi possível conectar ao serviço de pré-check.',
                'details' => $e->getMessage()
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

    public function savetransaction(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'number' => 'required|string|max:255',
                'status' => 'nullable|string|max:255',
                'user_name' => 'nullable|string|max:255',
                'booking_number' => 'nullable|string|max:255',
            ]);

            $precheck = PreCheck::create([
                'number' => $validatedData['number'],
                'status' => $validatedData['status'] ?? null,
                'user_name' => $validatedData['user_name'] ?? null,
                'booking_number' => $validatedData['booking_number'] ?? null,
            ]);

            return response()->json([
                'error'     => [],
                'message'   => [],
                'result'    => $precheck,
            ], 200);
            

        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message'   => [],
                'result'    => [],
            ], 500);
        }
    }

    public function listpreadvices()
    {
        //
        try {
            $precheckdata = DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')->orderBy('number','desc')->paginate(50);

            return response()->json([
                'data' => $precheckdata
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Database connection failed or query error: ' . $th->getMessage()
            ], 500);
        }
    }
}
