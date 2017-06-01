<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Storage;
use InterventionImage;
use App\Device;

class AccountController extends Controller
{

	public function getProfile()
	{
		$user = Auth::guard('api')->user();

		$data = [
			'id'                        => $user->id,
			'agent_id'                  => $user->agent_id,
			'first_name'                => $user->first_name,
			'last_name'                 => $user->last_name,
			'type'                      => $user->type,
			'email'                     => $user->email,
			'phone_main'                => $user->phone_main,
			'phone_mobile'              => $user->phone_mobile,
			'timezone'                  => $user->timezone,
		];

		return response()->json($data);
	}

	public function postEdit(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required',
			'password'   => 'confirmed',
		]);

		$user = Auth::guard('api')->user();

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		$user->save();

		return response()->json('success', 200);
	}

	public function postAddDevice(Request $request)
	{
		$this->validate($request, [
			'name'  => 'required',
			'token' => 'required'
		]);

		$device = Device::firstOrNew(['token' => $request->token]);
		$device->user_id = Auth::guard('api')->user()->id;
		$device->name = $request->name;
		$device->token = $request->token;
		$device->save();

		return response()->json($device);
	}

}
