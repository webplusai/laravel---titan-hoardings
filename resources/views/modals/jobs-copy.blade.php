@extends('layouts.modal')

@section('modal')

<script>
	$(function() {
	    rebind_clockpicker();
	});
</script>

<div class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4>Copy Job</h4>
			</div>
			<form action="/jobs/copy-job" method="post" class="form-horizontal">
				<div class="form-group">
					<label class="col-md-3 control-label">Job Type</label>
					<div class="col-md-8">
						<label class="radio-inline"><input type="radio" name="type" value="installation">Installation</label>
						<label class="radio-inline"><input type="radio" name="type" value="modification">Modification</label>
						<label class="radio-inline"><input type="radio" name="type" checked value="removal">Removal</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Start Date/Time</label>
					<div class="col-lg-4">
						<div class="input-group date" data-provide="datepicker">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</div>
							<input type="text" name="start_date" class="form-control">
						</div>
					</div>
					<div class="col-lg-4">
						<div class="input-group" data-provide="clockpicker">
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-time"></span>
							</div>
							<input type="text" name="start_time" class="form-control">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Internal Job ID</label>
					<div class="col-md-8">
						<input type="text" name="internal_job_id" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-3 control-label">Copy Installers?</label>
					<div class="col-md-8">
						<label class="radio-inline"><input type="radio" name="copy_installers" value="yes">Yes</label>
						<label class="radio-inline"><input checked type="radio" name="copy_installers" value="no">No</label>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
				<input type="hidden" name="job_id" value="{{ $job_id }}">
				{{ csrf_field() }}
				
			</form>
		</div>
	</div>
</div>
@endsection

@section('onsuccess')
	modal.modal('hide');
	document.location = '/jobs';
@endsection