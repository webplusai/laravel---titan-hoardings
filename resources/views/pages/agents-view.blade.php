@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/agents/edit/{{ $agent->id }}" class="btn btn-primary btn-xs">Edit Agent</a>
							<a href="javascript:show_modal('/agents/create-user/{{ $agent->id }}')" class="btn btn-primary btn-xs">Create User</a>
							@if (Auth::user()->isGlobalAdmin())
								<a href="#" onclick="add_user('{{ $agent->id }}');return false;" class="btn btn-primary btn-xs">Add User</a>
							@endif
						</div>
					</div>
					<div class="ibox-content">
						<div class="row m-b-xs">
							<div class="col-sm-12">
								<div class="col-sm-4">
									<label>Trading Name :</label>
									<span> {{ $agent->name }}</span>
								</div>
								<div class="col-sm-4">
									<label>Email :</label>
									<span> {{ $agent->email }}</span>
								</div>
								<div class="col-sm-4">
									<label>ABN/ACN :</label>
									<span> {{ $agent->abn }}</span>
								</div>
							</div>
						</div>
						<div class="row m-b-xs">
							<div class="col-sm-12">
								<div class="col-sm-4">
									<label>Billing Address :</label>
									<span> {{ $agent->billing_address }}</span>
								</div>
								<div class="col-sm-4">
									<label>Shipping Address :</label>
									<span> {{ $agent->shipping_address }}</span>
								</div>
								<div class="col-sm-4">
									<label>Phone(Main) :</label>
									<span> {{ $agent->phone }}</span>
								</div>
							</div>
						</div>
						<div class="row m-b-xs">
							<div class="col-sm-12">
								<div class="col-sm-4">
									<label>Phone(Mobile) :</label>
									<span> {{ $agent->mobile }}</span>
								</div>
								<div class="col-sm-4">
									<label>Email Billing :</label>
									<span> {{ $agent->billing_email }}</span>
								</div>
								<div class="col-sm-4">
									<label>Bank Account Name :</label>
									<span> {{ $agent->bank_acc_name }}</span>
								</div>
							</div>
						</div>
						<div class="row m-b">
							<div class="col-sm-12">
								<div class="col-sm-4">
									<label>Bank Account Number :</label>
									<span> {{ $agent->bank_acc_no }}</span>
								</div>
								<div class="col-sm-4">
									<label>Bank BSB:</label>
									<span> {{ $agent->bank_acc_bsb }}</span>
								</div>

							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="input-group">
									<input type="text" placeholder="Search User" class="input form-control" name="global_search">
									<span class="input-group-btn">
										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
									</span>
								</div>
							</div>
						</div>
						@if(count($agent->users))
							<table class="table table-striped">
								<thead>
								<tr>
									<th>ID</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Email</th>
									<th>Type</th>
									<th>More</th>
								</tr>
								</thead>
								<tbody class="table_body">
								@foreach($agent->users()->whereIn('type', ['agent-admin','agent-user'])->get() as $user)
									<tr>
										<td>{{ $user->id }}</td>
										<td>{{ $user->first_name }}</td>
										<td>{{ $user->last_name }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ $user->type }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="/users/edit/{{$user->id}}/{{$agent->id}}">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:;" onclick="delete_user({{ $user->id }}); return false;">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No Users found for this Agent</p>
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
		function delete_user(user_id)
		{
			if (window.confirm('Delete this user?')) {
				$.ajax({
					'url': "/users/delete/"+user_id,
					success: function(response){
						if(response.status==='success'){
							window.location.href = "/agents/view/"+'{{$agent->id}}';
						}
					}
				});
			}
		}
		@if (Auth::user()->isGlobalAdmin())
			function add_user(agent_id)
			{
				var html = ''+
					'<form action="/users/add-agent-user/{{ $agent->id }}" method="post" class="form-horizontal">'+
						'{{ csrf_field() }}'+
						'<div class="form-group">'+
							'<label class="col-lg-2 control-label">User</label>'+
							'<div class="col-lg-10">'+
								'<select class="form-control" name="user_id" id="user_id">'+
									'<option disabled >Select User</option>'+
									@foreach($users as $user)
										'<option value="{{ $user->id }}"> {{ addslashes($user->first_name." ".$user->last_name) }} </option>'+
									@endforeach
								'</select>'+
							'</div>'+
						'</div>'+
						'<div class="form-group">'+
							'<label class="col-lg-2 control-label">Type</label>'+
							'<div class="col-lg-10">'+
								'<select class="form-control" placeholder="Select Type" name="type" id="type">'+
									'<option value="">Select Type</option>'+
									'<option value="agent-admin">Agent Admin</option>'+
									'<option value="agent-user">Agent User</option>'+
								'</select>'+
							'</div>'+
						'</div>'+
					'</form>';

				modalform.dialog({
					bootbox: {
						message: html,
						title: 'Create User for Agent',
						buttons: {
							cancel: {
								label: 'Cancel',
								className: 'btn-default'
							},
							submit: {
								label: 'Submit',
								className: 'btn-primary'
							}
						}
					}
				});
			}
		@endif
	</script>
@endsection
