<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\N4;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use PDO;
use Exception;

class CGateAppointmentController extends Controller
{
    //
    use HttpResponses;
    //All appointment features on API's
    private static function conexao()
    {
        $con = new PDO("sqlsrv:Server=10.0.4.46; Database=cdms_commercial", "cdms2", "shD?fk9PGEqpw&3n");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    private static function conexaon4()
    {
        // $con = new PDO("sqlsrv:Server=10.0.4.26; Database=N4DB", "n4db", "P0rt0$");
        $conn4 = new PDO("sqlsrv:Server=10.0.4.26; Database=N4DB", "n4db", "S47#urn@09");
        $conn4->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn4;
    }

    public function appointments()
    {
        try {
            $sql = "SELECT TOP 100 * FROM [cdms_commercial].[cdms_commercial].[preadvise] ORDER BY number DESC";
            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row;
            }

            if (!$resultados) {
                return $this->error('Data not found, something get wrong.', 400,);
            }
            return $this->response('Successfull.', 200, $resultados);
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Data not found', 400, $messages);
        }
    }

    public function check_appointment_by_number(Request $request)
    {
        try {
            $number = $request->appointment_number;

            // $sql = "SELECT * FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] ='$number'";
            $sql = "SELECT TOP 1 p.*, t.id as 'trucking_company_id' FROM [cdms_commercial].[cdms_commercial].[preadvise] AS p LEFT JOIN [cdms_commercial].[dbo].[trucking_company_n4] AS t ON p.[trucking_company] = t.[name] WHERE p.[number] = '$number';";
            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row;
            }

            if (!$resultados) {
                return $this->error('Data not found, something get wrong.', 400,);
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => '',
                    'result' => $resultados,
                ],
                200
            );
        } catch (\Throwable $th) {
            $messages[] = $th->getMessage();
            return $this->error('Data not found, something get wrong.', 400, $messages);
        }
    }

    public function check_appointment_by_container_number(Request $request)
    {
        try {
            $container_number = $request->container_number;
            // $sql = "SELECT TOP 1 * FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[container_number] = '$container_number' ORDER BY [cdms_commercial].[cdms_commercial].[preadvise].[date_updated] DESC";
            $sql = "SELECT TOP 1 p.*, t.id as 'trucking_company_id' FROM [cdms_commercial].[cdms_commercial].[preadvise] AS p LEFT JOIN [cdms_commercial].[dbo].[trucking_company_n4] AS t ON p.[trucking_company] = t.[name] WHERE p.[container_number] = '$container_number' ORDER BY p.[number] DESC;";
            
            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            $resultados = array();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row;
            }

            if (!$resultados) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'gfhgf',
                        'result' => [],
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => '',
                    'result' => $resultados,
                ],
                200
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

    public function check_appointment_by_license_plate_number(Request $request)
    {
        try {
            $license_plate_number = $request->license_plate_number;
            $sql = "SELECT TOP 1 p.*, t.id as 'trucking_company_id' FROM [cdms_commercial].[cdms_commercial].[preadvise] AS p LEFT JOIN [cdms_commercial].[dbo].[trucking_company_n4] AS t ON p.[trucking_company] = t.[name] WHERE p.[truck_license_number] LIKE '%$license_plate_number%' ORDER BY p.[number] DESC;";
            
            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            $resultados = array();

            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                $resultados[] = $row;
            }

            if (!$resultados) {
                return response()->json(
                    [
                        'error' => [],
                        'message' => 'No Result Found',
                        'result' => [],
                    ],
                    404
                );
            }
            return response()->json(
                [
                    'error' => [],
                    'message' => '',
                    'result' => $resultados,
                ],
                200
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

    public function check_dummy_container_available(Request $request)
    {
        try {
            // Obtém os valores do request com fallback para valores padrão
            $visit_state = $request->visit_state ?? '1ACTIVE';
            $iso = $request->iso ?? '22G1';
            $id = $request->id ?? 'DMY';
            $transit_state = $request->transit_state ?? 'YARD';
        
            // Garante que 'DMY' esteja presente no ID
            if (!str_contains($id, 'DMY')) {
                $id .= 'DMY';
            }
        
            // Define a query com placeholders
            $sql = "SELECT [id], [iso]  FROM [dbo].[units_n4] 
                    WHERE [visit_state] = :visit_state 
                      AND [iso] = :iso 
                      AND [id] LIKE :id
                      AND [transit_state] LIKE :transit_state";
        
            // Prepara e executa a query
            $stmt = self::conexao()->prepare($sql);
            $stmt->bindValue(':visit_state', $visit_state, PDO::PARAM_STR);
            $stmt->bindValue(':iso', $iso, PDO::PARAM_STR);
            $stmt->bindValue(':id', "%$id%", PDO::PARAM_STR); // LIKE dinâmico para ID
            $stmt->bindValue(':transit_state', "%$transit_state%", PDO::PARAM_STR); // LIKE dinâmico para transit_state
            $stmt->execute();
        
            // Obtém os resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            // Retorna resposta apropriada
            if (empty($resultados)) {
                return $this->error('Data not found, something went wrong.', 400);
            }
        
            return $this->response('Successful.', 200, $resultados);
        
        } catch (\Throwable $th) {
            return $this->error('Data not found', 400, [$th->getMessage()]);
        }
    }

    //n4 CDMS SUPPLIER
    /**
     * Checar impedimentos para um container.
     * POST /n4/checkImpediments
     */
    public function checkImpediments(Request $request)
    {
        
        if ($request->has('container_number')) {
            $value = $request->input('container_number');
            $sql = "SELECT stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '1ACTIVE'";
            //$sql = "SELECT stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '1ACTIVE'  OR visit_state = '2ADVISED'";
        }


        $sql = self::conexaon4()->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            return response()->json([
                "error"=>"Nenhum resultado"
            ],404);
            throw new Exception("Nenhum resultado");
        }


        return $resultados;
    }

    public function checkImpedimentsEmptyIn()
    {
        if (isset($_POST)) {
            $value = $_POST['container_number'];
            // $sql = "SELECT TOP 1 stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED' OR  visit_state = '1ACTIVE'";
            // $sql = "SELECT stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED'";
            $sql = "SELECT TOP 1 stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED' OR  visit_state = '1ACTIVE' ORDER BY gkey DESC";

        }


        $sql = self::conexaon4()->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            return response()->json([
                "error"=>"Nenhum resultado"
            ],404);
            throw new Exception("Nenhum resultado");
        }


        return $resultados;
    }

    public function checkImpedimentsEmptyInTest()
    {
        if (isset($_POST)) {
            $value = $_POST['container_number'];
            $sql = "SELECT TOP 1 stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED' OR  visit_state = '1ACTIVE' ORDER BY gkey DESC";
            // $sql = "SELECT TOP 1 stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED' OR  visit_state = '1ACTIVE'";
            // $sql = "SELECT stopped_vessel, stopped_rail, stopped_road, imped_rail, imped_vessel, imped_road  FROM inv_unit WHERE id = '$value' AND visit_state = '2ADVISED'";
        }


        $sql = self::conexaon4()->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            return response()->json([
                "error"=>"Nenhum resultado"
            ],404);
            throw new Exception("Nenhum resultado");
        }


        return $resultados;
    }

    public function checkHazardous(Request $request)
    {
        if ($request->has('container_number')) {
            $value = $request->input('container_number');
            $sql = "SELECT INVU.gkey, INVU.goods, INVU.id, INVU.visit_state, INVG.hazardous FROM inv_unit AS INVU INNER JOIN inv_goods AS INVG ON INVU.goods = INVG.gkey WHERE INVU.id = '$value' AND INVU.visit_state = '1ACTIVE' AND INVG.hazardous = '1'";
        }
        $sql = self::conexaon4()->prepare($sql);
        $sql->execute();
        $resultados = array();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }
        if (!$resultados) {
            return response()->json([
                "error"=>"Nenhum resultado"
            ],404);
            throw new Exception("Nenhum resultado");
        }
        return $resultados;
    }


    public function holds()
    {
        $sql = "SELECT TOP 100 * FROM inv_unit WHERE visit_state = '1ACTIVE'";

        $sql = self::conexaon4()->prepare($sql);
        $sql->execute();

        $resultados = array();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $resultados[] = $row;
        }

        if (!$resultados) {
            return response()->json([
                "error"=>"Nenhum resultado"
            ],404);
            throw new Exception("Nenhum resultado");
        }

        return $resultados;
    }


    public function updateWeight()
    {
        //attributes from PreCheck APP
        $wt         = $_POST['weight'];
        $container  = $_POST['container'];



        $sql = "UPDATE inv_unit SET goods_and_ctr_wt_kg = '" . $wt . "' WHERE id LIKE '%" . $container . "%' AND visit_state = '1ACTIVE'";
        $sql = self::conexaon4()->prepare($sql);


        $sql->execute();

        $resultados = array();
        $resultados = "updated";
        return $resultados;
    }
}
