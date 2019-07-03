<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;

use App\Property;
use App\Purchase;
use App\Country;
use App\City;
use App\Supplier;
use App\PropertiesVariant;
use App\PropertiesInstallment;
use App\PropertiesRoom;
use App\PropertiesGallery;
use App\FlatsType;
use App\Facility;
use App\PropertiesFacility;
use Auth;
use Image;
use DateTime;
use Session;

class AdminPropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public $lastInseredPropertyId;
    public function properties(Request $request, $eid = null)
    {
        $viewData['pageTitle'] = 'Add Properties';
         $validationRules = [
            'project_name' => 'required',
            //'contact_no' => 'required|digits:10',
            //'address' => 'required|max:255',
            //'email' => 'required|email|unique:suppliers,email|max:255',
            //'gst' => 'required|max:20',
        ];

        $viewData['supplierlist'] = Supplier::pluck('name', 'id');

          $viewData['cities'] = City::where('state_id', '29')->pluck('name', 'id');
          $PropertiesRoom=$PropertiesRoom = DB::table('properties_rooms')->where('property_id', '=', $eid)->get();
      ///   $PropertiesRoom= json_decode(json_encode($PropertiesRoom), true);
          $viewData['PropertiesRoom']=$PropertiesRoom;
// echo $eid;

         //  $PropertiesRoom = PropertiesRoom::where('properties_rooms',$eid);

          // print_r($PropertiesRoom);
          // exit;
        $viewData['cities'] = City::where('state_id', '29')->pluck('name', 'id');
        $viewData['propertylist'] = Property::pluck('project_name', 'id');
        $viewData['property_types'] = ['NL' => 'New Launch', 'UC' => 'Under Construction', 'RTM' => 'Ready to Move'];
        $viewData['facilities'] = Facility::pluck('facility', 'id');
        $viewData['flattypelist'] = FlatsType::pluck('flat_type', 'id');
        
        if(isset($eid) && $eid != null){
            $getFormAutoFillup = Property::whereId($eid)->with('facility', 'installments')->first()->toArray();
            $getFormAutoFillup['facilities_id'] = $getFormAutoFillup['oldInstallments'] = [];
            foreach($getFormAutoFillup['facility'] as $fkey => $facility){
                $getFormAutoFillup['facilities_id'][$fkey] = $facility['facility_id'];
            }
            foreach($getFormAutoFillup['installments'] as $fkey => $installment){
                $getFormAutoFillup['oldInstallments'][$fkey] = $installment;
            }
        }else{
            $getFormAutoFillup = array();
        }
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
                    $updateStudent = Property::find($request->id);
                    if(isset($request->_token) && $request->_token != ''){
                        unset($request['_token']);
                    }
                    $propertyDataUpdate = $request->only(['project_name', 'landmark', 'property_type', 'project_desc', 'total_price', 'down_payment', 'super_build_up_area', 'build_up_area', 'address']);

                    $this->lastInseredPropertyId=$request->id;
                    // echo $this->lastInseredPropertyId;
                    // exit;
                    if(Property::where([['id', '=', $request->id]])->update($propertyDataUpdate)){
                        /**
                         * Save Facilities
                         */
                        if(isset($request->facilities))
                        {
                            $propFacility = [];
                            PropertiesFacility::where('property_id', $request->id)->delete();
                            foreach($request->facilities as $facilityKey => $facility){
                                $propFacility[$facilityKey]['property_id'] = $request->id;
                                $propFacility[$facilityKey]['facility_id'] = $facility;
                            }
                            PropertiesFacility::insert($propFacility);
                        }
                        
                        
                        //Save fot 3rd table
                        // $installmentData = $request->only(['user_id','property_id', 'installment_no', 'installment_price', 'installment_desc']);
                        // $installmentDataSave = [];
                        // if(isset($installmentData['installment_no'][0]['installment_no']) && $installmentData['installment_no'][0]['installment_no'] != ''){
                        //     foreach ($installmentData['installment_no'] as $Ikey => $Ivalue) {
                        //         $installmentDataSave[$Ikey]['property_id'] = $request->id;
                        //         $installmentDataSave[$Ikey]['installment_no'] = $Ivalue;
                        //         $installmentDataSave[$Ikey]['installment_price'] = $installmentData['installment_price'][$Ikey];
                        //         $installmentDataSave[$Ikey]['installment_desc'] = $installmentData['installment_desc'][$Ikey];
                        //         $installmentDataSave[$Ikey]['user_id'] = Auth::user()->id;
                        //     }

                             //$propertiesRoomData = $request->only(['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate']);
                    //    $propertiesRoomDataSave = [];
                       

                            /**
                             * Step 4. Insert into Installments table
                             */
                        //     PropertiesInstallment::insert($installmentDataSave);
                        // }

                        PropertiesRoom::where('property_id', $this->lastInseredPropertyId)->delete();
                        $propertiesRoomData = $request->only(['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate']);

                        $propertiesRoomDataSave = [];
                      
                            foreach ($propertiesRoomData['block_name'] as $Ikey=> $Ivalue ) {
                               // echo $propertiesRoomData['block_name'][$Ikey];

                                $propertiesRoomDataSave[$Ikey]['block_name'] = $propertiesRoomData['block_name'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['floor_number'] = $propertiesRoomData['floor_number'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['flate_number'] = $propertiesRoomData['flate_number'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['flate_type'] = $propertiesRoomData['flate_type'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['sq_ft'] = $propertiesRoomData['sq_ft'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['rate'] = $propertiesRoomData['rate'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['user_id'] = Auth::user()->id;
                                $propertiesRoomDataSave[$Ikey]['property_id'] = $this->lastInseredPropertyId;   
                                $propertiesRoomDataSave[$Ikey]['updated_at'] = new DateTime();

                            }
                            PropertiesRoom::insert($propertiesRoomDataSave);


                        $request->session()->flash('message.level', 'info');
                        $request->session()->flash('message.content', 'Property Details are Updated Successfully !');
                    }else{
                        session()->flash('status', ['danger', 'Addition was Failed!']);
                    }
                    return redirect('/admin/properties/edit/'.$request->id);
                }else{
                    /**
                     * SAVING of Property and its other parameters.....................
                     */
                    $request->request->add(['user_id' => Auth::user()->id]);
                    $propertyData = $request->only(['project_name', 'property_reff_id', 'landmark', 'project_desc', 'property_type',   'city_id', 'flat_no',   'down_payment','total_price', 'super_build_up_area', 'build_up_area', 'address']);
                    $propertyData['user_id'] = Auth::user()->id;
                    $savePropertyData = new Property($propertyData);
                    /**
                     * Step 1. Insert into Main Property Table
                     */
                    if($savePropertyData->save())
                    {
                       $this->lastInseredPropertyId = $savePropertyData->id;
                        $propertyId = $savePropertyData->id;
                        $request->request->add(['property_id' => $propertyId]);
                        /**
                         * Save Facilities
                         */
                        if(isset($request->facilities))
                        {
                            $propFacility = [];
                            foreach($request->facilities as $facilityKey => $facility){
                                $propFacility[$facilityKey]['property_id'] = $propertyId;
                                $propFacility[$facilityKey]['facility_id'] = $facility;
                            }
                            PropertiesFacility::insert($propFacility);
                        }
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
                                    $destinationPath = public_path('/uploads/properties/thumbnails');
                                    $thumb_img = Image::make($file->getRealPath())->resize(100, 100);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);

                                    // Resolution : 300 x 300 
                                    $destinationPath = public_path('/uploads/properties/square');
                                    $thumb_img = Image::make($file->getRealPath())->resize(300, 300);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);

                                    //Resolution : 1600 x 1500
                                    $destinationPath = public_path('/uploads/properties/slider');
                                    $thumb_img = Image::make($file->getRealPath())->resize(1600, 1500);
                                    $thumb_img->save($destinationPath.'/'.$filename,80);
                                                
                                    $destinationPath = public_path('/uploads/properties');
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
 // this is working cide for installment  :   start
                        //Save fot 3rd table
                        // $installmentData = $request->only(['user_id','property_id', 'installment_no', 'installment_price', 'installment_desc']);
                        // $installmentDataSave = [];
                        // foreach ($installmentData['installment_no'] as $Ikey => $Ivalue) {
                        //     $installmentDataSave[$Ikey]['property_id'] = $propertyId;
                        //     $installmentDataSave[$Ikey]['installment_no'] = $Ivalue;
                        //     $installmentDataSave[$Ikey]['installment_price'] = $installmentData['installment_price'][$Ikey];
                        //     $installmentDataSave[$Ikey]['installment_desc'] = $installmentData['installment_desc'][$Ikey];
                        //     $installmentDataSave[$Ikey]['user_id'] = Auth::user()->id;
                        // }
// this is working cide for installment  :   end

                        $propertiesRoomData = $request->only(['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate']);
                        $propertiesRoomDataSave = [];
                      
                            foreach ($propertiesRoomData['block_name'] as $Ikey=> $Ivalue ) {
                                echo $propertiesRoomData['block_name'][$Ikey];

                                $propertiesRoomDataSave[$Ikey]['block_name'] = $propertiesRoomData['block_name'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['floor_number'] = $propertiesRoomData['floor_number'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['flate_number'] = $propertiesRoomData['flate_number'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['flate_type'] = $propertiesRoomData['flate_type'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['sq_ft'] = $propertiesRoomData['sq_ft'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['rate'] = $propertiesRoomData['rate'][$Ikey];
                                $propertiesRoomDataSave[$Ikey]['user_id'] = Auth::user()->id;
                                $propertiesRoomDataSave[$Ikey]['property_id'] = $this->lastInseredPropertyId;
                                $propertiesRoomDataSave[$Ikey]['created_at'] = new DateTime();

                            }

                        }
                        if(PropertiesRoom::insert($propertiesRoomDataSave)){
                            $request->session()->flash('message.level', 'success');
                            $request->session()->flash('message.content', 'Property Room Saved Successfully');
                        /**
                         * Step 4. Insert into Installments table
                         */
                        // if(PropertiesInstallment::insert($installmentDataSave)){
                        //     $request->session()->flash('message.level', 'success');
                        //     $request->session()->flash('message.content', 'Property Data Saved Successfully');
                        // }
                    }
                    else{
                        session()->flash('status', ['danger', 'Updation of Service details Failed!']);
                    }
                    return redirect('/admin/properties/add');
                }
            }
        }
        $viewData['otherLinks']['text'] = 'View all Properties';
        $viewData['otherLinks']['url'] = url('/').'/admin/properties/list';
        return view('admin.Properties.property', $viewData)->with($getFormAutoFillup);
    }
    public function list(Request $request)
    {
        $viewData['pageTitle'] = 'Property Lists';
        $properties = Property::with('gallery', 'country', 'state', 'city', 'supplier', 'installments')->paginate(20);
        //dd($properties);
        $viewData['properties'] = $properties;

         // $PropertiesRoom = PropertiesRoom::with('block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate');
        // $PropertiesRoom = DB::table('tbl_user')->pluck('block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate');
      //  $viewData['PropertiesRoom'] = $PropertiesRoom;
        


        $viewData['otherLinks']['text'] = 'Add new Property';
        $viewData['otherLinks']['url'] = url('/').'/admin/properties/add';
        return view('admin.Properties.property-list', $viewData);
    }
    public function propertyTrash(Request $request, $cid = null)
    {
     if(Property::find($cid)->delete()){
         $request->session()->flash('message.level', 'warning');
         $request->session()->flash('message.content', 'Property was Trashed!');
     }else{
         session()->flash('status', ['danger', 'Operation was Failed!']);
     }
     return redirect('/admin/properties/add');
    }
    public function deleteInstallment(Request $request)
    {
        if(PropertiesInstallment::where('id', $request->instId)->delete()){
            echo 'Y';
        }else{
            echo 'N';
        }
    }
    public function purchasesTrash($id)
    {
       
       $purchese= DB::table('purchases')->find($id);
        $property_id=  $purchese->property_id;
        $purchese= DB::table('properties_rooms')->where('properties_rooms.property_id','=',$property_id)->get();
      //  print_r($purchese);
        foreach ($purchese as $key => $value) {
            DB::table('properties_rooms')
                ->where('id', $value->id)
                ->update(['property_registered' => null]);

         // echo  $value->property_registered;
        }
        DB::table('properties_installments')->where('properties_installments.purchases_id','=',$id)->delete();
         DB::table('payment_histories')->where('payment_histories.purchases_id','=',$id)->delete();
        
     //  exit;
         if(DB::table('purchases')->delete($id)){
            Session::flash('message.level', 'warning!'); 
            Session::flash('message.content','Customer was Trashed!');
            
         }else{
             Session::flash('status',['danger', 'Operation was Failed!']);
           
         }
         return redirect('/admin/purchase/list');
      //   return redirect('/admin/properties/add'); 
    }
}
