@extends(Auth::user() ? 'layouts.default' : 'layouts.minimal')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="text-center m-t-lg">
				<h1>Page Not Found</h1>

				<p>The URL you tried to visit is invalid. Looks like you hit a wall...</p>

				@if (Auth::user() && Auth::user()->isGlobalAdmin())
					<p>Since you're a global admin, maybe you need to change to a different agent to view this page.</p>
				@endif

				<br>

				@if (Auth::user())
					<p><a href="/dashboard" class="btn btn-default">Return to dashboard</a></p>
				@else
					<p><a href="/login" class="btn btn-default">Psst... try logging in</a></p>
				@endif
			</div>
		</div>
	</div>
@endsection
