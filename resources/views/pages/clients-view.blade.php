@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>{{ $title }}</h5>
						<div class="ibox-tools">
							<a href="/clients/edit/{{ $client->id }}" class="btn btn-default btn-xs">Edit Client</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label">Trading Name</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->email }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Billing Email</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->billing_email }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">ABN/ACN</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->abn }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Hoarding Materials</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $hoarding_materials }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Billing Address</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->billing_address }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Shipping Address</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->shipping_address }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Phone</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->phone }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Mobile</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->mobile }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Fax</label>
								<div class="col-lg-10">
									<p class="form-control-static">{{ $client->fax }}</p>
								</div>
							</div>

							<h3>Contacts</h3>

							@if (count($client->contacts))
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Name</th>
											<th>Position</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Type</th>
										</tr>
									</thead>
									<tbody class="table_body">
										@foreach ($client->contacts as $contact)
											<tr data-id="{{ $contact->id }}" data-name="{{ $contact->first_name }} {{ $contact->last_name }}" data-position="{{ $contact->position }}" data-email="{{ $contact->email }}" data-phone="{{ $contact->phone }}" data-type="{{ $contact->type }}">
												<td>{{ $contact->first_name }} {{ $contact->last_name }}</td>
												<td>{{ $contact->position }}</td>
												<td>{{ $contact->email }}</td>
												<td>{{ $contact->phone }}</td>
												<td>{{ $contact->type }}</td>
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
									</tbody>
								</table>
							@endif

							<p><button type="button" class="btn btn-default btn-create-contact">Create New Contact</button></p>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="/js/modalform.js"></script>
	<script>
	var contact_modal_html = ''+
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
			'<input type="hidden" name="client_id" value="{{ $client->id }}">'+
			'{{ csrf_field() }}'+
		'</form>';

	$('.btn-create-contact').on('click', function() {
		modalform.dialog({
			bootbox: {
				title: 'Create New Contact',
				message: contact_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Contact',
						className: 'btn-primary'
					}
				}
			}
		});
	});

	$('.btn-edit-contact').on('click', function(event) {
		event.preventDefault();

		var tr = $(this).closest('tr');

		modalform.dialog({
			bootbox: {
				title: 'Edit Contact',
				message: contact_modal_html,
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

		modalform.dialog({
			title: 'Delete Contact',
			message: ''+
				'<form action="/contacts/delete/' + contact_id + '" method="post" class="form-horizontal">'+
					'<p>Are you sure you want to delete this contact?</p>'+
					'{{ csrf_field() }}'+
				'</form>',
			buttons: {
				cancel: {
					label: 'Cancel',
					className: 'btn-default'
				},
				submit: {
					label: 'Delete Contact',
					className: 'btn-danger'
				}
			}
		});
	});
	</script>
@endsection
