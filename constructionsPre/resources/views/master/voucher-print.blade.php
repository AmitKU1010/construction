@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">Print Voucher</h3>
            </div>
            <!-- /.box-header -->
 <div class="box-body">
          
       


            <div class="box-body">
                
                <div class="table-bootstrap">
                  <table id="example1" class="table table-bordered table-striped">
                   
                    <tr>
                      <td>Voucher Number </td>
                      <td> : </td>
                      <td>{{ $voucher['voucher_no'] }}</td>
                      <td>Voucher Amount </td>
                      <td> : </td>
                       <td>{{ $voucher['amount'] }}</td>
                    </tr>
                    <tr>
                      <td>Flow Type </td>
                      <td> : </td>
                      <td>{{ $voucher['flow_type'] }}</td>
                      <td>Employee Id</td>
                      <td> : </td>
                       <td>{{ $voucher['employee_id'] }}</td>
                    </tr>
                    <tr>
                      <td>Pay To</td>
                      <td> : </td>
                      <td>{{ $voucher['pay_to'] }}</td>
                      <td>Voucher Date</td>
                      <td> : </td>
                       <td>{{ $voucher['voucher_date'] }}</td>
                    </tr>
                     <tr>
                      <td>Voucher Details</td>
                      <td> : </td>
                      <td>{{ $voucher['voucher_details'] }}</td>
                      <td>Payment Mode</td>
                      <td> : </td>
                       <td>{{ $voucher['payment_mode'] }}</td>
                    </tr>
                    <tr>
                      <td>Voucher created Date</td>
                      <td> : </td>
                      <td>{{ $voucher['created_at'] }}</td>
                      <td>Voucher Ureated Date</td>
                      <td> : </td>
                       <td>{{ $voucher['updated_at'] }}</td>
                    </tr>
                   
                  
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-lg-1 col-offset-6 centered">
                
               </div>  
                <div class="col-lg-1 col-offset-6 centered">
                
               </div>  
                <div class="col-lg-1 col-offset-6 centered">
               
               </div>  
                <div class="col-lg-1 col-offset-6 centered">
                
               </div>  
                <div class="col-lg-1 col-offset-6 centered">
                
               </div> 
                <div class="col-X-6 col-offset-6 centered">
                 <button align="center"  type="button" id="printVoucher"  class="btn btn-primary" >Print</button>
               </div>  
                <div class="col-lg-1 col-offset-6 centered">
               
               </div>   
             

          </div>


    </section>
    <!-- /.content -->
    @endsection
    @section('extra-javascript') 
<script type="text/javascript">
$(document).ready(function(){
   $('#printVoucher').on("click",function(){
    $('#printVoucher').hide();
        window.print();
      $('#printVoucher').show();
  })
});

</script>
    @endsection