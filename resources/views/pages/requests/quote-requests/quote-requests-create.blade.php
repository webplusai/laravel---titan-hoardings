@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Create Quote Request</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="">
							{{csrf_field()}}
							<div class="form-group">
								<label class="col-lg-2 control-label">Client</label>
								<div class="col-lg-3 {{ $errors->has('client_id') ? ' has-error' : '' }}">
									<select name="client_id" class="form-control">
										<option value="">Please select</option>
										@foreach ($clients as $client)
											<option value="{{ $client->id }}">{{ $client->name }}</option>
										@endforeach
									</select>
									@if ($errors->has('client_id'))
										<span class="help-block"><strong>{{ $errors->first('client_id') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Product</label>
								<div class="col-lg-3 {{ $errors->has('product_id') ? ' has-error' : '' }}">
									<select name="product_id" class="form-control">
										<option value="">Please select</option>
										@foreach ($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
										@endforeach
									</select>
									@if ($errors->has('product_id'))
										<span class="help-block"><strong>{{ $errors->first('product_id') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Type</label>
								<div class="col-lg-3 {{ $errors->has('type') ? ' has-error' : '' }}">
									<select name="type" class="form-control">
										<option value="">Please select</option>
										<option value="1">Impact Rated</option>
										<option value="2">Kiosk</option>
										<option value="3">Std Wind Rated</option>
										<option value="4">Eco Wind Rated</option>
										<option value="5">Temporary Fence</option>
									</select>
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('tenancy_width') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Tenancy Width</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Tenancy Width" name="tenancy_width" class="form-control" value="{{ old('tenancy_width') }}">
									<span class="help-block"></span>
									@if ($errors->has('tenancy_width'))
										<span class="help-block"><strong>{{ $errors->first('tenancy_width') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('distance_from_lease_line') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Distance From Lease Line</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Distance From Lease Line" name="distance_from_lease_line" value="{{ old('distance_from_lease_line') }}">
									<span class="help-block"></span>
									@if ($errors->has('distance_from_lease_line'))
										<span class="help-block"><strong>{{ $errors->first('distance_from_lease_line') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('ceiling_height') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Ceiling Height</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Ceiling Height" name="ceiling_height" value="{{ old('ceiling_height') }}">
									<span class="help-block"></span>
									@if ($errors->has('ceiling_height'))
										<span class="help-block"><strong>{{ $errors->first('ceiling_height') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('dust_suppression') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Dust Suppression</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Dust Suppression" name="dust_suppression" class="form-control" value="{{ old('dust_suppression') }}">
									<span class="help-block"></span>
									@if ($errors->has('dust_suppression'))
										<span class="help-block"><strong>{{ $errors->first('dust_suppression') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('specified_wind_speed') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Specified Wind Speed</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Specified Wind Speed" name="specified_wind_speed" class="form-control" value="{{ old('specified_wind_speed') }}">
									<span class="help-block"></span>
									@if ($errors->has('specified_wind_speed'))
										<span class="help-block"><strong>{{ $errors->first('specified_wind_speed') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('panel_type') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Panel Type</label>
								<div class="col-lg-3 {{ $errors->has('type') ? ' has-error' : '' }}">
									<select name="panel_type" class="form-control">
										<option value="">Please select</option>
										<option value="1">12mm MDF</option>
										<option value="2">16mm WB</option>
										<option value="3">18mm ply</option>
										<option value="4">50mm EPS</option>
									</select>
									@if ($errors->has('panel_type'))
										<span class="help-block"><strong>{{ $errors->first('panel_type') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('double_door_type') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Double Door Type</label>
								<div class="col-lg-3 {{ $errors->has('type') ? ' has-error' : '' }}">
									<select name="double_door_type" class="form-control">
										<option value="">Please select</option>
										<option value="1">Hinged</option>
										<option value="2">Sliding</option>
									</select>
									@if ($errors->has('double_door_type'))
										<span class="help-block"><strong>{{ $errors->first('double_door_type') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('double_door_qty') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Double Door Qty</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Double Door Qty" name="double_door_qty" class="form-control" value="{{ old('double_door_qty') }}">
									<span class="help-block"></span>
									@if ($errors->has('double_door_qty'))
										<span class="help-block"><strong>{{ $errors->first('double_door_qty') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('tenancy_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Tenancy Name</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Tenancy Name" name="tenancy_name" class="form-control" value="{{ old('tenancy_name') }}">
									<span class="help-block"></span>
									@if ($errors->has('tenancy_name'))
										<span class="help-block"><strong>{{ $errors->first('tenancy_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('tenancy_number') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Tenancy Number</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Tenancy Number" name="tenancy_number" class="form-control" value="{{ old('tenancy_number') }}">
									<span class="help-block"></span>
									@if ($errors->has('tenancy_number'))
										<span class="help-block"><strong>{{ $errors->first('tenancy_number') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('site_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Site Name</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Site Name" name="site_name" class="form-control" value="{{ old('site_name') }}">
									<span class="help-block"></span>
									@if ($errors->has('site_name'))
										<span class="help-block"><strong>{{ $errors->first('site_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('notes') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Notes</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Notes" name="notes" class="form-control" value="{{ old('notes') }}">
									<span class="help-block"></span>
									@if ($errors->has('notes'))
										<span class="help-block"><strong>{{ $errors->first('notes') }}</strong></span>
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