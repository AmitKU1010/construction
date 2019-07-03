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
        <?php 
            $period = '5';
        ?>
             {{ Form::open(['url' => 'diary/search', 'files' => true]) }} 
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
                                  @isset($CFvalue['message']) {{$CFvalue['message']}} @endisset
                                  {{ $errors->has($CFkey) ? $errors->first($CFkey, ':message') : '' }}
                                </p>
                              </div>
                            </div>
                    @endforeach
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
                    <th>Date of Teaching</th>
                    <th>Class/Section</th>
                    <th>Period</th>
                    <th>Subject</th>
                    <th>Syllabus</th>
                    <th>Actual date of Completion</th>
                    <th>Approved by HOD?</th>
                    <th>Updated Date</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($diaryList as $key => $diary)
                  <tr>
                    <td>{{ $diaryList->firstItem() + $key }}</td>
                    <td>{{Helper::getDayNameFromDate($diary['estimated_date_of_completion']) }}</td>
                    <td>{!! 'Class-'.Helper::str_ordinal($diary['class'], true) !!}</td>
                    <td>{!! Helper::str_ordinal($diary['period'], true) !!}</td>
                    <td>{{ $diary->subject->name }}</td>
                    <td>
                      {{ $diary->syllabus->title }}
                      <div class="">
                        <a href="" class="text-purple" data-toggle="modal" data-target="#syllabus_{{$key}}">Syllabus</a> | <a href="text-warning" class="" data-toggle="modal" data-target="#syllabus_{{$key}}">Courses Covered</a>
                      </div>
                      <!-- Modal -->
                      <div id="syllabus_{{$key}}" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Syllabus Details</h4>
                            </div>
                            <div class="modal-body">
                              <h3>Total Syllabus</h3>
                            <p>{{$diary->syllabus->description}}</p>
                              <hr>
                            <h3>Syllabus Covered</h3>
                            <p>{{$diary->courses_covered}}</p>

                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                          </div>

                        </div>
                      </div>

                    </td>
                    <td>
                      @isset($diary['actual_date_of_completion']) {{ Helper::changeDateFormat('d-m-Y', $diary['actual_date_of_completion']) }} @endisset
                    </td>
                    <td>
                      @if($diary['is_approved'] == '0')
                        {{ 'Approved' }}<br>
                        @if(isset(Auth::user()->role_id) && Auth::user()->role_id == '3')
                          <a href="{{ url('/')}}/diary/approve/{{ $diary['id']}}" class="btn btn-info btn-xs" onclick="return confirm('Are you sure you want to approve this diary details?');"><i class="fa fa-thumbs-up"></i> Approve</a>
                        @endif
                      @else
                        {{ 'Not approved'}}<br>
                        <a href="{{ url('/')}}/diary/unapprove/{{ $diary['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to approve this diary details?');"><i class="fa fa-thumbs-down"></i> un-Approve</a>
                      @endif
                    </td>
                    <td>{{ $diary['updated_at'] }}</td>
                    
                    <td style="white-space: nowrap">
                      {{-- <a href="{{ url('/')}}/diary/edit/{{ $diary['id'] }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>  --}}
                      <a href="{{ url('/')}}/diary/delete/{{ $diary['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this diary details?');"><i class="fa fa-trash"></i></a>

                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                </table>
              </div>
              {{ $diaryList->links() }}


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