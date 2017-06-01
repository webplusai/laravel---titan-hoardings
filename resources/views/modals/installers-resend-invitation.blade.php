@extends('layouts.modal')

@section('modal')

<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Resend Invitation</h4>
			</div>
			<form action="/installers/resend/{{ $installer->id }}" method="post">
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
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