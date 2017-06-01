@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/installers/invite" class="btn btn-primary btn-xs">Invite Installer</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/installers">
								<div class="col-sm-12">
									<div class="input-group">
										<input type="text" placeholder="Search Installers" class="input form-control" name="search" value="{{ Request::get('search') }}" >
										<span class="input-group-btn">
											<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
										</span>
									</div>
								</div>
							</form>
						</div>
						@if ($installers->total())
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
									@foreach ($installers as $installer)
										<tr>
											<td>{{ $installer->first_name . ' ' . $installer->last_name }}</td>
											<td>{{ $installer->email }}</td>
											<td>{{ ucfirst($installer->type) }}</td>
											<td>{{ $installer->created_at->format('d M Y') }}</td>
											<td>{{ $installer->updated_at->format('d M Y') }}</td>
											<td>{{ $installer->invitations->first() ? 'Pending' : 'Active' }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/installers/view/{{ $installer->id }}">View</a></li>
														<li><a href="javascript:show_modal('/installers/resend/{{ $installer->id }}')">Resend Invite</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_installer({{ $installer->id }});return false;">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
									@if ($installers->total() > 10)
										<tr>
											<td colspan="7" align="right">
												{{ $installers->render() }}
											</td>
										</tr>
									@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No installers found in the system. Please <a href="/installers/invite">invite</a> one.</p>
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
		function delete_installer(id)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete User?',
					message: ''+
						'<form action="/installers/delete/' + id + '" method="post">'+
							'<p>Are you sure you want to delete this installer?</p>'+
							'{{ csrf_field() }}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete Installer',
							className: 'btn-danger'
						}
					}
				}
			});
		}
	</script>
@endsection