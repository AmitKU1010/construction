<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use DB;
use PDF;
use Excel;
use Helper;
use App\User;
use App\Salary; 
use App\SalariesDate;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function salary(Request $request)
    {
        $viewData['pageTitle'] = "Manage Salary of Employees's";
        $employeeList = User::pluck('name', 'id')->toArray();
        $years = [];
        for ($year=1900; $year <= 2015; $year++) $years[$year] = $year;


        /**
         * Validation Check Parameters
         */
        $validationRules = [
            'employee_id' => 'required',
            'year' => 'required',
            'month' => 'required'
        ];
        $validator = Validator::make($request->all(), $validationRules);
        if ($request->isMethod('post')){
    		if($validator->fails()){
            	return back()->withErrors($validator)->withInput();
            }else{
               // dd($request->all());

                $getDate = $request->month.'/01/'.$request->year;
                $lastday = date('t',strtotime($getDate));
                $fromDate = $request->year.'-'.$request->month.'-'.'01';
                $toDate = $request->year.'-'.$request->month.'-'.$lastday;
                
                $viewData['person'] = $userData = User::where('id', $request->employee_id)->first();
                $viewData['person_count'] = User::where('id', $request->employee_id)->count();

         

                $totalNumberOfDays = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
                $viewData['dates'] = $dates = Helper::dateRange($fromDate, $toDate);
                $viewData['dailly_wedge'] = round($userData['net_salary']/$totalNumberOfDays, 2);
                //$viewData['basic'] = $userData['basic'];

                //
                $checkExist = Salary::where('employee_id', $request->employee_id)
                                    ->where('year', $request->year)
                                    ->where('month', $request->month)->get();
                $oldDates = $checkExist->toArray();
                if(isset($oldDates[0]['over_time']) && $oldDates[0]['over_time'] !=''){
                    $viewData['over_time'] = $oldDates[0]['over_time'];
                    $viewData['over_time_days'] = $oldDates[0]['over_time_days'];
                }
                $oldDatePresentArr = []; $oldIsPaid = '0';
                foreach ($oldDates as $oldDate) {
                    if(isset($oldDate['is_present']) && $oldDate['is_present'] == '1'){
                        $oldDatePresentArr[] = $oldDate['attendance_date'];
                    }
                    if(isset($oldDate['is_paid']) && $oldDate['is_paid'] == '1'){
                        $oldIsPaid = '1';
                    }
                }
                $viewData['oldDatePresent'] = $oldDatePresentArr;
                $viewData['is_paid_status'] = $oldIsPaid;

 

                if(isset($request->attendance_date) && count($request->attendance_date) > 0){
             $attendanceList = [];

                    $basicAttendanceData['year'] = $request->year;
                    $basicAttendanceData['month'] = $request->month;
                    $basicAttendanceData['employee_id'] = $request->employee_id;
                    $basicAttendanceData['is_present'] = '0';

                    $basicAttendanceData['dailly_wedge'] = $request->dailly_wedge;
                    $basicAttendanceData['over_time'] = $request->over_time;
                    $basicAttendanceData['over_time_days'] = $request->over_time_days;
                    $basicAttendanceData['present_days'] = $request->present_days;
                    $basicAttendanceData['total_wedge'] = $request->total_wedge;
                    $basicAttendanceData['is_paid'] = $request->is_paid;
                    $basicAttendanceData['updated_at'] = date('Y-m-d h:i:s');

                    
                    
                    //dd($attendanceList);
                    //Check if any Salary Information exists or not
                    $checkExist = Salary::where('employee_id', $request->employee_id)
                                    ->where('year', $request->year)
                                    ->where('month', $request->month)->first();
                                  //   $checkExist11= $checkExist;
                                  //   dump($checkExist11);
                                  // $checkExist11->count()
                                    $keyckeck=0;
                                    $checkExistCount=0;
                                    foreach ($checkExist as $keyckeck => $value) {
                                        $checkExistCount=$keyckeck+1;
                                    }
                                  
    //    echo "ok";
                    if((isset($checkExist) && ($checkExistCount == 0)) || is_null($checkExist) ){
                      //  echo "ok ok ok ";
                        /**
                         * New Entry -> Save the Data
                         */
                        $salarySave = new Salary($basicAttendanceData);
                       //  print_r($salarySave);
                       // exit;
                        if($salarySave->save()){
                            $salarySaveId = $salarySave->id;
                            $finalSalarySave = 0;
                            foreach ($dates as $key => $date) {
                                $attendanceList['salary_id'] = $salarySaveId;
                                $attendanceList['attendance_date'] = $date;
                                $attendanceList['created_at'] = date('Y-m-d h:i:s');
                                $attendanceList['updated_at'] = date('Y-m-d h:i:s');
                                $attendanceList['is_present'] = 0;
                                if(in_array($date, $request->attendance_date)){
                                    $attendanceList['is_present'] = '1';
                                }
                                $salaryDatesSave = new SalariesDate($attendanceList);
                                if($salaryDatesSave->save()){
                                    $finalSalarySave ++;
                                }
                            }
                            if(isset($finalSalarySav) && $finalSalarySav > 0){
                                $request->session()->flash('message.level', 'info');
                                $request->session()->flash('message.content', 'Salary Details are Added Successfully !');
                            }else{
                                session()->flash('status', ['danger', 'Addition was Failed!']);
                            }
                        }
                    }else{

                        /**
                         * Old Entry -> Update the Salary Data
                         */
                        $updateArr = [];
                        $loopComplete = 0;
                        /**
                         * First table Update
                         */
                        $basicAttendanceUpdateData['dailly_wedge'] = $request->dailly_wedge;
                        $basicAttendanceUpdateData['over_time'] = $request->over_time;
                        $basicAttendanceUpdateData['over_time_days'] = $request->over_time_days;
                        $basicAttendanceUpdateData['present_days'] = $request->present_days;
                        $basicAttendanceUpdateData['total_wedge'] = $request->total_wedge;
                        $basicAttendanceUpdateData['is_paid'] = $request->is_paid;
                        $basicAttendanceUpdateData['updated_at'] = date('Y-m-d h:i:s');
                        $update=Salary::where([
                            'employee_id' => $request->employee_id,'year' => $request->year,'month' => $request->month
                        ])->update($basicAttendanceUpdateData);
//                         echo "ok";
//                         echo $update;
//  print_r($basicAttendanceUpdateData);echo "<br/>";
//   //echo count($checkExist);
// exit;  
  
                        if($update){
                             
                            /**
                             * 2ND table Update
                             */
                            $salaryDatesUpdateId = $checkExist['id'];
                            $finalSalarySave = 0;
                            SalariesDate::where('salary_id', $salaryDatesUpdateId)->delete();
                            foreach ($dates as $key => $date) {
                                $attendanceList['salary_id'] = $salaryDatesUpdateId;
                                $attendanceList['attendance_date'] = $date;
                                $attendanceList['created_at'] = date('Y-m-d h:i:s');
                                $attendanceList['updated_at'] = date('Y-m-d h:i:s');
                                $attendanceList['is_present'] = 0;
                                if(in_array($date, $request->attendance_date)){
                                    $attendanceList['is_present'] = '1';
                                }
                                $salaryDatesSave = new SalariesDate($attendanceList);
                                if($salaryDatesSave->save()){
                                    $finalSalarySave ++;
                                }
                            }
                            if(isset($finalSalarySave) && $finalSalarySave > 0){
                                $request->session()->flash('message.level', 'info');
		    			        $request->session()->flash('message.content', 'Salary Details are Updated Successfully !');
                            }else{
                                session()->flash('status', ['danger', 'Updation was Failed!']);
                            }
                        }
                    }
                    return redirect('/admin/salary');
                }
            }
        }
        $viewData['basic'] = array(
			'employee_id'=>array('type' => 'select', 'label'=>'Choose Employee', 'value' => array('' => '--Select--')+$employeeList, 'mandatory'=>true, 'class' => 'employee_id select2'),
			'year'=>array('type' => 'select', 'label'=>'Select Year', 'value' => array('' => '--Select--')+Helper::listAllYears('6'), 'mandatory'=>true, 'class' => 'year'),
			'month'=>array('type' => 'select', 'label'=>'Select Month', 'value' => array('' => '--Select--')+Helper::listAllShortMonths(), 'mandatory'=>true, 'class' => 'month')

        );
        $viewData['otherLinks']['text'] = 'View all Salary';
        $viewData['otherLinks']['url'] = url('/').'/admin/salary/list';
        return view('admin.Salary.salary', $viewData);
    }
    public function salaryList(Request $request, $xportType = null)
    {
        $currentRoute = Route::currentRouteName();
        $viewData['pageTitle'] = "List of Salary of Employees's";
        $employeeList = User::pluck('name', 'id')->toArray();
        $currentRoute = $viewData['currentRoute'] = Route::currentRouteName();
        $filterYear = '2018';
        $filterMonth = '11';
        // $salaryList =  Salary::with('user', 'dates')->get();
        // dd($salaryList->toArray());
        $vochers=DB::table('vouchers')->get();
        $viewData['vochers']=$vochers;

        if ($request->isMethod('post')){
			$salaryList = Salary::whereNotNull('id');
			if(isset($request->employee_id) && $request->employee_id != ''){
				$salaryList->where('employee_id', $request->employee_id);
			}
			if(isset($request->month) && $request->month != ''){
				$salaryList->where('month', '=', $request->month);
            }
            if(isset($request->year) && $request->year != ''){
				$salaryList->where('year', '=', $request->year);
			}
			$salaryList = $salaryList->with('user', 'dates')->paginate('100');
		}else{
			$salaryList =  Salary::with('user', 'dates')->paginate('100');
        }
        
        $viewData['salaryList'] = $salaryList;
        /**
         * Export to Excel in different formats
         */

        if(isset($xportType) && $xportType != null){
            $exportSalary = Salary::with('user', 'user.role')->groupBy('employee_id', 'year', 'month')->get();
            $exportalaryData = [
                [
                    'SL NO', 'EMPLOYEE CODE', 'NAME', 'DESIGNATION', 'DAILLY WAGES', 'OT', 'TOTAL ATTENDANCE', 'ATTENDANCE AMOUNT', 'PF (12%) DEDUCTION', 'OTHER DEDUCTION', 'NET PAYABLE'
                ]
            ];
           
            foreach ($exportSalary as $key => $salary) {
                //dump($salary->toArray());
                $otherDeduction = '0';
                $totalPayableAmount = $salary['total_wedge'] + ($salary['over_time_days']*$salary['over_time']) - (($salary['total_wedge'] * 0.12) + $otherDeduction);
                
                $exportalaryData[] = [
                    $key+1,
                    $salary['employee_id'],
                    $salary['user']['name'],
                    $salary['user']['role']['name'],
                    $salary['dailly_wedge'],
                    ($salary['over_time_days']*$salary['over_time']),
                    $salary['present_days'],
                    $salary['total_wedge'],
                    ($salary['total_wedge'] * 0.12),
                    $otherDeduction,
                    $totalPayableAmount
                ];
            }
            //dd($salaryList);
            //dd($exportalaryData);

            //exit;
            return \Excel::create('users-list-sample', function($excel) use ($exportalaryData) {
                $excel->sheet('sheet name', function($sheet) use ($exportalaryData)
                {
                    $sheet->fromArray($exportalaryData);
                });
            })->download($xportType);
        }
        $viewData['customFields']['basic'] = array(
			'employee_id'=>array('type' => 'select', 'label'=>'Choose Employee', 'value' => array('' => '--Select--')+$employeeList, 'mandatory'=>true, 'class' => 'employee_id select2'),
			'year'=>array('type' => 'select', 'label'=>'Select Year', 'value' => array('' => '--Select--')+Helper::listAllYears('6'), 'mandatory'=>true, 'class' => 'year'),
			'month'=>array('type' => 'select', 'label'=>'Select Month', 'value' => array('' => '--Select--')+Helper::listAllShortMonths(), 'mandatory'=>true, 'class' => 'month')
        );
        $viewData['otherLinks']['text'] = 'Add new Salary';
        $viewData['otherLinks']['url'] = url('/').'/admin/salary';
        if(isset($currentRoute) && $currentRoute == 'report-salary-pdf'){
			$pdf = PDF::loadView('admin.Salary.salary-list', $viewData);
        	//return view('pdf.book-issue-invoice', ['data' => $data]);
			return $pdf->download('Employee-Salary-Report-datedOn-'.date('Ymdhis').'.pdf');
		}else{
            return view('admin.Salary.salary-list', $viewData);
			
        }
        
        //return view('admin.Salary.salary-list', $viewData);
    }
    public function exportToExcel($type = 'xlsx')
    {
        
        return \Excel::create('users-list-sample', function($excel) use ($products) {

            $excel->sheet('sheet name', function($sheet) use ($products)

            {

                $sheet->fromArray($products);

            });

        })->download($type);
    }
}
