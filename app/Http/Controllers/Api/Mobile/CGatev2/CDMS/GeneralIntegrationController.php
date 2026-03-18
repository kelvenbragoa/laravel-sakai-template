<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\CDMS;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Exception;
use PDO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PDOException;

class GeneralIntegrationController extends Controller
{
    //
    use HttpResponses;
    //All appointment features on API's
    private static function conexao()
    {
        return DB::connection('sqlsrv2')->getPdo();
    }

    private static function conexao_n4()
    {
        return DB::connection('n4db')->getPdo();
    }

    public function seal_cross_check()
    {

    }

    public function trucking_companies ()
    {

    }

    public function iso_codes(string $id)
    {
        try {
            $sql = "SELECT id, visit_state, category, transit_state, iso 
                    FROM [dbo].[units_n4] 
                    WHERE visit_state = '1ACTIVE' 
                      AND id = :id";
    
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
    
            $resultados = [];
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row;
            }
    
            if (empty($resultados)) {
                return $this->error('Data not found, something went wrong.', 400);
            }
    
            return $this->response('Successful.', 200, $resultados);
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Data not found', 400, $messages);
        }
    }

    public function container_details(string $id)
    {
        try {
            $sql = "SELECT TOP 1
                        u.id as container_number,
                        s.shipping_line,
                        u.iso as container_type,
                        s.stack_order_number,
                        s.booking_number,
                        s.status,
                        u.visit_state,
                        u.category,
                        u.transit_state,
                        s.date_created
                    FROM 
                        [dbo].[units_n4] u
                    INNER JOIN 
                        cdms_commercial.stack_order_container s
                        ON u.id = s.container_number
                    WHERE 
                        u.visit_state = '1ACTIVE'
                        AND u.id = :id
                        AND s.status = 'Complete - Booking Created'
                    ORDER BY 
                        s.date_created DESC";
            
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$resultado) {
                return $this->error('Data not found, something went wrong.', 400);
            }

            return $this->response('Successful.', 200, $resultado);
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Data not found', 400, $messages);
        }
    }

    public function bill_of_ladings(string $billLadingNumber)
    {
        try {
            $sql = "SELECT * FROM bill_ladings_n4 WHERE bill_lading_number = :bill";
            
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindParam(':bill', $billLadingNumber, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($resultado)) {
                return $this->error('No bill of ladings found for this number.', 404);
            }

            return $this->response('Bill of ladings retrieved successfully.', 200, $resultado);
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Error retrieving bill of ladings.', 400, $messages);
        }
    }

    public function bookings(string $bookingNumber)
    {
        try {
            $sql = "SELECT * FROM bookings_agents_n4 WHERE booking_number = :booking";
            
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindParam(':booking', $bookingNumber, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($resultado)) {
                return $this->error('No bookings found for this number.', 404);
            }

            return $this->response('Bookings retrieved successfully.', 200, $resultado);
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Error retrieving bookings.', 400, $messages);
        }
    }


     //cdms import bill of ladings - Import Full Out -  [import_bill_of_lading]
     public function cdms_import_bill_of_ladings(Request $request)
     {
         try {
            $validated = $request->validate([
                'bill_lading_number' => 'required',
                'container_number'   => 'required',
                'line'               => 'required',
            ]);

             $billLadingNumber = $request->input('bill_lading_number');
             $containerNumber = $request->input('container_number');
             $line = $request->input('line');
     
             if (empty($billLadingNumber) && empty($containerNumber) && empty($line)) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Provide at least bill_lading_number or container_number or Shipping Line.'
                 ], 422);
             }
     
             $sql = "SELECT 
                         [bill_of_lading], 
                         [status] 
                     FROM [cdms_commercial].[cdms].[import_bill_of_lading] 
                     WHERE [status] = 'Complete'";

            //croscheck N4 VALIDATION TO CHECK IF BOOKING AND CONTAINER NUMBER EXISTS ON CDMS AND N4
            $sql_n4 = "SELECT top 1 
            equipment_id, bl_nbr, line_operator_id, iso_code, category, created 
            from argo_chargeable_unit_events 
            where category = 'IMPRT' AND bl_nbr = :bill AND equipment_id = :container AND line_operator_id = :shippingline";
     
             if (!empty($billLadingNumber)) {
                 $sql .= " AND [bill_of_lading] = :bill";
             }
     
            //  if (!empty($containerNumber)) {
            //      $sql .= " AND [container_number] = :container";
            //  }
     
             $stmt = self::conexao()->prepare($sql);
             $stmt_n4 = self::conexao_n4()->prepare($sql_n4);

     
             if (!empty($billLadingNumber)) {
                 $stmt->bindValue(':bill', $billLadingNumber , PDO::PARAM_STR);
                 $stmt_n4->bindValue(':bill', $billLadingNumber, PDO::PARAM_STR);

             }
     
             if (!empty($containerNumber)) {
                //  $stmt->bindValue(':container', $containerNumber, PDO::PARAM_STR);
                 $stmt_n4->bindValue(':container', $containerNumber, PDO::PARAM_STR);

             }

             if (!empty($line)) {
                //  $stmt->bindValue(':container', $containerNumber, PDO::PARAM_STR);
                 $stmt_n4->bindValue(':shippingline', $line, PDO::PARAM_STR);

             }
     
             $stmt->execute();
             $stmt_n4->execute();
     
             $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
             $resultado_n4 = $stmt_n4->fetchAll(PDO::FETCH_ASSOC);

             if (empty($resultado_n4)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching Bill Of Lading record found on N4.'
                ], 404);
            }
     
             if (empty($resultado)) {
                 return response()->json([
                     'success' => false,
                     'message' => 'No completed bill of lading found.'
                 ], 404);
             }
     
             return response()->json([
                 'success' => true,
                 'message' => 'Bill of lading retrieved successfully.',
                 'data' => $resultado
             ], 200);
     
         } 
        //  catch (ValidationException $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Validation failed.',
        //         'errors' => $e->errors(),
        //     ], 422);
        // }
          catch (\Throwable $th) {
             return response()->json([
                 'success' => false,
                 'message' => 'Error retrieving bill of lading.',
                 'error' => $th->getMessage()
             ], 400);
         }
     }
    //Stack Order Container (Export Full In) - [stack_order_container]
    public function cdms_stack_order_container(Request $request)
    {
       
        try {
            $request->validate(['booking_number' => 'required', 'container_number' => 'required']); 

            $bookingNumber = $request->input('booking_number');
            $containerNumber = $request->input('container_number');
    
            if (empty($bookingNumber) && empty($containerNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide at least booking_number or container_number.'
                ], 422);
            }

            //croscheck N4 VALIDATION TO CHECK IF BOOKING AND CONTAINER NUMBER EXISTS ON CDMS AND N4
            $sql_n4 = "SELECT TOP 1 equipment_id,
                    booking_nbr,
                    line_operator_id,
                    iso_code,
                    category
                from argo_chargeable_unit_events
                where category ='EXPRT'
                and booking_nbr = :booking and equipment_id = :container
                ";
    
            $sql = "SELECT TOP 1 
                        [booking_number], 
                        [shipping_line], 
                        [vessel_visit],
                        [container_number], 
                        [iso_code], 
                        [status]
                    FROM [cdms_commercial].[cdms_commercial].[stack_order_container]
                    WHERE 1=1 AND [status]='Complete - Booking Created'";
    
            // Constrói as condições conforme os parâmetros disponíveis
            if (!empty($bookingNumber)) {
                $sql .= " AND [booking_number] = :booking";
            }
    
            if (!empty($containerNumber)) {
                $sql .= " AND [container_number] = :container";
            }
    
            $stmt = self::conexao()->prepare($sql);
            $stmt_n4 = self::conexao_n4()->prepare($sql_n4);

    
            if (!empty($bookingNumber)) {
                $stmt->bindValue(':booking', $bookingNumber, PDO::PARAM_STR);
                $stmt_n4->bindValue(':booking', $bookingNumber, PDO::PARAM_STR);
            }
    
            if (!empty($containerNumber)) {
                $stmt->bindValue(':container', $containerNumber, PDO::PARAM_STR);
                $stmt_n4->bindValue(':container', $containerNumber, PDO::PARAM_STR);

            }
    
            $stmt->execute();
            $stmt_n4->execute();
    
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultado_n4 = $stmt_n4->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($resultado_n4)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching Booking record found on N4.'
                ], 404);
            }

            if (empty($resultado)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching stack order container record found.'
                ], 404);
            }



    
            return response()->json([
                'success' => true,
                'message' => 'Stack order container record retrieved successfully.',
                'data' => $resultado
            ], 200);
    
        }
        catch (\ValidationException $v){
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving stack order container record.',
                'error' => $v->getMessage()
            ], 400);
        }
         catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving stack order container record.',
                'error' => $th->getMessage()
            ], 400);
        }
    }

    //Empty In - empty_in_release_order - ero_number - status complete
    public function cdms_empty_in_release_order(Request $request)
    {
        try {
            $eroNumber = $request->input('ero_number');
            $containerNumber = $request->input('container_number');
    
            if (empty($eroNumber) && empty($containerNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide at least ero_number or container_number.'
                ], 422);
            }
    
            $sql = "SELECT TOP 1 
                        [line_operator], 
                        [container_number], 
                        [local_transit], 
                        [ero_number], 
                        [status] 
                    FROM [cdms_commercial].[cdms].[empty_in_release_order] 
                    WHERE [status] = 'Complete'";
            
            $sql_n4 = "SELECT TOP 1
                        inv_unit.id,
                        inv_unit.visit_state,
                        inv_eq_base_order.nbr,
                        inv_unit.category
                    FROM
                        inv_eq_base_order
                    LEFT JOIN
                        inv_eq_base_order_item
                        ON inv_eq_base_order.gkey = inv_eq_base_order_item.eqo_gkey
                    LEFT JOIN
                        inv_unit
                        ON inv_eq_base_order_item.gkey = inv_unit.arrive_order_item_gkey
                    WHERE
                        inv_eq_base_order.sub_type = 'ERO'
                        AND inv_unit.id = :container AND inv_eq_base_order.nbr = :ero";

    
            if (!empty($eroNumber)) {
                $sql .= " AND [ero_number] LIKE :ero";
            }
    
            if (!empty($containerNumber)) {
                $sql .= " AND [container_number] = :container";
            }
    
            $stmt = self::conexao()->prepare($sql);
            $stmt_n4 = self::conexao_n4()->prepare($sql_n4);

    
            if (!empty($eroNumber)) {
                $stmt->bindValue(':ero', '%'.$eroNumber.'%', PDO::PARAM_STR);
                $stmt_n4->bindValue(':ero', $eroNumber, PDO::PARAM_STR);

            }
    
            if (!empty($containerNumber)) {
                $stmt->bindValue(':container', $containerNumber, PDO::PARAM_STR);
                $stmt_n4->bindValue(':container', $containerNumber, PDO::PARAM_STR);

            }
    
            $stmt->execute();
            $stmt_n4->execute();

    
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultado_n4 = $stmt_n4->fetchAll(PDO::FETCH_ASSOC);

            if (empty($resultado_n4)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching Equipment Receive Order (ERO) record found on N4.'
                ], 404);
            }
    
            if (empty($resultado)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching ERO record found with status Complete.'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'ERO record retrieved successfully.',
                'data' => $resultado
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving ERO record.',
                'error' => $th->getMessage()
            ], 400);
        }
    }


    //Empty Out - empty_out_release_order - edo_number - status complete
    public function cdms_empty_out_release_order(Request $request)
    {
        try {
            $edoNumber = $request->input('edo_number');
            // $containerNumber = $request->input('container_number');
    
            if (empty($edoNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Provide at least edo_number or container_number.'
                ], 422);
            }
    
            $sql = "SELECT TOP 1 
                        [line_operator], 
                        [booking_number], 
                        [iso_type], 
                        [local_transit], 
                        [edo_number], 
                        [status]
                    FROM [cdms_commercial].[cdms].[empty_out_release_order]
                    WHERE [status] = 'Complete'";

            $sql_n4 = "SELECT TOP 1
            dbo.argo_chargeable_unit_events.equipment_id,
            dbo.argo_chargeable_unit_events.booking_nbr,
            dbo.inv_eq_base_order.nbr,
            dbo.argo_chargeable_unit_events.category,
            dbo.argo_chargeable_unit_events.iso_code,
            dbo.inv_eq_base_order.created,
            dbo.inv_eq_base_order.sub_type
            FROM dbo.inv_eq_base_order
            INNER JOIN dbo.inv_eq_base_order_item
            ON dbo.inv_eq_base_order.gkey = dbo.inv_eq_base_order_item.eqo_gkey
            INNER JOIN dbo.argo_chargeable_unit_events
            ON dbo.argo_chargeable_unit_events.booking_nbr = dbo.inv_eq_base_order.nbr
            where dbo.inv_eq_base_order.sub_type = 'EDO' AND dbo.inv_eq_base_order.nbr = :edo";
            // $sql_n4 = "SELECT TOP 1
            //         argo_chargeable_unit_events.equipment_id,
            //         argo_chargeable_unit_events.booking_nbr,
            //         inv_eq_base_order.nbr,
            //         argo_chargeable_unit_events.category,
            //         argo_chargeable_unit_events.iso_code,
            //         inv_eq_base_order.created,
            //         inv_eq_base_order.sub_type
            //     FROM inv_eq_base_order
            //     INNER JOIN inv_eq_base_order_item
            //         ON inv_eq_base_order.gkey = inv_eq_base_order_item.eqo_gkey
            //     INNER JOIN argo_chargeable_unit_events
            //         ON argo_chargeable_unit_events.booking_nbr = inv_eq_base_order.nbr
            //     where inv_eq_base_order.sub_type = 'EDO' AND inv_eq_base_order.nbr = :edo";
            
    
            if (!empty($edoNumber)) {
                $sql .= " AND [edo_number] = :edo";
            }
    
            // if (!empty($containerNumber)) {
            //     $sql .= " AND [container_number] LIKE :container";
            // }
    
            $stmt = self::conexao()->prepare($sql);
            $stmt_n4 = self::conexao_n4()->prepare($sql_n4);

            if (!empty($edoNumber)) {
                $stmt->bindValue(':edo', $edoNumber, PDO::PARAM_STR);
                $stmt_n4->bindValue(':edo', $edoNumber, PDO::PARAM_STR);

            }
    
            // if (!empty($containerNumber)) {
            //     $stmt->bindValue(':container', '%' . $containerNumber . '%', PDO::PARAM_STR);
            // }
    
            $stmt->execute();
            $stmt_n4->execute();

    
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $resultado_n4 = $stmt_n4->fetchAll(PDO::FETCH_ASSOC);

            if (empty($resultado_n4)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching Equipment Delivery Order (EDO) record found on N4.'
                ], 404);
            }

            if (empty($resultado)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No matching EDO record found with status Complete.'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'EDO record retrieved successfully.',
                'data' => $resultado
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving EDO record.',
                'error' => $th->getMessage()
            ], 400);
        }
    }
    
}
