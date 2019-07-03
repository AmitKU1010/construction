<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use Helper;
use App\TeacherDiary;
use App\Models\User;
use App\Subject;
use App\Syllabus;

class TeacherDiaryController extends Controller
{
    public function addDiary(Request $request, $eid=null)
	{
		$viewData['pageTitle'] = 'Teachers Diary';
        $teachers = User::where('role_id', '4')->pluck('name', 'id');
		$periodList = array('' => '-- Select Period --');
		for($i=1;$i<8;$i++){
			$periodList[$i] = 'Period-'.$i;
		}
        /* Generate List of Available CLasses */
		$classList = array('' => '-- Select --', 'Nursery' => 'Nursery', 'LKG' => 'LKG', 'UKG' => 'UKG');
		for($i = 1; $i < 13; $i ++){
			$classList[$i] = 'STD - '.$this->integerToRoman($i);
        }
        $viewData['classes'] = $classList;
		
		/* Field specifiic Validations */	

		$getAmenities = array('WIFI' => 'WIFI', 'LANDLINE/MOBILE' => 'LANDLINE/MOBILE', 'AC' => 'AC', 'TV' => 'TV', 'FRIDGE' => 'FRIDGE');


        $validationRules = [

        ];

    	$validator = Validator::make($request->all(), $validationRules);
       	if(isset($eid) && $eid != null){
    		$getFormAutoFillup = TeacherDiary::whereId($eid)->first()->toArray();
    		//$admission_class = $getFormAutoFillup['admission_class'];
    	}else{
    		$getFormAutoFillup = array();
		}
		//dd($getFormAutoFillup);
		/* Submit all data */
    	if ($request->isMethod('post')){
    		if($validator->fails()){
            	return redirect('/hostel-room')->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != null){
		    		/* 
						Update
		    		*/
		    		$updateStudent = TeacherDiary::find($request->id);
		    		if(isset($request->_token) && $request->_token != ''){
		    			unset($request['_token']);
		    		}
		    		/* Calculation of total Amount on the GST and Discount */
		    		$totalPrice = $request->stock * $request->price;
		    		$discountInPrice = isset($request->discount) ? $totalPrice*($request->discount/100) : 0;
		    		$total = ($totalPrice + ($totalPrice*($request->gst/100)))-$discountInPrice;
		    		$request->request->add(['total' => $total]);

		    		//dd($request->toArray());
		    		if(TeacherDiary::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/voucher-edit/'.$request->id);
		    	}else{
					$saveData = $request->toArray();
					$saveData['user_id'] = Auth::user()->id;
					$saveData['teacher_id'] = Auth::user()->id;
				
					/**
					 * Check if the Data already exists or not
					 */
					//dd(TeacherDiary::where(['class' => $request->class, 'subject_id' => $request->subject_id, 'syllabi_id' => $request->syllabi_id, 'period' => $request->period ])->get());

					if(TeacherDiary::where(['class' => $request->class, 'subject_id' => $request->subject_id, 'syllabi_id' => $request->syllabi_id, 'period' => $request->period ])->count() == 0){
						$saveData = new TeacherDiary($saveData);

						if($saveData->save()){
							$request->session()->flash('message.level', 'success');
							$request->session()->flash('message.content', 'Teacher Diary was successfully added!');
						}else{
							session()->flash('status', ['danger', 'Updation of Teacher Diary Failed!']);
						}
						return redirect('/diary');
					}else{
						$request->session()->flash('message.level', 'danger');
						$request->session()->flash('message.content', 'Duplicate entry for teacher\'s Diary!');
						return back()->withErrors($validator)->withInput();
					}
		        	
		        }
            }
			
    	}
    	
		$viewData['customFields']['basic'] = array(
			'class'=>array('type' => 'select', 'value' => $classList,  'label'=>'Choose Class', 'mandatory'=>true, 'class' => 'class-list'),
			'subject_id'=>array('type' => 'select',  'value' => array('' => '--No Data--'), 'label'=>'Choose Subject', 'mandatory'=>true, 'class' => 'subject-list-down '),
			'separator_1' => array('type' => 'separator', 'label' => 'Lesson Details in Brief'),
			'syllabi_id'=>array('type' => 'select', 'value' => array('' => '--No Data--'), 'label'=>'Choose prefered Syllabus Title', 'mandatory'=>true, 'class' => 'syllabus-list'),
			'period'=>array('type' => 'select', 'value' => $periodList, 'label'=>'Choose Period', 'mandatory'=>true, 'class' => 'period-list'),
			'separator_2' => array('type' => 'separator', 'label' => 'Lesson Details in Brief'),
			'courses_covered'=>array('type' => 'textarea', 'label'=>'Enter Courses Covered', 'mandatory'=>true, 'class' => 'courses_covered'),
			'separator_3' => array('type' => 'separator', 'label' => '&nbsp;'),
			'estimated_date_of_completion'=>array('type' => 'text', 'label'=>'Date of Completion', 'id' => 'datepicker', 'mandatory'=>true, 'class' => 'estimated_date_of_completion', 'message' => 'Teaching Date'),
			
		);
		/**
		 * If the Login user is HOD then he/she can approve the techer's diary
		 */
		if(isset(Auth::user()->role_id) && Auth::user()->role_id == '3'){
			$viewData['customFields']['basic']['actual_date_of_completion'] = array('type' => 'text', 'label'=>'Actual Date of Completion', 'id' => 'datepicker', 'mandatory'=>true, 'class' => 'actual_date_of_completion');
			$viewData['customFields']['basic']['is_approved'] = array('type' => 'select', 'value' => array('0' => 'No', '1' => 'Yes'), 'label'=>'is Approved by HOD?', 'mandatory'=>true, 'class' => 'avail_beds', 'message' => 'TEST');
		}else{
			
		}
        
        $viewData['otherLinks'] =  array('link' => url('/').'/diary/list', 'text' => 'Diary List');
		
		return view('admin.TeachersDiary.teacher-diary', $viewData)->with($getFormAutoFillup);
	}
	public function diaryListing(Request $request)
	{
		DB::enableQueryLog();  

		$viewData['pageTitle'] = 'Teachers Diary Details';
		$viewData['otherLinks'] =  array('link' => url('/').'/diary', 'text' => 'Add Diary');
		/* Generate List of Available CLasses */
		$employeeList = array('' => '-- Select --', '0' => '.:: Other Person ::.');
    	$employeeList += User::pluck('name', 'id')->toArray();
		$validationRules = [
			'from_date' => 'required',
			'to_date' => 'required',
        ];

		$validator = Validator::make($request->all(), $validationRules);
		

		if ($request->isMethod('post')){
    		if($validator->fails()){
            	return back()->withErrors($validator)->withInput();
            }else{
				$diaries = TeacherDiary::with('subject', 'syllabus');
				if($request->from_date){
					$diaries = $diaries->where('estimated_date_of_completion', '>=', $request->from_date);
				}
				if($request->to_date){
					$diaries = $diaries->where('estimated_date_of_completion', '<=', $request->to_date);
				}
				$viewData['diaryList'] = $diaries->paginate(5);
			}
		}else{
			$viewData['diaryList'] = TeacherDiary::with('subject', 'syllabus')->orderBy('estimated_date_of_completion','asc')->paginate(5);
		}

		//$diaries = $diaries->with('subject', 'syllabus')->paginate(5);
		//$viewData['diaryList']

		//dd(DB::getQueryLog());
		$viewData['customFields']['basic'] = array(
			'from_date'=>array('type' => 'text', 'id' => 'datepicker', 'label'=>'From Date','mandatory'=>true),
			'to_date'=>array('type' => 'text', 'id' => 'datepicker', 'label'=>'To Date','mandatory'=>true),
			//'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number','mandatory'=>true),
			//'employee_id'=>array('type' => 'select', 'label'=>'Choose Class', 'value' => $employeeList, 'mandatory'=>true, 'class' => 'admission_class'),
    	);
		return view('admin.TeachersDiary.teachers-diary-list', $viewData);
	}
	public function trashDiary(Request $request, $evid = null)
	{
		if(TeacherDiary::where('id', $evid)->delete()){
			$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Diary was successfully Trashed!');
    	}else{
    		session()->flash('status', ['danger', 'Deletion of Diary Failed!']);
    	}
    	return redirect(url('/').'/diary/list');
	}
	public function approveDiary(Request $request, $evid = null)
	{
		if(TeacherDiary::where('id', $evid)->update(['is_approved' => '1'])){
			$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Diary was successfully Aproved!');
    	}else{
    		session()->flash('status', ['danger', 'Action was Failed!']);
    	}
    	return redirect(url('/').'/diary/list');
	}
	public function unapproveDiary(Request $request, $evid = null)
	{
		if(TeacherDiary::where('id', $evid)->update(['is_approved' => '0'])){
			$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Diary was successfully Aproved!');
    	}else{
    		session()->flash('status', ['danger', 'Action was Failed!']);
    	}
    	return redirect(url('/').'/diary/list');
	}
	/**
	 * 
	 */
	public function getSubjectList(Request $request)
	{
		$className = $request->cls;
		$subjectArr = [];

		if(Subject::where('class', $className)->count() > 0){
			$getSubList = Subject::where('class', $className)->select('name', 'id')->get();
			foreach ($getSubList as $key => $subject) {
				$subjectArr[$subject->id] = $subject->name;
			}
		}
		echo json_encode($subjectArr);
	}
	/**
	 * 
	 */
	public function getSyllabusDetails(Request $request)
	{
		$className = $request->cls;
		$subject_id = $request->sid;
		$syllabusArr = [];

		if(Syllabus::where('class', $className)->where('subject_id', $subject_id)->count() > 0){
			$getSyllabusList = Syllabus::where('class', $className)->where('subject_id', $subject_id)->select('title', 'id')->get();
			foreach ($getSyllabusList as $key => $syllabus) {
				$syllabusArr[$syllabus->id] = $syllabus->title;
			}
		}
		echo json_encode($syllabusArr);
	}
	/**
	 * Save the Syllabus Details of a particulat Teeacher
	 */
	public function saveSyllabus(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'class' => 'required',
            'subject_id' => 'required',
            'description' => 'required|max:1000|min:100'
		]);
		/* Submit all data */
    	if ($request->isMethod('post')){
    		if($validator->fails()){
            	return back()->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != null){
		    		/* 
						Update
		    		*/
		    		$updateStudent = Event::find($request->id);
		    		if(isset($request->_token) && $request->_token != ''){
		    			unset($request['_token']);
		    			unset($request['event_name2']);
		    			unset($request['event_name3']);
		    		}
		    		//dd($request->toArray());
		    		if(Event::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Student Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/event-edit/'.$request->id);
		    	}else{
					$request->request->add(['user_id' => Auth::user()->id]);
					$saveEvent = $request->toArray();
		    		$eventSave = new Syllabus($saveEvent);

		    		if($eventSave->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Syllabus was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Addition of Event Failed!']);
		        	}
		        	return redirect('/diary');
		        }
			}
		}
	}
}
