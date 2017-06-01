@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="tabs-container">
			<ul class="nav nav-tabs">
				<li{!! $type == 'clients' ? ' class="active"' : '' !!}><a href="/import/clients">Import Clients</a></li>
				<li{!! $type == 'installers' ? ' class="active"' : '' !!}><a href="/import/installers">Import Installers</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="panel-body">
						@if (session()->has('message'))
							<div class="alert alert-success">{{ session()->get('message') }}</div>
						@endif

						@if ($errors->count())
							<div class="alert alert-danger">
								@foreach ($errors->all() as $error)
									{{ $error }}<br>
								@endforeach
							</div>
						@endif

						<h2>CSV File Format</h2>

						<p>Your CSV file should contain a header row and the following columns:</p>

						@if ($type == 'clients')
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Billing Email</th>
										<th>Phone</th>
										<th>Mobile</th>
										<th>Fax</th>
										<th>Size</th>
										<th>ABN</th>
										<th>Billing Address</th>
										<th>Shipping Address</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Example Pty Ltd</td>
										<td>info@example.com</td>
										<td>accounts@example.com</td>
										<td>02 1234 5678</td>
										<td>0400 000 000</td>
										<td>02 8765 4321</td>
										<td>10-50</td>
										<td>45 123 456 789</td>
										<td>123 Fake Street</td>
										<td>123 Fake Street</td>
									</tr>
								</tbody>
							</table>

							<p>For example:</p>

							<div class="well" style="font-family:Courier">
								Name,Email,Billing Email,Phone,Mobile,Fax,Size,ABN,Billing Address,Shipping Address<br>
								Example Pty Ltd,info@example.com,info@example.com,02 1234 5678,0400 000 000,02 8765 4321,10-50,45 123 456 789,123 Fake Street,123 Fake Street<br>
							</div>
						@else
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>ABN</th>
										<th>Billing Address</th>
										<th>Shipping Address</th>
										<th>Phone</th>
										<th>Mobile</th>
										<th>Fax</th>
										<th>Billing Email</th>
										<th>Bank Account Name</th>
										<th>Bank Account Number</th>
										<th>BSB</th>
										<th>Date of Birth</th>
										<th>Gender</th>
										<th>Certification</th>
										<th>Timezone</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>John</td>
										<td>Smith</td>
										<td>info@example.com</td>
										<td>45 123 456 789</td>
										<td>123 Fake Street</td>
										<td>123 Fake Street</td>
										<td>02 1234 5678</td>
										<td>0400 000 000</td>
										<td>02 8765 4321</td>
										<td>accounts@example.com</td>
										<td>John Smith</td>
										<td>12345678</td>
										<td>123456</td>
										<td>1980-12-31</td>
										<td>Male</td>
										<td>1</td>
										<td>Australia/Brisbane</td>
									</tr>
								</tbody>
							</table>

							<p>For example:</p>

							<div class="well" style="font-family:Courier">
								First Name,Last Name,Email,ABN,Billing Address,Shipping Address,Phone,Mobile,Fax,Billing Email,Bank Account Name,Bank Account Number,BSB,Date of Birth,Gender,Certification,Timezone<br>
								John,Smith,info@example.com,45 123 456 789,123 Fake Street,123 Fake Street,02 1234 5678,0400 000 000,02 8765 4321,accounts@example.com,John Smith,12345678,123456,1980-12-31,Male,1,Australia/Brisbane
							</div>
						@endif

						<hr>
						<h2>Import {{ ucfirst($type) }}</h2>

						<form action="/import/{{ $type }}" method="post" enctype="multipart/form-data" class="form-horizontal">
							<div class="form-group">
								<label class="col-md-3 control-label">CSV File</label>
								<div class="col-md-9"><input type="file" name="file" class="form-control"></div>
							</div>

							<div class="form-group">
								<label class="col-md-3 control-label">Existing Data</label>
								<div class="col-md-9">
									@if ($has_jobs)
										<p class="form-control-static">New {{ $type }} will be merged with existing {{ $type }} (based on email address).</p>
										<input type="hidden" name="existing" value="merge">
									@else
										<div class="radio"><label><input type="radio" name="existing" value="delete"> Delete existing {{ $type }}</label></div>
										<div class="radio"><label><input type="radio" name="existing" value="merge"> Merge with existing {{ $type }} (based on email)</label></div>
									@endif
								</div>
							</div>

							@if ($type == 'installers')
								<div class="form-group">
									<label class="col-md-3 control-label">Invitations</label>
									<div class="col-md-9">
										<label class="checkbox-inline"><input type="checkbox" name="send_invitations" value="1"> Send invitations to new installers (you'll be limited to 25 rows per import when doing this)</label>
									</div>
								</div>
							@endif

							<div class="form-group">
								<div class="col-md-offset-3 col-md-9">
									<button type="submit" class="btn btn-primary">Import {{ ucfirst($type) }}</button>
									{!! csrf_field() !!}
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

