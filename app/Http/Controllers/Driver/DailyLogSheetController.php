<?php

namespace App\Http\Controllers\Driver;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Resources\DailyLogSheet as DailyLogSheetResource;
use App\Shift;
use App\Trip;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class DailyLogSheetController extends Controller
{

    public function DailyLogSheet(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;

        $inputDate = request()->validate(['date' => 'date']);
        $inputDate = $inputDate['date'];
        // $inputDate = '2019/07/09';
        $month = date("M", strtotime($inputDate)); // in letters
        $day = date("d", strtotime($inputDate));
        $year = date("Y", strtotime($inputDate));

        $dayLetters = date("D", strtotime($inputDate));
        $date = $month . ' ' . $day . ',' . $year;
        //get trips
        $trips = Trip::
            join('drivers', 'PermitNumber', '=', 'badge_id')
            ->whereDate('date', $inputDate)
            ->where('badge_id', '=', $badgeId)
            ->get();

        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();

        //get drivet full name
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        $total_fare = 0;
        $total_tip = 0;
        $total_total = 0;
        foreach ($trips as $trip) {
            $total_fare = $total_fare + $trip->fare;
            $total_tip = $total_tip + $trip->tip;
            $total_total = $total_total + $trip->total;
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $dailyLogSheet['trips'] = $trips;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['inputDate'] = $inputDate;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        return new DailyLogSheetResource($dailyLogSheet);

    }

    // current Month shifts
    public function currentMonthShifts(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;
        $shifts = Shift::
            join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')

            ->whereMonth('shift_start_time', date("m"))
            ->whereYear('shift_start_time', date("Y"))
            ->where('driver_badge_id', '=', $badgeId)
            ->orderby('shift_start_time')
            ->get()
            ->toArray();
            $allShiftsTotal = 0;
            $allShiftsFare = 0;
            $allShiftsTip = 0;
            for ($i=0; $i < count($shifts) ; $i++) { 
            // get trips of every shift
            // $shifts[$i]= json_decode(json_encode($shifts[$i]), true);

                $result = $this->getTripsOfShift($shifts[$i]['driver_shift_id']);
                // dd($result['trips']);
                //affecr data to every shift
                $shifts[$i]['trips'] =  $result['trips'];
                $shifts[$i]['shiftTotal'] = number_format((float)$result['shiftTotal'], 2, '.', '');
                $shifts[$i]['shiftFare'] =  number_format((float)$result['shiftFare'], 2, '.', '');
                $shifts[$i]['shiftTip'] =  number_format((float)$result['shiftTip'], 2, '.', '');
                
                
                //calculate totals
                $allShiftsTotal = $allShiftsTotal + $result['shiftTotal'];
                $allShiftsFare = $allShiftsFare + $result['shiftFare'];
                $allShiftsTip = $allShiftsTip + $result['shiftTip'];

            }
            $totals['allShiftsTotal']=number_format((float)$allShiftsTotal, 2, '.', '');
            $totals['allShiftsFare']=number_format((float)$allShiftsFare, 2, '.', '');
            $totals['allShiftsTip']= number_format((float)$allShiftsTip, 2, '.', '');
            $data['totals']=$totals;
            $data['shifts']=$shifts;
            return $data;
        


    }
    // past Month shifts
    public function pastMonthShifts(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;
        $shifts = Shift::
            join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')

            ->whereMonth('shift_start_time', date("m") - 1)
            ->whereYear('shift_start_time', date("Y"))
            ->where('driver_badge_id', '=', $badgeId)
            ->orderby('shift_start_time')
            ->get()
            ->toArray();

            $allShiftsTotal = 0;
            $allShiftsFare = 0;
            $allShiftsTip = 0;
            for ($i=0; $i < count($shifts) ; $i++) { 
            // get trips of every shift
            // $shifts[$i]= json_decode(json_encode($shifts[$i]), true);

                $result = $this->getTripsOfShift($shifts[$i]['driver_shift_id']);
                // dd($result['trips']);
                //affecr data to every shift
                $shifts[$i]['trips'] =  $result['trips'];
                $shifts[$i]['shiftTotal'] = number_format((float)$result['shiftTotal'], 2, '.', '');
                $shifts[$i]['shiftFare'] =  number_format((float)$result['shiftFare'], 2, '.', '');
                $shifts[$i]['shiftTip'] =  number_format((float)$result['shiftTip'], 2, '.', '');
                
                
                //calculate totals
                $allShiftsTotal = $allShiftsTotal + $result['shiftTotal'];
                $allShiftsFare = $allShiftsFare + $result['shiftFare'];
                $allShiftsTip = $allShiftsTip + $result['shiftTip'];

            }
            $totals['allShiftsTotal']=number_format((float)$allShiftsTotal, 2, '.', '');
            $totals['allShiftsFare']=number_format((float)$allShiftsFare, 2, '.', '');
            $totals['allShiftsTip']= number_format((float)$allShiftsTip, 2, '.', '');
            $data['totals']=$totals;
            $data['shifts']=$shifts;
            return $data;
        

    }
    public function getTripsOfShift($shift_id)
    {
        //get the authenticated user
        $user = Auth::user();
        $badgeId = $user->PermitNumber;
        //get shift
        $shift = Shift::
            join('drivers', 'drivers.PermitNumber', '=', 'driver_shifts.driver_badge_id')
            ->where('driver_badge_id', '=', $badgeId)
            ->where('driver_shift_id', '=', $shift_id)
            ->first();

        //get times
        $start_time = $shift->shift_start_time;
        $end_time = $shift->shift_end_time;

        //get trips
        $trips = Trip::
            where('date','>=', $start_time)
            ->where('date','<=', $end_time)
            ->where('badge_id', '=', $badgeId)
            ->get()
            ->toArray();

        $shiftTotal = 0;
        $shiftFare = 0;
        $shiftTip = 0;
        foreach ($trips as $trip) {
            
            $shiftTotal = $shiftTotal + $trip['total'];
            $shiftFare = $shiftFare + $trip['fare'];
            $shiftTip = $shiftTip + $trip['tip'];
        }
        $result['trips'] = $trips;
        $result['shiftTotal'] = $shiftTotal;
        $result['shiftFare'] = $shiftFare;
        $result['shiftTip'] = $shiftTip;
        return $result;

    }

    // current Month trips
    public function currentMonth(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;

        $trips = Trip::
            whereMonth('date', date("m"))
            ->whereYear('date', date("Y"))
            ->where('badge_id', '=', $badgeId)
            ->get();
        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        $total_fare = 0;
        $total_tip = 0;
        $total_total = 0;
        foreach ($trips as $trip) {
            $total_fare = $total_fare + $trip->fare;
            $total_tip = $total_tip + $trip->tip;
            $total_total = $total_total + $trip->total;
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $dailyLogSheet['trips'] = $trips;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        return new DailyLogSheetResource($dailyLogSheet);

    }

    // past Month
    public function pastMonth(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;

        $trips = Trip::
            whereMonth('date', date("m") - 1)
            ->whereYear('date', date("Y"))
            ->where('badge_id', '=', $badgeId)
            ->get();
        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        $total_fare = 0;
        $total_tip = 0;
        $total_total = 0;
        foreach ($trips as $trip) {
            $total_fare = $total_fare + $trip->fare;
            $total_tip = $total_tip + $trip->tip;
            $total_total = $total_total + $trip->total;
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $dailyLogSheet['trips'] = $trips;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        return new DailyLogSheetResource($dailyLogSheet);
    }

    // current Year

    public function currentYear(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;

        $first_day_this_year = date('Y-m-d', strtotime('first day of january this year'));
        $last_day_this_year = date('Y-m-d', strtotime('last day of December this year'));
        $monthStart = date("M", strtotime($first_day_this_year)); // in letters
        $dayStart = date("d", strtotime($first_day_this_year));
        $yearStart = date("Y", strtotime($first_day_this_year));

        $monthEnd = date("M", strtotime($last_day_this_year)); // in letters
        $dayEnd = date("d", strtotime($last_day_this_year));
        $yearEnd = date("Y", strtotime($last_day_this_year));
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Trip::
                select(
                DB::raw('SUM(fare) as fare'),
                DB::raw('SUM(tip) as tip'),
                DB::raw('SUM(total) as total')
            )
                ->whereDate('date', '>', $first_day_this_year)
                ->whereDate('date', '<', $last_day_this_year)
                ->whereMonth('date', $i < 10 ? '0' . $i : $i)
                ->where('badge_id', '=', $badgeId)
                ->get();
        }
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $months[$i][0];
        }

        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        (float) $total_fare = 0;
        (float) $total_tip = 0;
        (float) $total_total = 0;
        // $months[7]->fare = ;

        for ($i = 1; $i <= 12; $i++) {
            if ($months[$i]->fare !== null) {
                $total_fare = $total_fare + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->fare);
            }
            if ($months[$i]->tip !== null) {
                $total_tip = $total_tip + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->tip);
            }
            if ($months[$i]->total !== null) {
                $total_total = $total_total + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->total);
            }
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $dailyLogSheet['months'] = (array) $months;

        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        return $dailyLogSheet;
        // return new DailyLogSheetResource($dailyLogSheet);

    }

    // past Year
    public function pastYear(Request $request)
    {
        $user = Auth::user();
        $badgeId = $user->PermitNumber;

        $first_day_past_year = date('Y-m-d', strtotime('first day of january this year -1 years'));
        $last_day_past_year = date('Y-m-d', strtotime('last day of December this year -1 years'));
        $monthStart = date("M", strtotime($first_day_past_year)); // in letters
        $dayStart = date("d", strtotime($first_day_past_year));
        $yearStart = date("Y", strtotime($first_day_past_year));

        $monthEnd = date("M", strtotime($last_day_past_year)); // in letters
        $dayEnd = date("d", strtotime($last_day_past_year));
        $yearEnd = date("Y", strtotime($last_day_past_year));

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Trip::
                select(
                DB::raw('SUM(fare) as fare'),
                DB::raw('SUM(tip) as tip'),
                DB::raw('SUM(total) as total')
            )
                ->whereDate('date', '>', $first_day_past_year)
                ->whereDate('date', '<', $last_day_past_year)
                ->whereMonth('date', $i < 10 ? '0' . $i : $i)
                ->where('badge_id', '=', $badgeId)
                ->get();
        }
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $months[$i][0];
        }

        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        (float) $total_fare = 0;
        (float) $total_tip = 0;
        (float) $total_total = 0;
        // $months[7]->fare = ;

        for ($i = 1; $i <= 12; $i++) {
            if ($months[$i]->fare !== null) {
                $total_fare = $total_fare + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->fare);
            }
            if ($months[$i]->tip !== null) {
                $total_tip = $total_tip + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->tip);
            }
            if ($months[$i]->total !== null) {
                $total_total = $total_total + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->total);
            }
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $dailyLogSheet['months'] = (array) $months;

        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        return $dailyLogSheet;
    }

    public function checkIsAValidDate($myDateString)
    {
        return (bool) strtotime($myDateString);
    }
    ///////////////////////////////////////////Pdfs////////////////////////////////////////////////////////
    public function printPDF($idEncrypted, $date)
    {

        $badgeId = ($idEncrypted - 2019) / 2;
        ini_set('memory_limit', '-1');

        if ($this->checkIsAValidDate($date)) {
            $inputDate = $date;
            $month = date("M", strtotime($inputDate)); // in letters
            $day = date("d", strtotime($inputDate));
            $year = date("Y", strtotime($inputDate));

            $dayLetters = date("D", strtotime($inputDate));
            $date = $month . ' ' . $day . ',' . $year;
            $trips = DB::select('SELECT * FROM `piccolo_trips` WHERE DATE(date) LIKE ? and badge_id = ? ', [$inputDate, $badgeId]);
            $driver = DB::select('select * from drivers where PermitNumber = ?', [$badgeId]);
        } else {
            return redirect(route('driver.pages.DailyLogSheet.datePicker', $badgeId))->with('success', 'invalid date');
        }

        if ($trips && $driver) {
            set_time_limit(1000);
            $driver = $driver[0];
            $driver = (array) $driver;
            $name = $driver['FirstName'] . ' ' . $driver['LastName'];
            $pdf = PDF::loadView('admin.reports.DailyLogSheet.PDF_DailyLogSheet', compact('trips', 'date', 'name'))->setPaper('a3', 'landscape');
            //return view('reports.DailyLogSheet.PDF_DailyLogSheet',compact('trips'));
            $name_pdf = $name . '-' . $inputDate . '.pdf';
            return $pdf->stream($name_pdf, array('Attachment' => 0));
            return redirect(route('admin.reports.DailyLogSheet.datePicker', $badgeId))->with('success', 'pdf is generating');
        } else if ($driver) {
            return response(['error' => "No shifts Found!"], 406);
        } else {
            return response(['error' => "Sorry! this Driver does not exist!"], 406);
        }

    }

    // current Month pdf
    public function currentMonth_pdf($idEncrypted, Request $request)
    {

        $badgeId = ($idEncrypted - 2019) / 2;
        ini_set('memory_limit', '-1');

        $trips = Trip::
            whereMonth('date', date("m"))
            ->whereYear('date', date("Y"))
            ->where('badge_id', '=', $badgeId)
            ->get();
        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        $date = date("m") . "/" . date("Y");
        // get totals
        $total_fare = 0;
        $total_tip = 0;
        $total_total = 0;
        foreach ($trips as $trip) {
            $total_fare = $total_fare + $trip->fare;
            $total_tip = $total_tip + $trip->tip;
            $total_total = $total_total + $trip->total;
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');

        $nbr_trips = count($trips);
        //prepare dailyLogsheet array
        $dailyLogSheet['trips'] = $trips;
        $dailyLogSheet['date'] = $date;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        $dailyLogSheet['nbr_trips'] = $nbr_trips;

        if ($trips && count($trips) > 0) {
            set_time_limit(1000);
            $name = $driver['FirstName'] . ' ' . $driver['LastName'];
            $pdf = PDF::loadView('admin.reports.DailyLogSheet.Month_pdf', compact('dailyLogSheet'))->setPaper('a3', 'landscape');
            $name_pdf = $name . '-' . $date . '.pdf';
            return $pdf->stream($name_pdf, array('Attachment' => 0));
        } else {
            return response(['error' => "No shifts Found!"], 406);
        }

    }

    // past Month pdf
    public function pastMonth_pdf($idEncrypted, Request $request)
    {
        $badgeId = ($idEncrypted - 2019) / 2;
        ini_set('memory_limit', '-1');

        $trips = Trip::
            whereMonth('date', date("m") - 1)
            ->whereYear('date', date("Y"))
            ->where('badge_id', '=', $badgeId)
            ->get();
        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        $total_fare = 0;
        $total_tip = 0;
        $total_total = 0;
        foreach ($trips as $trip) {
            $total_fare = $total_fare + $trip->fare;
            $total_tip = $total_tip + $trip->tip;
            $total_total = $total_total + $trip->total;
        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array

        $date = (date("m") - 1) . "/" . date("Y");
        $nbr_trips = count($trips);
        //prepare dailyLogsheet array
        $dailyLogSheet['trips'] = $trips;
        $dailyLogSheet['date'] = $date;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;
        $dailyLogSheet['nbr_trips'] = $nbr_trips;

        if ($trips && count($trips) > 0) {
            set_time_limit(1000);
            $name = $driver['FirstName'] . ' ' . $driver['LastName'];
            $pdf = PDF::loadView('admin.reports.DailyLogSheet.Month_pdf', compact('dailyLogSheet'))->setPaper('a3', 'landscape');
            $name_pdf = $name . '-' . $date . '.pdf';
            return $pdf->stream($name_pdf, array('Attachment' => 0));
        } else {
            return response(['error' => "No shifts Found!"], 406);
        }

    }

    // current year pdf
    public function currentYear_pdf($idEncrypted, Request $request)
    {
        $badgeId = ($idEncrypted - 2019) / 2;

        $first_day_this_year = date('Y-m-d', strtotime('first day of january this year'));
        $last_day_this_year = date('Y-m-d', strtotime('last day of December this year'));

        $monthStart = date("M", strtotime($first_day_this_year)); // in letters
        $dayStart = date("d", strtotime($first_day_this_year));
        $yearStart = date("Y", strtotime($first_day_this_year));

        $monthEnd = date("M", strtotime($last_day_this_year)); // in letters
        $dayEnd = date("d", strtotime($last_day_this_year));
        $yearEnd = date("Y", strtotime($last_day_this_year));

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Trip::
                select(
                DB::raw('SUM(fare) as fare'),
                DB::raw('SUM(tip) as tip'),
                DB::raw('SUM(total) as total')
            )
                ->whereDate('date', '>', $first_day_this_year)
                ->whereDate('date', '<', $last_day_this_year)
                ->whereMonth('date', $i < 10 ? '0' . $i : $i)
                ->where('badge_id', '=', $badgeId)
                ->get();
        }
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $months[$i][0];
        }

        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        (float) $total_fare = 0;
        (float) $total_tip = 0;
        (float) $total_total = 0;
        // $months[7]->fare = ;

        for ($i = 1; $i <= 12; $i++) {

            if ($months[$i]->fare !== null) {
                $total_fare = $total_fare + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->fare);
            }
            if ($months[$i]->tip !== null) {
                $total_tip = $total_tip + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->tip);
            }
            if ($months[$i]->total !== null) {
                $total_total = $total_total + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->total);
            }
        }
        for ($i = 1; $i <= 12; $i++) {
            switch ($i) {
                case "1":$months[$i]->name = "January";
                    break;
                case "2":$months[$i]->name = "February";
                    break;
                case "3":$months[$i]->name = "March";
                    break;
                case "4":$months[$i]->name = "April";
                    break;
                case "5":$months[$i]->name = "May";
                    break;
                case "6":$months[$i]->name = "June";
                    break;
                case "7":$months[$i]->name = "July";
                    break;
                case "8":$months[$i]->name = "August";
                    break;
                case "9":$months[$i]->name = "September";
                    break;
                case "10":$months[$i]->name = "October";
                    break;
                case "11":$months[$i]->name = "November";
                    break;
                case "12":$months[$i]->name = "December";
                    break;
            }

        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $date = date('Y');
        $dailyLogSheet['months'] = (array) $months;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['date'] = $date;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;

        set_time_limit(1000);
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];
        $pdf = PDF::loadView('admin.reports.DailyLogSheet.Year_pdf', compact('dailyLogSheet'))->setPaper('a3', 'landscape');
        $name_pdf = $name . '-' . $date . '.pdf';
        return $pdf->stream($name_pdf, array('Attachment' => 0));
    }

    //past year
    public function pastYear_pdf($idEncrypted, Request $request)
    {
        $badgeId = ($idEncrypted - 2019) / 2;

        $first_day_past_year = date('Y-m-d', strtotime('first day of january this year -1 years'));
        $last_day_past_year = date('Y-m-d', strtotime('last day of December this year -1 years'));
        $monthStart = date("M", strtotime($first_day_past_year)); // in letters
        $dayStart = date("d", strtotime($first_day_past_year));
        $yearStart = date("Y", strtotime($first_day_past_year));

        $monthEnd = date("M", strtotime($last_day_past_year)); // in letters
        $dayEnd = date("d", strtotime($last_day_past_year));
        $yearEnd = date("Y", strtotime($last_day_past_year));

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Trip::
                select(
                DB::raw('SUM(fare) as fare'),
                DB::raw('SUM(tip) as tip'),
                DB::raw('SUM(total) as total')
            )
                ->whereDate('date', '>', $first_day_past_year)
                ->whereDate('date', '<', $last_day_past_year)
                ->whereMonth('date', $i < 10 ? '0' . $i : $i)
                ->where('badge_id', '=', $badgeId)
                ->get();
        }
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = $months[$i][0];
        }

        //get driver
        $driver = Driver::where('PermitNumber', $badgeId)->first();
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];

        // get totals
        (float) $total_fare = 0;
        (float) $total_tip = 0;
        (float) $total_total = 0;
        // $months[7]->fare = ;

        for ($i = 1; $i <= 12; $i++) {
            if ($months[$i]->fare !== null) {
                $total_fare = $total_fare + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->fare);
            }
            if ($months[$i]->tip !== null) {
                $total_tip = $total_tip + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->tip);
            }
            if ($months[$i]->total !== null) {
                $total_total = $total_total + preg_replace('/(?<=\d)\s+(?=\d)/', '', $months[$i]->total);
            }
        }
        //affecting names
        for ($i = 1; $i <= 12; $i++) {
            switch ($i) {
                case "1":$months[$i]->name = "January";
                    break;
                case "2":$months[$i]->name = "February";
                    break;
                case "3":$months[$i]->name = "March";
                    break;
                case "4":$months[$i]->name = "April";
                    break;
                case "5":$months[$i]->name = "May";
                    break;
                case "6":$months[$i]->name = "June";
                    break;
                case "7":$months[$i]->name = "July";
                    break;
                case "8":$months[$i]->name = "August";
                    break;
                case "9":$months[$i]->name = "September";
                    break;
                case "10":$months[$i]->name = "October";
                    break;
                case "11":$months[$i]->name = "November";
                    break;
                case "12":$months[$i]->name = "December";
                    break;
            }

        }
        $total_fare = number_format($total_fare, 2, '.', ' ');
        $total_tip = number_format($total_tip, 2, '.', ' ');
        $total_total = number_format($total_total, 2, '.', ' ');
        //prepare dailyLogsheet array
        $date = date('Y') - 1;
        $dailyLogSheet['months'] = (array) $months;
        $dailyLogSheet['driver'] = $driver;
        $dailyLogSheet['name'] = $name;
        $dailyLogSheet['date'] = $date;
        $dailyLogSheet['total_fare'] = $total_fare;
        $dailyLogSheet['total_tip'] = $total_tip;
        $dailyLogSheet['total_total'] = $total_total;

        set_time_limit(1000);
        $name = $driver['FirstName'] . ' ' . $driver['LastName'];
        $pdf = PDF::loadView('admin.reports.DailyLogSheet.Year_pdf', compact('dailyLogSheet'))->setPaper('a3', 'landscape');
        $name_pdf = $name . '-' . $date . '.pdf';
        return $pdf->stream($name_pdf, array('Attachment' => 0));
    }

}
