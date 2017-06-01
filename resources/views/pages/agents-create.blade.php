@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Create Agent</h5>
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
						<form class="form-horizontal" method="post" action="">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Trading Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Trading Name" name="name" class="form-control" value="{{ old('name') }}">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
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

							<div class="form-group {{ $errors->has('billing_email') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Billing Email</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Billing Email" name="billing_email" value="{{ old('billing_email') }}">
									@if ($errors->has('billing_email'))
										<span class="help-block"><strong>{{ $errors->first('billing_email') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('abn') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">ABN/ACN</label>
								<div class="col-lg-10"><input type="text" placeholder="ABN/ACN" name="abn" class="form-control" value="{{ old('abn') }}">
									@if ($errors->has('abn'))
										<span class="help-block"><strong>{{ $errors->first('abn') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('shipping_address') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Address</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Address" id="shipping_address" name="shipping_address" class="form-control" value="{{ old('shipping_address') }}">
										@if ($errors->has('shipping_address'))
											<span class="help-block"><strong>{{ $errors->first('shipping_address') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('billing_address') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Address</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Address" id="billing_address" name="billing_address" value="{{ old('billing_address') }}">
										@if ($errors->has('billing_address'))
											<span class="help-block"><strong>{{ $errors->first('billing_address') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('shipping_suburb') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Suburb</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Suburb" id="shipping_suburb" name="shipping_suburb" class="form-control" value="{{ old('shipping_suburb') }}">
										@if ($errors->has('shipping_suburb'))
											<span class="help-block"><strong>{{ $errors->first('shipping_suburb') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('billing_suburb') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Suburb</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Suburb" id="billing_suburb" name="billing_suburb" value="{{ old('billing_suburb') }}">
										@if ($errors->has('billing_suburb'))
											<span class="help-block"><strong>{{ $errors->first('billing_suburb') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('shipping_state') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping State</label>
									<div class="col-lg-4">
										<select id="shipping_state" name="shipping_state" class="form-control">
											<option value=""></option>
											@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
												<option value="{{ $state }}"{{ old('shipping_state') == $state ? ' selected' : '' }}>{{ $state }}</option>
											@endforeach
										</select>
										@if ($errors->has('shipping_state'))
											<span class="help-block"><strong>{{ $errors->first('shipping_state') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('billing_state') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing State</label>
									<div class="col-lg-4">
										<select id="billing_state" name="billing_state" class="form-control">
											<option value=""></option>
											@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
												<option value="{{ $state }}"{{ old('billing_state') == $state ? ' selected' : '' }}>{{ $state }}</option>
											@endforeach
										</select>
										@if ($errors->has('billing_state'))
											<span class="help-block"><strong>{{ $errors->first('billing_state') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div{!! $errors->has('shipping_postcode') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Shipping Postcode</label>
									<div class="col-lg-4">
										<input type="text" placeholder="Shipping Postcode" id="shipping_postcode" name="shipping_postcode" class="form-control" value="{{ old('shipping_postcode') }}">
										@if ($errors->has('shipping_postcode'))
											<span class="help-block"><strong>{{ $errors->first('shipping_postcode') }}</strong></span>
										@endif
									</div>
								</div>
								<div{!! $errors->has('billing_postcode') ? ' class="has-error"' : '' !!}>
									<label class="col-lg-2 control-label">Billing Postcode</label>
									<div class="col-lg-4">
										<input type="text" class="form-control" placeholder="Billing Postcode" id="billing_postcode" name="billing_postcode" value="{{ old('billing_postcode') }}">
										@if ($errors->has('billing_postcode'))
											<span class="help-block"><strong>{{ $errors->first('billing_postcode') }}</strong></span>
										@endif
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-offset-8 col-lg-2">
									<button class="btn btn-sm btn-primary" onclick="copy_shipping_fields_to_billing(); return false">Same as Shipping</button>
								</div>
							</div>

							<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Phone</label>
								<div class="col-lg-10">
									<input type="text" class="input-phone form-control" placeholder="Phone" name="phone" value="{{ old('phone') }}">
									@if ($errors->has('phone'))
										<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Mobile</label>
								<div class="col-lg-10">
									<input type="text" class="input-mobile form-control" placeholder="Mobile" name="mobile" value="{{ old('mobile') }}">
									@if ($errors->has('mobile'))
										<span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('fax') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Fax</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Fax" name="fax" value="{{ old('fax') }}">
									@if ($errors->has('fax'))
										<span class="help-block"><strong>{{ $errors->first('fax') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('bank_acc_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Bank Account Name</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Bank Account Name" name="bank_acc_name" value="{{ old('bank_acc_name') }}">
									@if ($errors->has('bank_acc_name'))
										<span class="help-block"><strong>{{ $errors->first('bank_acc_name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('bank_acc_no') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Bank Account Number</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Bank Account Number" name="bank_acc_no" value="{{ old('bank_acc_no') }}">
									@if ($errors->has('bank_acc_no'))
										<span class="help-block"><strong>{{ $errors->first('bank_acc_no') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('bank_acc_bsb') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Bank BSB</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" placeholder="Bank BSB" name="bank_acc_bsb" value="{{ old('bank_acc_bsb') }}">
									@if ($errors->has('bank_acc_bsb'))
										<span class="help-block"><strong>{{ $errors->first('bank_acc_bsb') }}</strong></span>
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
		var input_phone = new Cleave('.input-phone', {
		    phone: true,
		    phoneRegionCode: 'AU'
		});

		var input_mobile = new Cleave('.input-mobile', {
		    phone: true,
		    phoneRegionCode: 'AU'
		});

		$('form').submit(function(event) {
			$('.input-phone').val(input_phone.getRawValue());
			$('.input-mobile').val(input_mobile.getRawValue());
		});

		function copy_shipping_fields_to_billing() {
			var shipping_address = $("#shipping_address").val();
			var shipping_suburb = $("#shipping_suburb").val();
			var shipping_state = $("#shipping_state").val();
			var shipping_postcode = $("#shipping_postcode").val();

			$("#billing_address").val(shipping_address);
			$("#billing_suburb").val(shipping_suburb);
			$("#billing_state").val(shipping_state);
			$("#billing_postcode").val(shipping_postcode);
		}
	</script>
@endsection
