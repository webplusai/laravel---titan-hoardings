@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Invite User</h5>
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
						<form class="form-horizontal" method="POST" action="">
							{{csrf_field()}}

							<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
								<label class="col-sm-2 control-label">First Name:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
								<label class="col-sm-2 control-label">Last Name:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
								<label class="col-sm-2 control-label">Email:</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="email" value="{{ old('email') }}">
									@if ($errors->has('email'))
										<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
									@endif
								</div>
							</div>
							<div class='form-group{{ $errors->has('type') ? ' has-error' : '' }}'>
								<label class="col-lg-2 control-label">Type</label>
								<div class="col-lg-10">
									<select class='form-control' placeholder='Parent' name="type">
										<option disabled selected="selected">Select Type</option>
										@if(Auth::user()->isGlobalAdmin())
											<option @if(old('type') == 'global-admin') selected @endif>Global Admin</option>
										@else
											<option @if(old('type') == 'agent-admin') selected @endif>Agent Admin</option>
											<option @if(old('type') == 'agent-user') selected @endif>Agent User</option>
											<option @if(old('type') == 'installer') selected @endif>Installer</option>
										@endif
									</select>
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>
							<hr>


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

