<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Driver;

use PDF;

class WeeklyDriverHoursController extends Controller
{


    public function WeeklyDriverHours(Request $request)
    {
        $data = request()->validate([
            'date' => 'required|date',
        ]);
        $user = Auth::user();
        $badgeId = $user->PermitNumber;
        $inputDate = $data['date'];

        $startDate = date("Y-m-d", strtotime($inputDate . 'monday this week'));
        $endDate = date("Y-m-d", strtotime($inputDate . 'sunday this week'));

        $startDay = date("d", strtotime($startDate));
        $endDay = date("d", strtotime($endDate));

        $startMonth = date("M", strtotime($startDate)); // in letters
        $endMonth = date("M", strtotime($endDate)); // in letters

        $startYear = date("Y", strtotime($startDate));
        $endYear = date("Y", strtotime($endDate));

        $dateData = array(
            'inputDate' => $inputDate,
            'startDay' => $startDay,
            'endDay' => $endDay,
            'startMonth' => $startMonth,
            'endMonth' => $endMonth,
            'startYear' => $startYear,
            'endYear' => $endYear,
        );
        $date = $startMonth . ' ' . $startDay . ',' . $startYear . ' - ' . $endMonth . ' ' . $endDay . ',' . $endYear;

        $shifts = DB::table('driver_shifts')
            ->join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')
            ->where('driver_shifts.shift_start_time', '>', $startDate)
            ->where('driver_shifts.shift_start_time', '<', $endDate)
            ->where('driver_shifts.driver_badge_id', '=', $badgeId)
            ->orderby('drivers.LastName')
            ->orderby('driver_shifts.shift_start_time')
            ->get()
            ->toArray();

// converting shifts collection to array
        $shifts = (array) $shifts;
        for ($i = 0; $i < count($shifts); $i++) {
            //converting shitfs to array
            $shifts[$i] = (array) $shifts[$i];
            $shifts[$i]['break_time'] = $shifts[$i]['total_time'] - $shifts[$i]['work_time'];
        }

        $total_time = 0.00;
        $break_time = 0.00;
        $work_time = 0.00;

        for ($i = 0; $i < count($shifts); $i++) {

            $total_time = $total_time + $shifts[$i]['total_time'];
            $work_time = $work_time + $shifts[$i]['work_time'];
            $break_time = $break_time + $shifts[$i]['break_time'];

        }
//to hours : minutes
        $total_time_hours = floor($total_time / 60);
        $total_time_min = $total_time - ($total_time_hours * 60);

        $work_time_hours = floor($work_time / 60);
        $work_time_min = $work_time - ($work_time_hours * 60);

        $break_time_hours = floor($break_time / 60);
        $break_time_min = $break_time - ($break_time_hours * 60);
//building the total array
        $total_row = array(
            'total_time_hours' => $total_time_hours, 'total_time_min' => $total_time_min,
            'work_time_hours' => $work_time_hours, 'work_time_min' => $work_time_min,
            'break_time_hours' => $break_time_hours, 'break_time_min' => $break_time_min,
        );

// Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

// Create a new Laravel collection from the array data
        $itemCollection = collect($shifts);

// Define how many items we want to be visible in each page
        $perPage = 30;

// Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

// Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

// set url path for generted links
        $paginatedItems->setPath($request->url())->appends(request()->query());

        $shifts = $paginatedItems;
        $name = Auth::user()->FirstName . ' ' . Auth::user()->LastName;
        $result['shifts'] = $shifts;
        $result['total_row'] = $total_row;
        $result['date'] = $date;
        $result['name'] = $name;
        return $result;
    }



    public function checkIsAValidDate($myDateString)
    {
        return (bool) strtotime($myDateString);
    }



    

//////////////////////////////////Pdf//////////////////////////////////////////

    public function printPDF($idEncrypted,$date)
    {
        $badgeId=($idEncrypted-2019)/2;

        if ($this->checkIsAValidDate($date)) {
            
            $inputDate = $date;

            $startDate = date("Y-m-d", strtotime($inputDate . 'monday this week'));
            $endDate = date("Y-m-d", strtotime($inputDate . 'sunday this week'));

            $startDay = date("d", strtotime($startDate));
            $endDay = date("d", strtotime($endDate));

            $startMonth = date("M", strtotime($startDate)); // in letters
            $endMonth = date("M", strtotime($endDate)); // in letters

            $startYear = date("Y", strtotime($startDate));
            $endYear = date("Y", strtotime($endDate));

            $dateData = array(
                'inputDate' => $inputDate,
                'startDay' => $startDay,
                'endDay' => $endDay,
                'startMonth' => $startMonth,
                'endMonth' => $endMonth,
                'startYear' => $startYear,
                'endYear' => $endYear,
            );
            $date = $startMonth . ' ' . $startDay . ',' . $startYear . ' - ' . $endMonth . ' ' . $endDay . ',' . $endYear;

            $shifts = DB::table('driver_shifts')
                ->join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')
                ->where('driver_shifts.shift_start_time', '>', $startDate)
                ->where('driver_shifts.shift_start_time', '<', $endDate)
                ->where('driver_shifts.driver_badge_id', '=', $badgeId)
                ->orderby('drivers.LastName')
                ->orderby('driver_shifts.shift_start_time')
                ->get()
                ->toArray();

            if (count($shifts) > 0) {
                // converting shifts collection to array
                $shifts = (array) $shifts;
                for ($i = 0; $i < count($shifts); $i++) {
                    //converting shitfs to array
                    $shifts[$i] = (array) $shifts[$i];
                    $shifts[$i]['break_time'] = $shifts[$i]['total_time'] - $shifts[$i]['work_time'];
                }

                $total_time = 0.00;
                $break_time = 0.00;
                $work_time = 0.00;

                for ($i = 0; $i < count($shifts); $i++) {

                    $total_time = $total_time + $shifts[$i]['total_time'];
                    $work_time = $work_time + $shifts[$i]['work_time'];
                    $break_time = $break_time + $shifts[$i]['break_time'];

                }
                //to hours : minutes
                $total_time_hours = floor($total_time / 60);
                $total_time_min = $total_time - ($total_time_hours * 60);

                $work_time_hours = floor($work_time / 60);
                $work_time_min = $work_time - ($work_time_hours * 60);

                $break_time_hours = floor($break_time / 60);
                $break_time_min = $break_time - ($break_time_hours * 60);
                //building the total array
                $total_row = array(
                    'total_time_hours' => $total_time_hours, 'total_time_min' => $total_time_min,
                    'work_time_hours' => $work_time_hours, 'work_time_min' => $work_time_min,
                    'break_time_hours' => $break_time_hours, 'break_time_min' => $break_time_min,
                );
                set_time_limit(99000);
                // return view('driver.WeeklyDriverHours.PDF_WeeklyDriverHours',compact('shifts','dateData'));
                $driver = Driver::where('PermitNumber', $badgeId)->first();
                $name = $driver->FirstName . ' ' . $driver->LastName;
                $pdf = PDF::loadView('admin.reports.WeeklyDriverHours.WeeklyDriverHours', compact('name', 'shifts', 'dateData', 'total_row', 'date'))->setPaper('a3', 'landscape');
                $name = 'Weekly diver report - ' . $date . '.pdf';
                return $pdf->stream($name, array('Attachment' => 0));
                return redirect(route('driver.WeeklyDriverHours.index'))->with('success', 'pdf is generating');

            } else {
                return response(['error' => "No shifts Found!"], 406);
            }

        } else {
            return response(['error' => "Invalid date!"], 406);

        }

    }

}
