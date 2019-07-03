<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use Auth;

class AdminSupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function supplier(Request $request, $eid = null)
    {
        $viewData['pageTitle'] = 'Add Supplier';
         $validationRules = [
            'name' => 'required|max:255',
            // 'mobile' => 'required|digits:10',
            // 'address' => 'required|max:255',
            'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gstin' => 'required|max:20',
        ];

        
        $viewData['supplierList'] = Supplier::get();
    	
       	if(isset($eid) && $eid != null){
            $validationRules['email'] = 'required|email|max:255';
    		$getFormAutoFillup = Supplier::whereId($eid)->first()->toArray();
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
		    		$updateStudent = Supplier::find($request->id);
		    		if(isset($request->_token) && $request->_token != ''){
		    			unset($request['_token']);
		    		}
		    		

		    		//dd($request->toArray());
		    		if(Supplier::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/admin/supplier/add/'.$request->id);
		    	}else{
		    		$saveData = $request->toArray();
		    		/* Calculation of total Amount on the GST and Discount */
		    		$saveData['user_id'] = Auth::user()->id;
		    		$saveData = new Supplier($saveData);

		    		if($saveData->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Supplier details was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
		        	}
		        	return redirect('/admin/supplier/add');
		        }
            }
			
        }
        
        return view('admin.Supplier.supplier', $viewData)->with($getFormAutoFillup);
    }
    public function suppliersTrash(Request $request, $cid = null)
	{
		if(Supplier::find($cid)->delete()){
    		$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Service was Trashed!');
    	}else{
    		session()->flash('status', ['danger', 'Operation was Failed!']);
    	}
    	return redirect('/admin/supplier/add');
	}
}
