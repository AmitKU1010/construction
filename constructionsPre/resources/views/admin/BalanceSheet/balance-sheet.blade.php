@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of all Events</h3>
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
             {{-- {{ Form::open(['url' => 'employee-list', 'files' => true]) }} 
             {{ csrf_field() }}
             {{ Form::hidden('id', isset($id) ? $id :'', []) }}
                <div class="row">
                    @foreach($customFields['basic'] as $CFkey => $CFvalue)
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
                                  {{ $errors->has($CFkey) ? $errors->first($CFkey, ':message') : '' }}
                                </p>
                              </div>
                            </div>
                    @endforeach
                </div>
            <div class="box-footer">
              {{ Form::submit('Search', array('class' => 'btn btn-success')) }}
              {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
            </div> --}}

          <!-- /.row -->
        </div>



            <div class="box-body">
              <div class="table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  
                  <tbody>
                    @php $count = 1; $sum = 0; @endphp
                    @foreach($balanceSheet as $key => $sheet)
                    <?php //dd($sheet); ?>
                    <tr>
                        <th>
                          <h4>as on date of {{$key}}</h4>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL no.</th>
                                        <th>Sheet Type</th>
                                        <th>Flow type</th>
                                        <th>Date of Transaction</th>
                                        <th>Transaction Amount</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sheet as $dataKey => $data)
                                    @if($data['mode'] == 'INC')
                                      @php $sum += $data['amount']; @endphp
                                    @else
                                      @php $sum -= $data['amount']; @endphp
                                    @endif
                                    <tr class="@if(isset($data['mode']) && $data['mode'] == 'INC') {{'success'}} @else {{'danger'}} @endif ">
                                        <td>{{ $count }}</td>
                                        <td>{{ $data['type'] }}</td>
                                        <td>@if(isset($data['mode']) && $data['mode'] == 'INC') {{'Income'}} @else {{'Expenses'}} @endif</td>
                                        <td>{{ $data['date'] }}</td>
                                        <td>{{ $data['amount'] }}</td>
                                        <td>{{ $data['details'] }}</td>
                                    </tr>
                                    @php $count++; @endphp
                                    @endforeach
                                    <tr>
                                      <td colspan="4"></td>
                                      <td>Total</td>
                                      <td>{{$sum}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                  
                  @endforeach
                
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>


    </section>
    <!-- /.content -->
    @endsection