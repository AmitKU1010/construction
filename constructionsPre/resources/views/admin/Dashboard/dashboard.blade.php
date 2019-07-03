@extends('layouts.default')
@section('content')
<!-- Main content -->
<section class="content">
   <!-- Small boxes (Stat box) -->
   <div class="row">
      @foreach($dashlets as $dashlet)
      <div class="col-lg-3 col-xs-6">
         <!-- small box -->
         <div class="small-box bg-{{$dashlet['color']}}">
            <div class="inner">
               <h3>{{$dashlet['count']}}</h3>
               <p>{{$dashlet['name']}}</p>
            </div>
            <div class="icon">
               <i class="{{$dashlet['icon']}}"></i>
            </div>
            <a href="{{$dashlet['url']}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
         </div>
      </div>
      @endforeach
      <!-- ./col -->
      {{-- 
      <div class="col-lg-3 col-xs-6">
         <!-- small box -->
         <div class="small-box bg-green">
            <div class="inner">
               <h3>53<sup style="font-size: 20px">%</sup></h3>
               <p>Bounce Rate</p>
            </div>
            <div class="icon">
               <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
         </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
         <!-- small box -->
         <div class="small-box bg-yellow">
            <div class="inner">
               <h3>44</h3>
               <p>User Registrations</p>
            </div>
            <div class="icon">
               <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
         </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-xs-6">
         <!-- small box -->
         <div class="small-box bg-red">
            <div class="inner">
               <h3>65</h3>
               <p>Unique Visitors</p>
            </div>
            <div class="icon">
               <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
         </div>
      </div>
      <!-- ./col -->
   </div>
   <!-- /.row --> --}}
   <!-- Main row -->
   <div class="row">
   <!-- Left col -->
   <section class="col-lg-12 connectedSortable">
      <!-- Custom tabs (Charts with tabs)-->
      <div class="nav-tabs-custom">
         <!-- Tabs within a box -->
         <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i> Property Sold Dailly Statistics</li>
         </ul>
         <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <canvas id="totalPurchase" height="100"></canvas>
               </div>
            </div>
         </div>
         <!-- /.nav-tabs-custom -->
   </section>
   <section class="col-lg-12 connectedSortable">
      <!-- Custom tabs (Charts with tabs)-->
      <div class="nav-tabs-custom">
         <!-- Tabs within a box -->
         <ul class="nav nav-tabs pull-right">
            <li class="pull-left header"><i class="fa fa-inbox"></i> Dailly Statistics of Sales and Purchases</li>
         </ul>
         <div class="tab-content no-padding">
            <!-- Morris chart - Sales -->
            <div class="row">
               <div class="col-xs-12 col-sm-12 col-md-12">
                  <canvas id="totalPurchaseAndSales" height="100"></canvas>
               </div>
            </div>
         </div>
         <!-- /.nav-tabs-custom -->
   </section>
   <!-- right col -->
   </div>
   <!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
@section('extra-javascript')
<script type="text/javascript">
   function enquirymodal()
   {
     $('#modalContactForm').modal('show');
   }
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script>
   var ctx = document.getElementById("totalPurchase");
   var totalPurchase = new Chart(ctx, {
       type: 'line',
       data: {
           labels: {!! $totalDates !!},
           datasets: [{
               label: 'Total Amount(Rs) of Sales',
               fill: false,
               backgroundColor: 'rgb(31, 99, 96)',
               borderColor: 'rgb(31, 99, 96)',
               data: {{$totalPurchases}},
               borderWidth: 1,
           }]
       },
       options: {
           scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero:true
                   },
                   stacked: true
               }]
           }
       }
   });
   
   var ctx = document.getElementById("totalPurchaseAndSales");
   var totalPurchaseAndSales = new Chart(ctx, {
       type: 'line',
       data: {
           labels: ["January","February","March","April","May","June","July","August","September","October","November","December"],
           datasets: [{
               label: 'Total Amount(Rs) of Purchases',
               fill: false,
               backgroundColor: 'rgb(221, 75, 57) ',
               borderColor: 'rgb(221, 75, 57) ',
               data: [4,100,101,105,2,0,4,0,2,0,9,0],
               borderWidth: 1,
           },{
               label: 'Total Amount(Rs)  of Sales',
               fill: false,
               backgroundColor: 'rgb(243, 156, 16)',
               borderColor: 'rgb(243, 156, 16)',
               data: [156,22,66,66,99,24,0,0,7,0,89,0],
               borderWidth: 1,
           }]
       },
       options: {
           scales: {
               yAxes: [{
                   ticks: {
                       beginAtZero:true
                   },
                   stacked: true
               }]
           }
       }
   });
   
</script>
@endsection