@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/service-requests/create" class="btn btn-primary btn-xs">Create Service Request</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/service-requests">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Service Requests" class="input form-control" name="global_search" value="{{Request::get('global_search')}}" >
										<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
									</div>
								</div>
							</form>
						</div>
						@if (count($service_requests))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>Datetime Submitted</th>
										<th>Client Name</th>
										<th>Request Type</th>
										<th>Phone Number</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
								@foreach($service_requests as $service_request)
									<tr>
										<td>{{ $service_request->id }}</td>
										<td>{{ $service_request->datetime_submitted }}</td>
										<td>{{ $service_request->client_name }}</td>
										<td>{{ $service_request->request_type }}</td>
										<td>{{ $service_request->phone_number }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/service_requests/view/{{ $service_request->id }}">View</a></li>
													<li><a href="/service_requests/edit/{{ $service_request->id }}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="deleteServiceRequest('{{ $service_request->id }}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								@if ($service_requests->total() > 10)
									<tr>
										<td colspan="6" align="right">
											{{$service_requests->render()}}
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No Client found in the system, please <a href="/service_requests/create">create</a> one.</p>
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
		function deleteServiceRequest(service_request_id)
		{
			if (window.confirm('Delete Service Request?')) {
				$.ajax({
					url:'/service_requests/delete',
					method: 'post',
					data:{
						_token : '{{ csrf_token() }}',
						service_request_id : service_request_id
					},
					success: function (response) {
						window.location.href = '/service_requests';
					},
				});
			}
		}
	</script>
@endsection
