<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use DB;
use Helper;
use App\Menu;
use App\Role;
use Mail;
use Excel;

class AdminMenuController extends Controller
{
	public function __construct(){
        $this->middleware('auth');
    }
    public function send()
    {

    	Mail::send('Mail.mail',['text' => 'mail', 'name' => 'Tanmaya PARAM HERE'], function($message){
    		$message->to('tanmaya4u12@gmail.com', 'to tanmaya Patra')->subject('Test laravel email');
    		$message->from('tanmayasmtpdev@gmail.com', 'From Tanmaya Public Mail');
    	});
    }
    public function manageMenu(Request $request, $eid = null)
    {

    	$view['pageTitle'] = 'Menu Management';
    	$view['count'] = '1';
    	/* 
			Gather Menu List for Drop Down Parent menu
		*/
		$menuDdlList = [];
    	if(Menu::count() > 0){
    		$menuDdlList = Menu::where('parent_id', '0')->pluck('name', 'id')->toArray();
    		$view['menuList'] = Menu::where('parent_id', '0')->get()->toArray();
    		$view['menuListAll'] = Menu::get()->toArray();
    	}
    	$view['parentMenus'] = array('0' => '-- No Parent Menu --') + $menuDdlList;
    	/*
			Gather Roles for Assignment
    	*/
    	if(Role::count() > 0){
    		$view['roleList'] = array('' => '-- Choose Role --') + Role::pluck('name', 'id')->toArray();
    	}

    	$view['faIcons'] = Helper::getGlyphIcons();

    	//dd($view);

    	$validationRules = [
            'name' => 'required|max:255',
            'url' => 'required|max:150',
            'sort' => 'required|numeric',
            'role_id' => 'required'
        ];
    	$validator = Validator::make($request->all(), $validationRules);
        if(isset($eid) && $eid != null){
    		$getAutofillData = Menu::find($eid)->toArray();
    		$getAutofillData['role_id'] = json_decode($getAutofillData['role_id'], true);
    	}else{
    		$getAutofillData = array();
    	}
		/* Submit all data */
    	if ($request->isMethod('post')){
    		if($validator->fails()){
            	return redirect('/manage/menu')->withErrors($validator)->withInput();
            }else{
            	if(isset($request->id) && $request->id != null){
		    		/* 
						Update
		    		*/
                    $request->merge(['role_id' => json_encode($request->role_id)]);
		    		unset($request['_token']);
		    		//dd($request->toArray());
		    		if(Menu::where([['id', '=', $request->id]])->update($request->toArray())){
		    			$request->session()->flash('message.level', 'info');
		    			$request->session()->flash('message.content', 'Menu Details are Updated Successfully !');
		    		}else{
		        		session()->flash('status', ['danger', 'Addition was Failed!']);
		        	}
		        	return redirect('/manage/menu/');
		    	}else{
		    		$request->merge(['role_id' => json_encode($request->role_id)]);
		    		$saveMenu = $request->toArray();
		    		
		    		$menu = new Menu($saveMenu);

		    		if($menu->save()){
		        		$request->session()->flash('message.level', 'success');
		    			$request->session()->flash('message.content', 'Menu was successfully added!');
		        	}else{
		        		session()->flash('status', ['danger', 'Addition of Menu Failed!']);
		        	}
		        	return redirect('/manage/menu');
		        }
            }
			
    	}
    	
    	
    	return view('admin.Menu.manage-menu', $view)->with($getAutofillData);
    }
    public function menuDelete(Request $request, $deleteId)
    {
    	if(Menu::find($deleteId)->delete()){
    		$request->session()->flash('message.level', 'info');
		    $request->session()->flash('message.content', 'Menu was successfully deleted!');
    	}else{

    	}
    	return redirect('/manage/menu');
    }

}
