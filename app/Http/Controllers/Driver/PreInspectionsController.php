<?php


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vehicle as VehicleResource;
use App\Marks;
use App\RotationsCategory;
use App\Vehicle;
use App\PreInspection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
class PreInspectionsController extends Controller
{
    public function store(Request $request)
    {
        $currentDriver = Auth::user();
        $owner_badge_Id= $currentDriver->PermitNumber;
        $data = request()->validate([
            'shiftType'=>'required',
            'date'=>'Date|required',
            'VehicleId'=>'Numeric|required',
            'licensedPlate'=>'required',
            'odometer'=>'required',
            'interiorExterior'=>'required',
            'lampsAndIndicators'=>'required',
            'steeringTires'=>'required',
            'wheelsAndHubsAndFastners'=>'required',
            'glassAndMirrors'=>'required',
            'hydraulicBrakeSystem'=>'required',
            'heaterDefroster'=>'required',
            'windShieldWipersAndWashers'=>'required',
            'horn'=>'required',
            'wheelChairLiftAndEmergencyEquipment'=>'required',
            'turnJake'=>'required',
            'driverRemarks'=>'Nullable',
            'driverName'=>'required',
            'aboveAffectedCorrected'=>'required',
        ]);
        $data['ownerBadgeId']=$owner_badge_Id;
//        $preInspectionSlip  = new PreInspection();
        PreInspection::create($data);
    }

}
