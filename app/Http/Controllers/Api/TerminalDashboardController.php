<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CGateV2ErrorLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        

        $validationLog = CGateV2ErrorLogs::
        when($request->input('user'), fn($query) =>
            $query->where('logged_user', $request->input('user'))
        )
        ->whereBetween('created_at', [$date1Parameters, $date2Parameters])
            // ->where('gate_id', $gateQuery)
            ->orderBy('created_at', 'desc')
            ->get();

        

        $queryParams = $request->query();

        

        // Adiciona os dois parâmetros desejados
        $queryParams['start_date'] = $date1Parameters->toDateTimeString();
        $queryParams['end_date'] = $date2Parameters->toDateTimeString();

        
        $response = Http::get('http://20.87.9.35/api/v1/transacoes/dashboarduser',$queryParams);
// dd($response);
        return response()->json($response->json(), $response->status());
    }
}
