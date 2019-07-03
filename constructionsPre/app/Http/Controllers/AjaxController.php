<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admission;
use App\Property;
use App\Payment_historie;
use DateTime;
use DB;

class AjaxController extends Controller
{
    /*
		##################################################################
		-------------------------- AJAX PARTS ----------------------------
		##################################################################
	*/
	public function ajaxGetStudentListByStudentId(Request $request)
	{
		$admission_class = $request->admission_class;
		$getAllStudents = Admission::where('admission_class', $admission_class)->pluck('name', 'id');
		
		echo json_encode($getAllStudents);
	}
	public function ajaxGetBookListByClassId(Request $request)
	{
		$admission_class = $request->admission_class;
		$getAllBooks = Library::where('admission_class', $admission_class)->pluck('raw_book_name', 'id');
		
		echo json_encode($getAllBooks);
	}
	public function getPropertyDetails(Request $request)
	{
		$propertyId = $request->propid;
		$send = [];
		$propertyDetails = Property::where('id', $propertyId)->with('installments', 'flats_type')->first();
	//	print_r($propertyDetails['flats_type']->toArray());
		
		//dd($propertyDetails);
		/**
		 * Get the Property Details
		 */
		$send['details']['project_name'] = $propertyDetails['project_name'];
		$send['details']['project_desc'] = $propertyDetails['project_desc'];
		$send['details']['address'] = $propertyDetails['address'];
		$send['details']['down_payment'] = $propertyDetails['down_payment'];
		$send['details']['build_up_area'] = $propertyDetails['build_up_area'];
		$send['details']['block_name'] = $propertyDetails['block_name'];
		$send['details']['floor_no'] = $propertyDetails['floor_no'];
		$send['details']['flat_no'] = $propertyDetails['flat_no'];
		$send['details']['flat_type'] = $propertyDetails['flats_type']['flat_type']; 
		$send['details']['total_price'] = $propertyDetails['total_price']; 

		foreach ($propertyDetails->installments as $key => $value) {
			$send['installments'][$key]['inst_id'] = $value['id'];
			$send['installments'][$key]['inst_no'] = $value['installment_no'];
			$send['installments'][$key]['inst_price'] = $value['installment_price'];
			$send['installments'][$key]['inst_desc'] = $value['installment_desc'];
		}
		echo json_encode($send);
	}

	public function getRoomDetail(Request $request)
	{
		$eid= $request->propid;
		$PropertiesRoom = $PropertiesRoom = DB::table('properties_rooms')->where('property_id', '=', $eid)->get();
		$PropertiesRoom = json_decode(json_encode($PropertiesRoom), true);
		$propertiesRoomData = $request->only(['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate']);
		$table="";
		foreach($PropertiesRoom as $keyValue=>$key)
		{
			if($key['property_registered']==NULL)
			{
				$table.="<tr>";
				$table.="<td><input type=\"checkbox\" onchange=\"getRoomPrice(".$key['id'].",".$key['rate'].")\" name=\"selectedRoomList[]\" id=\"".$key['id']."\" value=\"".$key['id']."\"</td>";
				$table.="<td>".$key['block_name']."</td>";
				$table.="<td>".$key['floor_number']."</td>";
				$table.="<td>".$key['flate_number']."</td>";
				$table.="<td>".$key['flate_type']."</td>";
				$table.="<td>".$key['sq_ft']."</td>";
				$table.="<td>"."<input id='".$key['id']."'  value='".$key['rate']."' type=\"hidden\"/>".$key['rate']."</td>";
				$table.="</tr>";	
			}

					
		}
		return $table;
	}
	public function getSoldRoomDetail(Request $request)
	{
		$eid= $request->propid;
		 $PropertiesRoom = DB::table('properties_rooms')
		->leftJoin('users', 'users.id', '=', 'properties_rooms.property_registered')
		->where('property_id', '=', $eid)->get();
		$PropertiesRoom = json_decode(json_encode($PropertiesRoom), true);
		//$propertiesRoomData = $request->only(['block_name','floor_number', 'flate_number', 'flate_type', 'sq_ft','rate']);
		$table="";
		//print_r($PropertiesRoom);
		//exit;
		// dd($PropertiesRoom);
		$j=0;
		foreach($PropertiesRoom as $keyValue=>$key)
		{
			
			if($key['property_registered']!=NULL)
			{
				$table.="<tr>";
				$table.="<td>".++$j."</td>";
				$table.="<td>".$key['name']."</td>";
				$table.="<td>".$key['email']."</td>";
				$table.="<td>".$key['block_name']."</td>";
				$table.="<td>".$key['floor_number']."</td>";
				$table.="<td>".$key['flate_number']."</td>";
				$table.="<td>".$key['flate_type']."</td>";
				$table.="<td>".$key['sq_ft']."</td>";
				$table.="<td>"."<input id='".$key['id']."'  value='".$key['rate']."' type=\"hidden\"/>".$key['rate']."</td>";
				$table.="</tr>";	
			}

					
		 }
		return $table;
	}
	function savePayment(Request $request)
	{
		$pid = $request->propertyId;
		$purchase_id = $request->purchaseId;
		//echo $pid;
		//DB::table('payment_histories')->where('property_id', '=',  $request->propertyId)->get();
		 $paymentHistory = DB::table('payment_histories')
		->where('propery_id', '=',  $pid)
		->where('purchases_id','=',$purchase_id)
		->select('installment_amount')
		->get();
		$totalInstalmentNumber= DB::table('properties_installments')->where('purchases_id', $purchase_id)->pluck('installment_no');
		//print_r($totalInstalmentNumber);
		//echo $totalInstalmentNumber[0];
		//exit;
		$paymentHistory = json_decode(json_encode($paymentHistory), true);
		$totalAmount=$request->installmentPrice;
		$installmentNumber=0;
		foreach($paymentHistory as $key=>$value)
		{
			$totalAmount+=$value['installment_amount'];
			$installmentNumber=$installmentNumber+1;
			//echo $totalAmount."<br/>";
			
		}

		$installmentNumber=$installmentNumber+1;
		// echo $installmentNumber; echo "<br/>";
	//	print_r($paymentHistory);
 
		// if($totalAmount==0)
		// {
		// 	$totalAmount=$request->installmentPrice;
		// }
		//  echo $totalAmount; echo "<br/>";
		// exit;

		$data=[
		    	'propery_id' => $request->propertyId, 
		    	'purchases_id' => $request->purchaseId, 
		    	'installment_no' => $installmentNumber,
		    	'payment_type' => $request->paymentType, 
		    	'total_amount' =>$totalAmount+$request->down_payment_for_room, 
		    	'installment_amount' => $request->installmentPrice, 
		    	'discription'=>$request->paymentDiscription,
		    	'created_at' =>new DateTime() 
		    ];
		//print_r($data);
		//exit;
		if($installmentNumber>$totalInstalmentNumber[0])
		{
			return "<font color=\"green\">You Are Already Paid</font>";
		}
		else
		{
			if(DB::table('payment_histories')->insert($data))
			{
				return "<font color=\"blue\">Saved successfully</font>";
			}
			else
			{
				return "<font color=\"blue\">Error</font>";
			}
		}
		

       
	}
	function paymentList(Request $request)
	{
		
		$eid = $request->property_id;
		$purchase_id = $request->purchase_id;
//echo $purchase_id;
//exit;
		 $paymentHistory = DB::table('payment_histories')
		->Join('properties', 'properties.id', '=', 'payment_histories.propery_id')
		->Join('purchases', 'purchases.id', '=', 'payment_histories.purchases_id')
		
		->orderBy('payment_histories.id','desc')
		->select('payment_type','total_amount','down_payment_for_room','installment_amount','properties.*','purchases.*','payment_histories.created_at as paymentCreatedDate','purchases.created_at as purchasesCreatedDate')

		->where('purchases_id',$purchase_id)
		->where('propery_id',$eid)
		->get();
// dd($paymentHistory);
		$paymentHistory = json_decode(json_encode($paymentHistory), true);
	//	echo $paymentHistory[0]['created_at'];
	//echo $paymentHistory[0]['total'];
		// print_r($paymentHistory);
		$table="";
		$j=0;
		foreach($paymentHistory as $keyValue=>$key)
		{

			// if($key[0]['paymentCreatedDate']!=NULL)
			// {
			// 	echo "paymentCreatedDate";
			// }
			// else if($key[0]['purchasesCreatedDate']!=NULL)
			// {
			// 	echo "purchasesCreatedDate";
			// }
		//echo "<br/>";
				 $table.="<tr>";
				 $table.="<td>".++$j."</td>";
				 $table.="<td>".$key['payment_type']."</td>";				 
				 $table.="<td>".$key['total_amount']."</td>";
				 $table.="<td>".$key['down_payment_for_room']."</td>";
				 $table.="<td>".$key['installment_amount']."</td>";
				 $table.="<td>".$key['created_at']."</td>";
				 $table.="</tr>";		
		 }
		 // $users = DB::table('users')
   //              ->orderBy('name', 'desc')
   //              ->get();

		return $table;
	}
}
// function paymentDefaulterList(Request $request)
// 	{
		
