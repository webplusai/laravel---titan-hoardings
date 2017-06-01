@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Create Quote</h5>
					</div>
					<div class="ibox-content">
						@if ($errors->count())
							<div class="alert alert-danger">
								@foreach ($errors->all() as $message)
									{{ $message }}<br>
								@endforeach
							</div>
						@endif

						<form class="form-horizontal" method="post" action="/quotes/create">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="col-lg-2 control-label">Job Type</label>
								<div class="col-lg-6">
									@foreach (['installation','modification','removal'] as $job_type)
										<label class="radio-inline"><input type="radio" name="job_type" value="{{ $job_type }}"{{ old('job_type') == $job_type ? ' checked': '' }}> {{ ucfirst($job_type) }}</label>
									@endforeach
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Client</label>
								<div class="col-lg-2">
									<select name="client" class="form-control">
										<option value="">Please select a Client</option>
										@foreach ($clients as $client)
											<option value="{{ $client->id }}"{{ $client->id == old('client') ? ' selected' : '' }}>{{ $client->name }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Hoarding Type</label>
								<div class="col-lg-2">
									<select name="hoarding_type" class="form-control">
										<option value="">Please select a Hoarding Type</option>
										@foreach ($hoarding_types as $hoarding_type)
											<option value="{{ $hoarding_type->id }}"{{ $hoarding_type->id == old('hoarding_type') ? ' selected' : '' }}>{{ $hoarding_type->name }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Payment Terms</label>
								<div class="col-lg-2">
									<div class="checkbox">
										<input type="checkbox" name="payment_terms" value="1" {{ old('payment_terms') == 1 ? 'checked' : '' }}>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Client Details</label>
								<div class="col-lg-2">
									<input type="text" name="first_name" class="form-control" placeholder="First Name" value="{{ old('first_name') }}">
								</div>
								<div class="col-lg-2">
									<input type="text" name="last_name" class="form-control" placeholder="Last Name" value="{{ old('last_name') }}">
								</div>
								<div class="col-lg-2">
									<input type="text" name="position" class="form-control" placeholder="Position" value="{{ old('position') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Contact Email</label>
								<div class="col-lg-6">
									<input type="text" name="email" class="form-control" value="{{ old('email') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Tenancy Name</label>
								<div class="col-lg-6">
									<input type="text" name="tenancy" class="form-control" value="{{ old('tenancy') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Suburb, State and Postcode</label>
								<div class="col-lg-2">
									<input type="text" name="suburb" class="form-control" placeholder="Suburb" value="{{ old('suburb') }}">
								</div>
								<div class="col-lg-2">
									<select name="state" class="form-control">
										<option>Please select a State</option>
										@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
											<option value="{{ $state }}" {{ $state == old('state') ? 'selected' : '' }}>{{ $state }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-2">
									<input type="text" name="postcode" class="form-control" placeholder="Postcode" value="{{ old('postcode') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Job Description</label>
								<div class="col-lg-6">
								<textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Return Size</label>
								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" name="return_size" class="form-control decimal-input" value="{{ old('return_size') }}">
										<span class="input-group-addon">metres</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Panel Height</label>
								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" name="panel_height" class="form-control decimal-input" value="{{ old('panel_height') }}">
										<span class="input-group-addon">metres</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Lineal Meters</label>
								<div class="col-lg-6">
									<div class="input-group">
										<input type="text" name="lineal_meters" class="form-control decimal-input" value="{{ old('lineal_meters') }}">
										<span class="input-group-addon">metres</span>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Travel Charge</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="text" name="travel_charge" class="form-control decimal-input" value="{{ old('travel_charge') }}">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Total Cost</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="text" name="cost" class="form-control decimal-input" value="{{ old('cost') }}">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Quote Status</label>
								<div class="col-lg-6">
									<select name="status" class="form-control">
										<option value="">Please select a Status</option>
										<option value="Draft" {{ old('status') == 'Draft' ? ' selected' : '' }}>Draft</option>
										<option value="Quoted" {{ old('status') == 'Quoted' ? ' selected' : '' }}>Quoted</option>
										<option value="Accepted" {{ old('status') == 'Accepted' ? ' selected' : '' }}>Accepted</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Date</label>
								<div class="col-lg-6">
									<input type="text" name="date" class="date-input form-control" value="{{ old('date') }}">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-primary" type="submit">Create Job</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection