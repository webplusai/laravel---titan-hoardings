@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/quote_requests/create" class="btn btn-primary btn-xs">Create Quote Request</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/quote_requests">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Clients" class="input form-control" name="global_search" value="{{Request::get('global_search')}}" >
										<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
									</div>
								</div>
							</form>
						</div>
						@if (count($quote_requests))
							<table class="table table-striped">
								<thead>
								<tr>
									<th>ID</th>
									<th>Datetime Submitted</th>
									<th>Client Name</th>
									<th>Quote Type</th>
									<th>Tenancy</th>
									<th>Site Name</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody class="table_body">
								@foreach($quote_requests as $quote_request)
									<tr>
										<td>{{ $quote_request->id }}</td>
										<td>{{ $quote_request->updated_at }}</td>
										<td>{{ $quote_request->client->name }}</td>
										<td>{{ $quote_request->type }}</td>
										<td>
											@if ($quote_request->tenancy_name)
												{{ $quote_request->tenancy_name }}
											@elseif ($quote_request->tenancy_number)
												{{ $quote_request->tenancy_number }}
											@else
												N/A
											@endif
										</td>
										<td>{{ $quote_request->site_name }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/quote_requests/view/{{ $quote_request->id }}">View</a></li>
													<li><a href="/quote_requests/edit/{{ $quote_request->id }}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="deleteQuoteRequest('{{ $quote_request->id }}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								@if ($quote_requests->total() > 10)
									<tr>
										<td colspan="6" align="right">
											{{$quote_requests->render()}}
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No Client found in the system, please <a href="/quote_requests/create">create</a> one.</p>
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
		function deleteQuoteRequest(quote_request_id)
		{
			if (window.confirm('Delete Quote Request?')) {
				$.ajax({
					url:'/quote_requests/delete',
					method: 'post',
					data:{
						_token : '{{ csrf_token() }}',
						quote_request_id : quote_request_id
					},
					success: function (response) {
						window.location.href = '/quote_requests';
					},
				});
			}
		}
	</script>
@endsection
