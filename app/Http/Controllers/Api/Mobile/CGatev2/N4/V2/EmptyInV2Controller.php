<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Traits\HttpResponses;
use SoapClient;
use Exception;
use SimpleXMLElement;
use App\Service\N4\SendMailService;
use SoapParam;

class EmptyInV2Controller extends Controller
{
    use HttpResponses;

    //9.3 Drop Off Empty (RM) Receive Empty Transaction

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
            $url      = $this->url;
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            //variables from CDMS appointment
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'nullable|string',
                'truck_license_nbr' => 'required|string',
                'chassis_profile_id' => 'required|string',
                'driver_card_id' => 'required|string',
                'truck_visit_appointment_nbr' => 'required|string',
                'truck_visit_external_ref_nbr' => 'required|string',
                'truck_visit_gos_tv_key' => 'required|string',
                'truck_visit_tv_key' => 'required|string',
                'container_eqid' => 'required|string',
                'container_type' => 'required|string',
                'container_notes' => 'nullable|string',
                'container_truck_position' => 'required|string',
                'container_is_placarded' => 'required|string',
                'container_is_sealed' => 'required|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $processTruck = $xml->addChild('process-truck');
            $processTruck->addAttribute('do-stage-done', 'false');
            $processTruck->addAttribute('no-content', 'true');

            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);
            $processTruck->addChild('lane-id', $validatedData['lane_id'] ?? '');

            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);

            $chassisProfile = $processTruck->addChild('chassis-profile');
            $chassisProfile->addAttribute('id', $validatedData['chassis_profile_id']);

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
            $container->addAttribute('notes', $validatedData['container_notes'] ?? '');
            $container->addAttribute('truck-position', $validatedData['container_truck_position']);
            $container->addAttribute('is-placarded', $validatedData['container_is_placarded'] ?? 'false');
            $container->addAttribute('is-sealed', $validatedData['container_is_sealed'] ?? 'false');
            //N4 XML data - to create appointment
            $xmlDoc   = $xml->asXML();
            // dd($xmlDoc);
            //Attach to invoke and create appointment
            $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client = new SoapClient(null, [
                'location' => $url,
                'uri' => $url,
                'trace' => true,
                'exceptions' => true,
                'soap_version' => SOAP_1_1,
                'login' => $user,
                'password' => $pass,
            ]);
            //Webservice send data
            $params = [
                new SoapParam($scopeIDS, 'scopeCoordinateIds'),
                new SoapParam($xmlDoc, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
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
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'nullable|string', // Pode ser vazio
                'truck_visit_tv_key' => 'required|string',
                'truck_transaction_appointment_nbr' => 'required|string',
                'truck_transaction_category' => 'required|string',
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
            $client = new SoapClient(null, [
                'location' => $url,
                'uri' => $url,
                'trace' => true,
                'exceptions' => true,
                'soap_version' => SOAP_1_1,
                'login' => $user,
                'password' => $pass,
            ]);
            //Webservice send data
            $params = [
                new SoapParam($scopeIDS, 'scopeCoordinateIds'),
                new SoapParam($xmlDoc, 'xmlDoc')
            ];

            $wsParam = $client->__soapCall('basicInvoke', $params);
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
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'truck_visit_tv_key' => 'required|string',
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
            // dd($xmlDoc);[]
            //Attach to invoke and create appointment
            $basicInvoke = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            // $client = new SoapClient($url, array("trace" => 1, "exception" => 0, "login" => $user, "password" => $pass));
            $client = new SoapClient(null, [
                'location' => $url,
                'uri' => $url,
                'trace' => true,
                'exceptions' => true,
                'soap_version' => SOAP_1_1,
                'login' => $user,
                'password' => $pass,
            ]);
            //Webservice send data
            // $wsParam = $client->basicInvoke($basicInvoke);
            $params = [
                new SoapParam($scopeIDS, 'scopeCoordinateIds'),
                new SoapParam($xmlDoc, 'xmlDoc')
            ];

            $wsParam = $client->__soapCall('basicInvoke', $params);
            //Result to get information from navis
            $result_xml = $wsParam;
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
            $url      = $this->url;
            $user     = $this->user;
            $pass     = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'nullable|string',
                'truck_license_nbr' => 'required|string',
                'chassis_profile_id' => 'required|string',
                'driver_card_id' => 'required|string',
                'truck_visit_appointment_nbr' => 'required|string',
                'truck_visit_external_ref_nbr' => 'required|string',
                'truck_visit_gos_tv_key' => 'required|string',
                'truck_visit_tv_key' => 'required|string',
                'container_eqid' => 'required|string',
                'container_is_placarded' => 'required|string',
                'container_is_sealed' => 'required|string',
                'container_notes' => 'nullable|string',
                'container_truck_position' => 'required|string',
                'container_type' => 'required|string',
            ]);
            
            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $processTruck = $xml->addChild('process-truck');
            $processTruck->addAttribute('do-stage-done', 'false');
            $processTruck->addAttribute('no-content', 'true');

            $processTruck->addChild('gate-id', $validatedData['gate_id']);
            $processTruck->addChild('stage-id', $validatedData['stage_id']);
            $processTruck->addChild('lane-id', $validatedData['lane_id'] ?? '');

            $truck = $processTruck->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);

            $chassisProfile = $processTruck->addChild('chassis-profile');
            $chassisProfile->addAttribute('id', $validatedData['chassis_profile_id']);

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
            $container->addAttribute('is-placarded', $validatedData['container_is_placarded'] ? 'true' : 'false');
            $container->addAttribute('is-sealed', $validatedData['container_is_sealed'] ? 'true' : 'false');
            $container->addAttribute('notes', $validatedData['container_notes'] ?? '');
            $container->addAttribute('truck-position', $validatedData['container_truck_position']);
            $container->addAttribute('type', $validatedData['container_type']);
            //N4 XML data - to create appointment
            $xmlDoc   = $xml->asXML();
            // dd($xmlDoc);
            //Attach to invoke and create appointment
            $basicInvoke  = array('scopeCoordinateIds' => $scopeIDS, 'xmlDoc' => $xmlDoc);
            //Client WSDL call API to send Informations
            $client = new SoapClient(null, [
                'location' => $url,
                'uri' => $url,
                'trace' => true,
                'exceptions' => true,
                'soap_version' => SOAP_1_1,
                'login' => $user,
                'password' => $pass,
            ]);
            //Webservice send data
            $params = [
                new SoapParam($scopeIDS, 'scopeCoordinateIds'),
                new SoapParam($xmlDoc, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);
            //Result to get information from navis
            $xml_result       = $wsParam;
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
}