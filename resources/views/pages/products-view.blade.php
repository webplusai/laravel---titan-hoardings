@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>View Product</h5>
					</div>
					<div class="ibox-content">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label">Product Name</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->name }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Default Price</label>
								<div class="col-lg-4">
									<p class="form-control-static">${{ $product::getPriceForAgent(Auth::user()->agent, $product->id) }}</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Height of Panel</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->height_of_panel }} metres</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Height of Dust Supression</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->height_of_dust_supression }} metres</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Width</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->width }} metres</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Depth</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->depth }} metres</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Weight</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->weight }} kg</p>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Wind Rating</label>
								<div class="col-lg-4">
									<p class="form-control-static">{{ $product->wind_rating }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
