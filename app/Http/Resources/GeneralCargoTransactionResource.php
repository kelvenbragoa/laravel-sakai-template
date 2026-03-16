<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;


class GeneralCargoTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'gate'=>$this->gate,
            'movement_type'=>$this->movement_type,
            'document_number'=>$this->document_number,
            'cargo_type'=>$this->cargo_type,
            'trailers_quantity'=>$this->trailers_quantity,
            'driver_name'=>$this->driver_name,
            'driver_license_number'=>$this->driver_license_number,
            'driver_license_number_overwrite'=>$this->driver_license_number_overwrite,
            'driver_license_number_cutout_photo'=>$this->driver_license_number_cutout_photo,
            'truck_license_plate_number'=>$this->truck_license_plate_number,
            'truck_license_plate_number_overwrite'=>$this->truck_license_plate_number_overwrite,
            'truck_license_plate_number_cutout_photo'=>$this->truck_license_plate_number_cutout_photo,
            'trailer_1_license_plate_number'=>$this->trailer_1_license_plate_number,
            'trailer_1_license_plate_number_overwrite'=>$this->trailer_1_license_plate_number_overwrite,
            'trailer_1_license_plate_number_cutout_photo'=>$this->trailer_1_license_plate_number_cutout_photo,
            'trailer_2_license_plate_number'=>$this->trailer_2_license_plate_number,
            'trailer_2_license_plate_number_overwrite'=>$this->trailer_2_license_plate_number_overwrite,
            'trailer_2_license_plate_number_cutout_photo'=>$this->trailer_2_license_plate_number_cutout_photo,
            'trailer_1_internal_cargo_photo'=>$this->trailer_1_internal_cargo_photo,
            'trailer_2_internal_cargo_photo'=>$this->trailer_2_internal_cargo_photo,
            'user_name'=>$this->user_name,
            'document_number_overwrite'=>$this->document_number_overwrite,
            'driver_name_overwrite'=>$this->driver_name_overwrite,
            'status'=>$this->status,
            'document_number_cutout_photo'=>$this->document_number_cutout_photo,
            'created_at' => Carbon::parse($this->created_at)->timezone('Africa/Maputo')->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->timezone('Africa/Maputo')->format('Y-m-d H:i:s'),
            // Adicione outros campos relevantes
        ];
    }
}
