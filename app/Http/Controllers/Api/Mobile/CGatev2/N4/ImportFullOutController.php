<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use SoapClient;
use Exception;
use SimpleXMLElement;
use App\Service\N4\SendMailService;


class ImportFullOutController extends Controller
{
    use HttpResponses;

    //9.2 Pick Up Import (DI) Deliver Import Transaction

    protected $url, $user, $pass;
    protected SendMailService $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        $this->url = config('app.navis_api_url');
        $this->user = config('app.navis_api_username');
        $this->pass = config('app.name_api_password');
        $this->sendMailService = $sendMailService;
    }
    //convert xml to JSON
    function convert_xml_to_json($xmlString)
    {
        try {
            // Carregar o XML
            $xmlObject = new SimpleXMLElement($xmlString, LIBXML_NOCDATA);
            // Converter para array
            $arrayData = json_decode(json_encode($xmlObject), true);
            // Converter o array para JSON
            $json = json_encode($arrayData);
            return $json;
        } catch (Exception $e) {
            // Lidar com possíveis exceções
            return response()->json(['error' => 'Invalid XML'], 400);
        }
    }
    //gate in
    public function gate_in(Request $request)
    {
        try {
            //API WSDL URL
            $url      = "https://cap.cornelder.co.mz/apex/services/argobasicservice?wsdl";
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
           // Validação dos dados recebidos
            $validatedData = $request->validate([
                'scan_status'            => 'nullable|string',
                'do_stage_done'          => 'nullable|string',
                'no_content'             => 'nullable|string',
                'gate_id'                => 'nullable|string',
                'stage_id'               => 'nullable|string',
                'lane_id'                => 'nullable|string',
                'truck.license_nbr'      => 'nullable|string',
                'chassis_profile.id'     => 'nullable|string',
                'truck_visit.gos_tv_key' => 'nullable|string',
                'truck_visit.bat_nbr'    => 'nullable|string',
                'timestamp'              => 'nullable|string',
            ]);

            // Criação do XML
            $xml = new \SimpleXMLElement('<gate/>');

            // Criando o nó principal "process-truck"
            $processTruck = $xml->addChild('process-truck');

            // Adicionando atributos ao nó "process-truck"
            $processTruck->addAttribute('scan-status', $validatedData['scan_status']);
            $processTruck->addAttribute('do-stage-done', $validatedData['do_stage_done'] ? 'true' : 'false');
            $processTruck->addAttribute('no-content', $validatedData['no_content'] ? 'true' : 'false');

            // Adicionando elementos filhos
            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);
            $processTruck->addChild('lane-id', $validatedData['lane_id']);

            // Criando o nó "truck" com atributos
            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck']['license_nbr']);

            // Criando o nó "chassis-profile" com atributo
            $chassisProfile = $processTruck->addChild('chassis-profile');
            $chassisProfile->addAttribute('id', $validatedData['chassis_profile']['id']);

            // Criando o nó "truck-visit" com atributos
            $truckVisit = $processTruck->addChild('truck-visit');
            $truckVisit->addAttribute('gos-tv-key', $validatedData['truck_visit']['gos_tv_key']);
            $truckVisit->addAttribute('bat-nbr', $validatedData['truck_visit']['bat_nbr']);

            // Adicionando o timestamp
            $processTruck->addChild('timestamp', $validatedData['timestamp']);

            //N4 XML data - to create appointment
            $xmlDoc   = $xml->asXML();

            //Attach to invoke and create appointment
            $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client       = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            //Webservice send data
            $wsParam      = $client->basicInvoke($basicInvoke);
            //Result to get information from navis
            $result_xml       = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
            //tratar os estados
            $status = $result["@attributes"]["status"];
            $statusId = (string) $result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($result['messages']['message']) > 1) {
                    foreach ($result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        $errorMessages[] = [
                            'message_id' => $attributes['message-id'],
                            'message_severity' => $attributes['message-severity'],
                            'message_text' => $attributes['message-text'],
                        ];
                    }
                } else {
                    $errorMessages = $result['messages']['message']['@attributes'];
                }
                return response()->json(
                    [
                        'error' => [
                            $errorMessages
                        ],
                        'message' => 'error',
                        'result' => [],
                    ],
                    400
                );
            }
            // Carregar o XML em um objeto SimpleXMLElement
            $xml = new SimpleXMLElement($result_xml);
            $truckVisit = $xml->xpath('//truck-visit');
            if (!empty($truckVisit)) {
                $truckVisit = $truckVisit[0]; // Seleciona o primeiro truck-visit
                $tvKey = (string)$truckVisit['tv-key'];
                $gosTvKey = (string)$truckVisit['gos-tv-key'];
                $appointmentNbr = (string)$truckVisit['appointment-nbr'];
                $nextStageId = (string)$truckVisit['next-stage-id'];
                $statusVisit = (string)$truckVisit['status'];
                $gateId = (string)$truckVisit['gate-id'];
                $entered = (string)$truckVisit['entered'];
            } else {
                // Define valores padrão se truck-visit não for encontrado
                $tvKey = $gosTvKey = $appointmentNbr = $nextStageId = $statusVisit = $gateId = $entered = 'N/A';
            }

            // Extrair atributos do truck-transaction
            $truckTransaction = $xml->xpath('//truck-transaction');
            if (!empty($truckTransaction)) {
                $truckTransaction = $truckTransaction[0]; // Seleciona o primeiro truck-transaction
                $tranKey = (string)$truckTransaction['tran-key'];
                $tranNbr = (string)$truckTransaction['tran-nbr'];
                $tvKeyTran = (string)$truckTransaction['tv-key'];
                $seqNbr = (string)$truckTransaction['seq-nbr'];
                $tranType = (string)$truckTransaction['tran-type'];
                $category = (string)$truckTransaction['category'];
                $freightKind = (string)$truckTransaction['freight-kind'];
                $mission = (string)$truckTransaction['mission'];
                $blockId = (string)$truckTransaction['block-id'];
                $nextStageIdTran = (string)$truckTransaction['next-stage-id'];
                $gateIdTran = (string)$truckTransaction['gate-id'];
                $statusTran = (string)$truckTransaction['status'];
                $notes = (string)$truckTransaction['notes'];
                $isHazard = (string)$truckTransaction['is-hazard'];
            } else {
                // Define valores padrão se truck-transaction não for encontrado
                $tranKey = $tranNbr = $tvKeyTran = $seqNbr = $tranType = $category = $freightKind = $mission = $blockId = $nextStageIdTran = $gateIdTran = $statusTran = $notes = $isHazard = 'N/A';
            }

            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result["@attributes"]
                    ],
                    'result' => [
                        'tv_key'                => $tvKey,
                        'gos_tv_key'            => $gosTvKey,
                        'appointment_number'    => $appointmentNbr,
                        'next_stage_id'         => $nextStageId,
                        'status_visit_id'       => $statusVisit,
                        'gate_id'               => $gateId,
                        'entered'               => $entered,
                        'transaction_key'       => $tranKey,
                        'transaction_number'    => $tranNbr,
                        'tv_key_transaction'    => $tvKeyTran,
                        'sequence_number'       => $seqNbr,
                        'transaction_type'      => $tranType,
                        'category'              => $category,
                        'freight_kind'          => $freightKind,
                        'mission'               => $mission,
                        'block_id'              => $blockId,
                        'next_stage_id_transac' => $nextStageIdTran,
                        'gate_id_tran'          => $gateIdTran,
                        'status_tran'           => $statusTran,
                        'notes'                 => $notes,
                        'is_hazard'             => $isHazard,
                        'xml_response' => $result_xml

                    ],
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => [
                        $e->getMessage()
                    ],
                    'message' => 'transaction not created',
                    'result' => [],
                ],
                404
            );
        }
    }
    //submit transaction
    public function submit_transaction(Request $request)
    {
        try {
            //API WSDL URL
            $url = "https://cap.cornelder.co.mz/apex/services/argobasicservice?wsdl";
            $user = $this->user;
            $pass = $this->pass;

            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";

            $validatedData = $request->validate([
                'gate_id' => 'nullable|string',
                'stage_id' => 'nullable|string',
                'lane_id' => 'nullable|string', // Pode ser vazio
                'truck_visit_tv_key' => 'nullable|string',
                'truck_transaction_appointment_nbr' => 'nullable|string',
                'truck_transaction_category' => 'nullable|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $submitTransaction = $xml->addChild('submit-transaction');

            $submitTransaction->addChild('gate-id', $validatedData['gate_id']);
            $submitTransaction->addChild('stage-id', $validatedData['stage_id']);
            $submitTransaction->addChild('lane-id', $validatedData['lane_id'] ?? ''); // Lane ID pode ser vazio

            $truckVisit = $submitTransaction->addChild('truck-visit');
            $truckVisit->addAttribute('tv-key', $validatedData['truck_visit_tv_key']);

            $truckTransaction = $submitTransaction->addChild('truck-transaction');
            $truckTransaction->addAttribute('appointment-nbr', $validatedData['truck_transaction_appointment_nbr']);
            $truckTransaction->addAttribute('category', $validatedData['truck_transaction_category']);

            $xmlDoc = $xml->asXML();

            //Attach to invoke and create appointment
            $basicInvoke = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            //Webservice send data
            $wsParam = $client->basicInvoke($basicInvoke);
            //Result to get information from navis
            $result_xml = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);

            //tratar os estados
            $status = $result["@attributes"]["status"];
            $statusId = (string)$result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($result['messages']['message']) > 1) {
                    foreach ($result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        $errorMessages[] = ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail']];
                    }
                } else {
                    $errorMessages = $result['messages']['message']['@attributes'];
                }
                return response()->json(
                    [
                        'error' => [
                            $errorMessages
                        ],
                        'message' => 'error',
                        'result' => [],
                        'xml_response' => $result_xml,
                    ],
                    400
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => [],
                    'result' => [
                        $result,
                        'xml_response' => $result_xml,
                    ],

                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => [
                        $e->getMessage()
                    ],
                    'message' => 'transaction not saved',
                    'result' => [],
                ],
                404
            );
        }
    }
    //stage done
    public function stage_done(Request $request)
    {
        try {
            //API WSDL URL
            $url = "https://cap.cornelder.co.mz/apex/services/argobasicservice?wsdl";
            $user = $this->user;
            $pass = $this->pass;

            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'gate_id' => 'nullable|string',
                'stage_id' => 'nullable|string',
                'truck_visit_tv_key' => 'nullable|string',
                'lane_id' => 'nullable|string', // lane_id pode ser opcional
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $stageDone = $xml->addChild('stage-done');
            $stageDone->addAttribute('no-content', 'true');

            $stageDone->addChild('gate-id', $validatedData['gate_id']);
            $stageDone->addChild('stage-id', $validatedData['stage_id']);

            // Se o lane_id foi fornecido, adicionar ao XML
            if (isset($validatedData['lane_id']) && !empty($validatedData['lane_id'])) {
                $stageDone->addChild('lane-id', $validatedData['lane_id']);
            } else {
                $stageDone->addChild('lane-id', ''); // Caso o lane_id esteja vazio
            }

            // Adiciona a visita do caminhão
            $truckVisit = $stageDone->addChild('truck-visit');
            $truckVisit->addAttribute('tv-key', $validatedData['truck_visit_tv_key']);
            //N4 XML data - to create appointment
            $xmlDoc = $xml->asXML();

            //Attach to invoke and create appointment
            $basicInvoke = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            //Webservice send data
            $wsParam = $client->basicInvoke($basicInvoke);
            //Result to get information from navis
            $result_xml = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);

            //tratar os estados
            $status = $result["@attributes"]["status"];
            $statusId = (string)$result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($result['messages']['message']) > 1) {
                    foreach ($result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        $errorMessages[] = ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail']];
                    }
                } else {
                    $errorMessages = $result['messages']['message']['@attributes'];
                }
                return response()->json(
                    [
                        'error' => [
                            $errorMessages
                        ],
                        'message' => 'error',
                        'result' => [],
                        'xml_response' => $result_xml,
                    ],
                    400
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => [],
                    'result' => [
                        $result,
                        'xml_response' => $result_xml,
                    ],

                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => [
                        $e->getMessage()
                    ],
                    'message' => 'transaction not saved',
                    'result' => [],
                ],
                404
            );
        }
    }
    //gate out
    public function gate_out(Request $request)
    {
        try {
            //API WSDL URL
            $url      = "https://cap.cornelder.co.mz/apex/services/argobasicservice?wsdl";
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            //variables from REQUEST
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'gate_id' => 'nullable|string',
                'stage_id' => 'nullable|string',
                'truck_license_nbr' => 'nullable|string',
                'driver_card_id' => 'nullable|string',
                'truck_visit_gos_tv_key' => 'nullable|string',
                'container_eqid' => 'nullable|string',
                'timestamp' => 'nullable|date_format:Y-m-d\TH:i:s',
                'scan_status' => 'nullable|string',
                'truck_visit_tv_key' => 'nullable|string',

            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $processTruck = $xml->addChild('process-truck');
            $processTruck->addAttribute('scan-status', 0);

            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);

            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', trim($validatedData['truck_license_nbr']));

            $driver = $processTruck->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truckVisit = $processTruck->addChild('truck-visit');
            $truckVisit->addAttribute('gos-tv-key', $validatedData['truck_visit_gos_tv_key']);

            $equipment = $processTruck->addChild('equipment');
            $container = $equipment->addChild('container');
            $container->addAttribute('eqid', $validatedData['container_eqid']);

            $processTruck->addChild('timestamp', $validatedData['timestamp']);

            //N4 XML data - to create appointment
            $xmlDoc   = $xml->asXML();
            // dd($xmlDoc);

            //Attach to invoke and create appointment
            $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client       = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            //Webservice send data
            $wsParam      = $client->basicInvoke($basicInvoke);
            //Result to get information from navis
            $result_xml       = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
            //tratar os estados
            $status = $result["@attributes"]["status"];
            $statusId = (string) $result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($result['messages']['message']) > 1) {
                    foreach ($result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        $errorMessages[] = ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'],];
                    }
                } else {
                    $errorMessages = $result['messages']['message']['@attributes'];
                }
                return response()->json(
                    [
                        'error' => [
                            $errorMessages
                        ],
                        'message' => 'error',
                        'result' => [],
                        'xml_response' => $result_xml
                    ],
                    400
                );
            }

            // $this->sendMailService->sendDeliveryNoteEmail($validatedData['truck_visit_tv_key']);

            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result["@attributes"]
                    ],
                    'result' => [
                        $result,
                        'xml_response' => $result_xml
                    ],
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'error' => [
                        $e->getMessage()
                    ],
                    'message' => 'transaction not saved',
                    'result' => [],
                ],
                404
            );
        }
    }
}
