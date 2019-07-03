@extends('layouts.default')

@section('content')
<section class="content">
    {{ Form::open(['url' => 'admin/bill/save']) }} 
    {{ csrf_field() }}
    {{ Form::hidden('id', isset($id) ? $id :'', []) }}
      <div class="row">
        <!-- left column -->
        <div class="col-md-3">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                  Please fill up the details
              </h3>

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
            
              <div class="box-body">
                <div class="form-group {{ $errors->has('customer_name') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Customer Name</label>
                  {{Form::text('customer_name', isset($customer_name)?$customer_name: '', ['class' => 'form-control customer_name', 'placeholder' => 'Enter Full Name']  )}}
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"> --}}
                </div>
                <div class="form-group {{ $errors->has('customer_contact') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Customer Contact No.</label>
                  {{Form::text('customer_contact', isset($customer_contact)?$customer_contact: '', ['class' => 'form-control customer_contact', 'placeholder' => 'Enter Mobile no', 'maxLength' => '10']  )}}
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"> --}}
                </div>
                <div class="form-group {{ $errors->has('customer_address') ? 'has-error' : 'no-error' }}">
                  <label for="exampleInputEmail1">Customer Address</label>
                  {{Form::text('customer_address', isset($customer_address)?$customer_address: '', ['class' => 'form-control customer_address', 'placeholder' => 'Enter Address']  )}}
                  {{-- <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"> --}}
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Add/Update Stock</button>
              </div>
            
          </div>
          <!-- /.box -->

 

        </div>
        <div class="col-md-9">
            <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">
                  Please fill up the service details
              </h3>
              <div class="box-body">

                <table id="addmoretable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service Person</th>
                        <th>Service Type</th>
                        <th>Service Name</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Amount</th>
                        <th></th>

                    </tr>
                </thead>
                <tbody id="addedRows">
                    <tr id="1" class="row_1">
                        <td>
                            {{Form::select('service_person_id[]', $servicePersons, '', ['class' => 'form-control service_person_id', 'placeholder' => '--Select Person--'])}}
                        </td>
                        <td>{{Form::select('service_type_id[]', $serviceTypes, '', ['class' => 'form-control service_type_id', 'placeholder' => '--Select Service Type--'])}}</td>
                        <td class="service_names">{{Form::select('service_id[]', [], '', ['class' => 'form-control services', 'placeholder' => '--Service Name--'])}}</td>
                        <td class="service_price">{{Form::text('service_price[]', '0.00', ['class' => 'form-control price', 'style' => 'width:60px'])}}</td>
                        <td class="service_qty">{{Form::text('service_qty[]', '1', ['class' => 'form-control qty', 'style' => 'width:40px'])}}</td>
                        <td class="service_amount">{{Form::text('service_amount[]', '0.00', ['class' => 'form-control amount', 'style' => 'width:60px'])}}</td>
                        <td><a href="javascript:void(0)" class="addmore_xx btn btn-info btn-sm" onclick="addMoreRows(this.form)"><i class="glyphicon glyphicon-plus-sign "></i> Add</a></td>
                    </tr>
                </tbody>
            </table>


              </div>
            </div>
            </div>
        </div>
        <!--/.col (left) -->
      </div>
      {{Form::close()}}
      <!-- /.row -->
    </section>
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
    $('.overlay').hide();
    $(".admission_class").change(function(event){
        $('.overlay').show();
        var admission_class = $(this).val();
        //get-book-list-by-class-id
        $.ajax({
        type: "POST",
        url: "{{url('/')}}/get-student-list-by-class-id",
        data: { 
                "_token": "{{ csrf_token() }}",
                admission_class : admission_class,
                },
        dataType : 'html',
        cache: false,
        success: function(data){
            students = $.parseJSON(data);
            $('.admission_id')
                    .empty()
                    .append('<option selected="selected" value="">-Select Student -</option>');
            $.each(students, function(i, item) {
                $('.admission_id').append(
                    '<option value="'+i+'">'+item+'</option>'
                );
            });
            $('.overlay').hide();


            /* 
                Get Book List by Class 
            */

            $.ajax({
            type: "POST",
            url: "{{url('/')}}/get-book-list-by-class-id",
            data: { 
                    "_token": "{{ csrf_token() }}",
                    admission_class : admission_class,
                    },
            dataType : 'html',
            cache: false,
            success: function(data){
                console.log(data);
                students = $.parseJSON(data);
                $('.library_id')
                        .empty()
                        .append('<option selected="selected" value="">-Select Student -</option>');
                $.each(students, function(i, item) {
                    $('.library_id').append(
                        '<option value="'+i+'">'+item+'</option>'
                    );
                });
                $('.overlay').hide();
            }
            });



        }
        });
    });

    

    });
