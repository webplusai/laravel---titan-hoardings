<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Device;
use App\Invitation;

class UsersController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => [
			'postCreate',
			'getInvite',
		]]);
	}

	public function getProfile($user_id = null)
	{
		$id = $user_id ? $user_id : Auth::guard('api')->user()->id;
		$user = User::findOrFail($id);

		$data = [
			'id'                        => $user->id,
			'agent_id'                  => $user->agent_id,
			'first_name'                => $user->first_name,
			'last_name'                 => $user->last_name,
			'type'                      => $user->type,
			'email'                     => $user->email,
			'timezone'                  => $user->timezone,
		];

		return response()->json($data);
	}

	public function getInvite(Request $request, $invitation_token = null)
	{
		if (!$invitation_token) {
			return response()->json(['Error' => 'No token provided']);
		}

		$invitation = Invitation::whereToken($invitation_token)->first();

		if (!$invitation) {
			return response()->json(['Error' => 'No invitation found']);
		} else {
			return response()->json($invitation->user);
		}
	}

	public function postEdit(Request $request, $user_id)
	{
		$this->validate($request, [
			'first_name'            => 'required',
			'last_name'             => 'required',
			'email'                 => 'required|email|unique:users,email,'.$user_id,
			'password'              => 'confirmed',
			'password_confirmation' => '',
		]);

		$user = User::where('type', 'installer')
					->where('agent_id', Auth::guard('api')->user()->agent_id)
					->findOrFail($user_id);

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		$user->save();

		return response()->json(['status' => 'success']);
	}

}
