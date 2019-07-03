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
  </style>
 @php $mandatory = '<strong class="text-danger" style="font-size:20px">*</strong>'; @endphp  
 </style>
       <div class="box box-default">
         <div class="box-header with-border">
           <h3 class="box-title">{{$pageTitle}}</h3>
           <div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
             <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
           </div>
         </div>
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
        @php $disable = ''; $disableMessage = '';@endphp
        @if(isset($feesClearStatus) && $feesClearStatus !='' && $feesClearStatus > 0) 
           @php 
            $disable = 'disabled'; 
            $disableMessage = 'Sorry! All fees are not paid till now. So the student can not be processed Further. Please clear all unpaid Fees.';
           @endphp
        @endif

        {{ Form::open(['url' => url('/').'/readmission-save', 'form' => '1']) }} 
             {{ csrf_field() }}
             {{ Form::hidden('id', isset($id) ? $id :'', []) }}
             {{ Form::hidden('sid', isset($studentDetails->id) ? $studentDetails->id :'', ['class' => 'sid']) }}
             {{ Form::hidden('cc', isset($studentDetails->admission_class) ? $studentDetails->admission_class :'', ['class'=>'current_class']) }}
             {{ Form::hidden('ptc', isset($promotedToClass) ? $promotedToClass :'', ['class'=>'promoted_to_class']) }}
             {{ Form::hidden('acyr', isset($newAcademicYear) ? $newAcademicYear :'', ['class'=>'acyr']) }}
             

                 <div class="row">
                     <div class="col-md-4">
                       <div class="info-box bg-aqua">
                         <span class="info-box-icon"><i class="fa  fa-child"></i></span>
                         <div class="info-box-content">
                           <span class="info-box-text">{{ $studentDetails->name }}</span>
                           <span class="progress-description">Father <span class="pull-right badge bg-blue">{{$studentDetails->father_name}}</span></span>
                           <span class="progress-description">Mother <span class="pull-right badge bg-blue">{{$studentDetails->mother_name}}</span></span>
                           <span class="progress-description">Studying in Class {{$studentDetails->admission_class}}</span>
                         </div>
                         <!-- /.info-box-content -->
                       </div>
                     </div>
                       <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_year') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Re-Admission for <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::select('academic_year', array('' => '--Academic Year--')+$nextAcademicYear , isset($academic_year) ? $academic_year :'', ['class' => 'form-control academic_year input-md', 'required', $disable]) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_year') ? $errors->first('academic_year', ':message') : '' }}
                           </p>
                         </div>
                       </div>
 
                       <div class="col-sm-2">
                         <div class="form-group {{ $errors->has('academic_month') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Admission/Transfer <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::select('admission_type', [''=> '--Select Type--', 'READMISSION' => 'Re-admission', 'TRANSFER' => 'Transfer'],  isset($admission_type) ? $admission_type :'', ['class' => 'admission_type form-control', 'required', $disable]) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_month') ? $errors->first('academic_month', ':message') : '' }}
                           </p>
                         </div>
                       </div>
                       <div class="col-sm-4">
                         <div class="form-group {{ $errors->has('academic_month') ? 'has-error' : ''}}">
                                 <label for="exampleInputFile">Remarks <span class="text-danger"> *</span>
                           </label>
                           <div class="input text">
                             {{ Form::textarea('full_pay', isset($full_pay) ? $full_pay : '', ['class' => 'form-control', 'rows' => '3', $disable]) }}
                           </div>
                           <p class="help-block">
                             {{ $errors->has('academic_month') ? $errors->first('academic_month', ':message') : '' }}
                           </p>
                         </div>
                       </div>
 
 
                 </div>
             {{-- Cut awat Submit button and form end --}}
            @if(isset($disableMessage) && $disableMessage !='')
                {!! '<div class="alert alert-danger">'.$disableMessage.'</div>' !!}
            @endif
         </div>
 
 
    
           <!-- /.row -->
         </div>
       <!-- /.box -->
 
       <div class="box box-default">
         <div class="box-header with-border">
           <h3 class="box-title">Admission Date of the Student is : {{$admission_date}}</h3>
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
                     <th>Academic Year</th>
                     <th>Academic Month</th>
                     <th>Status</th>
                   </tr>
                 </thead>
                 <tbody>                  
                @foreach($feesStatus as $key => $data)
                    <tr class="{{$data['status']}}">
                        <td>{{$key+1}}</td>
                        <td>{{$data['year']}}</td>
                        <td>{{$data['month']}}</td>
                        <td>{{$data['message']}}</td>
                    </tr>
                @endforeach
                  
                 </tbody>
             </table>
 
             <center>
               <div class="box-footer">
                 {{ Form::submit($submitBtnName, array('name' => 'form-fees', 'class' => 'btn btn-success')) }}
                 {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
                  <a href="{{url('/')}}/payment/printout/{{$studentDetails->id}}" target="_blank" class="btn btn-info">Invoice</a>
               </div>
             </center>
 
           </div>
         </div>
       </div>
 
      {{ Form::close() }}
     </section>
     /* /.content */
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
             @for($i=1; $i<=count($getPayments);$i++)
               $('#datepicker_{{$i}}').datepicker({
                 autoclose: true,
                 format: 'yyyy-mm-dd'
               });
             @endfor
 
 
          });
       </script>
     @endsection
 