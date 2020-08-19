<?php


namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vehicle as VehicleResource;

use App\Vehicle;
use App\PreInspection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PreInspectionsController extends Controller
{
    public function store(Request $request)
    {
        $currentDriver = Auth::user();
        $owner_badge_Id = $currentDriver->PermitNumber;
        $data = request()->validate([
            'shiftType' => 'required',
            'date' => 'Date|required',
            'VehicleId' => 'Numeric|required',
            'licensedPlate' => 'required',
            'odometer' => 'required',
            'interiorExterior' => 'required',
            'lampsAndIndicators' => 'required',
            'steeringTires' => 'required',
            'wheelsAndHubsAndFastners' => 'required',
            'glassAndMirrors' => 'required',
            'hydraulicBrakeSystem' => 'required',
            'heaterDefroster' => 'required',
            'windShieldWipersAndWashers' => 'required',
            'horn' => 'required',
            'wheelChairLiftAndEmergencyEquipment' => 'required',
            'turnJake' => 'required',
            'driverRemarks' => 'Nullable',
            'driverName' => 'required',
            'aboveAffectedCorrected' => 'required',
        ]);
        $data['ownerBadgeId'] = $owner_badge_Id;
        $slip = PreInspection::create($data);

        return Response::json(array(
            'code' => 200,
            'message' => "Added successfully ",
            'id' => $slip->id,
        ), 200);
    }

    //slips by vehicle
    public function slipsByVehicle(Request $request)
    {
        $data = request()->validate([
            'vehicleId' => 'required',
            'password' => 'required'
        ]);
        $vehicle = Vehicle::whereId($data['vehicleId'])
            ->first();
        if ($vehicle) {
            if ($vehicle->password == $data['password']) {
                $slips = PreInspection::where('VehicleId', $data['vehicleId'])
                    ->join('vehicles', 'pre_inspection_slips.VehicleId', '=', 'vehicles.id')
                    ->orderBy('pre_inspection_slips.date')
                    ->paginate(20)
                    ->appends(request()->query());
                foreach ($slips as $slip) {
                    unset($slip["password"]);
                    $slip["vehicleName"] = $slip["type"] . " " . $slip["number"];

                }


                return $slips;

            } else {
                return Response::json(array(
                    'message' => "vehicle Not authenticated ",
                ), 401);
            }
        } else {
            return Response::json(array(
                'message' => "vehicle Not Found ",
            ), 400);
        }


    }


    //slips by driver
    public function slipsByDriver(Request $request)
    {
        $currentDriver = Auth::user();
        $owner_badge_Id = $currentDriver->PermitNumber;

        $slips = PreInspection::

        where('ownerBadgeId', $owner_badge_Id)
//            ->join('vehicles', 'pre_inspection_slips.VehicleId', '=', 'vehicles.id')
            ->withAll()
            ->orderBy('pre_inspection_slips.date')
            ->paginate(20)
            ->appends(request()->query());


        foreach ($slips as $slip) {
            unset($slip["vehicle"]["password"]);
            $slip["vehicle"]["vehicleName"] = $slip["vehicle"]["type"] . " " . $slip["vehicle"]["number"];

        }

        return $slips;

    }


    //slips by ID
    public function getSlipById($id)
    {
        if (!is_numeric($id)) {
            return Response::json(array(
                'code' => 400,
                'message' => "wrong id! "
            ), 400);
        }

        $slip = PreInspection::
        whereId($id)
            ->withAll()
            ->first();
        //hide vehicle password
        unset($slip["vehicle"]["password"]);
//        //prepare full name
        $slip["vehicle"]["vehicleName"] = $slip["vehicle"]["type"] . " " . $slip["vehicle"]["number"];
        return $slip;

    }


}
