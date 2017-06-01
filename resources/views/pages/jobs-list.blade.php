@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							@if (! Auth::user()->isInstaller())
							<a href="/jobs/create" class="btn btn-primary btn-xs">Create Job</a>
							@endif
						</div>
					</div>
					<div class="ibox-content">
						@if(count($jobs))
							<table class="table table-striped">
								<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Start Time</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody class="table_body">
								@foreach($jobs as $job)
									<tr>
										<td>{{ $job->id }}</td>
										<td>{{ ucfirst($job->type) }} of {{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }} for {{ $job->client->name }}</td>
										<td>{{ $job->client->email }}</td>
										<td>
											@if ($job->start_time)
												{{ $job->start_time->format('d/M/Y g:ia') }}
											@else
												<span class="text-muted">N/A</span>
											@endif
										</td>
										<td>{{ ucfirst($job->status) }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/jobs/view/{{$job->id}}">View</a></li>
													<li><a href="/jobs/edit/{{$job->id}}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="deleteJob('{{$job->id}}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								@if ($jobs->total() > 25)
									<tr>
										<td colspan="7" align="right">
											{{$jobs->render()}}
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								@if (Auth::user()->isInstaller())
									<p>There are no jobs assigned to you.</p>
								@else
									<p>No jobs found in the system. Please <a href="/jobs/create">create</a> one.</p>
								@endif
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
		function deleteJob(job_id)
		{
			if (window.confirm('Delete Job?')) {
				$.ajax({
					url:'/jobs/delete',
					method:'post',
					data:{
						_token : '{{csrf_token()}}',
						job_id : job_id,
					},
					success: function(response){
						window.location.href = '/jobs';
					}
				})
			}
		}
	</script>

@endsection
