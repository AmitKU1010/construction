@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of all Money Flow</h3>
            </div>
            <!-- /.box-header -->
 <div class="box-body">
          
        <?php 
          $cashFlowHelper = Helper::cashFlowList('2018-03-04');


?>
        @if(session()->has('message.level'))
          <div class="alert alert-{{ session('message.level') }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
            {!! session('message.content') !!}
          </div>
        @endif

        {{-- @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
             {{ Form::open(['url' => 'employee-list', 'files' => true]) }} 
             {{ csrf_field() }}
             {{ Form::hidden('id', isset($id) ? $id :'', []) }}
               




                                            

            {{-- <div class="box-footer">
              {{ Form::submit('Search', array('class' => 'btn btn-success')) }}
              {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
            </div> --}}

          <!-- /.row -->
        </div>
<?php 
 // dd($budgets);openingBalance
?>


            <div class="box-body">
              <div class="table-bootstrap">
              	{{--  SLOT FOR OPENING BAANCE  --}}
              	<h2>Opening balance overview</h2>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SL no.</th>
                    <th>Balance Voucher code</th>
                    <th>Added by</th>
                    <th>Added date</th>
                    <th>Balance Amount</th>
                    <th>Remark</th>

                  </tr>
                  </thead>
                  <tbody>
                  	@php $openingbalanceTotal = 0; @endphp
                    @foreach($openingBalance as $key => $oBalance)
                  <tr class="info">
                    <td>{{ $key+1 }}</td>
                    <td>{{ $oBalance['voucher_no'] }}</td>
                    <td>{{ $oBalance['pay_to'] }}</td>
                    <td>{{ Carbon\Carbon::parse($oBalance['voucher_date'])->format('d-M-Y') }}</td>
                    <td>
                      {{ $oBalance['amount'] }}
                      @php $openingbalanceTotal += $oBalance['amount']; @endphp
                    </td>
                    <td>{{ $oBalance['voucher_details'] }}</td>
                  </tr>
                  @endforeach
                
                  </tbody>
                  <thead>
                    <tr>
                      <th colspan="5">Total</th>
                      <th>{{ $openingbalanceTotal }}</th>
                    </tr>
                  </thead>
                </table>

              	<h2>Admission Cash Flows</h2>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SL no.</th>
                    <th>Student Name</th>
                    <th>Academic Year/Month</th>
                    <th>Fees paid for</th>
                    <th>Fees paid date</th>
                    <th>Fees paid amount</th>

                  </tr>
                  </thead>
                  <tbody>
                  	@php $admissionFeesTotal = 0; @endphp
                    @foreach($getFeesDetails as $key => $breaks)
                  <tr class="success">
                    <td>{{ $key+1 }}</td>
                    <td>{{ $breaks['name'] }}</td>
                    <td>{{ $breaks['academic_year'].'/'.$breaks['academic_month'] }}</td>
                    <td>{{ $breaks['subcategory_name'] }}</td>
                    <td>{{ Carbon\Carbon::parse($breaks['payment_date'])->format('d-M-Y') }}</td>
                    <td>
                      {{ $breaks['amount'] }}
                      @php $admissionFeesTotal += $breaks['amount']; @endphp
                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                  <thead>
                    <tr>
                      <th colspan="5">Total</th>
                      <th>{{ $admissionFeesTotal }}</th>
                    </tr>
                  </thead>
                </table>

                {{-- SLOT FOR VOUCHERS --}}
                <h2>Vochers Cash Flows</h2>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SL no.</th>
                    <th>Voucher Number</th>
                    <th>Voucher Type</th>
                    <th>Sanctioned for</th>
                    <th>Date of sanction</th>
                    <th>Payment Mode</th>
                    <th>Fees paid amount</th>

                  </tr>
                  </thead>
                  <tbody>
                  	@php $voucherBalanceTotal = 0; @endphp
                    @foreach($vouchers as $key => $voucher)
                  <tr class="{{ ($voucher->flow_type == 'OUTFLOW') ? 'danger' : 'success'}}">
                    <td>{{ $key+1 }}</td>
                    <td>{{ $voucher->voucher_no }}</td>
                    <td>{{ $voucher->flow_type }}</td>
                    <td>{{ $voucher->pay_to }}</td>
                    <td>{{ Carbon\Carbon::parse($voucher->voucher_date)->format('d-M-Y') }}</td>
                    <td>{{ $voucher->payment_mode }}</td>
                    <td>
                      {{ $voucher->amount }}
                      @php $voucherBalanceTotal += $voucher->amount; @endphp
                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                  <thead>
                    <tr>
                      <th colspan="6">Total</th>
                      <th>{{ $voucherBalanceTotal }}</th>
                    </tr>
                  </thead>
                </table>

                <div class="col-md-6 col-sm-6 col-xs-12">
		          <div class="info-box bg-aqua">
		            <span class="info-box-icon"><i class="fa fa-bookmark-o"></i></span>

		            <div class="info-box-content">
		              <span class="info-box-text">Rest Opening Balance</span>
		              <span class="info-box-number">
		              	@php
		              		$totalAllbalance = $admissionFeesTotal+$voucherBalanceTotal;
		              		$remainingBalance = $openingbalanceTotal - $totalAllbalance;

		              	@endphp
		              	{{$remainingBalance}}
		              </span>

		              <div class="progress">
		                <div class="progress-bar" style="width: 70%"></div>
		              </div>
		                  <span class="progress-description">
		                    70% Increase in 30 Days
		                  </span>
		            </div>
		            <!-- /.info-box-content -->
		          </div>
		          <!-- /.info-box -->
		        </div>


              </div>
             
            </div>
            <!-- /.box-body -->
          </div>


    </section>
    <!-- /.content -->
    @endsection