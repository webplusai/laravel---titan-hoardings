@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>My Account</h5>
						<div class="ibox-tools">

						</div>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/my-account/{{ $user->id }}">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">First Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="first_name" class="form-control" value="{{ old('first_name',$user->first_name) }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Last Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="last_name" class="form-control" value="{{ old('last_name',$user->last_name) }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label class="col-lg-2 control-label">Password</label>
								<div class="col-lg-10"><input type="password" placeholder="Password" class="form-control" name="password" value="">
									@if ($errors->has('password'))
										<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Confirm Password</label>
								<div class="col-lg-10">
									<input type="password" class="form-control" placeholder="Password" name="password_confirmation">
									@if ($errors->has('password_confirmation'))
										<span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-white" type="submit">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		$(document).ready(function(){
			$('#date_of_birth').datepicker({
				format: "dd/mm/yyyy"
			});

			$('#type').change(function(){
				if ($('#type').val() == 'installer') {
					$('#installer').removeClass('hide').addClass('show');
				}
				else{
					$('#installer').removeClass('show').addClass('hide');
				}
			})
		})
	</script>
@endsection
