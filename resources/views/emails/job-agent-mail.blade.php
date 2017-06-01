<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<p>
						Hello {{$job->agent->name}},
						<br>
						This email contains job details of job <b>"{{ ucfirst($job->type) }} of {{ $job->hoardingType ? $job->hoardingType->name : $job->hoarding_type_other }} for {{ $job->client->name }}</b>
					 </p>
				</div>
			</div>
		</div>
	</body>
</html>