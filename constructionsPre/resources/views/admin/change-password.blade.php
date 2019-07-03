@extends('layouts.default')
@section('content')
<section class="content">
   <div class="row">
	   {{ Form::open(['url' => 'admin/change-password', 'files' => true]) }} 
	   {{ csrf_field() }}
	 <div class="col-md-6">

	 	@if(session()->has('message.level'))
          <div class="alert alert-{{ session('message.level') }} alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> {{ ucfirst(session('message.level')) }}!</h4>
            {!! session('message.content') !!}
          </div>
        @endif

	 	<div class="form-group  {{ $errors->has('old_password') ? 'has-error' : 'no-error' }}">

	 		<label for="old_password">Old Password</label>
	 		<input type="password" name="old_password" id="old_password" class="form-control"> 	
	 		 	
	 	</div>
	 	<div class="form-group {{ $errors->has('password') ? 'has-error' : 'no-error' }}">
	 		<label for="password">Password</label>
	 		<input type="password" name="password" id="password" class="form-control"> 	
	 		
	 	</div>
	 	<div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : 'no-error' }}">
	 		<label for="password_confirmation">Confirm Password</label>
	 		<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">	
	 	</div>
	     <div class="box-footer">
            {{ Form::submit('Submit', array('class' => 'btn btn-success btn-sm')) }}
            {{ Form::reset('Reset', array('class' => 'btn btn-warning btn-sm', 'onclick' => 'resetForm()')) }}
         </div>
	 	{{ Form::close() }}
	</div>
</div>
</section>
@endsection
@section('extra-javascript')
</script>
@endsection