@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>{{ $title }}</h5>
						<div class="ibox-tools">
							<a href="/quote_requests/edit/{{ $quote_request->id }}" class="btn btn-default btn-xs">Edit Quote Request</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label">Client</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->client->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Product</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->product->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Type</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->type }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Tenancy Width</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->tenancy_width }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Distance From Lease Line</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->distance_from_lease_line }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Ceiling Height</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->ceiling_height }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Dust Suppression</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->dust_suppression }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Specified Wind Speed</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->specified_wind_speed }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Panel Type</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->panel_type }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Double Door Type</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->double_door_type }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Double Door Qty</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->double_door_qty }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Tenancy Name</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->tenancy_name }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Tenancy Number</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->tenancy_number }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Site Name</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->site_name }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Note</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $quote_request->notes }}</p>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

