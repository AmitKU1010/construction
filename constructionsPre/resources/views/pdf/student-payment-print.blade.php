@extends('layouts.pdf-layout')
@section('content')

@php $schoolInfo = Helper::schoolInfo(); @endphp
<div class="mysection" style="width:80%; margin: 0 auto;">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <center>
          MISCELLNEOUS RECEIPT #{{$generateInvoiceNumber}}
        <h2 class="page-header-x">{{$schoolInfo['name']}} </h2>
        <p>{{$schoolInfo['address']}}, <br>{{$schoolInfo['phone']}}, {{$schoolInfo['email']}}
        </center>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
      <!-- /.col -->
      <div class="col-sm-12 invoice-col">
        Name of the Student ...<u>{{$paymentDetails['name']}}</u>.., Token No - #SIN{{$generateInvoiceNumber}}, Class - {{$paymentDetails['admission_class']}}, Section - {{$paymentDetails['section']}}, Roll no - 09, Fees for the month - {{isset($feesPaidList[0]['academic_month']) ? $feesPaidList[0]['academic_month']: '-'}} & Year - {{$paymentDetails['academic_year']}}
        
      </div>
    <!-- /.row -->
    <!-- Table row -->
    
    <div class="row">         
      @php $totalFees = $totalParticularFees = 0; $finalArray = array(); @endphp
      @foreach($feesPaidList as $key => $payment)
      {{-- {{Helper::updatePaymentPrintStatus($payment['admission_fees_details_id'])}} --}}
      @if(isset($route) && $route != 'payment-print')
        @if($isSecret =='1' && $payment['is_secret'] == '1'  )
          @php 
            $finalArray[$key]['particulars'] = $payment['payment_for']; 
            $finalArray[$key]['fees'] = $payment['fees_amount']; 
          @endphp
        @elseif($isSecret =='0' && $payment['is_secret'] == '0')
          @php 
            $finalArray[$key]['particulars'] = $payment['payment_for']; 
            $finalArray[$key]['fees'] = $payment['fees_amount']; 
          @endphp
        @endif
      @else
      {{-- If Not filtered out then, all the list will be displayed --}}
          @php 
            $finalArray[$key]['particulars'] = $payment['payment_for']; 
            $finalArray[$key]['fees'] = $payment['fees_amount']; 
          @endphp
        {{-- End of condition --}}
      @endif


    @endforeach
        
        
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>Sl #</th>
            <th>Particulars</th>
            <th>Qty</th>
            <th>Payment amount</th>
          </tr>
          </thead>
          <tbody>
            @foreach($finalArray as $pkey => $particular)
              <tr>
                <td>{{$pkey+1}}</td>
                <td>{{$particular['particulars']}}</td>
                <td>1</td>
                <td>
                  @php $totalParticularFees+=$particular['fees']; @endphp
                  Rs. {{$particular['fees']}}
                </td>
              </tr>
            @endforeach
              <tr>
                <td colspan="2"></td>
                <td>TOTAL</td>
                <td>Rs. {{$totalParticularFees}}</td>
              </tr>
              <tr>
                <td colspan="4">
                  In Words : <br>
                  Guardian Sign : .................................... Collection Offcier : ...
                </td>
              </tr>
          </tbody>
        </table>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    {{-- <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p class="lead">Payment Methods:</p>
      <img src="{{url('/public')}}/adminLTE/dist/img/credit/visa.png" alt="Visa">
        <img src="{{url('/public')}}/adminLTE/dist/img/credit/mastercard.png" alt="Mastercard">
        <img src="{{url('/public')}}/adminLTE/dist/img/credit/american-express.png" alt="American Express">
        <img src="{{url('/public')}}/adminLTE/dist/img/credit/paypal2.png" alt="Paypal">

        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
          Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg
          dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <p class="lead">As on {{date('d/m/Y')}}</p>

        <div class="table-responsive">
          <table class="table">
            <tbody><tr>
              <th style="width:50%">Subtotal:</th>
            <td>Rs. {{$totalFees}}</td>
            </tr>
            <tr>
              <th>Tax (N.A)</th>
              <td>Rs 0</td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>Rs. {{$totalFees}}</td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div> --}}
    <!-- /.row -->
<a class="btn btn-social-icon btn-vk"><i class="fa fa-vk"></i></a>
    <!-- this row will not appear when printing -->
    @if($pageType == 'HTML')
    <div id="noprint" class="row no-print">
      <div class="col-md-3 col-md-offset-5">
 

        <div class="btn-group-horizontal">
        <button type="button" class="btn btn-warning print_btn" onclick="hideButtonPanel()"><i class="fa fa-print"></i> Print</button>
        
        <a href="{{url('/')}}/payment/printout/{{$studentID}}/pdf/secret" class="btn btn-danger  text-danger">
          <i class="glyphicon glyphicon-save-file"></i>
        </a>
        <a href="{{url('/')}}/payment/printout/{{$studentID}}/pdf/normal" class="btn btn-primary " style="margin-right: 5px;">
          <i class="glyphicon glyphicon-save-file"></i>
        </a>
        <a href="{{url('/')}}/admission-list" class="btn btn-success " ><i class="fa fa-credit-card"></i> Close </a>
        </div>

      </div>
    </div>
    @endif
  </div>
  
@endsection

    @section('extra-javascript')
     <script type = "text/javascript" language = "javascript">
         function hideButtonPanel(){
           document.getElementById("noprint").visibility  = 'hidden';
           window.print();
         }
         $(document).ready(function(){
           $(".toggle").click(function(){
              $(".secret").toggle();
          });
         });
    </script>
    <style>
      @media print
      {    
          .no-print, .no-print *
          {
              display: none !important;
          }
      }
    </style>
    @endsection