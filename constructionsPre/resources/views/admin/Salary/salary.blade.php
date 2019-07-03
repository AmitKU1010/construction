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
             {{ Form::open(['url' => 'admin/salary/search']) }} 
             {{ csrf_field() }}
             @php $SaveButton = 'Show Dates'; @endphp
                <div class="row">
                  <h4 class="box-title text-purple">Enter Search Details</h4>
                    @foreach($basic as $CFkey => $CFvalue)
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
                                    {{ Form::select($CFkey, $CFvalue['value'],  isset($$CFkey) ? $$CFkey :'', ['class' => 'form-control input-md '.$class.' ' , 'style' => isset($CFvalue['style']) ? $CFvalue['style']: '', isset($CFvalue['multiple']) ? $CFvalue['multiple'] : 'not-multiple' ]) }}
                                  @elseif($CFvalue['type'] == 'file')
                                    {{ Form::file($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                  @elseif($CFvalue['type'] == 'password')
                                    {{ Form::password($CFkey, ['id' => '', 'class' => 'form-control']) }}
                                  @endif
                                </div>
                                <p class="help-block">
                                  {{ $errors->has($CFkey) ? $errors->first($CFkey, ':message') : 'Enter Valid Information' }}
                                </p>
                              </div>
                            </div>
                    @endforeach
                </div>
                <?php 
                //dd($person);
                /*$pperson = $person->toArray();
                echo "##".count($pperson);
                exit;*/
                ?>
                @if(isset($person_count) && $person_count > 0)
                @php $SaveButton = 'Save/Update Details'; $totalPresent = 0; $totalWedges = 0;  @endphp
                  <h3 class="box-title">Please check the Present dates of Mr/Mrs. <b>{{$person['name']}}</b></h3>
                  
                  {{Form::checkbox('check_all', '', false, ['class' => '', 'id' => 'checkAll'])}} Check All Dates <br>
                      <div class="row">
                        
                          @foreach($dates as $date)
                            @php $isCheck = false;  @endphp
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label>
                                  @if(in_array($date, $oldDatePresent))
                                    @php $isCheck = true; $totalPresent++;@endphp
                                  @endif
                                  {{Form::checkbox('attendance_date[]', $date, $isCheck, ['class' => 'flat-redx attendance_date'])}}
                                  {{date('d/M/Y', strtotime($date))}}
                                </label>
                              </div>
                            </div>
                          @endforeach
                          @php $totalWedges = $totalPresent * $person['dailly_wedge']; @endphp
                      </div>
                      <div class="row">
                          <div class="col-sm-2 ">
                            <div class="form-group ">
                                <label for="exampleInputFile">Dailly Wedges</label>
                                {{Form::text('dailly_wedge', isset($dailly_wedge) ? $dailly_wedge : $person['dailly_wedge'], ['class' => 'form-control input-md dailly_wedge', 'readonly'])}}
                                <p class="help-block">
                                  Enter Valid Information
                                </p>
                            </div>
                          </div>
                          <div class="col-sm-2 ">
                            <div class="form-group ">
                                <label for="exampleInputFile">O.T. Amount & Days</label>
                                <div class="" style="white-space: nowrap">
                                {{Form::text('over_time', isset($person['over_time']) ? $person['over_time'] : '0', ['class' => 'form-control input-md over_time', 'style' => 'width:50%;float:left', 'placeholder' => 'Price'])}}
                                {{Form::text('over_time_days', isset($person['over_time_days']) ? $person['over_time_days'] : '0', ['class' => 'form-control input-md over_time_days', 'style' => 'width:50%;float:right', 'placeholder' => 'Days'])}}
                                </div>
                                <p class="help-block">
                                  Enter Valid Information
                                </p>
                            </div>
                          </div>
                          <div class="col-sm-2 ">
                            <div class="form-group ">
                                <label for="exampleInputFile">no. of Present Days</label>
                                {{Form::text('present_days', isset($present_days) ? $present_days : $totalPresent, ['class' => 'form-control input-md present_days', 'readonly'])}}
                                <p class="help-block">
                                  Enter Valid Information
                                </p>
                            </div>
                          </div>
                          <div class="col-sm-3 ">
                            <div class="form-group ">
                                <label for="exampleInputFile">Basic Salary</label>
                                {{Form::text('total_wedge', isset($total_wedge) ? $total_wedge : $totalWedges, ['class' => 'form-control input-md total_wedge', 'readonly'])}}
                                <p class="help-block">
                                  Enter Valid Information
                                </p>
                            </div>
                          </div>
                          <div class="col-sm-3 ">
                            <div class="form-group ">
                                <label for="exampleInputFile">Paid/Not paid</label>
                                {{Form::select('is_paid',['1' => 'Paid', '0'=>'Not Paid'], isset($is_paid) ? $is_paid : $is_paid_status, ['class' => 'form-control input-md'])}}
                                <p class="help-block">
                                  Enter Valid Information
                                </p>
                            </div>
                          </div>
                          <div class="pull-right badge">INR <span class="total_paid_salary">00.00</span></div>
                      </div>
                  @endif
                <div class="box-footer text-center">
                  {{ Form::submit($SaveButton, array('class' => 'btn btn-success')) }}
                  {{ Form::reset('Reset', array('class' => 'btn btn-warning')) }}
                </div>
            
                <!-- /.row -->
            {{Form::close()}}
            
          <!-- /.row -->
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
    $(".over_time, .over_time_days").keyup(function() {
        var ovettime = $(".over_time").val();
        var ovettimedays = $(".over_time_days").val();
        var present_days = $(".present_days").val();
        var total_wedge = $(".total_wedge").val();

        var paid_total_amount = (ovettime * ovettimedays) + (present_days * total_wedge);
        $(".total_paid_salary").val(paid_total_amount);

    });

    $("#checkAll").change(function() {
        console.log("go");
        $('input:checkbox').not(this).prop('checked', this.checked);

        var totalPresentCount = 0;
        $(".attendance_date").each(function() {
            if ($(this).prop("checked") === true) {
                totalPresentCount++;
            }

        });
        var daillyWedge = $(".dailly_wedge").val();
        var totalWedges = daillyWedge * totalPresentCount;
        $(".present_days").val(totalPresentCount).attr('readonly', 'readonly');
        $(".total_wedge").val(totalWedges).attr('readonly', 'readonly');


    });
    $(".attendance_date").change(function() {
        var totalPresentCount = 0;
        $(".attendance_date").each(function() {
            if ($(this).prop("checked") === true) {
                totalPresentCount++;
            }

        });
        console.log(totalPresentCount);
        var daillyWedge = $(".dailly_wedge").val();
        var totalWedges = daillyWedge * totalPresentCount;
        $(".present_days").val(totalPresentCount).attr('readonly', 'readonly');
        $(".total_wedge").val(totalWedges).attr('readonly', 'readonly');
    });

    $('.overlay').hide();
    $(".room_no").keyup(function(event) {
        $('.overlay').show();
        var room_no = $(this).val();
        var hostel_id = $('.hostels_id :selected').val();

        $.ajax({
            type: "POST",
            url: "{{url('/')}}/validate-hostel-roomno",
            data: {
                "_token": "{{ csrf_token() }}",
                room_no: room_no,
                hostel_id: hostel_id
            },
            dataType: 'html',
            cache: false,
            success: function(data) {
                if (data == 1) {
                    $(".room_no").val('');
                    alert('You have Entered Room Details for this Room Previously! Please try another room Number...');
                }


                /* $("#overview-payment-render").html(data);*/
                $('.overlay').hide();
            }
        });
    });
});
</script>
    @endsection