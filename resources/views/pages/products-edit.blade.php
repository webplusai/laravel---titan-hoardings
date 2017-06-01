@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit Product</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/products/edit/{{ $product->id }}">
							{{csrf_field()}}

							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Product Name</label>
								<div class="col-lg-3">
									<input type="text" placeholder="Product Name" name="name" class="form-control" value="{{ old('name', $product->name) }}">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('default_price') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Default Price</label>
								<div class="col-lg-3">
									<div class="input-group">
										<span class="input-group-addon">$</span>
										<input type="number" step="any" placeholder="00.00" name="default_price" class="form-control" value="{{ old('default_price', $product->default_price) }}">
										<span class="input-group-addon">per lineal meter</span>
									</div>
									@if ($errors->has('default_price'))
										<span class="help-block"><strong>{{ $errors->first('default_price') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('height_of_panel') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Height of Panel</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="number" step="any" placeholder="Height Of Panel" name="height_of_panel" class="form-control" value="{{ old('height_of_panel', $product->height_of_panel) }}">
										<span class="input-group-addon">meters</span>
									</div>
									@if ($errors->has('height_of_panel'))
										<span class="help-block"><strong>{{ $errors->first('height_of_panel') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('height_of_dust_supression') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Height of Dust Supression</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="number" step="any" class="form-control" placeholder="Height of dust panel" name="height_of_dust_supression" value="{{ old('height_of_dust_supression', $product->height_of_dust_supression) }}">
										<span class="input-group-addon">meters</span>
									</div>
									@if ($errors->has('height_of_dust_supression'))
										<span class="help-block"><strong>{{ $errors->first('height_of_dust_supression') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Width</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="number" step="any" placeholder="width" name="width" class="form-control" value="{{ old('width', $product->width) }}">
										<span class="input-group-addon">meters</span>
									</div>
									@if ($errors->has('width'))
										<span class="help-block"><strong>{{ $errors->first('width') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('depth') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Depth</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="number" step="any" class="form-control" placeholder="Depth" name="depth" value="{{ old('depth', $product->depth) }}">
										<span class="input-group-addon">meters</span>
									</div>
									@if ($errors->has('depth'))
										<span class="help-block"><strong>{{ $errors->first('depth') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('weight') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Weight</label>
								<div class="col-lg-3">
									<div class="input-group">
										<input type="number" step="any" placeholder="Weight" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}">
										<span class="input-group-addon">kg</span>
									</div>
									@if ($errors->has('weight'))
										<span class="help-block"><strong>{{ $errors->first('weight') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('wind_rating') ? ' has-error' : '' }}">
								<label class="col-lg-2 control-label">Wind Rating</label>
								<div class="col-lg-3">
									<input type="number" step="any" class="form-control" placeholder="Wind Rating" name="wind_rating" value="{{ old('wind_rating', $product->wind_rating) }}">
									@if ($errors->has('wind_rating'))
										<span class="help-block"><strong>{{ $errors->first('wind_rating') }}</strong></span>
									@endif
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-white" type="submit">Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
