<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\Firm;
use Auth;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFirmController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addfirm(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add Form';
         $validationRules = [
             'firm_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        
        $viewData['firmList'] = Firm::get();
        
        if(isset($eid) && $eid != null){
           // $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = Firm::whereId($eid)->first()->toArray();
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
                    if(Firm::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/firm/add/'.$request->id);
                }else{
                    $saveData = $request->toArray();

                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new Firm($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/firm/add');
                }
            }
            
        }
        
        return view('admin.Firm.firm', $viewData)->with($getFormAutoFillup);
    }
    public function firmsTrash(Request $request, $cid = null)
    {
        if(Firm::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/firm/add');
    }
    public function changePassword(Request $request)
    {
       $viewData['pageTitle'] = 'Change Password';
        if($request->isMethod('post'))
        {
            $id= Auth::user()->id;
            $userData=DB::table('users')->where('id','=',$id)->get();
            $validationRules= [
                'old_password' => 'required',
                'password' => 'required|confirmed',
            ];
            $validator = Validator::make($request->all(), $validationRules);
            if($validator->fails()){

                return back()->withInput()->withErrors($validator);
            }
            else
            {
                if (Hash::check($request->old_password, $userData[0]->password))
                {
                    $user= Auth::user();
                    $user->password = Hash::make($request->password);
                    $user->setRememberToken(Str::random(60));
                    if($user->save())
                    {
                         $request->session()->flash('message.level', 'success');
                         $request->session()->flash('message.content', 'Password Changed Successfully');
                        
                       
                    }
                    else
                    {
                        $request->session()->flash('message.level', 'danger');
                        $request->session()->flash('message.content', 'Something went wrong!');
                         return view('admin.change-password',$viewData);
                    }
                }
                else
                {
                    $request->session()->flash('message.level', 'danger');
                    $request->session()->flash('message.content', 'Password does not matched!');
                     return view('admin.change-password',$viewData);
                }
               
            }
        }
        $viewData['pageTitle'] = 'Change Password';
       return view('admin.change-password',$viewData); 
    }
}
