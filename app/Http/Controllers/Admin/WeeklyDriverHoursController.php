<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

use PDF;

class WeeklyDriverHoursController extends Controller
{


     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
      public function WeeklyDriverHours(Request $request){
        $data = request()->validate([
          'date' => 'required|date',
        ]);


        $inputDate =$data['date'] ;
        $startDate=date("Y-m-d", strtotime($inputDate.'monday this week'));
        $endDate =  date("Y-m-d", strtotime($inputDate.'sunday this week'));

        $startDay = date("d", strtotime($startDate));
        $endDay = date("d", strtotime($endDate));

        $startMonth = date("M", strtotime($startDate)); // in letters
        $endMonth = date("M", strtotime($endDate)); // in letters

        $startYear = date("Y", strtotime($startDate)); 
        $endYear = date("Y", strtotime($endDate)); 
        
        $dateData = array(
            'inputDate'=>$inputDate,
            'startDay' =>$startDay ,
            'endDay' =>$endDay ,
            'startMonth' =>$startMonth ,
            'endMonth' =>$endMonth ,
            'startYear' =>$startYear,
            'endYear' =>$endYear 
        );
        $date = $startMonth.' '.$startDay.','.$startYear.' - '. $endMonth.' '.$endDay.','.$endYear;

        $shifts = DB::table('driver_shifts')
        ->join('drivers','drivers.PermitNumber','=','driver_shifts.driver_badge_id')
        ->where ('driver_shifts.shift_start_time','>',$startDate)
        ->where ('driver_shifts.shift_start_time','<',$endDate)
        ->orderby('driver_shifts.driver_badge_id')
        ->orderby('driver_shifts.shift_start_time')
        ->get()
        ->toArray();
        
          $shifts=(array) $shifts ;
          for($i=0 ;$i< count($shifts); $i++)
          {       
            $shifts[$i]=(array) $shifts[$i];
            $shifts[$i]['is_total_row']='0';
            $shifts[$i]['break_time'] = $shifts[$i]['total_time'] - $shifts[$i]['work_time'];
          }
          
      $total_time=0.00;
      $break_time=0.00;
      $work_time=0.00;
      
      for($i=0 ;$i< count($shifts); $i++)
      {
        
        $total_time=$total_time+$shifts[$i]['total_time'];
        $work_time=$work_time+$shifts[$i]['work_time'];
        $break_time=$break_time+$shifts[$i]['break_time'];

        if(isset( $shifts[$i+1]) )
        {
              if($shifts[$i]['driver_badge_id'] != $shifts[$i+1]['driver_badge_id'])
              {
               
                //to hours : minutes
                $total_time_hours = floor($total_time / 60);
                $total_time_min = $total_time - ($total_time_hours * 60);

                $work_time_hours = floor($work_time / 60);
                $work_time_min = $work_time - ($work_time_hours * 60);

                $break_time_hours = floor($break_time / 60);
                $break_time_min = $break_time - ($break_time_hours * 60);

                $total_row = array(array(
                  'FirstName'=>$shifts[$i]['FirstName'],
                  'LastName'=>$shifts[$i]['LastName'],
                  'is_total_row'=>'1',
                  'total_time_hours'=>$total_time_hours,'total_time_min'=>$total_time_min,
                  'work_time_hours'=>$work_time_hours,'work_time_min'=>$work_time_min,
                  'break_time_hours'=>$break_time_hours,'break_time_min'=>$break_time_min,
                ));
                //add to the array 
                array_splice( $shifts, $i+1, 0,$total_row );
                $i++;
                //reset
                $total_time=0;
                $break_time=0;
                $work_time=0;
              
          } 
        }
        else{
          $break_time = $total_time - $work_time;
                //to hours : minutes
                $total_time_hours = floor($total_time / 60);
                $total_time_min = $total_time - ($total_time_hours * 60);

                $work_time_hours = floor($work_time / 60);
                $work_time_min = $work_time - ($work_time_hours * 60);

                $break_time_hours = floor($break_time / 60);
                $break_time_min = $break_time - ($break_time_hours * 60);

                $total_row = array(array(
                  'FirstName'=>$shifts[$i]['FirstName'],
                  'LastName'=>$shifts[$i]['LastName'],
                  'is_total_row'=>'1',
                  'total_time_hours'=>$total_time_hours,'total_time_min'=>$total_time_min,
                  'work_time_hours'=>$work_time_hours,'work_time_min'=>$work_time_min,
                  'break_time_hours'=>$break_time_hours,'break_time_min'=>$break_time_min,
                ));
                //add to the array 
                array_splice( $shifts, $i+1, 0,$total_row );
                $i++;
                //reset
                $total_time=0;
                $break_time=0;
                $work_time=0;
        }
      }

     
    // Get current page form url e.x. &page=1
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    
    // Create a new Laravel collection from the array data
    $itemCollection = collect($shifts);

    // Define how many items we want to be visible in each page
    $perPage = 30;

    // Slice the collection to get the items to display in current page
    $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
    
    // Create our paginator and pass it to the view
    $paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
    
    // set url path for generted links
    $paginatedItems->setPath($request->url())->appends(request()->query());

    $shifts = $paginatedItems;
    $result['shifts']=$shifts;
    $result['date']=$date;
    return $result;


  }

