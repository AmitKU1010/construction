@extends('layouts.default')

@section('content')
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                  Please fill up the service details
              </h3>
                <a href="{{url('/admin/stock/add')}}" class="btn btn-success btn-xs pull-right"><i class="fa fa-plus"></i> Add Stock</a>

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
            {{ Form::open(['url' => 'admin/stock/save']) }} 
            {{ csrf_field() }}
            {{ Form::hidden('id', isset($id) ? $id :'', []) }}
              <div class="box-body">
                <div class="form-group {{ $errors->has('supplier_id') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Choose Supplier Name</label>
                  {{Form::select('supplier_id', $supplierList, isset($supplier_id)?$supplier_id: '', ['class' => 'form-control', 'placeholder' => '-- Select Service Type']  )}}
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"> --}}
                  
                </div>
                <div class="form-group {{ $errors->has('product_name') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputPassword1">Product Name</label>
                  {{Form::text('product_name', isset($product_name)?$product_name: '', ['class' => 'form-control', 'placeholder' => 'Enter a Product name']  )}}
                </div>
                <div class="form-group {{ $errors->has('description') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputPassword1">Description</label>
                  {{Form::text('description', isset($description)?$description: '', ['class' => 'form-control', 'placeholder' => 'Enter Product Description']  )}}
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Invoice No</label>
                    {{Form::text('invoice_no', isset($invoice_no)?$invoice_no: '', ['class' => 'form-control', 'placeholder' => 'Invocie No']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('order_no') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Order No</label>
                    {{Form::text('order_no', isset($order_no)?$order_no: '', ['class' => 'form-control', 'placeholder' => 'Enter Order No']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('date') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Choose Date</label>
                    {{Form::text('date', isset($date)?$date: '', ['class' => 'form-control', 'id' => 'datepicker', 'placeholder' => 'Enter Stock Date']  )}}
                    </div>
                </div>
                
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('hsn_code') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">HSN Code</label>
                    {{Form::text('hsn_code', isset($hsn_code)?$hsn_code: '', ['class' => 'form-control', 'placeholder' => 'Enter HSN Code']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('price') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Price of the product</label>
                    {{Form::text('price', isset($price)?$price: '', ['class' => 'form-control', 'placeholder' => 'Enter Price for Product']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('quantity') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Product Quantity</label>
                    {{Form::text('quantity', isset($quantity)?$quantity: '', ['class' => 'form-control', 'placeholder' => 'Enter Product Quantity']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('gst') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Product GST</label>
                    {{Form::text('gst', isset($gst)?$gst: '', ['class' => 'form-control', 'placeholder' => 'Enter GST']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('discount') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Product Discount</label>
                    {{Form::text('discount', isset($discount)?$discount: '', ['class' => 'form-control', 'placeholder' => 'Enter Product Discount']  )}}
                    </div>
                </div>
                <div class="col-md-4 low-padding">
                    <div class="form-group {{ $errors->has('total') ? 'has-error' : 'no-error' }}">
                    <label for="exampleInputPassword1">Product Total Price</label>
                    {{Form::text('total', isset($total)?$total: '', ['class' => 'form-control', 'placeholder' => 'Product Total']  )}}
                    </div>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Add/Update Stock</button>
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
                    <th>Invocie No</th>
                    <th>Invoice Date</th>
                    <th>Supplier</th>
                    <th>Total Price</th>
                    <th style="width: 40px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        //dd($servicesList);
                    ?>
                @foreach($stockList as $key => $stock)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$stock->invoice_no}}</td>
                    <td>{{$stock->date}}</td>
                    <td>{{$stock->supplier->name}}</td>
                    <td>{{$stock->total}}</td>
                  <td style="white-space: nowrap;">
                    <a href="{{url('/admin/stock/add')}}/{{$stock['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/stock/trash')}}/{{$stock['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a>
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