@extends('layouts.default')

@section('content')
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the service details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(['url' => 'admin/services/save']) }} 
            {{ csrf_field() }}
            {{ Form::hidden('id', isset($id) ? $id :'', []) }}
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Choose Service Type</label>
                  {{Form::select('service_type_id', $servicesListOptn, isset($service_type_id)?$service_type_id: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"> --}}
                  <p class="help-block">
                    {{ $errors->has('service_type_id') ? $errors->first('service_type_id', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword1">Service Name</label>
                  {{Form::text('name', isset($name)?$name: '', ['class' => 'form-control', 'placeholder' => 'Enter a Service Name']  )}}
                  <p class="help-block">
                    {{ $errors->has('name') ? $errors->first('name', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">Price</label>
                  {{Form::text('price', isset($price)?$price: '', ['class' => 'form-control', 'placeholder' => 'Enter a Service Price']  )}}
                  <p class="help-block">
                      {{ $errors->has('price') ? $errors->first('price', ':message') : '' }}
                  </p>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">{{isset($id) ? 'Update':'Add'}} Service</button>
              </div>
            {{Form::close()}}
          </div>
          <!-- /.box -->

 

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Service List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                    <tr>
                    <th style="width: 10px">#</th>
                    <th>Srvice Type</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th style="width: 40px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        //dd($servicesList);
                    ?>
                @foreach($servicesList as $key => $service)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$service->type->name}}</td>
                    <td>{{$service->name}}</td>
                    <td>{{$service->price}}</td>
                  <td style="white-space: nowrap;">
                    <a href="{{url('/admin/services/add')}}/{{$service['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/services/trash')}}/{{$service['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a>
                  </td>
                </tr>
               @endforeach
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">«</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">»</a></li>
              </ul>
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
@endsection    