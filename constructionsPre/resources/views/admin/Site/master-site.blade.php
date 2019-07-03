@extends('layouts.default')

@section('content')
<section class="content">
        {{ Form::open(['url' => 'admin/site-master-save','files' => 'true' ,'enctype' => 'multipart/form-data']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
             
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the Site-master details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                
                <div class="form-group col-sm-4">
                  <label for="exampleInputPassword1"> Location</label>
                  {{Form::text('location', isset($location)?$location: '', ['class' => 'form-control', 'placeholder' => 'Enter location']  )}}
                  <p class="help-block">
                    {{ $errors->has('location') ? $errors->first('location', ':message') : '' }}
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
                  <label for="exampleInputPassword1">Buildup Area</label>
                  {{Form::number('build_up_area', isset($build_up_area)?$build_up_area: '', ['class' => 'form-control', 'placeholder' => 'Enter build_up_area']  )}}
                  <p class="help-block">
                    {{ $errors->has('build_up_area') ? $errors->first('build_up_area', ':message') : '' }}
                  </p>
                </div>
               <div class="form-group  col-sm-4">
                <label for="exampleInputPassword1">Upload photo</label>
                  
                  {{Form::file('avatar', ['class' => 'form-control'] )}}
                  <p class="help-block">
                    {{ $errors->has('avatar') ? $errors->first('avatar', ':message') : '' }}
                  </p>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Site-master</button>
          </div>
              
            
          </div>
          <!-- /.box -->

 

        </div>

        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Site Details</h3>
            </div>
            <!-- /.box-header -->
            
              
              <div class="box-body">
                  <table id="addmoretable" class="table table-bordered">
                <thead>
                    <tr>
                         <th style="width: 10px">#</th>
                        <th>Location</th>
                        <th>Address</th>
                        <th>Build Up Area</th>
                        <th>Photo</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody id="addedRows">
                   <?php 
                        //dd($servicesList);
                     ?>
                @foreach($siteList as $key => $sites)
               
                    <tr id="1" class="row_1">
                        <td>{{$key+1}}</td>
                          <td>
                          {{$sites->location}}
                          </td>
                          <td>{{$sites->address}}</td>
                          <td>{{$sites->build_up_area}}</td>
                          <td>{{$sites->avatar}}</td>
                          <td><a href="{{url('/admin/customer/add')}}/{{$sites['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/customer/trash')}}/{{$sites['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a></td>
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
