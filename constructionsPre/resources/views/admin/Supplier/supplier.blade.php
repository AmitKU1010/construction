@extends('layouts.default')

@section('content')
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the details for {{$pageTitle}} </h3>
            <a href="{{url('/admin')}}/supplier/add" class="btn btn-success btn-xs pull-right"><i class="fa fa-plus"></i> Add New</a>
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
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            {{ Form::open(['url' => 'admin/supplier/save']) }} 
            {{ csrf_field() }}
            {{ Form::hidden('id', isset($id) ? $id :'', []) }}
              <div class="box-body">
                <div class="form-group {{ $errors->has('name') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier Name</label>
                  {{Form::text('name', isset($name)?$name: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('name') ? $errors->first('name', ':message') : '' }}
                  </p>
                </div>
                 <div class="form-group {{ $errors->has('address') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier Address</label>
                  {{Form::textarea('address', isset($address)?$address: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type', 'rows' => '3']  )}}
                  <p class="help-block">
                    {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group {{ $errors->has('mobile') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier Contact No</label>
                  {{Form::text('mobile', isset($mobile)?$mobile: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('mobile') ? $errors->first('mobile', ':message') : '' }}
                  </p>
                </div>
               <div class="form-group {{ $errors->has('gstin') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier gstin</label>
                  {{Form::text('gstin', isset($gstin)?$gstin: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('gstin') ? $errors->first('gstin', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group {{ $errors->has('pan') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier PAN No</label>
                  {{Form::text('pan', isset($pan)?$pan: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('pan') ? $errors->first('pan', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group {{ $errors->has('email') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier Email Id</label>
                  {{Form::text('email', isset($email)?$email: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('email') ? $errors->first('email', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group {{ $errors->has('type') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Enter Supplier type</label>
                  {{Form::text('type', isset($type)?$type: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  <p class="help-block">
                    {{ $errors->has('type') ? $errors->first('type', ':message') : '' }}
                  </p>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Add/Update Supplier</button>
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
              <h3 class="box-title">Supplier List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                    <tr>
                    <th style="width: 10px">#</th>
                    <th>Supplier Details</th>
                    <th>Address</th>
                    <th>GSTIN</th>
                    <th>PAN</th>
                    <th>types</th>
                    <th style="width: 40px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        //dd($servicesList);
                    ?>
                @foreach($supplierList as $key => $supplier)
                
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                      {{$supplier->name}}<br>
                     Ph. {{$supplier->mobile}}<br>
                     Email.  {{$supplier->email}}
                    </td>
                    <td>{{$supplier->address}}</td>
                    <td>{{$supplier->gstin}} </td>
                     <td>{{$supplier->pan}} </td>
                      <td>{{$supplier->type}} </td>
                  <td style="white-space: nowrap;">
                    <a href="{{url('/admin/supplier/add')}}/{{$supplier['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/supplier/trash')}}/{{$supplier['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a>
                  </td>
                </tr>
               @endforeach
              </tbody></table>
              </div>
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