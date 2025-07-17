<?php

namespace Tests\Feature;

use App\Services\ExportTallyInService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExportTallyInServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    public function test_tally_in_for_export_flow_with_fake_http()
    {
        // Fakes para todos os endpoints usados no serviço
        Http::fake([
            // 1. create_container_appointment
            '*' => function ($request) {
                $url = $request->url();

                if (str_contains($url, 'create_container_appointment')) {
                    return Http::response([
                        'result' => ['appointmentNbr' => 'APT123']
                    ], 200);
                }

                if (str_contains($url, 'create_truck_visit_appointment')) {
                    return Http::response([
                        'result' => ['appointmentNbr' => 'APT456']
                    ], 200);
                }

                if (str_contains($url, 'update_container_appointment')) {
                    return Http::response(['status' => 'ok'], 200);
                }

                if (str_contains($url, 'create_truck_visit')) {
                    return Http::response([
                        'result' => [
                            'appointment_nbr' => 'APT789',
                            'tv_key' => 'TVKEY999'
                        ]
                    ], 200);
                }

                if (str_contains($url, 'gate_in')) {
                    return Http::response(['status' => 'ok'], 200);
                }

                if (str_contains($url, 'update_seal')) {
                    return Http::response(['status' => 'ok'], 200);
                }

                if (str_contains($url, 'transacoes/actualizar')) {
                    return Http::response(['status' => 'updated'], 200);
                }

                return Http::response([], 404);
            },
        ]);

        // Dados simulados
        $data = [
            'appointment' => [
                'booking_number' => 'BOOK123',
                'shipping_line' => 'MSC',
                'truck_company_id' => 'TRUCK_CO_001',
                'container_type' => '20GP'
            ],
            'transaction' => [
                'id' => 1,
                'driver_license_number' => 'DL999999',
                'truck_license_plate_number' => 'AB-123-CD',
                'container_number_1' => 'CONT1234567',
                'container_seal_1' => 'SEAL456'
            ]
        ];

        // Cria instância do serviço
        $service = new ExportTallyInService();

        // Executa o método
        $response = $service->tallyInForExport($data);
        $responseData = $response->getData(true);

        // Verificações
        $this->assertTrue($responseData['success']);
        $this->assertEquals('TVKEY999', $responseData['tv_key']);
        $this->assertEquals('Tally In para Export Full In realizado com sucesso', $responseData['message']);
    }
}
