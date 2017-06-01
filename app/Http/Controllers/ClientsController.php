<?php

namespace App\Http\Controllers;

use App\Client;
use App\ClientHoardingMaterial;
use App\HoardingMaterial;
use App\Contact;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Agent;
use Auth;

class ClientsController extends Controller
{

	/**
	 * Show list of all Clients
	 * @return [type] [description]
	 */
	public function getIndex(Request $request)
	{
		$query = Client::orderBy('id', 'desc');

		$query->where(function ($query) use ($request) {
			$query->orWhere('name', 'like', '%' . $request->global_search . '%');
			$query->orWhere('email', 'like', '%' . $request->global_search . '%');
		});

		$query->where('agent_id', Auth::user()->agent_id);

		$clients = $query->paginate(25);

		return view('pages.clients-list')
			->with('clients', $clients)
			->with('title', 'Client List');
	}

	public function getView($client_id)
	{
		$client     = Client::whereId($client_id)->whereAgentId(Auth::user()->agent_id)->first();

		if (!$client) {
			abort(404);
		}

		$client_hoarding_material_ids = $client->hoardingMaterials->pluck('hoarding_material_id');
		$hoarding_materials = '';

		foreach ($client_hoarding_material_ids as $id) {
			$hoarding_materials .= HoardingMaterial::find($id)->name . ', ';
		}

		$hoarding_materials = substr( $hoarding_materials, 0, strlen($hoarding_materials) - 2 );

		return view('pages.clients-view')
			->with('client', $client)
			->with('hoarding_materials', $hoarding_materials)
			->with('title', $client->name);
	}

	/**
	 * Show create Client form
	 * @return view
	 */
	public function getCreate()
	{
		$agents = Agent::get();

		return view('pages.clients-create')->with('agents', $agents);
	}

	/**
	 * Handle saving of Client data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'              => 'required',
			'email'             => 'required|email|unique:clients,email',
			'billing_email'     => '',
			'abn'               => 'required|numeric|digits_between:9,11',
			'billing_address'   => 'required',
			'billing_suburb'    => 'required',
			'billing_state'     => 'required|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'billing_postcode'  => 'required|digits:4',
			'shipping_address'  => '',
			'shipping_suburb'   => '',
			'shipping_state'    => 'in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'shipping_postcode' => 'digits:4',
			'phone'             => 'digits_between:8,10',
			'mobile'            => 'digits_between:8,10',
			'fax'               => 'digits_between:8,10',
		]);

		$client = new Client;

		$client->name              = $request->name;
		$client->email             = $request->email;
		$client->agent_id          = Auth::user()->agent_id;
		$client->abn               = $request->abn;
		$client->billing_address   = $request->billing_address;
		$client->billing_suburb    = $request->billing_suburb;
		$client->billing_state     = $request->billing_state;
		$client->billing_postcode  = $request->billing_postcode;
		$client->shipping_address  = $request->shipping_address;
		$client->shipping_suburb   = $request->shipping_suburb;
		$client->shipping_state    = $request->shipping_state;
		$client->shipping_postcode = $request->shipping_postcode;
		$client->phone             = $request->phone;
		$client->mobile            = $request->mobile;
		$client->fax               = $request->get('fax', '');
		$client->billing_email     = $request->billing_email;

		$client->save();

		if ($request->ajax()) {
			return response()->json($client);
		}

		return redirect('/clients/view/' . $client->id);
	}

	/**
	 * Show edit Client page
	 * @param  int $client_id
	 * @return view
	 */
	public function getEdit($client_id)
	{
		$client = Client::findOrFail($client_id);
		$client_hoarding_material_ids = $client->hoardingMaterials->pluck('hoarding_material_id');
		$all_hoarding_materials = HoardingMaterial::all();
		$hoarding_text = '';

		foreach ($client_hoarding_material_ids as $id) {
			$hoarding_text .= HoardingMaterial::find($id)->name . ', ';
		}

		return view('pages.clients-edit')
			->with('client', $client)
			->with('client_hoarding_material_ids', $client_hoarding_material_ids)
			->with('all_hoarding_materials', $all_hoarding_materials)
			->with('title', 'Edit Client');
	}

	/**
	 * Handle edit Client data
	 * @param  Request $request
	 * @param  int     $client_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $client_id)
	{
		$client = Client::find($client_id);

		$this->validate($request, [
			'name'              => 'required',
			'email'             => 'required|email|unique:clients,email,' . $client->id,
			'billing_email'     => '',
			'abn'               => 'required|numeric|digits_between:9,11',
			'billing_address'   => 'required',
			'billing_suburb'    => 'required',
			'billing_state'     => 'required|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'billing_postcode'  => 'required|digits:4',
			'shipping_address'  => '',
			'shipping_suburb'   => '',
			'shipping_state'    => 'in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'shipping_postcode' => 'digits:4',
			'phone'             => 'digits_between:8,10',
			'mobile'            => 'digits_between:8,10',
			'fax'               => 'digits_between:8,10',
		]);

		$client->name              = $request->name;
		$client->email             = $request->email;
		$client->abn               = $request->abn;
		$client->billing_address   = $request->billing_address;
		$client->billing_suburb    = $request->billing_suburb;
		$client->billing_state     = $request->billing_state;
		$client->billing_postcode  = $request->billing_postcode;
		$client->shipping_address  = $request->shipping_address;
		$client->shipping_suburb   = $request->shipping_suburb;
		$client->shipping_state    = $request->shipping_state;
		$client->shipping_postcode = $request->shipping_postcode;
		$client->phone             = $request->phone;
		$client->mobile            = $request->mobile;
		$client->fax               = $request->fax;
		$client->billing_email     = $request->billing_email;

		$client->save();

		ClientHoardingMaterial::where('client_id', $client_id)->delete();
		foreach ((array) $request->hoarding_material_ids as $id) {
			ClientHoardingMaterial::create( [ 'client_id' => $client_id, 'hoarding_material_id' => $id ] );
		}

		return redirect('/clients');
	}

	/**
	 * Delete Client
	 * @return redirect
	 */
	public function postDelete(Request $request)
	{
		$this->validate($request, [
			'client_id' => 'required',
		]);

		$client = Client::findOrFail($request->client_id);
		Contact::where('client_id', $request->client_id)->delete();
		$client->delete();

		return redirect('/clients');
	}

	public function getTypeahead($phrase = null)
	{
		$query = Client::query();

		$query->whereAgentId(Auth::user()->agent_id);

		if ($phrase) {
			$query->where('name', 'LIKE', $phrase . '%');
		}

		$clients = $query->orderBy('name')
			->take(10)
			->get([
				'id',
				'name',
				'shipping_address as address',
				'shipping_suburb as suburb',
				'shipping_state as state',
				'shipping_postcode as postcode',
			]);

		return response()->json($clients);
	}

}

