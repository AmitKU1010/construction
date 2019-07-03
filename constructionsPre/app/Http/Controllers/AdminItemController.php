<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Supplier;
use App\Firm;
use App\ItemCategory;
use App\ItemSubcategory;
use App\ItemMaster;
use Auth;

class AdminItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //------------------------------------item category-----------------------------------
    public function addCategory(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add Category';
         $validationRules = [
             'category_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        
        $viewData['categoryList'] = ItemCategory::get();
        
        if(isset($eid) && $eid != null){
           // $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = ItemCategory::whereId($eid)->first()->toArray();
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
                    if(ItemCategory::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/item/category/add/'.$request->id);
                }else{
                    $saveData = $request->toArray();

                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new ItemCategory($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/item/category/add');
                }
            }
            
        }
        
        return view('admin.Item.category', $viewData)->with($getFormAutoFillup);
    }
    public function categoryTrash(Request $request, $cid = null)
    {
        if(ItemCategory::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/item/category/add');
    }
    //--------------------------------------------------------------------

    //------------------------------item subcategory--------------------------

        public function addSubcategory(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add SubCategory';
         $validationRules = [
             'subcategory_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        /**/
        $getDetails = ItemCategory::leftJoin('Item_Subcategories', 'Item_categories.id', '=', 'Item_Subcategories.item_categories_id')
                        ->GET();


        $viewData['item_categories_id'] = ItemCategory::pluck('category_name', 'id');
        $viewData['subcategoryList'] = ItemSubcategory::get();
        
        if(isset($eid) && $eid != null){
           // $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = ItemSubcategory::whereId($eid)->first()->toArray();
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
                    if(ItemSubcategory::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/item/subcategory/add'.$request->id);
                }else{
                    $saveData = $request->toArray();

                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new ItemSubcategory($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/item/subcategory/add');
                }
            }
            
        }
        
        return view('admin.Item.subcategory', $viewData)->with($getFormAutoFillup);
    }
    public function subcategoryTrash(Request $request, $cid = null)
    {
        if(ItemSubcategory::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/item/subcategory/add');
    }
    //---------------------------------------------------------------------------
    //-----------------------------item master--------------------------------------
    public function addItemMaster(Request $request, $eid = null)
    {

        $viewData['pageTitle'] = 'Add master Item';
         $validationRules = [
             //'category_name' => 'required|max:255',
            // 'contact_no' => 'required|digits:10',
            // 'address' => 'required|max:255',
            // //'email' => 'required|email|unique:suppliers,email|max:255',
            // 'gst' => 'required|max:20',
        ];

        $viewData['item_categories_id'] = ItemCategory::pluck('category_name', 'id');
        $viewData['item_subcategories_id'] = ItemSubcategory::pluck('subcategory_name', 'id');
        $viewData['itemList'] = ItemMaster::get();
        
        if(isset($eid) && $eid != null){
           // $validationRules['email'] = 'required|email|max:255';
            $getFormAutoFillup = ItemMaster::whereId($eid)->first()->toArray();
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
                    if(ItemMaster::where([['id', '=', $request->id]])->update($request->toArray())){
                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Voucher Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/item/item_master/add'.$request->id);
                }else{
                    $saveData = $request->toArray();

                    /* Calculation of total Amount on the GST and Discount */
                    $saveData['user_id'] = Auth::user()->id;
                    $saveData = new ItemMaster($saveData);

                    if($saveData->save()){
                        $request->session()->flash('message.level', 'success');
                        $request->session()->flash('message.content', 'Supplier details was successfully added!');
                    }else{
                        session()->flash('status', ['danger', 'Updation of Supplier details Failed!']);
                    }
                    return redirect('/admin/item/item_master/add');
                }
            }
            
        }
        
        return view('admin.Item.item_master', $viewData)->with($getFormAutoFillup);
    }
    public function itemMasterTrash(Request $request, $cid = null)
    {
        if(ItemMaster::find($cid)->delete()){
            $request->session()->flash('message.level', 'warning');
            $request->session()->flash('message.content', 'Service was Trashed!');
        }else{
            session()->flash('status', ['danger', 'Operation was Failed!']);
        }
        return redirect('/admin/item/item_master/add');
    }
    //--------------------------------------------------
}
