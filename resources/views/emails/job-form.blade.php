<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>TITAN HOARDING SYSTEMS | SAFETY, QUALITY AND COMPLIANCE CHECKLIST</title>
	 <!-- Bootstrap css -->
	<link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{url('css/form-pdf.css')}}" rel="stylesheet" />
</head>
<body>
	<div class="container mrg-tb20">
		<div class="row">
			<div class="col-xs-12">
				<table class="table table-bordered table-header">
					<tbody>
						<tr>
							<td rowspan="3" class="td-logo"><img src="{{url("images/titan-logo.jpg")}}" alt="Titan Hoarding Systems" class="img-responsive"></td>
							<td rowspan="3"><h3>SAFETY, QUALITY AND COMPLIANCE CHECKLIST</h3></td>
							<td><h4>12.9</h4></td>
						</tr>
						<tr>
							<td><p>OPERATIONS MANUAL</p></td>
						</tr>
						<tr>
							<td><p>page 1 of 1</p></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-12">
				<table class="table table-bordered table-job-details">
					<tbody>
						<tr>
							<td rowspan="2">Job</td>
							<td rowspan="2">{{ ucfirst($job->type) }} of {{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }} for {{ $job->client->name }}</td>
							<td rowspan="2">State</td>
							<td rowspan="2">{{ $job->state }}</td>
							<td>Centre</td>
							<td class="centre">{{ $job->shop_name}}</td>
							<td rowspan="2">Start</td>
							<td rowspan="2">{{ $job->start_time->format('d/M/Y g:ia') }}</td>
							<td rowspan="2">Finish</td>
							<td rowspan="2">{{ $job->form_completed_at->format('d/M/Y g:ia') }}</td>
							<td rowspan="2">Date</td>
							<td rowspan="2">{{ $job->created_at->format('d/M/Y') }}</td>
						</tr>
						<tr>
							<td>Tenancy</td>
							<td class="centre">{{ $job->address}}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-xs-12">
				<table class="table table-bordered table-components-tracking">
					<tbody>
						<tr>
							<td><h4 class="red">COMPONENTS TRACKING</h4><p>RELATES TO ALL COMPONENTS GOING TO AND FROM A WORK SITE</p></td>
							<td><h4>QTY DISPATCHED</h4><p>TO INSTALLS / MODIFICATIONS OR REMOVALS</p></td>
							<td><h4>QTY RETURNED</h4><p>TO WAREHOUSE</p></td>
						</tr>
						@foreach ($job->products as $job_product)
						<tr>
							<td>
								<p>{{ strtoupper($job_product->product->name) }}</p>
							</td>
							<td>{{ $job->type == 'installation' ? $job_product->quantity : '' }}</td>
							<td>{{ $job->type == 'removal' ? $job_product->quantity : '' }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="col-xs-12">
				<table class="table table-bordered table-components-tracking">
					<tbody>
						<tr>
							<td colspan="2"><h4>DETAILS  OF WORK COMPLETED</h4></td>
						</tr>
						<tr>
							<td><p>L/M  INSTALLED INCLUDING RETURNS</p></td>
							<td></td>
						</tr>
						<tr>
							<td>
								<p>L/M  INSTALLED INCLUDING RETURNS</p>
								<p><small>In case of 2 different  systems install at the same time</small></p>
							</td>
							<td></td>
						</tr>
						<tr>
							<td><p>HEIGHT OF PANEL</p></td>
							<td>{{$job->total_height}} metres</td>
						</tr>
						<tr>
							<td><p>DUST SUPRESSION HEIGHT ABOVE PANEL</p></td>
							<td>{{$job->dust_panel_height}} metres</td>
						</tr>
						<tr>
							<td><p>QTY OF DOORS INSTALLED</p></td>
							<td>{{ $job->num_doors }}</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="col-xs-12">
				<table class="table table-bordered table-installer">
					<tbody>
						<tr>
							<td><h4>INSTALLER DECLARATION</h4></td>
							<td colspan="3">
								<p><strong>I the undersigned, hereby declare the following: <span class="red">Note this document is to be signed by each member of site team working.</span></strong></p>
								<ol>
									<li>That I have completed all works as I have verified above regarding Safety – JSRA, Quality, Compliance for TITAN Hoarding System Standards, Specifications & Engineers Certification requirements.</li>
									<li>I confirm my involvement in consultation re: site hazards & control measures  & I will follow agreed work methods.</li>
									<li>I also confirm I will wear required PPE, ensure it is in good condition & used effectively.</li>
								</ol>
							</td>
						</tr>
						<tr>
							<td><h5>INSTALLER NAME:</h5></td>
							<td><h5>INSTALLER SIGNATURE:</h5></td>
							<td><h5>INSTALLER NAME:</h5></td>
							<td><h5>INSTALLER SIGNATURE:</h5></td>
						</tr>
						@for($i = 0; $i< count($job->installers); $i = $i+2)
						<tr>
							<td>
								{{$i+1}}
								{{$job->installers[$i]->installer->first_name}}
								{{$job->installers[$i]->installer->last_name}}
							</td>
							<td>
								{{$job->installers[$i]->form_signed_at}}
							</td>
							</td>
							@if(isset($job->installers[$i+1]))
								<td>
									{{$i+2}}
									{{$job->installers[$i+1]->installer->first_name}}
									{{$job->installers[$i+1]->installer->last_name}}
								</td>
								<td>
									{{$job->installers[$i+1]->form_signed_at}}
								</td>
							@endif
						</tr>
						@endfor
					</tbody>
				</table>
			</div>
			<div class="col-xs-12">
				<table class="table table-footer">
					<tbody>
						<tr>
							<td></td>
							<td><p>UNCONTROLLED WHEN PRINTED</p></td>
							<td><p><small>Issue: V10</small></p></td>
						</tr>
						<tr>
							<td></td>
							<td><p><small>&copy Copyright Titan Hoarding Systems Australia Pty Ltd 2016</small></p></td>
							<td><p><small>Release Date: 29-10-16</small></p></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<script src="{{url('js/jquery-2.1.1.js')}}"></script>
	<script src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>