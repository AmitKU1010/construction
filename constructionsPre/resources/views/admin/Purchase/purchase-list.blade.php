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
            <!--   {{ Form::submit('Search', array('class' => 'btn btn-success')) }} -->
            </div>

          <!-- /.row -->
        </div>



            <div class="box-body">
              <div class=" table-responsive table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                      <th>#</th>
                      <th>Customer Name</th>
                      <th>Mobile number</th>
                      <th>Email Idr</th>
                      <th>Property Name</th>
                      <th>Registration Date</th>
                      <th>Agreement Date</th>
                      <th>Block Name</th>
                      <th>Floor Number</th>
                      <th>Flate Type</th>
                      <th>Flate Number</th>
                      <th>Sq Ft</th>
                      <th>Rate</th>
                      <th>Price</th>
                      <th>Down Payment</th>
                      <th>Total Paid</th>
                      <th>Purchase Date</th>
                      <th>Pay Remaining</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($datas as $key => $data)
                  <tr>
                    <td>{{ $datas->firstItem() + $key }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->mobile }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ isset($data->project_name) ? $data->project_name : 'Missing property' }}</td>
                    <td>{{ $data->registration_date }}</td>
                    <td>{{ $data->agreement_date }}</td>
                    <td>{{ $data->block_name }}</td>
                    <td>{{ $data->floor_number }}</td>
                    <td>{{ $data->flate_type }}</td>
                    <td>{{ $data->flate_number }}</td>
                    <td>{{ $data->sq_ft }}</td>
                    <td>{{ $data->rate }}</td>
                    <td>{{ $data->total }}</td>
                    <td>{{ $data->down_payment_for_room }}</td>
                    <td>{{ $data->total_paid }}</td>
                    <td>{{ $data->created_at!=null ? $data->created_at :$data->updated_at }}</td>
                    <td>
                       <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> -->
                        <input type="hidden" class="down_payment_for_room" value="{{ $data->down_payment_for_room }}"/>
                       <input type="hidden" class="total_paid" value="{{ $data->total_paid }}"/>
                       <input type="hidden" class="installment_price" value="{{ $data->installment_price }}"/>
                        <input type="hidden" class="purchase_id" value="{{ $data->purchasesId }}"/>
                       <input type="hidden" class="property_id" value="{{ $data->property_id }}"/>
                      <a class="forOpenModel" data-toggle="modal" data-target="#myModal" ><i class="fa fa-credit-card custom"></i></a>
                    </td>
                    
                    <td style="white-space: nowrap">
                      {{-- <a href="{{ url('/')}}/purchase/edit/{{ $data->id }}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a> --}}
                      <a href="{{ url('/')}}/admin/purchase/trash/{{ $data->purchasesId}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this diary details?');"><i class="fa fa-trash"></i></a> 

                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                </table>
              </div>
  
             

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div id="modelContent" class="modal-body">
         <input id="propery_id" type="hidden" name="propery_id">
          <table id="addmoretable" class="table table-bordered">
                <thead id="addedRowsServiceDetailhead" >
                     <tr>
                          <th>Payment type </th>
                          <th>Description</th>
                          <th>Total Price</th>
                          <th>Installment Price</th>
                      </tr>
                </thead>
                <tbody id="addedRowsServiceDetail">
                  <td >
                    <select id="payment_type" name="payment_type" class="form-control">
                      <option></option>
                       <option>Cheque</option>
                       <option> Online Transfer- NEFT or RTGS</option>
                       <option>UPI Apps</option>
                       <option>Credit Card or Debit Card</option>
                    </select>
                  </td>
                  <td >
                    <input type="text" class="form-control" id="payment_discription" name="payment_discription">
                  </td>
                  <td id="total_paid">
                  </td>
                  <td id="installment_price">
                   </td>
                </tbody>
                 <tfoot>
                   <tr><td>
                     <input type="button" class="btn btn-info " id="savePayment" value="Pay">
                </tfoot>
            </table>
<p id="paymentStatus" font="red"></p>
      <table style="display:none" id="paymentHistoryList" class="table table-bordered">
                <thead id="addedRowsServiceDetailhead" >
                     <tr>
                          <th>sl# </th>
                          <th>Payment type </th>
                          <th>Total Paid</th>
                          <th>Down Payment</th>
                          <th>Installment Price</th>
                          <th>Date</th>
                      </tr>
                </thead>
                <tbody id="paymentHistoryListBody">
                 
                </tbody>
      
            </table>

            <input type="hidden" name="installment_price" >
            <input type="hidden" name="down_payment_for_room">
          <input type="hidden" name="total_paid" >
         <input type="hidden" name="id" id="id" >
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div> 



           <!--  <div class="box-footer">
              <a href="" class="btn bg-maroon btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-close"></i> Clear Trash</a>
              <a href="" class="btn bg-purple btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-restore"></i> Restore Trash</a>
            </div> -->


            </div>
            <div class="box-header">
              <h3 class="box-title">Defaulter List</h3>
            </div>
            <div class="box-body">
              <div class=" table-responsive table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                      <th>#</th>
                      <th>Customer Name</th>
                      <th>Mobile number</th>
                      <th>Email Idr</th>
                      <th>Property Name</th>
                      <th>Block Name</th>
                      <th>Floor Number</th>
                      <th>Flate Type</th>
                      <th>Flate Number</th>
                      <th>Sq Ft</th>
                      <th>Rate</th>
                      <th>Price</th>
                      <th>Down Payment</th>
                      <th>Total Paid</th>
                      <th>Purchase Date</th>
                     
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($defaulterlist as $key => $data)
                  <tr>
                    <td>{{ $datas->firstItem() + $key }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->mobile }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ isset($data->project_name) ? $data->project_name : 'Missing property' }}</td>
                    <td>{{ $data->block_name }}</td>
                    <td>{{ $data->floor_number }}</td>
                    <td>{{ $data->flate_type }}</td>
                    <td>{{ $data->flate_number }}</td>
                    <td>{{ $data->sq_ft }}</td>
                    <td>{{ $data->rate }}</td>
                    
                    
                   
                    <td>{{ $data->total }}</td>
                    <td>{{ $data->down_payment_for_room }}</td>
                    <td>{{ $data->total_paid }}</td>
                    <td>{{ $data->created_at!=null ? $data->created_at :$data->updated_at }}</td>
                  </tr>
                  @endforeach
                
                  </tbody>
                </table>
              </div>
            <!-- /.box-body -->
          </div>

 {{ $datas->links() }}
    </section>
    <!-- /.content -->
  @endsection
  @section('extra-javascript')
  <script type = "text/javascript" language = "javascript">
  $(document).ready(function() {
    $(".forOpenModel").click(function () {
       var PropertyId = $(this).siblings('input.property_id').val();
        var purchase_id =  $(this).siblings('input.purchase_id').val();
        var total_paid =  $(this).siblings('input.total_paid').val()
        var   installment_price = $(this).siblings('input.installment_price').val()
        var   down_payment_for_room = $(this).siblings('input.down_payment_for_room').val()
        $('[name="down_payment_for_room"]').val(down_payment_for_room);
        $('#id').val(purchase_id);
        $('#propery_id').val(PropertyId);
        $('#total_paid').html(total_paid);
        $('[name="total_paid"]').val(total_paid);
        $('#installment_price').html(installment_price);
        $('[name="installment_price"]').val(installment_price);
        $('#addmoretable').show();
        $("#paymentStatus").hide();

         $.ajax({
          type: "POST",
          url: "{{url('/')}}/admin/ajax/purchase/payment-list",
          data: { 
                  "_token": "{{ csrf_token() }}",
                 property_id : PropertyId,
                 purchase_id : purchase_id

                },
          dataType : 'html',
          cache: false,
          success: function(data){
            $("#paymentHistoryList").show();
            
             $("#paymentHistoryListBody").html(data);
            }


    });
  });


  $("#savePayment").click(function () {

          var propertyId = $('input#propery_id').val()
          var totalPaid =$('[name="total_paid"]').val();
          var purchaseId = $('#id').val();
          var installmentPrice = $('[name="installment_price"]').val();
          var paymentType = $('[name="payment_type"]').val();
          var paymentDiscription = $('[name="payment_discription"]').val();
          var downPaymentForRoom = $('[name="down_payment_for_room"]').val();
      if(paymentDiscription=="")
      {
        paymentDiscription="Not Found";
      }
      // if(paymentDiscription=="" && paymentDiscription)
      // {
      //   alert("Please select Payment Type");
      // }
       // $('#id').val(purchase_id);
       // $('#propery_id').val(PrpropidopertyId);


         $.ajax({
          type: "POST",
          url: "{{url('/')}}/admin/ajax/purchase/payment",
          data: { 
                  "_token": "{{ csrf_token() }}",
                 propertyId : propertyId,
                 totalPaid : totalPaid,
                 purchaseId : purchaseId,
                 installmentPrice : installmentPrice,
                 paymentType : paymentType,
                 paymentDiscription : paymentDiscription,
                 down_payment_for_room : downPaymentForRoom
                },
         // dataType : 'html',
          cache: false,
          success: function(data){
            $('#addmoretable').hide();
            $("#paymentStatus").show();
            $("#paymentStatus").html(data);
           // props = $.parseJSON(data);
           // console.log(data);
            }


    });
  });


});
  </script>
  @endsection