<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;
use SimpleXMLElement;
use Illuminate\Support\Facades\Mail;
use App\Service\N4\SendMailService;
use Barryvdh\DomPDF\Facade\Pdf;




class N4DocumentsController extends Controller
{
    //
    protected SendMailService $sendMailService;

    public function __construct(SendMailService $sendMailService)
    {
        $this->sendMailService = $sendMailService;
    }

    private static function conexao()
    {
        $con = new PDO("sqlsrv:Server=10.0.4.26; Database=N4DB", "n4db", "S47#urn@09");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function get_delivery_note1(){
        return view('mails.delivery_note');
    }

    public function get_delivery_note()
    {
        $imagePath = public_path('cdm-logo.jpg');
        $imageBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($imagePath));
        $pdf = Pdf::loadView('mails.delivery_note',compact('document','imageBase64'))
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'dpi' => 96,
                'defaultFont' => 'Arial'
            ])
            ->setPaper([0, 0, 375, 841.89], 'portrait'); // 80mm x 297mm (papel térmico)
        
        return $pdf->stream('delivery_note_thermal.pdf');
    }

    public function sendMail()
    {
        // 1757311
        //1756917
        $this->sendMailService->sendDeliveryNoteEmail('1757498');
    }



    //PGI ENDPOINTS

    public function get_tvkey_xml_test_tid($tv_key)
    {
        try {
            // $sql = "SELECT TOP 6 doc_data FROM [N4DB].[dbo].[road_documents] WHERE tvdtls_gkey = '$tv_key' AND [N4DB].[dbo].[road_documents].[created] BETWEEN DATEADD(HOUR, -1, [N4DB].[dbo].[road_documents].[created]) AND [N4DB].[dbo].[road_documents].[created] ORDER BY [N4DB].[dbo].[road_documents].[created] DESC";
            $sql = "SELECT doc_data FROM [N4DB].[dbo].[road_documents] WHERE tvdtls_gkey = '$tv_key'";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            // $resultados = array();

            // while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            //     $resultados = $row;
            // }

            $resultados = [];

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row; // adiciona cada linha ao array
            }

            


            if (!$resultados) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'No results found',
                        'result' => [],
                    ],
                    404
                );
            }

            $tid_doc = [];
            $dn_doc = [];

            foreach ($resultados as $key) {
               
           



            $xml = new SimpleXMLElement($key['doc_data']);
            // Definir namespaces
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('argo', $namespaces['argo']);

            // Extrair dados da visita do caminhão
            $truckVisit = $xml->xpath('//argo:truckVisit')[0];
            $truckVisitData = [
                'truckLicense' => (string) $truckVisit->tvdtlsLicNbr,
                'truckVisitId' => (string) $truckVisit->tvdtlsTvKey,
                'truckCompanyName' => (string) $truckVisit->tvdtlsTrkCompanyName,
                'driverName' => (string) $truckVisit->tvdtlsDriverName,
                'driverLicense' => (string) $truckVisit->tvdtlsDriverLicenseNbr,
                'startTime' => (string) $truckVisit->tvdtlsTrkStartTime,
                'endTime' => (string) $truckVisit->tvdtlsTrkEndTime,
                'nextStage' => (string) $truckVisit->tvdtlsNextStageId,
                'yardCompletionTime' => (string) $truckVisit->tvdtlsYardCompletionTime,
                'appointmentNumber' => (string) $truckVisit->tvdtlsAppointmentNbr,
                'isHazard' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                'requiredStages' => []
            ];

            foreach ($truckVisit->xpath('argo:tvdtlsRequiredStages') as $stage) {
                $truckVisitData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageOrder' => (string) $stage->stageOrder,
                    'stageDescription' => (string) $stage->stageDescription
                ];
            }
            // Extrair dados da transação
            $transaction = $xml->xpath('//argo:trkTransaction')[0];

            $transactionData = [
                'transactionNumber' => (string) $transaction->tranNbr,
                'subType' => (string) $transaction->tranSubType,
                'status' => (string) $transaction->tranStatus,
                'createdDateTime' => (string) $transaction->tranCreated,
                'creator' => (string) $transaction->tranCreator,
                'changedDateTime' => (string) $transaction->tranChanged,
                'changer' => (string) $transaction->tranChanger,
                'transactionStage' => (string) $transaction->tranStageId,
                'nextTransactionStage' => (string) $transaction->tranNextStageId,
                'appointmentNumber' => (string) $transaction->tranAppointmentNbr,
                'containerNumber' => (string) $transaction->tranCtrNbr,
                'grossWeight' => (float) $transaction->tranCtrGrossWeight,
                'tareWeight' => (float) $transaction->tranCtrTareWeight,
                'isSealed' => (string) $transaction->tranCtrIsSealed,
                'origin' => (string) $transaction->tranOrigin,
                'description' => (string) $transaction->tranCommodityDescription,
                'sealNumber' => (string) $transaction->tranSealNbr1,
                'requiresVentilation' => (string) $transaction->tranVentRequired,
                'requiresXray' => (string) $transaction->tranIsXrayRequired,
                'startTime' => (string) $transaction->tranStartTime,
                'endTime' => (string) $transaction->tranEndTime,
                'requiredStages' => []
            ];

            foreach ($transaction->xpath('argo:tranRequiredStages') as $stage) {
                $transactionData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageSequence' => (string) $stage->stageSequence,
                    'stageDescription' => (string) $stage->stageDescription,
                    'stageStartTime' => (string) $stage->stageStartTime
                ];
            }




            $docDescription = $xml->xpath('//argo:docDescription')[0] ?? null;

            $doctranCtrPosition = $xml->xpath('//argo:tranCtrPosition')[0] ?? null;

            $doctrkTransaction = $xml->xpath('//argo:trkTransaction')[0] ?? null;

            $messageNode = $xml->xpath('//argo:Messages/argo:Message/message')[0] ?? null;
            $errorKeyNode = $xml->xpath('//argo:Messages/argo:Message/errKey')[0] ?? null;
            $severityNode = $xml->xpath('//argo:Messages/argo:Message/severity')[0] ?? null;



            $documentName = $docDescription ? (string)$docDescription->docName : null;
            $truckPlannedPosition = $doctranCtrPosition ? (string)$doctranCtrPosition : null;
            $isoCode = $doctrkTransaction ? (string)$doctrkTransaction->tranCtrTypeId : null;


            $message = $messageNode ? (string)$messageNode : null;
            $errorKey = $errorKeyNode ? (string)$errorKeyNode : null;
            $severity = $severityNode ? (string)$severityNode : null;



            $bookingNumber = $xml->xpath('//argo:tranUnitBls')[0] ?? NULL;
           // dd($bookingNumber);

           if($documentName === "TROUBLE"){
                $tid = [
                        'document_type' =>"TID",
                        'user' => 'Admin',
                        'operation_date' => date("Y-m-d H:i:s"),
                        'transaction_number' => $transactionData['transactionNumber'], 
                        'transaction_type' => $transactionData['subType'], 
                        'location'  => $truckPlannedPosition,
                        'iso_code' => $isoCode,
                        'trucking_company' => $truckVisitData['truckCompanyName'],
                        'license_plate' => $truckVisitData['truckLicense'],
                        'driver_name' => $truckVisitData['driverName'],
                        'driver_license' => $truckVisitData['driverLicense'],
                        'status' => "TROUBLE",
                        'message'=> $severity.' '.$message,
                        
                    ];

                    $tid_doc[] = $tid;
           }

            if ($documentName === "TID") {
                $tid = [
                    'document_type' => "TID",
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'transaction_type' => $transactionData['subType'], 
                    'location'  => $truckPlannedPosition,
                    'iso_code' => $isoCode,
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'license_plate' => $truckVisitData['truckLicense'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense']
                ];

                $tid_doc[] = $tid;

                // return response()->json(
                //     [
                //         'error' => [],
                //         'message' => '',
                //         'result' => $tid,
                //     ],
                //     200
                // );
              
            }else{
               
                $bookingNumber = $xml->xpath('//argo:tranUnitBls')[0] ?? NULL;
                if ($bookingNumber == null){
                    $xml->registerXPathNamespace('argo', $namespaces['argo']);
                    $bookingNumber = (string) $xml->xpath('//argo:tranEqo/eqboNbr')[0];
                }else{
                    $bookingNumber = (string) $bookingNumber->blNbr ?? NULL;
                }

                $trkTransaction = $xml->xpath('//argo:trkTransaction')[0];
                $line = (string) $trkTransaction->tranLineId;
                $destination = (string) $trkTransaction->tranDestination;
                $origin = (string) $trkTransaction->tranOrigin;

                $tranCarrierVisit = $xml->xpath('//argo:tranCarrierVisit')[0] ?? null;
                // dd($tranCarrierVisit);
                if($tranCarrierVisit){
                    $vessel = (string) $tranCarrierVisit->cvCvdCarrierVehicleName ?? NULL;
                    $voyageNumber = (string) $tranCarrierVisit->cvCvdCarrierIbVygNbr ?? null;
                }

                

            
                $grossWeight = (float) $transactionData['grossWeight'];
                $tareWeight = (float) $transactionData['tareWeight'];

                $weight = $grossWeight-$tareWeight;

                $delivery_node = [
                    'document_type' => 'DN',
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_type' => $transactionData['subType'], 
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'bookink_number' => $bookingNumber,
                    'container_number' => $transactionData['containerNumber'],
                    'line' => $line,
                    'seal_number_1' => $transactionData['sealNumber'],
                    'vessel' => $vessel ?? null,
                    'voyage' => $voyageNumber ?? null,
                    'imdg' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                    'iso_code' => $isoCode,
                    'destination' => $destination,
                    'origin' => $origin,
                    'weighth' => $weight,
                    'position'  => $truckPlannedPosition,
                    'license_plate' => $truckVisitData['truckLicense'],
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense'] 

                ];

                $dn_doc[] = $delivery_node;

                // return response()->json(
                //     [
                //         'error' => [],
                //         'message' => '',
                //         'result' =>[ 
                //             $delivery_node,
                //         ],
                //     ],
                //     200
                // );
            }
              
            }

            return response()->json(
                [
                    'error'=> [],
                    'message'=> '',
                    'result'=> $tid_doc
                    
                ]
                    );
           
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => [],
                    'message' => $th->getMessage(),
                    'result' => [],
                ],
                404
            );
        }
    }

        public function get_tvkey_xml_test($tv_key)
    {
        try {
            // $sql = "SELECT TOP 6 doc_data FROM [N4DB].[dbo].[road_documents] WHERE tvdtls_gkey = '$tv_key' AND [N4DB].[dbo].[road_documents].[created] BETWEEN DATEADD(HOUR, -1, [N4DB].[dbo].[road_documents].[created]) AND [N4DB].[dbo].[road_documents].[created] ORDER BY [N4DB].[dbo].[road_documents].[created] DESC";
            $sql = "SELECT doc_data FROM [N4DB].[dbo].[road_documents] WHERE tvdtls_gkey = '$tv_key'";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            // $resultados = array();

            // while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            //     $resultados = $row;
            // }

            $resultados = [];

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row; // adiciona cada linha ao array
            }

            


            if (!$resultados) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'No results found',
                        'result' => [],
                    ],
                    404
                );
            }

            $tid_doc = [];
            $dn_doc = [];

            foreach ($resultados as $key) {
               
           



            $xml = new SimpleXMLElement($key['doc_data']);
            // Definir namespaces
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('argo', $namespaces['argo']);

            // Extrair dados da visita do caminhão
            $truckVisit = $xml->xpath('//argo:truckVisit')[0];
            $truckVisitData = [
                'truckLicense' => (string) $truckVisit->tvdtlsLicNbr,
                'truckVisitId' => (string) $truckVisit->tvdtlsTvKey,
                'truckCompanyName' => (string) $truckVisit->tvdtlsTrkCompanyName,
                'driverName' => (string) $truckVisit->tvdtlsDriverName,
                'driverLicense' => (string) $truckVisit->tvdtlsDriverLicenseNbr,
                'startTime' => (string) $truckVisit->tvdtlsTrkStartTime,
                'endTime' => (string) $truckVisit->tvdtlsTrkEndTime,
                'nextStage' => (string) $truckVisit->tvdtlsNextStageId,
                'yardCompletionTime' => (string) $truckVisit->tvdtlsYardCompletionTime,
                'appointmentNumber' => (string) $truckVisit->tvdtlsAppointmentNbr,
                'isHazard' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                'requiredStages' => []
            ];

            foreach ($truckVisit->xpath('argo:tvdtlsRequiredStages') as $stage) {
                $truckVisitData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageOrder' => (string) $stage->stageOrder,
                    'stageDescription' => (string) $stage->stageDescription
                ];
            }
            // Extrair dados da transação
            $transaction = $xml->xpath('//argo:trkTransaction')[0];

            $transactionData = [
                'transactionNumber' => (string) $transaction->tranNbr,
                'subType' => (string) $transaction->tranSubType,
                'status' => (string) $transaction->tranStatus,
                'createdDateTime' => (string) $transaction->tranCreated,
                'creator' => (string) $transaction->tranCreator,
                'changedDateTime' => (string) $transaction->tranChanged,
                'changer' => (string) $transaction->tranChanger,
                'transactionStage' => (string) $transaction->tranStageId,
                'nextTransactionStage' => (string) $transaction->tranNextStageId,
                'appointmentNumber' => (string) $transaction->tranAppointmentNbr,
                'containerNumber' => (string) $transaction->tranCtrNbr,
                'grossWeight' => (float) $transaction->tranCtrGrossWeight,
                'tareWeight' => (float) $transaction->tranCtrTareWeight,
                'isSealed' => (string) $transaction->tranCtrIsSealed,
                'origin' => (string) $transaction->tranOrigin,
                'description' => (string) $transaction->tranCommodityDescription,
                'sealNumber' => (string) $transaction->tranSealNbr1,
                'requiresVentilation' => (string) $transaction->tranVentRequired,
                'requiresXray' => (string) $transaction->tranIsXrayRequired,
                'startTime' => (string) $transaction->tranStartTime,
                'endTime' => (string) $transaction->tranEndTime,
                'requiredStages' => []
            ];

            foreach ($transaction->xpath('argo:tranRequiredStages') as $stage) {
                $transactionData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageSequence' => (string) $stage->stageSequence,
                    'stageDescription' => (string) $stage->stageDescription,
                    'stageStartTime' => (string) $stage->stageStartTime
                ];
            }




            $docDescription = $xml->xpath('//argo:docDescription')[0];
            $doctranCtrPosition = $xml->xpath('//argo:tranCtrPosition')[0];
            $doctrkTransaction = $xml->xpath('//argo:trkTransaction')[0];

            $documentName = (string)$docDescription->docName;
            $truckPlannedPosition = (string)$doctranCtrPosition->posLocId;
            $isoCode = (string)$doctrkTransaction->tranCtrTypeId;

            $bookingNumber = $xml->xpath('//argo:tranUnitBls')[0] ?? NULL;
           // dd($bookingNumber);

            if ($documentName === "TID") {
                $tid = [
                    'document_type' => "TID",
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'transaction_type' => $transactionData['subType'], 
                    'location'  => $truckPlannedPosition,
                    'iso_code' => $isoCode,
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'license_plate' => $truckVisitData['truckLicense'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense']
                ];

                $tid_doc[] = $tid;

                // return response()->json(
                //     [
                //         'error' => [],
                //         'message' => '',
                //         'result' => $tid,
                //     ],
                //     200
                // );
              
            }else{
               
                $bookingNumber = $xml->xpath('//argo:tranUnitBls')[0] ?? NULL;
                if ($bookingNumber == null){
                    $xml->registerXPathNamespace('argo', $namespaces['argo']);
                    $bookingNumber = (string) $xml->xpath('//argo:tranEqo/eqboNbr')[0];
                }else{
                    $bookingNumber = (string) $bookingNumber->blNbr ?? NULL;
                }

                $trkTransaction = $xml->xpath('//argo:trkTransaction')[0];
                $line = (string) $trkTransaction->tranLineId;
                $destination = (string) $trkTransaction->tranDestination;
                $origin = (string) $trkTransaction->tranOrigin;

                $tranCarrierVisit = $xml->xpath('//argo:tranCarrierVisit')[0] ?? null;
                // dd($tranCarrierVisit);
                if($tranCarrierVisit){
                    $vessel = (string) $tranCarrierVisit->cvCvdCarrierVehicleName ?? NULL;
                    $voyageNumber = (string) $tranCarrierVisit->cvCvdCarrierIbVygNbr ?? null;
                }

                

            
                $grossWeight = (float) $transactionData['grossWeight'];
                $tareWeight = (float) $transactionData['tareWeight'];

                $weight = $grossWeight-$tareWeight;

                $delivery_node = [
                    'document_type' => 'DN',
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_type' => $transactionData['subType'], 
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'bookink_number' => $bookingNumber,
                    'container_number' => $transactionData['containerNumber'],
                    'line' => $line,
                    'seal_number_1' => $transactionData['sealNumber'],
                    'vessel' => $vessel ?? null,
                    'voyage' => $voyageNumber ?? null,
                    'imdg' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                    'iso_code' => $isoCode,
                    'destination' => $destination,
                    'origin' => $origin,
                    // 'weighth' => $weight,
                    'weighth' => $grossWeight,
                    'position'  => $truckPlannedPosition,
                    'license_plate' => $truckVisitData['truckLicense'],
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense'],
                    // 'weighth1' => $grossWeight,
                    // 'weighth2' => $tareWeight,


                ];

                $dn_doc[] = $delivery_node;

                // return response()->json(
                //     [
                //         'error' => [],
                //         'message' => '',
                //         'result' =>[ 
                //             $delivery_node,
                //         ],
                //     ],
                //     200
                // );
            }
              
            }

            return response()->json(
                [
                    'error'=> [],
                    'message'=> '',
                    'result'=> $dn_doc
                    
                ]
                    );
           
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => [],
                    'message' => $th->getMessage(),
                    'result' => [],
                ],
                404
            );
        }
    }

        public function get_xml_doc(Request $request)
    {
        // ESTE AQUI
        $container_number = $request->container_number;
        try {
            // $sql = "SELECT TOP 1 doc_data FROM [N4DB].[dbo].[road_documents] WHERE ctr_id = '$container_number' AND creator like '%Admin%' AND [N4DB].[dbo].[road_documents].[created] BETWEEN DATEADD(HOUR, -1, [N4DB].[dbo].[road_documents].[created]) AND [N4DB].[dbo].[road_documents].[created] ORDER BY [N4DB].[dbo].[road_documents].[created] DESC";
            $sql = "SELECT TOP 1 doc_data FROM [N4DB].[dbo].[road_documents] WHERE creator like '%Admin%' AND [N4DB].[dbo].[road_documents].[created] BETWEEN DATEADD(HOUR, -1, [N4DB].[dbo].[road_documents].[created]) AND [N4DB].[dbo].[road_documents].[created] ORDER BY [N4DB].[dbo].[road_documents].[created] DESC";
            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            $resultados = array();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados = $row;
            }

            if (!$resultados) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'No results found',
                        'result' => [],
                    ],
                    404
                );
            }
            $xml = new SimpleXMLElement($resultados['doc_data']);
            // Definir namespaces
            $namespaces = $xml->getNamespaces(true);
            $xml->registerXPathNamespace('argo', $namespaces['argo']);

            // Extrair dados da visita do caminhão
            $truckVisit = $xml->xpath('//argo:truckVisit')[0];
        
            $truckVisitData = [
                'truckLicense' => (string) $truckVisit->tvdtlsLicNbr,
                'truckVisitId' => (string) $truckVisit->tvdtlsTvKey,
                'truckCompanyName' => (string) $truckVisit->tvdtlsTrkCompanyName,
                'driverName' => (string) $truckVisit->tvdtlsDriverName,
                'driverLicense' => (string) $truckVisit->tvdtlsDriverLicenseNbr,
                'startTime' => (string) $truckVisit->tvdtlsTrkStartTime,
                'endTime' => (string) $truckVisit->tvdtlsTrkEndTime,
                'nextStage' => (string) $truckVisit->tvdtlsNextStageId,
                'yardCompletionTime' => (string) $truckVisit->tvdtlsYardCompletionTime,
                'appointmentNumber' => (string) $truckVisit->tvdtlsAppointmentNbr,
                'isHazard' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                'requiredStages' => []
            ];

            foreach ($truckVisit->xpath('argo:tvdtlsRequiredStages') as $stage) {
                $truckVisitData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageOrder' => (string) $stage->stageOrder,
                    'stageDescription' => (string) $stage->stageDescription
                ];
            }
            // Extrair dados da transação
            $transaction = $xml->xpath('//argo:trkTransaction')[0];

            $transactionData = [
                'transactionNumber' => (string) $transaction->tranNbr,
                'subType' => (string) $transaction->tranSubType,
                'status' => (string) $transaction->tranStatus,
                'createdDateTime' => (string) $transaction->tranCreated,
                'creator' => (string) $transaction->tranCreator,
                'changedDateTime' => (string) $transaction->tranChanged,
                'changer' => (string) $transaction->tranChanger,
                'transactionStage' => (string) $transaction->tranStageId,
                'nextTransactionStage' => (string) $transaction->tranNextStageId,
                'appointmentNumber' => (string) $transaction->tranAppointmentNbr,
                'containerNumber' => (string) $transaction->tranCtrNbr,
                'grossWeight' => (float) $transaction->tranCtrGrossWeight,
                'tareWeight' => (float) $transaction->tranCtrTareWeight,
                'isSealed' => (string) $transaction->tranCtrIsSealed,
                'origin' => (string) $transaction->tranOrigin,
                'description' => (string) $transaction->tranCommodityDescription,
                'sealNumber' => (string) $transaction->tranSealNbr1,
                'requiresVentilation' => (string) $transaction->tranVentRequired,
                'requiresXray' => (string) $transaction->tranIsXrayRequired,
                'startTime' => (string) $transaction->tranStartTime,
                'endTime' => (string) $transaction->tranEndTime,
                'requiredStages' => []
            ];

            foreach ($transaction->xpath('argo:tranRequiredStages') as $stage) {
                $transactionData['requiredStages'][] = [
                    'stageId' => (string) $stage->stageId,
                    'stageSequence' => (string) $stage->stageSequence,
                    'stageDescription' => (string) $stage->stageDescription,
                    'stageStartTime' => (string) $stage->stageStartTime
                ];
            }
            $transactionBooking = $xml->xpath('//argo:tranEqo')[0];
            $transactionBooking = (string)$transactionBooking->eqboNbr[0];
            // dd($transactionBooking);

            

            $docDescription = $xml->xpath('//argo:docDescription')[0];
            $doctranCtrPosition = $xml->xpath('//argo:tranCtrPosition')[0];
            $doctrkTransaction = $xml->xpath('//argo:trkTransaction')[0];

            $documentName = (string)$docDescription->docName;
            $truckPlannedPosition = (string)$doctranCtrPosition->posLocId;
            $isoCode = (string)$doctrkTransaction->tranCtrTypeId;

            if ($documentName === "TID") {
                $tid = [
                    'document_type' => "TID",
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'transaction_type' => $transactionData['subType'], 
                    'location'  => $truckPlannedPosition,
                    'iso_code' => $isoCode,
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'license_plate' => $truckVisitData['truckLicense'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense']
                ];

                //email sending function
                $pdf = PDF::loadView('pdf_template.tid', ['tid' => $tid])->output();
                $recipient = 'gerson.houane@cornelder.co.mz';

                Mail::send('mails_template.tid_mail', ['tid' => $tid], function ($m) use ($recipient, $pdf, $tid) {
                    $m->from('noreply@cornelder.co.mz', 'C-GATE 2.0');
                    $m->to($recipient)->cc(['kelven.bragoa@cornelder.co.mz','keven.goncalves@cornelder.co.mz','atumane.momade@cornelder.co.mz','ivan.goncalves@cornelder.co.mz','artur.muguiviza@cornelder.co.mz', 'gateout@cornelder.co.mz'])->subject('C-GATE 2.0');
                    $m->to($recipient)->subject('C-GATE 2.0');
                    $m->attachData($pdf, 'TID' . ' - ' . $tid['operation_date'] . '-.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                });
                
                return response()->json(
                    [
                        'error' => [],
                        'message' => '',
                        'result' => $tid,
                    ],
                    200
                );
              
            }else{
                $bookingNumber = $transactionBooking;
                // $bookingNumber = $xml->xpath('//argo:tranUnitBls')[0] ?? NULL;
                // if ($bookingNumber == null){
                //     $xml->registerXPathNamespace('argo', $namespaces['argo']);
                //     $bookingNumber = (string) $xml->xpath('//argo:tranEqo/eqboNbr')[0];
                // }else{
                //     $bookingNumber = (string) $bookingNumber->eqboNbr ?? NULL;
                // }
                // eqboNbr

                $trkTransaction = $xml->xpath('//argo:trkTransaction')[0];
                $line = (string) $trkTransaction->tranLineId;
                $destination = (string) $trkTransaction->tranDestination;
                $origin = (string) $trkTransaction->tranOrigin;

                $tranCarrierVisit = $xml->xpath('//argo:tranCarrierVisit')[0] ?? null;
                if ($tranCarrierVisit == null) {
                    $vessel = "";
                    $voyageNumber = "";
                }else{
                    $vessel = (string) $tranCarrierVisit->cvCvdCarrierVehicleName ?? NULL;
                    $voyageNumber = (string) $tranCarrierVisit->cvCvdCarrierIbVygNbr ?? NuLL;
                }
            
                $grossWeight = (float) $transactionData['grossWeight'];
                $tareWeight = (float) $transactionData['tareWeight'];

                $weight = $grossWeight;

                $delivery_node = [
                    'document_type' => 'DN',
                    'user' => 'Admin',
                    'operation_date' => date("Y-m-d H:i:s"),
                    'transaction_type' => $transactionData['subType'], 
                    'transaction_number' => $transactionData['transactionNumber'], 
                    'bookink_number' => $bookingNumber,
                    'container_number' => $transactionData['containerNumber'],
                    'line' => $line,
                    'seal_number_1' => $transactionData['sealNumber'],
                    'vessel' => $vessel,
                    'voyage' => $voyageNumber,
                    'imdg' => (string) $truckVisit->xpath('//tranIsHazard')[0],
                    'iso_code' => $isoCode,
                    'destination' => $destination,
                    'origin' => $origin,
                    'weighth' => $weight,
                    'position'  => $truckPlannedPosition,
                    'license_plate' => $truckVisitData['truckLicense'],
                    'trucking_company' => $truckVisitData['truckCompanyName'],
                    'driver_name' => $truckVisitData['driverName'],
                    'driver_license' => $truckVisitData['driverLicense'] 

                ];


                //sending email
                $pdf = PDF::loadView('pdf_template.delivery_note', ['dn' => $delivery_node])->output();
                $recipient = 'gerson.houane@cornelder.co.mz';

                Mail::send('mails_template.delivery_note_mail', ['dn' => $delivery_node], function ($m) use ($recipient, $pdf, $delivery_node) {
                    $m->from('noreply@cornelder.co.mz', 'C-GATE 2.0');
                    $m->to($recipient)->cc(['kelven.bragoa@cornelder.co.mz','keven.goncalves@cornelder.co.mz','atumane.momade@cornelder.co.mz','ivan.goncalves@cornelder.co.mz','artur.muguiviza@cornelder.co.mz', 'gateout@cornelder.co.mz'])->subject('C-GATE 2.0');
                    $m->to($recipient)->subject('C-GATE 2.0');
                    $m->attachData($pdf, 'DELIVERY NOTE' . ' - ' . $delivery_node['operation_date']. '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
                });
                return response()->json(
                    [
                        'error' => [],
                        'message' => '',
                        'result' =>[ 
                            $delivery_node,
                            $transactionData, 
                            $truckVisitData
                        ],
                    ],
                    200
                );
              
            }
           
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'error' => [],
                    'message' => $th->getMessage(),
                    'result' => [],
                ],
                404
            );
        }
    }

    public function send_appointment_email(Request $request)
    {
        $send_to = $request->send_to ?? 'gerson.houane@cornelder.co.mz';
        $appointment_number = $request->appointment_number;

        $send_email = Mail::send('appointments.created', ['appointment_number' => $appointment_number], function ($m) use ($send_to, $appointment_number) {
            $m->from('codigo.memo@cornelder.co.mz', 'C-Gate 2.0 Notification - Appointment creation #' . $appointment_number);
            $m->to($send_to)->cc(['kelven.bragoa@cornelder.co.mz','keven.goncalves@cornelder.co.mz','atumane.momade@cornelder.co.mz','ivan.goncalves@cornelder.co.mz','artur.muguiviza@cornelder.co.mz', 'gateout@cornelder.co.mz'])->subject('C-GATE 2.0 Appointment creation. Appointment Number - #' . $appointment_number);
            // $m->to($send_to)->subject('Appointment creation. Appointment Number - #' . $appointment_number);
        });

        return response()->json(
            [
                'error' => [],
                'message' => 'email sent succefully',
                'result' => [],
            ],
            200
        );
    }
}
