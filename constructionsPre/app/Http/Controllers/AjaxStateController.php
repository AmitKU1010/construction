<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Property;
use App\State;
use App\City;
use App\Country;
use Auth;

class AjaxStateController extends Controller
{
    //function for Ajax calling state with country id from view
    public function AjaxState(Request $request)
        {
            $country_id = $request->country_id;
            //echo "dsadas"; exit; 
            //$admission_section = $request->admission_section;
            $getAllstates = State::where('country_id', $country_id);
            // if(isset($admission_section) && $admission_section !=''){
            //     $getAllStudents->where('section', $admission_section);
            // }
            $getAllstates = $getAllstates->pluck('name', 'id');
            echo json_encode($getAllstates);
        }
        //function for Ajax calling city with country id from view
         public function AjaxCity(Request $request)
        {
            $state_id = $request->state_id;
            //echo "dsadas"; exit; 
            //$admission_section = $request->admission_section;
            $getAllcities = City::where('state_id', $state_id);
            // if(isset($admission_section) && $admission_section !=''){
            //     $getAllStudents->where('section', $admission_section);
            // }
            $getAllcities = $getAllcities->pluck('name', 'id');
            echo json_encode($getAllcities);
        }
}