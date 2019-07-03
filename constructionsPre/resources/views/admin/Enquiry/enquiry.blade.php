@extends('layouts.default')
@section('content')
<section class="content">
   <div class="row">
   {{ Form::open(['url' => 'admin/customer/enquiry/save', 'files' => true]) }} 
   {{ csrf_field() }}
   {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
      <!-- left column customer/enquiry -->
      <div class="col-md-12 col-sm-12">
         <!-- general form elements -->
         <div class="box box-primary">
            <div class="box-header with-border">
               <h3 class="box-title">Enquiry</h3>
            </div>
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
                      <div class="form-group">
                                  <label for="customer_name">Customer Name</label>
                                  {{Form::text('customer_name', isset($customer_name) ? $customer_name: '', ['class' => 'form-control','required', 'placeholder' => 'Enter Customer Name']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('customer_name') ? $errors->first('customer_name', ':message') : '' }}
                                  </p>
                      </div>
                       <div class="form-group">
                                  <label for="customer_name">Contact Number</label>
                                  {{Form::text('contact_number', isset($contact_number) ? $contact_number: '', ['class' => 'form-control','required','placeholder' => 'Enter Customer Name']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('customer_name') ? $errors->first('customer_name', ':message') : '' }}
                                  </p>
                      </div>
                       <div class="form-group">
                                  <label for="doe">Date of Enquiry</label>
                                 {{Form::text('doe', isset($doe)?$doe: '', ['class' => 'form-control doe', 'id' => 'datepicker','required','placeholder' => 'Choose a date']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('doe') ? $errors->first('doe', ':message') : '' }}
                                  </p>
                      </div>
                      @if(isset($id))
                      
                        <div class="form-group">
                                  <label for="address">Is Customer</label>
                                  {{Form::checkbox('is_customer', null, ['class' => 'form-control']  )}}
                                  <p class="help-block">
                                     {{ $errors->has('is_customer') ? $errors->first('is_customer', ':message') : '' }}
                                  </p>
                      </div>
                       <div class="form-group">
                                  <label for="address">Remark</label>
                                  {{Form::textarea('remark', isset($remark) ? $remark: '0', ['class' => 'form-control','required','rows' => 4, 'cols' => 54]  )}}
                                  <p class="help-block">
                                     {{ $errors->has('remark') ? $errors->first('remark', ':message') : '' }}
                                  </p>
                      </div>
                      
                      @endif
                       <div class="form-group">
                                  <label for="address">Address</label>
                                  {{Form::textarea('address', isset($address) ? $address: '', ['class' => 'form-control','required','rows' => 4, 'cols' => 54]  )}}
                                  <p class="help-block">
                                     {{ $errors->has('address') ? $errors->first('address', ':message') : '' }}
                                  </p>
                      </div>
                      
                     <!--  -->
                   </div>              
                     
            <!-- /.box-body -->
            <div class="box-footer">
               <button type="submit" id="submit" class="btn btn-primary ">{{ isset($id)?"update":"Add" }} Enquiry</button>
            </div>

          </div>   

          <div class="col-md-6">   
           <div class="box-body">
              <div class=" table-responsive table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                      <th>Id</th>
                      <th>Customer Name</th>
                      <th>Mobile number</th>
                      <th>Address</th>
                      <th>Is Customer</th>
                      <th>Date Of Enquiry</th>
                      <th>Remark</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($enquiry as $key => $data)
                  <tr>
                    <td>{{ $data->id  }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>{{ $data->contact_number }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ $data->is_customer==0?"No":"Yes" }}</td>
                    <td>{{ $data->doe }}</td>
                    <td>{{ $data->remark }}</td>
                    
                   
                    <td style="white-space: nowrap">
                      <a href="{{ url('/')}}/admin/customer/enquiry/edit/{{ $data->id }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                      <a href="{{ url('/')}}/admin/customer/enquiry/trash/{{ $data->id}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this diary details?');"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                   <tfoot>
                    <tr>
                      <td colspan="6">{!! $enquiry->render() !!}</td>
                     </tr>
                  </tfoot> 
                </table>
              </div>
          </div>
         </div>
         <!-- /.box -->
      </div>
 
   </div>
 </div>
   {{Form::close()}}
</section>
@endsection
@section('extra-javascript')
</script>
@endsection