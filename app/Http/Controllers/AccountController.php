<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AccountController extends Controller
{

	public function getIndex()
	{
		$user = Auth::user();

		return view('pages.account')
			->with('user', $user)
			->with('title', 'My Account');
	}

	public function postIndex(Request $request)
	{
		$this->validate($request, [
			'first_name'        => 'required',
			'last_name'         => 'required',
			'password'          => 'confirmed',
		]);

		$user = Auth::user();

		$user->first_name = $request->get('first_name');
		$user->last_name  = $request->get('last_name');
		$user->password   = bcrypt($request->get('password'));

		$user->save();

		return redirect('/account')->with('success', 'Account successfully updated');
	}

	public function postImpersonate(Request $request)
	{
		if (!Auth::user()->isGlobalAdmin()) {
			abort(403);
		}

		$user = Auth::user();

		$user->agent_id = $request->agent_id ? $request->agent_id : null;
		$user->save();

		return redirect()->back();
	}

}
