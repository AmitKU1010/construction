<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Bill;
use App\BillsDetail;
use App\ServiceType;
use App\Service;
use App\User;

class AdminCustomerBillController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function bill(Request $request)
    {
         $validationRules = [
            'customer_name' => 'required|max:255',
            'customer_contact' => 'required|digits_between:1,10',
            'customer_address' => 'required|max:255',
        ];

        
        
        
       	if(isset($eid) && $eid != null){
    		$getFormAutoFillup = Stock::whereId($eid)->first()->toArray();
    	}else{
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
		    		$updateStudent = Stock::find($request->id);
		    		if(isset($request->_token) && $request->_token != ''){
		    			unset($request['_token']);
		    		}
		    		

		    		//dd($request->toArray());
		    		if(Stock::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/admin/stock/add/'.$request->id);
		    	}else{
                    //dd($request->all());
                    $saveBill = new Bill();
                    $saveBill->customer_name = $request->customer_name;
                    $saveBill->customer_contact = $request->customer_contact;
                    $saveBill->customer_address = $request->customer_address;
                    if($saveBill->save()){
                        $billInsertId = $saveBill->id;
                        $billDetailsStatus = 0;
                        for($i=0; $i < count($request->service_person_id); $i++){
                            $saveBillDetails = new BillsDetail();
                            $saveBillDetails->bill_id = $billInsertId;
                            $saveBillDetails->service_person_id = $request->service_person_id[$i];
                            $saveBillDetails->service_type_id = $request->service_type_id[$i];
                            $saveBillDetails->service_id = $request->service_id[$i];
                            $saveBillDetails->service_price = $request->service_price[$i];
                            $saveBillDetails->service_qty = $request->service_qty[$i];
                            $saveBillDetails->service_amount = $request->service_amount[$i];
                            if($saveBillDetails->save()){
                                $billDetailsStatus ++;
                            }
                        }
                        if($billDetailsStatus == count($request->service_person_id)){
                            $request->session()->flash('message.level', 'info');
		    			    $request->session()->flash('message.content', 'Bill Details Successfully Inserted !');
                        }else{
                            $request->session()->flash('message.level', 'danger');
		    			    $request->session()->flash('message.content', 'Somethig went wrong!');
                        }
                    }
		        	return redirect('/admin/bill');
		        }
            }
			
        }
        $viewData = [
            'pageTitle' => 'Add Purchase',
            'otherLinks' => array(
                'link' => url('/').'/admin/bill/list',
                'text' => 'View Purchase Details'
            ),
            'serviceTypes' => ServiceType::pluck('name', 'id'),
            'servicePersons' => User::where('role_id', '4')->pluck('name', 'id')
        ];
        return view('admin.Bill.genearte-bill', $viewData)->with($getFormAutoFillup);
    }
    public function purchaseList(Request $request)
    {
        $viewData = [
            'pageTitle' => 'Purchase List',
            'otherLinks' => array(
                'link' => url('/').'/admin/bill',
                'text' => 'Add Purchase'
            ),
            // 'serviceTypes' => ServiceType::pluck('name', 'id'),
            // 'servicePersons' => User::where('role_id', '4')->pluck('name', 'id'),
            'purchaseList' => Bill::with('details','details.servicePerson', 'details.serviceType', 'details.service')->paginate(5)
        ];

        
//dd($viewData['purchaseList']);
        //dd($viewData['purchaseList']->toArray());
        return view('admin.Bill.bill-list', $viewData);
    }
    /**
     * Ajax : this fucntion returns the list of Services according to the Service type
     */
    public function serviceList(Request $request)
    {
        $servie_type_id = $request->servicetpid;
        $getServiceList = Service::where('service_type_id', $servie_type_id)->pluck('name', 'id');
        echo json_encode($getServiceList);
    }
}
