<?php

namespace App\Http\Controllers\Api\Mobile\CGatev2\Precheck;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use PDO;
use Exception;

class CGatePrecheckController extends Controller
{
    //
    use HttpResponses;
    //
    private static function conexao()
    {
        $con = new PDO("sqlsrv:Server=10.0.4.46; Database=cdms_commercial", "cdms2", "shD?fk9PGEqpw&3n");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    public function check_holds_and_impediments(Request $request)
    {
        $container_number = $request->container_number;
        
    }

        public function appointment($number)
        { 
            $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation], photoPathFront,[photoPathBack] ,[photoPathRight] ,[photoPathLeft] ,[photoPathSuperior] ,[photoPathOther] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = $number";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }

            return $resultados;
        }

        public function login(Request $request)
        {
            if($request->has('username') && $request->has('password')){
                $username = $request->input('username');
                $password = $request->input('password');
            }
            
            $sql = "SELECT [user_id] ,[user_name] ,[user_password] ,[first_name] ,[last_name] ,[company] ,[company_number] FROM [cdms_commercial].[cdms_commercial].[users] WHERE [user_name] = '$username' AND [user_password] = '$password'";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados [] = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }

            return $resultados;
        }

        public function checkAppointment(Request $request)
        {
            $number = $request->input('appointment_number');

            // $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = $number";
            $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation], photoPathFront,[photoPathBack] ,[photoPathRight] ,[photoPathLeft] ,[photoPathSuperior] ,[photoPathOther] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = $number";


            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }
            return $resultados;
        }

        public function checkAppointmentByContainer(Request $request)
        {

            $container_number = $request->input('container_number');

            // $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = $number";
            $sql = "SELECT TOP 1 [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation], photoPathFront,[photoPathBack] ,[photoPathRight] ,[photoPathLeft] ,[photoPathSuperior] ,[photoPathOther] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[container_number] = '".$container_number."' ORDER BY  [cdms_commercial].[cdms_commercial].[preadvise].[number] DESC ";


            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }


            return $resultados;
        }

        public function updateAppointment(Request $request)
        {
            //attributes from PreCheck APP
            if ($request->has('appointment_number')) {
                $number = $request->input('appointment_number');
            }
            if ($request->has('status')) {
                $status = $request->input('status');
            }else{
                $status = NULL;
            }
            if ($request->has('notes')) {
                $notes = $request->input('notes');
            }
        
            if ($request->has('photoPathFront')) {
                $photoPathFront = $request->input('photoPathFront');
            } else {
                $photoPathFront = "";
            }

            if ($request->has('photoPathBack')) {
                $photoPathBack = $request->input('photoPathBack');
            } else {
                $photoPathBack = "";
            }

            if ($request->has('photoPathRight')) {
                $photoPathRight = $request->input('photoPathRight');
            } else {
                $photoPathRight = "";
            }

            if ($request->has('photoPathLeft')) {
                $photoPathLeft = $request->input('photoPathLeft');
            } else {
                $photoPathLeft = "";
            }

            if ($request->has('photoPathSuperior')) {
                $photoPathSuperior = $request->input('photoPathSuperior');
            } else {
                $photoPathSuperior = "";
            }

            if ($request->has('photoPathOther')) {
                $photoPathOther = $request->input('photoPathOther');
            } else {
                $photoPathOther = "";
            }

            //conditions to UPDATE in CDMS 2.0
            if ($status == "Pre Check Completed") {
                $status = "Pre-Check Completed";
                $sql = "UPDATE [cdms_commercial].[cdms_commercial].[preadvise] 
                    SET 
                    [cdms_commercial].[cdms_commercial].[preadvise].[status] = '$status',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathFront] = '$photoPathFront',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathBack] = '$photoPathBack',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathRight] = '$photoPathRight',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathLeft] = '$photoPathLeft',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathSuperior] = '$photoPathSuperior',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathOther] = '$photoPathOther'
                    WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = '$number'";
                $sql = self::conexao()->prepare($sql);
                $sql->execute();
                $resultados = array();
                $resultados["finalResult"] =  "Approved";
                return $resultados;
            } else if ($status == "Pre Check Rejected") {
                $status = "Pre-Check Rejected";
                $sql = "UPDATE [cdms_commercial].[cdms_commercial].[preadvise] 
                    SET 
                    [cdms_commercial].[cdms_commercial].[preadvise].[status] = '$status', 
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathFront] = '$photoPathFront',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathBack] = '$photoPathBack',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathRight] = '$photoPathRight',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathLeft] = '$photoPathLeft',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathSuperior] = '$photoPathSuperior',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathOther] = '$photoPathOther',
                    [cdms_commercial].[cdms_commercial].[preadvise].[notes] = '$notes' 
                    WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = '$number'";
                $sql = self::conexao()->prepare($sql);
                $sql->execute();
                $resultados = array();
                $resultados["finalResult"] = "Rejected";
                return $resultados;
            }else{
                $sql = "UPDATE [cdms_commercial].[cdms_commercial].[preadvise] 
                    SET 
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathFront] = '$photoPathFront',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathBack] = '$photoPathBack',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathRight] = '$photoPathRight',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathLeft] = '$photoPathLeft',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathSuperior] = '$photoPathSuperior',
                    [cdms_commercial].[cdms_commercial].[preadvise].[photoPathOther] = '$photoPathOther'
                    WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = '$number'";
                $sql = self::conexao()->prepare($sql);
                $sql->execute();
                $resultados = array();
                return $resultados;
            }
        }

        public function updateAppointmentDataByAppointmentNumber()
        {

            if (isset($_POST['number'])){$number = $_POST['number'];} else {$number = "";}

            $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation], photoPathFront,[photoPathBack] ,[photoPathRight] ,[photoPathLeft] ,[photoPathSuperior] ,[photoPathOther] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = $number";


            $sql = self::conexao()->prepare($sql);
            $sql->execute();



            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }

            if (isset($_POST['container_number'])){ $container_number = $_POST['container_number'];} else {$container_number = $resultados['container_number'];}
            if (isset($_POST['seal_number'])){ $seal_number = $_POST['seal_number'];} else {$seal_number =  $resultados['seal_number'];}
            if (isset($_POST['driver_name'])){ $driver_name = $_POST['driver_name'];} else {$driver_name =  $resultados['driver_name'];}
            if (isset($_POST['driver_license_number'])){ $driver_license_number = $_POST['driver_license_number'];} else {$driver_license_number =  $resultados['driver_license_number'];}
            if (isset($_POST['truck_license_number'])){ $truck_license_number = $_POST['truck_license_number'];} else {$truck_license_number =  $resultados['truck_license_number'];}

            $sql = "UPDATE [cdms_commercial].[cdms_commercial].[preadvise] 
            SET 
            [cdms_commercial].[cdms_commercial].[preadvise].[container_number] = '$container_number',
            [cdms_commercial].[cdms_commercial].[preadvise].[seal_number] = '$seal_number',
            [cdms_commercial].[cdms_commercial].[preadvise].[driver_name] = '$driver_name',
            [cdms_commercial].[cdms_commercial].[preadvise].[driver_license_number] = '$driver_license_number',
            [cdms_commercial].[cdms_commercial].[preadvise].[truck_license_number] = '$truck_license_number'

            WHERE [cdms_commercial].[cdms_commercial].[preadvise].[number] = '$number'";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            $resultados = array();
            $resultados["finalResult"] =  "saved";
            return $resultados;
        }

        public function updateAppointmentDataByContainer(Request $request)
        {

            if ($request->has('container_number')){$container_number = $request->input('container_number');} else {$container_number = "";}

            $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation], photoPathFront,[photoPathBack] ,[photoPathRight] ,[photoPathLeft] ,[photoPathSuperior] ,[photoPathOther] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[container_number] = $container_number";


            $sql = self::conexao()->prepare($sql);
            $sql->execute();



            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }

            if ($request->has('number')){ $number = $request->input('number');} else {$number = $resultados['number'];}
            if ($request->has('seal_number')){ $seal_number = $request->input('seal_number');} else {$seal_number =  $resultados['seal_number'];}
            if ($request->has('driver_name')){ $driver_name = $request->input('driver_name');} else {$driver_name =  $resultados['driver_name'];}
            if ($request->has('driver_license_number')){ $driver_license_number = $request->input('driver_license_number');} else {$driver_license_number =  $resultados['driver_license_number'];}
            if ($request->has('truck_license_number')){ $truck_license_number = $request->input('truck_license_number');} else {$truck_license_number =  $resultados['truck_license_number'];}
            $sql = "UPDATE [cdms_commercial].[cdms_commercial].[preadvise] 
            SET 
            [cdms_commercial].[cdms_commercial].[preadvise].[number] = '$number',
            [cdms_commercial].[cdms_commercial].[preadvise].[seal_number] = '$seal_number',
            [cdms_commercial].[cdms_commercial].[preadvise].[driver_name] = '$driver_name',
            [cdms_commercial].[cdms_commercial].[preadvise].[driver_license_number] = '$driver_license_number',
            [cdms_commercial].[cdms_commercial].[preadvise].[truck_license_number] = '$truck_license_number'

            WHERE [cdms_commercial].[cdms_commercial].[preadvise].[container_number] = '$container_number'";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();
            $resultados = array();
            $resultados["finalResult"] =  "saved";
            return $resultados;
        }


        public function uploadImage()
        {
            $local_dir = "C:\\ex\\" ;
            $mapped_dir = "Z:\\" ;
            $network_dir = "\\\\10.0.4.46\cdms\photos\\";
            
            $target_file = $network_dir . basename($_FILES["file"]["name"]);
            $uploadOk = 1;
            // Check if image file is a actual image or fake image
            if (isset($_POST["submit"])) {
                $check = getimagesize($_FILES["file"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
            }
            $resultados = array();
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return $resultados;
                // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                    return $resultados = [
                        "photo" => "https://cdms.cornelder.co.mz:8013/cdms/photos/". basename($_FILES["file"]["name"]),
                    ];

                } else {
                    return $resultados;
                }
            }
        }

        public function getAppointment() 
        {
            $containerNum = trim($_POST['container_number']);
            $sql = "SELECT [number] ,[category] ,[shipping_line] ,[agent] ,[booking_number] ,[bill_lading_number] ,[shipping_line_release_order_number] ,[vessel_visit] ,[vessel_voyage_number] ,[quantity_containers] ,[quantity_container_20] ,[quantity_container_40] ,[container_type] ,[container_number] ,[seal_number] ,[vgm_weight_check] ,[weight] ,[vgm_weight] ,[trucking_company] ,[truck_license_number] ,[driver_name] ,[driver_license_number] ,[local_transit_type] ,[destination] ,[origin] ,[commodity] ,[created_by] ,[date_created] ,[updated_by] ,[date_updated] ,[status] ,[notes] ,[attachment_weight_slip] ,[attachment_vgm_weight] ,[appointment_qr_code] ,[appointment_date] ,[appointment_time_slot] ,[appointment_pin_number] ,[stack_open_date] ,[stack_close_date] ,[hold_status] ,[terms_conditions_confirmation] FROM [cdms_commercial].[cdms_commercial].[preadvise] WHERE [cdms_commercial].[cdms_commercial].[preadvise].[container_number] LIKE '%.$containerNum.%' ";

            $sql = self::conexao()->prepare($sql);
            $sql->execute();

            $resultados = array();

            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                $resultados[] = $row;
            }

            if(!$resultados){
                throw new Exception( "Nenhum resultado");
            }

            return $containerNum;
        }

}
