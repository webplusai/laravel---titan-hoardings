@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							@if(Auth::user()->isGlobalAdmin())
								<a href="/products/create" class="btn btn-primary btn-xs">Create Product</a>
							@endif
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
                            <form method="GET" action="/products">
                                <div class="col-sm-12">
                                    <div class="input-group">
                                        <input type="text" placeholder="Search Products" class="input form-control" name="phrase" value="{{Request::get('phrase')}}" >
    									<span class="input-group-btn">
    										<button type="submit" class="btn btn btn-primary search-user-btn"> <i class="fa fa-search"></i> Search</button>
    									</span>
                                    </div>
                                </div>
                            </form>
                        </div>
						@if(count($products)>0)
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Buy Price</th>
										@if (!Auth::user()->agent_id)
											<th>More</th>
										@endif
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach($products as $product)
										<tr>
											<td>{{ $product->id }}</td>
											<td>{{ $product->name }}</td>
											<td>{{ $product::getPriceForAgent(Auth::user()->agent, $product->id) }}</td>
											@if (!Auth::user()->agent_id)
												<td>
													<div class="btn-group">
														<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
														<ul class="dropdown-menu">
															<li><a href="/products/view/{{$product->id}}">View</a></li>
															<li><a href="/products/edit/{{$product->id}}">Edit</a></li>
															<li class="divider"></li>
															<li><a href="/products/delete/{{$product->id}}">Delete</a></li>
														</ul>
													</div>
												</td>
											@endif
										</tr>
									@endforeach
									@if ($products->total() > 2)
										<tr>
											<td colspan="6" align="right">
												{{$products->render()}}
											</td>
										</tr>
									@endif
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No Product found in the system</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')

@endsection
