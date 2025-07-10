<?php

namespace App\Services;

use App\Models\CGateV2Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExportTallyIn{
    public function tallyInForExport(Request $request)
    {
        $data = $request->all();
        $gateId = 'BEIRA-A';
        $transactionType = 'RE'; //EXPORT
        $appointmentData = $data['appointment'];
        $transactionData = $data['appointment'];

        $refNumber = '';
        $aptNumber = '';
        $tvKey = '';
        $tmpAptNumber = '';

        appointmentDate = DateFormat.n4DateFormat(),
                gateId = "BEIRA-A",
                driverCardId = transaction?.driverLicenseNumber,
                truckLicenseNbr = transaction?.truckLicensePlateNumber,
                line = appointment?.shippingLine,
                tranType = TransactionType.EXPORT,
                bookingNbr = appointment?.bookingNumber,
                containerEqid = transaction?.containerNumber1,

        try {
            $transaction = CGateV2Transaction::findOrFail($request->input('transaction_id'));
            $appointment = Appointment::find($request->input('appointment_id'));

            $refNumber = $this->generateRefNumber();

            // 1. Criar container appointment
            $aptNumber = $this->createContainerAppointment($transaction, $appointment);

            // 2. Criar truck visit appointment
            $aptNumber = $this->createTruckVisitAppointment($transaction, $appointment, $aptNumber, $refNumber);

            // 3. Atualizar container appointment
            $this->updateContainerAppointment($transaction, $appointment, $aptNumber);

            // 4. Criar truck visit e obter tvKey
            list($aptNumber, $tvKey) = $this->createTruckVisit($transaction, $appointment, $aptNumber, $refNumber);

            // 5. Gate in
            $this->gateIn($transaction, $appointment, $aptNumber, $refNumber);

            // 6. Atualizar selo
            $this->updateSeal($transaction);

            // 7. Atualizar transação local
            // $this->updateTransactionLocal($transaction, $aptNumber, $tvKey, $refNumber);

            return response()->json([
                'success' => true,
                'tv_key' => $tvKey,
                'message' => 'Tally In para exportação realizado com sucesso'
            ]);

        } catch (\Exception $e) {
            Log::error('Erro tallyInForExport: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro no processo: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateRefNumber()
    {
        return 'REF-' . now()->timestamp;
    }

    private function createContainerAppointment($transaction, $appointment)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'), // ou outro formato como 'Ymd'
            'gate_id' => 'BEIRA-A',
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'booking_nbr' => $appointment->booking_number ?? null,
            'line' => $appointment->shipping_line ?? null,
            'tran_type' => 'EXPORT',
            'container_eqid' => $transaction->container_number1 ?? null,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_container_appointment', $payload);

        if ($response->successful()) {
            return $response->json('data.data.appointmentNbr') ?? 'UNKNOWN';
        }

        // Em caso de erro
        throw new \Exception("Erro ao criar container appointment: " . $response->body());
    }

    private function createTruckVisitAppointment($transaction, $appointment, $aptNumber, $refNumber)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'), // Ou o formato que o N4 espera
            'appointment_time' => now()->format('H:i:s'),
            'gate_id' => 'BEIRA-A',
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_id' => $transaction->truck_license_plate_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'trucking_co_id' => $appointment->truck_company_id ?? null,
            'external_ref_nbr' => $refNumber,
            'appointment_nbr' => $aptNumber,
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_truck_visit_appointment', $payload);

        if ($response->successful()) {
            // Supondo que a estrutura seja: { data: { data: { appointmentNbr: 'APT-1234-TVA' } } }
            return $response->json('data.data.appointmentNbr') ?? 'UNKNOWN';
        }

        throw new \Exception("Erro ao criar truck visit appointment: " . $response->body());
    }

    private function updateContainerAppointment($transaction, $appointment, $aptNumber)
    {
        $payload = [
            'appointment_date' => now()->format('Y-m-d'), // ajuste conforme necessário
            'gate_id' => 'BEIRA-A',
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'booking_nbr' => $appointment->booking_number ?? null,
            'line' => $appointment->shipping_line ?? null,
            'tran_type' => 'EXPORT',
            'container_eqid' => $transaction->container_number1 ?? null,
            'container_type' => $appointment->container_type ?? null,
            'container_truck_position' => '1',
            'container_door_direction' => 'F',
            'container_seal_1' => $transaction->container_seal1 ?? null,
            'appointment_nbr' => $aptNumber,
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
            'gate_id' => 'BEIRA-A',
            'stage_id' => 'InGate',
            'lane_id' => '', // Se o sistema não tiver, pode ser vazio ou null
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'trucking_co_id' => $appointment->truck_company_id ?? null,
            'gos_tv_key' => $refNumber,
            'appointment_nbr' => $aptNumber,
            'timestamp' => now()->format('Y-m-d H:i:s'), // Ou o formato que N4 espera
        ];

        $response = Http::post(env('N4_API_URL') . '/n4integration/general_integration/create_truck_visit', $payload);

        if ($response->successful()) {
            $data = $response->json('data.data');
            // Exemplo de retorno: ['appointment_nbr' => ..., 'tv_key' => ...]
            return [
                $data['appointment_nbr'] ?? $aptNumber,
                $data['tv_key'] ?? ('TVKEY-' . rand(1000, 9999))
            ];
        }

        throw new \Exception("Erro ao criar truck visit: " . $response->body());
    }

    private function gateIn($transaction, $appointment, $aptNumber, $refNumber)
    {
        $payload = [
            'gate_id' => 'BEIRA-A',
            'stage_id' => 'InGate',
            'lane_id' => '', // Se não for obrigatório, pode ser vazio
            'truck_license_nbr' => $transaction->truck_license_plate_number ?? null,
            'chassis_profile_id' => '3TEU', // fixo como no código Kotlin
            'driver_card_id' => $transaction->driver_license_number ?? null,
            'truck_visit_appointment_nbr' => $aptNumber,
            'truck_visit_external_ref_nbr' => $refNumber,
            'truck_visit_gos_tv_key' => $refNumber,
            'container_eqid' => $transaction->container_number1 ?? null,
            'container_is_placarded' => 'false',
            'container_is_sealed' => 'true',
            'container_notes' => '',
            'container_truck_position' => '1',
            'container_type' => $appointment->container_type ?? null,
            'placards' => [], // lista vazia como no Kotlin
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
        if (empty($transaction->container_number1) || empty($transaction->container_seal1)) {
            throw new \Exception("Container ID ou Seal Number não informado para updateSeal");
        }

        $payload = [
            'container_id' => $transaction->container_number1,
            'seal_nbr1' => $transaction->container_seal1,
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
        $transaction->appointment_nbr = $aptNumber;
        $transaction->tv_key = $tvKey;
        $transaction->external_ref_nbr = $refNumber;
        $transaction->save();
    }


}