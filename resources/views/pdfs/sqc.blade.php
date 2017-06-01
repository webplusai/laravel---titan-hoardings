<!DOCTYPE html>
<html>
	<head>
		<title>Safety, Quality and Compliance Checklist - Job #{{ $job->id }}</title>
		<link href="{{ request()->root() }}/css/sqc.css" rel="stylesheet">
	</head>
	<body>
		<htmlpageheader name="titan">
			<table>
				<tr>
					<td rowspan="3" class="text-right" bgcolor="#000000"><img src="/images/logo.png"></td>
					<td rowspan="3" class="text-center text-title">
						SAFETY, QUALITY AND COMPLIANCE CHECKLIST<br>
						<span class="text-red">{{ ucfirst($job->type) }}</span>
					</td>
					<td class="text-center">12.9</td>
				</tr>
				<tr>
					<td class="text-center">OPERATIONS MANUAL</td>
				</tr>
				<tr>
					<td class="text-center">Page {PAGENO}</td>
				</tr>
			</table>
		</htmlpageheader>
		<sethtmlpageheader name="titan" show-this-page="1">

		<table>
			<tr>
				<th width="8.33%" class="text-left">JOB NO.</th>
				<td width="8.33%">{{ $job->id }}</td>
			</tr>
		</table>

		<table>
			<tr>
				<th width="8.33%" class="text-left">JOB NO.</th>
				<td width="8.33%">{{ $job->id }}</td>
				<th width="8.33%" class="text-left">STATE</th>
				<td width="8.33%">{{ $job->state }}</td>
				<th width="8.33%" class="text-left">CENTRE<br>TENANCY</th>
				<td width="8.33%">{{ $job->shop_name }}</td>
				<th width="8.33%" class="text-left">START</th>
				<td width="8.33%">{{ $job->start_time->setTimezone(Auth::user()->timezone)->format('g:ia') }}</td>
				<th width="8.33%" class="text-left">FINISH</th>
				<td width="8.33%"></td>
				<th width="8.33%" class="text-left">DATE</th>
				<td width="8.33%">{{ $job->start_time->setTimezone(Auth::user()->timezone)->format('j/m/Y') }}</td>
			</tr>
			<tr>
				<th colspan="2" class="text-left">HOARDING TYPE</th>
				<td colspan="10">{{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }}</td>
			</tr>
		</table>
		<br>

		<div class="row">
			<div class="col-6">
				<div class="pad-right">
					<table>
						<tr>
							<th class="text-left">
								COMPONENTS TRACKING<br>
								<span class="notbold">RELATES TO ALL COMPONENTS GOING TO AND FROM A WORK SITE</span>
							</th>
							<th>QUANTITY DISPATCHED FROM WAREHOUSE</th>
							<th>QUANTITY RETURNED TO WAREHOUSE</th>
						</tr>
						@foreach ($job->products as $product)
							<tr>
								<td>{{ $product->product->name }}</td>
								<td class="text-center">{{ $product->quantity }}</td>
								<td class="text-center"></td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
			<div class="col-6">
				<div class="pad-left">
					<table>
						<tr>
							<th colspan="2">DETAILS OF WORK COMPLETED</th>
						</tr>
						<tr>
							<td class="shaded">Linear metres installed including returns</td>
							<td>{{ ($job->total_length + $job->return_size) / 1000 }}m</td>
						</tr>
						<tr>
							<td class="shaded">
								Linear metres installed including returns<br>
								(in the case of 2 different TITAN systems being installed)
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="shaded">Height of Panel Installed</td>
							<td>{{ $job->total_height / 1000 }}m</td>
						</tr>
						<tr>
							<td class="shaded">Dust Supression Height Above Panel</td>
							<td>{{ $job->dust_panel_height / 1000 }}m</td>
						</tr>
						<tr>
							<td class="shaded">Quantity of Doors Installed</td>
							<td>{{ $job->num_doors }}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<div class="text-red"><strong>Safety &mdash; JSRA</strong> (Job Start Risk Assessment) <strong>Note:</strong> This document <strong>does not</strong> replace a contractor's requirement to supply a signed site specific SWMS</div>

		<table>
			<tr>
				<th colspan="2">LIST ACTUAL AND POTENTIAL HAZARDS</th>
				<th>VERIFY</th>
				<th>CONTROL MEASURES</th>
			</tr>
			<tr>
				<td>1</td>
				<td>Working above heights of 2.0 metres?</td>
				<td class="text-center">{{ $job->jsra->is_above_2_metres }}</td>
				<td>{{ implode(', ', $job->jsra->getHeightSelections()) }}</td>
			</tr>
			<tr>
				<td>2</td>
				<td>Does the public have access to your work area?</td>
				<td class="text-center">{{ $job->jsra->has_public_access }}</td>
				<td>Work area cordoned off to prevent pedestrian/public access if required</td>
			</tr>
			<tr>
				<td>3</td>
				<td>Will you be cutting or grinding any hazardous material?</td>
				<td class="text-center">{{ $job->jsra->hazardous_material }}</td>
				<td>Use vacuum dust extraction &amp; wear respiratory, eye and hearing PPE</td>
			</tr>
			<tr>
				<td>4</td>
				<td>Manual handling?</td>
				<td class="text-center">{{ $job->jsra->manual_handling }}</td>
				<td>Sheet &amp; flatbed trolleys for movement of pine, panels and TITAN components</td>
			</tr>
			<tr>
				<td>5</td>
				<td>Does all the site electrical equipment bear a current test tag?</td>
				<td class="text-center">{{ $job->jsra->has_current_test_tag }}</td>
				<td>Remove from site until compliant</td>
			</tr>
			<tr>
				<td>6</td>
				<td>You all wear the appropriate PPE (based on task risk assessment)</td>
				<td class="text-center">{{ $job->jsra->ppe_boots || $job->jsra->ppe_shirt || $job->jsra->ppe_gloves || $job->jsra->ppe_eye_protection || $job->jsra->ppe_other ? 'Y' : 'N'}}</td>
				<td>{{ implode(', ', $job->jsra->getPPESelections()) }}</td>
			</tr>
		</table>

		<table>
			<tr>
				<th>INSTALLER'S DECLARATION</th>
				<td colspan="3">
					<strong>I the undersigned, hereby declare the following:</strong> <span class="text-red">Note this document must be signed by every member of the installation team.</span><br>
					1. I have completed Safety &mdash; JSRA prior to start of work.<br>
					2. I confirm my involvement in consultation regarding site hazards &amp; control measures &amp; I will follow agreed work methods.<br>
					3. I confirm I will wear required PPE, ensure it is in good condition &amp; used effectively.
				</td>
			</tr>
			<tr>
				<th>INSTALLER'S NAME</th>
				<th>INSTALLER'S SIGNATURE</th>
				<th>INSTALLER'S NAME</th>
				<th>INSTALLER'S SIGNATURE</th>
			</tr>
			@for ($i = 0; $i < ceil($job->installers->count() / 2); $i++)
				@php($other_i = ceil($job->installers->count() / 2) + $i)
				<tr>
					<td>{{ $i + 1 }} &nbsp; {{ $job->installers[$i]->installer->first_name }} {{ $job->installers[$i]->installer->last_name }}</td>
					<td></td>
					<td>
						@if ($job->installers->count() > $other_i)
							{{ $other_i + 1 }} &nbsp; {{ $job->installers[$other_i]->installer->first_name }} {{ $job->installers[$other_i]->installer->last_name }}
						@endif
					</td>
					<td></td>
				</tr>
			@endfor
		</table>
		<br>

		<table>
			<tr>
				<th colspan="3">QUALITY CHECKLIST</th>
			</tr>
			<tr>
				<th>KEY CRITERIA</th>
				<th>VERIFY</th>
				<th>DETAILED PERFORMANCE STANDARD</th>
			</tr>
			<tr>
				<td rowspan="8" class="shaded text-center">
					<strong>
						INSTALLATION PROCESS<br><br>
						and<br><br>
						VISUAL PRESENTATION
					</strong>
				</td>
				<td class="text-center">{{ $job->qc->good_condition }}</td>
				<td>Hoarding panels in good condition and free of significant damage</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->hoardings_aligned }}</td>
				<td>Hoarding alignment plumb and straight</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->good_paint }}</td>
				<td>Doors have good paint finish colour matched to hoarding panels</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->well_lubed }}</td>
				<td>Doors, pad bolts and locks installed and operating without binding or jamming</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->taped }}</td>
				<td>All joints, skirting, corner, door jambs, raw materials and screw heads are taped straight and smooth</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->dust_supression_installed }}</td>
				<td>Dust suppression installed neat and taught (no creases)Anti-tamper devices installed to all TITAN uprights</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->do_not_lean_installed }}</td>
				<td>‘DO NOT LEAN OR MODIFY HOARDING’ stickers affixed to rear of hoarding</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->fingerprints_removed }}</td>
				<td>Finger/hand prints and scuff marks removed from hoarding panels, ceilings, bulkhead and adjacent tenancies</td>
			</tr>
			<tr>
				<td rowspan="2" class="shaded text-center"><strong>HOUSEKEEPING</strong></td>
				<td class="text-center">{{ $job->qc->floor_swept }}</td>
				<td>Floor area swept to remove left over screws, dust and debris</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->waste_removed }}</td>
				<td>All waste materials have been removed from site (<strong>DO NOT</strong> use mall or site bins)</td>
			</tr>
		</table>
		<br>

		<div style="page-break-inside:avoid">
		<table>
			<tr>
				<th colspan="3">COMPLIANCE CHECKLIST</th>
			</tr>
			<tr>
				<th>KEY CRITERIA</th>
				<th>VERIFY</th>
				<th>DETAILED PERFORMANCE STANDARD</th>
			</tr>
			<tr>
				<td rowspan="3" class="shaded text-center"><strong>SITE SPECIFICATION</strong></td>
				<td></td>
				<td>Hoarding Type: <strong>{{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }}</strong></td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->installed_per_plan }}</td>
				<td>Hoarding installed as per plan</td>
			</tr>
			<tr>
				<td></td>
				<td><strong>{{ $job->qc->set_out }}mm</strong> set-out from lease line as specified</td>
			</tr>
			<tr>
				<td rowspan="11" class="shaded text-center"><strong>ENGINEER'S SPECIFICATION FOR CERTIFICATION TO AS 4687</strong></td>
				<td class="text-center">{{ $job->qc->uprights_installed }}</td>
				<td>TITAN Upright supports placed at each panel join (excluding corners)</td>
			</tr>
			<tr>
				<td></td>
				<td>Stud Spec: <strong>{{ $job->qc->stud_spec }}</strong></td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->double_stud }}</td>
				<td>Panel heights above 4.4m are double stud of 70x45mm MPG12 (into TITAN Upright) with 70x35mm MGP10 laminated with 12gx65mm screws @ 500mm centres</td>
			</tr>
			<tr>
				<td></td>
				<td>Panel installed: <strong>{{ $job->qc->panel_installed }}</strong></td>
			</tr>
			<tr>
				<td></td>
				<td>Screw size: <strong>{{ $job->qc->panel_installed }}</strong></td>
			</tr>
			<tr>
				<td></td>
				<td>Panel fixing: <strong>{{ $job->qc->panel_installed }}</strong></td>
			</tr>
			<tr>
				<td></td>
				<td>Quantity of counterweights per upright: <strong>{{ $job->qc->counterweights_quantity }}</strong> @ <strong>{{ $job->qc->counterweights_height }}m</strong> high</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->wind_compliant }}</td>
				<td>Wind rated installation completed in accordance with TITAN Standards and Engineer's Specifications</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->returns }}</td>
				<td>Hoarding panel returns (forming a buttress) fixed to TITAN Counterweighted Upright</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->bracing }}</td>
				<td>Bracing required above 6.0m high (refer to site specific engineer's details)</td>
			</tr>
			<tr>
				<td class="text-center">{{ $job->qc->certificate }}</td>
				<td>Install and complete/update installation/modification certificate to rear of hoarding door</td>
			</tr>
		</table>
		</div>
		<br>

		<table>
			<tr>
				<th>INSTALLER'S DECLARATION</th>
				<td colspan="3"><strong>I hereby declare that all processes including visual presentation, site specific requirements and structural specifications have been completed as detailed above.</strong></td>
			</tr>
			<tr>
				<th width="25%">SENIOR INSTALLER'S NAME</th>
				<td width="25%">
					@if ($job->primaryInstaller)
						{{ $job->primaryInstaller->first_name }} {{ $job->primaryInstaller->last_name }}
					@endif
				</td>
				<th width="25%">SENIOR INSTALLER'S SIGNATURE</th>
				<td width="25%"></td>
			</tr>
		</table>
		<br>

		<table>
			<tr>
				<td>&copy; Copyright Titan Hoarding Systems Australia Pty Ltd {{ date('Y') }}</td>
				<td>UNCONTROLLED WHEN PRINTED</td>
				<td class="text-right">Release Date {{ Carbon::today(Auth::user()->timezone)->format('j/m/Y') }} &nbsp; Issue: V15</td>
			</tr>
		</table>
	</body>
</html>

