<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use SoapClient;
use Exception;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
use PDO;
use PDOException;
use SoapParam;

class N4GeneralIntegrationV2Controller extends Controller
{
    use HttpResponses;
    //Export Full In Transactions
    protected $url, $user, $pass;

    public function __construct()
    {
        $this->url = config('app.navis_api_url');
        $this->user = config('app.navis_api_username');
        $this->pass = config('app.name_api_password');
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
    //create container visit appointment
    public function create_container_appointment(Request $request)
    {
        try {
            //API WSDL URL and N4 Credentials
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scope_ids = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'appointment_date' => 'required|date_format:Y-m-d',
                'gate_id' => 'required|string',
                'driver_card_id' => 'required|string',
                'truck_license_nbr' => 'required|string',
                'booking_nbr' => 'nullable|string',
                'line' => 'required|string',
                'tran_type' => 'required|string', // Supondo que "RE" ou "DE" sejam valores válidos
                'container_eqid' => 'required|string',
            ]);
     
            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $createAppointment = $xml->addChild('create-appointment');
            $createAppointment->addChild('appointment-date', $validatedData['appointment_date']);
            $createAppointment->addChild('gate-id', $validatedData['gate_id']);

            $driver = $createAppointment->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truck = $createAppointment->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);

            $booking = $createAppointment->addChild('booking');
            $booking->addAttribute('booking-nbr', $validatedData['booking_nbr'] ?? '');
            $booking->addAttribute('line', $validatedData['line']);

            $createAppointment->addChild('tran-type', $validatedData['tran_type']);

