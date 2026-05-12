<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\GateTransaction;

use App\Http\Controllers\Controller;
use App\Models\CGateV2\CGateExcpetion;
use App\Models\CGateV2\GateTransaction;
use App\Models\CGateV2\GateTransactionHistory;
use App\Models\CGateV2ErrorLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GateTransactionController extends Controller
{
    private $gateTransaction;
    private $gateTransactionHistory;

    public function __construct(GateTransaction $gateTransaction, GateTransactionHistory $gateTransactionHistory)
    {
        $this->gateTransaction = $gateTransaction;
        $this->gateTransactionHistory = $gateTransactionHistory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $limit      = $request->limit;
        $search     = $request->search;
        $status     = $request->status;
        $gate     = $request->gate;
        $query     = $request->query;
        $searchquery     = $request->searchquery;
        $noPagination = $request->no_pagination;
        $user     = $request->user;
        $gateout     = $request->gateout;


        //all transactions
        $transactionQuery = $this->gateTransaction->when(
            $search,
            function ($query, $s) {
                // $query->whereFullText(
                //     ['driver_license_number', 'truck_license_plate_number', 'appointment_number', 'container_number_1', 'container_seal_1'],
                //     $s,
                //     ['mode' => 'boolean']
                // );
                $query->where(function ($q) use ($s) {
                    $q->where('driver_license_number', 'like', "%{$s}%")
                      ->orWhere('truck_license_plate_number', 'like', "%{$s}%")
                      ->orWhere('appointment_number', 'like', "%{$s}%")
                      ->orWhere('container_number_1', 'like', "%{$s}%")
                      ->orWhere('container_seal_1', 'like', "%{$s}%");
                });
            }
        )->when(
            $status,
            function ($query, $st) {
                $query->where('status', $st);
            }
        )->when(
            $gate,
            function ($query, $gate) {
                $query->where('transaction_gate', 'like', "%{$gate}%");
            }
        )->when(
            $gateout,
            function ($query, $gateout) {
                $query->where('transaction_gate_out', 'like', "%{$gateout}%");
            }
        )
        ->when(
            $user,
            function ($query, $user) {
                $query->where(function ($q) use ($user) {
                    $q->where('logged_user', 'like', "%{$user}%")
                    ->orWhere('logged_user_gate_out', 'like', "%{$user}%");
                });
            }
        )
        // ->when(
        //     $user,
        //     function ($query, $user) {
        //         $query->where('logged_user', 'like', "%{$user}%")->orWhere('logged_user_gate_out', 'like', "%{$user}%");
        //     }
        // )
         ->when(request('startdatetime') && request('enddatetime'), function ($query) {
                    $startDateTimeSearch = Carbon::parse(request('startdatetime'))->format('Y-m-d H:i:s');
                    $endDateTimeSearch = Carbon::parse(request('enddatetime'))->format('Y-m-d H:i:s');
        
                    $query->whereBetween('created_at', [$startDateTimeSearch, $endDateTimeSearch]);
            })
        ->orderBy('id', 'desc');
        // ->paginate($limit);

        if ($noPagination) {
            $transactions = $transactionQuery->get();
        } else {
            $transactions = $transactionQuery->paginate(50);
        }

        return response()->json(
            [
                'error' => [],
                'message' => 'success',
                'result' => $transactions,
            ],
            '200'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //save on DB
        // dd($request->all());

        $data = $request->all();
        $exists = $this->gateTransaction->where('appointment_number',$data['appointment_number'])
        // ->where('movement_type', 'Security Check In')
        ->first();

        if($exists){
             Log::info('Transacao ja existe');
            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => [$exists],
                ],
                '200'
            );
        }
        Log::info('Transacao nao existe. criando uma nova transacao...');

        $saveTransaction = $this->gateTransaction->create($data);

        if ($saveTransaction->save()) {
            $dataTransactionHistory = $data;
            $dataTransactionHistory['gate_transaction_id'] = $saveTransaction->id;
            $transactionHistory = $this->gateTransactionHistory->create($dataTransactionHistory);
            $transactionHistory->save();

            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => [$saveTransaction],
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //show specific transaction
        if ($gateTransaction = $this->gateTransaction->find($id)) {
            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => $gateTransaction,
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
        if ($gateTransaction = $this->gateTransaction->find($id)) {
            $data = $request->all();
            if ($gateTransaction->update($request->all())) {
                if (isset($data['movement_type'])) {
                    $updatedTransaction = $gateTransaction->find($id);
                    $dataTransactionHistory = $data;
                    $dataTransactionHistory['gate_transaction_id'] = $updatedTransaction->id;
                    $transactionHistory = $this->gateTransactionHistory->create($dataTransactionHistory);
                    $transactionHistory->save();
                }


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

    public function closeTransaction(Request $request, $tvkey){
        $data = $request->all();
        $transactions = $this->gateTransaction->where('tv_key',$tvkey)->get();
        foreach ($transactions as $transaction) {
            $transaction->update([
                'movement_type'=>'Tally Out',
                'transaction_gate_out'=>$data['transaction_gate_out'] ?? null,
                'logged_user_gate_out'=>$data['logged_user_gate_out'] ?? null,
            ]);
        }
        return response()->json(
                    [
                        'error' => [],
                        'message' => 'success',
                        'result' => [],
                    ],
                    200
                );
    }

    public function checkTransaction(Request $request, $tvkey){
        $data = $request->all();
        $notifyTypes = ['Export Full In', 'Empty In'];
        $user = $request->input('logged_user', 'desconhecido');


        $transactions = $this->gateTransaction->where('tv_key',$tvkey)->where('movement_type','Tally In')->get();
        $count = $transactions->count();


        if ($count > 1) {
            $matched = $transactions->filter(function ($t) use ($notifyTypes) {
                $type = $t->type ?? '';
                return in_array($type, $notifyTypes, true);
            })->values();

            if ($matched->isNotEmpty()) {
                Log::info('Enviando notificacao para admin: usuario tentou realizar a operacao '.$user);
            }
        }

        return response()->json(
                    [
                        'error' => [],
                        'message' => 'success',
                        'result' => $transactions,
                    ],
                    200
                );
    }

    public function jobTransaction(){
        $transactions = $this->gateTransaction->whereBetween('created_at', [
            Carbon::now()->subMinutes(value: 10),
            Carbon::now()
        ])->select('driver_name', 'driver_license_number','created_at')
        ->orderBy('created_at','desc')->get();

        return response()->json(
                    [
                        'error' => [],
                        'message' => 'success',
                        'result' => $transactions,
                    ],
                    200
                );
    }

    public function search(Request $request)
    {
        $status = $request->status;
        $appointment_number = $request->appointment_number;
        $container_number_1 = $request->container_number_1;
        $truck_license_plate_number = $request->truck_license_plate_number;
        $movement_type = $request->movement_type;

        if ($gateTransaction = $this->gateTransaction->query()
            ->when(request('status'), function ($query, $status) {
                $query->where('status', '=', $status);
            })->when(request('appointment_number'), function ($query, $appointment_number) {
                $query->where('appointment_number', '=', $appointment_number);
            })->when(request('container_number_1'), function ($query, $container_number_1) {
                $query->where('container_number_1', '=', $container_number_1);
            })->when(request('truck_license_plate_number'), function ($query, $truck_license_plate_number) {
                $query->where('truck_license_plate_number', 'like', "%".$truck_license_plate_number."%");
            })->when(request('movement_type'), function ($query, $movement_type) {
                $query->where('movement_type', '=', $movement_type);
            })->orderBy('updated_at', 'desc')->first()
        ) {
            if (empty($gateTransaction)) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'transaction not found',
                        'result' => [],
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => 'success',
                    'result' => $gateTransaction,
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

    public function generalSearch()
    {
    }

    public function dashboard(Request $request)
    {
        // Ano a ser filtrado, ou o atual caso não informado
        $year = $request->get('year', now()->year);
    
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
    
        // Transações por mês
        $transactions = DB::connection('cgatev2')->table('gate_transactions')
            ->select(DB::raw("MONTH(created_at) as month_number"), DB::raw("COUNT(*) as total"))
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('total', 'month_number');
    
        // Exceções aprovadas por mês
        $exceptions_cargo = DB::connection('cgatev2')->table('exceptions')
            ->select(DB::raw("MONTH(created_at) as month_number"), DB::raw("COUNT(*) as total"))
            ->whereYear('created_at', $year)
            ->where('status', 'LIKE','%cargo%')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('total', 'month_number');
        
        $exceptions_container = DB::connection('cgatev2')->table('exceptions')
            ->select(DB::raw("MONTH(created_at) as month_number"), DB::raw("COUNT(*) as total"))
            ->whereYear('created_at', $year)
            ->where('status', 'LIKE','%container%')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('total', 'month_number');
    
        // Montando os arrays finais com todos os meses
        $transactionsData = [];
        $exceptionsDataCargo = [];
        $exceptionsDataTerminal = [];

    
        foreach (range(1, 12) as $i) {
            $transactionsData[] = $transactions->get($i, 0);
            $exceptionsDataCargo[] = $exceptions_cargo->get($i, 0);
            $exceptionsDataTerminal[] = $exceptions_container->get($i, 0);

        }
    
        // Totais gerais
        $total_exception = CGateExcpetion::count();
        $total_exception_general_cargo = CGateExcpetion::where('status', 'LIKE', '%cargo%')->count();
        $total_exception_container_terminal = CGateExcpetion::where('status', 'LIKE', '%container%')->count();
    
        return response()->json([
            'error' => [],
            'message' => 'success',
            'result' => [
                'totals' => [
                    'total_exception' => $total_exception,
                    'total_exception_general_cargo' => $total_exception_general_cargo,
                    'total_exception_container_terminal' => $total_exception_container_terminal,
                ],
                'chart_transaction' => [
                    'labels' => $months,
                    'datasets' => [
                        [
                            'label' => 'Transaction',
                            'data' => $transactionsData,
                        ],
                    ],
                ],
                'chart_exception_cargo_terminal' => [
                    'labels' => $months,
                    'datasets' => [
                        [
                            'label' => 'Exception Cargo',
                            'data' => $exceptionsDataCargo,
                        ],
                        [
                            'label' => 'Exception Terminal',
                            'data' => $exceptionsDataTerminal,
                        ],
                    ],
                ],
                'year' => $year
            ]
        ]);
    }

    public function updatemanualcheck(Request $request, string $id){
        $data = $request->all();
        $gateTransaction = $this->gateTransaction->find($id);
        $gateTransaction->update([
            "manual_check"=>$data["manual_check"]
        ]);

        return response()->json([
            "data"=>$gateTransaction
        ]);
    }

    public function dashboarduser(Request $request)
{
    $gateQuery = $request->get('gate');
    $dateQuery = $request->get('date');
    $shiftQuery = $request->get('shift');
    $userQuery = $request->get('user');

    // Processar shift e date para gerar start_date e end_date
    if ($shiftQuery == 1) {
        $startDate = Carbon::parse($dateQuery . ' 07:00:00');
        $endDate = Carbon::parse($dateQuery . ' 19:00:00');
    } elseif ($shiftQuery == 2) {
        $startDate = Carbon::parse($dateQuery . ' 19:00:00');
        $endDate = Carbon::parse($dateQuery)->addDay()->setTime(7, 0, 0); // dia seguinte às 07:00
    } else {
        // Caso não tenha shift, usar os parâmetros start_date e end_date diretamente (retrocompatibilidade)
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
    }

    // Base da query para reutilizar filtros
    $baseQuery = DB::connection('cgatev2')->table('gate_transactions');
    //->where('movement_type', 'Tally In');
    $exceptionBaseQuery = DB::connection('cgatev2')->table('exceptions')
        ->join('gate_transactions', 'exceptions.transaction_id', '=', 'gate_transactions.id')
        ->select('exceptions.logged_user', DB::raw('COUNT(*) as total'))
        ->groupBy('exceptions.logged_user');

    if ($startDate && $endDate) {
        $baseQuery->whereBetween('created_at', [$startDate, $endDate]);
        $exceptionBaseQuery->whereBetween('exceptions.created_at', [$startDate, $endDate]);
    }

    if ($gateQuery) {
        $baseQuery->where('transaction_gate', 'like', "%{$gateQuery}%");
        $exceptionBaseQuery->where('gate_transactions.transaction_gate', 'like', "%{$gateQuery}%");
    }

    if ($userQuery) {
        $baseQuery->where('logged_user', $userQuery);
        $exceptionBaseQuery->where('exceptions.logged_user', $userQuery);
    }

    // Query para gráfico por usuário
    $transactionsByUser = (clone $baseQuery)
        ->select('logged_user', DB::raw('COUNT(*) as total'))
        ->groupBy('logged_user')
        ->get();
    
    $transactionsByType = (clone $baseQuery)
        ->select('type', DB::raw('COUNT(*) as total'))
        ->groupBy('type')
        ->get();

    $exceptionsByUser = $exceptionBaseQuery->get();

    // Total de transações (com os mesmos filtros)
    $totalTransactions = (clone $baseQuery)->count();

    $totalExceptions = DB::connection('cgatev2')->table('exceptions')
        ->join('gate_transactions', 'exceptions.transaction_id', '=', 'gate_transactions.id')
        ->when($startDate && $endDate, fn($q) => $q->whereBetween('exceptions.created_at', [$startDate, $endDate]))
        ->when($gateQuery, fn($q) => $q->where('gate_transactions.transaction_gate', 'like', "%{$gateQuery}%"))
        ->when($userQuery, fn($q) => $q->where('exceptions.logged_user', $userQuery))
        ->count();

    // Total de usuários distintos
    $totalUsers = (clone $baseQuery)
        ->select('logged_user')
        ->distinct()
        ->count('logged_user');

    $totalUsersWithExceptions = DB::connection('cgatev2')->table('exceptions')
        ->join('gate_transactions', 'exceptions.transaction_id', '=', 'gate_transactions.id')
        ->when($startDate && $endDate, fn($q) => $q->whereBetween('exceptions.created_at', [$startDate, $endDate]))
        ->when($gateQuery, fn($q) => $q->where('gate_transactions.transaction_gate', 'like', "%{$gateQuery}%"))
        ->when($userQuery, fn($q) => $q->where('exceptions.logged_user', $userQuery))
        ->select('exceptions.logged_user')
        ->distinct()
        ->count('exceptions.logged_user');

    // Preparar dados do gráfico
    $labels = [];
    $data = [];

    $exceptionLabels = [];
    $exceptionData = [];

    foreach ($transactionsByUser as $row) {
        $username = Str::upper(Str::before($row->logged_user ?? 'sem_nome', '@'));
        $labels[] = $username;
        $data[] = $row->total;
    }

    foreach ($exceptionsByUser as $row) {
        $username = Str::upper(Str::before($row->logged_user ?? 'sem_nome', '@'));
        $exceptionLabels[] = $username;
        $exceptionData[] = $row->total;
    }


     $logChartData = CGateV2ErrorLogs::query()
            ->select('error_message', DB::raw('COUNT(*) as total'))
            ->when($userQuery, fn($query) => $query->where('logged_user', $userQuery))
            ->whereBetween('created_at', [$startDate, $endDate])
            // ->where('gate_id', $gateQuery)
            ->groupBy('error_message')
            ->orderByDesc('total')
            ->get();

        $logLabels = [];
        $logValues = [];

        foreach ($logChartData as $log) {
            $logLabels[] = $log->error_message ?? 'Sem erro';
            $logValues[] = $log->total;
        }

    return response()->json([
        'error' => [],
        'message' => 'success',
        'result' => [
            'totals' => [
                'total_transactions' => $totalTransactions,
                'total_users' => $totalUsers,
            ],
            'chart_transaction_by_user' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Transações por Usuário',
                        'data' => $data,
                    ],
                ],
            ],
            'exception_summary' => [
                'total_exceptions' => $totalExceptions,
                'total_users' => $totalUsersWithExceptions,
            ],
            'chart_exceptions_by_user' => [
                'labels' => $exceptionLabels,
                'datasets' => [
                    [
                        'label' => 'Exceções Submetidas por Usuário',
                        'data' => $exceptionData,
                    ],
                ],
            ],
            'types' => $transactionsByType,

            'chart_validation_log' => [
                'labels' => $logLabels,
                'datasets' => [
                    [
                        'label' => 'Ocorrências por Tipo de Erro',
                        'data' => $logValues,
                    ]
                ]
        ]
    ]]);
}
}
