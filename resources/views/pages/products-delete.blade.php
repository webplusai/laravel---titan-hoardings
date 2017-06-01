@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Delete Product</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/products/delete/{{ $product->id }}">
							{{csrf_field()}}

							<p>Are you sure you want to delete {{ $product->name }}?</p>

							<button class="btn btn-sm btn-danger" type="submit">Delete</button>
							<a href="/products" class="btn btn-sm btn-default">Cancel</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
