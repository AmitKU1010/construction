@extends('layouts.default')

@section('content')
<section class="content">
        {{ Form::open(['url' => 'admin/firm/save','files' => 'true' ,'enctype' => 'multipart/form-data']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
             
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the Firm details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                
                <div class="form-group col-sm-4">
                  <label for="exampleInputPassword1">Firm Name</label>
                  {{Form::text('firm_name', isset($firm_name)?$firm_name: '', ['class' => 'form-control', 'placeholder' => 'Enter a FIrm name']  )}}
                  <p class="help-block">
                    {{ $errors->has('firm_name') ? $errors->first('firm_name', ':message') : '' }}
                  </p>
                </div>
               <div class="form-group  col-sm-4">
                  <label for="exampleInputPassword1">Address</label>
                  {{Form::text('address', isset($address)?$address: '', ['class' => 'form-control', 'placeholder' => 'Enter  address']  )}}
                  <p class="help-block">
                    {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                  </p>
                </div>
                
                <div class="form-group  col-sm-4">
                  <label for="exampleInputPassword1">Contact no</label>
                  {{Form::text('contact', isset($contact)?$contact: '', ['class' => 'form-control', 'placeholder' => 'Enter a customer contact no']  )}}
                  <p class="help-block">
                    {{ $errors->has('contact') ? $errors->first('contact', ':message') : '' }}
                  </p>
                </div>
                
               
                 
                <div class="form-group  col-sm-4">
                  <label for="exampleInputPassword1">Branch Name</label>
                  {{Form::text('branch', isset($branch)?$branch: '', ['class' => 'form-control', 'placeholder' => 'Enter  branch name']  )}}
                  <p class="help-block">
                    {{ $errors->has('branch') ? $errors->first('branch', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group col-sm-4">
                  <label for="exampleInputPassword1">Account No</label>
                  {{Form::text('account_no', isset($account_no)?$account_no: '', ['class' => 'form-control', 'placeholder' => 'Enter  account no']  )}}
                  <p class="help-block">
                    {{ $errors->has('account_no') ? $errors->first('account_no', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group  col-sm-4">
                 <label for="exampleInputPassword1">IFSC Code</label>
                  {{Form::text('ifsc', isset($ifsc)?$ifsc: '', ['class' => 'form-control', 'placeholder' => 'Enter  ifsc code']  )}}
                  <p class="help-block">
                    {{ $errors->has('ifsc') ? $errors->first('ifsc', ':message') : '' }}
                  </p>
                </div>

                <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">GSTIN No</label>
                  {{Form::text('gstin', isset($gstin)?$gstin: '', ['class' => 'form-control', 'placeholder' => 'Enter a customer gstin no']  )}}
                  <p class="help-block">
                    {{ $errors->has('gstin') ? $errors->first('gstin', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">PAN No</label>
                  {{Form::text('pan', isset($pan)?$pan: '', ['class' => 'form-control', 'placeholder' => 'Enter a customer pan no']  )}}
                  <p class="help-block">
                    {{ $errors->has('pan') ? $errors->first('pan', ':message') : '' }}
                  </p>
                </div>
                
                <!-- <div class="form-group  col-sm-4">
                <label for="exampleInputPassword1">Upload Logo</label>
                  
                  {{Form::file('logo_image_raw', ['class' => 'form-control'] )}}
                  <p class="help-block">
                    {{ $errors->has('logo_image_raw') ? $errors->first('logo_image_raw', ':message') : '' }}
                  </p>
                </div> -->
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Form</button>
          </div>
              
            
          </div>
          <!-- /.box -->

 

        </div>

         
        </div>

        <!--/.col (right) -->
      </div>
      {{Form::close()}}
      <!-- /.row -->
    </section>
@endsection    
