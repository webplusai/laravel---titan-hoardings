@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-8">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title }}</h5>
						@if (Auth::user()->canManageJob($job))
						<div class="ibox-tools">
							<a href="/jobs/edit/{{ $job->id }}" class="btn btn-default btn-xs">Edit Job</a>
							<a onclick="javascript:show_modal('/jobs/copy-job/{{ $job->id }}')" class="btn btn-default btn-xs">Copy Job</a>
							<a href="/jobs/sqc/{{ $job->id }}" target="_blank" class="btn btn-default btn-xs">Export SQC</a>
						</div>
						@endif
					</div>
					<div class="ibox-content">
						<div class="row">
							<div class="col-sm-12">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#details" data-toggle="tab">Job Details</a></li>
									<li><a href="#people" data-toggle="tab">People</a></li>
									<li><a href="#images" data-toggle="tab">Images</a></li>
									<li><a href="#documents" data-toggle="tab">Documents</a></li>
									<li><a href="#jsra" data-toggle="tab">JSRA</a></li>
									<li><a href="#qc" data-toggle="tab">QC</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane active" id="details">
										<br>
										<div class="row m-b-xs">

											<div class="col-sm-6">
												<label>Client:</label>
												{{ $job->client->name }}
											</div>

											<div class="col-sm-6">
												<label>Shop Name/Unit No.:</label>
												{{ $job->shop_name }}
											</div>

											<div class="col-sm-6">
												<label>Address:</label>
												{{ $job->address }}
											</div>

											<div class="col-sm-6">
												<label>Suburb:</label>
												{{ $job->suburb }}
											</div>

											<div class="col-sm-6">
												<label>State: </label>
												{{$job->state}}
											</div>

											<div class="col-sm-6">
												<label>Postcode:</label>
												{{ $job->postcode }}
											</div>

											<div class="col-sm-6">
												<label>Number of Doors:</label>
												{{ $job->num_doors }}
											</div>

											<div class="col-sm-6">
												<label>Dust Suppression Height:</label>
												{{ number_format($job->dust_panel_height / 1000,2) }} meters
											</div>

											<div class="col-sm-6">
												<label>Total Height:</label>
												{{ number_format($job->total_height / 1000,2) }} meters
											</div>

											<div class="col-sm-6">
												<label>Return size:</label>
												{{ number_format($job->return_size / 1000,2) }} meters
											</div>

											<div class="col-sm-6">
												<label>Total Length:</label>
												{{ number_format($job->total_length / 1000,2) }} meters
											</div>

											<div class="col-sm-6">
												<label>Material:</label>
												{{ $job->material ? $job->material->name : $job->material_other}}
											</div>

											<div class="col-sm-6">
												<label>Hoarding Type:</label>
												{{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }}
											</div>

											<div class="col-sm-6">
												<label>Start Date/Time:</label>
												{{ $job->start_time ? $job->start_time->format('l, j F Y g:ia') : '' }}
											</div>

											<div class="col-sm-6">
												<label>Internal Job ID:</label>
												@if ($job->internal_job_id)
													{{ $job->internal_job_id }}
												@else
													<em class="text-muted">Not entered</em>
												@endif
											</div>

											<div class="col-sm-6">
												<label>Comments:</label>
												{{ $job->comments }}
											</div>
										</div>
									</div>
									<div class="tab-pane" id="people">
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div class="btn-group m-y-10">
														<h3>Contacts</h3>
													</div>
													@if (Auth::user()->canManageJob($job))
													<div class="btn-group pull-right m-y-10">
														<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Add Contact <span class="caret"></span></button>
														<ul class="dropdown-menu">
															<li><a href="javascript:show_modal('/jobs/add-existing-contacts/{{ $job->id }}')" class="btn-add-contact">Client </a></li>
															<li><a href="javascript:show_modal('/jobs/create-contact/{{ $job->id }}')" class="btn-new-contact">Create New Contact </a></li>
														</ul>
													</div>
													@endif
												</div>
											</div>
										</div>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Name</th>
													<th>Email</th>
													<th>Phone</th>
													<th>Position</th>
													<th>Type</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@if(count($job->contacts))
													@foreach ($job->contacts as $job_contact)
														<tr data-id="{{ $job_contact->contact->id }}" data-name="{{ $job_contact->contact->first_name }} {{ $job_contact->contact->last_name }}" data-position="{{ $job_contact->contact->position }}" data-email="{{ $job_contact->contact->email }}" data-phone="{{ $job_contact->contact->phone }}" data-type="{{ $job_contact->contact->type }}">
															<td>{{ $job_contact->contact->first_name }} {{ $job_contact->contact->last_name }}</td>
															<td>{{ $job_contact->contact->email }}</td>
															<td>{{ $job_contact->contact->phone }}</td>
															<td>{{ $job_contact->contact->position }}</td>
															<td>{{ $job_contact->contact->type }}</td>
															<td>
																<div class="btn-group">
																	<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
																	<ul class="dropdown-menu">
																		<li><a href="#" class="btn-edit-contact">Edit</a></li>
																		<li><a href="#" class="btn-delete-contact">Delete</a></li>
																	</ul>
																</div>
															</td>
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
										<hr />
										<div class="row">
											<div class="col-md-12">
												<div class="btn-group m-y-10">
														<h3>Installers</h3>
												</div>
												@if (Auth::user()->canManageJob($job))
												<button class="btn btn-default btn-sm btn-add-installer pull-right m-y-10">Add Installer</button>
												@endif
											</div>
										</div>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Name</th>
													<th>Email</th>
													<th>Phone</th>
													<th>Primary</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($job->installers as $installer)
													<tr data-id="{{ $installer->installer->id }}" >
														<td> {{ $installer->installer->first_name }} {{ $installer->installer->last_name }}</td>
														<td>{{ $installer->installer->email }}</td>
														<td>{{ $installer->installer->phone_main }}</td>


														@if ($job->primary_installer_id == $installer->installer->id)
														<td>Yes</td>
														@else
															@if (Auth::user()->canSetPrimaryInstallers())
															<td>
																<button class="btn btn-xs btn-primary" onclick="set_primary_installer('{{$installer->installer->id}}');return false;">Make Primary Installer</button>
															</td>
															@else
															<td>No</td>
															@endif
														@endif
														<td>{{ ucfirst($installer->status) }}</td>
														<td>
															<div class="btn-group">
																<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
																<ul class="dropdown-menu">
																	<li><a href="#" class="btn-delete-installer">Delete</a></li>
																</ul>
															</div>
														</td>
													</tr>
													<input type="hidden" name="job_id" value="{{ $job->id }}">
													{{ csrf_field() }}
												@endforeach
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="images">
										<div class="row">
													@if(count($job->images))
														@foreach($job->images as $image)
															<div class="col-md-4 col-lg-3">
																<a href="{{ $image->getUrl() }}" data-toggle="lightbox" data-gallery="job-images" data-title="{{ $image->caption }}" data-footer="{{ $image->type == 'pre-installation' ? 'Pre installation' : 'Post installation' }}">
																	<img src="{{ $image->getUrl() }}" class="img img-responsive" style="padding: 10px;">
																	@if($image->caption != '')
																		<p>{{ $image->caption }}                                                                                                                                                                                               </p>
																	@endif
																</a>
															</div>
												@endforeach
											@else
												<div class="col-md-12">
													<p>No images have been uploaded in this job.</p>
												</div>
											@endif
										</div>
									</div>
									<div class="tab-pane" id="documents">
										<div class="row">
											@if (Auth::user()->canManageJob($job))
												<div class="col-md-12">
													<button data-toggle="modal" data-target="#add-document-modal" class="btn btn-default btn-sm btn-add-document pull-right m-y-10">Add Document</button>
												</div>
											@endif
										</div>
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Name</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($job->documents as $document)
													<tr data-id="{{ $document->id }}">
														<td>{{ $document->name }}</td>
														<td>
															<div class="btn-group">
																<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
																<ul class="dropdown-menu">
																	<li>
																		<a href="/storage/job_documents/{{$document->name}}" target="__blank" title="Download Document"><i class="fa fa-download"></i> Download</a>
																	</li>
																	<li>
																		<form action="/jobs/delete-document/{{$document->id}}" method="post">
																			<input type="hidden" name="document_id" value="{{$document->id}}">
																			{{csrf_field()}}
																			<button type="submit" class="btn-block btn btn-delete-document btn-default btn-sm"><i class="fa fa-trash"></i> Delete</button>
																		</form>
																	</li>
																</ul>
															</div>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
									<div class="tab-pane" id="jsra">
										<div class="row" style="margin-top:15px">
											<div class="col-md-8">
												<table class="table table-bordered">
												<thead>
													<tr>
														<th>List Actual and Potential Hazards</th>
														<th>Verify</th>
														<th>Control Measures</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Manual Handling of materials or equipment?</td>
														<td>@printvalue($job->jsra->manual_handling)</td>
														<td>{{ implode(", ", $job->jsra->getHandlingSelections()) }}</td>
													</tr>
													<tr>
														<td>Does the public have access to your work area?</td>
														<td>@printvalue($job->jsra->has_public_access)</td>
														<td>{{ implode(", ", $job->jsra->getPublicSelections()) }}</td>
													</tr>
													<tr>
														<td>Will you be working at heights above 2.0m?</td>
														<td>@printvalue($job->jsra->is_above_2_metres)</td>
														<td>{{ implode(", ", $job->jsra->getHeightSelections()) }}</td>
													</tr>
													<tr>
														<td>Will you be cutting or grinding any hazardous material?</td>
														<td>@printvalue($job->jsra->hazardous_material)</td>
														<td>{{ implode(", ", $job->jsra->getHazardSelections()) }}</td>
													</tr>
													<tr>
														<td>Is there potential for falling objects(e.g. hoarding panels)?</td>
														<td>@printvalue($job->jsra->has_potential_falling_objects)</td>
														<td>{{ implode(", ", $job->jsra->getFallingSelections()) }}</td>
													</tr>
													<tr>
														<td>You will all wear appropriate PPE(based on task risk assessment)</td>
														<td>@printvalue($job->jsra->wear_appropriate_ppe)</td>
														<td>{{ implode(", ", $job->jsra->getPPESelections()) }}</td>
													</tr>
													<tr>
														<td>Any other uncontrolled hazards on site?</td>
														<td>@printvalue($job->jsra->has_other_hazards)</td>
														<td>{{ $job->jsra->getOtherSelections()  }}</td>
													</tr>
												</tbody>
											</table>
												<hr>
												@if ($job->form_completed_at)
													<p><em>Form completed on {{ $job->form_completed_at->setTimezone(Auth::user()->timezone)->format('l, j M Y \a\t g:ia') }}</em></p>
												@else
													<p><em>This form has not been completed.</em></p>
												@endif
											</div>
											<div class="col-md-4">
												<h3>Signatures</h3>
												@if ($job->installers->count())
													<table class="table" id="signatures-table">
														<tr>
															<th colspan="2">Signatures</th>
														</tr>
														@foreach ($job->installers as $installer)
															<tr>
																<td>{{ $installer->installer->first_name }} {{ $installer->installer->last_name }}</td>
																<td>
																	@if ($installer->form_signed_at)
																		<i class="fa fa-check text-success"></i> {{ $installer->form_signed_at->setTimezone(Auth::user()->timezone)->format('j M Y') }}
																	@else
																		<em class="text-muted">Not yet signed</em>
																	@endif
																</td>
															</tr>
														@endforeach
													</table>
												@else
													<p>Add installers to the job and their names will appear here.</p>
												@endif
											</div>
										</div>
									</div>
									<div class="tab-pane" id="qc">
										<div style="margin-top:15px">
											<h2>Quality Checklist</h2>

											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Key Criteria</th>
														<th>Verify</th>
														<th>Detailed Performance Standard</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td rowspan="9">Visual Presentation</td>
														<td>@printvalue($job->qc->good_condition)</td>
														<td>Hoarding panels in good condition and free of significant damage</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->hoardings_aligned)</td>
														<td>Hoarding alignment plumb and straight</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->good_paint)</td>
														<td>Doors have good paint finish colour matched to hoarding panels</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->well_lubed)</td>
														<td>Doors, pad bolts and locks installed and operating without binding or jamming</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->taped)</td>
														<td>All joints, skirting, corner, door jambs, raw materials and screw heads are taped straight and smooth</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->dust_supression_installed)</td>
														<td>Dust suppression installed neat and taught (no creases)</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->anti_tamper_installed)</td>
														<td>Anti‐tamper devices installed to all TITAN uprights</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->do_not_lean_installed)</td>
														<td>‘DO NOT LEAN OR MODIFY HOARDING’ stickers affixed to rear of hoarding</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->fingerprints_removed)</td>
														<td>Finger/hand prints and scuff marks removed from hoarding panels, ceilings, bulkhead and adjacent tenancies</td>
													</tr>
													<tr>
														<td rowspan="2">Housekeeping</td>
														<td>@printvalue($job->qc->floor_swept)</td>
														<td>Floor area swept to remove leftover screws, dust and debris</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->waste_removed)</td>
														<td>Waste materials removed from site (DO NOT use mall or site bins)</td>
													</tr>
												</tbody>
											</table>

											<h2>Compliance Checklist</h2>

											<table class="table table-bordered">
												<thead>
													<tr>
														<th>Key Criteria</th>
														<th>Verify</th>
														<th>Detailed Performance Standard</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td rowspan="3">Site Specification</td>
														<td>N/A</td>
														<td>Hoarding type: <strong>{{ $job->qc->hoarding_type }}</strong></td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->installed_per_plan)</td>
														<td>Hoarding installed as per plan</td>
													</tr>
													<tr>
														<td></td>
														<td><strong>{{ $job->qc->set_out }}mm</strong> set-out from lease line as specified</td>
													</tr>
													<tr>
														<td rowspan="11">Engineer's Specification for Certification to AS 4687</td>
														<td>@printvalue($job->qc->uprights_installed)</td>
														<td>TITAN Upright supports placed at each panel join (excluding corners)</td>
													</tr>
													<tr>
														<td>N/A</td>
														<td>Stud spec: <strong>{{ $job->qc->stud_spec }}</strong></td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->double_stud)</td>
														<td>Panel heights above 4.4m are double stud of 70x45mm MGP12 (into TITAN Upright) with 70x35mm MGP10 laminated with 12Gx65mm screws @ 500mm centres</td>
													</tr>
													<tr>
														<td>N/A</td>
														<td>Panel installed: <strong>{{ $job->qc->panel_installed }}</strong></td>
													</tr>
													<tr>
														<td>N/A</td>
														<td>Screw size: <strong>{{ $job->qc->screw_size }}</strong></td>
													</tr>
													<tr>
														<td>N/A</td>
														<td>Panel fixing: <strong>{{ $job->qc->panel_fixing }}</strong></td>
													</tr>
													<tr>
														<td>N/A</td>
														<td>Quantity of counterweights per upright: <strong>{{ $job->qc->counterweights_quantity }}</strong> @ <strong>{{ (float) $job->qc->counterweights_height }}m</strong> high</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->wind_compliant)</td>
														<td>Wind rated installation completed in accordance with TITAN Standards and Engineer’s Specifications</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->returns)</td>
														<td>Hoarding panel returns (forming a buttress) fixed to TITAN Counterweighted Upright</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->bracing)</td>
														<td>Bracing required above 6.0m high (refer to site specific Engineer’s details)</td>
													</tr>
													<tr>
														<td>@printvalue($job->qc->certificate)</td>
														<td>Install and complete/update installation/modification certificate to rear of hoarding door</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				@if (count($related_jobs) > 1)
					<div class="ibox">
						<div class="ibox-title">
							<h5>Related Jobs</h5>
						</div>
						<div class="ibox-content">
								<div class="list-group">
									@foreach ($related_jobs as $loop_job)
										<a href="/jobs/view/{{ $loop_job->id }}" class="list-group-item{{ $loop_job->id == $job->id ? ' active' : '' }}">
											<strong>{{ ucwords($loop_job->type) }}</strong>
											@if ($loop_job->status == 'pending')
												<span class="label label-info">Pending</span>
											@elseif ($loop_job->status == 'installation')
												<span class="label label-warning">Installation</span>
											@elseif ($loop_job->status == 'documentation')
												<span class="label label-danger">Documentation</span>
											@elseif ($loop_job->status == 'complete')
												<span class="label label-success">Complete</span>
											@endif

											<br>
											<small>{{ $loop_job->start_time ? $loop_job->start_time->format('l, j F Y g:ia') : 'N/A' }}</small>
										</a>
									@endforeach
								</div>
						</div>
					</div>
				@endif
				<div class="ibox">
					<div class="ibox-title">
						<h5>Notes</h5>
						<div class="ibox-tools">
							<button class="btn btn-primary btn-xs pull-right btn-add-note" type="button">Add Note</button>
						</div>
					</div>
					<div class="ibox-content">
						@if (count($job->notes))
							@foreach($job->notes()->orderBy('created_at', 'desc')->limit(10)->get() as $note)
								<div class="media-body">
									<small class="pull-right">
										{{ $note->created_at->diffForHumans() }}
									</small>
									<strong>
										{{ $note->user->first_name . ' ' . $note->user->last_name }}
									</strong>
									posted a note
									<br>
										<small class="text-muted">
											{{ $note->created_at->format('h:i a - d.m.Y') }}
										</small>
										<div class="well">
											{!! nl2br(e($note->message)) !!}
										</div>
									</br>
								</div>
							@endforeach
						@else
						<div class="text-center">
							<p>
								No Messages found in this job.
							</p>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('css')
	<link href="/css/ekko-lightbox.min.css" rel="stylesheet">
	<style type="text/css">
	#signatures-table th {
		border: 0
	}
	#signatures-table td {
		border-top: 0;
		border-bottom: solid 1px #e7eaec;
	}
	</style>
