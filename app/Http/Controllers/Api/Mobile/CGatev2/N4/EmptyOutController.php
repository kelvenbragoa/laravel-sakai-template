<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use SoapClient;
use Exception;
use SimpleXMLElement;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;
use App\Service\N4\SendMailService;
use Illuminate\Support\Facades\DB;

class EmptyOutController extends Controller
{
    use HttpResponses;

    //9.4 Pick Up Empty (DM) Deliver Empty Transaction

    protected $url, $user, $pass;
    protected SendMailService $sendMailService;


    public function __construct(SendMailService $sendMailService)
    {
        $this->url = config('app.navis_api_url');
        $this->user = config('app.navis_api_username');
        $this->pass = config('app.name_api_password');
        $this->sendMailService = $sendMailService;
    }
    private static function conexao()
    {
        return DB::connection('n4db')->getPdo();
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
            $url      = $this->url;
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'gate_id' => 'nullable|string',
                'stage_id' => 'nullable|string',
                'lane_id' => 'nullable|string',
                'truck_license_nbr' => 'nullable|string',
                'chassis_profile_id' => 'nullable|string',
                'driver_card_id' => 'nullable|string',
                'truck_visit_appointment_nbr' => 'nullable|string',
                'truck_visit_external_ref_nbr' => 'nullable|string',
                'truck_visit_gos_tv_key' => 'nullable|string',
                'truck_visit_tv_key' => 'nullable|string',
                'container_eqid' => 'nullable|string',
                'container_type' => 'nullable|string',
                'container_notes' => 'nullable|string',
                'truck_position' => 'nullable|string',
                'is_placarded' => 'nullable|string',
                'is_sealed' => 'nullable|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $processTruck = $xml->addChild('process-truck');
            $processTruck->addAttribute('do-stage-done', 'false');
            $processTruck->addAttribute('no-content', 'true');

            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);
            $processTruck->addChild('lane-id', $validatedData['lane_id']);

            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', trim($validatedData['truck_license_nbr']));

            $processTruck->addChild('chassis-profile')->addAttribute('id', $validatedData['chassis_profile_id']);

            $driver = $processTruck->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truckVisit = $processTruck->addChild('truck-visit');
            $truckVisit->addAttribute('appointment-nbr', $validatedData['truck_visit_appointment_nbr']);
            $truckVisit->addAttribute('external-ref-nbr', $validatedData['truck_visit_external_ref_nbr']);
            $truckVisit->addAttribute('gos-tv-key', $validatedData['truck_visit_gos_tv_key']);
            $truckVisit->addAttribute('tv-key', $validatedData['truck_visit_tv_key']);

