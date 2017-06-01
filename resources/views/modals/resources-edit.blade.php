@extends('layouts.modal')

@section('modal')
	<div class="modal animated fadeIn">
		<div class="modal-dialog modal-lg">
			<form action="/resources/create" method="post" class="form-horizontal" enctype="multipart/form-data">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Resource</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-md-3 control-label">Name</label>
						<div class="col-md-8"><input type="text" name="name" class="form-control" value={{ old('name', $resource->name) }}></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Description</label>
						<div class="col-md-8"><input type="text" name="description" class="form-control" value={{ old('description', $resource->description) }}></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Type</label>
						<div class="col-md-8">
							<label class="radio-inline"><input type="radio" name="type" value="file" {{ old('type', $resource->type) == 'file' ? 'checked' : '' }}> File</label>
							<label class="radio-inline"><input type="radio" name="type" value="image" {{ old('type', $resource->type) == 'image' ? 'checked' : '' }}> Image</label>
							<label class="radio-inline"><input type="radio" name="type" value="video" {{ old('type', $resource->type) == 'video' ? 'checked' : '' }}> Video</label>
						</div>
					</div>
					<div class="form-group" id="file-row">
						<label class="col-md-3 control-label">File</label>
						<div class="col-md-8"><input type="file" name="file" class="form-control" value={{ old('file', $resource->url) }}></div>
					</div>
					<div class="form-group" id="image-row">
						<label class="col-md-3 control-label">Image</label>
						<div class="col-md-8"><input type="file" name="image" class="form-control" value={{ old('url', $resource->url) }}></div>
					</div>
					<div class="form-group" id="video-row">
						<label class="col-md-3 control-label">Video URL</label>
						<div class="col-md-8"><input type="text" name="video" class="form-control" value={{ old('video', $resource->url) }}></div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label">Products</label>
						<div class="col-md-8">
							@foreach ($products as $product)
								<div class="checkbox"><label><input type="checkbox" name="product_ids[]" value="{{ $product->id }}" {{ old('') }} {{ in_array($product->id, $product_resources) ? 'checked' : '' }}> {{ $product->name }}</label></div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
				{!! csrf_field() !!}
			</form>
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