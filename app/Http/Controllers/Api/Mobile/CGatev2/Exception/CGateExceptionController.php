<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\Exception;

use App\Http\Controllers\Controller;
use App\Models\CGateV2\CGateExcpetion;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CGateExceptionController extends Controller
{
    protected $transactionException;
    public function __construct(CGateExcpetion $exception)
    {
        $this->transactionException = $exception;
    }
    public function index(Request $request)
    {
        $limit      = $request->limit;
        $search     = $request->search;
        $status     = $request->status;
        $terminal     = $request->terminal;
        $type     = $request->type;
        $number = $request->number;
        // $noPagination = request('no_pagination');
        $noPagination =$request->no_pagination;

        // activity()
        // ->performedOn($this->transactionException)
        // ->causedBy('1')
        // ->withProperties(['exceptions list' => 'get all exceptions'])
        // ->event('list')
        // ->log('test');

        //all transactions
        $exceptionsQuery = $this->transactionException->when(
            $search,
            function ($query, $s) {
                $query->whereFullText(
                    ['message', 'comments'],
                    $s,
                    ['mode' => 'natural language']
                );
            },
            // function ($query) {
            //     $query->latest();
            // }
        )->when(
            $status,
            function ($query, $st) {
                $query->where('status', $st);
            },
            // function ($query) {
            //     $query->latest();
            // }
        )->when(
            $terminal,
            function ($query, $ter) {
                $query->where('status','LIKE', "%{$ter}%");
            },
            // function ($query) {
            //     $query->latest();
            // }
        )->when(
            $type,
            function ($query, $typ) {
                $query->where('type','LIKE', "%{$typ}%");
            },
            // function ($query) {
            //     $query->latest();
            // }
        )
        ->when(
            $number,
            function ($queryBuilder, $number) {
                $queryBuilder->whereHas('transaction', function ($q) use ($number) {
                    $q->where('appointment_number', $number);
                });
            },
        )
        ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                    $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                    $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');
        
                    $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
            })
        ->orderBy('id', 'desc')
        ->with('transaction');
        // ->paginate($limit);

        if ($noPagination) {
                $exceptions = $exceptionsQuery->get();
            } else {
                $exceptions = $exceptionsQuery->paginate($limit);
            }

        return response()->json(
            [
                'error' => [],
                'message' => 'success',
                'result' => $exceptions,
            ],
            '200'
        );
    }

    public function store(Request $request)
    {
        //save on DB
        $saveTransaction = $this->transactionException->create($request->all());
        if ($saveTransaction->save()) {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => [],
                ],
                '200'
            );
        } else {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'error',
                    'result' => [],
                ],
                '404'
            );
        }
    }

    public function show($id)
    {
        //show specific transaction
        if ($transactionException = $this->transactionException->find($id)) {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => $transactionException,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //update
        if ($transactionException = $this->transactionException->find($id)) {
            if ($transactionException->update($request->all())) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'success',
                        'result' => [],
                    ],
                    200
                );
            } else {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'error saving data',
                        'result' => [],
                    ],
                    500
                );
            }
        } else {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
}
