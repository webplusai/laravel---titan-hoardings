@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Create User</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-wrench"></i>
						</a>
						<ul class="dropdown-menu dropdown-user">
							<li><a href="#">Config option 1</a>
							</li>
							<li><a href="#">Config option 2</a>
							</li>
						</ul>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" method="post" action="/users/create">
						{{csrf_field()}}

						<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">First Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="first_name" class="form-control" value="{{ old('first_name') }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Last Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="last_name" class="form-control" value="{{ old('last_name') }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-lg-10">
							<input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">
								@if ($errors->has('email'))
									<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
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
						<div class='form-group{{ $errors->has('type') ? ' has-error' : '' }}'>
							<label class="col-lg-2 control-label">Type</label>
							<div class="col-lg-10">
								<select class='form-control' placeholder='Parent' name="type" id="type">
									<option disabled selected="selected">Select Type</option>
									@if (Auth::user()->isGlobalAdmin())
										<option value="global-admin"{{ old('type') == 'global-admin' ? ' selected' : '' }}>Global Admin</option>
									@endif
									@if (Auth::user()->isAgentAdmin() || Auth::user()->isGlobalAdmin())
										<option value="agent-admin"{{ old('type') == 'agent-admin' ? ' selected' : '' }}>Agent Admin</option>
									@endif
									<option value="agent-user"{{ old('type') == 'agent-user' ? ' selected' : '' }}>Agent User</option>
								</select>
								@if ($errors->has('type'))
									<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
								@endif
							</div>
						</div>
						<div class='form-group{{ $errors->has('agent_id') ? ' has-error' : '' }}' id="agent_id_div">
							<label class="col-lg-2 control-label">Agent</label>
							<div class="col-lg-10">
								<select class='form-control' placeholder='Parent' name="agent_id" id="agent_id">
									<option disabled selected="selected">Select Agent</option>
									@foreach($agents as $agent)
										<option value="{{$agent->id}}"{{ old('agent_id') == $agent->id ? ' selected' : '' }}>{{$agent->name}}</option>
									@endforeach
								</select>
								@if ($errors->has('agent_id'))
									<span class="help-block"><strong>{{ $errors->first('agent_id') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-sm btn-primary" type="submit">Create</button>
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
		$(document).ready(function () {
			let type = $('#type');

			$('#date_of_birth').datepicker({
				format: "dd/mm/yyyy"
			});
			type.change(function () {
				if (type.val() === 'installer') {
					$('#installer').removeClass('hide').addClass('show');
				} else {
					$('#installer').removeClass('show').addClass('hide');
				}

				if (type.val() === 'agent-admin' || type.val() === 'agent-user') {
					$('#agent_id_div').show();
				} else {
					$('#agent_id_div').hide();
				}
			}).trigger('change');
		})
	</script>
@endsection
