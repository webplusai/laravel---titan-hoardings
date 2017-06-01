@extends('layouts.modal')

@section('modal')
<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Add New Contact</h4>
			</div>
			<form action="/jobs/add-existing-contacts/{{ $job->id }}" method="post" class="form-horizontal">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th></th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Position</th>
							<th>Type</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($job->agent->contacts as $contact)
							<tr>
								<td><input type="checkbox" name="contact_ids[]" id="" value="{{ $contact->id }}"></td>
								<td> {{ $contact->first_name }} {{ $contact->last_name }}</td>
								<td>{{ $contact->email }}</td>
								<td>{{ $contact->phone }}</td>
								<td>{{ $contact->position }}</td>
								<td>{{ $contact->type }}</td>
							</tr>
						@endforeach
						{{ csrf_field() }}
					</tbody>
				</table>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Add Contact</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('onsuccess')
	modal.modal('hide');
	document.location = '/jobs/view/{{ $job->id }}';
@endsection