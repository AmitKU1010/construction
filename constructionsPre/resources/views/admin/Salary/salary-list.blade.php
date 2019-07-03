@if($currentRoute == 'report-salary-pdf')
  @php $defLayout = 'pdf-layout'; @endphp
@else
  @php $defLayout = 'default'; @endphp
@endif
@php
 $isMatch = strpos($currentRoute, 'report');
@endphp

@extends('layouts.'.$defLayout)
@section('content')
@if($currentRoute != 'report-salary-pdf')
<section class="content">

  <div class="box">
    <div class="box-header"> 
        <h3 class="box-title">List of all Refillings </h3>
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
             {{ Form::open(['url' => 'admin/report/salary/search']) }} 
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
            </div>
          <!-- /.row -->
        </div>



            <div class="box-body">
              <a href="{{url('/')}}/admin/report/salary/pdf" class="btn btn-xs btn-info pull-right margin"><i class="fa fa-pdf"></i> Export to PDF</a>
  @endif
            <h3 class="text-center margin text-purple"><strong> Report for Salary of Employees as on : {{date('d/m/Y')}} </strong></h3>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL no.</th>
                  <th>Employee</th>
                  <th>Year/Month</th>
                  <th>Dailly Wedges</th>
                  <th>Total Present</th>
                  <th>Over Time</th>
                  <th>Basic Wedge</th>
                  <th>Paid Wedge</th>
                  <th>Is Paid ? </th>
                  <th>Paid Date </th>
                </tr>
                </thead>
                <tbody>
                @php $i=1 @endphp
                  @foreach($salaryList as $salary)
                <tr>
                    <td>{{$i}}</td>
                    <td>{{ $salary->user->name }}</td>
                    <td>{{ $salary->year.'/'.$salary->month }}</td>
                    <td>{{ $salary->dailly_wedge }}</td>
                    <td>{{ $salary->present_days.' Days' }}</td>
                    <td>
                      @if(isset($salary->over_time) && $salary->over_time !='')
                        {{$salary->over_time}} * {{$salary->over_time_days}} = {{$salary->over_time * $salary->over_time_days}}
                      @endif
                    </td>
                    <td>{{ round($salary->total_wedge, 2) }}</td>
                    <td>{{ (($salary->over_time * $salary->over_time_days) + $salary->total_wedge) }}</td>
                    <td>{!! ($salary->is_paid == '1') ? '<span class="label label-success">Paid</span>' : '<span class="label label-danger">not Paid</span>' !!}</td>
                    
                    <td>
                      @if(isset($salary->is_paid) && $salary->is_paid == '1')
                        {{ date('d/M/Y h:i:s', strtotime($salary->updated_at)) }}
                      @else
                        {{'NA'}}
                      @endif
                    </td>
                </tr>
                @php $i++ @endphp
                @endforeach
              
                </tbody>
              </table>
  {{-- @if($currentRoute != 'report-salary-pdf') --}}
              {{ $salaryList->links() }}
            </div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
  {{-- @endif --}}
    @endsection