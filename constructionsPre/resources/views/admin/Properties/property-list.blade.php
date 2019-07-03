@extends('layouts.default')

@section('content')
<section class="content">

  <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of all Properties</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="table-bootstrap">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Property Name</th>
                    <th>Details</th>
                    <th>Address</th>
                    <th>Total Price</th>
                    <th>Down Payment</th>
                    <th>Buildup Area</th>
                    <th>Added on</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($properties as $key => $property)
                  <tr>
                    <td>{{ $properties->firstItem() + $key }}</td>
                    <td>{{ $property->project_name }}</td>
                    <td>{{ $property->project_desc }}</td>
                    <td>{{ $property->address }}</td>
                    <td>{{ $property->total_price }}</td>
                    <td>{{ $property->down_payment }}</td>
                    <td>{{ $property->build_up_area }}</td>
                    <td>{{ date('d/M/Y', strtotime($property->created_at)) }}</td>
                    <td>
                      <div class="" style="white-space: nowrap">
                       {{--  <a href="" class="btn btn-info btn-xs" data-toggle="modal" data-target="#viewProperty_{{$property['id']}}"><i class="fa fa-eye"></i></a> --}}
                        <a href="{{ url('/')}}/admin/properties/edit/{{ $property['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                        <a href="{{ url('/')}}/admin/properties/trash/{{ $property['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this diary details?');"><i class="fa fa-trash"></i></a>
                      </div>

                      <!-- Modal -->
                      <div id="viewProperty_{{$property['id']}}" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                        
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Details of the Property</h4>
                              </div>
                              <div class="modal-body">
                              <h3>Details for {{$property['project_name']}}</h3>
                              {{-- 
                                GALLERY SRARTS  
                              --}}
                              <div class="row">
                                <div class="col-md-offset-4 col-md-4 col-md-offset-4">
                                  <div id="myCarousel_{{$property['id']}}" class="carousel slide" data-ride="carousel">
                                      <!-- Indicators -->
                                      <ol class="carousel-indicators">
                                        @foreach($property['gallery'] as $gallLoopKey => $gallery)
                                          <li data-target="#myCarousel_{{$property['id']}}" data-slide-to="{{$gallLoopKey}}" class="@if(isset($gallLoopKey) && $gallLoopKey == '0') {{'active'}} @endif"></li>
                                        @endforeach
                                      </ol>
                                    
                                      <!-- Wrapper for slides -->
                                      <div class="carousel-inner">
                                        @foreach($property['gallery'] as $gallKey => $gallery)
                                        <div class="item @if(isset($gallKey) && $gallKey == '0') {{'active'}} @endif ">
                                          <img src="{{url('/public/uploads/properties/square').'/'.$gallery['property_image']}}" alt="Los Angeles">
                                        </div>
                                        @endforeach
                                      </div>
                                    
                                      <!-- Left and right controls -->
                                      <a class="left carousel-control" href="#myCarousel_{{$property['id']}}" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                      </a>
                                      <a class="right carousel-control" href="#myCarousel_{{$property['id']}}" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                      </a>
                                    </div>
                                </div>
                              </div>

                                  <h4>Basic Details of Property</h4>
                                  <table id="example1" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                              <th>Property Name</th>
                                              <th>Details</th>
                                              <th>Address</th>
                                              <th>Total Price</th>
                                              <th>Down Payment</th>
                                              <th>Buildup Area</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @if(isset($properties) && count($properties) >0)
                                            @foreach($properties as $key => $property)
                                                <tr>
                                                  <td>{{ $property->project_name }}</td>
                                                  <td>{{ $property->project_desc }}</td>
                                                  <td>{{ $property->address }}</td>
                                                  <td>{{ $property->total_price }}</td>
                                                  <td>{{ $property->down_payment }}</td>
                                                  <td>{{ $property->build_up_area }}</td>
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
                                  <h4>Variant Details</h4>
                                 
                                  <table id="example1" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                              <th>Block Name</th>
                                              <th>Floor No.</th>
                                              <th>Flat No</th>
                                              <th>Flat Type</th>
                                              <th>Remarks</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @if(isset($property->variants) && count($property->variants) >0)
                                            @foreach($property->variants as $varKey => $variant)
                                                <tr>
                                                  <td>{{ $variant->block_name }}</td>
                                                  <td>{{ $variant->floor_no }}</td>
                                                  <td>{{ $variant->flat_no }}</td>
                                                  <td>{{ isset($variant->typeOfFlat->flat_type) ? $variant->typeOfFlat->flat_type : '' }}</td>
                                                  <td>{{ $variant->remarks }}</td>
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

                                  <h4>Installment Details</h4>
                            
                                  <table id="example1" class="table table-bordered table-striped">
                                      <thead>
                                          <tr>
                                              <th>Installment Number</th>
                                              <th>Price</th>
                                              <th>Remarks</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @if(isset($property->installments) && count($property->installments) >0)
                                            @foreach($property->installments as $instKey => $installment)
                                                <tr>
                                                  <td>{{ $installment->installment_no }}</td>
                                                  <td>{{ $installment->installment_price }}</td>
                                                  <td>{{ $installment->installment_desc }}</td>
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


                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                        
                          </div>
                        </div>
                        <!-- Modal Ends-->
                        
                    </td>
                  </tr>
                  @endforeach
                
                  </tbody>
                </table>
              </div>
              {{ $properties->links() }}


            <div class="box-footer">
              {{-- <a href="" class="btn bg-maroon btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-close"></i> Clear Trash</a>
              <a href="" class="btn bg-purple btn-flat margin" data-toggle="modal" data-target="#addSyllabus"><i class="fa fa-restore"></i> Restore Trash</a> --}}
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