</script>

<script type="text/javascript">

var rowCount = 1;
function addMoreRows(frm) {
	rowCount ++;
	
    var recRow = '<tr id="'+rowCount+'" class="row_'+rowCount+'"><td>{{Form::select('service_person_id[]', $servicePersons, '', ['class' => 'form-control service_person_id', 'placeholder' => '--Select Person--'])}}</td><td>{{Form::select('service_type_id[]', $serviceTypes, '', ['class' => 'form-control service_type_id', 'placeholder' => '--Select Service Type--'])}}</td><td class="service_names">{{Form::select('service_id[]', [], '', ['class' => 'form-control services', 'placeholder' => '--Service Name--'])}}</td><td class="service_price">{{Form::text('service_price[]', '0.00', ['class' => 'form-control price', 'style' => 'width:60px'])}}</td><td class="service_qty">{{Form::text('service_qty[]', '1', ['class' => 'form-control', 'style' => 'width:40px'])}}</td><td class="service_amount">{{Form::text('service_amount[]', '0.00', ['class' => 'form-control amount', 'style' => 'width:60px'])}}</td><td><a href="javascript:void(0);" onclick="removeRow('+rowCount+');"><i class="glyphicon glyphicon-trash text-danger"></i></a></td></tr>'

	jQuery('#addedRows').append(recRow);

    //
    $('.service_type_id').change(function(){
        var currRow = $(this).parent().parent().attr('id');
        var servcie_type_id = $(this).val();
        
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/admin/getservcielistbytypeid",
            data: { 
                    "_token": "{{ csrf_token() }}",
                    servicetpid : servcie_type_id,
                    },
            dataType : 'html',
            cache: false,
            success: function(data){
                console.log(data);
                services = $.parseJSON(data);
                $('.row_'+currRow+' .services')
                        .empty()
                        .append('<option selected="selected" value="">-Select -</option>');
                $.each(services, function(i, item) {
                    $('.row_'+currRow+' .services').append(
                        '<option value="'+i+'">'+item+'</option>'
                    );
                });
                $('.overlay').hide();
            }
        });

    })
}
 
function removeRow(removeNum) {
	var didConfirm = confirm("Are you sure You want to delete");
    if (didConfirm == true) {
		jQuery('.row_'+removeNum).remove();
	}
}

$(document).ready(function(){
    $('.service_type_id').change(function(){
        var currRow = $(this).parent().parent().attr('id');
        var servcie_type_id = $(this).val();
        
        $.ajax({
            type: "POST",
            url: "{{url('/')}}/admin/getservcielistbytypeid",
            data: { 
                    "_token": "{{ csrf_token() }}",
                    servicetpid : servcie_type_id,
                    },
            dataType : 'html',
            cache: false,
            success: function(data){
                console.log(data);
                services = $.parseJSON(data);
                $('.row_'+currRow+' .services')
                        .empty()
                        .append('<option selected="selected" value="">-Select -</option>');
                $.each(services, function(i, item) {
                    $('.row_'+currRow+' .services').append(
                        '<option value="'+i+'">'+item+'</option>'
                    );
                });
                $('.overlay').hide();
            }
        });

    })
})
</script>
@endsection