<?php

namespace App\Services;

use App\Models\CGateV2Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExportTallyInService{

    private $gateId = 'BEIRA-A';
    private $transactionType = 'RE';
    public function tallyInForExport(array $data)
    {
        
        // $data = $request->all();
        // $gateId = 'BEIRA-A';
        // $transactionType = 'RE'; //EXPORT
        $appointmentData  = (object) $data['appointment'];
        $transactionData  = (object) $data['transaction'];

        $refNumber = '';
        $aptNumber = '';
        $tvKey = '';
        $tmpAptNumber = '';



        try {
            $transaction = $transactionData;
            $appointment = $appointmentData;

            $refNumber = $this->generateRefNumber();

            // 1. Criar container appointment
            $aptNumber = $this->createContainerAppointment($transaction, $appointment);
            $tmpAptNumber = $this->createContainerAppointment($transaction, $appointment);

            // 2. Criar truck visit appointment
            $aptNumber = $this->createTruckVisitAppointment($transaction, $appointment, $aptNumber, $refNumber);

            // 3. Atualizar container appointment
            $this->updateContainerAppointment($transaction, $appointment, $tmpAptNumber);

            // 4. Criar truck visit e obter tvKey
            list($aptNumber, $tvKey) = $this->createTruckVisit($transaction, $appointment, $aptNumber, $refNumber);

            // 5. Gate in
            $this->gateIn($transaction, $appointment, $aptNumber, $refNumber);

            // 6. Atualizar selo
            $this->updateSeal($transaction);

            // 7. Atualizar transação local
            $this->updateTransaction($transaction, $aptNumber, $tvKey, $refNumber);

            return response()->json([
                'success' => true,
                'tv_key' => $tvKey,
                'message' => 'Tally In para Export Full In realizado com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro Tally In Export Full In: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro Tally In Export Full In: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateRefNumber(): string
    {
        $chars = '0123456789';
        $ref = '';

        for ($i = 0; $i < 8; $i++) {
            $ref .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $ref;
    }

    private function createContainerAppointment($transaction, $appointment)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'),
            'gate_id' => $this->gateId,
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'booking_nbr' => $appointment->booking_number ?? null,
            'line' => $appointment->shipping_line ?? null,
            'tran_type' => $this->transactionType,
            'container_eqid' => $transaction->container_number_1 ?? null,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_container_appointment', $payload);

        if ($response->successful()) {
            return $response->json('result.appointmentNbr') ?? 'UNKNOWN';
        }

        // Em caso de erro
        throw new \Exception("Erro ao criar container appointment: " . $response->body());
    }

    private function createTruckVisitAppointment($transaction, $appointment, $aptNumber, $refNumber)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'), // Ou o formato que o N4 espera
            'appointment_time' => now()->format('H:i:s'),
            'gate_id' => $this->gateId,
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_id' => $transaction->truck_license_plate_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'trucking_co_id' => $appointment->truck_company_id ?? null,
            'external_ref_nbr' => $refNumber,
            'appointment_nbr' => $aptNumber,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_truck_visit_appointment', $payload);

        if ($response->successful()) {
            return $response->json('result.appointmentNbr') ?? 'UNKNOWN';
        }

        throw new \Exception("Erro ao criar truck visit appointment: " . $response->body());
    }

    private function updateContainerAppointment($transaction, $appointment, $tempAptNumber)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'), // ajuste conforme necessário
            'gate_id' => $this->gateId,
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'booking_nbr' => $appointment->booking_number ?? null,
            'line' => $appointment->shipping_line ?? null,
            'tran_type' => $this->transactionType,
            'container_eqid' => $transaction->container_number_1 ?? null,
            'container_type' => $appointment->container_type ?? null,
            'container_truck_position' => '1',
            'container_door_direction' => 'F',
            'container_seal_1' => $transaction->container_seal_1 ?? null,
            'appointment_nbr' => $tempAptNumber,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/update_container_appointment', $payload);

        if ($response->successful()) {
            Log::info('UpdateContainerAppointment resposta', $response->json());
            return true;
        }

        throw new \Exception("Erro ao atualizar container appointment: " . $response->body());
    }

    private function createTruckVisit($transaction, $appointment, $aptNumber, $refNumber)
    {
        $payload = [
            'gate_id' => $this->gateId,
            'stage_id' => 'InGate',
            'lane_id' => '',
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'trucking_co_id' => $appointment->truck_company_id ?? null,
            'gos_tv_key' => $refNumber,
            'appointment_nbr' => $aptNumber,
            'timestamp' => now()->format('Y-m-d H:i:s'),
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_truck_visit', $payload);

        if ($response->successful()) {
            $data = $response->json('result');
            return [
                $data['appointment_nbr'],
                $data['tv_key']
            ];
        }

        throw new \Exception("Erro ao criar truck visit: " . $response->body());
    }

    private function gateIn($transaction, $appointment, $aptNumber, $refNumber)
    {
        $payload = [
            'gate_id' => $this->gateId,
            'stage_id' => 'InGate',
            'lane_id' => '',
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'chassis_profile_id' => '3TEU',
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_visit_appointment_nbr' => $aptNumber,
            'truck_visit_external_ref_nbr' => $refNumber,
            'truck_visit_gos_tv_key' => $refNumber,
            'container_eqid' => $transaction->container_number_1 ?? null,
            'container_is_placarded' => 'false',
            'container_is_sealed' => 'true',
            'container_notes' => '',
            'container_truck_position' => '1',
            'container_type' => $appointment->container_type ?? null,
            'placards' => [],
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/export_full_in/gate_in', $payload);

        if ($response->successful()) {
            Log::info('Gate In realizado com sucesso');
            return true;
        }

        throw new \Exception("Erro ao realizar gate in: " . $response->body());
    }

    private function updateSeal($transaction)
    {
        if (empty($transaction->container_number_1) || empty($transaction->container_seal_1)) {
            throw new \Exception("Container ID ou Seal Number não informado para updateSeal");
        }

        $payload = [
            'container_id' => $transaction->container_number_1,
            'seal_nbr1' => $transaction->container_seal_1,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/update_seal', $payload);

        if ($response->successful()) {
            Log::info('Seal atualizado com sucesso');
            return true;
        }

        throw new \Exception("Erro ao atualizar seal: " . $response->body());
    }

    private function updateTransaction($transaction, $aptNumber, $tvKey, $refNumber)
    {
        $id = $transaction->id ?? null;
        $payload = [
                'appointment_nbr'       => $aptNumber,
                'tv_key'                => $tvKey,
                'external_ref_nbr'       => $refNumber,
            ];
        if (!$id) {
            throw new \Exception("ID da transação não informado.");
        }

        $response = Http::post(env('BASE_URL') . "/transacoes/actualizar/{$id}", $payload);
        if (!$response->successful()) {
                throw new \Exception("Erro ao atualizar transação: " . $response->body());
            }

            Log::info("Transação {$id} atualizada com sucesso");
            return true;
        }
}