@endsection

@section('scripts')
	<script src="/js/modalform.js"></script>
	<script src="/js/ekko-lightbox.min.js"></script>
	<script>
		// Persist tabs
		if (location.hash) {
			$('a[href="' + location.hash + '"]').tab('show');
		}

		$('a[data-toggle="tab"]').on('shown.bs.tab', function() {
			location.hash = $(this).attr('href').substr(1);
		});

		var edit_contact_html = ''+
			'<form action="/contacts/create" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Name</label>'+
					'<div class="col-md-9"><input type="text" name="name" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Position</label>'+
					'<div class="col-md-9"><input type="text" name="position" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
						'<label class="col-md-3 control-label">Type</label>'+
						'<div class="col-md-9">'+
							'<select name="type" class="form-control">'+
								'<option value="">Please select</option>'+
								'<option value="Client">Client</option>'+
								'<option value="Supplier">Supplier</option>'+
								'<option value="Other">Other</option>'+
							'</select>'+
						'</div>'+
					'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Email</label>'+
					'<div class="col-md-9"><input type="text" name="email" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Phone</label>'+
					'<div class="col-md-9"><input type="text" name="phone" class="form-control"></div>'+
				'</div>'+
				'<input type="hidden" name="client_id" value="{{ $job->client_id }}">'+
				'{{ csrf_field() }}'+
			'</form>';

		$('.btn-edit-contact').on('click', function(event) {
			event.preventDefault();

			var tr = $(this).closest('tr');

			modalform.dialog({
				bootbox: {
					title: 'Edit Contact',
					message: edit_contact_html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default',
						},
						submit: {
							label: 'Save Changes',
							className: 'btn-primary'
						}
					}
				},
				after_init: function() {
					$('.modal input[name="name"]').val(tr.data('name'));
					$('.modal input[name="position"]').val(tr.data('position'));
					$('.modal input[name="email"]').val(tr.data('email'));
					$('.modal input[name="phone"]').val(tr.data('phone'));
					$('.modal [name="type"]').val(tr.data('type'));
					$('.modal form').attr('action', '/contacts/edit/' + tr.data('id'));
				}
			});
		});

		$('.btn-delete-contact').on('click', function(event) {
			event.preventDefault();
			var contact_id = $(this).closest('tr').data('id');
			bootbox.confirm('Are you sure you want to delete this contact?', function(response){
				if (response) {
					$.ajax({
						type: "post",
						url: "/jobs/delete-contact/" + contact_id,
						data: {_token: '{{ csrf_token() }}', job_id: "{{$job->id}}"},
					}).done(function(response) {
						if (response) {
							window.location.reload();
						}
					});
				}
			});
		});

		var add_note_form = ''+
			'<form action="/jobs/add-note" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<div class="col-md-12"><label class="control-label">Message</label>'+
					'<textarea name="message" rows="3" class="form-control" placeholder="Enter Message"></textarea></div>'+
				'</div>'+
				'<input type="hidden" name="job_id" value="{{ $job_id }}">'+
				'{{ csrf_field() }}'+
			'</form>';

		$('.btn-add-note').on('click', function() {
			modalform.dialog({
				bootbox: {
					title: 'Add Note',
					message: add_note_form,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add',
							className: 'btn-primary'
						}
					}
				}
			});
		});

		var add_installer_form = ''+
				'<form action="/jobs/add-installers" method="post" class="form-horizontal">'+
					@if ($installers)
					'<table id="installers-table" class="table table-bordered">'+
						'<thead>'+
							'<tr>'+
								'<th></th>'+
								'<th>Name</th>'+
								'<th>Email</th>'+
								'<th>Phone</th>'+
							'</tr>'+
						'</thead>'+
						'<tbody>'+
							@foreach ($installers as $installer)
								'<tr>'+
									'<td><input type="checkbox" name="installer_ids[]" id="" value="{{ $installer->id }}"></td>'+
									'<td> {{ $installer->first_name }} {{ $installer->last_name }}</td>'+
									'<td>{{ $installer->email }}</td>'+
								'</tr>'+
								'<input type="hidden" name="job_id" value="{{ $job_id }}">'+
								'{{ csrf_field() }}'+
							@endforeach
						'</tbody>'+
					'</table>'+
					@else
						'No installers found.'+
					@endif
					'<input type="hidden" name="job_id" value="{{ $job->id }}">'+
					'{{ csrf_field() }}'+
				'</form>';

		$('.btn-add-installer').on('click', function() {
			modalform.dialog({
				bootbox: {
					title: 'Add Installer',
					message: add_installer_form,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add',
							className: 'btn-primary'
						}
					}
				},
				after_init: function() {
					$('.btn-installer-search').on('click', function(event) {
						event.preventDefault();
						$.ajax({
							type: "get",
							url: "/jobs/search-installers/",
							data: {global_search: $('#search-installers').val(), },
						}).done(function(response) {
							var html = '';
							if (response.length) {
								$.each(response, function(i, installer) {
									html += '<tr>'+
										'<td><input type="checkbox" name="installer_ids[]" id="" value="'+ installer.id +'"></td>'+
										'<td> '+ installer.first_name +' '+ installer.last_name +'</td>'+
										'<td>'+ installer.email +'</td>'+
										'<td>'+ installer.phone_main +'</td>'+
									'</tr>';
								});
							}
							$('#installers-table tbody').html(html);
						});
					});
				}
			});
		});

		$('.btn-delete-installer').on('click', function(event) {
			event.preventDefault();
			var installer_id = $(this).closest('tr').data('id');
			bootbox.confirm('Are you sure you want to delete this installer?', function(response){
				if (response) {
					$.ajax({
						type: "post",
						url: "/jobs/delete-installer/" + installer_id,
						data: {_token: '{{ csrf_token() }}', job_id: "{{$job_id}}"},
					}).done(function(response) {
						if (response) {
							window.location.reload();
						}
					});
				}
			});
		});

		$('.btn-add-document').on('click', function() {
			var html = ''+
				'<form action="/jobs/add-document" method="post" class="form-horizontal" enctype="multipart/form-data">'+
					'<div class="form-group">'+
						'<div class="col-md-12">'+
							'<label class="control-label">Select File</label>'+
							'<input type="file" name="file" class="form-control">'+
						'</div>'+
					'</div>'+
					'<input type="hidden" name="job_id" value="{{ $job->id }}">'+
					'{{ csrf_field() }}'+
				'</form>';

			modalform.dialog({
				bootbox: {
					title: 'Add Document',
					message: html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add Document',
							className: 'btn-primary'
						}
					}
				}
			});
		});

		var product_modal_html = ''+
				'<form action="/jobs/add-product" method="post" class="form-horizontal">'+
					'<div class="form-group product-row">'+
						'<label class="col-md-4 control-label">Name</label>'+
						'<div class="col-md-7">'+
							'<select name="product_id" class="form-control">'+
								'<option value="">Select Product</option>'+
								@foreach ($product_prices as $price)
									'<option value="{{$price->product_id}}">{{addslashes($price->product->name) . ' ($'.$price->product->getPriceForAgent($price->Agent,$price->product_id).')'}}</option>'+
								@endforeach
							'</select>'+
						'</div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-4 control-label">Quantity</label>'+
						'<div class="col-md-7"><input type="text" name="quantity" class="form-control"></div>'+
					'</div>'+
					@if($job->type == "installation")
						'<div class="form-group">'+
							'<label class="col-md-4 control-label">Collected From Warehouse</label>'+
							'<div class="col-md-7"><input type="checkbox" name="is_collected"></div>'+
						'</div>'+
					@elseif($job->type == "removal")
						'<div class="form-group">'+
							'<label class="col-md-4 control-label">Returned to Warehouse</label>'+
							'<div class="col-md-7"><input  type="checkbox" name="is_collected"></div>'+
						'</div>'+
					@endif
					'<input type="hidden" name="job_id" value="{{ $job->id }}">'+
					'</div>'+

					'{{ csrf_field() }}'+
				'</form>';

		$('.btn-add-product').on('click', function() {
			modalform.dialog({
				bootbox: {
					title: 'Add Product',
					message: product_modal_html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add',
							className: 'btn-primary'
						}
					}
				}
			});
		});

		$('.btn-edit-product').on('click', function(event) {
			event.preventDefault();

			var tr = $(this).closest('tr');

			modalform.dialog({
				bootbox: {
					title: 'Edit Product',
					message: product_modal_html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default',
						},
						submit: {
							label: 'Save Changes',
							className: 'btn-primary'
						}
					}
				},
				after_init: function() {
					$('.modal input[name="quantity"]').val(tr.data('quantity'));

					if (tr.data('collected') == 1) {
						$('.modal input[name="is_collected"]').attr("checked","checked");
					}

					$('.modal form').attr('action', '/jobs/edit-product/{{ $job->id }}/' + tr.data('product-id'));
					$('.product-row').remove();
				}
			});
		});

		$('.btn-delete-product').on('click', function(event) {
			event.preventDefault();
			var id = $(this).closest('tr').data('id');
			bootbox.confirm('Are you sure you want to delete this product?', function(response){
				if (response) {
					$.ajax({
						type: "post",
						url: "/jobs/delete-product/" + id,
						data: {_token: '{{ csrf_token() }}'},
					}).done(function(response) {
						if (response) {
							window.location.reload();
						}
					});
				}
			});
		});

		function set_primary_installer(installer_id)
		{
			var set_primary_installer = ''+
			'<form action="/jobs/set-primary-installer/{{ $job_id }}" method="post" class="form-horizontal">'+
				'<div>Do you want to set this installer to be the primary installer?</div>'+
				'<div><small><i>Note : this will remove other installers as the primary installer.</i></small></div>'+
				'<input type="hidden" name="installer_id" value="'+installer_id+'">'+
				'{{ csrf_field() }}'+
			'</form>';

			modalform.dialog({
				bootbox: {
					title: 'Set Primary Installer for Job',
					message: set_primary_installer,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Continue',
						}
					}
				},
				autofocus : false,
			});
		}

		// Init light box
		$(document).on('click', '[data-toggle="lightbox"]', function(event) {
			event.preventDefault();
			$(this).ekkoLightbox();
		});
	</script>
@endsection
