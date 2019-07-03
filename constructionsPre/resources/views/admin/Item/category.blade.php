@extends('layouts.default')

@section('content')
<section class="content">
        {{ Form::open(['url' => 'admin/item/category/save','files' => 'true' ,'enctype' => 'multipart/form-data']) }} 
        {{ csrf_field() }}
        {{ Form::hidden('id', isset($id) ? $id :'', []) }} 
             
      <div class="row">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Please fill up the Category details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
                
                <div class="form-group col-sm-12">
                  <label for="exampleInputPassword1">Category Name</label>
                  {{Form::text('category_name', isset($category_name)?$category_name: '', ['class' => 'form-control', 'placeholder' => 'Enter a FIrm name']  )}}
                  <p class="help-block">
                    {{ $errors->has('category_name') ? $errors->first('category_name', ':message') : '' }}
                  </p>
                </div>
               
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-primary col-sm-12">{{isset($id) ? 'Update':'Add'}} Category</button>
          </div>
              
            
          </div>
          <!-- /.box -->

 

        </div>

        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Add Category Details</h3>
            </div>
            <!-- /.box-header -->
            
              
              <div class="box-body">
                  <table id="addmoretable" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Category name</th>
                        
                </tr>
                </thead>
                <tbody id="addedRows">
                   <?php 
                        //dd($servicesList);
                     ?>
                @foreach($categoryList as $key => $category)
               
                    <tr id="1" class="row_1">
                          <td>{{$key+1}}</td>
                          <td>{{$category->category_name}}</td>
                          <td><a href="{{url('/admin/item/category/add')}}/{{$category['id']}}" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>
                    <a href="{{url('/admin/item/category/trash')}}/{{$category['id']}}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure you want to delete this item?');"><i class="glyphicon glyphicon-trash"></i></a></td>
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
