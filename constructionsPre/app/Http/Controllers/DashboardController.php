<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Http\Request;
use App\Property;
use App\PropertiesGallery;
use App\Enquiry;
use App\Customer;
use App\Supplier;
use App\User;
use Helper;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $viewData['pageTitle'] = 'Dashboard';
        $dbCustomer = Customer::all();
        $customerList = count($dbCustomer);
        $dbSupplier = Supplier::all();
        $supplierList = count($dbSupplier);
        $dbUser = User::all();
        $userList = count($dbUser);
        $viewData['dashlets'] = [
            [
                'name' => 'Total Customers',
                'count' => $customerList,
                'color' => 'blue',
                'icon' => 'ion ion-person-add',
                'url' => url('/').'/admin/customer/add',
            ],
            [
                'name' => 'Total Income',
                'count' => '15,000',
                'color' => 'green',
                'icon' => 'ion ion-stats-bars',
                'url' => '#',
            ],
            [
                'name' => 'Total Supplier',
                'count' => $supplierList,
                'color' => 'yellow',
                'icon' => 'ion ion-bag',
                'url' => url('/').'/admin/supplier/add',
            ],
            [
                'name' => 'Total Staffs',
                'count' => $userList,
                'color' => 'red',
                'icon' => 'ion ion-person-add',/*ion-pie-graph*/
                'url' => '#',
            ]
        ];
        
        /**
         * ------------------------------------------------------------------------------
         * Generating Statistics Graphs for different Fields
         * ------------------------------------------------------------------------------
         */
        // Start date
        $date = date('Y-m-01');
        // End date
        $end_date = date('Y-m-t');
        $totalDates = '';
        $totalPurchases = '';
        while (strtotime($date) <= strtotime($end_date)) {
            $total = Helper::GetCurrentDateDataCount($date);
            //echo "COUNT=".$totalPurchase;
            if(isset($total) && $total > '0'){
                $totalPurchases .= $total.',';
            }else{
                $totalPurchases .= '0'.',';
            }
            $totalDates .= '"'.$date.'",';
            
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        
        $totalDates = rtrim($totalDates, ',');
        $totalDates = '['.$totalDates.']';

        $totalPurchases = rtrim($totalPurchases, ',');
        $totalPurchases = '['.$totalPurchases.']';


        $viewData['totalDates']     = $totalDates;
        $viewData['totalPurchases'] = $totalPurchases;
        

         //$viewData['propertyList'] = Property::get();
         $viewData['propertyList'] = Property::with('gallery')->get();
         
         //$viewData['galleryphoto'] = PropertiesGallery::get();
    	return view('admin.Dashboard.dashboard', $viewData);
    }
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    public function enquiryadd(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add Customer';
         $validationRules = [
             'reference_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        
        $viewData['enquiryList'] = Enquiry::get();
        
        if(isset($eid) && $eid != null){
            //$validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = Enquiry::whereId($eid)->first()->toArray();
        }else{
            //$validationRules['email'] = 'required|email|max:255';
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
                    if(Enquiry::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/dashboard'.$request->id);
                }else{
                    $saveData = $request->toArray();
                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new Enquiry($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/dashboard');
                }
            }
            
        }
        
        return view('admin.Dashboard.dashboard', $viewData)->with($getFormAutoFillup);
    }   
    
}
