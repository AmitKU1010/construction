@php
    $menuList = [];
    if(isset($view_share['menuList']) && count($view_share['menuList']) > 0){
        $menuList = $view_share['menuList'];
    }
    $f = Request::path();

@endphp

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            @php 
                $avatar = Helper::getAvatar(Auth::user()->id);
            @endphp
            @if(isset($avatar->avatar))
            {{ Html::image('public'.$avatar->avatar, 'User Image', ['class' => 'img-circle', 'height' => '25' , 'width' => '25']) }}
            @else
            {{ Html::image('public/no-user-icon.png', 'Please Upload a Image', ['title' => 'Please Upload a Image', 'class' => 'img-circle', 'height' => '25' , 'width' => '25']) }}
            @endif
        </div>
        <div class="pull-left info">
        <p>{{Auth::user()->name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      {{-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> --}}
  <!-- /Page Sidebar Header -->
  <!-- Sidebar Menu -->
  {{-- {!! Menu::get('DuctLabAdminMenu')->asUl(['class' => 'nav sidebar-menu']) !!} --}}
  <ul class="sidebar-menu" data-widget="tree">
      <!--Dashboard-->
      @foreach($menuList as $key => $menu)
          @php 
              $getChilds = Helper::getMenuChilds($menu['id']);
          @endphp
      <li class="@if($key == 0) {!! 'active' !!} @endif @if(isset($getChilds) && count($getChilds) > 0) {{'treeview'}} @endif" id="{{'parent-'.$menu['id']}}">
          @if(isset($getChilds) && count($getChilds) > 0)
              @php $expandIco = '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>'; @endphp
              
                  @php $allowedRoles1 = json_decode($menu['role_id'], true); @endphp
                     @if(in_array(Auth::user()->role_id, $allowedRoles1))
                     <a href="#" class="menu-dropdown">
                      <?php // print_r($menu['role_id'])   ?>
                       <?php // print_r($menu['role_id'])   ?>
                     @endif
                
              
          @else
              @php $expandIco = ''; @endphp
              <a href="{{url('/')}}/{{$menu['url']}}" class="">
          @endif
          @php $allowedRoles1 = json_decode($menu['role_id'], true); @endphp
                     @if(in_array(Auth::user()->role_id, $allowedRoles1))
                     <i class="menu-icon glyphicon glyphicon-{{ isset($menu['icon'])?$menu['icon']:'' }}"></i>
                  <span class="menu-text"> {{$menu['name']}} </span>
                  {!! $expandIco !!}
              </a>
                     @endif
                  
          @if(isset($getChilds) && count($getChilds) > 0)
              <ul class="treeview-menu" test="">
                  @foreach($getChilds as $key => $child)
                  @php $allowedRoles = json_decode($child['role_id'], true); @endphp
                      @if(Auth::user()->role_id > 2 && in_array(Auth::user()->role_id, $allowedRoles))
                      @section('scripts')
                      <script>
                          $(document).ready(function(){
                              //alert("hello---");
                              $('#'+'{{'parent-'.$menu['id']}}').addClass('active open');
                          });
                      </script>
                      @endsection
                          <li class="{{$child['parent_id']}}" data-test="TANMAYA">
                              <a href="{{url('/')}}/{{$child['url']}}">
                                  <span class="menu-text"><i class="fa fa-circle-o"></i> {{$child['name']}}</span>
                              </a>
                          </li>
                      @elseif(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                          <li class="{{$child['parent_id']}}">
                              <a href="{{url('/')}}/{{$child['url']}}">
                                  <span class="menu-text"><i class="fa fa-circle-o"></i> {{$child['name']}}</span>
                              </a>
                          </li>
                      @endif
                  @endforeach
              </ul>
          @endif
      </li>
      @endforeach
      <li class="dropdown-footer">
          <a href="{{ route('logout') }}"
              onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
              <i class="menu-icon glyphicon glyphicon-off"></i>
              <span class="menu-text"> <strong>Logout</strong> </span>
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
          </form>
      </li>
  </ul>
  <!-- /Sidebar Menu -->

    </section>
    <!-- /.sidebar -->
  </aside>