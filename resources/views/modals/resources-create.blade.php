@extends('layouts.modal')

@section('modal')
<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Create Resource</h4>
			</div>
			<div class="modal-body">
				<form action="/resources/create" method="post" class="form-horizontal" enctype="multipart/form-data">
					<div class="form-group">
						<label class="col-md-3 control-label">Name</label>
						<div class="col-md-8"><input type="text" name="name" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Description</label>
						<div class="col-md-8"><input type="text" name="description" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Type</label>
						<div class="col-md-8">
							<label class="radio-inline"><input type="radio" name="type" value="file"> File</label>
							<label class="radio-inline"><input type="radio" name="type" value="image"> Image</label>
							<label class="radio-inline"><input type="radio" name="type" value="video"> Video</label>
						</div>
					</div>
					<div class="form-group" id="file-row">
						<label class="col-md-3 control-label">File</label>
						<div class="col-md-8"><input type="file" name="file" class="form-control"></div>
					</div>
					<div class="form-group" id="image-row">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-8"><input type="file" name="image" class="form-control"></div>
					</div>
					<div class="form-group" id="video-row">
						<label class="col-md-3 control-label">Video URL</label>
						<div class="col-md-8"><input type="text" name="video" class="form-control"></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Products</label>
						<div class="col-md-8">
							@foreach ($products as $product)
								<div class="checkbox"><label><input type="checkbox" name="product_ids[]" value="{{ $product->id }}"> {{ $product->name }}</label></div>
							@endforeach
						</div>
					</div>
					{!! csrf_field() !!}
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('onload')
	$('input[name="type"]').on('change', function() {
		var type = $('input[name="type"]:checked').val();

		$('#file-row').hide();
		$('#image-row').hide();
		$('#video-row').hide();

		$('#' + type + '-row').show().find('input').focus();
	}).trigger('change');
@endsection

@section('onsuccess')
	modal.modal('hide');
	location.reload();
@endsection