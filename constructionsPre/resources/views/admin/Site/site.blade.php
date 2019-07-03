@extends('layouts.default')

@section('content')
<section class="content">
        {{ Form::open(['url' => 'admin/construction_site/save','files' => 'true' ,'enctype' => 'multipart/form-data']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
             
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the Firm details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                
                <div class="form-group col-sm-6">
                  <label for="exampleInputPassword1">Site Name</label>
                  {{Form::text('site_name', isset($site_name)?$site_name: '', ['class' => 'form-control', 'placeholder' => 'Enter a Site name']  )}}
                  <p class="help-block">
                    {{ $errors->has('site_name') ? $errors->first('site_name', ':message') : '' }}
                  </p>
                </div>
               <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Project Name</label>
                  {{Form::text('project_name', isset($project_name)?$project_name: '', ['class' => 'form-control', 'placeholder' => 'Enter Project Name']  )}}
                  <p class="help-block">
                    {{ $errors->has('project_name') ? $errors->first('project_name', ':message') : '' }}
                  </p>
                </div>
                
                <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Block Name</label>
                  {{Form::text('block_name', isset($block_name)?$block_name: '', ['class' => 'form-control', 'placeholder' => 'Enter Block Name']  )}}
                  <p class="help-block">
                    {{ $errors->has('block_name') ? $errors->first('block_name', ':message') : '' }}
                  </p>
                </div>
                
               
                <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Floor No</label>
                  {{Form::text('floor_no', isset($floor_no)?$floor_no: '', ['class' => 'form-control', 'placeholder' => 'Enter  floor no']  )}}
                  <p class="help-block">
                    {{ $errors->has('floor_no') ? $errors->first('floor_no', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Variation</label>
                   {{Form::select('variation',[ '1bhk' => '1 BHK','2bhk' => '2 BHK'], null , ['class' => 'form-control', 'placeholder' => 'Choose Variation'] )}}
                  <p class="help-block">
                    {{ $errors->has('variation') ? $errors->first('variation', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">description</label>
                  {{Form::text('description', isset($description)?$description: '', ['class' => 'form-control', 'placeholder' => 'Enter  description']  )}}
                  <p class="help-block">
                    {{ $errors->has('description') ? $errors->first('description', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Address</label>
                  {{Form::text('address', isset($address)?$address: '', ['class' => 'form-control', 'placeholder' => 'Enter  address']  )}}
                  <p class="help-block">
                    {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group  col-sm-6">
                  <label for="exampleInputPassword1">Advance Payment</label>
                  {{Form::text('advance', isset($advance)?$advance: '', ['class' => 'form-control', 'placeholder' => 'Enter  Advance Amount']  )}}
                  <p class="help-block">
                    {{ $errors->has('advance') ? $errors->first('advance', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group col-sm-6">
                  <label for="exampleInputPassword1">No of Installments</label>
                  {{Form::text('no_of_installments', isset($no_of_installments)?$no_of_installments: '', ['class' => 'form-control', 'placeholder' => 'Enter  No Of Installments']  )}}
                  <p class="help-block">
                    {{ $errors->has('no_of_installments') ? $errors->first('no_of_installments', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group  col-sm-6">
                 <label for="exampleInputPassword1">Installments Details</label>
                  {{Form::text('installment_details', isset($installment_details)?$installment_details: '', ['class' => 'form-control', 'placeholder' => 'Enter  Installments Details']  )}}
                  <p class="help-block">
                    {{ $errors->has('installment_details') ? $errors->first('installment_details', ':message') : '' }}
                  </p>
                </div>
                
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Customer</button>
          </div>
              
            
          </div>
          <!-- /.box -->

 

        </div>

        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add customer Details</h3>
            </div>
            <!-- /.box-header -->
            
              
              <div class="box-body">
                  <table id="addmoretable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Site</th>
                        
                        <th>Block</th>
                          <th>Variation</th>
                          
                            <th>Description</th>
                            <th>Advance</th>
                            <th>Installments</th>
                            <th>action</th>
                    </tr>
                </thead>
                <tbody id="addedRows">
                   <?php 
                        //dd($servicesList);
                     ?>
                @foreach($siteList as $key => $site)
               
                    <tr id="1" class="row_1">
                          <td>{{$key+1}}</td>
                          <td>
                            Name: {{$site->site_name}}</br>
                            proj name: {{$site->project_name}}
                          </td>
                          
                          <td>Block Name: {{$site->block_name}}</br>
                              Floor : {{$site->floor_no}}
                          </td>
                          
                          <td>{{$site->variation}}</td>
                          <td>{{$site->description}}</td>
                          <td>{{$site->advance}}</td>
                          <td>no:{{$site->no_of_installments}}</br>
                              Details:{{$site->installment_details}}
                          </td>
                         
                          <td><a href="{{url('/admin/construction_site/add')}}/{{$site['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/construction_site/trash')}}/{{$site['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
              
              </div>
              <!-- /.box-body -->
          
            
            
            </div>
            
          </div>
         
        </div>

        <!--/.col (right) -->
      </div>
      {{Form::close()}}
      <!-- /.row -->
    </section>
@endsection    
