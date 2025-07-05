<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CGateV2ErrorLogs;
use App\Models\PreCheck;
use Illuminate\Http\Request;

class ErrorLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $searchQuery = request('query');
        $errorlogs = CGateV2ErrorLogs::query()
            ->when(request('query'), function ($query, $searchQuery) {
                $query->where('error_type', 'like', "%{$searchQuery}%");
            })
            ->with('error_type')
            ->orderBy('id', 'desc')
            ->paginate(50);

        return response()->json([
            'data' => $errorlogs
        ]);
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
        try {
            $validatedData = $request->validate([
                'appointment_number' => 'required|string|max:255',
                'error_code' => 'nullable|string|max:255',
                'error_message' => 'nullable|string',
                'error_type_id' => 'nullable',
                'error_type' => 'nullable',
                'logged_user' => 'nullable|string|max:255',
                'error_n4' => 'nullable|string',
            ]);

            $errorLogs = CGateV2ErrorLogs::create([
                'appointment_number' => $validatedData['appointment_number'] ?? null,
                'error_code' => $validatedData['error_code'] ?? null,
                'error_message' => $validatedData['error_message'] ?? null,
                'error_type_id' => $validatedData['error_type_id'] ?? null,
                'error_type' => $validatedData['error_type'] ?? null,
                'logged_user' => $validatedData['logged_user'] ?? null,
                'error_n4' => $validatedData['error_n4'] ?? null,

            ]);

            return response()->json([
                'error'     => [],
                'message'   => [],
                'result'    => $errorLogs,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'message'   => [],
                'result'    => [],
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
