@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Resources</h5>
						<div class="ibox-tools">
							<a href="javascript:show_modal('/resources/create/')" class="btn btn-primary btn-xs">Create Resource</a>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Name</th>
									<th>Description</th>
									<th>Type</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="table_body">
								@foreach ($resources as $resource)
									<tr
										data-id="{{ $resource->id }}"
										data-name="{{ $resource->name }}"
										data-description="{{ $resource->description }}"
										data-type="{{ $resource->type }}"
										data-url="{{ $resource->url }}"
										data-product-ids="{{ implode(',', $resource->products()->lists('products.id')->all()) }}">

										<td>{{ $resource->name }}</td>
										<td>{{ $resource->description }}</td>
										<td>{{ ucfirst($resource->type) }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="{{ $resource->url }}" target="_blank">View</a></li>
													<li><a href="javascript:show_modal('/resources/edit/{{ $resource->id }}')">Edit</a></li>
													<li class="divider"></li>
													<li><a href="javascript:show_modal('/resources/delete/{{ $resource->id }}')">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
