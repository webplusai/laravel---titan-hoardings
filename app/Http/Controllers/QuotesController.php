<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Quote;
use App\Client;
use App\HoardingType;

class QuotesController extends Controller
{

	/**
	 * Show list of all quotes for the relevant agent
	 * @return view
	 */
	public function getIndex()
	{
		$quotes = Quote::where('agent_id', $this->user->agent_id)->orderby('id')->paginate(25);

		return view('pages.quotes-list')
				->with('quotes', $quotes)
				->with('title', 'Quotes List');
	}

	/**
	 * Show create quote form
	 * @return view
	 */
	public function getView($quote_id)
	{
		$quote = Quote::findOrFail($quote_id);

		if (!$this->user->canManageQuote($quote)) {
			abort(404);
		}

		$hoarding_type = $quote->hoardingType ? $quote->hoardingType->name : $quote->hoarding_type_other;
		$title = ucfirst($quote->description) . ' of ' . $hoarding_type . ' for ' . $quote->client->name;

		return view('pages.quotes-view')
			->with('title', $title)
			->with('quote', $quote);
	}

	/**
	 * Show create quote form
	 * @return view
	 */
	public function getCreate()
	{
		$clients = Client::where('agent_id', '=', $this->user->agent_id)->get();
		$hoarding_types = HoardingType::orderBy('name')->get();

		return view('pages.quotes-create')
			->with('title', 'Create Quote')
			->with('clients', $clients)
			->with('hoarding_types', $hoarding_types);
	}

	/**
	 * Show create quote form
	 * @return view
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'job_type'      => 'required',
			'client'        => 'required',
			'hoarding_type' => 'required',
			'payment_terms' => 'boolean',
			'first_name'    => 'required',
			'last_name'     => 'required',
			'position'      => 'required',
			'email'         => 'required|e-mail',
			'tenancy'     	=> 'required|string',
			'suburb' 	  	=> 'required|string',
			'state'		  	=> 'required|string',
			'postcode'    	=> 'required|digits:4',
			'description' 	=> 'required|string',
			'return_size' 	=> 'required|integer',
			'panel_height' 	=> 'required|integer',
			'lineal_meters' => 'required|integer',
			'travel_charge' => 'required|integer',
			'cost' 			=> 'required|integer',
			'status' 		=> 'required',
			'date' 			=> 'required',
		]);

		$quote = new Quote;

		$quote->agent_id = $this->user->agent_id;
		$quote->client_id = $request->client;
		$quote->hoarding_type_id = $request->hoarding_type;
		$quote->first_name = $request->first_name;
		$quote->last_name = $request->last_name;
		$quote->position = $request->position;
		$quote->email = $request->email;
		$quote->tenancy_name = $request->tenancy;
		$quote->suburb = $request->suburb;
		$quote->state = $request->state;
		$quote->postcode = $request->postcode;
		$quote->description = $request->description;
		$quote->return_size = $request->return_size;
		$quote->panel_height = $request->panel_height;
		$quote->lineal_meters = $request->lineal_meters;
		$quote->travel_charge = $request->travel_charge;
		$quote->cost = $request->cost;
		$quote->status = $request->status;
		$quote->quote_date = $request->date;

		$quote->save();

		return redirect('/quotes')->with('message', 'Quote succesfully created.');
	}

}