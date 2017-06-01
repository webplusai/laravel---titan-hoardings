@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Create Job</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/jobs/create">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Job Type</label>
								<div class="col-lg-6">
									@foreach (['installation','modification','removal'] as $type)
										<label class="radio-inline"><input type="radio" name="type" value="{{ $type }}"{{ old('type') == $type ? ' checked': '' }}> {{ ucfirst($type) }}</label>
									@endforeach
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('client') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Client</label>
								<div class="col-lg-6">
									<input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}">
									<input type="hidden" name="client" value="{{ old('client') }}">
									@if ($errors->has('client'))
										<span class="help-block"><strong>{{ $errors->first('client') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-4">
									<a href="/clients/create" class="btn btn-default" target="_blank">Create New Client</a>
								</div>
							</div>

							<div class="form-group {{ $errors->has('internal_job_id') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Internal Job ID</label>
								<div class="col-lg-6">
									<input type="text" name="internal_job_id" class="form-control" value="{{ old('internal_job_id') }}">
									@if ($errors->has('internal_job_id'))
										<span class="help-block"><strong>{{ $errors->first('internal_job_id') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Shop Name/Unit No.</label>
								<div class="col-lg-6">
									<input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}">
									@if ($errors->has('shop_name'))
										<span class="help-block"><strong>{{ $errors->first('shop_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Address</label>
								<div class="col-lg-6">
									<input type="text" name="address" class="form-control" value="{{ old('address') }}">
									@if ($errors->has('address'))
										<span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('suburb') || $errors->has('state') || $errors->has('postcode') ? ' has-error' : '' }}">Suburb, State and Postcode</label>
								<div class="col-lg-2 {{ $errors->has('suburb') ? ' has-error' : '' }}">
									<input type="text" name="suburb" class="form-control" placeholder="Suburb" value="{{ old('suburb') }}">
									@if ($errors->has('suburb'))
										<span class="help-block"><strong>{{ $errors->first('suburb') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-2 {{ $errors->has('state') ? ' has-error' : '' }}">
									<select name="state" class="form-control">
										<option value="">Please select a State</option>
										@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
											<option value="{{ $state }}"{{ $state == old('state') ? ' selected' : '' }}>{{ $state }}</option>
										@endforeach
									</select>
									@if ($errors->has('state'))
										<span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-2 {{ $errors->has('postcode') ? ' has-error' : '' }}">
									<input type="text" name="postcode" class="form-control" placeholder="Postcode" value="{{ old('postcode') }}">
									@if ($errors->has('postcode'))
										<span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('num_doors') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Number of Doors</label>
								<div class="col-lg-2">
									<input type="number" name="num_doors" class="form-control" value="{{ old('num_doors') }}">
									@if ($errors->has('num_doors'))
										<span class="help-block"><strong>{{ $errors->first('num_doors') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('dust_panel_height') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Dust Suppression Height</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="dust_panel_height" class="form-control decimal-input" value="{{ old('dust_panel_height') }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('dust_panel_height'))
										<span class="help-block"><strong>{{ $errors->first('dust_panel_height') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('total_height') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Hoarding Panel Height</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="total_height" class="form-control decimal-input" value="{{ old('total_height') }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('total_height'))
										<span class="help-block"><strong>{{ $errors->first('total_height') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('total_length') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Total Length</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="total_length" class="form-control decimal-input" value="{{ old('total_length') }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('total_length'))
										<span class="help-block"><strong>{{ $errors->first('total_length') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('return_size') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Return Size</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="return_size" class="form-control decimal-input" value="{{ old('return_size') }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('return_size'))
										<span class="help-block"><strong>{{ $errors->first('return_size') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('material') || $errors->has('material_other') ? ' has-error' : '' }}">Material</label>
								<div class="col-lg-3 {{ $errors->has('material') ? ' has-error' : '' }}">
									<select name="material" class="form-control">
										<option value="">Please select</option>
										@foreach ($materials as $material)
											<option value="{{ $material->id }}"{{ $material->id == old('material') ? ' selected' : '' }}>{{ $material->name }}</option>
										@endforeach
										<option value="other"{{ old('material') == 'other' ? ' selected' : '' }}>Other</option>
									</select>
									@if ($errors->has('material'))
										<span class="help-block"><strong>{{ $errors->first('material') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-3 other {{ $errors->has('material_other') ? ' has-error' : '' }}">
									<input type="text" name="material_other" class="form-control" value="{{ old('material_other') }}" placeholder="Enter material">
									@if ($errors->has('material_other'))
										<span class="help-block"><strong>{{ $errors->first('material_other') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('hoarding_type') || $errors->has('hoarding_type_other') ? ' has-error' : '' }}">Hoarding Type</label>
								<div class="col-lg-3 {{ $errors->has('hoarding_type') ? ' has-error' : '' }}">
									<select name="hoarding_type" class="form-control">
										<option value="">Please select</option>
										@foreach ($hoarding_types as $type)
											<option value="{{ $type->id }}"{{ $type->id == old('hoarding_type') ? ' selected' : '' }}>{{ $type->name }}</option>
										@endforeach
										<option value="other"{{ old('hoarding_type') == 'other' ? ' selected' : '' }}>Other</option>
									</select>
									@if ($errors->has('hoarding_type'))
										<span class="help-block"><strong>{{ $errors->first('hoarding_type') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-3 other {{ $errors->has('hoarding_type_other') ? ' has-error' : '' }}">
									<input type="text" name="hoarding_type_other" class="form-control" value="{{ old('hoarding_type_other') }}" placeholder="Enter hoarding type">
									@if ($errors->has('hoarding_type_other'))
										<span class="help-block"><strong>{{ $errors->first('hoarding_type_other') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('start_date') || $errors->has('start_time') ? ' has-error' : '' }}">Start Date/Time</label>
								<div class="col-lg-3 {{ $errors->has('start_date') ? ' has-error' : '' }}">
									<div class="input-group date" data-provide="datepicker">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</div>
										<input type="text" name="start_date" class="form-control" value="{{ old('start_date') }}">
									</div>
									@if ($errors->has('start_date'))
										<span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-3 {{ $errors->has('start_time') ? ' has-error' : '' }}">
									<div class="input-group" data-provide="clockpicker" data-placement="top">
										<div class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</div>
										<input type="text" name="start_time" class="form-control" value="{{ old('start_time') }}">
									</div>
									@if ($errors->has('start_time'))
										<span class="help-block"><strong>{{ $errors->first('start_time') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Comments</label>
								<div class="col-lg-6">
								<textarea name="comments" class="form-control" rows="3">{{ old('comments') }}</textarea>
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

@section('scripts')
	<script src="/js/typeahead.js"></script>
	<script src="/js/handlebars.js"></script>
	<script>
		// Clients typeahead
		var clients = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			prefetch: {
				url: "/clients/typeahead",
			},
			remote: {
				url: '/clients/typeahead/%QUERY',
				wildcard: '%QUERY'
			}
		});

		$('input[name="client_name"]').typeahead({
			minLength: 0,
			highlight: true,
			limit: 9999,
			templates: {
				empty: '<div class="empty-message">(no clients found)</div>',
				suggestion: Handlebars.compile('<div><strong>@{{name}}</strong></div>')
			}
		},
		{
			name: 'clients',
			display: 'name',
			source: clients
		}).on('typeahead:select', function(event, suggestion) {
			console.log(suggestion.address);
			$('input[name="client"]').val(suggestion.id);
			$('input[name="address"]').val(suggestion.address);
			$('input[name="suburb"]').val(suggestion.suburb);
			$('select[name="state"]').val(suggestion.state);
			$('input[name="postcode"]').val(suggestion.postcode);
		}).bind('typeahead:render', function(e) {
			$('input[name="client_name"]').parent().find('.tt-selectable:first').addClass('tt-cursor');
		});

		$('.decimal-input').on('blur',function(){
			var number = $(this).val();

			if (number.substring(0,1) == '.') {
				$(this).val('0'+number);
			}
		});

		// When material or hoarding type is changed, show "other" box
		$('select[name="material"],select[name="hoarding_type"]').on('change', function() {
			if ($(this).val() == 'other') {
				$(this).closest('.form-group').find('.other').show().find('input').focus();
			} else {
				$(this).closest('.form-group').find('.other').hide();
			}
		}).trigger('change');

	</script>
@endsection
