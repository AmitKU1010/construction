@extends('layouts.default')

@section('content')
<section class="content">
<style type="text/css">
  .box-title{
    margin-left: 12px;
  }
</style>
      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">Please fill up necessary fields.</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
             <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
        
        @if(session()->has('message.level'))
          <div class="alert alert-{{ session('message.level') }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
            {!! session('message.content') !!}
          </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <ul>
                  <li>Warning ! Please resolve following errors.</li>
                </ul>
            </div>
        @endif
             {{ Form::open(['url' => 'diary/save']) }} 
             {{ csrf_field() }}
             {{ Form::hidden('id', isset($id) ? $id :'', []) }}
                <div class="row">
                  <h4 class="box-title text-purple">General Informations</h4>
                    @foreach($customFields['basic'] as $CFkey => $CFvalue)

                      @if($CFvalue['type'] == 'separator')
                        </div><div class="row"><h4 class="box-title text-purple">{{$CFvalue['label']}}</h4>
                      @else
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
                                      {{ Form::select($CFkey, $CFvalue['value'],  isset($$CFkey) ? $$CFkey :'', ['class' => 'form-control input-md '.$class.' ', 'style' => isset($CFvalue['style']) ? $CFvalue['style']: '' ]) }}
                                    @elseif($CFvalue['type'] == 'file')
                                      {{ Form::file($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                    @elseif($CFvalue['type'] == 'password')
                                      {{ Form::password($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                    @elseif($CFvalue['type'] == 'textarea')
                                      {{ Form::textarea($CFkey,  isset($$CFkey) ? $$CFkey :'', ['id' => '', 'class' => 'form-control input-md '.$class.' ', 'rows' => '3']) }}
                                    @endif
                                  </div>
                                  <p class="help-block">
                                    @isset($CFvalue['message']) {!! $CFvalue['message'] !!} @endisset
                                    {{ $errors->has($CFkey) ? $errors->first($CFkey, ':message') : '' }}
                                  </p>
                                </div>
                              </div>
                      @endif
                    @endforeach
                </div>


            <div class="box-footer">
              {{ Form::submit('Save/Update', array('class' => 'btn btn-success')) }}
              {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
              {{ Form::close() }}
            </div>

          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <a href="" class="btn bg-maroon btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-plus"></i> Add Syllabus</a>
          <a href="" class="btn bg-purple btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-list"></i> List of Syllabie</a>
          <!-- Modal -->
          <div id="addSyllabus" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Create a new Syllabus</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                      {{ Form::open(['url' => 'syllabus/save', 'method' => 'post', 'class' => '']) }}
                            {{ csrf_field() }}
                            {{ Form::hidden('id', isset($id) ? $id :'', []) }}
                                <div class="col-md-4">
	                                 <div class="form-group">
	                                    <label for="exampleInputEmail2">Choose Class</label>
	                                    <span class="input-icon icon-right">
                                          {{Form::select('class', $classes, isset($class) ? $class : '', ['class' => 'class-list form-control', 'required'])}}
                                          <p class="help-block">
                                            {{ $errors->has('class') ? $errors->first('class', ':message') : '' }}
                                          </p>
	                                    </span>
                                   </div>
                                </div>
                                <div class="col-md-4">
	                                 <div class="form-group">
	                                    <label for="exampleInputEmail2">Choose Section</label>
	                                    <span class="input-icon icon-right">
                                          {{Form::select('section', ['' => '--Select Section--','A' => 'A', 'B' => 'B', 'C' => 'C'], isset($section) ? $section : '', ['class' => 'section-list form-control' ])}}
                                          <small>If you dont choose then Your syllabus will be applicable for all sections</small>
	                                    </span>
                                   </div>
                                </div>
	                             	<div class="col-md-4">
	                                 <div class="form-group">
	                                    <label for="exampleInputEmail2">Choose Subject</label>
	                                    <span class="input-icon icon-right">
	                                    {{Form::select('subject_id', [], isset($type) ? $type : '', ['class' => 'subject-list form-control', 'required'])}}
	                                    
	                                    </span>
                                   </div>
                                 </div>
                                 <div class="col-md-8">
	                                 <div class="form-group">
	                                    <label for="exampleInputEmail2">Title for the Syllabus</label>
	                                    <span class="input-icon icon-right">
	                                    {{Form::text('title', isset($title) ? $title : '', ['class' => 'title form-control', 'required'])}}
	                                    
	                                    </span>
                                   </div>
                                 </div>
                                 <div class="col-md-12">
	                                 <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
	                                    <label for="exampleInputEmail2">Enter Lesson Descriptions</label>
	                                    <span class="input-icon icon-right">
                                      {{Form::textarea('description', isset($description) ? $description : '', ['class' => 'form-control', 'required', 'rows' => '5', 'cols' => '10'])}}
                                        <p class="help-block">
                                          {{ $errors->has('description') ? $errors->first('description', ':message') : '' }}
                                        </p>
	                                    </span>
	                                 </div>

                                 </div>
                                
                  </div>
                </div>
                <div class="modal-footer">
                  {{Form::submit(isset($id) ? 'Update Syllabus' : 'Add Syllabus', ['class' => 'btn btn-success'])}}
                  {{ Form::close() }}
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>


        </div>
      </div>
      <!-- /.box -->


    </section>
    <!-- /.content -->
    @endsection

    @section('extra-javascript')
    <script type="text/javascript">
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    </script>
        <script type = "text/javascript" language = "javascript">
          $(document).ready(function() {
            /**
              Note : To enlarge the Duyanmically generated Textarea i have add class by script
            */
            var dd = $(".courses_covered").parent().parent().parent().removeClass('col-md-3').addClass('col-md-8');
    

          $('.overlay').hide();
            $(".class-list").change(function(event){
              $('.overlay').show();
               var classname = $(this).val();
               $.ajax({
                type: "POST",
                url: "{{url('/')}}/getsubjectlistfromclass",
                data: { 
                        "_token": "{{ csrf_token() }}",
                       cls : classname,
                      },
                dataType : 'html',
                cache: false,
                success: function(data){
                  
                  subjects = $.parseJSON(data);
                  $('.subject-list')
                          .empty()
                          .append('<option selected="selected" value="">-Select Subject -</option>');
                  $.each(subjects, function(i, item) {
                    console.log(i +   "   " + item);
                      $('.subject-list, .subject-list-down').append(
                            '<option value="'+i+'">'+item+'</option>'
                       );
                  });
                 /* $("#overview-payment-render").html(data);*/
                  $('.overlay').hide();
                }
              });
            });

            $(".subject-list-down").change(function(event){
              $('.overlay').show();
               var subject_id = $(this).val();
               var classname = $('.class-list').val();
               $.ajax({
                type: "POST",
                url: "{{url('/')}}/getsyllabusaccordingtosubjectid",
                data: { 
                        "_token": "{{ csrf_token() }}",
                       cls : classname,
                       sid : subject_id,
                      },
                dataType : 'html',
                cache: false,
                success: function(data){
                  
                  syllabus = $.parseJSON(data);
                  $('.syllabus-list')
                          .empty()
                          .append('<option selected="selected" value="">- Select Syllabus Title -</option>');
                  $.each(syllabus, function(i, item) {
                    console.log(i +   "   " + item);
                      $('.syllabus-list').append(
                            '<option value="'+i+'">'+item+'</option>'
                       );
                  });
                 /* $("#overview-payment-render").html(data);*/
                  $('.overlay').hide();
                }
              });
            });
            

         });
      </script>
    @endsection