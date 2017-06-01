@extends('layouts.modal')

@section('modal')
<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Add New Contact</h4>
			</div>
			<form action="/contacts/create" method="post" class="form-horizontal">
				<input type="hidden" name="job_id" value="{{ $job->id }}">
				<input type="hidden" name="client_id" value="{{ $job->client->id }}">
				<br>
				<div class="form-group">
					<label class="col-md-2 control-label">Name</label>
					<div class="col-md-9"><input type="text" name="name" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Position</label>
					<div class="col-md-9"><input type="text" name="position" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Type</label>
					<div class="col-md-9">
						<select name="type" class="form-control">
							<option value="">Please select</option>
							<option value="Client">Client</option>
							<option value="Supplier">Supplier</option>
							<option value="Other">Other</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Email</label>
					<div class="col-md-9"><input type="text" name="email" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-md-2 control-label">Phone</label>
					<div class="col-md-9"><input type="text" name="phone" class="form-control"></div>
				</div>
				<div class="form-group">
					<label class="col-md-9 control-label"><input type="checkbox" name="client_associated">
					This contact is associated with {{ $job->client->name }}</label>
				</div>
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
	document.location = '/jobs/view/{{ $job->id }}';
@endsection