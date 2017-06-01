@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/users/create" class="btn btn-primary btn-xs">Create User</a>
							<a href="/users/invite" class="btn btn-primary btn-xs">Invite User</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/users">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Users" class="input form-control" name="global_search" value="{{Request::get('global_search')}}" >
										<span class="input-group-btn">
											<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
										</span>
									</div>
								</div>
							</form>
						</div>
						@if(count($users)>0)
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Type</th>
										<th>Date Created</th>
										<th>Date Modified</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
								@foreach($users as $user)
									<tr>
										<td>{{ $user->first_name . ' ' . $user->last_name }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ ucfirst($user->type) }}</td>
										<td>{{ $user->created_at->format('d M Y') }}</td>
										<td>{{ $user->updated_at->format('d M Y') }}</td>
										<td>{{ $user->invitations->first() ? 'Pending' : 'Active' }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/users/view/{{$user->id}}">View</a></li>
													<li><a href="/users/edit/{{$user->id}}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="#" onclick="delete_user({{$user->id}});return false">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								@if ($users->total() > 10)
									<tr>
										<td colspan="6" align="right">
											{{$users->render()}}
										</td>
									</tr>
								@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No User found in the system, please <a href="/users/create">create</a> one.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="/js/modalform.js"></script>
	<script>
		function delete_user(id)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete User?',
					message: ''+
						'<form action="/users/delete/' + id + '" method="post">'+
							'<p>Are you sure you want to delete this user?</p>'+
							'{{ csrf_field() }}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete User',
							className: 'btn-danger'
						}
					}
				}
			});
		}
	</script>
@endsection