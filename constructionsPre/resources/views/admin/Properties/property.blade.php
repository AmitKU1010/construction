@extends('layouts.default')
@section('content')
<section class="content">
   {{ Form::open(['url' => 'admin/properties/save', 'files' => true]) }} 
   {{ csrf_field() }}
   {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
   <div class="row">
      <!-- left column -->
      <div class="col-md-12">
         <!-- general form elements -->
         <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Please fill up the Properties details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
               @if(session()->has('message.level'))
               <div class="alert alert-{{ session('message.level') }} alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
                  {!! session('message.content') !!}
               </div>
               @endif
               @if ($errors->any())
               <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <ul>
                     <li>Warning ! Please resolve following errors.</li>
                  </ul>
               </div>
               @endif
               <div class="row">
                  <div class="col-md-6">
                     <div class="col-md-12">
                        <div class="form-group col-sm-6 col-md-6">
                           <label for="exampleInputPassword1">Project Name</label>
                           {{Form::text('project_name', isset($project_name)?$project_name: '', ['class' => 'form-control','required', 'placeholder' => 'Enter a Project Name']  )}}
                           <p class="help-block">
                              {{ $errors->has('project_name') ? $errors->first('project_name', ':message') : '' }}
                           </p>
                        </div>
                        <div class="form-group col-sm-6 col-md-6">
                           <label for="exampleInputPassword1">Choose Parent Project</label>
                           {{Form::select('property_reff_id', $propertylist, isset($property_reff_id)?$property_reff_id: '', ['class' => 'form-control select2', 'placeholder' => 'Enter a Project Name']  )}}
                           <p class="help-block">
                              {{ $errors->has('property_reff_id') ? $errors->first('property_reff_id', ':message') : '' }}
                           </p>
                        </div>
                     </div>
                     <div class="form-group col-sm-6 col-md-12">
                        <label for="exampleInputPassword1">Project Description</label>
                        {{Form::textarea('project_desc', isset($project_desc)?$project_desc: '', ['rows' => '3', 'class' => 'form-control', 'placeholder' => 'Enter a Project Description']  )}}
                        <p class="help-block">
                           {{ $errors->has('project_desc') ? $errors->first('project_desc', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-12">
                        <label for="exampleInputPassword1">Project Address</label>
                        {{Form::textarea('address', isset($address)?$address: '', ['rows' => '3', 'class' => 'form-control','required', 'placeholder' => 'Enter Project Address']  )}}
                        <p class="help-block">
                           {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-12">
                        <label for="exampleInputPassword1">Upload Property Image</label>
                        {{Form::file('property_image_raw[]', ['class' => 'form-control', 'multiple'] )}}
                        <p class="help-block">
                           {{ $errors->has('property_image_raw') ? $errors->first('property_image_raw', ':message') : '' }}
                        </p>
                     </div>
                    <!--  <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Block No</label>
                        {{Form::text('block_name',isset($block_name)?$block_name: '', ['class' => 'form-control block_name', 'placeholder' => 'e.g: A or B or C']  )}}
                        <p class="help-block">
                           {{ $errors->has('block_name') ? $errors->first('block_name', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Floor No</label>
                        {{Form::text('floor_no',isset($floor_no)?$floor_no: '', ['class' => 'form-control floor_no', 'placeholder' => 'e.g: 1 or 2']  )}}
                        <p class="help-block">
                           {{ $errors->has('floor_no') ? $errors->first('floor_no', ':message') : '' }}
                        </p>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Flat No.</label>
                        {{Form::text('flat_no', isset($flat_no)?$flat_no: '', ['class' => 'form-control flat_no', 'placeholder' => 'e.g. 101 or 309']  )}}
                        <p class="help-block">
                           {{ $errors->has('flat_no') ? $errors->first('flat_no', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Flat Type</label>
                        {{Form::select('flat_type', $flattypelist,isset($block_name)?$block_name: '', ['class' => 'form-control flat_type', 'placeholder' => '-- Select Flat Type']  )}}
                        <p class="help-block">
                           {{ $errors->has('flat_type') ? $errors->first('flat_type', ':message') : '' }}
                        </p>
                     </div> -->
                     <div class="clearfix"></div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Landmark</label>
                        {{Form::text('landmark', isset($landmark)?$landmark: '', ['class' => 'form-control','id'=>'landmark', 'placeholder' => 'Landmark']  )}}
                        <p class="help-block">
                           {{ $errors->has('country_id') ? $errors->first('country_id', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Choose City</label>
                        {{Form::select('city_id', $cities, isset($city_id)?$city_id: '', ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id'=>'city_id', 'placeholder' => '-- Select city']  )}}
                        <p class="help-block">
                           {{ $errors->has('city_id') ? $errors->first('city_id', ':message') : '' }}
                        </p>
                     </div>
                     <div class="clearfix"></div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Total Price</label>
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                           {{Form::number('total_price', isset($total_price)?$total_price: '', ['class' => 'form-control', 'placeholder' => 'Enter Total Price']  )}}
                        </div>
                        <p class="help-block">
                           {{ $errors->has('total_price') ? $errors->first('total_price', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="down_payment">Down Payment</label>
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                           {{Form::number('down_payment', isset($down_payment)?$down_payment: '', ['class' => 'form-control', 'placeholder' => 'Enter Down Payment']  )}}
                        </div>
                        <p class="help-block">
                           {{ $errors->has('down_payment') ? $errors->first('down_payment', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Enter Supper Buildup Area</label>
                        {{Form::text('super_build_up_area', isset($super_build_up_area)?$super_build_up_area: '', ['class' => 'form-control', 'placeholder' => 'Enter Supper Buildup Area']  )}}
                        <p class="help-block">
                           {{ $errors->has('super_build_up_area') ? $errors->first('super_build_up_area', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-6">
                        <label for="exampleInputPassword1">Enter Buildup Area</label>
                        {{Form::text('build_up_area', isset($build_up_area)?$build_up_area: '', ['class' => 'form-control', 'placeholder' => 'Enter Buildup Area']  )}}
                        <p class="help-block">
                           {{ $errors->has('build_up_area') ? $errors->first('build_up_area', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-12">
                        <label for="exampleInputPassword1">Property Type</label>
                        {{Form::select('property_type', $property_types, isset($property_type)?$property_type: '', ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id'=>'property_type', 'placeholder' => '-- Select Property Type --']  )}}
                        <p class="help-block">
                           {{ $errors->has('city_id') ? $errors->first('city_id', ':message') : '' }}
                        </p>
                     </div>
                     <div class="form-group col-sm-6 col-md-12">
                        <label for="exampleInputPassword1">Facilities</label>
                        {{Form::select('facilities[]', $facilities, isset($facilities_id)?$facilities_id: '', ['class' => 'form-control select2', 'style' => 'width: 100%;', 'id'=>'property_type', 'data-placeholder' => 'Please select facility', 'multiple']  )}}
                        <p class="help-block">
                           {{ $errors->has('city_id') ? $errors->first('city_id', ':message') : '' }}
                        </p>
                     </div>
                  </div>
               </div>


               <div class="row">
                  <div class="col-md-12">
                     <h3>Add Room
                     </h3>
                     <table id="addmoretable" class="table table-bordered">
                         <thead id="addRoomHead">
                           <tr>
                              <th>Block No</th>
                              <th>Floor No</th>
                              <th>Flat No</th>
                              <th>Flat Type</th>
                              <th>Sq Ft</th>
                              <th>Rate</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody id="addRoom">


                           @if(isset($PropertiesRoom)  && count($PropertiesRoom) > 0)
                           @for($i=0; $i<count($PropertiesRoom) ; $i++)

                           <tr id="row<?php $j=$i; echo $j+1 ?>" ">  
                              <input type="hidden" id="totalRoomCount" value="<?php echo count($PropertiesRoom) ?>"  />
                               <td><input type="hidden"  name="block_name[]" id="block_name2" value="<?php echo $PropertiesRoom[$i]->block_name ?>"  ><?php echo $PropertiesRoom[$i]->block_name ?></td>
                               <td><input type="hidden"  name="floor_number[]" value="<?php echo $PropertiesRoom[$i]->floor_number ?>">
                                 <?php echo $PropertiesRoom[$i]->floor_number ?>
                               </td>
                               <td><input type="hidden"  name="flate_number[]"   value="<?php echo $PropertiesRoom[$i]->flate_number ?>">
                                 <?php echo $PropertiesRoom[$i]->flate_number ?>
                               </td>
                               <td>
                                 <input type="hidden"  name="flate_type[]"  value="<?php echo $PropertiesRoom[$i]->flate_type ?>" >
                                 <?php echo $PropertiesRoom[$i]->flate_type ?>
                              </td>
                              <td><input type="hidden" value="<?php echo $PropertiesRoom[$i]->sq_ft ?>"  name="sq_ft[]" id="sqFt2">
                              <?php echo $PropertiesRoom[$i]->sq_ft ?></td>
                              <td><input type="hidden" value="<?php echo $PropertiesRoom[$i]->rate ?>"  name="rate[]" id="rate2">
                              <?php echo $PropertiesRoom[$i]->rate ?></td>
                              <td> <a id="<?php $k=$i; echo $k+1 ?>" href="javascript:void(0);" class="removeRoom"><i class="glyphicon glyphicon-trash text-danger"></i></a>                          </td>  </tr>
                              

                           
                           @endfor
                           @endif

                           <tr>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td></td>
                           </tr>
                         
                        </tbody>
                        <tfoot>
                          <!-- <tr> <a href="javascript:void(0)" class="addmore_xx btn btn-info btn-sm" onclick="addMoreRows1(this.form)"><i class="glyphicon glyphicon-plus-sign "></i> Add</a> -->
                           <tr><td>
                              <a href="javascript:void(0)"  id="addAnotherRoom"  class="addmore_xx btn btn-info btn-sm" ><i class="glyphicon glyphicon-plus-sign "></i> Add</a>
                             </td></tr>
                        </tfoot>
                        
                     </table>

                  </div>
               </div>


              <!--  <div class="row">
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
               </div>
 -->

               

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
               <center>
                  <button type="submit" id="AddUpdateProperty" class="btn btn-primary">{{isset($id) ? 'Update':'Add'}} Property</button>
               </center>
            </div>
         </div>
         <!-- /.box -->
      </div>
      <!--/.col (left) -->
      <!-- /.box -->
   </div>
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
   $(document).ready(function(){
    $(".new_project").show();
    $(".existing_project").hide();
       $(".new_existing").click(function(){
           var current_selection = $(this).text();
           console.log(current_selection);
           if(current_selection == 'New'){
               $(".new_existing").text('Existing');
               $(".new_project").show();
               $(".existing_project").hide();
           }else if(current_selection == 'Existing'){
               $(".new_existing").text('New');
               $(".new_project").hide();
               $(".existing_project").show();
           }
       })
   })
</script>
<script type = "text/javascript" language = "javascript">
   function removeInstallment(instId){
       $.ajax({
         type: "POST",
         url: "{{url('/')}}/admin/properties/ajax/remove/installment",
         data: { 
                 "_token": "{{ csrf_token() }}",
                 instId : instId,
               },
         dataType : 'html',
         cache: false,
         success: function(data){
           if(data == 'Y'){
               $("table tr.delete_"+instId).remove();
           }
           $('.overlay').hide();
           
         }
         
     });
   }
   $(document).ready(function() {
   $('.overlay').hide();
     $("#country_id").change(function(event){
       $('.overlay').show();
        var country_id = $(this).val();
        //get-book-list-by-class-id
        $.ajax({
         type: "POST",
         url: "{{url('/')}}/admin/statelistaccordingtocountry",
         data: { 
                 "_token": "{{ csrf_token() }}",
                country_id : country_id,
               },
         dataType : 'html',
         cache: false,
         success: function(data){
           states = $.parseJSON(data);
           $('#state_id')
                   .empty()
                   .append('<option selected="selected" value="">-Select State -</option>');
           $.each(states, function(i, item) {
               $('#state_id').append(
                     '<option value="'+i+'">'+item+'</option>'
                );
           });
           $('.overlay').hide();
   
         }
         
     });
   });
   $("#state_id").change(function(event){
       $('.overlay').show();
        var state_id = $(this).val();
        //get-book-list-by-class-id
        $.ajax({
         type: "POST",
         url: "{{url('/')}}/admin/citylistaccordingtocountry",
         data: { 
                 "_token": "{{ csrf_token() }}",
                state_id : state_id,
               },
         dataType : 'html',
         cache: false,
         success: function(data){
           cities = $.parseJSON(data);
           $('#city_id')
                   .empty()
                   .append('<option selected="selected" value="">-Select City -</option>');
           $.each(cities, function(i, item) {
               $('#city_id').append(
                     '<option value="'+i+'">'+item+'</option>'
                );
           });
           $('.overlay').hide();
   
         }
         
     });
   });
   });
</script>
<script type="text/javascript">

var checkregistered= "<?php 
 if(isset($PropertiesRoom)  && count($PropertiesRoom) > 0)
 {
      for($i=0; $i<count($PropertiesRoom) ; $i++)
      {
         if($PropertiesRoom[$i]->property_registered!=NULL)
         {
            echo $PropertiesRoom[$i]->property_registered;
         }
      }
   }                  
    ?> ";
   if(checkregistered.trim()!="")
   {
      $('#AddUpdateProperty').replaceWith( "<p>This property cannot be update  because of already sold </p>" );
   }

 $(document).ready(function(){  
                  
                 
                    var i= $('#totalRoomCount').val();
                     if(isNaN(i)){
                        i=0;
                      }
                     $('#addAnotherRoom').on("click",function(){             
                        $('#addRoomHead').show();
                        i++;  
                        var addRow='<tr id= "row'+i+'" class="row_'+i+'">\
                           <td ><input required type="text" class="roomClass form-control" name="block_name[]"  placeholder="e.g: A or B or C" id="block_name'+i+'"/></td><td ><input required type="number" class="roomClass form-control" name="floor_number[]" placeholder="e.g: 1 or 2" id= "floor_no'+i+'"/></td><td ><input required type="number"  placeholder="e.g. 101 or 309" class="roomClass form-control" name="flate_number[]"  id= "flate_number'+i+'"/></td>\
                           \
                           <td ><input required type="text" class="roomClass form-control" name="flate_type[]" placeholder="Flate Type"  id= "flate_type'+i+'"/>\
                           </td><td ><input required type="number" class="roomClass form-control" name="sq_ft[]" placeholder="Enter Sq Ft"  id= "sqFt'+i+'"/></td><td ><input required type="number" class="roomClass form-control" placeholder="Enter Rate" name="rate[]"  id= "rate'+i+'"/></td><td > <a id="'+i+'" href="javascript:void(0);" class="removeRoom" "><i class="glyphicon glyphicon-trash text-danger"></i></a>\
                          </td>\
                         </tr>';

                        $('#addRoom').append(addRow);  
                    });    
                     if($('.removeRoom').length==0) 
                     {
                         $('#addRoomHead').hide();
                     }
                      $(document).on('click', '.removeRoom', function(){  
                        var button_id = $(this).attr("id");  
                        $('#addRoomHead').show();
                        // if($('.removeRoom').length==1) 
                        // {<select required class="form-control roomClass" name="flate_type[]"  id= "flate_type'+i+'">\
                           //  <option value="">-- Select Flat Type</option>\
                           //    <option value="1BHK">1BHK</option>\
                           //     <option value="2BHK">2BHK</option>\
                           // </select>\
                            
                        // }
                        // alert(button_id);
                        if($('.removeRoom').length==1)
                        {
                           alert("Please Add Atleast one Room");
                          // $('#addRoomHead').hide();
                        }
                        else
                        {
                           $('#row'+button_id+'').remove();  
                        }
                        
                    }); 
                      if($('.removeRoom').length==0)
                      {
                         $('#addAnotherRoom').click();
                      }
                      
                  


   
    } );               




////   {{Form::text('block_name[]',isset($block_name)?$block_name: '', ['class' => 'form-control roomClass', 'placeholder' => 'e.g: A or B or C']  )}}
   var rowCount = 1;
   function addMoreRows(frm) {  
                          
     rowCount ++;
      var recRow = '<tr id="'+rowCount+'" class="row_'+rowCount+'"><td>{{Form::text('block_name[]',isset($block_name)?$block_name: '', ['class' => 'form-control block_name', 'placeholder' => 'e.g: A or B or C']  )}}</td><td class="floor_no">{{Form::text('floor_no[]',isset($floor_no)?$floor_no: '', ['class' => 'form-control floor_no', 'placeholder' => 'e.g: 1 or 2']  )}}</td><td class="flat_no">{{Form::text('flat_no[]', isset($flat_no)?$flat_no: '', ['class' => 'form-control flat_no', 'placeholder' => 'e.g. 101 or 309']  )}}</td><td class="flat_type">{{Form::select('flat_type[]', $flattypelist,isset($block_name)?$block_name: '', ['class' => 'form-control flat_type', 'placeholder' => '-- Select Flat Type']  )}}</td><td class="flat_type">{{Form::textarea('remarks[]' , isset($remarks)?$remarks: '', ['class' => 'form-control flat_type', 'rows' => '3']  )}}</td><td><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="glyphicon glyphicon-trash text-danger"></i></a></td></tr>';
   
     jQuery('#addedRows').append(recRow);
   
       
       $('.service_type_id').change(function(){
           var currRow = $(this).parent().parent().attr('id');
           var servcie_type_id = $(this).val();
           
           $.ajax({
               type: "POST",
               url: "{{url('/')}}/admin/getservcielistbytypeid",
               data: { 
                       "_token": "{{ csrf_token() }}",
                       servicetpid : servcie_type_id,
                       },
               dataType : 'html',
               cache: false,
               success: function(data){
                   console.log(data);
                   services = $.parseJSON(data);
                   $('.row_'+currRow+' .services')
                           .empty()
                           .append('<option selected="selected" value="">-Select -</option>');
                   $.each(services, function(i, item) {
                       $('.row_'+currRow+' .services').append(
                           '<option value="'+i+'">'+item+'</option>'
                       );
                   });
                   $('.overlay').hide();
               }
           });
   
       })
   }
   function addMoreRows1(frm) {  
                          
     rowCount ++;
     
       var recRow = '<tr id="'+rowCount+'" class="row_'+rowCount+'"><td>{{Form::text('installment_no[]', isset($installment_no)?$installment_no: '', ['class' => 'form-control', 'placeholder' => 'Enter No of Installments']  )}}</td><td class="installment_price">{{Form::text('installment_price[]', isset($installment_price)?$installment_price: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Price'] )}}</td></td><td class="installment_desc">{{Form::text('installment_desc[]', isset($installment_desc)?$installment_desc: '', ['class' => 'form-control', 'placeholder' => 'Enter Installment Description']  )}}</td><td><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="glyphicon glyphicon-trash text-danger"></i></a></td></tr>'
   
     jQuery('#addedRows1').append(recRow);
   
       
       $('.service_type_id').change(function(){
           var currRow = $(this).parent().parent().attr('id');
           var servcie_type_id = $(this).val();
           
           $.ajax({
               type: "POST",
               url: "{{url('/')}}/admin/getservcielistbytypeid",
               data: { 
                       "_token": "{{ csrf_token() }}",
                       servicetpid : servcie_type_id,
                       },
               dataType : 'html',
               cache: false,
               success: function(data){
                   console.log(data);
                   services = $.parseJSON(data);
                   $('.row_'+currRow+' .services')
                           .empty()
                           .append('<option selected="selected" value="">-Select -</option>');
                   $.each(services, function(i, item) {
                       $('.row_'+currRow+' .services').append(
                           '<option value="'+i+'">'+item+'</option>'
                       );
                   });
                   $('.overlay').hide();
               }
           });
   
       })
   }
    
   function removeRow(removeNum) {
     var didConfirm = confirm("Are you sure You want to delete");
       if (didConfirm == true) {
       jQuery('.row_'+removeNum).remove();
     }
   }
</script>
@endsection