            $container = $createAppointment->addChild('container');
            $container->addAttribute('eqid', $validatedData['container_eqid']);
            $xml_document = $xml->asXML();
            //Attach to invoke and create appointment
            $basic_invoke = array('scopeCoordinateIds' => $scope_ids, 'xmlDoc' => $xml_document);
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
                new SoapParam($scope_ids, 'scopeCoordinateIds'),
                new SoapParam($xml_document, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
            //json
            $json_result = $this->convert_xml_to_json($xml_result);
            $json_result = json_decode($json_result, true);
            //tratar os estados
            $status = $json_result["@attributes"]["status"];
            $statusId = (string)$json_result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($json_result['messages']['message']) > 1) {
                    foreach ($json_result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        array_push($errorMessages, ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail'] ? $attributes['message-detail'] : ""]);
                    }
                } else {
                    $errorMessages = $json_result['messages']['message']['@attributes'];
                }
                return $this->error('Error executing this operation', 400, $errorMessages);
            }
            /**
             * \
             */
            // Carregar o XML na classe SimpleXML
            $xml = simplexml_load_string($xml_result);
            // Registar o namespace usado no XML
            $xml->registerXPathNamespace('argo', 'http://www.navis.com/argo');
            $gateId = (string)$xml->xpath('//gate-id')[0];
            $appointmentNbr = (string)$xml->xpath('//appointment-nbr')[0];
            $slotStart = (string)$xml->xpath('//slot')[0]['slot-start'];
            $slotEnd = (string)$xml->xpath('//slot')[0]['slot-end'];
            $bookingNbr = (string)$xml->xpath('//booking')[0]['booking-nbr'];
            $line = (string)$xml->xpath('//booking')[0]['line'];
            $tranType = (string)$xml->xpath('//tran-type')[0];
            $requiresXray = (string)$xml->xpath('//appointment')[0]['requires-xray'];
            $containerEqid = (string)$xml->xpath('//container')[0]['eqid'];
            $chassisOwners = (string)$xml->xpath('//chassis')[0]['is-owners'];
            $truckLicenseNbr = (string)$xml->xpath('//truck')[0]['license-nbr'];
            //API N4
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $json_result['@attributes']
                    ],
                    'result' => [
                        'gate_id' => $gateId,
                        'appointment_nbr' => $appointmentNbr,
                        'slot_start' => $slotStart,
                        'slot_end' => $slotEnd,
                        'booking_nbr' => $bookingNbr,
                        'line' => $line,
                        'tran_type' => $tranType,
                        'requires_xray' => $requiresXray,
                        'container_eqid' => $containerEqid,
                        'chassis_owners' => $chassisOwners,
                        'truck_license_nbr' => $truckLicenseNbr,
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
                    'message' => 'error',
                    'result' => [],
                ],
                404
            );
        }
    }
    //update container visit appointment
    public function update_container_appointment(Request $request)
    {
        try {
            //API WSDL URL and N4 Credentials
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scope_ids = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'appointment_date' => 'nullable|date_format:Y-m-d',
                'gate_id' => 'nullable|string',
                'driver_card_id' => 'nullable|string',
                'truck_license_nbr' => 'nullable|string',
                'booking_nbr' => 'nullable|string',
                'line' => 'nullable|string',
                'tran_type' => 'nullable|string', // Supondo que "RE" ou "DE" sejam valores válidos
                'container_eqid' => 'nullable|string',
                'container_type' => 'nullable|string',
                'container_truck_position' => 'nullable|string',
                'container_door_direction' => 'nullable|string', // F para frente, B para trás
                'container_seal_1' => 'nullable|string',
                'appointment_nbr' => 'nullable|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $updateAppointment = $xml->addChild('update-appointment');

            $updateAppointment->addChild('appointment-date', $validatedData['appointment_date']);
            $updateAppointment->addChild('gate-id', $validatedData['gate_id']);

            $driver = $updateAppointment->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truck = $updateAppointment->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);

            $booking = $updateAppointment->addChild('booking');
            $booking->addAttribute('booking-nbr', $validatedData['booking_nbr'] ?? '');
            $booking->addAttribute('line', $validatedData['line']);

            $updateAppointment->addChild('tran-type', $validatedData['tran_type']);

            $container = $updateAppointment->addChild('container');
            $container->addAttribute('eqid', $validatedData['container_eqid']);
            $container->addAttribute('type', $validatedData['container_type']);
            $container->addAttribute('truck-position', $validatedData['container_truck_position']);
            $container->addAttribute('door-direction', $validatedData['container_door_direction']);
            $container->addAttribute('seal-1', $validatedData['container_seal_1'] ?? '');

            $updateAppointment->addChild('appointment-nbr', $validatedData['appointment_nbr']);
            $xml_document = $xml->asXML();

            //Attach to invoke and create appointment
            $basic_invoke = array('scopeCoordinateIds' => $scope_ids, 'xmlDoc' => $xml_document);
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
                new SoapParam($scope_ids, 'scopeCoordinateIds'),
                new SoapParam($xml_document, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
            //json
            $json_result = $this->convert_xml_to_json($xml_result);
            $json_result = json_decode($json_result, true);
            //tratar os estados
            $status = $json_result["@attributes"]["status"];
            $statusId = (string)$json_result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($json_result['messages']['message']) > 1) {
                    foreach ($json_result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        array_push($errorMessages, ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail'] ? $attributes['message-detail'] : ""]);
                    }
                } else {
                    $errorMessages = $json_result['messages']['message']['@attributes'];
                }
                return $this->error('Error executing this operation', 400, $errorMessages);
            }
            /**
             * \
             */
            // Carregar o XML na classe SimpleXML
            $xml = simplexml_load_string($xml_result);
            // Registar o namespace usado no XML
            $xml->registerXPathNamespace('argo', 'http://www.navis.com/argo');
            $gateId = (string)$xml->xpath('//gate-id')[0];
            $appointmentNbr = (string)$xml->xpath('//appointment-nbr')[0];
            $slotStart = (string)$xml->xpath('//slot')[0]['slot-start'];
            $slotEnd = (string)$xml->xpath('//slot')[0]['slot-end'];
            $bookingNbr = (string)$xml->xpath('//booking')[0]['booking-nbr'];
            $line = (string)$xml->xpath('//booking')[0]['line'];
            $tranType = (string)$xml->xpath('//tran-type')[0];
            $requiresXray = (string)$xml->xpath('//appointment')[0]['requires-xray'];
            $containerEqid = (string)$xml->xpath('//container')[0]['eqid'];
            $chassisOwners = (string)$xml->xpath('//chassis')[0]['is-owners'];
            $truckLicenseNbr = (string)$xml->xpath('//truck')[0]['license-nbr'];

            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $json_result['@attributes']
                    ],
                    'result' => [
                        'gate_id' => $gateId,
                        'appointment_nbr' => $appointmentNbr,
                        'slot_start' => $slotStart,
                        'slot_end' => $slotEnd,
                        'booking_nbr' => $bookingNbr,
                        'line' => $line,
                        'tran_type' => $tranType,
                        'requires_xray' => $requiresXray,
                        'container_eqid' => $containerEqid,
                        'chassis_owners' => $chassisOwners,
                        'truck_license_nbr' => $truckLicenseNbr,
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
                    'message' => 'error',
                    'result' => [],
                ],
                404
            );
        }
    }

    //create truck visit appointment
    public function create_truck_visit_appointment(Request $request)
    {
        try {
            //API WSDL URL and N4 Credentials
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scope_ids = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'appointment_date' => 'required|date_format:Y-m-d',
                'appointment_time' => 'required|date_format:H:i:s',
                'gate_id' => 'required|string',
                'driver_card_id' => 'required|string',
                'truck_id' => 'required|string',
                'truck_license_nbr' => 'required|string',
                'trucking_co_id' => 'required|string',
                'external_ref_nbr' => 'required|string',
                'appointment_nbr' => 'required|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $createTruckVisitAppointment = $xml->addChild('create-truck-visit-appointment');

            $createTruckVisitAppointment->addChild('appointment-date', $validatedData['appointment_date']);
            $createTruckVisitAppointment->addChild('appointment-time', $validatedData['appointment_time']);
            $createTruckVisitAppointment->addChild('gate-id', $validatedData['gate_id']);

            $driver = $createTruckVisitAppointment->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truck = $createTruckVisitAppointment->addChild('truck');
            $truck->addAttribute('id', $validatedData['truck_id']);
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);
            $truck->addAttribute('trucking-co-id', $validatedData['trucking_co_id']);

            $createTruckVisitAppointment->addChild('external-ref-nbr', $validatedData['external_ref_nbr']);

            $appointments = $createTruckVisitAppointment->addChild('appointments');
            $appointment = $appointments->addChild('appointment');
            $appointment->addAttribute('appointment-nbr', $validatedData['appointment_nbr']);
            //N4 XML data - to create appointment
            $xml_document = $xml->asXML();

            //Attach to invoke and create appointment
            $basic_invoke = array('scopeCoordinateIds' => $scope_ids, 'xmlDoc' => $xml_document);
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
                new SoapParam($scope_ids, 'scopeCoordinateIds'),
                new SoapParam($xml_document, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
            //json
            $json_result = $this->convert_xml_to_json($xml_result);
            $json_result = json_decode($json_result, true);
            //tratar os estados
            $status = $json_result["@attributes"]["status"];
            $statusId = (string)$json_result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($json_result['messages']['message']) > 1) {
                    foreach ($json_result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        array_push($errorMessages, ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail'] ? $attributes['message-detail'] : ""]);
                    }
                } else {
                    $errorMessages = $json_result['messages']['message']['@attributes'];
                }
                return $this->error('Error executing this operation', 400, $errorMessages);
            }
            // Carregar XML usando SimpleXMLElement
            $xml = new \SimpleXMLElement($xml_result);
            // Registrar namespace 'argo'
            $xml->registerXPathNamespace('argo', 'http://www.navis.com/argo');
            // Extrair os dados usando XPath
            $status = (string) $xml['status'];
            $statusId = (string) $xml['status-id'];
            $gateId = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/gate-id')[0];
            $appointmentNbr = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/appointment-nbr')[0];
            $slotStart = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/slot/@slot-start')[0];
            $slotEnd = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/slot/@slot-end')[0];
            $driverCardId = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/driver/@card-id')[0];
            $truckId = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/truck/@id')[0];
            $truckLicense = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/truck/@license-nbr')[0];
            $externalRefNbr = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/external-ref-nbr')[0];
            $appointmentNbrInner = (string) $xml->xpath('//argo:gate-response/create-truck-visit-appointment-response/appointments/appointment/@appointment-nbr')[0];


            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $json_result['@attributes']
                    ],
                    'result' => [
                        'status' => $status,
                        'statusId' => $statusId,
                        'gateId' => $gateId,
                        'appointmentNbr' => $appointmentNbr,
                        'slotStart' => $slotStart,
                        'slotEnd' => $slotEnd,
                        'driverCardId' => $driverCardId,
                        'truckId' => $truckId,
                        'truckLicense' => $truckLicense,
                        'externalRefNbr' => $externalRefNbr,
                        'appointmentNbrInner' => $appointmentNbrInner,
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
                    'message' => 'error',
                    'result' => [],
                ],
                404
            );
        }
    }
    //update truck visit appointment
    public function update_truck_visit_appointment(Request $request)
    {
        try {
            //API WSDL URL and N4 Credentials
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scope_ids = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados recebidos
            $validatedData = $request->validate([
                'appointment_date' => 'required|date_format:Y-m-d',
                'appointment_time' => 'required|date_format:H:i:s',
                'gate_id' => 'required|string',
                'driver_card_id' => 'required|string',
                'truck_id' => 'required|string',
                'truck_license_nbr' => 'required|string',
                'trucking_co_id' => 'required|string',
                'external_ref_nbr' => 'required|string',
                'appointment_nbr' => 'required|string',
                'updated_appointment_nbr' => 'required|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $updateTruckVisitAppointment = $xml->addChild('update-truck-visit-appointment');

            $updateTruckVisitAppointment->addChild('appointment-date', $validatedData['appointment_date']);
            $updateTruckVisitAppointment->addChild('appointment-time', $validatedData['appointment_time']);
            $updateTruckVisitAppointment->addChild('gate-id', $validatedData['gate_id']);

            $driver = $updateTruckVisitAppointment->addChild('driver');
            $driver->addAttribute('card-id', $validatedData['driver_card_id']);

            $truck = $updateTruckVisitAppointment->addChild('truck');
            $truck->addAttribute('id', $validatedData['truck_id']);
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);
            $truck->addAttribute('trucking-co-id', $validatedData['trucking_co_id']);

            $updateTruckVisitAppointment->addChild('external-ref-nbr', $validatedData['external_ref_nbr']);

            $appointments = $updateTruckVisitAppointment->addChild('appointments');
            $appointment = $appointments->addChild('appointment');
            $appointment->addAttribute('appointment-nbr', $validatedData['appointment_nbr']);

            $updateTruckVisitAppointment->addChild('appointment-nbr', $validatedData['updated_appointment_nbr']);
            //N4 XML data - to create appointment
            $xml_document = $xml->asXML();
            //Attach to invoke and create appointment
            $basic_invoke = array('scopeCoordinateIds' => $scope_ids, 'xmlDoc' => $xml_document);
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
                new SoapParam($scope_ids, 'scopeCoordinateIds'),
                new SoapParam($xml_document, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
            //json
            $json_result = $this->convert_xml_to_json($xml_result);
            $json_result = json_decode($json_result, true);
            //tratar os estados
            $status = $json_result["@attributes"]["status"];
            $statusId = (string)$json_result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($json_result['messages']['message']) > 1) {
                    foreach ($json_result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        array_push($errorMessages, ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail'] ? $attributes['message-detail'] : ""]);
                    }
                } else {
                    $errorMessages = $json_result['messages']['message']['@attributes'];
                }
                return $this->error('Error executing this operation', 400, $errorMessages);
            }
            /**
             * \
             */
            // Carregar o XML na classe SimpleXML
            $xml = simplexml_load_string($xml_result);
            // Registar o namespace usado no XML
            $xml->registerXPathNamespace('argo', 'http://www.navis.com/argo');
            $gateId = (string)$xml->xpath('//gate-id')[0];
            $appointmentNbr = (string)$xml->xpath('//appointment-nbr')[0];
            $slotStart = (string)$xml->xpath('//slot')[0]['slot-start'];
            $slotEnd = (string)$xml->xpath('//slot')[0]['slot-end'];
            $bookingNbr = (string)$xml->xpath('//booking')[0]['booking-nbr'];
            $line = (string)$xml->xpath('//booking')[0]['line'];
            $tranType = (string)$xml->xpath('//tran-type')[0];
            $requiresXray = (string)$xml->xpath('//appointment')[0]['requires-xray'];
            $containerEqid = (string)$xml->xpath('//container')[0]['eqid'];
            $chassisOwners = (string)$xml->xpath('//chassis')[0]['is-owners'];
            $truckLicenseNbr = (string)$xml->xpath('//truck')[0]['license-nbr'];

            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $json_result['@attributes']
                    ],
                    'result' => [
                        'gate_id' => $gateId,
                        'appointment_nbr' => $appointmentNbr,
                        'slot_start' => $slotStart,
                        'slot_end' => $slotEnd,
                        'booking_nbr' => $bookingNbr,
                        'line' => $line,
                        'tran_type' => $tranType,
                        'requires_xray' => $requiresXray,
                        'container_eqid' => $containerEqid,
                        'chassis_owners' => $chassisOwners,
                        'truck_license_nbr' => $truckLicenseNbr,
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
                    'message' => 'error',
                    'result' => [],
                ],
                404
            );
        }
    }
    //cancel truck visit appointment
    public function cancel_truck_visit_appointment(Request $request)
    {
        try {
            //API WSDL URL and N4 Credentials
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scope_ids = "CDM/BEIRA/BEIRA/BEIRA";
            //variables from CDMS appointment
            $validatedData = $request->validate([
                'appointment_nbr' => 'required|string',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $cancelAppointment = $xml->addChild('cancel-truck-visit-appointment');
            $appointments = $cancelAppointment->addChild('appointments');
            $appointment = $appointments->addChild('appointment');
            $appointment->addAttribute('appointment-nbr', $validatedData['appointment_nbr']);
            //N4 XML data - to create appointment
            $xml_document = $xml->asXML();
            //Attach to invoke and create appointment
            $basic_invoke = array('scopeCoordinateIds' => $scope_ids, 'xmlDoc' => $xml_document);
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
                new SoapParam($scope_ids, 'scopeCoordinateIds'),
                new SoapParam($xml_document, 'xmlDoc')
            ];
            $wsParam = $client->__soapCall('basicInvoke', $params);

            //Result to get information from navis
            $xml_result = $wsParam;
            //json
            $json_result = $this->convert_xml_to_json($xml_result);
            $json_result = json_decode($json_result, true);
            //tratar os estados
            $status = $json_result["@attributes"]["status"];
            $statusId = (string)$json_result["@attributes"]["status-id"];
            if ($status == 3 && $statusId == "SEVERE") {
                $errorMessages = [];
                if (count($json_result['messages']['message']) > 1) {
                    foreach ($json_result['messages']['message'] as $message) {
                        $attributes = $message['@attributes'];
                        array_push($errorMessages, ['message_id' => $attributes['message-id'], 'message_severity' => $attributes['message-severity'], 'message_text' => $attributes['message-text'], 'message_detail' => $attributes['message-detail'] ? $attributes['message-detail'] : ""]);
                    }
                } else {
                    $errorMessages = $json_result['messages']['message']['@attributes'];
                }
                return $this->error('Error executing this operation', 400, $errorMessages);
            }

            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $json_result['@attributes']
                    ],
                    'result' => [
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
                    'message' => 'error',
                    'result' => [],
                ],
                404
            );
        }
    }
    //crete truck visit
    public function create_truck_visit(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'nullable|string',
                'truck_license_nbr' => 'required|string',
                'trucking_co_id' => 'required|string',
                'gos_tv_key' => 'required|string',
                'appointment_nbr' => 'required|string',
                'timestamp' => 'required|string',
            ]);
            //N4 XML data - to create appointment
            $xml = new SimpleXMLElement('<gate/>');
            $createTruckVisit = $xml->addChild('create-truck-visit');
            $createTruckVisit->addChild('gate-id', $validatedData['gate_id']);
            $createTruckVisit->addChild('stage-id', $validatedData['stage_id']);
            $createTruckVisit->addChild('lane-id', $validatedData['lane_id']);

            $truck = $createTruckVisit->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);
            $truck->addAttribute('trucking-co-id', $validatedData['trucking_co_id']);

            $truckVisit = $createTruckVisit->addChild('truck-visit');
            $truckVisit->addAttribute('gos-tv-key', $validatedData['gos_tv_key']);
            $truckVisit->addAttribute('appointment-nbr', $validatedData['appointment_nbr']);

            $createTruckVisit->addChild('timestamp', $validatedData['timestamp']);
            $xmlDoc = $xml->asXML();
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
            $result_xml = $wsParam;
            //now result return the appointment number
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
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
                        'xml_response' => [
                            $result_xml
                        ]
                    ],
                    400
                );
            }
            // Registar o namespace usado no XML
            // Analisar o XML usando SimpleXMLElement
            $xml = new \SimpleXMLElement($result_xml);
            $truckVisit = $xml->xpath('//create-truck-visit-response/truck-visit')[0];

            $tv_key = (string)$truckVisit['tv-key'];
            $gos_tv_key = (string)$truckVisit['gos-tv-key'];
            $appointment_nbr = (string)$truckVisit['appointment-nbr'];
            $is_internal = (string)$truckVisit['is-internal'];
            $next_stage_id = (string)$truckVisit['next-stage-id'];
            $status = (string)$truckVisit['status'];
            $gate_id = (string)$truckVisit['gate-id'];
            $entered = (string)$truckVisit['entered'];
            $trucking_co_id = (string)$truckVisit->{'trucking-co'}['id'];
            $truck_license_nbr = (string)$truckVisit->truck['license-nbr'];
            // Iniciando o cURL
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes']
                    ],
                    'result' => [
                        'tv_key' => $tv_key,
                        'gos_tv_key' => $gos_tv_key,
                        'appointment_nbr' => $appointment_nbr,
                        'is_internal' => $is_internal,
                        'next_stage_id' => $next_stage_id,
                        'status' => $status,
                        'gate_id' => $gate_id,
                        'entered' => $entered,
                        'trucking_co_id' => $trucking_co_id,
                        'truck_license_nbr' => $truck_license_nbr,
                        'xml_response' => $result_xml,
                        'c_gate_response' => ""
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
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
    //create truck visit exception
    public function create_truck_visit_exception(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'required|string',
                'truck_license_nbr' => 'required|string',
                'trucking_co_id' => 'required|string',
                'driver_license_nbr' => 'required|string',
                'gos_tv_key' => 'required|string',
                'bat_nbr' => 'required|string',
            ]);
            //N4 XML data - to create appointment
            $xml = new SimpleXMLElement('<gate/>');
            $createTruckVisit = $xml->addChild('create-truck-visit');
            $createTruckVisit->addChild('gate-id', $validatedData['gate_id']);
            $createTruckVisit->addChild('stage-id', $validatedData['stage_id']);
            $createTruckVisit->addChild('lane-id', $validatedData['lane_id']);

            $truck = $createTruckVisit->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);
            $truck->addAttribute('trucking-co-id', $validatedData['trucking_co_id']);

            $driver = $createTruckVisit->addChild('driver');
            $driver->addAttribute('license-nbr', $validatedData['driver_license_nbr']);

            $truckVisit = $createTruckVisit->addChild('truck-visit');
            $truckVisit->addAttribute('gos-tv-key', $validatedData['gos_tv_key']);
            $truckVisit->addAttribute('bat-nbr', $validatedData['bat_nbr']);

            $xmlDoc = $xml->asXML();
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
            $result_xml = $wsParam;
            //now result return the appointment number
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
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
                        'xml_response' => [
                            $result_xml
                        ]
                    ],
                    400
                );
            }
            // Registar o namespace usado no XML
            // Analisar o XML usando SimpleXMLElement
            $xml = new \SimpleXMLElement($result_xml);
            $truckVisit = $xml->xpath('//create-truck-visit-response/truck-visit')[0];

            $tv_key = (string)$truckVisit['tv-key'];
            $gos_tv_key = (string)$truckVisit['gos-tv-key'];
            $appointment_nbr = (string)$truckVisit['appointment-nbr'];
            $is_internal = (string)$truckVisit['is-internal'];
            $next_stage_id = (string)$truckVisit['next-stage-id'];
            $status = (string)$truckVisit['status'];
            $gate_id = (string)$truckVisit['gate-id'];
            $entered = (string)$truckVisit['entered'];
            $trucking_co_id = (string)$truckVisit->{'trucking-co'}['id'];
            $truck_license_nbr = (string)$truckVisit->truck['license-nbr'];
            // Iniciando o cURL
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes']
                    ],
                    'result' => [
                        'tv_key' => $tv_key,
                        'gos_tv_key' => $gos_tv_key,
                        'appointment_nbr' => $appointment_nbr,
                        'is_internal' => $is_internal,
                        'next_stage_id' => $next_stage_id,
                        'status' => $status,
                        'gate_id' => $gate_id,
                        'entered' => $entered,
                        'trucking_co_id' => $trucking_co_id,
                        'truck_license_nbr' => $truck_license_nbr,
                        'xml_response' => $result_xml,
                        'c_gate_response' => ""
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
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
    //update truck visit
    public function update_truck_visit(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'stage_id' => 'required|string',
                'lane_id' => 'required|string',
                'truck_license_nbr' => 'required|string',
                'trucking_co_id' => 'required|string',
                'gos_tv_key' => 'required|string',
                'bat_nbr' => 'required|string',
                'scale_weight' => 'required|numeric',
                'unit' => 'required|string|in:kg,lb',
                'timestamp' => 'required|date_format:Y-m-d\TH:i:s',
            ]);

            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $updateTruckVisit = $xml->addChild('update-truck-visit');
            $updateTruckVisit->addChild('gate-id', $validatedData['gate_id']);
            $updateTruckVisit->addChild('stage-id', $validatedData['stage_id']);
            $updateTruckVisit->addChild('lane-id', $validatedData['lane_id']);

            $truck = $updateTruckVisit->addChild('truck');
            $truck->addAttribute('license-nbr', $validatedData['truck_license_nbr']);
            $truck->addAttribute('trucking-co-id', $validatedData['trucking_co_id']);

            $truckVisit = $updateTruckVisit->addChild('truck-visit');
            $truckVisit->addAttribute('gos-tv-key', $validatedData['gos_tv_key']);
            $truckVisit->addAttribute('bat-nbr', $validatedData['bat_nbr']);

            $scaleWeight = $updateTruckVisit->addChild('scale-weight', $validatedData['scale_weight']);
            $scaleWeight->addAttribute('unit', $validatedData['unit']);

            $updateTruckVisit->addChild('timestamp', $validatedData['timestamp']);
            $xmlDoc = $xml->asXML();
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
            $result_xml = $wsParam;
            //now result return the appointment number
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
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
                        'xml_response' => [
                            $result_xml
                        ]
                    ],
                    400
                );
            }
            // Registar o namespace usado no XML
            // Analisar o XML usando SimpleXMLElement
            $xml = new \SimpleXMLElement($result_xml);
            $truckVisit = $xml->xpath('//create-truck-visit-response/truck-visit')[0];

            $tv_key = (string)$truckVisit['tv-key'];
            $gos_tv_key = (string)$truckVisit['gos-tv-key'];
            $appointment_nbr = (string)$truckVisit['appointment-nbr'];
            $is_internal = (string)$truckVisit['is-internal'];
            $next_stage_id = (string)$truckVisit['next-stage-id'];
            $status = (string)$truckVisit['status'];
            $gate_id = (string)$truckVisit['gate-id'];
            $entered = (string)$truckVisit['entered'];
            $trucking_co_id = (string)$truckVisit->{'trucking-co'}['id'];
            $truck_license_nbr = (string)$truckVisit->truck['license-nbr'];
            // Iniciando o cURL
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes']
                    ],
                    'result' => [
                        'tv_key' => $tv_key,
                        'gos_tv_key' => $gos_tv_key,
                        'appointment_nbr' => $appointment_nbr,
                        'is_internal' => $is_internal,
                        'next_stage_id' => $next_stage_id,
                        'status' => $status,
                        'gate_id' => $gate_id,
                        'entered' => $entered,
                        'trucking_co_id' => $trucking_co_id,
                        'truck_license_nbr' => $truck_license_nbr,
                        'xml_response' => $result_xml,
                        'c_gate_response' => ""
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
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
    //cancel truck visit
    public function cancel_truck_visit(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados
            $validatedData = $request->validate([
                'tv_key' => 'required|string',
            ]);
            //N4 XML data - to create appointment
            $xml = new SimpleXMLElement('<gate/>');
            $cancelTruckVisit = $xml->addChild('cancel-truck-visit');

            $truckVisit = $cancelTruckVisit->addChild('truck-visit');
            $truckVisit->addAttribute('tv-key', $validatedData['tv_key']);

            $xmlDoc = $xml->asXML();
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
            $result_xml = $wsParam;
            //now result return the appointment number
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
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
                        'xml_response' => [
                            $result_xml
                        ]
                    ],
                    400
                );
            }

            // Iniciando o cURL
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes']
                    ],
                    'result' => [
                        'xml_response' => $result_xml,
                        'c_gate_response' => ""
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
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
    //pickup appointments
    public function pickup_appointments(Request $request)
    {
        try {
            //API WSDL URL
            $url = $this->url;
            $user = $this->user;
            $pass = $this->pass;
            //N4 Scope Params
            $scopeIDS = "CDM/BEIRA/BEIRA/BEIRA";
            // Validação dos dados
            $validatedData = $request->validate([
                'gate_id' => 'required|string',
                'tv_key' => 'required|string',
                'timestamp' => 'required|date_format:Y-m-d\TH:i:s',
            ]);
            // Criação do XML
            $xml = new SimpleXMLElement('<gate/>');
            $requestPickupAppointments = $xml->addChild('request-pickup-appointments');

            $requestPickupAppointments->addChild('gate-id', $validatedData['gate_id']);

            $truckVisit = $requestPickupAppointments->addChild('truck-visit');
            $truckVisit->addAttribute('tv-key', $validatedData['tv_key']);

            $requestPickupAppointments->addChild('timestamp', $validatedData['timestamp']);

            $xmlDoc = $xml->asXML();
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
            $result_xml = $wsParam;
            //now result return the appointment number
            $result = $this->convert_xml_to_json($result_xml);
            $result = json_decode($result, true);
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
                        'xml_response' => [
                            $result_xml
                        ]
                    ],
                    400
                );
            }

            // Iniciando o cURL
            return response()->json(
                [
                    'error' => [],
                    'message' => [
                        $result['@attributes']
                    ],
                    'result' => [
                        'xml_response' => $result_xml,
                        'c_gate_response' => ""
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
                    'message' => 'transaction not found',
                    'result' => [],
                ],
                404
            );
        }
    }
    //create a new driver
    public function create_driver(Request $request)
    {
        try {
            // Criando uma única conexão
            $conn = self::conexao();
    
            // Obtém os valores do request com fallback para valores padrão
            $reference_set = '3';  // reference_set fixo como '3'
            $name = $request->name ?? '';
            $card_id = $request->card_id ?? '';
            $driver_license_nbr = $request->driver_license_nbr ?? '';
            $status = 'OK'; // Status fixo
            $creator = $request->creator ?? 'api';
            $life_cycle_state = 'ACT'; // Life cycle state fixo
            $id = 'card=' . ($request->card_id ?? ''); // id com 'card=' e o card_id
            $created = date('Y-m-d H:i:s'); // Agora, pega a data e hora atual
    
            // Verifica se o motorista já existe pelo driver_license_nbr
            // $sqlCheck = "SELECT card_id, name, driver_license_nbr  FROM [dbo].[road_truck_drivers]  WHERE driver_license_nbr = :driver_license_nbr AND card_id IS NOT NULL AND card_id = driver_license_nbr"";
            $sqlCheck = "SELECT COUNT(*) FROM [dbo].[road_truck_drivers] WHERE [driver_license_nbr] = :driver_license_nbr  AND card_id IS NOT NULL AND card_id = driver_license_nbr";

            $stmtCheck = $conn->prepare($sqlCheck);
            $stmtCheck->bindValue(':driver_license_nbr', $driver_license_nbr, PDO::PARAM_STR);
            $stmtCheck->execute();
            $driverExists = $stmtCheck->fetchColumn();
    
            if ($driverExists > 0) {
                 $sqlUpdate = "UPDATE [dbo].[road_truck_drivers]
                    SET [life_cycle_state] = 'ACT'
                    WHERE [driver_license_nbr] = :driver_license_nbr AND card_id IS NOT NULL AND card_id = driver_license_nbr";
                    $stmtUpdate = $conn->prepare($sqlUpdate);
                    $stmtUpdate->bindValue(':driver_license_nbr', $driver_license_nbr, PDO::PARAM_STR);
                    $stmtUpdate->execute();
                return $this->error('Driver with this license already exists.', 400);
            }
    
            // Define a query para inserir um novo motorista
            $sql = "INSERT INTO [dbo].[road_truck_drivers] 
                    ([reference_set], [name], [card_id], [driver_license_nbr], [status], [created], [creator], [life_cycle_state], [id]) 
                    VALUES 
                    (:reference_set, :name, :card_id, :driver_license_nbr, :status, :created, :creator, :life_cycle_state, :id)";
    
            // Prepara e executa a query
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':reference_set', $reference_set, PDO::PARAM_STR);
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':card_id', $card_id, PDO::PARAM_STR);
            $stmt->bindValue(':driver_license_nbr', $driver_license_nbr, PDO::PARAM_STR);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':created', $created, PDO::PARAM_STR);
            $stmt->bindValue(':creator', $creator, PDO::PARAM_STR);
            $stmt->bindValue(':life_cycle_state', $life_cycle_state, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $success = $stmt->execute();
    
            // Verifica se a inserção foi bem-sucedida
            if (!$success) {
                return $this->error('Failed to insert driver.', 400);
            }
    
            // Recupera o registro criado
            $sqlSelect = "SELECT * FROM [dbo].[road_truck_drivers] WHERE [driver_license_nbr] = :driver_license_nbr AND card_id IS NOT NULL AND card_id = driver_license_nbr";
            $stmtSelect = $conn->prepare($sqlSelect);
            $stmtSelect->bindValue(':driver_license_nbr', $driver_license_nbr, PDO::PARAM_STR);
            $stmtSelect->execute();
            $createdRecord = $stmtSelect->fetch(PDO::FETCH_ASSOC);
    
            // Verifica se o registro foi encontrado
            if (!$createdRecord) {
                return $this->error('Error fetching the created driver.', 400);
            }
    
            // Retorna resposta de sucesso com o registro criado
            return $this->response('Driver created successfully.', 200, $createdRecord);
    
        } catch (\Throwable $th) {
            return $this->error('Error creating driver', 400, [$th->getMessage()]);
        }
    }
    //get driver data
    public function get_driver_by_license(Request $request)
    {
        try {
            // Obtém o número da carteira de motorista do request
            $driver_license_nbr = $request->driver_license_nbr ?? null;
    
            // Verifica se foi informado um número de licença
            if (!$driver_license_nbr) {
                return $this->error('Driver license number is required.', 400);
            }
    
            // Consulta para buscar o motorista com as condições sem a barra no card_id
            $sql = "SELECT card_id, name, driver_license_nbr 
                    FROM [dbo].[road_truck_drivers] 
                    WHERE driver_license_nbr = :driver_license_nbr
                      AND card_id IS NOT NULL
                      AND card_id = driver_license_nbr";
    
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindValue(':driver_license_nbr', $driver_license_nbr, PDO::PARAM_STR);
            $stmt->execute();
            $driver = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Verifica se encontrou o motorista
            if (!$driver) {
                return $this->error('Driver not found.', 404);
            }
    
            // Retorna os dados do motorista encontrado
            return $this->response('Driver found.', 200, $driver);
    
        } catch (\Throwable $th) {
            return $this->error('Error fetching driver', 400, [$th->getMessage()]);
        }
    }
    
    //update seal number
    public function update_seal(Request $request)
    {
        try {
            $containerId = $request->container_id;
            $sealNbr1 = $request->seal_nbr1;

            if (!$containerId || !$sealNbr1) {
                return $this->error('container_id e seal_nbr1 são obrigatórios.', 422);
            }
    
           $conn = self::conexao();

            // Primeiro, obter o gkey do contêiner válido com transit_state contendo 'ECIN'
            $sqlSelect = "
                SELECT iu.gkey
                FROM [N4DB].[dbo].[inv_unit] iu
                JOIN [N4DB].[dbo].[inv_unit_fcy_visit] ufv ON ufv.unit_gkey = iu.gkey
                WHERE iu.id = :container_id
                  AND iu.visit_state = '1ACTIVE'
                  AND iu.category = 'EXPRT'
                  AND ufv.transit_state LIKE '%ECIN%'";
    
            $stmt = $conn->prepare($sqlSelect);
            $stmt->bindValue(':container_id', $containerId, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                return $this->error('Contentor não foi encontrado.', 404);
            }
    
            $unitGkey = $result['gkey'];
    
            // Atualizar o seal_nbr1
            $sqlUpdate = "
                UPDATE [N4DB].[dbo].[inv_unit]
                SET seal_nbr1 = :seal_nbr1
                WHERE gkey = :gkey";
    
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindValue(':seal_nbr1', $sealNbr1, PDO::PARAM_STR);
            $stmtUpdate->bindValue(':gkey', $unitGkey, PDO::PARAM_INT);
            $stmtUpdate->execute();
    
            return $this->response('Selo atualizado com sucesso.', 200);
    
        } catch (\Throwable $th) {
            return $this->error('Erro ao atualizar o selo.', 500, [$th->getMessage()]);
        }
    }

}