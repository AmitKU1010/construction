<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ URL::asset('/') }}public/adminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
        <p>{{Auth::user()->name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="active">
          <a href="{{url('/')}}/admin/dashboard">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/customer/add">
            <i class="fa fa-dashboard"></i> <span>Customer</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/properties/add">
            <i class="fa fa-dashboard"></i> <span>Properties</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/supplier/add">
            <i class="fa fa-dashboard"></i> <span>Supplier</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/stock/add">
            <i class="fa fa-dashboard"></i> <span>Stock</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/firm/add">
            <i class="fa fa-dashboard"></i> <span>Firm</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/construction_site/add">
            <i class="fa fa-dashboard"></i> <span>Construction Site</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/item/category/add">
            <i class="fa fa-dashboard"></i> <span>Item Category</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/item/subcategory/add">
            <i class="fa fa-dashboard"></i> <span>Item Subcategory</span>
            
          </a>
          
        </li>
        <li class="active">
          <a href="{{url('/')}}/admin/item/item_master/add">
            <i class="fa fa-dashboard"></i> <span>Item Master</span>
            
          </a>
          
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>