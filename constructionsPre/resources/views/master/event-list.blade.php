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
             {{ Form::open(['url' => 'employee-list', 'files' => true]) }} 
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
              <div class="table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>SL no.</th>
                    <th>Event Name</th>
                    <th>Class</th>
                    <th>First Position</th>
                    <th>Second Position</th>
                    <th>Third Position</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($events as $event)
                  <tr>
                    <td>{{ $loopInit }}</td>
                    <td>{{ $event['event_name'] }}</td>
                    <td>{{ 'STD-'.Helper::integerToRoman($event['admission_class']) }}</td>
                    <td><span class="text-success">{{ Helper::getStudentInfo($event['first_position']) }}</span></td>
                    <td><span class="text-info">{{ Helper::getStudentInfo($event['second_position']) }}</span></td>
                    <td><span class="text-warning">{{ Helper::getStudentInfo($event['third_position']) }}</span></td>
                    <td>{{ $event['remark'] }}</span></td>
                    <td>
                      <a href="{{ url('/')}}/event-edit/{{ $event['id'] }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a> 
                      <a href="{{ url('/')}}/event-delete/{{ $event['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this user?');"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>
                  @php $loopInit++ @endphp
                  @endforeach
                
                  </tbody>
                </table>
              </div>
              {{ $events->links() }}
            </div>
            <!-- /.box-body -->
          </div>


    </section>
    <!-- /.content -->
    @endsection