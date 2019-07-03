<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\ConstructionSite;
use Auth;

class AdminConstructionSiteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addsite(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add Site';
         $validationRules = [
             'site_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
             'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        
        $viewData['siteList'] = ConstructionSite::get();
        
        if(isset($eid) && $eid != null){
            //$validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = ConstructionSite::whereId($eid)->first()->toArray();
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
                    if(ConstructionSite::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/construction_site/add/'.$request->id);
                }else{
                    $saveData = $request->toArray();
                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new ConstructionSite($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/construction_site/add');
                }
            }
            
        }
        
        return view('admin.Site.site', $viewData)->with($getFormAutoFillup);
    }
    public function sitesTrash(Request $request, $cid = null)
    {
        if(ConstructionSite::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/construction_site/add');
    }
}
