@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
					</div>
					<div class="ibox-content">

						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td></td>
									@foreach ($products as $product)
										<th>{{ $product->name }}</th>
									@endforeach
								</tr>
								<tr>
									<td><strong>Your Price</strong></td>
									@foreach ($products as $product)
										<td><strong>${{ number_format(\App\Product::getPriceForAgent(Auth::user()->agent, $product->id), 2) }}</strong></td>
									@endforeach
								</tr>
							</thead>
							<tbody>
								@foreach ($agents as $agent)
									<tr data-agent-id="{{ $agent->id }}">
										<td>{{ $agent->name }}</td>
										@foreach ($products as $product)
											<?php
											$price_key = $agent->id . '-' . $product->id;
											if (isset($prices[$price_key])) {
												list($type, $price) = explode('-', $prices[$price_key]);
											} else {
												$type = 'N';
												$price = '';
											}
											?>
											<td data-product-id="{{ $product->id }}" data-type="{{ $type }}" data-fixed-price="{{ $price }}">
												@if ($type == 'F')
													${{ number_format($price, 2) }} <span class="text-muted">(fixed)</span>
												@elseif ($type == 'I')
													${{ number_format(\App\Product::getPriceForAgent($agent, $product->id), 2) }} <span class="text-muted">(my price)</span>
												@else
													<span class="text-muted">N/A</span>
												@endif
											</td>
										@endforeach
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

@section('scripts')
	<style type="text/css">
		td[data-product-id]:hover {
			cursor: pointer;
			background: #ddd;
		}
	</style>
	<script src="/js/modalform.js"></script>
	<script>
		// When a cell is clicked
		$('td[data-product-id]').on('click', function() {
			var cell = $(this);

			modalform.dialog({
				bootbox: {
					title: 'Set Price',
					message: ''+
						'<form action="/pricing/set" method="POST" class="form-horizontal">'+
							'<input type="hidden" name="agent_id" value="'+cell.closest('tr').data('agent-id')+'">'+
							'<input type="hidden" name="product_id" value="'+cell.data('product-id')+'">'+
							'<div class="form-group">'+
								'<div class="col-md-9">'+
									'<div class="radio"><label><input type="radio" name="type" value="N"> N/A - Make product unavailable to this agent</label></div>'+
									'<div class="radio"><label><input type="radio" name="type" value="I"> My price - Use my price, even if my price changes</label></div>'+
									'<div class="radio"><label><input type="radio" name="type" value="F"> Fixed</label></div>'+
									'<div class="input-group" id="fixed-box">'+
										'<span class="input-group-addon">$</span>'+
										'<input type="text" name="price" value="'+cell.attr('data-fixed-price')+'" class="form-control">'+
									'</div>'+
								'</div>'+
							'</div>'+
							'{{csrf_field()}}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Save Changes',
							className: 'btn-primary'
						}
					}
				},
				after_init : function() {
					// Make fixed box appear/disappear when needed
					$('input[name="type"]').on('click', function() {
						if ($(this).val() == 'F') {
							$('#fixed-box').show();
							$('#fixed-box input').select();
						} else {
							$('#fixed-box').hide();
						}
					});

					$('input[name="type"][value="' + cell.data('type') + '"]').click();
				}
			});
		});
	</script>
@endsection
