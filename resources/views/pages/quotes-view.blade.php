@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-8">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title }}</h5>
						@if (Auth::user()->canManageQuote($quote))
						<div class="ibox-tools">
							<a href="/quotes/edit/{{ $quote->id }}" class="btn btn-default btn-xs">Edit Quote</a>
						</div>
						@endif
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-12">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#details" data-toggle="tab">Quote Details</a></li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="details">
										<br>
										<div class="row m-b-xs">
											<div class="col-sm-6">
												<label>Client:</label>
												{{ $quote->client->name }}
											</div>

											<div class="col-sm-6">
												<label>Description:</label>
												{{ $quote->description }}
											</div>

											<div class="col-sm-6">
												<label>Hoarding Type:</label>
												{{ $quote->hoardingType ? $quote->hoardingType->name : $quote->hoarding_type_other }}
											</div>

											<div class="col-sm-6">
												<label>Payment Terms:</label>
												@if ($quote->payment_terms) 
													yes
												@else
													no
												@endif
											</div>

											<div class="col-sm-6">
												<label>First Name:</label>
												{{ $quote->first_name }}
											</div>

											<div class="col-sm-6">
												<label>Last Name:</label>
												{{ $quote->last_name }}
											</div>

											<div class="col-sm-6">
												<label>Position:</label>
												{{ $quote->position }}
											</div>

											<div class="col-sm-6">
												<label>Address:</label>
												{{ $quote->address }}
											</div>

											<div class="col-sm-6">
												<label>Suburb:</label>
												{{ $quote->suburb }}
											</div>

											<div class="col-sm-6">
												<label>State:</label>
												{{ $quote->state }}
											</div>

											<div class="col-sm-6">
												<label>Postcode:</label>
												{{ $quote->postcode }}
											</div>

											<div class="col-sm-6">
												<label>Email:</label>
												{{ $quote->email }}
											</div>

											<div class="col-sm-6">
												<label>Tenancy Name:</label>
												{{ $quote->tenancy_name }}
											</div>

											<div class="col-sm-6">
												<label>Return Size:</label>
												{{ number_format($quote->return_size / 1000,2) }}
											</div>

											<div class="col-sm-6">
												<label>Panel Height:</label>
												{{ number_format($quote->panel_height / 1000,2) }}
											</div>

											<div class="col-sm-6">
												<label>Travel Charge:</label>
												{{ number_format($quote->travel_charge / 1000,2) }}
											</div>

											<div class="col-sm-6">
												<label>Lineal Meters:</label>
												{{ number_format($quote->lineal_meters / 1000,2) }}
											</div>

											<div class="col-sm-6">
												<label>Cost:</label>
												{{ number_format($quote->cost, 2) }}
											</div>

											<div class="col-sm-6">
												<label>Created At:</label>
												@if ($quote->created_at)
													{{ $quote->created_at->setTimezone($this->user->timezone)->format('d/M/Y g:ia') }}
												@else
													<span class="text-muted">N/A</span>
												@endif
											</div>

											<div class="col-sm-6">
												<label>Updated At:</label>
												@if ($quote->updated_at)
													{{ $quote->updated_at->setTimezone($this->user->timezone)->format('d/M/Y g:ia') }}
												@else
													<span class="text-muted">N/A</span>
												@endif
											</div>

											<div class="col-sm-6">
												<label>Expires At:</label>
												@if ($quote->expires_at)
													{{ $quote->expires_at->setTimezone($this->user->timezone)->format('d/M/Y g:ia') }}
												@else
													<span class="text-muted">N/A</span>
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection