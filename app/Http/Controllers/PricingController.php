<?php

namespace App\Http\Controllers;

use App\Agent;
use App\ProductPrice;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Auth;
use DB;
use Validator;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Support\MessageBag;
use Log;

use App\Http\Requests;

class PricingController extends Controller
{

	/**
	 * This screen needs to accommodate for an admin setting prices for agents,
	 * as well as agents setting prices for their subagents.
	 *
	 * Pricing types are:
	 * F = Fixed price
	 * I = Inherit from parent agent or from product's default price
	 * N = Not available to this agent
	 */
	public function getIndex()
	{
		// Get array of products assigned to current user's agent, with
		// wholesale prices
		if (Auth::user()->isGlobalAdmin()) {
			$products = DB::table('products')
						  ->orderBy('name')
						  ->get(['id', 'name', 'default_price']);
		} else {
			$products = DB::table('product_prices AS pp')
						  ->join('products AS p', 'pp.product_id', '=', 'p.id')
						  ->where('pp.agent_id', '=', Auth::user()->agent_id)
						  ->where('pp.type', '!=', 'N')
						  ->orderBy('p.name')
						  ->get(['pp.*', 'p.id', 'p.name', 'p.default_price']);
		}

		// Get array of subagents
		$agents = Agent::byParent(Auth::user()->agent_id)->orderBy('name')->get();

		// Get array of pricing, indexed by {agent id}-{product id} and with
		// values of {type}-{price}
		$prices = DB::table('product_prices AS pp')
					->select([
						DB::raw("CONCAT(pp.type, '-', COALESCE(pp.price, '')) AS `value`"),
						DB::raw("CONCAT(a.id, '-', pp.product_id) AS `key`"),
					])
					->join('agents AS a', 'pp.agent_id', '=', 'a.id')
					->where('a.parent_agent_id', '=', Auth::user()->agent_id)
					->pluck('value', 'key');

		return view('pages.pricing-list')
			 ->with('title', 'Pricing List')
			 ->with('products', $products)
			 ->with('agents', $agents)
			 ->with('prices', $prices);
	}

	public function postSet(Request $request)
	{
		$this->validate($request, [
			'product_id' => 'required',
			'agent_id'   => 'required',
			'type'       => 'required',
			'price'      => 'required_if:type,F|numeric',
		]);

		$price = ProductPrice::whereAgentId($request->agent_id)->whereProductId($request->product_id)->first();

		if (!$price) {
			$price = new ProductPrice;
			$price->agent_id = $request->agent_id;
			$price->product_id = $request->product_id;
		}

		$price->type = $request->type;
		$price->price = $request->type == 'F' ? $request->price : null;
		$price->save();
	}

}
