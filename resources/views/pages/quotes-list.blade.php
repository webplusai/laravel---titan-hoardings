@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/quotes/create" class="btn btn-primary btn-xs">Create Quote</a>
						</div>
					</div>
					<div class="ibox-content">
						@if(session()->has('message'))
							<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
								<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
								<strong>{{ session('message') }}</strong>
							</div>
						@endif
						@if(count($quotes))
						<table class="table table-striped">
								<thead>
								<tr>
									<th>ID</th>
									<th>Description</th>
									<th>Email</th>
									<th>Created At</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody class="table_body">
								@foreach($quotes as $quote)
									<tr>
										<td>{{ $quote->id }}</td>
										<td><strong>{{ ucfirst($quote->description) }}</strong> of <strong>{{ $quote->hoardingType ? $quote->hoardingType->name : $quote->hoarding_type_other }}</strong> for <strong>{{ $quote->client->name }}</strong></td>
										<td>{{ $quote->client->email }}</td>
										<td>
											@if ($quote->created_at)
												{{ $quote->created_at->format('d/M/Y g:ia') }}
											@else
												<span class="text-muted">N/A</span>
											@endif
										</td>
										<td>{{ ucfirst($quote->status) }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/quotes/view/{{$quote->id}}">View</a></li>
													<li><a href="/quotes/edit/{{$quote->id}}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="deleteJob('{{$quote->id}}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							@if($quotes->lastPage() > 1)
								{{$quotes->render()}}
							@endif
						@else
							<div class="text-center">
								<p>No quotes found in the system. Please <a href="/quotes/create">create</a> one.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
