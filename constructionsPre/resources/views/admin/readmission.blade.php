{{-- 
  Name : Tanmaya Patra
  Info : Modal Window for Subcaterory Management
  Date : 13-Nov-2017
 --}}
 @extends('layouts.default')
 @section('content')
 <section class="content">
 <style type="text/css">
   .box-title{
     margin-left: 12px;
   }
 @php $mandatory = '<strong class="text-danger" style="font-size:20px">*</strong>'; @endphp  
 </style>
       <!-- SELECT2 EXAMPLE -->
       <div class="box box-default">
         <div class="box-header with-border">
           <h3 class="box-title">Please fill up necessary fields.</h3>
           <div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
             <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
           </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">
           <div class="overlay" style="display: none">
             <i class="fa fa-refresh fa-spin"></i>
           </div>
         @if(session()->has('message.level'))
           <div class="alert alert-{{ session('message.level') }} alert-dismissible">
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
             <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
             {!! session('message.content') !!}
           </div>
         @endif
              {{ Form::open(['url' => url('/').'/payment-save', 'form' => '1']) }} 
              {{ csrf_field() }}
              {{ Form::hidden('id', isset($id) ? $id :'', []) }}
              {{ Form::hidden('sid', isset($data['studentDetails']->id) ? $data['studentDetails']->id :'', ['class' => 'sid']) }}
              {{ Form::hidden('s_class', isset($data['studentDetails']->admission_class) ? $data['studentDetails']->admission_class :'', ['class'=>'s_class']) }}
               @php
                 if(isset($academic_month) && $academic_month != null){
                   $r = json_decode($academic_month);
                   $academic_month = array_values($r);
                 }
                 if(isset($fees_master_id) && $fees_master_id != null){
                   $fees_master_id = json_decode($fees_master_id);
                 }else{
                   $fees_master_id = array();
                 }
 
               
               @endphp
                 <div class="row">
                     <div class="col-md-4">
                       <div class="info-box bg-aqua">
                         <span class="info-box-icon"><i class="fa  fa-child"></i></span>
                         <div class="info-box-content">
                           <span class="info-box-text">{{ $data['studentDetails']->name }}</span>
                           <span class="progress-description">Father <span class="pull-right badge bg-blue">{{$data['studentDetails']->father_name}}</span></span>
                           <span class="progress-description">Mother <span class="pull-right badge bg-blue">{{$data['studentDetails']->mother_name}}</span></span>
                           <span class="progress-description">Studying in Class {{$data['studentDetails']->admission_class}}</span>
                         </div>
                         <!-- /.info-box-content -->
                       </div>
                     </div>
                       <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_year') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Academic Year <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::select('academic_year', $data['year_array'] , isset($academic_year) ? $academic_year :'', ['class' => 'form-control academic_year input-md', 'required']) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_year') ? $errors->first('academic_year', ':message') : '' }}
                           </p>
                         </div>
                       </div>
 
                       <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_month') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Months <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::select('academic_month', $data['months'],  isset($academic_month) ? $academic_month :'', ['class' => 'academic_month form-control', 'required']) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_month') ? $errors->first('academic_month', ':message') : '' }}
                           </p>
                         </div>
                       </div>
                       <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_month') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Full Year Pay <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::checkbox('full_pay', null,  '0', ['class' => 'iCheck-helper']) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_month') ? $errors->first('academic_month', ':message') : '' }}
                           </p>
                         </div>
                       </div>
                     <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_month') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Type <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::select('admission_type', array('0' => 'New Admission', '1' => 'Re-admission'),  isset($admission_type) ? $admission_type :'', ['class' => 'form-control', 'readonly']) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('admission_type') ? $errors->first('admission_type', ':message') : '' }}
                           </p>
                         </div>
                       </div>
 
 
                 </div>
             {{-- Cut awat Submit button and form end --}}
             <div class="alert alert-warning">Alert ! Some Previous Payment Exists. Please Update the Informations</div>
             <span class="text-danger"> {!! $mandatory !!} marked categories are mandatory for fees payment. A student can't escaleted to next session with out paying the mandatory fees payments.</span>
         </div>
 
 
    
           <!-- /.row -->
         </div>
       <!-- /.box -->
 
       <div class="box box-default">
         <div class="box-header with-border">
           <h3 class="box-title">Please Provide Below Fees to Process next</h3>
           <div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
             <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
           </div>
         </div>
         <!-- /.box-header -->
         <div class="box-body">
         <div id="admission-list">
             <table class="table table-responsive table-bordered">
                 <thead>
                   <tr>
                     <th>#</th>
                     <th>{{ Form::checkbox('check_all', 'check_all', '', array('id' => 'ckbCheckAll')) }}</th>
                     <th>Category</th>
                     <th>Sub-category</th>
                     <th>Class</th>
                     <th>Price</th>
                     <th>Remark</th>
                     <th>Date of Payment</th>
                   </tr>
                 </thead>
                 <tbody>                  
                   @foreach($data['getPayments'] as $key => $paymnet)
                     @if(in_array($paymnet['id'], $fees_master_id))
                       @php $checked = 1 @endphp
                     @else
                       @php $checked = 0 @endphp
                     @endif
                   <tr>
                     <td>{{ $counter }}</td>
                     <td>{{ Form::checkbox('payment[]', $paymnet['id'], $checked, array('id' => $paymnet['id'],'class' => 'checkBoxClass payment_type_chkbx paymnet_id_'.$paymnet['id'], 'onchange' => 'setDateForPaymentType(this.value);')) }}</td>
                     <td>{{ Helper::getCategoryName($paymnet['category_id']) }}</td>
                     <td>{{ Helper::getSubCategoryName($paymnet['subcategory_id']) }} @if($paymnet['is_mandatory'] == '1') 
                         {!! $mandatory !!}  @endif 
                     </td>
                     <td>{{ $paymnet['class'] }}</td>
                     <td>{{ $paymnet['amount'] }}</td>
                     <td>{{ $paymnet['remark'] }}</td>
                     <td>{{Form::text('fees_paymet_date[]', isset($fees_paymet_date) ? $fees_paymet_date : '', ['class' => 'form-control payment_datepicker', 'id' => 'datepicker_'.$paymnet['id']] )}}</td>
                   </tr>
                   @php $counter ++; @endphp
                   @endforeach
                 </tbody>
             </table>
 
             <center>
               <div class="box-footer">
                 {{ Form::submit($data['submitBtnName'], array('name' => 'form-fees', 'class' => 'btn btn-success')) }}
                 {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
               </div>
             </center>
 
           </div>
         </div>
       </div>
 
           {{ Form::close() }}
     </section>
     <!-- /.content -->
     @endsection
 
     @section('extra-javascript')
     <script type="text/javascript">
       $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
     </script>
     <script type="text/javascript">
       function setDateForPaymentType(paymentId){
         /*console.log($('#paymnet_id_'+paymentId).is(":checked"));
         if ($('#paymnet_id_'+paymentId).is(":checked")) {
           console.log('checked');
         }
         else{
           console.log('not checked');
         }*/
         //if(0)
       }
     </script>
       <script type = "text/javascript" language = "javascript">
          $(document).ready(function() {
             $(".payment_type_chkbx").change(function(){
               var paymentId = $(this).attr('id');
               var currDate = $.datepicker.formatDate('yy-mm-dd', new Date());
 
               if($(this).prop("checked") == true){
                   //alert(paymentId+" Checkbox is checked."+currDate);
                   $("#datepicker_"+paymentId).val(currDate);
 
               }
               else if($(this).prop("checked") == false){
                   //alert(paymentId+" Checkbox is unchecked.");
                   $("#datepicker_"+paymentId).val('');
               }
             });
 
 
             $(".academic_month").change(function(event){
               $('.overlay').show();
               $('.checkBoxClass').prop('checked', false);
               $(".payment_datepicker").val('');
                var academic_month = $(this).val();
                var sid = $('.sid').val();
                var s_class = $('.s_class').val();
                var academic_year = $('.academic_year :selected').val();
                $.ajax({
                 type: "POST",
                 url: "{{url('/')}}/ajax-get-paid-details",
                 data: { academic_month: academic_month, 
                         academic_year:academic_year,
                         "_token": "{{ csrf_token() }}",
                         s_class : s_class,
                         sid : sid
                       },
                 dataType : 'html',
                 cache: false,
                 success: function(data){
                   var jsonPayment = $.parseJSON(data);
 
                   $.each(jsonPayment.json_payment, function(index, element) {
                       $.each(element, function(k, v) {
                         console.log("k="+k+" and v ="+v+ "//");
                         var payment_id = '';
                         $(".paymnet_id_"+k).prop('checked', true);
                         $("#datepicker_"+k).val(v);
 
                       });
                       console.log("-----------------------------");
                   });
                   $('.overlay').hide();
                 }
               });
             });
 
             $("#ckbCheckAll").click(function () {
                 $(".checkBoxClass").prop('checked', $(this).prop('checked'));
             });
 
             /*
               Set Datepickers Recursively for Date Fields against the Payment Types
             */
             @for($i=1; $i<=count($data['getPayments']);$i++)
               $('#datepicker_{{$i}}').datepicker({
                 autoclose: true,
                 format: 'yyyy-mm-dd'
               });
             @endfor
 
 
          });
       </script>
     @endsection
 