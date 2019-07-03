<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Stock;
use App\Supplier;
use Auth;

class AdminStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function stockManage(Request $request, $eid = null)
    {
        $viewData['pageTitle'] = 'Add Stock Entry';
         $validationRules = [
            'supplier_id' => 'required|max:255',
            'invoice_no' => 'required|max:255',
            'order_no' => 'required|max:255',
            'date' => 'required|date',
            'hsn_code' => 'required|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|digits_between:1,10',
            'gst' => 'required|numeric',
            'product_name' => 'required|max:255',
            'discount' => 'required|numeric',
            'total' => 'required|numeric',
            //'email' => 'required|email|unique:suppliers,email|max:255',
            'gst' => 'required|max:20',
        ];

        
        $viewData['supplierList'] = Supplier::pluck('name', 'id');
        $viewData['stockList'] = Stock::with('supplier')->get();
    	
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
		    		$saveData = $request->toArray();
		    		/* Calculation of total Amount on the GST and Discount */
		    		$saveData['user_id'] = Auth::user()->id;
		    		$saveData = new Stock($saveData);

		    		if($saveData->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Stock details was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Updation of Stock details Failed!']);
		        	}
		        	return redirect('/admin/stock/add');
		        }
            }
			
        }
        
        return view('admin.Stock.stock-add', $viewData)->with($getFormAutoFillup);
    }
    public function stockTrash(Request $request, $cid = null)
	{
		if(Stock::find($cid)->delete()){
    		$request->session()->flash('message.level', 'warning');
			$request->session()->flash('message.content', 'Stock was Trashed!');
    	}else{
    		session()->flash('status', ['danger', 'Operation was Failed!']);
    	}
    	return redirect('/admin/stock/add');
	}
}
