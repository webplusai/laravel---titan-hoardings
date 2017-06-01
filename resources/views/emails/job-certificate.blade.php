<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	{{-- <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<meta charset="utf-8"> --}}
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title>Certificate </title>
	 <!-- Bootstrap css -->
	<link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{url('css/certificate.css')}}" rel="stylesheet" />
	<style>
		.divider-2 {
			background-image: url("{{url("images/certificate-images/right-side.png")}}");
		}
	</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<img src="{{url('images/certificate-images/erc-titan-hoarding-cert-of-installation.png')}}" class="img-responsive">
			</div>
		</div>
		<div class="row divider-2 mar-top30">
			<div class="col-xs-4">
					<div class="form-group">
						<label>Date of Installation <span class="edited-text">13/10/2016</span></label>
					</div>
					<div class="form-group">
						<label>Client Name: <span class="edited-text"></span>{{$job->client->name}}</label>
					</div>
					<div class="form-group">
						<label>Location: <span class="edited-text">{{$job->address}}</span></label>
					</div>
					<div class="form-group">
						<label>Tenancy: <span class="edited-text">ABCD</span></label>
					</div>
					<h4>Hoarding installation detail <xsall class="red">(HID)</xsall></h4>
					<div class="form-group">
						<label>Hoarding Type: <span class="edited-text">{{$job->hoardingType->name}}</span></label>
					</div>
					<div class="form-group">
						<label>Panel Type: <span class="edited-text">white board</span></label>
					</div>
					<div class="form-group">
						<label>Height: <span class="edited-text">{{$job->total_height}}</span></label>
					</div>
					<div class="form-group">
						<label>L/m: <span class="edited-text">{{$job->total_length}}</span></label>
					</div>
					<h4>Hoarding installation detail <xsall class="red">(HID)</xsall></h4>
					<div class="form-group">
						<label>Australian Standard: <span class="edited-text">AS 4687</span></label>
					</div>
					<p>Signed by, <img></p>
					<p>Greg Bloom <br> Managing Director <br> TITAN Hoarding Systems Australia PL </p>
			</div>
			<div class="col-xs-8  double-divider">
				<p>This certificate certifies that the hoarding system installed has been constructed in accordance with:</p>
				<ul>
					<li>The Building Code of Australia (BCA)</li>
					<li>Structural Engineer' s specification and certification</li>
					<li>TITAN Hoarding Systems designs and specifications</li>
					<li>Australian Standards (AS) and related clauses:</li>
				</ul>
				<ul class="list-style-none">
					<li>AS 4687 Clause 4.1 Simulated climbing test
						<ul class="list-style-none padd-left67">
							<li>Clause 4.3 Impact loading as per the <span class="red">HID</span></li>
							<li>Clause 4.5 Wind loading as per the <span class="red">HID</span></li>
						</ul>
					</li>
					<li>AS 1720 -1997 Timber Structures Code Part 1 - Design Methods</li>
					<li>AS 1664.1 -1997 Aluminium Structures - Limited State Design</li>
					<li>AS1170.1 -2002 Balustrade Crowd Loading (BCL) <span class="red">HSD if nominated</span></li>
				</ul>
				<p>The certificate is to be used in conjunction with the following documents that have / must be completed at the time on installation:</p>
				<ul>
					<li>Job Start Risk Assesxsent (JSRA)</li>
					<li>An installers signed Hoarding installation Check List detailing compliance with specific BCA, AS, Engineer's and TITAN's specification and certification.</li>
				</ul>
				<p>A copy of the completed installation check list and JSRA will accompany the invoice with this certificate as part of the TITAN Hoarding Quality Assured Systems.</p>
				<h4>Signed <span class="edited-text">{{$job->primaryInstaller->first_name}} {{$job->primaryInstaller->last_name}}</span></h4>
				<h4>By <span class="edited-text">{{$job->primaryInstaller->first_name}} {{$job->primaryInstaller->last_name}}</span> on behalf of <br>Eagle Rock Construction (Qld) Pty Ltd</h4>
			</div>
			<div class="col-xs-12 comment-div">
				<h4 class="contact-us"> <span>p </span> 1300 046 273 <span>e </span> <a href="mailto:hoardings@eaglerock.net.au">hoardings@eaglerock.net.au</a> <span>w </span> <a href="http://www.titanhoardingsystems.com.au/">www.titanhoardingsystems.com.au</a></h4>
			</div>
		</div>
		<div class="row">
			<div class="row">
				<div class="col-xs-12 mrg-top5">
					<img src="{{url('images/certificate-images/footer.png')}}" class="img-responsive">
				</div>
			</div>
		</div>
	</div>

	<script src="{{url('js/jquery-2.1.1.js')}}"></script>
	<script src="{{url('js/bootstrap.min.js')}}"></script>
</body>
</html>