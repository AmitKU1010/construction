@extends('layouts.default')
@section('content')
<section class="content">
   {{ Form::open(['url' => 'admin/customer/save','files' => 'true' ,'enctype' => 'multipart/form-data', 'autocomplete' => 'OFF']) }} 
   {{ csrf_field() }}
   {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
   <div class="row">
      <!-- left column -->
      <div class="col-md-12 col-sm-12">
         <!-- general form elements -->
         <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Please fill up the Customer details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
            @if ($errors->any())
                <ul class="alert alert-danger" style="list-style:none">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            @if(session()->has('message.level'))
                <div class="alert alert-{{ session('message.level') }} alert-dismissible" onload="javascript: Notify('You`ve got mail.', 'top-right', '5000', 'info', 'fa-envelope', true); return false;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
                  {!! session('message.content') !!}
                </div>
            @endif
            
               <div class="col-md-6">
                  <div class="form-group">
                      <label for="exampleInputPassword1"> Choose Property</label>
                      {{Form::select('property_id', $properties, isset($property_id)?$property_id: '', ['class' => 'select2 form-control property_id', 'placeholder' => 'Enter a customer name']  )}}
                      <p class="help-block">
                         {{ $errors->has('property_id') ? $errors->first('property_id', ':message') : '' }}
                      </p>
                   </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Customer Name</label>
                     {{Form::text('customer_name', isset($customer_name)?$customer_name: '', ['class' => 'form-control','required' ,'placeholder' => 'Enter a customer name']  )}}
                     <p class="help-block">
                        {{ $errors->has('customer_name') ? $errors->first('customer_name', ':message') : '' }}
                     </p>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="exampleInputPassword1">Date of Birth</label>
                           {{Form::text('dob', isset($dob)?$dob: '', ['class' => 'form-control payment_datepicker','id'=>'datepicker', 'placeholder' => '-- Select dob']  )}}
                           <p class="help-block">
                              {{ $errors->has('dob') ? $errors->first('dob', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-grou">
                           <label for="exampleInputPassword1">Gender</label>
                           {{Form::select('gender',[ 'male' => 'male','female' => 'female'], null , ['class' => 'form-control', 'placeholder' => 'Choose gender'] )}}
                           <p class="help-block">
                              {{ $errors->has('gender') ? $errors->first('gender', ':message') : '' }}
                           </p>
                        </div>
                     </div> 
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="exampleInputPassword1">Contact no</label>
                           {{Form::text('contact', isset($contact)?$contact: '', ['class' => 'form-control', 'placeholder' => 'Enter a customer contact no']  )}}
                           <p class="help-block">
                              {{ $errors->has('contact') ? $errors->first('contact', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="exampleInputPassword1">Email Id (*A Password will be sent to this Email ID)</label>
                           {{Form::text('email', isset($email)?$email: '', ['class' => 'form-control','required', 'placeholder' => 'Enter a customer email id']  )}}
                           <p class="help-block">
                              {{ $errors->has('email') ? $errors->first('email', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="exampleInputPassword1">Aadhaar No</label>
                           {{Form::text('aadhaar', isset($adhhar)?$adhhar: '', ['class' => 'form-control', 'placeholder' => 'Enter a customer Aadhaar no']  )}}
                           <p class="help-block">
                              {{ $errors->has('aadhaar') ? $errors->first('aadhaar', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label for="exampleInputPassword1">Aadhaar Proof Upload</label>
                           {{Form::file('aadhaar_upload', ['class' => 'form-control', 'placeholder' => 'Enter a customer Aadhaar no']  )}}
                           <p class="help-block">
                              {{ $errors->has('aadhaar_upload') ? $errors->first('aadhaar_upload', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                  </div>
                  
                  
                  <div class="form-group">
                     <label for="exampleInputPassword1">Address</label>
                     {{Form::textarea('address', isset($address)?$address: '', ['class' => 'form-control', 'placeholder' => 'Enter  address', 'rows' => '3']  )}}
                     <p class="help-block">
                        {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                     </p>
                  </div>
                  <div class="form-group">
                     <label for="exampleInputPassword1">Office Address</label>
                     {{Form::textarea('office_address', isset($office_address)?$office_address: '', ['class' => 'form-control', 'placeholder' => 'Enter office address', 'rows' => '3']  )}}
                     <p class="help-block">
                        {{ $errors->has('office_address') ? $errors->first('office_address', ':message') : '' }}
                     </p>
                  </div>
                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        {{Form::checkbox('agreement_check')}} Accept Agreements ? (By clicking on check box, You are accepting this)
                      </label>
                    </div>
                    {{Form::text('agreement_date', isset($agreement_date)?$agreement_date: '', ['class' => 'form-control agreement_date', 'placeholder' => 'Choose a date']  )}}
                  </div>
                  <div class="form-group">
                      <div class="checkbox">
                        <label>
                            {{Form::checkbox('registration_check')}} Registration Done ? (By clicking on check box, You are accepting this)
                        </label>
                      </div>
                      {{Form::text('registration_date', isset($registration_date)?$registration_date: '', ['class' => 'form-control registration_date', 'id' => 'datepicker', 'placeholder' => 'Choose a date']  )}}
                  </div>
  

               </div>
               <div class="col-md-6">
                  <div class="col-md-12">
                      <div class="box box-success box-solid">
                        <div class="box-header with-border">
                          <h3 class="box-title">Property/Flat Details</h3>
                          <div class="box-tools pull-right">
                            {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> --}}
                          </div>
                          <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <div class="property_details"></div>
                        </div>
                        <!-- /.box-body -->
                      </div>
                      <!-- /.box -->
                    </div>

                    <!-- property Table start    -->
                    <div class="col-md-12">
                      <div class="box box-success box-solid">
                        <div class="box-header with-border">
                          <h3 class="box-title">Room List</h3>
                          
                         <table class="table table-bordered table-condensed"> 
                            <thead  id="propertyRoomHead">
                              <tr>
                                  <th>Select</th>
                                  <th>Block No</th>
                                  <th>Floor No</th>
                                  <th>Flat No</th>
                                  <th>Flat Type</th>
                                  <th>Sq Ft</th>
                                  <th>Rate</th>
                              </tr>
                           </thead>
                           <tbody id="propertyRoomBody">
                             
                           </tbody>
                         </table>
                        </div>
                      </div>
                    </div>
                      <!-- property Table end   -->
                      <!-- property Table start    -->
                    <div class="col-md-12">
                      <div class="box box-success box-solid">
                        <div class="  box-header with-border">
                          <h3 class="box-title">Sold Room List</h3>
                          <div class="table-responsive">
                         <table class="table table-bordered table-condensed"> 
                            <thead  id="propertyRoomHead">
                              <tr>
                                <th>SL#</th>
                                  <th>Customer Name</th>
                                  <th>Customer Email</th>                                  
                                  <th>Block No</th>
                                  <th>Floor No</th>
                                  <th>Flat No</th>
                                  <th>Flat Type</th>
                                  <th>Sq Ft</th>
                                  <th>Rate</th>
                              </tr>
                           </thead>
                           <tbody id="propertySoldRoomBody">
                             
                           </tbody>
                         </table>
                       </div>
                        </div>
                      </div>
                    </div>
                      <!-- property Table end   -->


                    <div class="col-md-12">
                        <div class="box box-warning box-solid">
                          <div class="box-header with-border">
                            <h3 class="box-title">Installment List</h3>
                            <div class="box-tools pull-right">
                              {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> --}}
                            </div>
                            <!-- /.box-tools -->
                          </div>
                          <!-- /.box-header -->
                          <div class="box-body">
                            <div class="property_installments"></div>
                          </div>
                          <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                      </div>

                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Discount(in %)</label>
                                  {{Form::text('discount', isset($discount) ? $discount: '0', ['class' => 'form-control discount', 'placeholder' => 'Enter Discount']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('discount') ? $errors->first('discount', ':message') : '' }}
                                  </p>
                               </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">GST(in %)</label>
                                  {{Form::text('gst', isset($gst) ? $gst: '15', ['class' => 'form-control gst', 'placeholder' => 'Enter GST']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('gst') ? $errors->first('gst', ':message') : '' }}
                                  </p>
                               </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Raw Total (in INR)</label>
                                  {{Form::text('raw_total', isset($raw_total) ? $raw_total: '0.00', ['class' => 'form-control raw_total', 'placeholder' => 'Enter raw_total']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('raw_total') ? $errors->first('raw_total', ':message') : '' }}
                                  </p>
                               </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Sum Total (in INR)</label>
                                  {{Form::text('total', isset($total) ? $total: '0.00', ['class' => 'form-control total', 'placeholder' => 'Enter total']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('total') ? $errors->first('total', ':message') : '' }}
                                  </p>
                               </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label for="exampleInputPassword1">Down Payment (in INR)</label>
                                  {{Form::number('down_payment_for_room', isset($down_payment_for_room) ? $down_payment_for_room: '', ['id'=>'down_payment_for_room','required','class' => 'form-control down_payment_for_room', 'placeholder' => 'Enter Payment']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('down_payment_for_room') ? $errors->first('down_payment_for_room', ':message') : '' }}
                                  </p>
                               </div>
                          </div>
                      </div>
                      
                      
                      
                       
                       
                       

               </div>
               <!-- <div class="form-group  col-sm-4">
                  <label for="exampleInputPassword1">Upload customer photo</label>
                    
                    {{Form::file('customer_image_raw', ['class' => 'form-control'] )}}
                    <p class="help-block">
                      {{ $errors->has('customer_image_raw') ? $errors->first('customer_image_raw', ':message') : '' }}
                    </p>
                  </div> -->
            </div>

          <table id="addmoretable" class="table table-bordered">
                <thead id="addedRowsServiceDetailhead" >
                     <tr>
                          <th>Installment No.</th>
                          <th>Installment Price</th>
                          <th>Description</th>
                          <th></th>
                      </tr>
                </thead>
                <tbody id="addedRowsServiceDetail">
                  <td >
                     {{Form::number('installment_no[]', isset($installment_no)?$installment_no: '', ['id'=>'istallmentNumber',"required",'min'=>'1','class' => 'form-control', 'placeholder' => 'Enter No of Installments']  )}}
                   </td>
                  <td>
                    {{Form::text('installment_price[]', isset($installment_price)?$installment_price: '', ['id'=>'istallmentPrice','class' => 'form-control', "readonly",'placeholder' => 'Enter Installment Price'] )}}
                   </td>
                   <td>
                     {{Form::text('installment_desc[]', isset($installment_desc)?$installment_desc: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Description']  )}}
                   </td>
                  
                </tbody>
                <!--  <tfoot>
                   <tr><td>
                      <a href="javascript:void(0)"  id="addAnotherServiceDetail"  class="addmore_xx btn btn-info btn-sm" ><i class="glyphicon glyphicon-plus-sign "></i> Add</a>
                     </td></tr>
                </tfoot> -->
            </table>

             <!-- <div class="row">
                  <div class="col-md-12">
                     <h3>Add Installment Details
                     </h3>
                     <table id="addmoretable" class="table table-bordered">
                        <thead>
                           <tr>
                              <th>Installment No.</th>
                              <th>Installment Price</th>
                              <th>Description</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           @if(isset($installments) && is_array($installments) && count($installments) > 0)
                           @foreach($installments as $oldInstKey => $oldInstValues)
                           <tr class="delete_{{$oldInstValues['id']}}">
                              <td>{{$oldInstValues['installment_no']}}</td>
                              <td>{{$oldInstValues['installment_price']}}</td>
                              <td>{{$oldInstValues['installment_desc']}}</td>
                              <td>
                                 <a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="removeInstallment({{$oldInstValues['id']}})"><i class="fa fa-trash "></i> Detete</a>
                              </td>
                           </tr>
                           @endforeach
                           @endif
                        </tbody>
                        <tbody id="addedRows1">
                           <tr id="1" class="row_2">
                              <td>{{Form::text('installment_no[]', isset($installment_no)?$installment_no: '', ['class' => 'form-control', 'placeholder' => 'Enter No of Installments']  )}}</td>
                              <td class="installment_price">{{Form::text('installment_price[]', isset($installment_price)?$installment_price: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Price']  )}}</td>
                              <td class="installment_desc">{{Form::text('installment_desc[]', isset($installment_desc)?$installment_desc: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Description']  )}}</td>
                              <td><a href="javascript:void(0)" class="addmore_xx btn btn-info btn-sm" onclick="addMoreRows1(this.form)"><i class="glyphicon glyphicon-plus-sign "></i> Add</a></td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div> -->

            <!-- /.box-body -->
            <div class="box-footer">
               <button type="submit" id="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Customer</button>
            </div>
         </div>
         <!-- /.box -->
      </div>
      <!--/.col (left) -->
      <!-- right column -->
      <!--/.col (right) -->
   </div>
   {{Form::close()}}
   <!-- /.row -->
</section>
@endsection
@section('extra-javascript')
<script type="text/javascript">
   $.ajaxSetup({
       headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
</script>
<script type = "text/javascript" language = "javascript">
  $('#submit').on("click",function(e)
  {
    if(!$("[name^=selectedRoomList]").is(':checked'))
    {
      $("[name^=selectedRoomList]").scroll();
      alert("Please Select Atleast One Room");
      e.preventDefault();
    }
  })
 // $("[name^=installments]").attr('checked',true);selectedRoomList[]
  $("#addmoretable").hide();
  $('.agreement_date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
  $('.registration_date').datepicker({
    autoclose: true,
    format: 'yyyy-mm-dd'
  });
   $(document).ready(function() {
     $('.property_id').on("change",function()
      {
         $(".total").val(0);
          $(".raw_total").val(0);
      });
    $('.overlay').hide();
      $(".property_id").change(function(event){
        $(".property_details, .property_installments").html('Loading. Please wait.....');
          $('#propertyRoomBody').html('Loading. Please wait.....');
        $('.overlay').show();
         var property_id = $(this).val();
         //property_installments
         $.ajax({
          type: "POST",
          url: "{{url('/')}}/admin/ajax/property/details",
          data: { 
                  "_token": "{{ csrf_token() }}",
                 propid : property_id,
                },
          dataType : 'html',
          cache: false,
          success: function(data){
            props = $.parseJSON(data);
            $(".property_details").empty();            
            $('.property_details').append('<ol type="i">');
            $('.property_details').append('<li>Project Name:'+props.details.project_name+'</li>');
            $('.property_details').append('<li>Address:'+props.details.address+'</li>');
            $('.property_details').append('<li>Details:'+props.details.project_desc+'</li>');
            $('.property_details').append('<li>Down Payment:'+props.details.down_payment+'</li>');
            $('.property_details').append('<li>Buildup Area:'+props.details.build_up_area+'</li>');
            $('.property_details').append('<li style="display:none"><input type="hidden" id="totalPrice" value="'+props.details.total_price+'"/></li>'); 
             $('.property_details').append('<li style="display:none"><input type="hidden" id="totalDownPayment" value="'+props.details.down_payment+'"/></li>'); 
            $('#istallmentPrice').val((props.details.total_price)-(props.details.down_payment))  ;      
            $('.property_details').append('</ol>');
          //  $("#addmoretable").show();
            $("#istallmentNumber").val("")


            $('.property_installments').empty();
            $.each(props.installments, function(i, item) {
                $('.property_installments').append(
                      '<div id="installment_'+i+'" class="checkbox"><label><input onchange="getInstallmentPrice('+i+')" id="installments_no_'+i+'" name="installments[]" value="'+item.inst_id+'" type="checkbox">'+item.inst_no+' Installment of worth INR '+item.inst_price+'</label><input type="hidden" name="price[]" class="price" value="'+item.inst_price+'"></div>'
                 );
            });


            $('.overlay').hide();
   
          }
          
      });
       // getting room detail start 
       $.ajax({
        type: "POST",
        url: "{{url('/')}}/admin/ajax/property/roomDetails",
        data: { 
                "_token": "{{ csrf_token() }}",
               propid : property_id,
              },
        dataType : 'html',
        cache: false,
        success: function(data){
          $('#propertyRoomBody').html(data);
        
         }

       });
       // getting room detail  end
        // getting room detail start 
       $.ajax({
        type: "POST",
        url: "{{url('/')}}/admin/ajax/property/sold-room-detail",
        data: { 
                "_token": "{{ csrf_token() }}",
               propid : property_id,
              },
        dataType : 'html',
        cache: false,
        success: function(data){
          $('#propertySoldRoomBody').html(data);
        
         }

       });
       // getting room detail  end
   });
  });
  function getInstallmentPrice(id) {
    
    var price = $("#installment_"+id).find('.price').val();
    //alert(price);
    var old_total_price = $(".raw_total").val();
    //alert("Old="+old_total_price);
    var discount = $(".discount").val();
    var gst = $(".gst").val();
    console.log(price + "  " + old_total_price);
    if($("#installments_no_"+id).is(":checked")){
      var totalPrice = parseFloat(old_total_price) + parseFloat(price);
    }else{
      var totalPrice = parseFloat(old_total_price) - parseFloat(price);
    }
    
    //alert(totalPrice);
    var total_with_discount = totalPrice - (totalPrice*(discount/100));
    var total_with_discount_gst = total_with_discount + (total_with_discount*(gst/100));

    
    //alert(price);
    $(".total").val(total_with_discount_gst);
    $(".raw_total").val(totalPrice);
  }
  function getRoomPrice(id,rate)
  {
    var old_total_price = $(".raw_total").val();
    //alert("Old="+old_total_price);
    var discount = $(".discount").val();
      var gst = $(".gst").val();
    if($("#"+id).is(":checked")){
      var totalPrice = parseFloat(old_total_price) + parseFloat(rate);
    }else{
      var totalPrice = parseFloat(old_total_price) - parseFloat(rate);
    }
    
    //alert(totalPrice);
    var total_with_discount = totalPrice - (totalPrice*(discount/100));
    var total_with_discount_gst = total_with_discount + (total_with_discount*(gst/100));

     $('#istallmentPrice').val(total_with_discount_gst)  ; 
    //alert(price);
    $(".total").val(total_with_discount_gst);
    $(".raw_total").val(totalPrice);
  } 
  $(document).on('keypress, keyup', '#istallmentNumber', function(){  
     var  installmentNo=parseInt($("#istallmentNumber").val());
     var totalPrice = parseInt($("[name='total']").val());
       var totalDownPayment = parseInt($("#down_payment_for_room").val());
     
    // console.log(installmentNo);
    console.log(totalDownPayment);
    if(installmentNo!=0 && installmentNo!="" && installmentNo != null && !isNaN(installmentNo))
    {

     $("#istallmentPrice").val((totalPrice-totalDownPayment)/installmentNo);
    }
    }); 
  $(document).on('keypress, keyup', '#down_payment_for_room', function(){  
     var  totalDownPayment=parseInt($("#down_payment_for_room").val());
     var totalPrice = parseInt($("[name='total']").val());
      $("#addmoretable").show();
      // var totalDownPayment = parseInt($("#totalDownPayment").val());
     
    // console.log(installmentNo);
    // console.log(totalDownPayment);
    if(totalDownPayment!=0 && totalDownPayment!="" && totalDownPayment != null && !isNaN(totalDownPayment))
    {
     $("#istallmentPrice").val(totalPrice-totalDownPayment);
    }
    }); 

  // var rowCount = 1;
  // function addMoreRows1(frm) {  
                          
  //    rowCount ++;
     
  //      var recRow = '<tr id="'+rowCount+'" class="row_'+rowCount+'"><td>{{Form::text('installment_no[]', isset($installment_no)?$installment_no: '', ['class' => 'form-control', 'placeholder' => 'Enter No of Installments']  )}}</td><td class="installment_price">{{Form::text('installment_price[]', isset($installment_price)?$installment_price: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Price'] )}}</td></td><td class="installment_desc">{{Form::text('installment_desc[]', isset($installment_desc)?$installment_desc: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Description']  )}}</td><td><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="glyphicon glyphicon-trash text-danger"></i></a></td></tr>'
   
  //    jQuery('#addedRows1').append(recRow);
   
       
  //      $('.service_type_id').change(function(){
  //          var currRow = $(this).parent().parent().attr('id');
  //          var servcie_type_id = $(this).val();
           
  //          $.ajax({
  //              type: "POST",
  //              url: "{{url('/')}}/admin/getservcielistbytypeid",
  //              data: { 
  //                      "_token": "{{ csrf_token() }}",
  //                      servicetpid : servcie_type_id,
  //                      },
  //              dataType : 'html',
  //              cache: false,
  //              success: function(data){
  //                  console.log(data);
  //                  services = $.parseJSON(data);
  //                  $('.row_'+currRow+' .services')
  //                          .empty()
  //                          .append('<option selected="selected" value="">-Select -</option>');
  //                  $.each(services, function(i, item) {
  //                      $('.row_'+currRow+' .services').append(
  //                          '<option value="'+i+'">'+item+'</option>'
  //                      );
  //                  });
  //                  $('.overlay').hide();
  //              }
  //          });
   
  //      })
  //  }
    
  //  function removeRow(removeNum) {
  //    var didConfirm = confirm("Are you sure You want to delete");
  //      if (didConfirm == true) {
  //      jQuery('.row_'+removeNum).remove();
  //    }
  //  }
  // $(document).ready(function(){  
                  
                 
  //               var i= $('#service_person_id').val();
  //               if(isNaN(i)){
  //                 i=0;
  //               }
  //               $('#addAnotherServiceDetail').on("click",function(){    
                
  //               var addRow='<tr id= "row'+i+'" class="row_'+i+'">\
  //               <td >\
  //                 {{Form::text('installment_no[]', isset($installment_no)?$installment_no: '', ['class' => 'form-control', 'placeholder' => 'Enter No of Installments']  )}}\
  //               </td>\
  //               <td>\
  //                 {{Form::text('installment_price[]', isset($installment_price)?$installment_price: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Price'] )}}\
  //               </td>\
  //               <td>\
  //                 {{Form::text('installment_desc[]', isset($installment_desc)?$installment_desc: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Description']  )}}\
  //               </td>\
  //               <td > <a id="'+i+'" href="javascript:void(0);" class="removeInstallmentDetail" "><i class="glyphicon glyphicon-trash text-danger"></i></a>\
  //               </td>\
  //               </tr>';

  //               $('#addedRowsServiceDetail').append(addRow);  
  //               });    
                
  //               $(document).on('click', '.removeInstallmentDetail', function(){  
  //                 var button_id = $(this).attr("id"); 
  //                   if($('.removeInstallmentDetail').length!=1)
  //                   {
  //                     $('#row'+button_id+'').remove();  
  //                   }
  //                   else
  //                   {
  //                     alert("Please Add Atleast One Installment");
  //                   } 
                 
  //               //  $('#rowForProduct'+button_id+'').remove();  
  //               }); 
  //               if($('.removeInstallmentDetail').length==0)
  //               {
  //               $('#addAnotherServiceDetail').click();
  //               }         
  //   });      

</script>
@endsection