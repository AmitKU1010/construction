<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\Customer;
use App\Property;
use App\Purchase;
use App\PurchasesInstallment;
use App\User;
use App\PropertiesRoom;
use App\CustomerEnquiry;
use DateTime;
use Auth;
use DB;
use App\PropertiesInstallment;
use Carbon\Carbon;


// use App\State;
// use App\City;
// use App\Country;
// use App\PropertiesVariant;
// use App\PropertiesInstallment;
// use App\PropertiesGallery;
// use App\FlatsType;
// use App\Facility;

class AdminCustomerController extends Controller
{
    public $customersUserId;
    public function addcustomer(Request $request, $eid = null)
    {
        $viewData['pageTitle'] = 'Customer Purchase Details';
         $validationRules = [
             'customer_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            'email' => 'required|email|unique:email|max:255',
            // 'gst' => 'required|max:20',
        ];
//         $PropertiesRoom=$PropertiesRoom = DB::table('properties_rooms')->where('property_id', '=', $eid)->get();
//       ///   $PropertiesRoom= json_decode(json_encode($PropertiesRoom), true);
//           $viewData['PropertiesRoom']=$PropertiesRoom;
// // echo $eid;

//          //  $PropertiesRoom = PropertiesRoom::where('properties_rooms',$eid);

//           // print_r($PropertiesRoom);
//           // exit;
//         $viewData['cities'] = City::where('state_id', '29')->pluck('name', 'id');
//         $viewData['propertylist'] = Property::pluck('project_name', 'id');
//         $viewData['property_types'] = ['NL' => 'New Launch', 'UC' => 'Under Construction', 'RTM' => 'Ready to Move'];
//         $viewData['facilities'] = Facility::pluck('facility', 'id');
//         $viewData['flattypelist'] = FlatsType::pluck('flat_type', 'id');
        $viewData['properties'] = Property::pluck('project_name', 'id');

        
        $viewData['customerList'] = Customer::get();
        
        if(isset($eid) && $eid != null){
            $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = Customer::whereId($eid)->first()->toArray();
        }else{
            $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = array();
        }
        //dd($validationRules);
        $validator = Validator::make($request->all(), $validationRules);
        /* Submit all data */
        if ($request->isMethod('post')){
            if($validator->fails()){
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
                    if(Customer::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/customer/add/'.$request->id);
                }else{
                    $saveData = $request->toArray();
                    //dd($saveData);
                    /**
                     * Gather USer Data and Save to the USer Table
                     */
                    $userTable = [
                        'name' => $request->customer_name,
                        'dob' => $request->dob,
                        'email' => $request->email,
                        'password' => bcrypt('secret'),
                        'aadhaar'   => $request->aadhaar,
                        'address'   => $request->address,
                        'office_address'    => $request->office_address,
                        'mobile'    => $request->contact,
                        'gender'    => $request->gender,
                    ];
                    

                    /**
                     * 1. Insert into Users Table
                     */
                    $users = new User($userTable);
                    if($users->save()){
                        $userId = $users->id;
                        $this->customersUserId=$userId;
                        /**
                         * 2. Insert into Purchase Table
                         */
                        $purchaseTable = [
                            'property_id' => $request->property_id,
                            'customer_id'   => $userId,
                            'discount' => $request->discount,
                            'gst'   => $request->gst,
                            'raw_total' => $request->raw_total,
                            'total' => $request->total,
                            'down_payment_for_room' => $request->down_payment_for_room,
                            'total_paid' => $request->down_payment_for_room,
                            'agreement_date' => $request->agreement_date,
                            'registration_date' => $request->registration_date,
                            'user_id'   => Auth::user()->id
                        ];
                        $purchaseSave = new Purchase($purchaseTable);

                        // update room start
                        $selectedRoomList= $request->all('selectedRoomList');
                        $propertiesRoomDataUpdate = [];
                        foreach($selectedRoomList['selectedRoomList'] as $keyOfRoom => $value ) 
                        {                           
                             $update = ['property_registered' => $this->customersUserId,'property_registered_at' =>new DateTime()];
                             $uId=$selectedRoomList['selectedRoomList'][$keyOfRoom];
                            DB::table('properties_rooms')->where('id', $uId)->update($update);
                        }
                      // update room end

                        if($purchaseSave->save()){
                            $purchaseId = $purchaseSave->id;
                            $purchaseInstallment = [];
                            if(isset($request->installments))
                            {
                                foreach ($request->installments as $key => $installment) {
                                    $purchaseInstallment[$key]['customer_id'] = $userId;
                                    $purchaseInstallment[$key]['property_id'] = $request->property_id;
                                    $purchaseInstallment[$key]['purchase_id'] = $purchaseId;
                                    $purchaseInstallment[$key]['installment_id'] = $installment;
                                    $purchaseInstallment[$key]['installment_id'] = $installment;
                                  //  $purchaseId
                                }
                            }


                            $installmentData = $request->only(['installment_no', 'installment_price', 'installment_desc']);
                          //  print_r(($installmentData['installment_no']));
                          //  $installmentDataSave = [];
                            if(isset($installmentData['installment_no']) && $installmentData['installment_no'] != ''){
                                foreach ($installmentData['installment_no'] as $Ikey => $Ivalue) {
                                    $installmentDataSave[$Ikey]['property_id'] =  $request->property_id;
                                    $installmentDataSave[$Ikey]['purchases_id'] =   $purchaseId;
                                    $installmentDataSave[$Ikey]['installment_no'] = $Ivalue;
                                    $installmentDataSave[$Ikey]['installment_price'] = $installmentData['installment_price'][$Ikey];
                                    $installmentDataSave[$Ikey]['installment_desc'] = $installmentData['installment_desc'][$Ikey];
                                    $installmentDataSave[$Ikey]['created_at'] = new DateTime();
                                    $installmentDataSave[$Ikey]['user_id'] = Auth::user()->id;
                                }
                                PropertiesInstallment::insert($installmentDataSave);
                            }

                           // exit;
                            /**
                             * 3. Insert into Purchase Installment table
                             */
                            if(PurchasesInstallment::insert($purchaseInstallment)){
                                $request->session()->flash('message.level', 'success');
                                $request->session()->flash('message.content', 'Customer Purchase details was successfully added!');
                            }else{
                                $request->session()->flash('message.level', 'danger');
                                $request->session()->flash('message.content', 'Something went wrong!');
                            }
                            return redirect('/admin/customer/add');
                        }
                    }
                }
            }//validation if else ends
            
        }//POST if ends
        $viewData['otherLinks'] = [
            'text' => 'View all purchases',
            'url'   => url('/').'/admin/purchase/list'
        ];
        return view('admin.Customer.customer', $viewData)->with($getFormAutoFillup);
    }
    public function purchaseListing(Request $request)
	{
		/* Generate List of Available CLasses */
      //  $purchase = Purchase::with('customer', 'installment', 'property')->paginate(20);
        // foreach($purchase)
        // dd($purchase[0]->customer->name);
       // dd($purchase-toSql()
        $purchase = DB::table('purchases')
        ->leftJoin('users', 'purchases.customer_id', '=', 'users.id')
        ->leftJoin('properties', 'purchases.property_id', '=', 'properties.id')
        ->leftJoin('properties_rooms', 'purchases.customer_id', '=', 'properties_rooms.property_registered')
        // ->leftJoin('properties_installments', 'properties_installments.purchases_id', '=', 'purchases.id')
        ->leftJoin('properties_installments', 'properties_installments.purchases_id', '=', 'purchases.id')
        
         ->select('users.*', 'properties.*', 'properties_rooms.*','purchases.*', 'properties_rooms.*','properties_installments.*','purchases.created_at as purchaseDate','purchases.id as purchasesId' ,'properties_installments.installment_price as installment_price')
        ->orderBy('users.id', 'desc')
        ->paginate(10);

        $purchase1 = DB::table('purchases')
        ->leftJoin('users', 'purchases.customer_id', '=', 'users.id')
        ->leftJoin('properties', 'purchases.property_id', '=', 'properties.id')
        ->leftJoin('properties_rooms', 'purchases.customer_id', '=', 'properties_rooms.property_registered')
        // ->leftJoin('properties_installments', 'properties_installments.purchases_id', '=', 'purchases.id')
        ->leftJoin('properties_installments', 'properties_installments.purchases_id', '=', 'purchases.id')
        ->leftJoin('payment_histories', 'payment_histories.propery_id', '=', 'purchases.property_id')
         ->select('users.*', 'properties.*', 'properties_rooms.*','purchases.*', 'properties_rooms.*','properties_installments.*','purchases.created_at as purchaseDate','payment_histories.created_at as paymentDate','payment_histories.propery_id as properyIdForPaymentHistory','purchases.id as purchasesId' ,'properties_installments.installment_price as installment_price')
        ->orderBy('users.id', 'desc')
        ->paginate(10);
//dd($purchase);
 // $defaulterList = DB::table('purchases')
 //        ->leftJoin('users', 'purchases.customer_id', '=', 'users.id')
 //        ->leftJoin('properties', 'purchases.property_id', '=', 'properties.id')
 //        ->leftJoin('properties_rooms', 'purchases.customer_id', '=', 'properties_rooms.property_registered')
 //        ->leftJoin('payment_histories', 'payment_histories.propery_id', '=', 'properties.id')
 //        ->leftJoin('properties_installments', 'properties_installments.property_id', '=', 'properties.id')
 //         ->select('users.*', 'properties.*', 'properties_rooms.*','purchases.*', 'properties_rooms.*','properties_installments.*','purchases.created_at as purchaseDate','payment_histories.created_at as paymentDate','payment_histories.propery_id as properyIdForPaymentHistory')

//working code : start
        //  $defaulterList = DB::table('purchases')
        //  ->leftJoin('users', 'purchases.customer_id', '=', 'users.id')
        //  ->leftJoin('properties', 'purchases.property_id', '=', 'properties.id')
        //  ->leftJoin('properties_installments', 'properties_installments.property_id', '=', 'properties.id')
        //  ->leftJoin('properties_rooms', 'purchases.property_id', '=', 'properties_rooms.property_id')
        // ->leftJoin('payment_histories', 'payment_histories.propery_id', '=', 'purchases.property_id')
        //  ->select( 'properties_rooms.*','users.*','properties.*','purchases.created_at as purchaseDate','payment_histories.created_at as paymentDate','payment_histories.propery_id as properyIdForPaymentHistory','purchases.*')
        //  ->get();
//working code : end




      //   echo nl2br($defaulterList);
 //       //  ->whereRaw('payment_histories.id=select max(id) from payment_histories where ')

 //        ->orderBy('users.id', 'desc')
 //        ->get();
 //      //  echo nl2br($defaulterList);
         $currentdate = new Carbon();
        $currentdate=date_add($currentdate,date_interval_create_from_date_string("-30 days"));
        $defaulterListArray=[];
        foreach ($purchase1 as $key => $value) {
         // $paymentDate = $value->paymentDate;
             $properyIdForPaymentHistory=$value->properyIdForPaymentHistory;
            if(isset($properyIdForPaymentHistory) && $properyIdForPaymentHistory!=null)
            {
                //   DB::enableQueryLog();
                $paymentmax = DB::table('payment_histories')
                ->where("propery_id","=",$properyIdForPaymentHistory)
                ->select("payment_histories.*")
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();
                //dd(DB::getQueryLog());
                foreach ($paymentmax as $key1 => $value1) {
              //  echo "if(".$value1->created_at."<".$currentdate.")";
// echo strtotime($value1->created_at)."<".strtotime($currentdate);
                    if(strtotime($value1->created_at)<strtotime($currentdate))
                    {
                        array_push($defaulterListArray, $value);
                    }
                }
                // echo "<br/>,$properyIdForPaymentHistory===paymentmax";
                // echo nl2br($paymentmax);
                // echo "==<br/>";          
            }
            else
            {
                if($value->purchaseDate<$currentdate)
                {
                  //  $defaulterListArray= $value;
                    array_push($defaulterListArray, $value);
                }
            }
        }

       // print_r($defaulterListArray);
 //            echo "<br/>paymentDate".$properyIdForPaymentHistory;
 //          print_r($paymentDate);
 //        //  echo "<br/>paymentDate".$paymentDate;
 //           $purchaseDate = $value->purchaseDate;
 //         //  echo "<br/>purchaseDate".$purchaseDate;
 //           if((isset($paymentDate) &&  $paymentDate < $currentdate ))
 //           {
 //           // echo "ok";
 //           }
 //        }
 //        exit;
        //$currentdate = new Carbon();
      //  $currentdate=date_add($currentdate,date_interval_create_from_date_string("-30 days"));
//DB::enableQueryLog();
//         $defaulterList = DB::table('purchases')
//         ->leftJoin('properties_installments', 'purchases.property_id', '=', 'purchases.property_id')
//         ->leftJoin('payment_histories', 'payment_histories.propery_id', '=', 'purchases.property_id')
//         ->select('properties_installments.*', 'purchases.*', 'payment_histories.*','purchases.created_at as purchaseDate','payment_histories.created_at as paymentDate')
//       //  ->where('purchases.created_at','<=',$currentdate)
//         // ->where('purchases.created_at','>',$currentdate)
//       //  ->orWhere('payment_histories.created_at', '<=', $currentdate)
//         // ->andWhere('payment_histories.created_at', '>', $currentdate)date_format($date,"Y/m/d H:i:s")
//         ->get();
//         foreach ($defaulterList as $key => $value) {
//             if(isset($value->paymentDate))
//             {
//                  echo $value->paymentDate;echo "<br/>";
//                 $paymentDate = $value->paymentDate;    
//                 $date = new DateTime($paymentDate);
//                 $diffTime= $date->diff(new Carbon())->format("%d");
//                 if($diffTime>30)
//                 {
//                 echo $diffTime;echo "<br/>";
//                 }
//             }else
//             {
//             $purchaseDate = $value->purchaseDate;    
//                 $date = new DateTime($purchaseDate);
//                 $diffTime= $date->diff(new Carbon())->format("%d");
//                 if($diffTime>30)
//                 {
//                 echo $diffTime;echo "<br/>";
//                 }
//             }


            
// //$now = new DateTime();

// //echo $date->diff($currentdate)->format("%d days, %h hours and %i minuts");
//         //   $purchaseDate = date_format($value->purchaseDate,'Y-m-d');
//           //  $date1=date_create("2013-03-15");
// //$date2=date_create("2013-12-12");
// //$diff=date_diff($value->purchaseDate,$currentdate);
// //echo $diff;
//             // if((date_format($value['purchaseDate'],'Y-m-d')) < (date_format($value['purchaseDate'],'Y-m-d'))
//             // {
//             //     echo date_format($value['purchaseDate'],'Y-m-d');
//             // }
//         }
        // echo $defaulterList;
        // dd(DB::getQueryLog());
        // foreach ($defaulterList as $key => $value) {
        //    print_r($value);
        // }
       //  $currentdate = new Carbon();
       // // $currentdate= new DateTime();
       //  $currentdate=date_add($currentdate,date_interval_create_from_date_string("-30 days"));
       //  echo $currentdate;
//         $date1=date_create("2013-03-15");
// $date2=date_create("2013-12-12");
// $diff=date_diff($date1,$date2);
// echo $diff->format("%R%a days");
//       //  print_r($defaulterList);

        // $defaulterList = DB::table('purchases')
        // ->leftJoin('properties_installments', 'purchases.property_id', '=', 'purchases.property_id')
        // ->leftJoin('payment_histories', 'payment_histories.propery_id', '=', 'purchases.property_id')
        //  ->select('properties_installments.*', 'purchases.*', 'payment_histories.*')
        // ->get(20);
        // foreach ($defaulterList as $key => $value) {
        //     # code...
        // }
        // print_r($defaulterList);
//print_r($purchase);
//dd( $purchase);
//exit;
//SELECT * FROM `properties_rooms` JOIN purchases on purchases.customer_id =properties_rooms.property_registered
       // $PropertiesRoom=$PropertiesRoom = DB::table('properties_rooms')->where('property_id', '=', $eid)->get();
       //   $viewData['PropertiesRoom']=$PropertiesRoom;

      //  dd($purchase);

       // dd($purchase->toArray());
		$customFields['basic'] = array(
			// 'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number','mandatory'=>true),
			// 'employee_id'=>array('type' => 'select', 'label'=>'Choose Class', 'value' => $employeeList, 'mandatory'=>true, 'class' => 'admission_class'),
    	);
		return view('admin.Purchase.purchase-list', ['otherLinks' => array('url' => url('/').'/admin/customer/add', 'text' => 'New Purchase'), 'pageTitle' => 'Purchase List', 'datas' => $purchase, 'defaulterlist' => $defaulterListArray,'customFields' => $customFields, 'loopInit' => '1']);
	}
    public function customersTrash(Request $request, $cid = null)
    {
        if(Customer::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/customer/add');
    }
    public function addEnquiry()
    {
        $viewData['pageTitle'] = 'Customer Enquiry ';
         $viewData['enquiry']= CustomerEnquiry::paginate(5);
        return view('admin.Enquiry.enquiry',$viewData);
    }
    public function saveEnquiry(Request $request,$eid = null)
    {
        if(isset($request->id) && $request->id != null)
        {
            if(isset($request->_token) && $request->_token != ''){
                unset($request['_token']);
            }
            $request['is_customer'] = isset($request['is_customer']) ? 1 : 0;
            //dd($request->toArray());
            if(CustomerEnquiry::where([['id', '=', $request->id]])->update($request->toArray())){
                $request->session()->flash('message.level', 'info');
                $request->session()->flash('message.content', 'Enquiry Details are Updated Successfully !');
            }else{
                session()->flash('status', ['danger', 'Addition was Failed!']);
            }
           // return redirect('/admin/customer/add/'.$request->id); 
            return redirect('/admin/customer/enquiry'); 
        }
        else
        {
            $saveEnquiry= $request->except(['id','_token']);
            if(CustomerEnquiry::insert($saveEnquiry)){
                $request->session()->flash('message.level', 'success');
                $request->session()->flash('message.content', 'Enquiry Details  was successfully added!');
            }else{
                $request->session()->flash('message.level', 'danger');
                $request->session()->flash('message.content', 'Something went wrong!');
            }
            return redirect('/admin/customer/enquiry'); 
            // if(CustomerEnquiry::insert($saveEnquiry));
            // {
            //    return redirect('/admin/customer/enquiry');  
            // }
            
        }
       
      
    }
    public function editEnquiry($eid)
    {
         $viewData['pageTitle'] = 'Customer Enquiry ';
         $viewData['enquiry']= CustomerEnquiry::paginate(5);
         $getFormAutoFillup = CustomerEnquiry::whereId($eid)->first()->toArray();
          return view('admin.Enquiry.enquiry',$viewData)->with($getFormAutoFillup);
      //  CustomerEnquiry::
       // echo $eid;
    }
     public function trashEnquiry(Request $request, $cid = null)
    {
        if(CustomerEnquiry::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Customer Enquiry Has Deleted!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/customer/enquiry'); 
    }
//     public function purchaseListing(Request $request)
//     {
//         /* Generate List of Available CLasses */
//       //  $purchase = Purchase::with('customer', 'installment', 'property')->paginate(20);
//         // foreach($purchase)
//         // dd($purchase[0]->customer->name);
//        // dd($purchase-toSql()
//         $purchase = DB::table('purchases')
//         ->leftJoin('users', 'purchases.customer_id', '=', 'users.id')
//         ->leftJoin('properties', 'purchases.property_id', '=', 'properties.id')
//         ->leftJoin('properties_rooms', 'purchases.customer_id', '=', 'properties_rooms.property_registered')
//         ->leftJoin('properties_installments', 'properties_installments.property_id', '=', 'properties.id')
//          ->select('users.*', 'properties.*', 'properties_rooms.*','purchases.*', 'properties_rooms.*','properties_installments.*')
//         ->orderBy('users.id', 'desc')
//         ->paginate(20);
// //print_r($purchase);
// //dd( $purchase);
// //exit;
// //SELECT * FROM `properties_rooms` JOIN purchases on purchases.customer_id =properties_rooms.property_registered
//        // $PropertiesRoom=$PropertiesRoom = DB::table('properties_rooms')->where('property_id', '=', $eid)->get();
//        //   $viewData['PropertiesRoom']=$PropertiesRoom;

//       //  dd($purchase);

//        // dd($purchase->toArray());
//         $customFields['basic'] = array(
//             // 'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number','mandatory'=>true),
//             // 'employee_id'=>array('type' => 'select', 'label'=>'Choose Class', 'value' => $employeeList, 'mandatory'=>true, 'class' => 'admission_class'),
//         );
//         return view('admin.Purchase.purchase-list', ['otherLinks' => array('url' => url('/').'/admin/customer/add', 'text' => 'New Purchase'), 'pageTitle' => 'Purchase List', 'datas' => $purchase, 'customFields' => $customFields, 'loopInit' => '1']);
//     }
}
