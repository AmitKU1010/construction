@extends('layouts.default')

@section('content')
<section class="content">
        {{ Form::open(['url' => 'admin/item/item_master/save','files' => 'true' ,'enctype' => 'multipart/form-data']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
             
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the Item Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                <div class="form-group  col-sm-12">
                  <label for="exampleInputPassword1">Choose Category</label>
                  {{Form::select('item_categories_id', $item_categories_id, isset($item_categories_id)?$item_categories_id: '', ['class' => 'form-control','id'=>'item_categories_id', 'placeholder' => '-- Select Category']  )}}
                  <p class="help-block">
                    {{ $errors->has('item_categories_id') ? $errors->first('item_categories_id', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group  col-sm-12">
                  <label for="exampleInputPassword1">Choose Subcategory</label>
                  {{Form::select('item_subcategories_id', $item_subcategories_id, isset($item_subcategories_id)?$item_subcategories_id: '', ['class' => 'form-control','id'=>'item_subcategories_id', 'placeholder' => '-- Select Category']  )}}
                  <p class="help-block">
                    {{ $errors->has('item_subcategories_id') ? $errors->first('item_subcategories_id', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group col-sm-12">
                  <label for="exampleInputPassword1">Particular</label>
                  {{Form::text('particular', isset($particular)?$particular: '', ['class' => 'form-control', 'placeholder' => 'Enter particular']  )}}
                  <p class="help-block">
                    {{ $errors->has('particular') ? $errors->first('particular', ':message') : '' }}
                  </p>
                </div>
                <div class="form-group col-sm-12">
                  <label for="exampleInputPassword1">Unit</label>
                  {{Form::text('unit', isset($unit)?$unit: '', ['class' => 'form-control', 'placeholder' => 'Enter unit']  )}}
                  <p class="help-block">
                    {{ $errors->has('unit') ? $errors->first('unit', ':message') : '' }}
                  </p>
                </div>
               
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Item</button>
          </div>
              
            
          </div>
          <!-- /.box -->

 

        </div>

        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add Item Details</h3>
            </div>
            <!-- /.box-header -->
            
              
              <div class="box-body">
                  <table id="addmoretable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Category name</th>
                        <th>SubCategory name</th>
                        <th>Particular</th>
                        <th>Unit</th>
                        <th>Action</th>
                        
                </tr>
                </thead>
                <tbody id="addedRows">
                   <?php 
                        //dd($servicesList);
                     ?>
                @foreach($itemList as $key => $items)
               
                    <tr id="1" class="row_1">
                          <td>{{$key+1}}</td>
                          <td>{{$items->item_categories_id}}</td>
                          <td>{{$items->item_subcategories_id}}</td>
                          <td>{{$items->particular}}</td>
                          <td>{{$items->unit}}</td>

                          <td><a href="{{url('/admin/item/item_master/add')}}/{{$items['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/item/item_master/trash')}}/{{$items['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
              
              </div>
              <!-- /.box-body -->
          
            
            
            </div>
            
          </div>
         
        </div>

        <!--/.col (right) -->
      </div>
      {{Form::close()}}
      <!-- /.row -->
    </section>
@endsection    
