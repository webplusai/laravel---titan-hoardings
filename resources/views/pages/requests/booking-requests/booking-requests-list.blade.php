@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/booking-requests/create" class="btn btn-primary btn-xs">Create Booking Request</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/booking-requests">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Booking Requests" class="input form-control" name="global_search" value="{{Request::get('global_search')}}" >
										<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
									</div>
								</div>
							</form>
						</div>
						@if (count($booking_requests))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>Datetime Submitted</th>
										<th>Client Name</th>
										<th>Tenancy</th>
										<th>Site Name</th>
										<th>Requested Date/Time</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
								@foreach($service_requests as $service_request)
									<tr>
										<td>{{ $service_request->id }}</td>
										<td>{{ $service_request->datetime_submitted }}</td>
										<td>{{ $service_request->client_name }}</td>
										<td>{{ $service_request->tenancy }}</td>
										<td>{{ $service_request->site_name }}</td>
										<td>{{ $service_request->requested_datetime }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/booking_requests/view/{{ $booking_request->id }}">View</a></li>
													<li><a href="/booking_requests/edit/{{ $booking_request->id }}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="deleteBookingRequest('{{ $booking_request->id }}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								@if ($booking_requests->total() > 10)
									<tr>
										<td colspan="6" align="right">
											{{$booking_requests->render()}}
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No Client found in the system, please <a href="/booking_requests/create">create</a> one.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		function deleteBookingRequest(booking_request_id)
		{
			if (window.confirm('Delete Booking Request?')) {
				$.ajax({
					url:'/booking_requests/delete',
					method: 'post',
					data:{
						_token : '{{ csrf_token() }}',
						booking_request_id : booking_request_id
					},
					success: function (response) {
						window.location.href = '/booking_requests';
					},
				});
			}
		}
	</script>
@endsection