// 		$eid = $request->property_id;
// 		 $paymentHistory = DB::table('payment_histories')
// 		->leftJoin('properties', 'properties.id', '=', 'payment_histories.propery_id')
// 		->leftJoin('purchases', 'properties.id', '=', 'purchases.property_id')
// 		->where('propery_id', '=', $eid)
// 		->orderBy('payment_histories.id','desc')
// 		->select('payment_histories.*','properties.*','purchases.*','payment_histories.created_at as paymentCreatedDate','purchases.created_at as purchasesCreatedDate')
// 		->get();

// 		$paymentHistory = json_decode(json_encode($paymentHistory), true);
// 	//	echo $paymentHistory[0]['created_at'];
// 	//echo $paymentHistory[0]['total'];
// 		// print_r($paymentHistory);
// 		$table="";
// 		$j=0;
// 		foreach($paymentHistory as $keyValue=>$key)
// 		{

// 			if($key['paymentCreatedDate']!=NULL)
// 			{
// 				echo "paymentCreatedDate";
// 			}
// 			else if($key['purchasesCreatedDate']!=NULL)
// 			{
// 				echo "purchasesCreatedDate";
// 			}
		
// 				 $table.="<tr>";
// 				 $table.="<td>".++$j."</td>";
// 				 $table.="<td>".$key['payment_type']."</td>";				 
// 				 $table.="<td>".$key['total_amount']."</td>";
// 				 $table.="<td>".$key['down_payment_for_room']."</td>";
// 				 $table.="<td>".$key['installment_amount']."</td>";
// 				 $table.="<td>".$key['created_at']."</td>";
// 				 $table.="</tr>";		
// 		 }
// 		 // $users = DB::table('users')
//    //              ->orderBy('name', 'desc')
//    //              ->get();

// 		return $table;
// 	}
//}
// foreach($paymentHistory as $keyValue1=>$key1)
// 		{
// 			//$paymentHistory[$i]
// 			// if($key['property_registered']!=NULL)
// 			// {
// 			foreach($key1 as $keyValue=>$key)
// 			{
// 				print_r($keyValue);echo "<br/>";
// 				// $table.="<tr>";
// 				// $table.="<td>".++$j."</td>";
// 				// //$table.="<td>".$key['total_amount']."</td>";
// 				// $table.="<td>".$key['down_payment']."</td>";
// 				// $table.="<td>".$key['installment_amount']."</td>";
// 				// $table.="<td>".$key['created_at']."</td>";
// 				// $table.="</tr>";	
// 			}

					
// 		 }