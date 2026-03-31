<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\GeneralCargoTransaction;

use App\Http\Controllers\Controller;
use App\Models\CGateV2\GeneralCargoTransaction;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use App\Http\Resources\GeneralCargoTransactionResource;



class GeneralCargoTransactionController extends Controller
{
    //
    use HttpResponses;

    protected $transaction;

    public function __construct(GeneralCargoTransaction $generalCargoTransaction)
    {
        $this->transaction = $generalCargoTransaction;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchQuery = request('search');
        $gateQuery = request('gate');
        $noPagination = request('no_pagination');


        $transactionQuery = $this->transaction::query()
        ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                //general DB ENEGNINE
                // $startDateTimeSearch = request('startdatetime');
                // $endDateTimeSearch = request('enddatetime');
    
                // // //SQL SERVER ENGINE
                $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');
    
                $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
        })
        ->when(request('search'), function ($query, $searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
        $q->where('driver_license_number', 'like', "%{$searchQuery}%")
          ->orWhere('driver_license_number_overwrite', 'like', "%{$searchQuery}%")
          ->orWhere('truck_license_plate_number', 'like', "%{$searchQuery}%")
          ->orWhere('truck_license_plate_number_overwrite', 'like', "%{$searchQuery}%")
          ->orWhere('trailer_1_license_plate_number', 'like', "%{$searchQuery}%")
          ->orWhere('trailer_2_license_plate_number', 'like', "%{$searchQuery}%")
          ->orWhere('document_number', 'like', "%{$searchQuery}%")
          ->orWhere('driver_name', 'like', "%{$searchQuery}%")
          ->orWhere('driver_name_overwrite', 'like', "%{$searchQuery}%")
          ->orWhere('cargo_type', 'like', "%{$searchQuery}%");

          
    });
        })
        ->when(request('gate'), function ($query, $gateQuery) {
            $query->where('gate', 'like', "%{$gateQuery}%");
        })
        
        ->orderBy('id','desc');
        // ->paginate();

        if ($noPagination) {
            $transaction = $transactionQuery->get();
        } else {
            $transaction = $transactionQuery->paginate();
        }
        return response()->json([
            'error'     => [],
            'message'   => [],
            'result'    => $transaction,
        ], 200);
    }

    public function indexWithoutAuth(){
        
        $searchQuery = request('search');

        $transaction = $this->transaction::query()
        ->when(request('search'), function ($query, $searchQuery) {
            $query->where('driver_license_number', 'like', "%{$searchQuery}%")
            ->orWhere('driver_license_number_overwrite', 'like', "%{$searchQuery}%")
            ->orWhere('truck_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('truck_license_plate_number_overwrite', 'like', "%{$searchQuery}%")
            ->orWhere('trailer_1_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('trailer_2_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('document_number', 'like', "%{$searchQuery}%")
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
        ->orderBy('id','desc')->paginate(1000);
        // return response()->json($transaction);

        // return response()->json(GeneralCargoTransactionResource::collection($transaction));
        return GeneralCargoTransactionResource::collection($transaction);

    

    }

    public function indexWithoutAuthTest(){
        $searchQuery = request('search');

        $transaction = $this->transaction::query()
        ->when(request('search'), function ($query, $searchQuery) {
            $query->where('driver_license_number', 'like', "%{$searchQuery}%")
            ->orWhere('driver_license_number_overwrite', 'like', "%{$searchQuery}%")
            ->orWhere('truck_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('truck_license_plate_number_overwrite', 'like', "%{$searchQuery}%")
            ->orWhere('trailer_1_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('trailer_2_license_plate_number', 'like', "%{$searchQuery}%")
            ->orWhere('document_number', 'like', "%{$searchQuery}%")
            ;
        })
        ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                //general DB ENEGNINE
                // $startDateTimeSearch = request('startdatetime');
                // $endDateTimeSearch = request('enddatetime');
    
                // //SQL SERVER ENGINE
                $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');

                // $startDateTimeSearch = Carbon::createFromFormat('Y-m-d\TH:i:s', request('startdatetime'), 'Africa/Harare')
                //     ->setTimezone('UTC')
                //     ->toDateTimeString();

                // $endDateTimeSearch = Carbon::createFromFormat('Y-m-d\TH:i:s', request('enddatetime'), 'Africa/Harare')
                //     ->setTimezone('UTC')
                //     ->toDateTimeString();
    
                $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
        })
        ->orderBy('id','desc')->paginate(1000);

        // return response()->json([
        //     'start'=>Carbon::createFromFormat('Y-m-d\TH:i:s', request('startdatetime'), 'UTC')
        //     ->setTimezone('UTC')
        //     ->toDateTimeString(),
        //     'end'=>Carbon::createFromFormat('Y-m-d\TH:i:s', request('enddatetime'), 'UTC')
        //     ->setTimezone('UTC')
        //     ->toDateTimeString()
        // ]);
        // return response()->json([
        //     'start'=>Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s'),
        //     'end'=>Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s')
        // ]);


        return response()->json($transaction);
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
            $transaction = $this->transaction::find($id);
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
}
