<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\ContainerTransaction;
use App\Services\ExportTallyInService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MobileContainerTransactionController extends Controller
{

    protected $transaction;

    public function __construct(ContainerTransaction $cGateContainerTransaction, private readonly ExportTallyInService $exportTallyService)
    {
        $this->transaction = $cGateContainerTransaction;
    }

    public function index()
    {
        //
        $transaction = $this->transaction::query()
        ->when(request('search'), function ($query, $searchQuery) {
            $query->where('driver_license_number', 'like', "{$searchQuery}%")
            ->orWhere('driver_license_number_overwrite', 'like', "{$searchQuery}%")
            ->orWhere('main_plate', 'like', "%{$searchQuery}%")
            ->orWhere('main_plate_overwrite', 'like', "{$searchQuery}%")
            ->orWhere('trailer_1_license_plate_number', 'like', "{$searchQuery}%")
            ->orWhere('trailer_2_license_plate_number', 'like', "{$searchQuery}%")
            ->orWhere('container_number_1', 'like', "{$searchQuery}%")
            ->orWhere('container_seal_number_1', 'like', "{$searchQuery}%")
            ->orWhere('container_number_2', 'like', "%{$searchQuery}%")
            ->orWhere('container_seal_number_2', 'like', "{$searchQuery}%")
            ->orWhere('container_number_3', 'like', "{$searchQuery}%")
            ->orWhere('container_seal_number_3', 'like', "{$searchQuery}%")
            ;
        })
        ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                //general DB ENEGNINE
                // $startDateTimeSearch = request('startdatetime');
                // $endDateTimeSearch = request('enddatetime');
    
                // // //SQL SERVER ENGINE
                $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');
    
                $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
        })
        ->orderBy('id','desc')->paginate(15);
        return response()->json([
            'error'     => [],
            'message'   => [],
            'result'    => $transaction,
        ], 200);
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
        $data = $request->all();

        if (!$saved = $this->transaction->create($data)){
            return response()->json(
                [
                    'error' => [],
                    'message' => 'error',
                    'result' => [],
                ],
                400
            );
        }

        return response()->json(
            [
                'error' => [],
                'message' => [
                    'successfull'
                ],
                'result' => [
                    $saved
                ],
            ],
            200
        );

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $transaction = $this->transaction::find($id);
            return response()->json([
                'error'     => [],
                'message'   => [],
                'result'    => $transaction,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
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
        try {
            $data = $request->all();
        $transaction = ContainerTransaction::findOrFail($id);
        if (!$updated = $transaction->update($data)){
            return response()->json(
                [
                    'error' => [],
                    'message' => 'error',
                    'result' => [],
                ],
                400
            );
        }

        return response()->json(
            [
                'error' => [],
                'message' => [
                    'successfull'
                ],
                'result' => [
                    $updated
                ],
            ],
            200
        );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => [],
                    'message' => $th->getMessage(),
                    'result' => [],
                ],
                400
            );
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function uploadimage(Request $request){

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'format' => 'required|string',
                'id' => 'required|string',
            ]);
            $data = $request->all();

            // dd($data); //teste dps dadps qie vem da request
    
            $folderName = now()->format('m_Y');
    
            
            $path = "uploads/cgate_container/".now()->format('Y')."/".now()->format('m')."/".$data['id']."/".$data['format'];

            $imageName = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();

            // $filePath = $request->file('image')->storeAs($path, $imageName, 'public');
            $filePath = $request->file('image')->storeAs($path, $imageName, 'network');

            // $url = Storage::url($filePath);
    
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        'successfull'
                    ],
                    'result' => [
                        $filePath
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

        public function uploadv2image(Request $request){

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
                'format' => 'required|string',
                'id' => 'required|string',
            ]);
            $data = $request->all();

            // dd($data); //teste dps dadps qie vem da request
    
            $folderName = now()->format('m_Y');
    
            
            $path = "uploads/cgate_v2/".now()->format('Y')."/".now()->format('m')."/".$data['id']."/".$data['format'];

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


    public function tallyInForExport(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->exportTallyService->tallyInForExport($data);
            return $response;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

}