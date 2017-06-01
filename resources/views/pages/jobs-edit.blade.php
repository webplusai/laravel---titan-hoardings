@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit Job</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/jobs/edit/{{$job->id}}">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Job Type</label>
								<div class="col-lg-6">
									@foreach (['installation','modification','removal'] as $type)
										<label class="radio-inline"><input type="radio" name="type" value="{{ $type }}"{{ $job->type == $type ? 'checked': '' }}> {{ ucfirst($type) }}</label>
									@endforeach
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('client') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Client</label>
								<div class="col-lg-6">
									<input type="text" name="client_name" class="form-control" value="{{ old('client_name',$job->client->name) }}">
									<input type="hidden" name="client" value="{{ old('client', $job->client->id) }}">
									@if ($errors->has('client'))
										<span class="help-block"><strong>{{ $errors->first('client') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-4">
									<button type="button" class="btn btn-default btn-create-client">Create New Client</button>
								</div>
							</div>

							<div class="form-group {{ $errors->has('internal_job_id') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Internal Job ID</label>
								<div class="col-lg-6">
									<input type="text" name="internal_job_id" class="form-control" value="{{ old('internal_job_id', $job->internal_job_id) }}">
									@if ($errors->has('internal_job_id'))
										<span class="help-block"><strong>{{ $errors->first('internal_job_id') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('shop_name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Shop Name/Unit No.</label>
								<div class="col-lg-6">
									<input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $job->shop_name) }}">
									@if ($errors->has('shop_name'))
										<span class="help-block"><strong>{{ $errors->first('shop_name') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Address</label>
								<div class="col-lg-6">
									<input type="text" name="address" class="form-control" value="{{ old('address', $job->address) }}">
									@if ($errors->has('address'))
										<span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('suburb') || $errors->has('state') || $errors->has('postcode') ? ' has-error' : '' }}">Suburb, State and Postcode</label>
								<div class="col-lg-2 {{ $errors->has('suburb') ? ' has-error' : '' }}">
									<input type="text" name="suburb" class="form-control" value="{{ old('suburb', $job->suburb) }}">
									@if ($errors->has('suburb'))
										<span class="help-block"><strong>{{ $errors->first('suburb') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-2 {{ $errors->has('state') ? ' has-error' : '' }}">
									<select name="state" class="form-control">
										<option value="">Please select</option>
										@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
											<option value="{{ $state }}"{{ $state == old('state',$job->state) ? 'selected' : '' }}>{{ $state }}</option>
										@endforeach
									</select>
									@if ($errors->has('state'))
										<span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-2 {{ $errors->has('postcode') ? ' has-error' : '' }}">
									<input type="text" name="postcode" class="form-control" value="{{ old('postcode', $job->postcode) }}">
									@if ($errors->has('postcode'))
										<span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('num_doors') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Number of Doors</label>
								<div class="col-lg-2">
									<input type="text" name="num_doors" class="form-control" value="{{ old('num_doors', $job->num_doors) }}">
									@if ($errors->has('num_doors'))
										<span class="help-block"><strong>{{ $errors->first('num_doors') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('dust_panel_height') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Dust Suppression Height</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="dust_panel_height" class="form-control decimal-input" value="{{ old('dust_panel_height', $job->dust_panel_height / 1000) }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('dust_panel_height'))
										<span class="help-block"><strong>{{ $errors->first('dust_panel_height') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group {{ $errors->has('total_height') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Total Height</label>
								<div class="col-lg-2">
									<div class="input-group">
										<input type="number" step="any" name="total_height" class="form-control decimal-input" value="{{ old('total_height', $job->total_height / 1000) }}">
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
										<input type="number" step="any" name="total_length" class="form-control decimal-input" value="{{ old('total_length', $job->total_length / 1000) }}">
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
										<input type="number" step="any" name="return_size" class="form-control decimal-input" value="{{ old('return_size', $job->return_size / 1000) }}">
										<span class="input-group-addon">metres</span>
									</div>
									@if ($errors->has('return_size'))
										<span class="help-block"><strong>{{ $errors->first('return_size') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label {{ $errors->has('material') || $errors->has('material_other') ? ' has-error' : '' }}">Material</label>
								<div class="col-lg-3  {{ $errors->has('material') ? ' has-error' : '' }}">
									<select name="material" class="form-control">
										<option value="">Please select</option>
										@foreach ($materials as $material)
											<option value="{{ $material->id }}"{{ $material->id == old('material', $job->material_id) ? 'selected' : '' }}>{{ $material->name }}</option>
										@endforeach
										<option value="other"{{ $job->material_id == null ? ' selected' : '' }}>Other</option>
									</select>
									@if ($errors->has('material'))
										<span class="help-block"><strong>{{ $errors->first('material') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-3 other {{ $errors->has('material_other') ? ' has-error' : '' }}">
									<input type="text" name="material_other" class="form-control" value="{{ old('material_other',$job->material_other) }}" placeholder="Enter material">
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
											<option value="{{ $type->id }}"{{ $type->id == old('hoarding_type', $job->hoarding_type_id) ? 'selected' : '' }}>{{ $type->name }}</option>
										@endforeach
										<option value="other"{{ $job->hoarding_type_id == null ? ' selected' : '' }}>Other</option>
									</select>
									@if ($errors->has('hoarding_type'))
										<span class="help-block"><strong>{{ $errors->first('hoarding_type') }}</strong></span>
									@endif
								</div>
								<div class="col-lg-3 other {{ $errors->has('hoarding_type_other') ? ' has-error' : '' }}">
									<input type="text" name="hoarding_type_other" class="form-control" value="{{ old('hoarding_type_other', $job->hoarding_type_other) }}" placeholder="Enter hoarding type">
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
										<input type="text" name="start_date" class="form-control" value="{{ old('start_date', $job->start_time ? $job->start_time->format('d/m/Y') : '') }}">
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
										<input type="text" name="start_time" class="form-control" value="{{ old('start_time', $job->start_time ? $job->start_time->format('h:iA') : '') }}">
									</div>
									@if ($errors->has('start_time'))
										<span class="help-block"><strong>{{ $errors->first('start_time') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Comments</label>
								<div class="col-lg-6">
								<textarea name="comments" class="form-control" rows="3">{{ old('comments',$job->comments) }}</textarea>
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-primary" type="submit">Update</button>
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
	<script>
		// Clients typeahead
		var clients = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: '/clients/typeahead/%QUERY',
				wildcard: '%QUERY'
			}
		});

		$('input[name="client_name"]').typeahead(null, {
			name: 'clients',
			display: 'name',
			source: clients,
			limit: 9999,
			templates: {
				empty: '<div class="empty-message">(no clients found)</div>'
			}
		}).on('typeahead:select', function(event, suggestion) {
			$('input[name="client"]').val(suggestion.id);
		});

		$('.decimal-input').on('blur',function(){
			var number = $(this).val();

			if (number.substring(0,1) == '.') {
				$(this).val('0'+number);
			}
		});

		// Modal for creating client
		$('.btn-create-client').on('click', function() {
			bootbox.dialog({
				title: 'Create New Client',
				message: ''+
					'<form action="/clients/create" method="post" class="form-horizontal">'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Name</label>'+
							'<div class="col-md-9"><input type="text" name="name" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Email</label>'+
							'<div class="col-md-9"><input type="text" name="email" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Billing Email</label>'+
							'<div class="col-md-9"><input type="text" name="billing_email" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Phone</label>'+
							'<div class="col-md-9"><input type="text" name="phone" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Mobile</label>'+
							'<div class="col-md-9"><input type="text" name="mobile" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Fax</label>'+
							'<div class="col-md-9"><input type="text" name="fax" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Size</label>'+
							'<div class="col-md-9"><input type="text" name="size" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">ABN</label>'+
							'<div class="col-md-9"><input type="text" name="abn" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Billing Address</label>'+
							'<div class="col-md-9"><input type="text" name="billing_address" class="form-control"></div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-md-3 control-label">Shipping Address</label>'+
							'<div class="col-md-9"><input type="text" name="shipping_address" class="form-control"></div>'+
						'</div>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Client',
						className: 'btn-primary',
						callback: function() {
							$('.modal form').trigger('submit');
							return false;
						}
					}
				}
			});

			$('.modal form').on('submit', function(event) {
				event.preventDefault();

				$('.modal-footer .text-danger').remove();
				$('.modal-footer button').attr('disabled','disabled');

				$.ajax({
					url: '/clients/create',
					method: 'post',
					data: $(this).serialize(),
					success: function(data, status, jqxhr) {
						$('input[name="client_name"]').val($('input[name="name"]').val());
						$('input[name="client"]').val(data.id);
						bootbox.hideAll();
					},
					error: function(jqxhr, status, error) {
						if (jqxhr.status == 422) {
							var field = Object.keys(jqxhr.responseJSON)[0];
							error = jqxhr.responseJSON[field][0];
						}

						$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').text(error));
						$('.modal-footer button').removeAttr('disabled');
					}
				});
			});
		});

		// When material or hoarding type is changed, show "other" box
		$('select[name="material"],select[name="hoarding_type"]').on('change', function() {
			if ($(this).val() == 'other') {
				$(this).closest('.form-group').find('.other').show().find('input').focus();
			} else {
				$(this).closest('.form-group').find('.other').hide();
			}
		}).trigger('change');

		$('select[name="material"],select[name="hoarding_type"]').change();
	</script>
@endsection
