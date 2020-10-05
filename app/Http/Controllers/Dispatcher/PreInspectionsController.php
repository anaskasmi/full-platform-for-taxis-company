<?php


namespace App\Http\Controllers\Dispatcher;

use App\Http\Controllers\Controller;

use App\PreInspection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PDF;
class PreInspectionsController extends Controller
{


    //get all slips
    public function allSlips(Request $request)
    {

        $slips = PreInspection::
        withAll()
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
    public function slipsByDriver($badgeId)
    {
        if (!is_numeric($badgeId)) {
            return Response::json(array(
                'code' => 400,
                'message' => "wrong badge id! "
            ), 400);
        }

        $slips = PreInspection::

        where('ownerBadgeId', $badgeId)
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


    // current Month pdf
    public function downloadPreInspectioSlip($id, Request $request)
    {
        ini_set('memory_limit', '-1');
        //validate if the id is a number
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


        if (!$slip) {
            return Response::json(array(
                'code' => 404,
                'message' => "Not Found! "
            ), 404);
        }
        //hide vehicle password
        unset($slip["vehicle"]["password"]);
        //prepare full name
        $slip["vehicle"]["vehicleName"] = $slip["vehicle"]["type"] . " " . $slip["vehicle"]["number"];


        $pdf = PDF::loadView('dispatcher.pre_inspection_slips', compact('slip'))->setPaper('a4', 'landscape');
        $date = $slip['date'];
        $name = $slip["vehicle"]["vehicleName"];
        $name_pdf = $name . '-' . $date . '.pdf';
        return $pdf->stream($name_pdf, array('Attachment' => 0));


    }

}
