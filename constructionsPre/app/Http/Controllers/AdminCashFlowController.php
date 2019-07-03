<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminCashFlowController extends Controller
{
    public function balanceSheet(Request $request)
    {
        $viewData['pageTitle'] = 'Balance Sheet';
        $balanceSheet = [];
        $count = 0;
        /**
         * Get List from Voucher Table
         */
        $voucherData = \App\Voucher::get();
        foreach ($voucherData as $voucherKey => $voucherValue) {
            if($voucherValue['flow_type'] == 'OUTFLOW'){
                $balanceSheet[$voucherValue['voucher_date']][$count]['type'] = 'Voucher';
                $balanceSheet[$voucherValue['voucher_date']][$count]['mode'] = 'EXP';
                $balanceSheet[$voucherValue['voucher_date']][$count]['date'] = $voucherValue['voucher_date'];
                $balanceSheet[$voucherValue['voucher_date']][$count]['amount'] = $voucherValue['amount'];
                $balanceSheet[$voucherValue['voucher_date']][$count]['details'] = 'A sum of total Rs.'.$voucherValue['amount'].' paid to '.$voucherValue['pay_to'].' by '.$voucherValue['payment_mode'];
            }else if($voucherValue['flow_type'] == 'INFLOW'){
                $balanceSheet[$voucherValue['voucher_date']][$count]['type'] = 'Voucher';
                $balanceSheet[$voucherValue['voucher_date']][$count]['mode'] = 'INC';
                $balanceSheet[$voucherValue['voucher_date']][$count]['date'] = $voucherValue['voucher_date'];
                $balanceSheet[$voucherValue['voucher_date']][$count]['amount'] = $voucherValue['amount'];
                $balanceSheet[$voucherValue['voucher_date']][$count]['details'] = 'A sum of total Rs. '.$voucherValue['amount'].' deposited on behalf of '.$voucherValue['pay_to'].' by '.$voucherValue['payment_mode'];
            }
            $count++;
        }

        /**
         * Get List from the Purchase Module
         */
        $purchaseData = \App\Purchase::with('customer')->get();
        //$count++;
        foreach ($purchaseData as $purchaseKey => $purchaseValue) {
            $purchaseDate = \Carbon\Carbon::parse($purchaseValue->cretead_at)->format('Y-m-d');
            $balanceSheet[$purchaseDate][$count]['type'] = 'Sell';
            $balanceSheet[$purchaseDate][$count]['mode'] = 'INC';
            $balanceSheet[$purchaseDate][$count]['date'] = \Carbon\Carbon::parse($purchaseValue->cretead_at)->format('d/M/Y');
            $balanceSheet[$purchaseDate][$count]['amount'] = $purchaseValue['total'];
            $balanceSheet[$purchaseDate][$count]['details'] = 'Property Sold to '.$purchaseValue['customer']['name'].', for an amount of Rs. '.$purchaseValue['total'];

            $count++;
        }
        
        /**
         * Sorting the Multidimentional Balance Sheet Array Date wise Descending Manner
         */
        foreach ($balanceSheet as $key => $part) {
            $sort[$key] = $key;
        }
        array_multisort($sort, SORT_DESC, $balanceSheet);
        $viewData['balanceSheet'] = $balanceSheet;
        return view('admin.BalanceSheet.balance-sheet', $viewData);
    }
}
