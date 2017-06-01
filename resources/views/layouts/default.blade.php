<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Titan Hordings Portal | {{ $title or '' }}</title>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">

	<link href="/css/animate.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
	<link href="/css/bootstrap-datepicker3.min.css" rel="stylesheet">
	<link href="/css/bootstrap-clockpicker.min.css" rel="stylesheet">
	@yield('css')

</head>

<body>

<div id="wrapper">

	@if (Auth::user()->isGlobalAdmin())
		<div id="admin-bar">
			<form action="/account/impersonate" method="post" class="form-inline">
				<select name="agent_id" class="form-control" onchange="this.form.submit()">
					<option value="">Global Admin</option>
					<optgroup label="Act as agent">
						@foreach ($_agents as $_agent)
							<option value="{{ $_agent->id }}"{{ $_agent->id == Auth::user()->agent_id ? ' selected' : '' }}>{{ $_agent->name }}</option>
						@endforeach
					</optgroup>
				</select>
				{!! csrf_field() !!}
			</form>
		</div>
	@endif

	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="clear">
								<span class="block m-t-xs">
									<strong class="font-bold">{{ Auth::user()->first_name .' '. Auth::user()->last_name }}</strong>
								</span>

								<span class="text-muted text-xs block">
									@if (Auth::user()->isGlobalAdmin())
										Global Admin
									@elseif (Auth::user()->isAgentAdmin())
										Agent Admin
									@elseif (Auth::user()->isAgentUser())
										Agent User
									@else
										Installer
									@endif
									<b class="caret"></b>
								</span>
							</span>
						</a>

						<ul class="dropdown-menu animated fadeInRight m-t-xs">
							@if (Auth::user()->isAgentAdmin())
								<li><a href="/import">Import Data</a></li>
							@endif
							<li><a href="/account">My Account</a></li>
							<li><a href="/logout">Logout</a></li>
						</ul>
					</div>
					<div class="logo-element">
						TH
					</div>
				</li>
				@if (Auth::user()->isGlobalAdmin() && !Auth::user()->agent_id)
					<!-- Global admin nav -->
					<li class="{{ Request::is( 'admin/dashboard*') ? 'active' : '' }}"><a href="/admin/dashboard"><i class="fa fa-tasks"></i> <span class="nav-label">Dashboard</span></a></li>
					<li class="{{ Request::is( 'products*') ? 'active' : '' }}"><a href="/products"><i class="fa fa-wrench"></i> <span class="nav-label">Products</span></a></li>
					<li class="{{ Request::is( 'pricing*') ? 'active' : '' }}"><a href="/pricing"><i class="fa fa-usd"></i> <span class="nav-label">Pricing</span></a></li>
					<li class="{{ Request::is( 'agents*') ? 'active' : '' }}"><a href="/agents"><i class="fa fa-building"></i> <span class="nav-label">Agents</span></a></li>
					<li class="{{ Request::is( 'resources*') ? 'active' : '' }}"><a href="/resources"><i class="fa fa-graduation-cap"></i> <span class="nav-label">Resources</span></a></li>
					<li class="{{ Request::is( 'users*') ? 'active' : '' }}"><a href="/users"><i class="fa fa-users"></i> <span class="nav-label">Admin Users</span></a></li>
				@elseif (Auth::user()->isAgentAdmin() || Auth::user()->isGlobalAdmin())
					<!-- Agent admin nav -->
					{{-- <li class="{{ Request::is( 'quotes*') ? 'active' : '' }}"><a href="/quotes"><i class="fa fa-tasks"></i> <span class="nav-label">Quotes</span></a></li> --}}
					<li class="{{ Request::is( 'dashboard*') ? 'active' : '' }}"><a href="/dashboard"><i class="fa fa-tasks"></i> <span class="nav-label">Dashboard</span></a></li>
					<li class="{{ Request::is( 'jobs*') ? 'active' : '' }}"><a href="/jobs"><i class="fa fa-tasks"></i> <span class="nav-label">Jobs</span></a></li>
					<li class="{{ Request::is( 'clients*') ? 'active' : '' }}"><a href="/clients"><i class="fa fa-briefcase"></i> <span class="nav-label">Clients</span></a></li>
					<li class="{{ Request::is( 'installers*') ? 'active' : '' }}"><a href="/installers"><i class="fa fa-address-card"></i> <span class="nav-label">Installers</span></a></li>
					<li class="{{ Request::is( 'products*') ? 'active' : '' }}"><a href="/products"><i class="fa fa-wrench"></i> <span class="nav-label">Products</span></a></li>
					<li class="{{ Request::is( '*requests*') ? 'active' : '' }}">
						<a href="/requests"><i class="fa fa-th-large"></i> <span class="nav-label">Requests</span> <span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="/service_requests">Service Requests</a></li>
							<li><a href="/quote_requests">Quote Requests</a></li>
							<li><a href="/booking_requests">Booking Requests</a></li>
						</ul>
					</li>
					@if (Auth::user()->isRootAgentAdmin() || (Auth::user()->isGlobalAdmin() && !Auth::user()->agent->parent_agent_id))
						<li class="{{ Request::is( 'pricing*') ? 'active' : '' }}"><a href="/pricing"><i class="fa fa-usd"></i> <span class="nav-label">Pricing</span></a></li>
						<li class="{{ Request::is( 'agents*') ? 'active' : '' }}"><a href="/agents"><i class="fa fa-building"></i> <span class="nav-label">Sub-agents</span></a></li>
					@endif
					<li class="{{ Request::is( 'users*') ? 'active' : '' }}"><a href="/users"><i class="fa fa-users"></i> <span class="nav-label">Users</span></a></li>
				@elseif (Auth::user()->isAgentUser())
					<!-- Agent user nav -->
					<li class="{{ Request::is( 'jobs*') ? 'active' : '' }}"><a href="/jobs"><i class="fa fa-tasks"></i> <span class="nav-label">Jobs</span></a></li>
					<li class="{{ Request::is( 'clients*') ? 'active' : '' }}"><a href="/clients"><i class="fa fa-briefcase"></i> <span class="nav-label">Clients</span></a></li>
					<li class="{{ Request::is( 'installers*') ? 'active' : '' }}"><a href="/installers"><i class="fa fa-address-card"></i> <span class="nav-label">Installers</span></a></li>
					<li class="{{ Request::is( 'products*') ? 'active' : '' }}"><a href="/products"><i class="fa fa-wrench"></i> <span class="nav-label">Products</span></a></li>
				@elseif (Auth::user()->isInstaller())
					<!-- Installer nav -->
					<li class="{{ Request::is( 'installer/dashboard*') ? 'active' : '' }}"><a href="/installer/dashboard"><i class="fa fa-tasks"></i> <span class="nav-label">Dashboard</span></a></li>
					<li class="{{ Request::is( 'installer/jobs*') ? 'active' : '' }}"><a href="/installer/jobs"><i class="fa fa-tasks"></i> <span class="nav-label">Jobs</span></a></li>
				@endif
			</ul>
		</div>
	</nav>

	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom">
			<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
					<form role="search" class="navbar-form-custom" method="post" action="#">
						<div class="form-group">
							<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
						</div>
					</form>
				</div>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<a href="/logout">
							<i class="fa fa-sign-out"></i> Log out
						</a>
					</li>
				</ul>

			</nav>
		</div>
		<div class="wrapper wrapper-content">

			@yield('content')

		</div>
		<div class="footer">
			<div>
				<strong>Copyright</strong> Titan Hoardings &copy; {{ date("Y") }}
			</div>
		</div>

	</div>
</div>

<!-- Mainly scripts -->
<script src="/js/jquery-2.1.1.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/bootbox.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
<script src="/js/bootstrap-datepicker.min.js"></script>
<script src="/js/bootstrap-clockpicker.min.js"></script>
<script src="/js/common.js"></script>
<script src="/js/cleave/cleave.min.js"></script>
<script src="/js/cleave/addons/cleave-phone.au.js"></script>

@yield('scripts')

</body>

</html>
