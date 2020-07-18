<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use App\SpecialShift;
use App\Shift;
use PDF;

class WeeklyTaxiShiftsController extends Controller
{







    public function WeeklyTaxiShifts(Request $request){
        // api variables declarations 



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
         


          $specialShifts = DB::table('specialShifts')
          ->join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
          ->select(
            'specialShifts.id',
            'specialShifts.PermitNumber',
            'specialShifts.ShiftType',
            'specialShifts.Car',
            'specialShifts.CarCoveredBy',
            'specialShifts.SingInTime',
            'drivers.FirstName',
            'drivers.LastName'
            )
          ->where ('specialShifts.SingInTime','>',$startDate)
          ->where ('specialShifts.SingInTime','<',$endDate)
          ->orderby("specialShifts.SingInTime")
          ->get()
          ->toArray();
          $result['specialShifts'] = $specialShifts;

        $director_comments = DB::table('director_comments')
        ->join('directors','directors.id','=','director_comments.director_id')
        ->select(
            'directors.name',
            'director_comments.id',
            'director_comments.director_id',
            'director_comments.comment',
            'director_comments.date',
            'director_comments.created_at'
        )
        ->where ('director_comments.date','>',$startDate)
        ->where ('director_comments.date','<',$endDate)
        ->orderby("director_comments.created_at",'desc')
        ->get()
        ->toArray();
        $result['director_comments'] = $director_comments;




        $shifts = DB::table('driver_shifts')
        ->join('drivers','drivers.PermitNumber','=','driver_shifts.driver_badge_id')
        ->where ('driver_shifts.shift_start_time','>',$startDate)
        ->where ('driver_shifts.shift_start_time','<',$endDate)
        ->orderby("vehicle_id")
        ->orderby("shift_start_time")
        ->get()
        ->toArray();



                $shifts=(array) $shifts ;
                for($i=0 ;$i< count($shifts); $i++)
                {       
                  $shifts[$i]=(array) $shifts[$i];
                  $shifts[$i]['is_total_row']='0';
                }

                $count=0;
                for($i=0 ;$i< count($shifts); $i++)
                {
                  $count++;
                  if(isset( $shifts[$i+1]) )
                  {
                        if($shifts[$i]['vehicle_id'] !== $shifts[$i+1]['vehicle_id'] )
                        {
                        
                      

                          $total_row = array(array(
                            'vehicle_id'=>$shifts[$i]['vehicle_id'],
                            'count'=>$count,
                            'is_total_row'=>'1',
                          ));
                          //add to the array 
                          array_splice( $shifts, $i+1, 0,$total_row );
                          $i++;
                          //reset
                          $count=0;
            
                        
                        } 
                  }
                  else
                  {
                    $total_row = array(array(
                      'vehicle_id'=>$shifts[$i]['vehicle_id'],
                      'count'=>$count,
                      'is_total_row'=>'1',
                    ));
                    //add to the array 
                    array_splice( $shifts, $i+1, 0,$total_row );
                    $i++;
                    //reset
                    $count=0;
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
              $result['dateData']=$dateData;
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
            ->orderby("vehicle_id")
            ->orderby("shift_start_time")
            ->get()
            ->toArray();

              $specialShifts = DB::table('specialShifts')
              ->join('drivers','drivers.PermitNumber','=','specialShifts.PermitNumber')
              ->where ('specialShifts.SingInTime','>',$startDate)
              ->where ('specialShifts.SingInTime','<',$endDate)
              ->orderby("specialShifts.SingInTime")
              ->get()
              ->toArray();


              
              $director_comments = DB::table('director_comments')
              ->join('directors','directors.id','=','director_comments.director_id')
              ->select(
                'directors.name',
                'director_comments.id',
                'director_comments.director_id',
                'director_comments.comment',
                'director_comments.date',
                'director_comments.created_at'
                )
              ->where ('director_comments.date','>',$startDate)
              ->where ('director_comments.date','<',$endDate)
              ->orderby("director_comments.created_at",'desc')
              ->get()
              ->toArray();
          }
          else{
            return redirect(route('admin.reports.WeeklyTaxiShifts.index'))->with('success','date is incorrect');
          }
      
      
          if(count($shifts)<1)
          {
          return redirect(route('admin.reports.WeeklyTaxiShifts.index'))->with('success','No shifts found for the selected week');
          }


          set_time_limit(1000);
          // return view('admin.reports.WeeklyTaxiShifts.PDF_WeeklyTaxiShifts',compact('shifts','dateData','specialShifts')); 
          $pdf = PDF::loadView('admin.reports.WeeklyTaxiShifts.PDF_WeeklyTaxiShifts',compact('shifts','date','specialShifts','director_comments'))->setPaper('a3', 'landscape'); 
          $name = 'Weekly taxi shifts report - '.$date.'.pdf';
          return $pdf->stream($name,array('Attachment'=>0));
          return redirect(route('admin.reports.WeeklyTaxiShifts.index'))->with('success','pdf is generating');
    }

}
