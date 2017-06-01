<?php

namespace App\Http\Controllers;

use App\Agent;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Carbon;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Invitation;

class UsersController extends Controller
{

	/**
	 * Show list of users.
	 */
	public function getIndex(Request $request)
	{
		$query = User::orderBy('id', 'desc');

		$query->where('type', '!=', 'installer');

		$query->where(function($query) use($request) {
			$query->orWhere('first_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('last_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('email', 'like', '%'.$request->global_search.'%');
		});

		if ($this->user->agent_id) {
			$query->where('agent_id', $this->user->agent_id);
			$query->where('type', '!=', 'global-admin');
		} else {
			$query->where('type', 'global-admin');
		}

		$users = $query->paginate(25);

		return view('pages.users-list')
			->with('users', $users)
			->with('title', 'User List');
	}

	/**
	 * Show create user form
	 * @return view
	 */
	public function getCreate()
	{
		$user = Auth::user();

		return view('pages.users-create')
			->with('agents', Agent::all())
			->with('user', $user);
	}

	/**
	 * Handle saving of user data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email',
			'password'   => 'required|confirmed',
			'type'       => 'required|in:agent-user,agent-admin,global-admin',
			'agent_id'   => 'required_if:type,agent-admin,agent-user',
		]);

		$user = new User;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->password = bcrypt($request->password);
		$user->type = $request->type;

		if ($user->type == 'agent-admin' || $user->type == 'agent-user') {
			$user->agent_id = $request->agent_id;
		}

		$user->save();

		$user->sendInvitation();

		if (Auth::user()->agent_id) {
			return Redirect::to('/agents/view/' . Auth::user()->agent_id);
		}

		return redirect('/users');
	}

	public function getView($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('pages.users-view')
			 ->with('user', $user)
			 ->with('title', 'View User');
	}

	/**
	 * Show edit user page
	 * @param  int $user_id
	 * @return view
	 */
	public function getEdit($user_id, $agent_id = null)
	{
		$user = User::findOrFail($user_id);

		return view('pages.users-edit')
			->with('user', $user)
			->with('agent_id', $agent_id)
			->with('agents', Agent::all())
			->with('title', 'Edit User');
	}

	/**
	 * Handle edit user data
	 * @param  Request $request
	 * @param  int     $user_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $user_id)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email,'.$user_id,
			'password'   => 'confirmed',
			'type'       => 'required|in:agent-user,agent-admin,global-admin',
			'agent_id'   => 'required_if:type,agent-admin,agent-user',
		]);

		$user = User::find($user_id);
		$user->first_name = $request->get('first_name', '');
		$user->last_name = $request->get('last_name', '');
		$user->email = $request->get('email', '');

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		$user->type = $request->type;

		if ($user->type == 'agent-admin' || $user->type == 'agent-user') {
			$user->agent_id = $request->agent_id;
		}

		$user->save();

		return redirect('/users');
	}

	public function postDelete(Request $request, $user_id)
	{
		$user = User::findOrFail($user_id);
		$user->delete();
	}

	/**
	 * Show user invite page
	 * @return view
	 */
	public function getInvite()
	{
		return view('pages.users-invite')
			->with('title', 'Invite User');
	}

	/**
	 * Handle user invite data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postInvite(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required|max:255',
			'last_name'  => 'required|max:255',
			'email'      => 'required|email|max:255|unique:users',
			'type'       => 'required',
		]);

		$user = new User;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->type = $request->type;
		$user->save();

		$invitation = new Invitation;
		$invitation->token = substr(md5(microtime()), 0, 10);
		$invitation->user_id = $user->id;
		$invitation->save();

		$invitation->send();

		return redirect('/users');
	}

}
