<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CGateV2ErrorLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TerminalDashboardController extends Controller
{
    //
    public function dashboard(Request $request)
    {
        $gateQuery = request('gate');
        $dateQuery = request('date');
        $shiftQuery = request('shift');
        $userQuery = request('user');


        if ($shiftQuery == 1) {
            $date1Parameters = Carbon::parse($dateQuery . ' 07:00:00');
            $date2Parameters = Carbon::parse($dateQuery . ' 19:00:00');
        } elseif ($shiftQuery == 2) {
            $date1Parameters = Carbon::parse($dateQuery . ' 19:00:00');
            $date2Parameters = Carbon::parse($dateQuery)->addDay()->setTime(7, 0, 0); // dia seguinte às 07:00
        }


        $logChartData = CGateV2ErrorLogs::query()
            ->select('error_message', DB::raw('COUNT(*) as total'))
            ->when($userQuery, fn($query) => $query->where('logged_user', $userQuery))
            ->whereBetween('created_at', [$date1Parameters, $date2Parameters])
            // ->where('gate_id', $gateQuery)
            ->groupBy('error_message')
            ->orderByDesc('total')
            ->get();

        $logLabels = [];
        $logValues = [];

        foreach ($logChartData as $log) {
            $logLabels[] = $log->error ?? 'Sem erro';
            $logValues[] = $log->total;
        }

        $queryParams = $request->query();

        $queryParams['start_date'] = $date1Parameters->toDateTimeString();
        $queryParams['end_date'] = $date2Parameters->toDateTimeString();
        $queryParams['user'] = $userQuery;
        $queryParams['gate'] = $gateQuery;

        $response = Http::get('http://20.87.9.35/api/v1/transacoes/dashboarduser', $queryParams);

        $responseData = $response->json();

        // Adiciona validationLog ao resultado da resposta externa
        if (isset($responseData['result'])) {
            $responseData['result']['chart_validation_log'] = [
                'labels' => $logLabels,
                'datasets' => [
                    [
                        'label' => 'Ocorrências por Tipo de Erro',
                        'data' => $logValues,
                    ]
                ]
            ];
        }

        return response()->json($responseData, $response->status());
        // return response()->json($response->json(), $response->status());
    }
}
