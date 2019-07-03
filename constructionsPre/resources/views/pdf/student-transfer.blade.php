@extends('layouts.pdf-layout')
@section('content')
<style>
    .fullpage{
        border: 1px solid black;
    padding: 10px;
    
    margin: 0 auto;
   
    background-repeat: no-repeat;
    background-attachment: fixed;
    /* background-position: -168px -182px; */
    background-size: 500px;
    }
    .keep-center{
        width: 70%;
    float: none;
    margin: 0 auto;
   
    }
</style>
@php $schoolInfo = Helper::schoolInfo(); @endphp
    <!-- title row -->
<div class="fullpage">
    <div class="row">
        <div class="col-xs-12 text-center">
            
        </div>
      <div class="col-xs-12 text-center">
        <h4 class="">
          <i class="fa fa-globe"></i> <b>{{$schoolInfo['name']}}</b>, {{$schoolInfo['address']}}, <br>{{$schoolInfo['phone']}}, {{$schoolInfo['email']}}
        </h4><br>
        <h4><strong>TRANSFER CERTIFICATE</strong></h4>
      </div>
      <div class="keep-center">
      <p>Parents while seeking admission must submit the Transfer Certificate from the last school attended in the format below and should be on the original letterhead of the leaving school.</p>
            <ul>
                <li>Complete the form as of the student’s last day in attendance on his/her school letterhead</li>
                <li>This form must be signed and stamped by a school official (blue ink preferred)</li>
                <li>The Original Transfer Certificate must be presented at the time of admission testing.</li>
            </ul>

            <ol>
            <li>Name of the student : <span class="lead"><u>{{$studentDetails['name']}}</u></span>, Father's Name : <span class="lead"><u>{{$studentDetails['father_name']}}</u></span>, Mother's Name : <span class="lead"><u>{{$studentDetails['mother_name']}}</u></span></li>
                <li>Natioanlity : <span class="lead"><u>{{'INDIAN'}}</u></span></li>
                <li>Date fo Birth: <span class="lead"><u>{{ Helper::changeDateFormat('d/m/Y',$studentDetails['dob'])}}</u></span></li>
                <li>Class to which he/she was admitted: <span class="lead"><u>{{ $studentDetails['admission_class']}}</u>,  Acaemic Year: <span class="lead"><u>{{ Helper::getAcademicYear('2017')}}</u></li>
                <li>Is Payment all clear ? : <span class="lead"><u>{{'yes'}}</u></span></li>
            </ol>
            <div class="row">
                <h3 class="">Headmaster/ Principal/ Director of school:</h3><br>
                <span class="">Name: <u>_______________________________________</u></span><br><br><br>
                <span class="">Signature: <u>_______________________________________</u></span><br><br><br>
            </div>
            <div class="row pull-right">
                School Stamp 
            </div>

      </div>

      <!-- /.col -->
    </div>
    <!-- info row -->
   
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
      
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    @if($pageType == 'HTML')
    <div class="row no-print">
      <div class="col-xs-12">
        <a href="invoice-print.html" target="_blank" class="btn btn-default" onclick="window.print()"><i class="fa fa-print"></i> Print</a>
        <button type="button" class="btn btn-success pull-right" onclick="window.close()"><i class="fa fa-credit-card"></i> Close
        </button>
      <a href="{{url('/')}}/transfer/{{$studentDetails['id']}}/pdf" class="btn btn-primary pull-right" style="margin-right: 5px;">
          <i class="fa fa-download"></i> Generate PDF
        </a>
      </div>
    </div>
    @endif
</div>  
  
@endsection