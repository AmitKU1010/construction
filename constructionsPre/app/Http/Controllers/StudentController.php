<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Crypt;
use App\Admission;
use DB;
use PDF;
use Helper;
use App\FeesMaster;
use App\AdmissionFee;
use App\AdmissionFeesDetail;
use App\StudentDetail;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function admission(Request $request, $sid=null)
    {
        
        //Debugbar::info($object);
    	$otherLinks = '<a href="/student-list">Admission List</a>';
    	/* generate Year Range for Academic Year */
    	$max_year = date('Y');
		$current_year = $max_year - 6;
		for($i = $current_year; $i <= $max_year; $i++){
		    $year_array[$i] = $i.'-'.($i+1);
		}
		$getStudentDetails = array('admission_date' => date('Y-m-d'));
		/* Generate List of Available CLasses */
		$classList = array('' => '-- Select --', 'Nursery' => 'Nursery', 'LKG' => 'LKG', 'UKG' => 'UKG');
		for($i = 1; $i < 13; $i ++){
			$classList[$i] = 'STD - '.$this->integerToRoman($i);
		}

    	/* Field specifiic Validations */
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'gender' => 'required',
            'father_mobile_no' => 'required|digits:10',
            'dob' => 'required',
            'aadhar_card' => 'required|digits:16',
            'academic_year' => 'required',
            'admission_class' => 'required',
            'section'   => 'required',
            'academic_year' => 'required',
            'father_name' => 'required',
            'mother_name' => 'required',
            'mother_mobile_no' => 'required|digits:10',
            'present_at' => 'required',
            'present_pin' => 'required|digits:6',
            'permanent_at' => 'required',
            'permanent_pin' => 'required|digits:6',
            'residental_type' => 'required',

        ]);
      
    	/* Submit all data */
    	if ($request->isMethod('post')){
    		$md5Time = md5(time());
    		$uploadFilesName = array();
    		
    		/* All File Names are Generated Here */
			$blood_group_proof 	= count($request->blood_group_proof_raw) > 0 ? $md5Time.'-'.$request->blood_group_proof_raw->getClientOriginalName() : '';
			$birth_certificate 	= count($request->birth_certificate_raw) > 0 ? $md5Time.'-'.$request->birth_certificate_raw->getClientOriginalName() : '';
			$aadhar_card_proof 	= count($request->aadhar_card_proof_raw) > 0 ? $md5Time.'-'.$request->aadhar_card_proof_raw->getClientOriginalName() : '';

			/* All Images are Moved to the Corresponding Folders Here */
    		if(isset($request->blood_group_proof_raw) && count($request->blood_group_proof_raw) > 0){
    			echo "Dasdasdasdsadsadad";
	    		$request->file('blood_group_proof_raw')->move(
			        base_path() . '/public/blood_group_proof/', $blood_group_proof
			    );
			}
			if(isset($request->birth_certificate_raw)  && count($request->birth_certificate_raw) > 0){
			    $request->file('birth_certificate_raw')->move(
			        base_path() . '/public/birth_certificate/', $birth_certificate
			    );
			}

			if(isset($request->aadhar_card_proof_raw) && count($request->aadhar_card_proof_raw) > 0){
			    $request->file('aadhar_card_proof_raw')->move(
			        base_path() . '/public/aadhar_card_proof/', $aadhar_card_proof
			    );
			}
			
			/* All File Names are Set Here */
			$request->request->add(['blood_group_proof' => $blood_group_proof]);
			$request->request->add(['birth_certificate' => $birth_certificate]);
			$request->request->add(['aadhar_card_proof' => $aadhar_card_proof]);

			/* Save Object pushed to Model */
			$admissionObj = new Admission($request->toArray());
    		if($validator->fails()){
            	return redirect('/admission')->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != ''){
            		/* 
						Update
            		*/
                    //dd($request->toArray());
            		$updateStudent = Admission::find($request->id);
            		if(Admission::where('id', $request->id)->update($request->toArray())){
            			$request->session()->flash('message.level', 'info');
	        			$request->session()->flash('message.content', 'Student Details are Updated Successfully !');
            		}else{
	            		session()->flash('status', ['danger', 'Addition was Failed!']);
	            	}
	            	return redirect('/admission/'.$request->id);
            	}else{
            		if($admissionObj->save()){
	            		$request->session()->flash('message.level', 'success');
	        			$request->session()->flash('message.content', 'Post was successfully added!');
	            	}else{
	            		session()->flash('status', ['danger', 'Addition of User Failed!']);
	            	}
	            	return redirect('/admission');
	            }
            	
            }      

    	}
    	
    	/* Generate Custom Fields Array */
    	$customFields['general'] = array(
    		'name'=>array('type' => 'text', 'label'=>'Name of the Pupil', 'class' => 'capsLock alpha', 'mandatory'=>true),
    		'gender'=>array('type' => 'select', 'value' => array('' => '-- Select --','Girl' => 'Girl', 'Boy' => 'Boy', 'Other' => 'Other'), 'label'=>'Gender','mandatory'=>true),
    		'dob'=>array('type' => 'text', 'label'=>'Date of Birth', 'id' => 'datepicker', 'mandatory'=>true),
    		'mother_language'=>array('type' => 'text', 'label'=>'Mother Language', 'mandatory'=>true),
    		'secondary_language'=>array('type' => 'select', 'value' => array('' => '-- Select --', 'Oriya' => 'Oriya', 'Hindi' => 'Hindi', 'Sanskrit' => 'Sanskrit'), 'label'=>'Secondary Language', 'mandatory'=>true),
    		'blood_group'=>array('type' => 'select', 'value' => array('' => '-- Select --','A+' => 'A+', 'B+' => 'B+', 'AB+' => 'AB+', 'O+' => 'O+', 'A-' => 'A-', 'B-' => 'B-'), 'label'=>'Blood Group', 'mandatory'=>true),
    		'blood_group_proof_raw'=>array('type' => 'file', 'label'=>'Blood Group Certificate', 'mandatory'=>true),
    		'birth_certificate_raw'=>array('type' => 'file', 'label'=>'Birth Certificate', 'optColDiv' => 'col-md-2', 'mandatory'=>true),
    		'aadhar_card_proof_raw'=>array('type' => 'file', 'label'=>'Aadhar Card Proof', 'mandatory'=>true),
    		'aadhar_card'=>array('type' => 'text', 'label'=>'Aadhar Card', 'mandatory'=>true),
    		'academic_year' => array('type' => 'select', 'value' => $year_array, 'label'=>'Academic Year', 'mandatory'=>true),
            'admission_class' => array('type' => 'select', 'value' => $classList, 'label'=>'Choose Class', 'mandatory'=>true),
            'section' => array('type' => 'select', 'value' => ['' => '--Select--', 'A' => 'A','B'=>'B', 'C'=>'C', 'D' => 'D'], 'label'=>'Choose Class', 'mandatory'=>true),
    	);
    	$customFields['father'] = array(
    		'father_name'=>array('type' => 'text', 'label'=>'Father\'s Name', 'mandatory'=>true),
    		'father_qualification'=>array('type' => 'text', 'label'=>'Qualification','mandatory'=>true),
			'father_occupation'=>array('type' => 'text', 'label'=>'Occupation','mandatory'=>true),
			'father_official_designation'=>array('type' => 'text', 'label'=>'Official Designation','mandatory'=>true),
			'father_office_no'=>array('type' => 'text', 'label'=>'Office No.', 'class' => 'mobile', 'mandatory'=>true),
			'father_residential_no'=>array('type' => 'text', 'label'=>'Residential No', 'class' => 'mobile', 'mandatory'=>true),
			'father_mobile_no'=>array('type' => 'text', 'label'=>'Mobile No.', 'class' => 'mobile', 'mandatory'=>true),
			'father_email_id'=>array('type' => 'text', 'label'=>'E-Mail Id','mandatory'=>true),
    	);
    	$customFields['mother'] = array(
    		'mother_name'=>array('type' => 'text', 'label'=>'Mother\'s Name', 'mandatory'=>true),
    		'mother_qualification'=>array('type' => 'text', 'label'=>'Qualification','mandatory'=>true),
			'mother_occupation'=>array('type' => 'text', 'label'=>'Occupation','mandatory'=>true),
			'mother_official_designation'=>array('type' => 'text', 'label'=>'Official Designation','mandatory'=>true),
			'mother_office_no'=>array('type' => 'text', 'label'=>'Office No.', 'class' => 'mobile', 'mandatory'=>true),
			'mother_residential_no'=>array('type' => 'text', 'label'=>'Residential No', 'class' => 'mobile', 'mandatory'=>true),
			'mother_mobile_no'=>array('type' => 'text', 'label'=>'Mobile No.', 'class' => 'mobile', 'mandatory'=>true),
			'mother_email_id'=>array('type' => 'text', 'label'=>'E-Mail Id','mandatory'=>true),
    	);
    	$customFields['present_address'] = array(
    		'present_plot_house_no'=>array('type' => 'text', 'label'=>'Plot or House No.', 'mandatory'=>true),
    		'present_at'=>array('type' => 'text', 'label'=>'AT','mandatory'=>true),
			'present_post'=>array('type' => 'text', 'label'=>'POST','mandatory'=>true),
			'present_ps'=>array('type' => 'text', 'label'=>'PS','mandatory'=>true),
			'present_dist'=>array('type' => 'text', 'label'=>'DIST','mandatory'=>true),
			'present_state'=>array('type' => 'text', 'label'=>'STATE','mandatory'=>true),
			'present_pin'=>array('type' => 'text', 'label'=>'PIN', 'class' => 'mobile', 'mandatory'=>true),
    	);
    	$customFields['permanent_address'] = array(
    		'permanent_plot_house_no'=>array('type' => 'text', 'label'=>'Plot or House No.', 'mandatory'=>true),
    		'permanent_at'=>array('type' => 'text', 'label'=>'AT','mandatory'=>true),
			'permanent_post'=>array('type' => 'text', 'label'=>'POST','mandatory'=>true),
			'permanent_ps'=>array('type' => 'text', 'label'=>'PS','mandatory'=>true),
			'permanent_dist'=>array('type' => 'text', 'label'=>'DIST','mandatory'=>true),
			'permanent_state'=>array('type' => 'text', 'label'=>'STATE','mandatory'=>true),
			'permanent_pin'=>array('type' => 'text', 'label'=>'PIN', 'class' => 'mobile', 'mandatory'=>true),
    	);
    	$customFields['residental_type'] = array(
    		'residental_type'=>array('type' => 'select', 'value' => array('' => '-- Select --','transport' => 'Transport', 'dayboarding' => 'Dayboarding', 'hostel' => 'Hostel', 'localised' => 'Localised'), 'label'=>'Resident Type', 'mandatory'=>true),
			'residental_type_amount'=>array('type' => 'text', 'label'=>'Amount','mandatory'=>true),
    	);
    	$customFields['other_informations'] = array(
    		'serious_illness'=>array('type' => 'text', 'label'=>'Serious Illness', 'mandatory'=>true),
    		'identified_allergies'=>array('type' => 'text', 'label'=>'Identified Allergies','mandatory'=>true),
			'previous_edication'=>array('type' => 'text', 'label'=>'Previous Education','mandatory'=>true),
			'special_intrest'=>array('type' => 'text', 'label'=>'Special Intrest','mandatory'=>true),
			'two_person_allowed'=>array('type' => 'text', 'label'=>'Only Two Person Allow to Visit','mandatory'=>true),
			'mode_of_transport'=>array('type' => 'select', 'label' => 'Mode of Transport', 'value' => array('' => '-- Select --','bus' => 'BUS', 'own_arrangement' => 'Own Arrangement'), 'mandatory'=>true),
			'caste'=>array('type' => 'select', 'label' => 'Category', 'value' => array('' => '-- Select --','general' => 'General', 'sc' => 'SC','st' => 'ST', 'obc' => 'OBC','handicpped' => 'Handicapped'), 'mandatory'=>true),
			'whether_child_of_staff'=>array('type' => 'select', 'label' => 'Wheater Staff Child', 'value' => array('' => '-- Select --','1' => 'YES', '0' => 'NO'), 'mandatory'=>true),
			//'application_submit_date'=>array('type' => 'text', 'label'=>'Application Submit Date','mandatory'=>true),
			'application_fee_receipt_no'=>array('type' => 'text', 'label'=>'Application Fee Receipt No','mandatory'=>true),
			'photo_copy_front_side'=>array('type' => 'file', 'label'=>'Photo Copy of Application Form (Front Side)', 'mandatory'=>true),
            'photo_copy_back_side'=>array('type' => 'file', 'label'=>'Photo Copy of Application Form (Back Side)', 'mandatory'=>true),
            'admission_date'=>array('type' => 'text', 'label'=>'Admission Date', 'value' => date('Y-m-d'), 'class'=>'admission_date hidetextbox' , 'id' => 'datepicker', 'mandatory'=>true, 'readonly' => 'readonly'),		
    	);

    	if(isset($sid) && $sid != null){
    		$getStudentDetails = Admission::find($sid)->toArray();
            //dd($getStudentDetails);
    	}

    	return view('admin.admission', ['otherLinks' => array('link' => url('/').'/admission-list', 'text' => 'Admision List'), 'pageTitle' => isset($sid) && $sid != '' ? 'Application Form (Edit)':'Application Form', 'customFields' => $customFields, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details'] )->with($getStudentDetails);
    }

    public function admissionList(Request $request)
    {
        // $romans = array(
        //     'M' => 1000,
        //     'CM' => 900,
        //     'D' => 500,
        //     'CD' => 400,
        //     'C' => 100,
        //     'XC' => 90,
        //     'L' => 50,
        //     'XL' => 40,
        //     'X' => 10,
        //     'IX' => 9,
        //     'V' => 5,
        //     'IV' => 4,
        //     'I' => 1,
        // );
        // $studentDetails = StudentDetail::get()->toArray();
        // $insertAdmissionData = array();
        // foreach ($studentDetails as $key => $value) {
        //     $insertAdmissionData[$key]['name'] = $value['stud_name'];
        //     $insertAdmissionData[$key]['gender'] = $value['gender'];
        //     $insertAdmissionData[$key]['dob'] = date('Y-m-d', strtotime($value['dob']));
        //     $insertAdmissionData[$key]['blood_group'] = $value['bloodgroup'];
        //     $insertAdmissionData[$key]['academic_year'] = $value['aca_year'];
        //     $insertAdmissionData[$key]['admission_class'] = Helper::romanToNumber($value['admitted_class']);
        //     $insertAdmissionData[$key]['section'] = $value['section'];
        //     $insertAdmissionData[$key]['created_at'] = $value['created'];
        //     $insertAdmissionData[$key]['updated_at'] = $value['created'];
        // }
        // Admission::insert($insertAdmissionData);
        // exit;
        /* Generate List of Available CLasses */
        $classList = array('' => '-- Select --', 'Nursery' => 'Nursery', 'LKG' => 'LKG', 'UKG' => 'UKG');
        for($i = 1; $i < 13; $i ++){
            $classList[$i] = 'STD - '.$this->integerToRoman($i);
        }
        $admission = Admission::where('is_active', '1');

        /**
         * Putting FIlter in Action ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
         */
        $conditions = array();
        $appends = array();
        if ($request->has('name') && isset($request->name) && $request->name != ''){
            $admission->where('name', 'like', '%'.$request->name.'%');
            $appends += array('name' => $request->name);
        }
        if($request->has('s_father_name') && isset($request->s_father_name) && $request->s_father_name != ''){
            $admission->where('father_name', 'like', '%'.$request->s_father_name.'%');
            $appends += array('s_father_name' => $request->s_father_name);
        }
        if($request->has('admission_class') && isset($request->admission_class) && $request->admission_class != ''){
            $admission->where('admission_class', '=', $request->admission_class);
            $appends += array('admission_class' => $request->admission_class);
        }

        /* Final Statement */
        $getList = $admission->paginate(15)->appends($appends);
        $customFields['search'] = array(            
            'name'=>array('type' => 'text', 'label'=>'Student Name', 'col_num' => '3', 'mandatory'=>true),
            's_father_name'=>array('type' => 'text', 'label'=>'Father', 'col_num' => '3', 'mandatory'=>true),
            'admission_class'=>array('type' => 'select', 'value' => $classList, 'label'=>'Class', 'col_num' => '2', 'mandatory'=>true),
            'from'=>array('type' => 'text', 'label'=>'From Date', 'col_num' => '2', 'id' => 'datepicker', 'mandatory'=>true),
            'to'=>array('type' => 'text', 'label'=>'To Date', 'col_num' => '2', 'id' => 'datepicker2', 'mandatory'=>true),
        );
    	return view('admin.admission-list', ['otherLinks' => array('link' => url('/').'/admission', 'text' => 'New Admision'), 'admissionList' => $getList, 'pageTitle' => 'Admission List', 'loopInit' => '1', 'customFields' => $customFields])->with($request->all());
    }
    public function admissionDelete(Request $request, $sid=null)
    {
    	if(Admission::where('id', $sid)->delete()){
			$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Admission Record Was Delted from Server!');
    	}else{
    		session()->flash('status', ['danger', 'Deletion Failed!']);
    	}
    	return redirect('/admission');
    	
    }
    /*
        Info : Student Fees payment part
        Date : 14-11-2017
    */
    public function feesPayment(Request $request, $sid=null)
    {
        /* Check payment staus */
        $getCurrentYearRange = Helper::listAllYears(); 
        $getCurrentYearRange = reset($getCurrentYearRange);
        $returnDetails = 0;
        $getListOfMonth = Helper::listAllMonths();

        $getStudentPaymentDetails =  Helper::getStudentPaymnetStatus('1', '5', $getCurrentYearRange);
        $getStudentPaymentDetails = json_decode($getStudentPaymentDetails, true); 
        //dd($getStudentPaymentDetails);
       // exit;

       


        /*dd($getListOfMonth);
        exit;*/




        $data[] = '';
        $formAutoFill = array(); $cnt = 0; $data['submitBtnName'] = 'Save payment Details';
        $getPaymentHistory = array();
        $data['months'] = array('' => '-- Select --');
        $data['months'] += Helper::listAllMonths();
        $data['studentDetails'] = Helper::getStudentDetails($sid, ['id', 'name','father_name', 'mother_name', 'admission_class']);
        $studentClass = isset($data['studentDetails']->admission_class) ? $data['studentDetails']->admission_class : '';
        /* Get all Payment Structures of the Coressp. Class */
        $data['getPayments'] = FeesMaster::where('class', $studentClass)->get()->toArray();
    
        /* generate Year Range for Academic Year */
        $data['year_array'] = Helper::listAllYears();

        /* 
            Check if there any Previous payments Exists
        */
        $admission_id = isset($sid) ? $sid : $request->sid;
    


        /* Form Processing Starts */
        if ($request->isMethod('post')){
            $is_new = [];
            $payments = array_values(array_filter($request->payment));
            $payment_dates = array_values(array_filter($request->fees_paymet_date));
            
            $is_new = $request->is_new;
            //AdmissionFeesDetail

            //dd($paymentDetails);


            // Check if the Admission ID exists in the Admission_Fees table then Update
            $checkExistanceOfAdmIdCount = AdmissionFee::where('admission_id', $request->sid)
            ->where('admission_class', $request->s_class)
            ->where('academic_year', $request->academic_year)
            ->where('academic_month', $request->academic_month)
            ->count();

            
      

            if(isset($checkExistanceOfAdmIdCount) && $checkExistanceOfAdmIdCount > 0){
                /*
                    UPDATE PAYMNET
                */
                $checkExistanceOfAdmId = AdmissionFee::where('admission_id', $request->sid)
                ->where('admission_class', $request->s_class)
                ->where('academic_year', $request->academic_year)
                ->where('academic_month', $request->academic_month)
                ->first();

                $updatePaymnets['fees_master_id'] = json_encode($request->payment);
                DB::beginTransaction();
                if(AdmissionFee::where('id', '=', $checkExistanceOfAdmId['id'])->update($updatePaymnets)){

                /*
                    # new - 03 March 2018
                    # Update the Pyment Details Table Data
                */
                /* 
                    1. First I deleted the previous Entries , so that I can freshly insert new changes into the Table
                */
                if(AdmissionFeesDetail::where('admision_fees_id', $checkExistanceOfAdmId['id'])->delete())
                    $paymentDetails = array();
                    foreach ($payments as $key => $payment) {
                        $paymentDetails[$key]['is_new'] = '0';
                        if(array_search($payment, $is_new)){
                            $paymentDetails[$key]['is_new'] = '1';
                        }
                        $paymentDetails[$key]['admision_fees_id'] = $checkExistanceOfAdmId['id'];
                        $paymentDetails[$key]['admission_type_id'] = $payment;
                        $paymentDetails[$key]['payment_date'] = $payment_dates[$key];

                    }
                    //dd($paymentDetails);
                    if(AdmissionFeesDetail::insert($paymentDetails)){
                        DB::commit();
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Paymnet Details are Updated Successfully !');
                    }
                    else{
                        DB::rollBack();
                        session()->flash('status', ['danger', 'The system experienced some glitch! Last action was failed to perform. Please try again.']);
                    }
                }else{
                    DB::rollBack();
                    session()->flash('status', ['danger', 'The system experienced some glitch! Last action was failed to perform. Please try again.']);
                }
                //echo "<script>window.open('payment/printout/".$request->sid."', '_blank')</script>";
               //return redirect('/payment/'.$request->sid);
               return redirect('/payment/printout/'.$request->sid);
            
            }else{
                /*
                    SAVE PAYMNET
                */
                /* @@@ Calculations for Fines and ETC @@@ */


                /* @@ Ends @@*/

                DB::beginTransaction();
                //foreach ($request->academic_month as $key => $month) {
                    $savePaymnets = new AdmissionFee();
                    $savePaymnets['admission_class'] = $request->s_class;
                    $savePaymnets['admission_id'] = $request->sid;
                    $savePaymnets['academic_year'] = $request->academic_year;
                    $savePaymnets['academic_month'] = $request->academic_month;//json_encode(array_values($request->academic_month));
                    $savePaymnets['fees_master_id'] = json_encode(array_values($request->payment));
                    //dd($savePaymnets);
                //}
                if($savePaymnets->save()){
                    $paymentInsId = $savePaymnets->id;
                    /*
                        # (New) : 02 March 2018
                        # I want to save the Payment details and their payment date in a separte Table.
                        # Generate an Array For Submission of Payment Details in Payment Details table
                        # Loop through 2 array to get a perfect multi dimentional array for Laravel submission
                    */
                    $paymentDetails = array();
                    foreach ($payments as $key => $payment) {
                        //$is_new = array("sadsadsa")+["6", "9"];

                        $paymentDetails[$key]['is_new'] = '0';
                        if(in_array($payment, $is_new)){
                            $paymentDetails[$key]['is_new'] = '1';
                        }

                        $paymentDetails[$key]['admision_fees_id'] = $paymentInsId;
                        $paymentDetails[$key]['admission_type_id'] = $payment;
                        $paymentDetails[$key]['payment_date'] = $payment_dates[$key];

                    }
                    if(AdmissionFeesDetail::insert($paymentDetails)){
                        DB::commit();
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Student Paymnet Details are Saved Successfully !');
                    }
                }else{
                    DB::rollBack();
                    session()->flash('status', ['danger', 'Addition was Failed!']);
                }
                //return redirect('/payment/'.$request->sid);
                //return redirect('/admission-list');
                //echo "<script>window.open('payment/printout/".$request->sid."', '_blank')</script>";
                return redirect('/payment/printout/'.$request->sid);
            }
        }
        $customFields['master'] = array(
            'residental_type'=>array('type' => 'select', 'value' => array('' => '-- Select --','transport' => 'Transport', 'dayboarding' => 'Dayboarding', 'hostel' => 'Hostel', 'localised' => 'Localised'), 'label'=>'Resident Type', 'mandatory'=>true),
            'residental_type_amount'=>array('type' => 'text', 'label'=>'Amount','mandatory'=>true),
        );
        /* pass student admission id to the payment details page */
        $admId = isset($sid)?$sid:'';
        return view('admin.payment', ['otherLinks' => array('link' => url('/').'/get-payment-overview/'.$admId, 'text' => 'Payment List'), 'pageTitle' => isset($sid) && $sid != '' ? 'Student Payment Details':'Application Payment Form', 'data' => $data, 'customFields' => $customFields, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details', 'counter' => '1'] )->with($formAutoFill);
    }
    /**
     * A Printout will be generated for all students
     */
    public function getPaymentPrintout(Request $request, $pid = null, $pdf = null, $secret = null)
    {
        /**
         * Get Student Payment Info
         */
        
        $pdfData['paymentDetails'] = Admission::where('id', $pid)
                            ->with('admission_fees', 'admission_fees.admission_fees_details', 'admission_fees.admission_fees_details.fees_subcategory', 'admission_fees.admission_fees_details.feesmaster', 'admission_fees.admission_fees_details.feesmaster.subcategory')
                            ->first()
                            ->toArray();
        //dump($pdfData['paymentDetails']);
        /**
         * Get Fees Details for Invoice
         */
        //dd($pdfData['paymentDetails']['admission_fees']);
        //$admissionFeesList = $pdfData['paymentDetails']['admission_fees']['admission_fees_details'];
        $admissionFees = $pdfData['paymentDetails']['admission_fees'];
        $feesIdCombi = '';
        $feesDetailsFullArray = [];
        foreach ($admissionFees as $key => $value) {
            $feesDetailsArray = [];
            foreach ($value['admission_fees_details'] as $keyfl => $valuefl) {
                //dd($valuefl);
                if(isset($valuefl['is_new']) && $valuefl['is_new'] == '1'){
                    $feesIdCombi .= $valuefl['id'];
                    $feesDetailsArray[$keyfl]['admission_fees_details_id'] = $valuefl['id'];
                    $feesDetailsArray[$keyfl]['academic_year'] = $value['academic_year'];
                    $feesDetailsArray[$keyfl]['academic_month'] = $value['academic_month'];
                    $feesDetailsArray[$keyfl]['payment_date'] = $valuefl['payment_date'];
                    $feesDetailsArray[$keyfl]['payment_for'] = $valuefl['feesmaster']['subcategory']['subcategory_name'];
                    $feesDetailsArray[$keyfl]['fees_amount'] = $valuefl['feesmaster']['amount'];
                    $feesDetailsArray[$keyfl]['is_mandatory'] = $valuefl['feesmaster']['is_mandatory'];
                    $feesDetailsArray[$keyfl]['is_secret'] = $valuefl['feesmaster']['is_secret'];
                }
            }
            if(isset($feesDetailsArray) && count($feesDetailsArray) > 0){
                $feesDetailsFullArray[] = $feesDetailsArray;
            }
        }
        //dd($feesDetailsFullArray);
        $rrr = [];
        foreach ($feesDetailsFullArray as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $rrr[] = $value1;
            }
            
        }

       AdmissionFeesDetail::whereNotNull('id')->update(['is_new' => '0']);

        // foreach ($admissionFeesList as $key => $value) {
        //     $feesIdCombi .= $value['id'];
        //     $feesDetailsArray[$key]['academic_year'] = $pdfData['paymentDetails']['admission_fees']['academic_year'];
        //     $feesDetailsArray[$key]['academic_month'] = $pdfData['paymentDetails']['admission_fees']['academic_month'];
        //     $feesDetailsArray[$key]['payment_date'] = $value['payment_date'];
        //     $feesDetailsArray[$key]['payment_for'] = $value['feesmaster']['subcategory']['subcategory_name'];
        //     $feesDetailsArray[$key]['fees_amount'] = $value['feesmaster']['amount'];
        //     $feesDetailsArray[$key]['is_mandatory'] = $value['feesmaster']['is_mandatory'];
        // }
        $pdfData['generateInvoiceNumber'] = $pdfData['paymentDetails']['id'].$feesIdCombi;
        $pdfData['feesPaidList'] = $rrr;
        $pdfData['studentID'] = isset($pid) ? $pid : '';
        $pdfData['isSecret'] = (isset($secret) && $secret == 'secret') ? '1' : '0' ;
        $pdfData['pageTitle'] = 'Payment Printout';
        $pdfData['route'] = Route::currentRouteName();
        $pdfData['pageType'] = 'HTML';
        if(isset($pdf) && $pdf == 'pdf'){
            $pdfData['pageType'] = 'PDF';
            PDF::setOptions(['dpi' => 100000, 'defaultFont' => 'sans-serif']);
            $pdf = PDF::loadView('pdf.student-payment-print', $pdfData);
            return $pdf->download($pdfData['generateInvoiceNumber'].".pdf");
        }else{
            return view('pdf.student-payment-print', $pdfData);
        }
        //$pdf = PDF::loadView('pdf.student-payment-print', $pdfData);
        //return $pdf->download('invoice.pdf');
    }
    public function transferPrintout(Request $request, $pid = null, $pdf = null)
    {
        /**
         * Get Student Payment Info
         */
        $pdfData['studentDetails'] = Admission::where('id', $pid)
                            //->with('admission_fees', 'admission_fees.admission_fees_details', 'admission_fees.admission_fees_details.fees_subcategory', 'admission_fees.admission_fees_details.feesmaster')
                            ->first()
                            ->toArray();

        /**
         * Get Fees Details for Invoice
         */
       
        
        // $pdfData['generateInvoiceNumber'] = $pdfData['paymentDetails']['id'].$pdfData['paymentDetails']['admission_fees']['id'].$feesIdCombi;
        // $pdfData['feesPaidList'] = $feesDetailsArray;

        
        $pdfData['pageTitle'] = 'Payment Printout';
        $pdfData['pageType'] = 'HTML';
        if(isset($pdf) && $pdf == 'pdf'){
            $pdfData['pageType'] = 'PDF';
            $pdf = PDF::loadView('pdf.student-transfer', $pdfData);
            return $pdf->download("tranfer-invoice.pdf");
        }else{
            return view('pdf.student-transfer', $pdfData);
        }
        //$pdf = PDF::loadView('pdf.student-payment-print', $pdfData);
        //return $pdf->download('invoice.pdf');
    }
    /*
        Student's Payment Overview of various Years
    */
    public function paymentOverview($sid=null, $year=null)
    {
            $data['submitBtnName'] = 'Show Payment Overview';

            $data['year_array']   = Helper::listAllYears();
            $getDefYear = isset($year) ? $year : reset($data['year_array']);
            $getListOfMonth = Helper::listAllMonths();
            $data['studentDetails'] = Admission::where('id', $sid)->select('admission_class', 'father_name', 'mother_name', 'name')->first()->toArray();
            $getStudentClass = $data['studentDetails']['admission_class'];

            //dd($getListOfMonth);
            $getStudentDetails = Helper::getStudentPaymnetStatus($sid, $getStudentClass, $getDefYear);
            $data['allMonthOverview'] = json_decode($getStudentDetails, true);
               // dd($getStudentDetails);
            $customFields['search'] = array(            
                'name'=>array('type' => 'text', 'label'=>'Student Name', 'col_num' => '3', 'mandatory'=>true),
                's_father_name'=>array('type' => 'text', 'label'=>'Father', 'col_num' => '3', 'mandatory'=>true),
                'admission_class'=>array('type' => 'select', 'value' => array(), 'label'=>'Class', 'col_num' => '2', 'mandatory'=>true),
                'from'=>array('type' => 'text', 'label'=>'From Date', 'col_num' => '2', 'id' => 'datepicker', 'mandatory'=>true),
                'to'=>array('type' => 'text', 'label'=>'To Date', 'col_num' => '2', 'id' => 'datepicker2', 'mandatory'=>true),
            );
            
           return view('admin.student-payment-overview', ['otherLinks' => array('link' => url('/').'/payment/'.$sid, 'text' => 'Go Back'), 'pageTitle' => isset($sid) && $sid != '' ? "Student's Payment Overview":'Student\'s Payment Overview', 'data' => $data, 'customFields' => $customFields, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details', 'counter' => '1', 'sid' => $sid, 'year' => isset($year) ? $year : null] );
       

    }
    public function ajaxGetPaidDetails(Request $request)
    {
        $academic_year = $request->academic_year;
        $academic_month = $request->academic_month;
        $sid = $request->sid;
        $s_class = $request->s_class;

        //echo $academic_month." ".$academic_year;
        $getDetails = AdmissionFee::where('academic_year', $academic_year)
                      ->where('academic_month', $academic_month)
                      ->where('admission_id', $sid)
                      ->where('admission_class', $s_class)
                      ->first();
        /* fetch Data from the Payment Details table */
        if( AdmissionFeesDetail::where('admision_fees_id', $getDetails['id'])->count() > 0 ){
            $getFeesDetails = AdmissionFeesDetail::where('admision_fees_id', $getDetails->id)->get()->toArray();
        }

        $paymentIdwithDates = array();
        /*
            generate a Array of payment_id and date. and send to php page as a json.
        */
        if(isset($getFeesDetails) && count($getFeesDetails) > 0){
            foreach ($getFeesDetails as $key => $value) {
                $paymentIdwithDates[$key][$value['admission_type_id']] = $value['payment_date'];
            }
        }
        //dd($paymentIdwithDates);

        if(isset($paymentIdwithDates) && count($paymentIdwithDates) > 0 ){
            $getPaymentDetails = $getDetails->toArray();
            //print_r($getDetails->toArray()); exit;
            $return['json_payment'] = $paymentIdwithDates;
            echo json_encode($return);
        }else{
            $return['json_payment'] = array();
            echo json_encode($return);
        }
    }

    /**
     * Module : Readmission Module Starts
     * Date : 17 mar 2018
     * By : Tanmaya Patra
     */
    public function readmision(Request $request, $sid = null)
    {

        $data['counter'] = '0';
        $getPaymentHistory = array();
        $data['months'] = array('' => '-- Select --');
        $data['months'] += Helper::listAllMonths();
        $data['studentDetails'] = Helper::getStudentDetails($sid, ['id', 'name','father_name', 'mother_name', 'admission_class']);
        $studentClass = isset($data['studentDetails']->admission_class) ? $data['studentDetails']->admission_class : '';
        /* Get all Payment Structures of the Coressp. Class */
        $data['getPayments'] = FeesMaster::where('class', $studentClass)->get()->toArray();
    
        /* generate Year Range for Academic Year */
        $data['year_array'] = Helper::listAllYears();
        $data['otherLinks'] = array('link' => url('/').'/get-payment-overview/', 'text' => 'Payment List');
        $data['pageTitle'] = 'Student Re-admission and Transfer Procesing';
        $data['customFields'] = $customFields = array();
        $data['formButton'] = isset($sid) ? 'Update Details' : 'Save Details';
        $data['submitBtnName'] = 'Submit';


        



        $getAdmissionDetails = array();  
        if(isset($sid) && $sid != ''){
            if(Admission::where('id', $sid)->count() > 0){
                $getAdmissionDetails = Admission::where('id', $sid)->with('admission_fees', 'admission_fees.admission_fees_details')->first()->toArray();
                //dd($getAdmissionDetails);
                $data['admission_date'] = date("d M Y", strtotime($getAdmissionDetails['admission_date']));
                $admissionClass = $getAdmissionDetails['admission_class'];
                $admissionMonth = Helper::getMonthCode(date("F", strtotime($getAdmissionDetails['admission_date'])));
                $admissionYear = date("Y", strtotime($getAdmissionDetails['admission_date']));
                $academic_year = Helper::getAcademicYear($admissionYear);


                $currShortAcademinYear = date("y", strtotime($getAdmissionDetails['admission_date']));
                $data['nextAcademicYear'] = [
                    ($admissionYear+1).'-'.($currShortAcademinYear+2) => ($admissionYear+1).'-'.($currShortAcademinYear+2)
                ];

                /**
                 * Get the Mandatory Fees List of the Speciific Class 
                 */
                $feesDetails = FeesMaster::where('class', $admissionClass)
                                ->where('is_mandatory', '1')
                                //->with('category', 'subcategory')
                                ->get()->toArray();
                //dd($feesDetails);
                $mandatorySubcategoryFeesIds = array_pluck($feesDetails, 'id');
                //Ends

                /**
                 * Getting the Valid Academic Month Lists : 
                 * --Using the Helper, we get the Academic mnths list.
                 * --Then frm the list we filtered out the Particular Student's VALID Academic Year on the basis of his/her
                 * --Admission Date.
                 */
                $academicMonths = Helper::listAllMonths();
                $studentVoidMonths = array();
                foreach($academicMonths as $keyCode => $months){
                    if($admissionMonth == $keyCode){
                        break;
                    }else{
                        $studentVoidMonths[$keyCode] = $months;
                    }
                }
                // Subtracting the Above Void Months from the total Academic Months
                $studentAcademicMonths = array_diff($academicMonths, $studentVoidMonths);
                //End

        
                $dueAcademicPayment = $studentPaidFeesIds = array(); 
                $loop = $feesClearStatus = 0;
                /**
                 * Looping the Student's Valid Academic Months List and getting Information about each month from our DB
                 */
                foreach ($studentAcademicMonths as $academicMonthCode => $value) {
                    
                    //dump($academicMonthCode);
                    //Get Paid Subcategory Fees List
                    /**
                     * 
                     */
                    if(AdmissionFee::where('admission_id', $sid)->where('admission_class', $admissionClass)->where('academic_year', $academic_year)->where('academic_month', $academicMonthCode)->count() > 0){
                        
                        $studentPaidFeesIds = $rop[$loop] = AdmissionFee::where('admission_id', $sid)
                                                ->where('admission_class', $admissionClass)
                                                ->where('academic_year', $academic_year)
                                                ->where('academic_month', $academicMonthCode)
                                                ->with('admission_fees_details')
                                                ->first()
                                                ->toArray();
                    }else{
                        $studentPaidFeesIds = array();
                    }

                    $paidfeesJson = isset($studentPaidFeesIds['fees_master_id'])? $studentPaidFeesIds['fees_master_id'] : '';
                    $paidfeesData = json_decode($paidfeesJson, true);

                    /**
                     * Check if there is NO ENTRY for this Month in DB then add a Sorry message
                     */
                    if(isset($studentPaidFeesIds) && is_array($studentPaidFeesIds) && count($studentPaidFeesIds) == 0 ){
                        $feesClearStatus++;
                        $dueAcademicPayment[$loop]['month'] = $academicMonthCode;
                        $dueAcademicPayment[$loop]['year'] = $academic_year;
                        $dueAcademicPayment[$loop]['status'] = 'danger';
                        $dueAcademicPayment[$loop]['message'] = 'Sorry ! No Payment recorded During this Month';
                    }
                    if(isset($paidfeesData) && count($paidfeesData) > 0){
                        $paidInfoDifference = array_diff($mandatorySubcategoryFeesIds, $paidfeesData);
                        if(isset($paidInfoDifference) && count($paidInfoDifference) == 0){ //Means, All fees are paid.
                            $dueAcademicPayment[$loop]['month'] = $academicMonthCode;
                            $dueAcademicPayment[$loop]['year'] = $academic_year;
                            $dueAcademicPayment[$loop]['status'] = 'success';
                            $dueAcademicPayment[$loop]['message'] = 'Congrats ! All Fees are paid in this Month';
                        }else if(isset($paidInfoDifference) && count($paidInfoDifference) > 0){
                            $feesClearStatus++;
                            $dueAcademicPayment[$loop]['month'] = $academicMonthCode;
                            $dueAcademicPayment[$loop]['year'] = $academic_year;
                            $dueAcademicPayment[$loop]['status'] = 'warning';
                            $dueAcademicPayment[$loop]['message'] = 'Warning! Some of the payments are still due. Please Make those paid.';
                        }
                    }
                    //dump($studentPaidFeesIds);
                    $loop++;
                }
                $data['feesClearStatus'] = $feesClearStatus;
                $data['feesStatus'] = $dueAcademicPayment;
                $data['promotedToClass'] = ($admissionClass + 1);
                $data['newAcademicYear'] = ($admissionYear + 1);
                
                
            }
        }
        
        return view('admin.student-readmission-and-transfer', $data);
    }
    public function readmisionSave(Request $request)
    {
        //dump($request->all());
        $studentId = $request->sid;
        $getStudentDetails = array();
        if(Admission::find($studentId)->count() > 0){
            $getStudentDetails = Admission::find($studentId)->toArray();
        }
        $newStudentDetails = array_set($getStudentDetails, 'admission_class', $request->ptc);
        $newStudentDetails = array_set($getStudentDetails, 'academic_year', $request->acyr);
        //array_splice($newStudentDetails, 'created_at', 'updated_at');
        unset($newStudentDetails['created_at']); unset($newStudentDetails['updated_at']); unset($newStudentDetails['id']);

        //dd($newStudentDetails);
        if(isset($request->admission_type) && $request->admission_type  == 'READMISSION'){
            $insertStudent = new Admission($newStudentDetails);
            if($insertStudent->save()){
                if(Admission::where('id', $studentId)->update(['status' => 'COMPLETED', 'readmission_id' => $insertStudent->id ])){
                    $request->session()->flash('message.level', 'info');
                    $request->session()->flash('message.content', 'Student is Successfully Promoted to the class '.$request->ptc);
                }
            }else{
                session()->flash('status', ['danger', 'Last action was Failed!']);
            }
        }else{
            if(Admission::where('id', $studentId)->update(['status' => $request->admission_type])){
                $request->session()->flash('message.level', 'info');
                $request->session()->flash('message.content', 'Student is Successfully Transfered. !');
            }
        }
        return redirect('/admission-list');
    }
}
