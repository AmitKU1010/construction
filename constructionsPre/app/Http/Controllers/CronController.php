<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\AdmissionFee;
use App\AdmissionFeesDetail;
use Helper;
use App\Supplier;
use App\Library;
use App\Voucher;
use App\FeesMaster;
use App\MoneyFlow;
use DB;

class CronController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function cashFlowReport(Request $request)
    {   
        $withInput = [
            'month' => date('m'),
            'year' => date('Y'),
        ];
        $viewData['pageTitle'] = 'Month wise Money Flow details';
        $filterMonth = $withInput['month'];
        $filterYear = $withInput['year'];
        if ($request->isMethod('post')){
            $filterMonth = $withInput['month'] = $request->month;
            $filterYear = $withInput['year'] = $request->year;

        }
        $start_date = $filterYear.'-'.$filterMonth.'-'.'01';
        $end_date = $filterYear.'-'.$filterMonth.'-'.'31';
        //echo "SD=".$start_date.' ED='.$end_date; exit;
        $dateRange = Helper::dateRange($start_date, $end_date);

        /**
         * Get the List of Payment in/outs From the "Admission" Module
         */
        foreach ($dateRange as $key => $dateRange) {

            $totalCashFlows = Helper::cashFlowList($dateRange);
            //dd($totalCashFlows);
            $viewData['totalBalance'] = 0;
            $viewData['data'][$dateRange]['inflow'] = array();
            $viewData['data'][$dateRange]['outflow'] = array();
            $viewData['data'][$dateRange]['balance'] = array();

            foreach($totalCashFlows['admissions'] as $admissions){
                
                

                $viewData['data'][$dateRange]['inflow'][] = [
                    'info' => 'Received the Fees having Payment ID = <strong>PMT'.$admissions['admission_fees_details_id'].$admissions['admision_fees_id'].$admissions['admission_type_id'].strtotime($admissions['payment_date']).'</strong> for '.$admissions['subcategory_name'].' of the Student -'.$admissions['name'].', for the Academic Year of : '.$admissions['academic_year'].'/'.$admissions['academic_month'],
                    'amount' => $admissions['amount']
                ];
            }

            foreach ($totalCashFlows['vouchers'] as $vouchers) {
                if(isset($vouchers['flow_type']) && $vouchers['flow_type'] == 'OUTFLOW'){
                    $viewData['data'][$dateRange]['outflow'][] = [
                       'info' => 'A Voucher having no. "' .$vouchers['voucher_no']. '" was processed of amount '.$vouchers['amount'].' to '.$vouchers['pay_to'],
                       'amount' => $vouchers['amount']
                    ];
                }else if(isset($vouchers['flow_type']) && $vouchers['flow_type'] == 'INFLOW'){
                    $viewData['data'][$dateRange]['inflow'][] = [
                        'info' => 'A Voucher having no. "' .$vouchers['voucher_no']. '" was processed of amount '.$vouchers['amount'].' to '.$vouchers['pay_to'],
                        'amount' => $vouchers['amount']
                     ];
                }
            }
            
            foreach ($totalCashFlows['openingBalance'] as $key => $cashflow) {
                $viewData['data'][$dateRange]['balance'][] = [
                    'info' => 'Balance of '.$cashflow['amount'].' is added to the Account by '.$cashflow['pay_to'],
                    'amount' => $cashflow['amount']
                ];
            }
        }   
        
        //dd($totalCashFlows);
        //dd($viewData);

        return view('admin.cash-flow', $viewData)->with($withInput);
    }
    public function calculateInout(Request $request)
    {
    	$finalBudgetOverview = array();
    	$getAdmissionDetails = AdmissionFee::select('fees_master_id')->get()->toArray();
    	$sumOfFees = 0;
    	foreach ($getAdmissionDetails as $key => $value) {
            /* All Fees ID. Get Total Money by circulating each fees ID */
    		$fees_master_id = json_decode($value['fees_master_id'], true);
            $totalFeesOfAllStudent = 0;
    		foreach ($fees_master_id as $k => $v) {
    			$getFeesDetails = FeesMaster::select('amount')->get()->toArray();
                foreach ($getFeesDetails as $key => $value) {
                    $sumOfFees += $value['amount'];
                }
                $totalFeesOfAllStudent += $sumOfFees;
    		}
    	}
        /* Here the Sum of all Fees */
        $finalBudgetOverview['fees_total'] = $totalFeesOfAllStudent;


        /* Get Voucher Total */
        $voucherTotal = 0;
        $getVoucherDetails = Voucher::select('amount', 'flow_type')->get()->toArray();
        foreach ($getVoucherDetails as $voucher) {
            if($voucher['flow_type'] == 'OUTFLOW'){
                $voucherTotal = $voucherTotal - $voucher['amount'];
            }else if($voucher['flow_type'] == 'INFLOW'){
                $voucherTotal = $voucherTotal + $voucher['amount'];
            }
        }
        /* Here the sum of all Voucher */
        $finalBudgetOverview['voucher_total'] = $voucherTotal;
        //dd($getVoucherDetails);

        MoneyFlow::truncate();
        foreach ($finalBudgetOverview as $key => $value) {
            $insertToMoneyFlow = new MoneyFlow();
            $insertToMoneyFlow->amount = $value;
            if($key == 'fees_total'){
                $type = 'Total Admission Fees';
            }else if($key == 'voucher_total'){
                $type = 'Total Voucher Fees';
            }
            $insertToMoneyFlow->type = $type;
            $insertToMoneyFlow->save();
        }
        
        $customFields['basic'] = array(
            'voucher_no'=>array('type' => 'text', 'label'=>'Voucher Number','mandatory'=>true),
            'employee_id'=>array('type' => 'select', 'label'=>'Choose Class', 'value' => array(), 'mandatory'=>true, 'class' => 'admission_class'),
        );
    	$budgets = MoneyFlow::get()->toArray();
        return view('master.money-flow', ['otherLinks' => array('link' => url('/').'#', 'text' => ''), 'customFields' => $customFields, 'budgets' => $budgets, 'formButton' => isset($sid) ? 'Update Details' : 'Save Details', 'pageTitle' => isset($sid) && $sid != '' ? 'Edit Voucher':'Money Management Overview', 'loopInit' => '1']);
    }
    public function moneyflowDetails(Request $request)
    {
        /*
            Student Admission Fees Calculation 
        */
        $getAdmissionDetails = AdmissionFee::with('admission')->get()->toArray();
        $sumOfFees = 0;
        $admissionMoneyFlow = array();
        foreach ($getAdmissionDetails as $key => $value) {
            /* All Fees ID. Get Total Money by circulating each fees ID */
            $fees_master_id = json_decode($value['fees_master_id'], true);
            $totalFeesOfAllStudent = 0;
            foreach ($fees_master_id as $k => $v) {
                $getFeesDetails = FeesMaster::select('amount')->get()->toArray();
                foreach ($getFeesDetails as $key1 => $value1) {
                    $sumOfFees += $value1['amount'];
                }
            }
            $value['total_money'] = $sumOfFees;
            //dd($value);
            $admissionMoneyFlow[] = $value;

        }

        dd($admissionMoneyFlow);


        /*
            Library Fines Calculations
        */


        /*
            Vouchers Amount Calculations    
        */
    }
}
