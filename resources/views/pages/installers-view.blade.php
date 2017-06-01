@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-14">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>{{ $installer->first_name }} {{ $installer->last_name }}</h5>
					</div>
					<div class="ibox-content">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-4 control-label">First Name</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $installer->first_name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Last Name</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $installer->last_name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Email</label>
								<div class="col-lg-8">
									<p class="form-control-static">{{ $installer->email }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
