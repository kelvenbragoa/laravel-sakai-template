<?php

namespace App\Service\N4;

use App\Mail\SendDeliveryNote;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendMailService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendDeliveryNoteEmail($transaction)
    {
        try {
            $deliveryNote = $this->getDeliveryNote($transaction);
            
            if ($deliveryNote && count($deliveryNote) > 0) {
                $toEmails = [];
                $ccEmails = [];

                foreach ($deliveryNote as $dn) {
                    $container_number = $dn['container_number'] ?? null;
                    $license_plate   = $dn['license_plate'] ?? null;

                    // Busca o preadvice relacionado pelo container ou pela matrícula do camião.
                    $preadvice = DB::connection('sqlsrv2')
                        ->table('cdms_commercial.preadvise')
                        ->when($container_number, function($q) use ($container_number) {
                            $q->orWhere('container_number', $container_number);
                        })
                        ->when($license_plate, function($q) use ($license_plate) {
                            $q->orWhere('truck_license_number', $license_plate);
                        })
                        ->orderBy('number', 'desc')
                        ->first();

                    $email = $preadvice->created_by ?? '';
                    // $email = 'kelven.bragoa@cornelder.co.mz';

                    if (!empty($email)) {
                        $toEmails[] = trim($email);
                    }

                    
                    $ccEmails[] = 'adelino.carneiroBute@cornelder.co.mz';
                    $ccEmails[] = 'joaoalfredo.malavo@cornelder.co.mz';
                    $ccEmails[] = 'joao.manuel@cornelder.co.mz';
                    $ccEmails[] = 'antonio.sande@cornelder.co.mz';
                    $ccEmails[] = 'antonio.joao@cornelder.co.mz';
                    $ccEmails[] = 'encarregados.gatestc_mail@cornelder.co.mz';
                    $ccEmails[] = 'gateout@cornelder.co.mz';
                    $ccEmails[] = 'kelven.bragoa@cornelder.co.mz';
                    $ccEmails[] = 'gerson.houane@cornelder.co.mz';
                    $ccEmails[] = 'ivan.goncalves@cornelder.co.mz';
                    
                }

                // Remove duplicados e normaliza arrays
                $toEmails = array_values(array_unique(array_filter($toEmails)));
                $ccEmails = array_values(array_unique(array_filter($ccEmails)));

                if (empty($toEmails)) {
                    Log::warning('Nenhum email destinatário encontrado após processamento das delivery notes.');
                    return;
                }

                Log::info('Delivery note - destinatários TO: ' . implode(', ', $toEmails));
                Log::info('Delivery note - destinatários CC: ' . implode(', ', $ccEmails));
                Log::info('Encontradas ' . count($deliveryNote) . ' delivery notes. Enviando 1 email para os destinatários únicos.');

                $createdBy = $toEmails[0] ?? '';
                try {
                    Mail::to($toEmails)
                    ->cc($ccEmails)
                    ->send(new SendDeliveryNote($transaction, $deliveryNote, $createdBy));
                } catch (\Throwable $th) {
                    Log::error('Falha ao enviar delivery note email: ' . $th->getMessage());
                }
                

                Log::info('Delivery note email enviado para: ' . implode(', ', $toEmails));

            } else {
                Log::warning("No delivery note documents found for transaction: " . $transaction);
            }
        } catch (\Exception $e) {
            Log::error("Failed to send delivery note email: " . $e->getMessage());
        }
        // try {
        //     $deliveryNote = $this->getDeliveryNote($transaction);
            
        //     if ($deliveryNote && count($deliveryNote) > 0) {
        //         $list_cc = [];
        //         Log::info("Found " . count($deliveryNote) . " documents");
        //         Log::info("First document booking number: " . ($deliveryNote[0]['bookink_number'] ?? 'N/A'));
        //         $booking_number = $deliveryNote[0]['bookink_number'];
        //         // $booking_number = 'EBKG14242073';
        //         $preadvice = DB::connection('sqlsrv2')->table('cdms_commercial.preadvise')->where('booking_number',$booking_number)->first();
                
        //         $email = 'kelven.bragoa@cornelder.co.mz';
        //         $list_cc[] ='gerson.houane@cornelder.co.mz'; 
        //         $list_cc[] ='kelven.bragoa@cornelder.co.mz'; 
        //         // $list_cc[] ='ivan.goncalves@cornelder.co.mz'; 
        //         // $email = $preadvice->created_by ?? '';
        //         Mail::to($email)->cc($list_cc)->send(new SendDeliveryNote($transaction, $deliveryNote,$preadvice->created_by ?? ''));
        //         Log::info("Delivery note email sent to " . $email);
        //     } else {
        //         Log::warning("No delivery note documents found for transaction: " . $transaction);
        //     }
        // } catch (\Exception $e) {
        //     Log::error("Failed to send delivery note email: " . $e->getMessage());
        // }
    }

    public function getDeliveryNote($transaction){
        $httpClient = Http::timeout(30);
            
            // if (!app()->environment('production')) {
                $httpClient = $httpClient->withOptions([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ]);
            // }
            
            $response = $httpClient->get("https://pgi.cornelder.co.mz/api/v1/n4/documents/getdoc/{$transaction}");
            
            Log::info("API Response Status: " . $response->status());
            Log::info("API Response Body: " . $response->body());
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Verificar se há resultados
                if (isset($data['result']) && is_array($data['result']) && !empty($data['result'])) {
                    
                    return $data['result'];
                }else{
                    return null;
                }
            }else{
                Log::error("Failed to retrieve delivery note document. Status: " . $response->status());
            }
    }
}
