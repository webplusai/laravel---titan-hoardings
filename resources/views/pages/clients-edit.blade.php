@extends('layouts.default')
@section('css')
	<link href="/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit Client</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="">
							{{csrf_field()}}
							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Trading Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Trading Name" name="name" class="form-control" value="{{ old('name',$client->name) }}">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email',$client->email) }}">
									@if ($errors->has('email'))
										<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('billing_email') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Billing Email</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Billing Email" name="billing_email" value="{{ old('billing_email',$client->billing_email) }}">
									@if ($errors->has('billing_email'))
										<span class="help-block"><strong>{{ $errors->first('billing_email') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('abn') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">ABN/ACN</label>
								<div class="col-lg-10"><input type="text" placeholder="ABN/ACN" name="abn" class="form-control" value="{{ old('abn',$client->abn) }}">
									@if ($errors->has('abn'))
										<span class="help-block"><strong>{{ $errors->first('abn') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label"> Hoarding Materials </label>
								<div class="col-lg-10">
									<select class="hoarding-materials form-control" multiple="multiple" name="hoarding_material_ids[]">
										@foreach( $all_hoarding_materials as $material )
											<option value="{{ $material->id }}" @if (in_array($material->id, json_decode($client_hoarding_material_ids))) selected @endif> {{ $material->name }} </option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<div{!! $errors->has('billing_address') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Address</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Address" id="billing_address" name="billing_address" value="{{ old('billing_address', $client->billing_address) }}">
										@if ($errors->has('billing_address'))
											<span class="help-block"><strong>{{ $errors->first('billing_address') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('shipping_address') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Address</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Address" id="shipping_address" name="shipping_address" class="form-control" value="{{ old('shipping_address', $client->shipping_address) }}">
										@if ($errors->has('shipping_address'))
											<span class="help-block"><strong>{{ $errors->first('shipping_address') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('billing_suburb') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Suburb</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Suburb" id="billing_suburb" name="billing_suburb" value="{{ old('billing_suburb', $client->billing_suburb) }}">
										@if ($errors->has('billing_suburb'))
											<span class="help-block"><strong>{{ $errors->first('billing_suburb') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('shipping_suburb') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Suburb</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Suburb" id="shipping_suburb" name="shipping_suburb" class="form-control" value="{{ old('shipping_suburb', $client->shipping_suburb) }}">
										</div>
										@if ($errors->has('shipping_suburb'))
											<span class="help-block"><strong>{{ $errors->first('shipping_suburb') }}</strong></span>
										@endif
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('billing_state') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing State</label>
									<div class="col-lg-4">
										<select id="billing_state" name="billing_state" class="form-control">
											<option value=""></option>
											@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
												<option value="{{ $state }}"{{ old('billing_state', $client->billing_state) == $state ? ' selected' : '' }}>{{ $state }}</option>
											@endforeach
										</select>
										@if ($errors->has('billing_state'))
											<span class="help-block"><strong>{{ $errors->first('billing_state') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('shipping_state') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping State</label>
									<div class="col-lg-4">
										<select id="shipping_state" name="shipping_state" class="form-control">
											<option value=""></option>
											@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
												<option value="{{ $state }}"{{ old('shipping_state', $client->shipping_state) == $state ? ' selected' : '' }}>{{ $state }}</option>
											@endforeach
										</select>
										@if ($errors->has('shipping_state'))
											<span class="help-block"><strong>{{ $errors->first('shipping_state') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('billing_postcode') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Postcode</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Postcode" id="billing_postcode" name="billing_postcode" value="{{ old('billing_postcode', $client->billing_postcode) }}">
										@if ($errors->has('billing_postcode'))
											<span class="help-block"><strong>{{ $errors->first('billing_postcode') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('shipping_postcode') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Postcode</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Postcode" id="shipping_postcode" name="shipping_postcode" class="form-control" value="{{ old('shipping_postcode', $client->shipping_postcode) }}">
										@if ($errors->has('shipping_postcode'))
											<span class="help-block"><strong>{{ $errors->first('shipping_postcode') }}</strong></span>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-8 col-lg-2">
									<button type="button" class="btn btn-sm btn-primary" id="btn_same_as_billing">Same as Billing</button>
								</div>
							</div>
							<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Phone</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Phone" name="phone" value="{{ old('phone',$client->phone) }}">
									@if ($errors->has('phone'))
										<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Mobile</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Mobile" name="mobile" value="{{ old('mobile',$client->mobile) }}">
									@if ($errors->has('mobile'))
										<span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('fax') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Fax</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Fax" name="fax" value="{{ old('fax',$client->fax) }}">
									@if ($errors->has('fax'))
										<span class="help-block"><strong>{{ $errors->first('fax') }}</strong></span>
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
	<script src="/js/select2.full.min.js"> </script>
	<script>
		$(document).ready(function() {
			$(".hoarding-materials").select2();
		});
	</script>
	<script>
		$(document).ready(function () {
			// Get dom
			let billing_address     = $('#billing_address'),
			    billing_suburb      = $('#billing_suburb'),
			    billing_state       = $('#billing_state'),
			    billing_postcode    = $('#billing_postcode'),
			    shipping_address    = $('#shipping_address'),
			    shipping_suburb     = $('#shipping_suburb'),
			    shipping_state      = $('#shipping_state'),
			    shipping_postcode   = $('#shipping_postcode'),
			    btn_same_as_billing = $('#btn_same_as_billing');

			// Events
			btn_same_as_billing.on('click', function () {
				shipping_address.val(billing_address.val());
				shipping_suburb.val(billing_suburb.val());
				shipping_state.val(billing_state.val());
				shipping_postcode.val(billing_postcode.val());
			});
		});
	</script>
@endsection