            $equipment = $processTruck->addChild('equipment');
            $container = $equipment->addChild('container');
            $container->addAttribute('eqid', $validatedData['container_eqid']);
            $container->addAttribute('type', $validatedData['container_type']);
            $container->addAttribute('notes', $validatedData['container_notes']);
            $container->addAttribute('truck-position', $validatedData['truck_position']);
            $container->addAttribute('is-placarded', $validatedData['is_placarded'] ? 'true' : 'false');
            $container->addAttribute('is-sealed', $validatedData['is_sealed'] ? 'true' : 'false');

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
            $xml_result       = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($xml_result);
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
                        'xml_response' => $xml_result,
                    ],
                    400
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes'],
                    ],
                    'result' => [
                        $result,
                        'xml_response' => $xml_result
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
    //submit transaction
    public function submit_transaction(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
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
            // dd($xmlDoc);
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
            $url = $this->url;
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
            // dd($xmlDoc);

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
            
            ignore_user_abort(true);
            // time limit de execucao do metodo
            set_time_limit(0);
            
            $container1 = true;
            $container2 = true;
            $container3 = true;

            //API WSDL URL
            $url      = $this->url;
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'gate_id' => 'nullable|string',
                'stage_id' => 'nullable|string',
                'lane_id' => 'nullable|string',
                'truck_license_nbr' => 'nullable|string',
                'chassis_profile_id' => 'nullable|string',
                'driver_card_id' => 'nullable|string',
                'truck_visit_appointment_nbr' => 'nullable|string',
                'truck_visit_external_ref_nbr' => 'nullable|string',
                'truck_visit_gos_tv_key' => 'nullable|string',
                'truck_visit_tv_key' => 'nullable|string',
                
                'container_notes' => 'nullable|string',
                'truck_position' => 'nullable|string',
                'is_placarded' => 'nullable|string',
                'is_sealed' => 'nullable|string',
                
                'container_eqid_1' => 'nullable|string',
                'container_type_1' => 'nullable|string',
                'container_eqid_dummy_1' => 'nullable|string',

                'container_eqid_2' => 'nullable|string',
                'container_type_2' => 'nullable|string',
                'container_eqid_dummy_2' => 'nullable|string',

                'container_eqid_3' => 'nullable|string',
                'container_type_3' => 'nullable|string',
                'container_eqid_dummy_3' => 'nullable|string',

            ]);

  

            Log::warning('Iniciando processamento dos containers para truck_visit_tv_key: ' . $validatedData['truck_visit_tv_key'], $validatedData);

            

            $processedContainers = [];

            if(isset($validatedData['container_eqid_1'])){
                $container1 = $this->updateContainer($validatedData['truck_visit_tv_key'], $validatedData['container_eqid_1'], $validatedData['container_eqid_dummy_1'], $validatedData['container_type_1']);
                Log::info('Resultado updateContainer 1', ['input' => [
                    'truck_visit_tv_key' => $validatedData['truck_visit_tv_key'],
                    'container_eqid' => $validatedData['container_eqid_1'],
                    'container_eqid_dummy' => $validatedData['container_eqid_dummy_1'],
                    'container_type' => $validatedData['container_type_1'],
                ], 'result' => $container1]);
                $processedContainers[] = $container1['result'] ?? false;
            }
            

            if(isset($validatedData['container_eqid_2'])){
                $container2 = $this->updateContainer($validatedData['truck_visit_tv_key'], $validatedData['container_eqid_2'], $validatedData['container_eqid_dummy_2'], $validatedData['container_type_2']);
                Log::info('Resultado updateContainer 2', ['input' => [
                    'truck_visit_tv_key' => $validatedData['truck_visit_tv_key'],
                    'container_eqid' => $validatedData['container_eqid_2'],
                    'container_eqid_dummy' => $validatedData['container_eqid_dummy_2'],
                    'container_type' => $validatedData['container_type_2'],
                ], 'result' => $container2]);
                $processedContainers[] = $container2['result'] ?? false;
            }
            if(isset($validatedData['container_eqid_3'])){
                $container3 = $this->updateContainer($validatedData['truck_visit_tv_key'], $validatedData['container_eqid_3'], $validatedData['container_eqid_dummy_3'], $validatedData['container_type_3']);
                Log::info('Resultado updateContainer 3', ['input' => [
                    'truck_visit_tv_key' => $validatedData['truck_visit_tv_key'],
                    'container_eqid' => $validatedData['container_eqid_3'],
                    'container_eqid_dummy' => $validatedData['container_eqid_dummy_3'],
                    'container_type' => $validatedData['container_type_3'],
                ], 'result' => $container3]);
                $processedContainers[] = $container3['result'] ?? false;
            }

            Log::debug('Resultados dos containers processados', ['processedContainers' => $processedContainers]);

            if(in_array(false, $processedContainers)) {
                Log::error('Erro: Container not found. Contentor ainda nao foi feito execute para Yard', [
                    'processedContainers' => $processedContainers,
                    'validatedData' => $validatedData
                ]);
                return response()->json(
                    [
                        'error' => "Container not found. Contentor ainda nao foi feito execute para Yard",
                        'message' => [],
                        'result' => [],
                    ],
                    421
                );
            }
            

            $xml = new SimpleXMLElement('<gate/>');
            $processTruck = $xml->addChild('process-truck');
            $processTruck->addAttribute('do-stage-done', 'false');
            $processTruck->addAttribute('no-content', 'true');

            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);
            $processTruck->addChild('lane-id', $validatedData['lane_id']);

            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', trim($validatedData['truck_license_nbr']));

            $processTruck->addChild('chassis-profile')->addAttribute('id', $validatedData['chassis_profile_id']);

            $driver = $processTruck->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truckVisit = $processTruck->addChild('truck-visit');
            $truckVisit->addAttribute('appointment-nbr', $validatedData['truck_visit_appointment_nbr']);
            $truckVisit->addAttribute('external-ref-nbr', $validatedData['truck_visit_external_ref_nbr']);
            $truckVisit->addAttribute('gos-tv-key', $validatedData['truck_visit_gos_tv_key']);
            $truckVisit->addAttribute('tv-key', $validatedData['truck_visit_tv_key']);

            $equipment = $processTruck->addChild('equipment');
            $container = $equipment->addChild('container');
            $container->addAttribute('eqid', $validatedData['container_eqid_1']);
            $container->addAttribute('type', $validatedData['container_type_1']);
            $container->addAttribute('notes', $validatedData['container_notes']);
            $container->addAttribute('truck-position', $validatedData['truck_position']);
            $container->addAttribute('is-placarded', $validatedData['is_placarded'] ? 'true' : 'false');
            $container->addAttribute('is-sealed', $validatedData['is_sealed'] ? 'true' : 'false');
            //N4 XML data - to create appointment
            $xmlDoc   = $xml->asXML();

            //Attach to invoke and create appointment
            $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client       = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            //Webservice send data
            $wsParam      = $client->basicInvoke($basicInvoke);
            //Result to get information from navis
            $xml_result       = $wsParam->basicInvokeResponse;
            //condition when SHED16 "Appointment not created"
            $result = $this->convert_xml_to_json($xml_result);
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
                        'xml_response' => $xml_result
                    ],
                    400
                );
            }
            
            $this->sendMailService->sendDeliveryNoteEmail($validatedData['truck_visit_tv_key']);
            
            return response()->json(
                [
                    'error' => [],
                    'message' => [],
                    'result' => [
                        $result,
                        'xml_response' => $xml_result,
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

    public function updateContainer($truck_visit_gkey, $containerId, $dummyId, $isoCode)
    {
        $conn = self::conexao();
        // $conn = self::conexaoTest();

        // $sqlSelect = "
        //     SELECT TOP 1 [gkey], [id], [visit_state], [needs_review], [category], [freight_kind], [dray_status], create_time
        //     FROM [N4DB].[dbo].[inv_unit] 
        //     WHERE id = :container_id AND visit_state = '1ACTIVE' 
        //     ORDER BY create_time DESC
        // ";


        $sqlSelect= "SELECT TOP 1 [N4DB].[dbo].[inv_unit].[gkey], [N4DB].[dbo].[inv_unit].[id] as container, [visit_state], [needs_review], [category], [freight_kind], [dray_status], create_time, basic_length, eqrt.id as isocode
            FROM [N4DB].[dbo].[inv_unit] join [N4DB].[dbo].[ref_equipment] rtt on rtt.gkey = inv_unit.eq_gkey join [N4DB].[dbo].[ref_equip_type] eqrt on eqrt.gkey = rtt.eqtyp_gkey
            WHERE [N4DB].[dbo].[inv_unit].[id] = :container_id AND visit_state = '1ACTIVE'
            ORDER BY create_time DESC";




        $stmtSelect = $conn->prepare($sqlSelect);
        $stmtSelect->bindValue(':container_id', $containerId, PDO::PARAM_STR);
        $stmtSelect->execute();
        $container = $stmtSelect->fetch(PDO::FETCH_ASSOC);

        Log::info($container);

        //nova verificacao se ja foi feito execute completo do container

        $verifications = [
            'road_truck_transactions' => [],
            'road_truck_transaction_stages' => []
        ];

        try {
            // Query 1
            $sqlCheck1 = "SELECT * FROM [N4DB].[dbo].[road_truck_transactions] where truck_visit_gkey = :tvg and status != 'CANCEL'";
            $stmtCheck1 = $conn->prepare($sqlCheck1);
            $stmtCheck1->bindValue(':tvg', $truck_visit_gkey, PDO::PARAM_INT);
            $stmtCheck1->execute();
            $verifications['road_truck_transactions'] = $stmtCheck1->fetchAll(PDO::FETCH_ASSOC);
            Log::info('check1', $verifications['road_truck_transactions']);
        } catch (\Throwable $th) {
            Log::error('Erro na verificação 1(Road Truck Transactions): ' . $th->getMessage());
            $verifications['road_truck_transactions'] = ['error' => $th->getMessage()];
            return [
                    "container_id" => $containerId,
                    "result" => false
            ];
        }

        try {
           // tran_gkey a partir dos resultados da query 1
            $tranGkeys = array_column($verifications['road_truck_transactions'], 'gkey');

            if (!empty($tranGkeys)) {
                // Cria placeholders paramétricos (?, ?, ...)
                $placeholders = implode(',', array_fill(0, count($tranGkeys), '?'));

                $sqlCheck2 = "SELECT * FROM [N4DB].[dbo].[road_truck_transaction_stages] WHERE tran_gkey IN ($placeholders) AND id = 'yard' AND status = 'COMPLETE'";
                $stmtCheck2 = $conn->prepare($sqlCheck2);

                // Bind de cada gkey (1-based para bindValue com ?)
                foreach ($tranGkeys as $i => $gkey) {
                    $stmtCheck2->bindValue($i + 1, (int)$gkey, PDO::PARAM_INT);
                }

                $stmtCheck2->execute();
                $verifications['road_truck_transaction_stages'] = $stmtCheck2->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Nenhuma transaction encontrada, retorna array vazio
                $verifications['road_truck_transaction_stages'] = [];
                Log::info('check2', 'Nenhum tran_gkey encontrado para verificar stages');
                return [
                    "container_id" => $containerId,
                    "result" => false
                ];
            }

            Log::info('check2', $verifications['road_truck_transaction_stages']);
        } catch (\Throwable $th) {
            Log::error('Erro na verificação 2(Road Truck Transaction Stage): ' . $th->getMessage());
            $verifications['road_truck_transaction_stages'] = ['error' => $th->getMessage()];
            return [
                    "container_id" => $containerId,
                    "result" => false
            ];
        }

        $countTrans = is_array($verifications['road_truck_transactions']) ? count($verifications['road_truck_transactions']) : 0;
        $countStages = is_array($verifications['road_truck_transaction_stages']) ? count($verifications['road_truck_transaction_stages']) : 0;

        // Se os numeros forem diferentes, retorna false ou seja ainda nao deram baixa ou execute
        if ($countTrans !== $countStages) {
            Log::warning("Mismatch counts: transactions={$countTrans} stages={$countStages}");
            return [
                    "container_id" => $containerId,
                    "result" => false
            ];
        }
        //fim verificacao se ja foi feito execute completo do container
        


        if ($container) {

            if($isoCode == '22G1') {
                $basicLength = substr($container['basic_length'],5);
                $result = $isoCode[0] == $basicLength[0];
  
            }elseif($isoCode == '45G1') {
                $basicLength = substr($container['basic_length'],5);
                $result =  $isoCode[0] == $basicLength[0];
            }else{
                return [
                    "container_id" => $containerId,
                    "result" => false
                ];
            }

            if($result){
                try {
                    $unitGkey = $container['gkey'];
                    $sqlUpdate = "
                        UPDATE [N4DB].[dbo].[road_truck_transactions]
                        SET ctr_id = :container_id,
                            unit_gkey = :unit_gkey
                        WHERE gkey = (
                            SELECT TOP 1 gkey
                            FROM [N4DB].[dbo].[road_truck_transactions]
                            WHERE truck_visit_gkey = :truck_visit_gkey AND ctr_id_assigned = :dummy
                            ORDER BY gkey DESC
                        )
                    ";

                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    $stmtUpdate->bindValue(':container_id', $containerId, PDO::PARAM_STR);
                    $stmtUpdate->bindValue(':dummy', $dummyId, PDO::PARAM_STR);
                    $stmtUpdate->bindValue(':unit_gkey', $unitGkey, PDO::PARAM_INT);
                    $stmtUpdate->bindValue(':truck_visit_gkey', $truck_visit_gkey, PDO::PARAM_INT);
                    $stmtUpdate->execute();
                    return [
                        "container_id" => $containerId,
                        "result" => true
                    ];
                } catch (\Throwable $th) {
                    return [
                        "container_id" => $containerId,
                        "result" => false,
                        "message" => $th->getMessage()
                    ];
                }
                
            }else{
                return [
                    "container_id" => $containerId,
                    "result" => false
                ];
            }
        }
    }
    //gate out old
    // public function gate_out(Request $request)
    // {
    //     try {
    //         //API WSDL URL
    //         $url      = $this->url;
    //         $user     = $this->user;
    //         $pass     = $this->pass;
    //         //N4 Scope Params
    //         $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
    //         // Validação dos dados recebidos

    //         $validatedData = $request->validate([
    //             'gate_id' => 'nullable|string',
    //             'stage_id' => 'nullable|string',
    //             'lane_id' => 'nullable|string',
    //             'truck_license_nbr' => 'nullable|string',
    //             'chassis_profile_id' => 'nullable|string',
    //             'driver_card_id' => 'nullable|string',
    //             'truck_visit_appointment_nbr' => 'nullable|string',
    //             'truck_visit_external_ref_nbr' => 'nullable|string',
    //             'truck_visit_gos_tv_key' => 'nullable|string',
    //             'truck_visit_tv_key' => 'nullable|string',
    //             'container_eqid' => 'nullable|string',
    //             'container_type' => 'nullable|string',
    //             'container_notes' => 'nullable|string',
    //             'truck_position' => 'nullable|string',
    //             'is_placarded' => 'nullable|string',
    //             'is_sealed' => 'nullable|string',
    //         ]);

    //         $this->updateContainer($validatedData['truck_visit_tv_key'], $validatedData['container_eqid']);

    //         // Criação do XML
    //         $xml = new SimpleXMLElement('<gate/>');
    //         $processTruck = $xml->addChild('process-truck');
    //         $processTruck->addAttribute('do-stage-done', 'false');
    //         $processTruck->addAttribute('no-content', 'true');

    //         $processTruck->addChild('gate-id', $validatedData['gate_id']);
    //         $processTruck->addChild('stage-id', $validatedData['stage_id']);
    //         $processTruck->addChild('lane-id', $validatedData['lane_id']);

    //         $truck = $processTruck->addChild('truck');
    //         $truck->addAttribute('license-nbr', trim($validatedData['truck_license_nbr']));

    //         $processTruck->addChild('chassis-profile')->addAttribute('id', $validatedData['chassis_profile_id']);

    //         $driver = $processTruck->addChild('driver');
    //         $driver->addAttribute('card-id', $validatedData['driver_card_id']);

    //         $truckVisit = $processTruck->addChild('truck-visit');
    //         $truckVisit->addAttribute('appointment-nbr', $validatedData['truck_visit_appointment_nbr']);
    //         $truckVisit->addAttribute('external-ref-nbr', $validatedData['truck_visit_external_ref_nbr']);
    //         $truckVisit->addAttribute('gos-tv-key', $validatedData['truck_visit_gos_tv_key']);
    //         $truckVisit->addAttribute('tv-key', $validatedData['truck_visit_tv_key']);

    //         $equipment = $processTruck->addChild('equipment');
    //         $container = $equipment->addChild('container');
    //         $container->addAttribute('eqid', $validatedData['container_eqid']);
    //         $container->addAttribute('type', $validatedData['container_type']);
    //         $container->addAttribute('notes', $validatedData['container_notes']);
    //         $container->addAttribute('truck-position', $validatedData['truck_position']);
    //         $container->addAttribute('is-placarded', $validatedData['is_placarded'] ? 'true' : 'false');
    //         $container->addAttribute('is-sealed', $validatedData['is_sealed'] ? 'true' : 'false');
    //         //N4 XML data - to create appointment
    //         $xmlDoc   = $xml->asXML();

    //         //Attach to invoke and create appointment
    //         $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
    //         //Client WSDL call API to send Informations
    //         $client       = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
    //         //Webservice send data
    //         $wsParam      = $client->basicInvoke($basicInvoke);
    //         //Result to get information from navis
    //         $xml_result       = $wsParam->basicInvokeResponse;
    //         //condition when SHED16 "Appointment not created"
    //         $result = $this->convert_xml_to_json($xml_result);
    //         $result = json_decode($result, true);
    //         //tratar os estados
    //         $status = $result["@attributes"]["status"];
    //         $statusId = (string) $result["@attributes"]["status-id"];
    //         if ($status == 3 && $statusId == "SEVERE") {
    //             $errorMessages = [];
    //             if (count($result['messages']['message']) > 1) {
    //                 foreach ($result['messages']['message'] as $message) {
    //                     $attributes = $message['@attributes'];
    //                     $errorMessages[] = ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'],];
    //                 }
    //             } else {
    //                 $errorMessages = $result['messages']['message']['@attributes'];
    //             }
    //             return response()->json(
    //                 [
    //                     'error' => [
    //                         $errorMessages
    //                     ],
    //                     'message' => 'error',
    //                     'result' => [],
    //                     'xml_response' => $xml_result
    //                 ],
    //                 400
    //             );
    //         }
            
    //         return response()->json(
    //             [
    //                 'error' => [],
    //                 'message' => [],
    //                 'result' => [
    //                     $result,
    //                     'xml_response' => $xml_result,
    //                 ],

    //             ],
    //             200
    //         );
    //     } catch (Exception $e) {
    //         return response()->json(
    //             [
    //                 'error' => [
    //                     $e->getMessage()
    //                 ],
    //                 'message' => 'transaction not saved',
    //                 'result' => [],
    //             ],
    //             404
    //         );
    //     }
    // }

    // public function updateContainer($truck_visit_gkey, $containerId)
    // {
    //     $conn = self::conexao();
    //     // $conn = self::conexaoTest();

    //     $sqlSelect = "
    //         SELECT TOP 1 [gkey], [id], [visit_state], [needs_review], [category], [freight_kind], [dray_status], create_time
    //         FROM [N4DB].[dbo].[inv_unit]
    //         WHERE id = :container_id AND visit_state = '1ACTIVE'
    //         ORDER BY create_time DESC
    //     ";

    //     $stmtSelect = $conn->prepare($sqlSelect);
    //     $stmtSelect->bindValue(':container_id', $containerId, PDO::PARAM_STR);
    //     $stmtSelect->execute();
    //     $container = $stmtSelect->fetch(PDO::FETCH_ASSOC);

    //     if ($container) {
    //         $unitGkey = $container['gkey'];
    //         $sqlUpdate = "
    //             UPDATE [N4DB].[dbo].[road_truck_transactions]
    //             SET ctr_id = :container_id,
    //                 unit_gkey = :unit_gkey
    //             WHERE gkey = (
    //                 SELECT TOP 1 gkey
    //                 FROM [N4DB].[dbo].[road_truck_transactions]
    //                 WHERE truck_visit_gkey = :truck_visit_gkey
    //                 ORDER BY gkey DESC
    //             )
    //         ";

    //         $stmtUpdate = $conn->prepare($sqlUpdate);
    //         $stmtUpdate->bindValue(':container_id', $containerId, PDO::PARAM_STR);
    //         $stmtUpdate->bindValue(':unit_gkey', $unitGkey, PDO::PARAM_INT);
    //         $stmtUpdate->bindValue(':truck_visit_gkey', $truck_visit_gkey, PDO::PARAM_INT);
    //         $stmtUpdate->execute();
    //     }
    // }
}