  public function checkIsAValidDate($myDateString){
      return (bool)strtotime($myDateString);
  }

  public function printPDF($date)
  {
      if($this->checkIsAValidDate($date)){
            $inputDate =$date;
            $startDate=date("Y-m-d", strtotime($inputDate.'monday this week'));
            $endDate =  date("Y-m-d", strtotime($inputDate.'sunday this week'));
  
            $startDay = date("d", strtotime($startDate));
            $endDay = date("d", strtotime($endDate));
  
  
            $startMonth = date("M", strtotime($startDate)); // in letters
            $endMonth = date("M", strtotime($endDate)); // in letters
  
            $startYear = date("Y", strtotime($startDate)); 
            $endYear = date("Y", strtotime($endDate)); 
            
            $dateData = array(
                'inputDate'=>$inputDate,
                'startDay' =>$startDay ,
                'endDay' =>$endDay ,
                'startMonth' =>$startMonth ,
                'endMonth' =>$endMonth ,
                'startYear' =>$startYear,
                'endYear' =>$endYear 
            );
            $date = $startMonth.' '.$startDay.','.$startYear.' - '. $endMonth.' '.$endDay.','.$endYear;
          
          $shifts = DB::table('driver_shifts')
          ->join('drivers','drivers.PermitNumber','=','driver_shifts.driver_badge_id')
          ->where ('driver_shifts.shift_start_time','>',$inputDate)
          ->where ('driver_shifts.shift_start_time','<',$endDate)
          ->orderby('drivers.LastName')
          ->orderby('driver_shifts.shift_start_time')
          ->get();
      }
      else{
        return redirect(route('admin.reports.WeeklyDriverHours.index'))->with('success','date is incorrect');
      }
        



      if(count($shifts)<1)
      {
        return redirect(route('admin.reports.WeeklyDriverHours.index'))->with('success','no shifts found for the selected week');
      }
      else {
        set_time_limit(99000);
        // return view('admin.reports.WeeklyDriverHours.PDF_WeeklyDriverHours',compact('shifts','dateData'));
        $pdf = PDF::loadView('admin.reports.WeeklyDriverHours.PDF_WeeklyDriverHours',compact('shifts','dateData','date'))->setPaper('a3', 'landscape'); 
        $name = 'Weekly diver report - '.$date.'.pdf';
        return $pdf->stream($name,array('Attachment'=>0));
        return redirect(route('admin.reports.WeeklyDriverHours.index'))->with('success','pdf is generating');
      }

      
  }

}
