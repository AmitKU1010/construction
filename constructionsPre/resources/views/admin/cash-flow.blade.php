@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">Please select a Month-Year combination</h3>
            </div>
            <!-- /.box-header -->
        <div class="box-body">
            
        {{ Form::open(['url' => 'cash-flow-filter']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }}
        <div class="row">
          <div class="col-md-4 col-sm-offset-2">
                  <div class="form-group ">
                      <label for="exampleInputFile">Select Month
                        <span class="text-danger"> *</span>
                      </label>
                    <div class="input text">
                      {{ Form::selectMonth('month', isset($month) ? $month : date('m'), ['class' => 'form-control input-md'] ) }}
                    </div>
                    <p class="help-block">
                      
                    </p>
                  </div>
          </div>
          <div class="col-md-4">
              <div class="form-group ">
                  <label for="exampleInputFile">Select Year
                    <span class="text-danger"> *</span>
                  </label>
                <div class="input text">
                    {{ Form::selectYear('year', date('Y') , date('Y')-10, isset($year) ? $year : date('Y'),  ['class' => 'form-control input-md'] ) }}
                </div>
                <p class="help-block">
                  
                </p>
              </div>
          </div>
        </div>
        <center>
          <div class="box-footer">
            {{ Form::submit('Search', array('class' => 'btn btn-success btn-sm')) }}
            {{ Form::reset('Reset', array('class' => 'btn btn-warning btn-sm', 'onclick' => 'resetForm()')) }}
          </div>
        </center>


        </div>
            <div class="box-body">
              <div class="table-bootstrap">
              	{{--  SLOT FOR OPENING BAANCE  --}}
              <h2>School Moneyflow overview for the session of {{date('F', mktime(0, 0, 0, $month, 10))}} {{$year}}</h2>
                
                <table id="example1" class="table table-bordered table-striped">
                    
                    @foreach($data as $date => $eachDate)
                    <thead>
                        <tr class="info">
                          <th colspan="2" class="text-center">Hstory on the Date {{Carbon\Carbon::parse($date)->format('d/M/Y')}}</th>
                        </tr>
                    </thead>
                    <thead>
                        <tr>
                            <th>Balance Inflow</th>
                            <th>Balance Outflow</th>
                        </tr>
                        @if(isset($eachDate['balance']) && is_array($eachDate['balance']) && count($eachDate['balance']) > 0)
                          <tr style="background: #cddc39ad;">
                            <td colspan="2" class="text-center">
                                <span class="label label-success">
                                  @foreach($eachDate['balance'] as $balance)
                                  Balance Added to Account :-  {{$balance['amount']}}
                                    @php $totalBalance += $balance['amount']; @endphp
                                  @endforeach
                                </span>
                            </td>
                          </tr>
                        @endif
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                              @if(isset($eachDate['inflow']) && is_array($eachDate['inflow']) && count($eachDate['inflow']) > 0)
                                <table id="example1" class="table table-bordered table-striped">
                                  {{--  Inflow Data Starts Here  --}}
                                  <tbody>
                                    @foreach($eachDate['inflow'] as $inflow)
                                    <tr class="success">
                                      <td>{!! $inflow['info'] !!}</td>
                                      <td>{{$inflow['amount']}} @php $totalBalance += $inflow['amount']; @endphp</td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                                
                              @else
                                <table id="example1" class="table table-bordered table-striped">
                                  <tbody>
                                    <tr class="success">
                                      <td colspan="2"><div class="alert alert-danger"> {!! 'Sorry! No records found.' !!} </div> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              @endif
                            </td>
                            <td>
                                @if(isset($eachDate['outflow']) && is_array($eachDate['outflow']) && count($eachDate['outflow']) > 0)
                                  <table id="example1" class="table table-bordered table-striped">
                                    {{--  Inflow Data Starts Here  --}}
                                    <tbody>
                                      @foreach($eachDate['outflow'] as $outflow)
                                      <tr class="danger">
                                        <td>{{$outflow['info']}}</td>
                                        <td>{{$outflow['amount']}} @php $totalBalance = (float)$totalBalance - (float)$outflow['amount']; @endphp</td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>     
                                            
                                  @else
                                  <table id="example1" class="table table-bordered table-striped">
                                    <tbody>
                                      <tr class="success">
                                        <td colspan="2"><div class="alert alert-danger"> {!! 'Sorry! No records found.' !!} </div> </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                @endif
                            </td>
                        </tr>
                        <tr>
                          <td colspan="2"> <blockquote class="pull-right" style="border-right: 5px solid #E91E63;">Closing Balance as on {{Carbon\Carbon::parse($date)->format('d/M/Y')}} = <span class="text-danger">{{$totalBalance}} </span> </blockquote></td>
                        </tr>
                      </tbody>
                    @endforeach
                </table>
              </div>
            </div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
    @endsection