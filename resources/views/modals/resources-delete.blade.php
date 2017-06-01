@extends('layouts.modal')

@section('modal')
<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Create Resource</h4>
			</div>
			<form action="/resources/delete/{{ $resource->id }}" method="post" class="form-horizontal" enctype="multipart/form-data">
				<p>Are you sure you want to delete resource {{ $resource->name }}?</p>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Delete Resource</button>
				</div>
				{!! csrf_field() !!}
			</form>
		</div>
	</div>
</div>
@endsection

@section('onsuccess')
	modal.modal('hide');
	location.reload();
@endsection