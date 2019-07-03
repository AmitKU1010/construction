<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Service;
use App\ServiceType;
use Auth;

class AdminServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function services(Request $request, $eid = null)
    {
        $viewData['pageTitle'] = 'Add Services';
         $validationRules = [
            'name' => 'required',
            'price' => 'required'
        ];
        $viewData['servicesList'] = Service::with('type')->get();
        $viewData['servicesListOptn'] = ServiceType::pluck('name', 'id'); 
    	$validator = Validator::make($request->all(), $validationRules);
       	if(isset($eid) && $eid != null){
    		$getFormAutoFillup = Service::whereId($eid)->first()->toArray();
    	}else{
    		$getFormAutoFillup = array();
    	}
		/* Submit all data */
    	if ($request->isMethod('post')){
    		if($validator->fails()){
            	return back()->withErrors($validator)->withInput();
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
		        	return redirect('/voucher-edit/'.$request->id);
		    	}else{
		    		$saveData = $request->toArray();
		    		/* Calculation of total Amount on the GST and Discount */
		    		$saveData['user_id'] = Auth::user()->id;
		    		$saveData = new Service($saveData);

		    		if($saveData->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Service details was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Updation of Service details Failed!']);
		        	}
		        	return redirect('/admin/services/add');
		        }
            }
			
        }
        
        return view('admin.Services.service', $viewData)->with($getFormAutoFillup);
    }
    public function servicesTrash(Request $request, $cid = null)
	{
		if(Service::find($cid)->delete()){
    		$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Service was Trashed!');
    	}else{
    		session()->flash('status', ['danger', 'Operation was Failed!']);
    	}
    	return redirect('/admin/services/add');
	}
}
