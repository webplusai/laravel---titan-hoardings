@extends('layouts.modal')

@section('modal')
	<div class="modal">
		<div class="modal-dialog modal-lg">
			<form action="/agents/create-user" method="post" class="modal-content" autocomplete="off">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Create User</h4>
				</div>
				<div class="modal-body form-horizontal">
					<div class="form-group">
						<label class="col-md-3 control-label">First Name</label>
						<div class="col-md-7"><input type="text" name="first_name" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Last Name</label>
						<div class="col-md-7"><input type="text" name="last_name" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Email</label>
						<div class="col-md-7"><input type="text" name="email" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Password</label>
						<div class="col-md-7"><input type="password" name="password" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Password Confirmation</label>
						<div class="col-md-7"><input type="password" name="password_confirmation" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Type</label>
						<div class="col-md-7">
							<select name="type" class="form-control">
								<option value="">Please select</option>
								<option value="agent-admin">Agent Admin</option>
								<option value="agent-user">Agent User</option>
							</select>
						</div>
					</div>
					<input type="hidden" name="agent_id" value="{{ $agent_id }}">
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Add Contact</button>
					</div>
					{{ csrf_field() }}
				</form>
			</div>
		</div>
	</div>
@endsection

@section('onsuccess')
modal.modal('hide');
location.reload();
@endsection