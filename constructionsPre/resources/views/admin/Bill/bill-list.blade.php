@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of all Purchases</h3>
            </div>
            <!-- /.box-header -->
 <div class="box-body">
          
        
        @if(session()->has('message.level'))
          <div class="alert alert-{{ session('message.level') }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
            {!! session('message.content') !!}
          </div>
        @endif

        {{-- @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}
        <?php 
            $period = '5';
        ?>
             {{ Form::open(['url' => 'diary/search', 'files' => true]) }} 
             {{ csrf_field() }}
             {{ Form::hidden('id', isset($id) ? $id :'', []) }}
                <div class="row">
                    {{-- @foreach($customFields['basic'] as $CFkey => $CFvalue)
                      @php $class = isset($CFvalue['class']) ? $CFvalue['class'] : ''; @endphp
                            <div class="col-sm-3 {{ isset($CFvalue['optColDiv']) ? $CFvalue['optColDiv']: '' }}">
                              <div class="form-group {{ $errors->has($CFkey) ? 'has-error' : ''}}">
                                      <label for="exampleInputFile">{{ $CFvalue['label'] }} 
                                    @if($CFvalue['mandatory'])
                                        <span class="text-danger"> *</span>
                                    @endif
                                </label>
                                <div class="input text">
                                  @if($CFvalue['type'] == 'text')
                                    {{ Form::text($CFkey, isset($$CFkey) ? $$CFkey :'', ['class' => 'form-control input-md '.$class.' ', 'id' => isset($CFvalue['id']) ? $CFvalue['id']: '', 'style' => isset($CFvalue['style']) ? $CFvalue['style']: '', 'placeholder' => $CFvalue['label'], 'autocomplete' => 'off']) }}
                                  @elseif($CFvalue['type'] == 'select')
                                    {{ Form::select($CFkey, $CFvalue['value'],  isset($$CFkey) ? $$CFkey :'', ['class' => 'form-control input-md' ]) }}
                                  @elseif($CFvalue['type'] == 'file')
                                    {{ Form::file($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                  @elseif($CFvalue['type'] == 'password')
                                    {{ Form::password($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                  @endif
                                </div>
                                <p class="help-block">
                                  @isset($CFvalue['message']) {{$CFvalue['message']}} @endisset
                                  {{ $errors->has($CFkey) ? $errors->first($CFkey, ':message') : '' }}
                                </p>
                              </div>
                            </div>
                    @endforeach --}}
                </div>
            <div class="box-footer">
              {{ Form::submit('Search', array('class' => 'btn btn-success')) }}
            </div>

          <!-- /.row -->
        </div>



            <div class="box-body">
              <div class="table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Customer Contact</th>
                    <th>Address</th>
                    <th>Services</th>
                    <th>Service Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($purchaseList as $key => $purchase)
                  <tr>
                    <td>{{ $purchaseList->firstItem() + $key }}</td>
                    <td>{{ $purchase->customer_name }}</td>
                    <td>{{ $purchase->customer_contact }}</td>
                    <td>{{ $purchase->customer_address }}</td>
                    <td>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Service Person</th>
                                    <th>Service Type</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($purchase->details) && count($purchase->details) >0)
                                @foreach($purchase->details as $billDetails)
                                    <tr>
                                        <td>{{$billDetails->servicePerson->name}}</td>
                                        <td>{{$billDetails->serviceType->name}}</td>
                                        <td>{{$billDetails->service->name}}</td>
                                        <td>{{$billDetails->service_price}}</td>
                                        <td>{{$billDetails->service_qty}}</td>
                                        <td>{{$billDetails->service_amount}}</td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">
                                            <div class="text-danger">Sorry, no record found</div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                    <td>{{ $purchase->created_at }}</td>
                    
                    
                    <td style="white-space: nowrap">
                      {{-- <a href="{{ url('/')}}/diary/edit/{{ $diary['id'] }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>  --}}
                      <a href="{{ url('/')}}/diary/delete/{{ $purchase['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this diary details?');"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                </table>
              </div>
              {{ $purchaseList->links() }}


            <div class="box-footer">
              <a href="" class="btn bg-maroon btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-close"></i> Clear Trash</a>
              <a href="" class="btn bg-purple btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-restore"></i> Restore Trash</a>
            </div>


            </div>
            <!-- /.box-body -->
          </div>


    </section>
    <!-- /.content -->
  @endsection
  @section('extra-javascript')
  <script type = "text/javascript" language = "javascript">
  $(document).ready(function() {
  });
  </script>
  @endsection