<?php

namespace App\Http\Controllers;

use App\Agent;
use App\User;
use Illuminate\Http\Request;
use Log;
use Auth;

use App\Http\Requests;

class AgentsController extends Controller
{

	/**
	 * Show list of all Agents
	 * @return view
	 */
	public function getIndex(Request $request)
	{
		$query = Agent::orderBy('name');

		if ($request->phrase) {
			$query->where(function ($query) use ($request) {
				$query->orWhere('name', 'like', '%' . $request->phrase . '%');
				$query->orWhere('email', 'like', '%' . $request->phrase . '%');
			});
		}

		$query->where('parent_agent_id', $this->user->agent_id);

		$agents = $query->paginate(25);

		return view('pages.agents-list')
			->with('agents', $agents)
			->with('title', 'Agent List');
	}

	/**
	 * Show create agent form
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.agents-create');
	}

	/**
	 * Handle saving of agent data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'              => 'required',
			'email'             => 'required|email|unique:agents,email',
			'billing_email'     => 'email',
			'abn'               => 'required|numeric|digits_between:9,11',
			'billing_address'   => '',
			'billing_suburb'    => '',
			'billing_state'     => 'in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'billing_postcode'  => 'digits:4',
			'shipping_address'  => 'required',
			'shipping_suburb'   => 'required',
			'shipping_state'    => 'required|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'shipping_postcode' => 'required|digits:4',
			'phone'             => 'required|max:10',
			'mobile'            => 'required|max:10',
			'fax'               => 'max:10',
			'bank_acc_no'       => 'numeric',
			'bank_acc_bsb'      => 'numeric'
		]);

		$agent = new Agent;

		$agent->name = $request->name;
		$agent->email = $request->email;
		$agent->abn = $request->abn;
		$agent->billing_address = $request->get('billing_address', '');
		$agent->billing_suburb = $request->get('billing_suburb', '');
		$agent->billing_state = $request->get('billing_state', '');
		$agent->billing_postcode = $request->get('billing_postcode', '');
		$agent->shipping_address = $request->shipping_address;
		$agent->shipping_suburb = $request->shipping_suburb;
		$agent->shipping_state = $request->shipping_state;
		$agent->shipping_postcode = $request->shipping_postcode;
		$agent->phone = $request->get('phone', '');
		$agent->mobile = $request->get('mobile', '');
		$agent->fax = $request->fax;
		$agent->billing_email = $request->get('billing_email', '');
		$agent->bank_acc_name = $request->get('bank_acc_name', '');
		$agent->bank_acc_no = $request->get('bank_acc_no', '');
		$agent->bank_acc_bsb = $request->get('bank_acc_bsb', '');
		$agent->parent_agent_id = Auth::user() ? Auth::user()->agent_id : 0;

		$agent->save();

		return redirect('/agents/view/' . $agent->id);
	}

	/**
	 * Show edit agent page
	 * @param  int $agent_id
	 * @return view
	 */
	public function getEdit($agent_id)
	{
		$agent = Agent::findOrFail($agent_id);

		return view('pages.agents-edit')
			->with('agent', $agent)
			->with('title', 'Edit Agent');
	}

	/**
	 * Handle edit agent data
	 * @param  Request $request
	 * @param  int     $agent_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $agent_id)
	{
		$this->validate($request, [
			'name'              => 'required',
			'email'             => 'required|email|unique:agents,email,'.$agent_id,
			'billing_email'     => 'email',
			'abn'               => 'required|numeric',
			'billing_address'   => '',
			'billing_suburb'    => '',
			'billing_state'     => 'in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'billing_postcode'  => 'digits:4',
			'shipping_address'  => 'required',
			'shipping_suburb'   => 'required',
			'shipping_state'    => 'required|in:ACT,NSW,NT,QLD,SA,TAS,VIC,WA',
			'shipping_postcode' => 'required|digits:4',
			'phone'             => 'required|max:10',
			'mobile'            => 'max:10',
			'fax'               => 'max:10',
			'bank_acc_no'       => 'numeric',
			'bank_acc_bsb'      => 'numeric'
		]);

		$agent = Agent::find($agent_id);

		$agent->name = $request->name;
		$agent->email = $request->email;
		$agent->abn = $request->abn;
		$agent->billing_address = $request->get('billing_address', '');
		$agent->billing_suburb = $request->get('billing_suburb', '');
		$agent->billing_state = $request->get('billing_state', '');
		$agent->billing_postcode = $request->get('billing_postcode', '');
		$agent->shipping_address = $request->shipping_address;
		$agent->shipping_suburb = $request->shipping_suburb;
		$agent->shipping_state = $request->shipping_state;
		$agent->shipping_postcode = $request->shipping_postcode;
		$agent->phone = $request->phone;
		$agent->mobile = $request->mobile;
		$agent->fax = $request->fax;
		$agent->billing_email = $request->billing_email;
		$agent->bank_acc_name = $request->bank_acc_name;
		$agent->bank_acc_no = $request->bank_acc_no;
		$agent->bank_acc_bsb = $request->bank_acc_bsb;

		$agent->save();

		return redirect('/agents');
	}

	/**
	 * Show Agent page
	 * @param  int $agent_id
	 * @return view
	 */
	public function getView($agent_id)
	{
		$agent = Agent::findOrFail($agent_id);

		$users = User::where('agent_id', '!=', $agent_id)
			->whereIn('type', ['agent-admin', 'agent-user'])
			->orderBy('first_name')
			->orderBy('last_name')
			->get();

		return view('pages.agents-view')
			->with('agent', $agent)
			->with('users', $users)
			->with('title', 'View Agent');
	}

	/**
	 * Deletes user
	 */
	public function postDelete(Request $request)
	{
		$this->validate($request, [
			'agent_id' => 'required',
		]);

		$agent = Agent::findOrFail($request->agent_id);
		$agent->delete();
	}

	public function getCreateUser($agent_id)
	{
		return view('modals.agents-create-user')
			->with('agent_id', $agent_id);
	}

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreateUser(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email',
			'password'   => 'required|confirmed',
			'type'       => 'required|in:agent-user,agent-admin',
			'agent_id'   => 'required',
		]);

		$user = new User;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->type = $request->type;
		$user->agent_id = $request->agent_id;

		$user->save();
	}

}
