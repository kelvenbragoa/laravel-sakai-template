<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PreCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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

    public function uploadprecheckimage(Request $request){

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'format' => 'required|string',
                'id' => 'required|string',
            ]);
            $data = $request->all();

    
            $folderName = now()->format('m_Y');
    
            
            $path = "uploads/precheck/".now()->format('Y')."/".now()->format('m')."/".$data['id']."/".$data['format'];

            $imageName = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();

            $filePath = $request->file('image')->storeAs($path, $imageName, 'public');

            $url = Storage::url($filePath);
    
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        'successfull'
                    ],
                    'result' => [
                        $url
                    ],
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => [
                        
                    ],
                    'message' => $th->getMessage(),
                    'result' => [],
                ],
                400
            );
        }

        
    }

    public function savetransaction(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'number' => 'required|string|max:255',
                'status' => 'nullable|string|max:255',
                'user_name' => 'nullable|string|max:255',
                'booking_number' => 'nullable|string|max:255',

                'first_last_name' => 'nullable|string|max:255',
                'first_last_name_overwrite' => 'nullable|string|max:255',

                'driver_license_number' => 'nullable|string|max:255',
                'driver_license_number_overwrite' => 'nullable|string|max:255',
                'driver_license_cutout_photo' => 'nullable|string|max:255',

                'main_plate_overwrite' => 'nullable|string|max:255',
                'main_plate_cutout_photo' => 'nullable|string|max:255',

                'container_number' => 'nullable|string|max:255',
                'container_number_overwrite' => 'nullable|string|max:255',
                'container_number_cutout_photo' => 'nullable|string|max:255',
                'container_seal_number' => 'nullable|string|max:255',
                'seal_cutout_photo' => 'nullable|string|max:255',

                'status_comment' => 'nullable|string|max:255',

            ]);

            $precheck = PreCheck::create([
                'number' => $validatedData['number'],
                'status' => $validatedData['status'] ?? null,
                'user_name' => $validatedData['user_name'] ?? null,
                'booking_number' => $validatedData['booking_number'] ?? null,
                'first_last_name' => $validatedData['first_last_name'] ?? null,
                'first_last_name_overwrite' => $validatedData['first_last_name_overwrite'] ?? null,
                'driver_license_number' => $validatedData['driver_license_number'] ?? null,
                'driver_license_number_overwrite' => $validatedData['driver_license_number_overwrite'] ?? null,
                'driver_license_cutout_photo' => $validatedData['driver_license_cutout_photo'] ?? null,
                'main_plate_overwrite' => $validatedData['main_plate_overwrite'] ?? null,
                'main_plate_cutout_photo' => $validatedData['main_plate_cutout_photo'] ?? null,
                'container_number' => $validatedData['container_number'] ?? null,
                'container_number_overwrite' => $validatedData['container_number_overwrite'] ?? null,
                'container_number_cutout_photo' => $validatedData['container_number_cutout_photo'] ?? null,
                'container_seal_number' => $validatedData['container_seal_number'] ?? null,
                'seal_cutout_photo' => $validatedData['seal_cutout_photo'] ?? null,
                'status_comment' => $validatedData['status_comment'] ?? null,
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

    public function listpreadvices(Request $request)
    {
        try {
            $queryResult = DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')->orderBy('number', 'desc');

            $searchQuery = $request->input('number');

            if (!empty($searchQuery)) {
                $queryResult->where('number', $searchQuery);
            }

            $precheckdata = $queryResult->paginate(50);

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
