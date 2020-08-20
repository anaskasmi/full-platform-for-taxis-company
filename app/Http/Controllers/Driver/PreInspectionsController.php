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
            'safeToDrive' => 'required'
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
    public function slipsByVehicle($id)
    {
        if (!is_numeric($id)) {
            return Response::json(array(
                'code' => 400,
                'message' => "wrong id! "
            ), 400);
        }

        $slips = PreInspection::
        where('vehicleId', $id)
            ->withAll()
            ->orderBy('pre_inspection_slips.date')
            ->paginate(20)
            ->appends(request()->query());


        foreach ($slips as $slip) {
            if (isset($slip["vehicle"]["password"]))
                unset($slip["vehicle"]["password"]);
            if (isset($slip["vehicle"]["number"]) && isset($slip["vehicle"]["type"]))
                $slip["vehicle"]["vehicleName"] = $slip["vehicle"]["type"] . " " . $slip["vehicle"]["number"];

        }

        return $slips;


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
            if (isset($slip["vehicle"]["password"]))
                unset($slip["vehicle"]["password"]);
            if (isset($slip["vehicle"]["number"]) && isset($slip["vehicle"]["type"]))
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
        //prepare full name
        $slip["vehicle"]["vehicleName"] = $slip["vehicle"]["type"] . " " . $slip["vehicle"]["number"];
        return $slip;

    }

    //delete by ID
    public function deleteSlip($id)
    {
        if (!is_numeric($id)) {
            return Response::json(array(
                'code' => 400,
                'message' => "wrong id! "
            ), 400);
        }
        //get slip
        $slip = PreInspection::
        whereId($id)
            ->first();
        //exist?
        if (!$slip) {
            return Response::json(array(
                'code' => 400,
                'message' => "Slip with this id doesnt exist"
            ), 400);
        }
        //is owner?
        $currentDriver = Auth::user();
        $owner_badge_Id = $currentDriver->PermitNumber;
        if ($owner_badge_Id != $slip->ownerBadgeId) {
            return Response::json(array(
                'code' => 400,
                'message' => "You are not Allowed to remove this slip"
            ), 400);
        }
        //delete
        if ($slip->delete()) {
            return Response::json(array(
                'code' => 200,
                'message' => "slip of id  " . $slip->id . " deleted successfully !",
            ), 200);
        } else {
            return Response::json(array(
                'code' => 400,
                'message' => "Couldn't delete slip "
            ), 400);
        }


    }

    //edit by ID
    public function editSlip(Request $request, $id)
    {
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
            'safeToDrive' => 'required'

        ]);

        if (!is_numeric($id)) {
            return Response::json(array(
                'code' => 400,
                'message' => "wrong id! "
            ), 400);
        }
        //get slip
        $slip = PreInspection::
        whereId($id)
            ->first();
        //exist?
        if (!$slip) {
            return Response::json(array(
                'code' => 400,
                'message' => "Slip with this id doesnt exist"
            ), 400);
        }
        //is owner?
        $currentDriver = Auth::user();
        $owner_badge_Id = $currentDriver->PermitNumber;
        if ($owner_badge_Id != $slip->ownerBadgeId) {
            return Response::json(array(
                'code' => 400,
                'message' => "You are not Allowed to remove this slip"
            ), 400);
        }


        //update
        if ($slip->update($data)) {
            return Response::json(array(
                'code' => 200,
                'message' => "slip of id  " . $slip->id . " updated successfully !",
                'id' => $slip->id
            ), 200);
        } else {
            return Response::json(array(
                'code' => 400,
                'message' => "Something went wrong! we couldn't update slip "
            ), 400);
        }
    }


}
