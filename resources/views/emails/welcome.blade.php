<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					@if(count($agent)>0)
						<p>You have been assigned as a user for {{ $agent->name }}</p>
					@else
						<p>Welcome {{ $user->first_name." ".$user->last_name }} you have been registered in the Titan Hoardings portal.</p>
					@endif
				</div>
			</div>
		</div>

	</body>
</html>