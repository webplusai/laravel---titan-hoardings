@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>View User</h5>
						<div class="ibox-tools">
							<a href="/users/edit/{{ $user->id }}" class="btn btn-default btn-xs">Edit User</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="form-horizontal">
							{{csrf_field()}}

                            <div class="row">
                                <div class="col-lg-2"><label > Name</label></div>
                                <div class="col-lg-4">
                                    <p class="form-control-static">{{ $user->first_name .' '. $user->last_name}}</p>
                                </div>

                                <div class="col-lg-2"><label >Email</label></div>
                                <div class="col-lg-4">
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-2"><label >Type</label></div>
                                <div class="col-lg-4">
                                    <p class="form-control-static">{{ ucfirst($user->type) }}</p>
                                </div>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')

@endsection