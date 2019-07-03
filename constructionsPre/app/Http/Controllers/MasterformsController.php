<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\FeesCategory;
use App\FeesSubcategory;
use App\FeesMaster;
use DB;
use Helper;
use Auth;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Role;
use Analytics;
use App\Event;
use App\Admission;
use App\Supplier;
use App\Library;
use App\Voucher;
use App\UserDetail;
use App\StockHistory;
use App\Site;
use Image;
use Crypt;
//use App\User;


class MasterformsController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    /*
		info : Employee/User Master
		Date ; 14-11-2017
    */
	public function addUser(Request $request, $eid=null, $view = null)
	{
		/* Generating Year List */
		$data['yearOfJoing'] = array('' => '-- Select --');
		for($i=(int)(date('Y')-10);$i<=date('Y');$i++){ $data['yearOfJoing'] += array($i => $i);}
		/* Get List of Roles */
		$getRoles = Role::where('is_active', '1')->pluck('name', 'id')->toArray();
		/* Field specifiic Validations */	
		if(isset($request->id) && $request->id != null){
	        $validationRules = [
	            'name' => 'required|max:255',
	            'email' => 'required|email|max:150',
				'confirm_password' => 'same:password',
				'role_id'	=> 'required',
				'basic'	=> 'required|numeric',
				'esic'	=> 'required',
				'epf'	=> 'required',
				'pt'	=> 'required',
				'gross_salary'	=> 'required',
				'net_salary'	=> 'required',
				'role_id'	=> 'required',
				'role_id'	=> 'required',
				'role_id'	=> 'required',
				'role_id'	=> 'required',
	        ];
    	}else{
			$validationRules = [
	            'name' => 'required|max:255',
	            'email' => 'required|email|max:150|unique:users',
	            //'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
	            'password' => 'required',
				'confirm_password' => 'required|same:password',
				'role_id'	=> 'required'
	        ];
    	}
    	$validator = Validator::make($request->all(), $validationRules);
        if(isset($eid) && $eid != null){
			$employeeDetails = User::where('id', $eid)->with('user_detail')->first()->toArray();
			unset($employeeDetails['user_detail']['id']);
			if(isset($employeeDetails['user_detail']) && count($employeeDetails['user_detail']) > 0){
				$getEmployeeDetails = array_merge($employeeDetails, $employeeDetails['user_detail']);
			}else{
				$getEmployeeDetails = $employeeDetails;
			}
    	}else{
    		$getEmployeeDetails = array();
		}
		/* Submit all data */
    	if ($request->isMethod('post')){
			/**
			 * Separates Data for Both Tables
			 */
			$userData = $request->only(['name', 'email', 'password', 'confirm_password', 'role_id', 'gender', 'dob', 'blood_group', 'basic', 'esic', 'epf', 'pt', 'gross_salary', 'net_salary', 'per_day_salary']);
			$userOtherData = $request->only(['father_husband_name', 'residental_address', 'residental_at', 'residental_pin', 'residental_phoneno', 'permanent_address', 'permanent_at', 'permanent_pin', 'permanent_phoneno', 'official_address', 'official_at', 'official_pin', 'official_phoneno', 'emergency_contact', 'student_one', 'student_two', 'bank_account_name', 'bank_account_no', 'bank_ifsc_code', 'date_of_superannuation', 'specimen_of_initials', 'specimen_of_full_signature', 'date_of_filling_record', ]);

			
    		if($validator->fails()){
            	return back()->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != null){
					/**
					 * Updation of Employees
					 */
		    		$updateStudent = User::find($request->id);
		    		if(!isset($request->password) && $request->password == ''){
		    			unset($userData['password']);
		    			unset($userData['confirm_password']);
		    			unset($userData['_token']);
		    		}else{
						unset($userData['confirm_password']);
		    			unset($userData['_token']);
					}
					
		    		if(User::where([['id', '=', $request->id], ['role_id', '!=', '1']])->update($userData)){
						
						if(UserDetail::where('users_id', $request->id)->count() == '0'){
							$userDetailVoidEntry = new UserDetail(['users_id' => $request->id]);
							$userDetailVoidEntry->save();
						}
						
						if(UserDetail::where('users_id', $request->id)->update($userOtherData)){
		    				$request->session()->flash('message.level', 'info');
							$request->session()->flash('message.content', 'Employee Details are Updated Successfully !');
						}else{
							session()->flash('status', ['danger', 'Updation was Failed!']);	
						}
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/admin/employee-edit/'.$request->id);
		    	}else{
					/**
					 * Save New Employee Records
					 */
		    		$userData['password'] = Hash::make($userData['password']);
					/**
					 * Specimen's File uploading
					 */
					if( $request->hasFile('specimen_of_initials')) {
						$file = Input::file('specimen_of_initials');
						// Validate each file
						$rules = array('file' => 'required|mimes:png,gif,jpeg');
						$validator = Validator::make(array('file'=> $file), $rules);
						if($validator->passes()) {
							$userOtherData['specimen_of_initials'] = $filename = 'Emp_specimen_initial_'.time().'.'.$file->getClientOriginalName();
							$upload_success = $file->move(public_path('uploads/employee'), $filename);
						} else {
							// redirect back with errors.
							return redirect('/admin/employee')->withErrors($validator)->withInput();
						}
					}
					if( $request->hasFile('specimen_of_full_signature')) {
						$file = Input::file('specimen_of_full_signature');
						// Validate each file
						$rules = array('file' => 'required|mimes:png,gif,jpeg');
						$validator = Validator::make(array('file'=> $file), $rules);
						if($validator->passes()) {
							$userOtherData['specimen_of_full_signature'] = $filename = 'Emp_specimen_sign_'.time().'.'.$file->getClientOriginalName();
							$upload_success = $file->move(public_path('uploads/employee'), $filename);
						} else {
							// redirect back with errors.
							return redirect('/admin/employee')->withErrors($validator)->withInput();
						}
					}
					//End of FIle Uploading
		    		$empSave = new User($userData);
		    		//dd($userData);
		    	
		    		if($empSave->save()){
						/**
						 * Save User other Informations in the User_Details Table
						 */
						$userOtherData['users_id'] = $empSave->id;
						$userDetailsSave = new UserDetail($userOtherData);
						if($userDetailsSave->save()){
							$request->session()->flash('message.level', 'success');
							$request->session()->flash('message.content', 'Employee was successfully added!');
						}
		        	}else{
		        		session()->flash('status', ['danger', 'Addition of User Failed!']);
		        	}
		        	return redirect('/admin/employee');
		        }
            }
			
    	}
		$customFields['basic'] = array(
			'name'=>array('type' => 'text', 'label'=>'Employee Name', 'class' => 'alpha capsLock', 'mandatory'=>true),
			'father_husband_name'=>array('type' => 'text', 'label'=>'Father/Husband Name','class' => 'alpha capsLock','mandatory'=>true), //*New
			'role_id'=>array('type' => 'select', 'label'=>'Employee Designation', 'value' => $getRoles,'mandatory'=>true),
			'gender'=>array('type' => 'select', 'label' => 'Gender', 'value'=> array('male'=>'Male','female'=> 'Female'), 'mandatory'=>true),
			'dob'=>array('type' => 'text', 'label'=>'Date of Birth', 'id' => 'datepicker', 'mandatory'=>true),
			'blood_group'=>array('type' => 'select', 'label' => 'Blood Group', 'value' => array('A+' => 'A+', 'AB+' => 'AB+','B+' => 'B+','O+' => 'O+','A-' => 'A-','B-' => 'B-'), 'mandatory'=>true),

			'separator_1' => array('type' => 'separator', 'label' => 'Residental Address'),
			'residental_address'=>array('type' => 'text', 'label'=>'Residental Address','mandatory'=>true), //*New
			'residental_at'=>array('type' => 'text', 'label'=>'Residental At','mandatory'=>true), //*New
			'residental_pin'=>array('type' => 'text', 'label'=>'Residental pin No','mandatory'=>true), //*New
			'residental_phoneno'=>array('type' => 'text', 'label'=>'Residental Phone No.','mandatory'=>true), //*New

			'separator_2' => array('type' => 'separator', 'label' => 'Permanent Address'),
			'permanent_address'=>array('type' => 'text', 'label'=>'Permanent Address','mandatory'=>true), //*New
			'permanent_at'=>array('type' => 'text', 'label'=>'Permanent At','mandatory'=>true), //*New
			'permanent_pin'=>array('type' => 'text', 'label'=>'Permanent pin no','mandatory'=>true), //*New
			'permanent_phoneno'=>array('type' => 'text', 'label'=>'Permanent Phone no','mandatory'=>true), //

			'separator_3' => array('type' => 'separator', 'label' => 'Official Address'),
			'official_address'=>array('type' => 'text', 'label'=>'Official Address','mandatory'=>true), //*New
			'official_at'=>array('type' => 'text', 'label'=>'Official At','mandatory'=>true), //*New
			'official_pin'=>array('type' => 'text', 'label'=>'Official pin no','mandatory'=>true), //*New
			'official_phoneno'=>array('type' => 'text', 'label'=>'Official phone no','mandatory'=>true), //*New

			'separator_4' => array('type' => 'separator', 'label' => 'Emergency Contact'),
			'emergency_contact'=>array('type' => 'text', 'label'=>'Emergency contact no','mandatory'=>true), //*New
			

			'separator_6' => array('type' => 'separator', 'label' => 'Bank Account Details'),
			'bank_account_name'=>array('type' => 'text', 'label'=>'Name as in Bank Account','class' => 'alpha capsLock','mandatory'=>true),//*New
			'bank_account_no'=>array('type' => 'text', 'label'=>'Bank Account Number','mandatory'=>true),//*New
			'bank_ifsc_code'=>array('type' => 'text', 'label'=>'Bank IFSC code','mandatory'=>true),//*New

			'separator_7' => array('type' => 'separator', 'label' => 'Salary Segregations'),
			'basic'=>array('type' => 'text', 'label'=>'Basic Salary','class' => 'mobile','mandatory'=>true),//*New
			'esic'=>array('type' => 'text', 'label'=>'ESIC','class' => 'mobile','mandatory'=>true),//*New
			'epf'=>array('type' => 'text', 'label'=>'EPF','class' => 'mobile','mandatory'=>true),//*New
			'pt'=>array('type' => 'text', 'label'=>'PT','class' => 'mobile','mandatory'=>true),//*New
			'gross_salary'=>array('type' => 'text', 'label'=>'Gross Salary','class' => 'mobile gross_salary','mandatory'=>true),//*New
			'net_salary'=>array('type' => 'text', 'label'=>'Net Salary','class' => 'mobile net_salary','mandatory'=>true),//*New
			'per_day_salary'=>array('type' => 'text', 'label'=>'Per-day Salary','class' => 'mobile per_day_salary','mandatory'=>false, 'readonly' => true),//*New
		

			'separator_8' => array('type' => 'separator', 'label' => 'Login Details'),
			'email'=>array('type' => 'text', 'label'=>'Email ID','mandatory'=>true),
			'password'=>array('type' => 'password', 'label'=>'Password','mandatory'=>true),
			'confirm_password'=>array('type' => 'password', 'label'=>'Confirm Password','mandatory'=>true),
		);
		if(isset($view) && $view == 'view'){ $data['field_disable'] = true; }
		return view('admin.user', ['otherLinks' => array('url' => url('/').'/admin/employee-list', 'text' => 'Employee List'), 'customFields' => $customFields, 'data' => $data, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details', 'pageTitle' => isset($sid) && $sid != '' ? 'Edit Employee':'Add Employee'])->with($getEmployeeDetails);
	}

	public function userList(Request $request)
	{
		/*$analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
		dd($analyticsData);*/
		/* Fetch User List along with corsp. Role Details ;) */
		if($request->isMethod('post'))
		{
			$users1 = User::where('role_id', '!=', '1');
			if($request->name)
			{
				$users1->where('name','like','%'.$request->name.'%');
			}
			 if($request->email)
			{
				$users1->where('email','like','%'.$request->email.'%');
			}
			
			$users=$users1->with('role')->paginate(15);
		}
		else
		{
			$users = User::where('role_id', '!=', '1')->with('role')->paginate(15);
		}
		
		$customFields['basic'] = array(
			'name'=>array('type' => 'text', 'label'=>'Employee Name','mandatory'=>true),
			'email'=>array('type' => 'text', 'label'=>'Email ID','mandatory'=>true),
    	);
		return view('admin.user-list', ['otherLinks' => array('link' => url('/').'/employee', 'text' => 'Add Employee'), 'pageTitle' => 'Employee List', 'users' => $users, 'customFields' => $customFields, 'loopInit' => '1']);
	}

	public function blockUser(Request $request, $bool = null, $uid = null)
	{
		$status = $bool == '1' ? 'Unblocked' : 'Blocked';
		if(isset($uid) && $uid !=''){
			if(User::where('id', Crypt::decrypt($uid))->update(['is_active' => $bool])){
				$request->session()->flash('message.level', 'success');
				$request->session()->flash('message.content', 'User was successfully '.$status.' !');
				return redirect()->back();
			}
		}
	}
	public function trashUser(Request $request, $bool = null, $uid = null)
	{
		$status = $bool == '0' ? 'removed from Trash' : 'Trashed';
		if(isset($uid) && $uid !=''){
			if(User::where('id', $uid)->delete()){
				$request->session()->flash('message.level', 'success');
				$request->session()->flash('message.content', 'User was successfully '.$status.' !');
				return redirect('/admin/employee-list');
			}
		}
	}

	
	


	/* ##########################  Voucher MANAGEMENT ############################*/
	/*
		info : Voucher Master
		Date ; 14-12-2017 
    */
	public function voucherManagement(Request $request, $eid=null)
	{
		$data[] = array();
		/* Generate List of Available CLasses */
		$classList = array('' => '-- Select --', 'Nursery' => 'Nursery', 'LKG' => 'LKG', 'UKG' => 'UKG');
		for($i = 1; $i < 13; $i ++){
			$classList[$i] = 'STD - '.$this->integerToRoman($i);
		}
		$getSuppliers = array('' => '-- Slect --');
		$getSuppliers += Supplier::pluck('name', 'id')->toArray();
		/* Field specifiic Validations */	



        $validationRules = [
            'voucher_no' => 'required',
            'employee_id' => 'required',
            'pay_to' => 'required|max:200',
            'voucher_date' => 'required',
            'voucher_details' => 'required|max:200',
            'payment_mode' => 'required'
        ];
    	
    	$validator = Validator::make($request->all(), $validationRules);
       	if(isset($eid) && $eid != null){
    		$getFormAutoFillup = Voucher::whereId($eid)->first()->toArray();
    		$admission_class = $getFormAutoFillup['admission_class'];
    	}else{
    		$getFormAutoFillup = array();
    	}
		/* Submit all data */
    	if ($request->isMethod('post')){
    		if($validator->fails()){
            	return redirect('/admin/voucher-issue')->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != null){
		    		/* 
						Update
		    		*/
		    		$updateStudent = Voucher::find($request->id);
		    		if(isset($request->_token) && $request->_token != ''){
		    			unset($request['_token']);
		    		}
		    		/* Calculation of total Amount on the GST and Discount */
		    		$totalPrice = $request->stock * $request->price;
		    		$discountInPrice = isset($request->discount) ? $totalPrice*($request->discount/100) : 0;
		    		$total = ($totalPrice + ($totalPrice*($request->gst/100)))-$discountInPrice;
		    		$request->request->add(['total' => $total]);

		    		//dd($request->toArray());
		    		if(Voucher::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/admin/voucher-edit/'.$request->id);
		    	}else{
		    		$saveData = $request->toArray();
		    		/* Calculation of total Amount on the GST and Discount */
		    		

		    		$saveData = new Voucher($saveData);

		    		if($saveData->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Voucher was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Updation of Voucher Failed!']);
		        	}
		        	return redirect('/admin/voucher-issue');
		        }
            }
			
    	}
    	$employeeList = array('' => '-- Select --', '0' => '.:: Other Person ::.');
    	$employeeList += User::pluck('name', 'id')->toArray();
    	$voucherMaxId = DB::table('vouchers')->max('id');
    	$voucherId = 'SCVCH'.($voucherMaxId+1);

		$customFields['basic'] = array(
			'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number', 'mandatory'=>true, 'class' => 'voucher_no'),
			'amount'=>array('type' => 'text', 'label'=>'Amount', 'mandatory'=>true, 'class' => 'amount'),
			'flow_type'=>array('type' => 'select', 'label'=>'Choose Money Flow', 'value' => array('OUTFLOW' => 'Expense', 'INFLOW' => 'Income', 'OPNBAL' => '*Add Opening Balance'), 'mandatory'=>true, 'class' => 'flow_type'),
			'employee_id'=>array('type' => 'select', 'label'=>'Choose Person', 'value' => $employeeList, 'mandatory'=>true, 'class' => 'employee_id'),
			'pay_to'=>array('type' => 'text', 'label'=>'Paid To/By', 'mandatory'=>true, 'class' => 'pay_to'),
			'voucher_date'=>array('type' => 'text', 'label'=>'Voucher Date', 'mandatory'=>true, 'id' => 'datepicker'),
			'voucher_details'=>array('type' => 'text', 'label'=>'Voucher Details', 'mandatory'=>true, 'class' => 'voucher_details'),
			'payment_mode'=>array('type' => 'select', 'label'=>'Payment Mode', 'value' => ['CASH' => 'By Cash', 'CHEQUE' => 'By Cheque', 'iBanking' => 'By Internet Banking'], 'mandatory'=>true, 'class' => 'payment_mode'),
			'personal_expense' => array('type' => 'checkbox', 'label'=>'Personal Expenses', 'mandatory'=>false, 'class' => 'personal_expense'),
    	);
    	
		
		return view('master.voucher', ['otherLinks' => array('url' => url('/').'/admin/voucher-list', 'text' => 'Voucher List'), 'customFields' => $customFields, 'data' => $data, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details', 'pageTitle' => isset($sid) && $sid != '' ? 'Edit Voucher':'Add Voucher Details and Personal Expenses', 'voucher_no' => isset($voucherId) ? $voucherId : 'SCVCH1'])->with($getFormAutoFillup);
	}
	public function voucherListing()
	{
		/* Generate List of Available CLasses */
		$employeeList = array('' => '-- Select --', '0' => '.:: Other Person ::.');
    	$employeeList += User::pluck('name', 'id')->toArray();

		$voucher = Voucher::paginate(15);
		$customFields['basic'] = array(
			'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number','mandatory'=>true),
			'employee_id'=>array('type' => 'select', 'label'=>'Choose Class', 'value' => $employeeList, 'mandatory'=>true, 'class' => 'admission_class'),
    	);
		return view('master.voucher-list', ['otherLinks' => array('url' => url('/').'/admin/voucher-issue', 'text' => 'Add Voucher'), 'pageTitle' => 'Voucher List', 'datas' => $voucher, 'customFields' => $customFields, 'loopInit' => '1']);
	}
	public function trashVoucher(Request $request, $evid = null)
	{
		if(Event::where('id', $evid)->delete()){
			$request->session()->flash('message.level', 'success');
			$request->session()->flash('message.content', 'Event was successfully Deleted!');
    	}else{
    		session()->flash('status', ['danger', 'Deletion of Event Failed!']);
    	}
    	return redirect(url('/').'/event');
	}
/* ##########################  Site Master ############################*/
	/*
		info : Site Master
		Date ; 09-07-2018
    */
	public function addsite(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add Site';
         $validationRules = [
             'location' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        
        $viewData['siteList'] = Site::get();
        
        if(isset($eid) && $eid != null){
           // $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = Site::whereId($eid)->first()->toArray();
        }else{
            //$validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = array();
        }
        //dd($validationRules);
        $validator = Validator::make($request->all(), $validationRules);
        /* Submit all data */
        if ($request->isMethod('post')){
            if($validator->fails()){
            dd($request);exit();

                return back()->withErrors($validator)->withInput();
            }else{
                if(isset($request->id) && $request->id != null){
                    /* 
                        Update
                    */
                   // $updateStudent = Supplier::find($request->id);
                    if(isset($request->_token) && $request->_token != ''){
                        unset($request['_token']);
                    }
                    

                    //dd($request->toArray());
                    if(Site::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/site-master/'.$request->id);
                }else{
                    $saveData = $request->toArray();

                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new Site($saveData);

                    if($saveData->save()){
                    	/*
                            Multiple file Upload Starts 
                        */
                        $prodctGallery = array(); 
                        $pG = 0; $galleryUploaded = ' And No Gallery Files were newly added.';

                        if( $request->hasFile('property_image_raw')) {
                            $files = Input::file('property_image_raw');
                            foreach ($files as $key => $file) {
                                // Validate each file
                                $rules = array('file' => 'required|mimes:png,gif,jpeg');
                                $validator = Validator::make(array('file'=> $file), $rules);

                                if($validator->passes()) {
                                    /**
                                     * Save image in other format
                                     */
                                    $prodctGallery[$key]['property_id'] = $propertyId;
                                    $filename = $prodctGallery[$key]['property_image'] = $propertyId.'_'.date('Y-m-d-h-i-s').'.'.$file->getClientOriginalExtension(); 

                                    // Resolution : 100 x 100 
                                    $destinationPath = public_path('/uploads/sites/thumbnails');
                                    $thumb_img = Image::make($file->getRealPath())->resize(100, 100);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);

                                    // Resolution : 300 x 300 
                                    $destinationPath = public_path('/uploads/sites/square');
                                    $thumb_img = Image::make($file->getRealPath())->resize(300, 300);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);

                                    //Resolution : 1600 x 1500
                                    $destinationPath = public_path('/uploads/sites/slider');
                                    $thumb_img = Image::make($file->getRealPath())->resize(1600, 1500);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);
                                                
                                    $destinationPath = public_path('/uploads/sites');
                                    $file->move($destinationPath, $filename);

                                    // Flash a message and return the user back to a page...
                                } else {
                                    // redirect back with errors.
                                    return back()->withErrors($validator)->withInput();
                                }
                                $pG++;
                            }
                            /**
                             *  Step 2. Save all Images to Respective Table 
                             */
                            if(PropertiesGallery::insert($prodctGallery)){
                                $galleryUploaded = ' And gallery Files are also Uploaded.';
                            }
                        }
                        //-------------image-----------------------
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/site-master');
                }
            }
            
        }
        
        return view('admin.Site.master-site', $viewData)->with($getFormAutoFillup);
    }
    public function siteTrash(Request $request, $cid = null)
    {
        if(Firm::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/firm/add');
    }
    public function voucherPrint($vid=null)
    {
    //	echo $vid;
    	 $voutureData['pageTitle'] = 'Customer Voucher ';
    	
    	$voutureData['voucher'] = Voucher::whereId($vid)->first()->toArray();
    //	print_r($voutureData);
    	return view('master.voucher-print', $voutureData);

    }
}
