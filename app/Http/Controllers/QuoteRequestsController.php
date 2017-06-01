<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Client;
use App\Product;
use App\QuoteRequest;

use Auth;

class QuoteRequestsController extends Controller
{
	public function getIndex(Request $request) {
		$query = QuoteRequest::orderBy('id', 'desc');

		$query->where(function ($query) use ($request) {
			$query->orWhere('tenancy_name', 'like', '%' . $request->global_search . '%');
			$query->orWhere('site_name', 'like', '%' . $request->global_search . '%');
		});

		$quote_requests = $query->paginate(25);

		return view('pages.requests.quote-requests.quote-requests-list')
			->with('quote_requests', $quote_requests)
			->with('title', 'Quote Request List');
	}

	public function getView($quote_request_id) {
		$quote_request     = QuoteRequest::whereId($quote_request_id)->first();

		if (!$quote_request) {
			abort(404);
		}

		return view('pages.requests.quote-requests.quote-requests-view')
			->with('quote_request', $quote_request)
			->with('title', 'Quote Request ' . $quote_request->id);
	}

	/**
	 * Show create Quote Request form
	 * @return view
	 */
	public function getCreate() {
		$clients = Client::get();
		$products = Product::get();

		return view('pages.requests.quote-requests.quote-requests-create')->with([ 'clients' => $clients, 'products' => $products ]);
	}

	public function postCreate(Request $request) {
		$this->validate($request, [
			'client_id'                 => 'required|exists:clients,id',
			'product_id'                => '',
			'type'                      => 'required|in:1,2,3,4,5',
			'tenancy_width'             => 'required|numeric',
			'distance_from_lease_line'  => 'required|numeric',
			'ceiling_height'            => 'required|numeric',
			'dust_suppression'          => 'required|numeric',
			'specified_wind_speed'      => 'required|numeric',
			'panel_type'                => 'required|in:1,2,3,4',
			'double_door_type'          => 'required|numeric|in:1,2',
			'double_door_qty'           => 'required|numeric',
			'tenancy_name'              => 'required',
			'tenancy_number'            => 'required|numeric',
			'site_name'                 => 'required',
			'notes'                     => '',
		]);

		$quote_request = new QuoteRequest;

		$quote_request->client_id                   = $request->client_id;
		$quote_request->agent_id                    = Auth::user()->agent_id;
		$quote_request->product_id                  = $request->product_id;
		$quote_request->type                        = $request->type;
		$quote_request->tenancy_width               = $request->tenancy_width;
		$quote_request->distance_from_lease_line    = $request->distance_from_lease_line;
		$quote_request->ceiling_height              = $request->ceiling_height;
		$quote_request->dust_suppression            = $request->dust_suppression;
		$quote_request->specified_wind_speed        = $request->specified_wind_speed;
		$quote_request->panel_type                  = $request->panel_type;
		$quote_request->double_door_type            = $request->double_door_type;
		$quote_request->double_door_qty             = $request->double_door_qty;
		$quote_request->tenancy_name                = $request->tenancy_name;
		$quote_request->tenancy_number              = $request->tenancy_number;
		$quote_request->site_name                   = $request->site_name;
		$quote_request->notes                       = $request->notes;

		$quote_request->save();

		if ($request->ajax()) {
			return response()->json($quote_request);
		}

		return redirect('/quote_requests/view/' . $quote_request->id);
	}

	public function getEdit($quote_request_id) {
		$clients = Client::get();
		$products = Product::get();
		$quote_request = QuoteRequest::find($quote_request_id);

		return view('pages.requests.quote-requests.quote-requests-edit')->with([ 'clients' => $clients, 'products' => $products, 'quote_request' => $quote_request ]);
	}

	public function postEdit(Request $request, $quote_request_id) {
		$this->validate($request, [
			'client_id'                 => 'required|exists:clients,id',
			'product_id'                => '',
			'type'                      => 'required|in:1,2,3,4,5',
			'tenancy_width'             => 'required|numeric',
			'distance_from_lease_line'  => 'required|numeric',
			'ceiling_height'            => 'required|numeric',
			'dust_suppression'          => 'required|numeric',
			'specified_wind_speed'      => 'required|numeric',
			'panel_type'                => 'required|in:1,2,3,4',
			'double_door_type'          => 'required|numeric|in:1,2',
			'double_door_qty'           => 'required|numeric',
			'tenancy_name'              => 'required',
			'tenancy_number'            => 'required|numeric',
			'site_name'                 => 'required',
			'notes'                     => '',
		]);

		$quote_request = QuoteRequest::find($quote_request_id);

		$quote_request->client_id                   = $request->client_id;
		$quote_request->agent_id                    = Auth::user()->agent_id;
		$quote_request->product_id                  = $request->product_id;
		$quote_request->type                        = $request->type;
		$quote_request->tenancy_width               = $request->tenancy_width;
		$quote_request->distance_from_lease_line    = $request->distance_from_lease_line;
		$quote_request->ceiling_height              = $request->ceiling_height;
		$quote_request->dust_suppression            = $request->dust_suppression;
		$quote_request->specified_wind_speed        = $request->specified_wind_speed;
		$quote_request->panel_type                  = $request->panel_type;
		$quote_request->double_door_type            = $request->double_door_type;
		$quote_request->double_door_qty             = $request->double_door_qty;
		$quote_request->tenancy_name                = $request->tenancy_name;
		$quote_request->tenancy_number              = $request->tenancy_number;
		$quote_request->site_name                   = $request->site_name;
		$quote_request->notes                       = $request->notes;

		$quote_request->save();

		if ($request->ajax()) {
			return response()->json($quote_request);
		}

		return redirect('/quote_requests/view/' . $quote_request->id);
	}

	public function postDelete(Request $request) {
		$this->validate($request, [
			'quote_request_id' => 'required',
		]);

		$quote_request = QuoteRequest::findOrFail($request->quote_request_id);
		$quote_request->delete();

		return redirect('/quote_requests');
	}
}
