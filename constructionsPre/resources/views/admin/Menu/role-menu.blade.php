@extends('layouts.admin-dashboard')

@section('content')
<div class="page-body">
   <div class="row">
      <div class="col-lg-12 col-sm-12 col-xs-12">
       
         <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                {{-- Menu Managemnet Starts --}}
               <div class="row">
                  <div class="col-lg-6 col-sm-6 col-xs-12">
                     <div class="widget flat radius-bordered">
                        <div class="widget-header bg-blue">
                           <span class="widget-caption">{{$pageTitle}}</span>
                        </div>
                        <div class="widget-body">
                            @if ($errors->any())
                                <ul class="alert alert-danger" style="list-style:none">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif


                            @if(session()->has('message.level'))
                                <div class="alert alert-{{ session('message.level') }} alert-dismissible">
                                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                  <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
                                  {!! session('message.content') !!}
                                </div>
                              @endif
                           <div id="registration-form">
                              
                            {{ Form::open(['url' => 'manage/menu/save', 'method' => 'post', 'class' => '']) }}
                            {{ csrf_field() }}
                            {{ Form::hidden('id', isset($id) ? $id :'', []) }}
                                 <div class="form-group">
                                    <label for="exampleInputEmail2">Choose Parent Menu</label>
                                    <span class="input-icon icon-right">
                                        {{Form::select('parent_id', $parentMenus, isset($parent_id) ? $parent_id : '', ['class' => 'form-control', 'required'] )}}
                                    </span>
                                 </div>
                                 <div class="form-group">
                                    <label for="exampleInputEmail2">Menu Name</label>
                                    <span class="input-icon icon-right">
                                        {{Form::text('name', isset($name) ? $name : '', ['class' => 'form-control', 'required'])}}
                                    <i class="glyphicon glyphicon-user circular"></i>
                                    </span>
                                 </div>
                                 <div class="form-group">
                                    <label for="exampleInputEmail2">Menu Url</label>
                                    <span class="input-icon icon-right">
                                    {{Form::text('url', isset($url) ? $url : '', ['class' => 'form-control', 'required'])}}
                                    <i class="fa fa-envelope-o circular"></i>
                                    </span>
                                 </div>
                                 <div class="form-group">
                                    <label for="exampleInputEmail2">Menu Icon</label>
                                    <span class="input-icon icon-right">
                                    {{Form::select('icon', $faIcons, isset($icon) ? $icon : '', ['class' => 'form-control'] )}}
                                    </span>
                                 </div>
                                 <div class="form-group">
                                    <label for="exampleInputEmail2">Sequence</label>
                                    <span class="input-icon icon-right">
                                    {{Form::text('sort', isset($sort) ? $sort : '', ['class' => 'form-control'])}}
                                    <i class="fa fa-lock circular"></i>
                                    </span>
                                 </div>
                                 <div class="form-group">
                                    <div class="checkbox">
                                       <label>
                                        {{Form::checkbox('sort', isset($sort) ? $sort : '1', true, ['class' => 'colored-blue'])}}
                                       
                                       <span class="text"> Active</span>
                                       </label>
                                    </div>
                                 </div>
                                 {{Form::submit('Add Menu', ['class' => 'btn btn-blue'])}}
                                 {{-- <button type="submit" class="btn btn-blue">Register</button> --}}
                              {{ Form::close() }}
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 col-sm-6 col-xs-12">
                     <div class="widget flat radius-bordered">
                        <div class="widget-header bg-danger">
                           <span class="widget-caption">Menu List</span>
                        </div>
                        <div class="widget-body">
                           <div id="registration-form">
                              <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th>#</th>
                                          <th>Menu</th>
                                          <th>Url</th>
                                          <th>Parent?</th>
                                          <th>Action</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($menuListAll as $menu)
                                      <tr>
                                          <td>{{$count}}</td>
                                          <td>{{$menu['name']}}</td>
                                          <td>{{$menu['url']}}</td>
                                          <td>{!! ($menu['parent_id']=='0')?'<i class="glyphicon glyphicon-ok"></i>':'<i class="glyphicon glyphicon-remove"></i>' !!}</td>
                                          <td>
                                              <a href="{{url('/manage/menu')}}/{{$menu['id']}}" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-pencil"></i></a>
                                              <a href="{{url('/manage/menu/delete')}}/{{$menu['id']}}" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></a>
                                          </td>
                                      </tr>
                                      @php $count++ @endphp
                                    @endforeach
                                  </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               {{-- End of Manage Menu & Subemenu --}}
               
            </div>
         </div>
      </div>
   </div>
</div>
@endsection                