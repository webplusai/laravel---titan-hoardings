@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit User</h5>
						<div class="ibox-tools">

						</div>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/users/edit/{{$user->id}}">
							{{csrf_field()}}
							<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">First Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="first_name" class="form-control" value="{{ $user->first_name }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Last Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="last_name" class="form-control" value="{{ $user->last_name }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input type="email" class="form-control" placeholder="Email" name="email" value="{{ $user->email }}">
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
										@if ($user->isGlobalAdmin())
											<option value="global-admin"{{ old('type',$user->type) == 'global-admin' ? ' selected' : '' }}>Global Admin</option>
										@else
											<option value="agent-admin"{{ old('type',$user->type) == 'agent-admin' ? ' selected' : '' }}>Agent Admin</option>
											<option value="agent-user"{{ old('type',$user->type) == 'agent-user' ? ' selected' : '' }}>Agent User</option>
										@endif
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
											<option value="{{$agent->id}}"{{ ($user->agent_id != null) && ($user->agent_id == $agent->id) ? ' selected' : '' }}>{{$agent->name}}</option>
										@endforeach
									</select>
									@if ($errors->has('agent_id'))
										<span class="help-block"><strong>{{ $errors->first('agent_id') }}</strong></span>
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
